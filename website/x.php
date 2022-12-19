<?php
$one='$2y$14$Y2gv36TaQN8YAN5wh0erxecZ1sSHfCZN9CsSehZjl6wz6Zs4fjdKW';
$two='6b97ed68d14eb3f1aa959ce5d49c7dc612e1eb1dafd73b1e705847483fd6a6c809f2ceb4e8df6ff9984c6298ff0285cace6614bf8daa9f0070101b6c89899e22';
$opts['cost']=14; // Dropping the cost to 14 from 16 for now.
$password = password_hash($two, PASSWORD_BCRYPT,$opts);
$three=password_verify($two,$one);
print $one . " |<br>| " . $two . " |<br>| " . $three . "|<br>|". $password . "|<br>";
 
$three=password_verify($two,$password);
if ($three) {
print $one . " |<br>| " . $two . " |<br>| " . $three . "|<br>|". $password . "|<br>";
$five="$2y$14$iyMIhXw.cC.Xus.4J8l1IOR5jWz1UT.YlByg9FTHb32PW/0b07FOi";
}
?>
