<?php
session_start();
if (isSet($_GET['T']))  {
	if ($_GET['T']=="T") {
		$_SESSION['DisplayStyle']="T";
	}
	if ($_GET['T']=="I") {
		$_SESSION['DisplayStyle']="I";
	}
}
    header("Location: /");
?>
