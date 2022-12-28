<?php

/*
// Cognito replaced the need for these.
function userLogin($email, $password, $RememberMe) {
	$dbh=getDBH();
	$sql="SELECT id, username, password FROM users WHERE email = :UserEmail";
    if ($stmt = $dbh->prepare($sql)) {
        $stmt->bindParam(':UserEmail', $email);
        $stmt->execute();
        $foo=$stmt->fetchAll();
        if (count($foo) == 1) {
                $UserPassword = $foo[0]['password']; // The Hashed One.
                $UserName=$foo[0]['username'];
                $UserID=$foo[0]['id'];
            if (checkbrute($UserID) == true) {
                // Account is locked
                return false; // YOU SHAL NOT PASS!
            } else {
                if (password_verify($password, $UserPassword)) {  // The Unhashed One vs The Hashed One.
                    $user_browser = $_SERVER['HTTP_USER_AGENT'];
                    $UserID = preg_replace("/[^0-9]+/", "", $UserID);
                    $_SESSION['user_id'] = $UserID;
                    $UserName = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $UserName);
                    $_SESSION['username'] = $UserName;
                    // use /STORED/ versus /ENTERED/ since they are not "the same" in this instance for the case below.
                    $_SESSION['login_string'] = hash('sha512', $UserPassword . $user_browser);
                    // Login successful.
                    if ($RememberMe=="Y") {
                        setcookie("user_id",$UserID,strtotime( '+365 days' ),'/');
                        setcookie("username",$UserName,strtotime( '+365 days' ),'/'   );
                        setcookie("fridgeCode",$_SESSION['login_string'],strtotime( '+365 days' ),'/');
                    }
                    return true;
                } else {
                    // Password is not correct
                    $now = time();
                    $sql= "INSERT INTO login_attempts(user_id, whenfailed ) VALUES (:UserID, :Now)";
                    $stmt = $dbh->prepare($sql);
                    $stmt->bindParam(':UserID', $UserID);
                    $stmt->bindParam(':Now', $now );
			        $stmt->execute();
                    return false;
                }
            }
        } else {
            // No user exists.
            return false;
        }
    }
}
function checkbrute($user_id) {
	$MAX_FAILED_LOGINS = 5; // Past this, your account is locked for a bit.
    $now = time();
    $valid_attempts = $now - (2 * 60 * 60); // Two hours ago...
	$dbh=getDBH();
	$sql="SELECT whenfailed FROM login_attempts WHERE user_id = :UserID AND time > '$valid_attempts'";
	    $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':UserID', $user_id);
        $stmt->execute();
        $foo=$stmt->fetchAll();
 		$RowCount=count($foo);
        if ($RowCount > $MAX_FAILED_LOGINS) {  // Set in configs.php
            return true; // YES, there have been too many failed login attempts.
        } else {
            return false; // NO, there have not.
        }
	}
*/


function login_check() {
    if (isset($_SESSION["I"],$_SESSION["A"],$_SESSION["R"],$_SESSION["U"]))
    {
        $_SESSION['loggedin']=true;
        return true;
            } else {
        return false;
}
}
?>