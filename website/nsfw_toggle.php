<?php
session_start();
if (isSet($_GET['N']))  {
	if ($_GET['N']=="Yes") {
		$_SESSION['NSFW']="Y";
	}
	if ($_GET['N']=="No") {
		$_SESSION['NSFW']="N";
	}
}
    header("Location: ".$_GET['U']);
?>
