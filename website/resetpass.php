<?php
require_once 'header.inc';
include_once 'library.inc';

	if (isSet($_POST['funct'])) {
		if ($_POST['funct']==="Send E-mail.") {
			$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
			// Check to see if the e-mail account exists, grab a random value while we do that...
		    $dbh=getDBH();
		    $sql="select email, username, rand()*4096 as Seed from users where email=:Email limit 1";
		    if ($stmt = $dbh->prepare($sql) ) {
		        $stmt->bindParam(':Email', $email);
		        $stmt->execute();
		        $foo=$stmt->fetchAll();
		        if (count($foo) == 1) {
		        		$seed=$foo[0]['Seed'];
		        		$username=$foo[0]['username'];
			        $mailhash=hash('sha512', $email.$username."whyYes".$seed."Pounds0fH@mst3rsSoundsGood");

		    $sql="insert into pass_reset values ('$email','$mailhash',now() + interval 2 day) on DUPLICATE KEY UPDATE code='$mailhash', submitted=now()+interval 2 day";
		    if ($stmt = $dbh->prepare($sql) ) {
		        $stmt->execute();
	        	}
                    $subject="Password Reset Request: arttic.us";
                    $to=$email;
                    $message="Password Reset E-mail! Click this! ; https://www.arttic.us/pr.php?h=".$mailhash."  This message will self-destruct, or well, be invalid in 24 hours.";
                    $headers="From: noreply@arttic.us";
                    mail($to,$subject,$message,$headers);

                    $subject="Admin Note: Password Reset Request: $email";
                    $to="rdewalt@gmail.com";
                    $message="Pass Reset Request for $email done.";
                    $headers="From: noreply@arttic.us";
                    mail($to,$subject,$message,$headers);


		        }

		    } else { print '<span style="font-size:200%;>An error has occured.</span>';}


		}
	}

?>

<br clear="all">
<p style="margin: 20px;">Due to the security of the passwords, we do not have a way to recover your password and tell you what it was. All we can do is reset your password to a new one.</p>
<p style="margin: 20px;">This will send you an e-mail with a code to reset your password.  It will be valid for 24 hours.  If you are using the 2 Factor Authentication (2FA/OTP) system, and your password is correct but there is an issue with 2FA/OTP, you will need to <a href="mailto:rdewalt@gmail.com?subject=2fa-issues">[contact the administrator]</a> for assistance.  Use the e-mail account you registered with.  Yes, this is a pain, however given the additional security, this is the fastest <i>sufficiently secure</i> path until a proper automated solution can be implemented.</p>
<div style="margin: 20px;">
        <form action="resetpass.php" method="post">
            Email: <input type="text" name="email" id="email">
            <br><input type="submit" name="funct" value="Send E-mail.">
        </form>
</div>

<?
require_once 'footer.inc';
?>
