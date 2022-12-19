<?php
include_once 'library.inc';

$error_msg = "";

if (isset($_POST['username'], $_POST['email'], $_POST['p'])) {
    // Cleanse All The Data!
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_msg .= '<p class="error">The email address you entered is not valid</p>';
    }

    $password = filter_input(INPUT_POST, 'p', FILTER_SANITIZE_STRING);
    if (strlen($password) != 128) {
        $error_msg .= '<p class="error">Invalid password configuration.</p>';
    }
    $dbh=getDBH();
    $sql = "SELECT id FROM users WHERE email = :UserEmail LIMIT 1";
    if ($stmt = $dbh->prepare($sql) ) {
        $stmt->bindParam(':UserEmail', $email);
        $stmt->execute();
        $foo=$stmt->fetchAll();

        if (count($foo) >= 1) {
            $error_msg .= '<p class="error">A user with this email address already exists.</p>';
        }
    } else {
        $error_msg .= '<p class="error">Database error Line 39</p>';
                $stmt->close();
    }

// check existing username
    $sql = "SELECT id FROM users WHERE username = :UserName LIMIT 1";
    if ($stmt = $dbh->prepare($sql)) {
        $stmt->bindParam(':UserName', $username);
        $stmt->execute();
        $foo=$stmt->fetchAll();
                if (count($foo) >= 1) {
                        $error_msg .= '<p class="error">A user with this username already exists</p>';
                }
        } else {
                $error_msg .= '<p class="error">Errors Occured (-1).</p>';
        }

    if (empty($error_msg)) {
        $opts['cost']=14; // Dropping the cost to 14 from 16 for now.
        $password = password_hash($password, PASSWORD_BCRYPT,$opts);
        $mailhash= substr(hash('sha512', $password.$email.$username."I'mAFluffyLittleBunny"),2,32);
        $sql="INSERT INTO users (username, email, role, password) VALUES (:UserName, :UserEmail, 'U', :UserPassword)";
        if ($stmt = $dbh->prepare($sql)) {
                    $stmt->bindParam(':UserName', $username);
                    $stmt->bindParam(':UserEmail', $email);
                    $stmt->bindParam(':UserPassword', $password);
            if (! $stmt->execute()) {
                header('Location: ../error.php?err=RegFAIL!');
            }
        $sql="insert into email_hashes values (:UserEmail,:MailHash)";
        if ($stmt = $dbh->prepare($sql)) {
                    $stmt->bindParam(':UserEmail', $email);
                    $stmt->bindParam(':MailHash', $mailhash);
                    $stmt->execute();
                    $subject="New User Registration: arttic.us";
                    $to=$email;
                    $message="Welcome to Up Dere!  To finish your registration, click on this link to validate your e-mail; https://www.arttic.us/ev.php?h=".$mailhash."  Yeah, I hate these e-mails too, but consider it a necessary evil.";
                    $headers="From: noreply@arttic.us";
                    mail($to,$subject,$message,$headers);
                    $subject="Admin Note: Registration: $username";
                    $to="rdewalt@gmail.com";
                    $message="Registration: $username $email";
                    $headers="From: noreply@arttic.us";
                    mail($to,$subject,$message,$headers);
        }
        }
            header('Location: ./register_success.php');
    }
    else
    {
            header("Location: ../error.php?err=$error_msg");
    }
}
?>
