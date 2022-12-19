<?php
include_once 'library.inc';
require_once 'rfc6238.php';

if (isset($_POST['email'], $_POST['p'])) {
    $email = $_POST['email'];
    if (isset($_POST['RememberMe'])) {
        $RememberMe=$_POST['RememberMe'];
        if ($RememberMe!="Y") { $RememberMe="N";}
    }  else
    {$RememberMe="N";}
    $password = $_POST['p']; // The hashed password.
    if (isset($_POST['OTP'])) {
    $OTP=$_POST['OTP'];
    $secret=otpKeyGrab($email);
    $OTPEntered=true;
    }
    $dbh=getDBH();
    $sql="SELECT email FROM users, tfa where users.id=tfa.id and users.email = :UserEmail";
    if ($stmt = $dbh->prepare($sql)) {
        $stmt->bindParam(':UserEmail', $email);
        $stmt->execute();
        $foo=$stmt->fetchAll();
        if (count($foo) == 1) {
                // user HAS 2FA.
                if($OTPEntered){
                    if (TokenAuth6238::verify($secret,$OTP))
                        { $TWO=true;} else {header('Location: /login_page.php?error=1'); exit; }  // 2FA Code failed.
                    } else { header('Location: /login_page.php?error=1'); exit; } // Needs 2FA, Did not enter code.

                }
        } // if User falls through here, then the user does not have 2FA enabled, OR they've already passed 2FA check.
    if (userLogin($email, $password, $RememberMe) == true) {
        // Login success
        header('Location: /'); exit;
    } else {
        // Login failed
        header('Location: /login_page.php?error=1'); exit;
    }
} else {
    // The correct POST variables were not sent to this page.
    echo 'Invalid Request';
}
?>