<?php
require_once 'header.inc';
include_once 'library.inc';

$Success=false;

	if (isset($_POST))
{
    $hash = filter_input(INPUT_POST, 'prh', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'p', FILTER_SANITIZE_STRING);
	$dbh=getDBH();
	$sql="select email from pass_reset where code=:hash and submitted > now()";
    if ($stmt = $dbh->prepare($sql)) {
	    $stmt->bindParam(':hash', $hash);
	    $stmt->execute();
	    $foo=$stmt->fetchAll();
	    if (count($foo) == 1) {
	    	$email=$foo[0]['email'];
	        $opts['cost']=14; // Dropping the cost to 14 from 16 for now.
	        $password = password_hash($password, PASSWORD_BCRYPT,$opts);
	        $sql = "update users set password=:Password where email=:email limit 1";
		    if ($stmt = $dbh->prepare($sql)) {
				$stmt->bindParam(':email', $email);
				$stmt->bindParam(':Password', $password);
				$stmt->execute();
			    $Success=true;
			}
			$sql="delete from pass_reset where code=:hash limit 1";
		    if ($stmt = $dbh->prepare($sql)) {
		    $stmt->bindParam(':hash', $hash);
		    $stmt->execute();
			    $Success=true;
			}
                    $subject="Password Reset Complete: arttic.us";
                    $to=$email;
                    $message="Your password has been reset.  If you did not request this, please contact the administrator (rdewalt@gmail.com) immediately. ";
                    $headers="From: noreply@arttic.us";
                    mail($to,$subject,$message,$headers);
                    $subject="Admin Note: Password Reset Complete: $email";
                    $to="rdewalt@gmail.com";
                    $message="Pass Reset for $email done.";
                    $headers="From: noreply@arttic.us";
                    mail($to,$subject,$message,$headers);
			    $Success=true;

			}
	    else {
        	$Success=false;
	    }
    }



}

if ($Success)  { print "<br clear='all'><span style='font-size:200%;'>Password reset Success!</span><br><p>Your password has been reset, Thank you. <a href='/login_page.php'>[Click Here to Login]</a>";}
else { print "<br clear='all'><span style='font-size:200%;'>Oops...</span><br><p>Something Broke... Contact the admin (rdewalt@gmail.com) for help.  <a href='/'>[Click Here]</a>";}
?>
<div class="clearbox"></div>
<?php
require_once 'footer.inc';
?>
