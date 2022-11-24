<?php


$subject="New User Registration: arttic.us";
$to="rdewalt@gmail.com";
$message="New user Registration e-mail";
$headers="From: noreply@arttic.us";
mail($to,$subject,$message,$headers);

print "sent!";
?>
