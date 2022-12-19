<?php
// This returns back the e-mail address in a json packet for the "ajax" part of the login script.  No other data is returned to the user.
// this way if someone tries to poke at the form, all they'll get is confirmation that the user's account has 2FA enabled.

include_once("includes/db_library.php");
if (isset($_GET['e'])) {
    $email = $_GET['e'];
    $dbh=getDBH();
    $sql="SELECT email FROM users, tfa where users.id=tfa.id and users.email = :UserEmail";
    if ($stmt = $dbh->prepare($sql)) {
        $stmt->bindParam(':UserEmail', $email);
        $stmt->execute();
        $foo=$stmt->fetchAll();
        if (count($foo) == 1) {
                $User = json_encode($foo[0]);
                print $User;
                }
        }
}
?>