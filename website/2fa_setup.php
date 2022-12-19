<?php
require_once 'header.inc';
include_once 'library.inc';
require_once 'rfc6238.php';

function otpKeyGen()
{   // moved this here, rather than a library, this is only needed in this page.
    $user_id = $_SESSION['user_id'];
    $dbh=getDBH();
    $sql="SELECT password FROM users WHERE id = :UserID";
    if ($stmt = $dbh->prepare($sql)) {
        $stmt->bindParam(':UserID', $user_id);
        $stmt->execute();
        $foo=$stmt->fetchAll();
        if (count($foo) == 1) {
                $UserPassword = $foo[0]['password'];
                return base32static::encode((substr(sha1($UserPassword),2,16)));
                }
        }

}

if (login_check() != true) {
    header ("Location: login_page.php");
}

	$UserID=$_SESSION['user_id'];
    $dbh=getDBH();
    $sql="SELECT ss FROM tfa WHERE tfa.id = :UserID";
    if ($stmt = $dbh->prepare($sql)) {
        $stmt->bindParam(':UserID', $UserID);
        $stmt->execute();
        $foo=$stmt->fetchAll();
        if (count($foo) == 1) {
        		// You ALREADY have 2FA.
print "<br clear=all><h2>Your account is ALREADY set up for Two Factor Authentication. You cannot re-generate this a second time. In case of problems, contact the Administrators.</h2><br><a href=\"/\">Return to the Main Page</a>";
include_once "footer.inc";
exit();
                }
        }


$secretkey=otpKeyGen();
$username=$_SESSION['username'];
$showagain=true;
if (isset($_POST['funct']) && isset($_POST['AuthCode'])) {
	if ($_POST['funct']==="Verify Code" ) {

		if (isset($_POST['AuthCode'])) {
			$currentcode=$_POST['AuthCode'];
			if (TokenAuth6238::verify($secretkey,$currentcode))
				{  // The code worked..  Store the key for good, mark them as 2FA Ready.
					echo "Code is valid\n";
					$user_id = $_SESSION['user_id'];
					$showagain=false;
					$dbh=getDBH();
					$sql="insert into tfa values(:UserID,:Secret)";
				    if ($stmt = $dbh->prepare($sql)) {
				        $stmt->bindParam(':UserID', $user_id);
				        $stmt->bindParam(':Secret', $secretkey);
				        $stmt->execute();
				    }
				    print "<br clear=all>Your account is set up for Two Factor Authentication.  You will need to enter a code upon every login.  In case of problems, contact the Administrators.";
				}
				else
				{
					echo "Invalid code\n";
				}

		}

	}
}

if ( $showagain )  {

print "<div style='margin:10px;padding:10px'><br clear=\"all\">Scan this into Google Authenticator (<a href=\"https://itunes.apple.com/us/app/google-authenticator/id388497605\" target=\"_blank\">iOS</a> <a href=\"https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2\" target=\"_blank\">Android</a>), or other RFC6238 compatible OTP Authenticator program.<br>";

print sprintf('<img src="%s"/>',TokenAuth6238::getBarCodeUrl($username,$SITEURL,$secretkey));
?>
<br clear="all">Once you have the program configured and generating keys, wait until a new one is generated. (they expire every 30 seconds) Enter it below before pressing submit.  IF all is well and the code works, your account will be set up for Two Factor Authentication.  Once this is complete, the code will <b>never</b> be shown again.  If you need to reset or disable Two Factor Authentication, due to security, you'll have to contact staff.  (That, and I haven't written that component yet.)
<div style="margin: 10px; padding: 10px;">OTP Code:<br>
	<form method="post" action="<?php echo esc_url($_SERVER['PHP_SELF']); ?>">
	<input type="text" name="AuthCode" size="10" maxlength="6"><br><input type="submit" name="funct" value="Verify Code">
	</form>
</div></div>


<?php
print $secretkey;

}

?>

