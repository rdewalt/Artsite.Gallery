<?php
//Placeholder Bits
print "<!-- ".$_SERVER['SCRIPT_NAME'] . " -->";
if ($_SERVER['SCRIPT_NAME']!='/index.php')
{
	if (isset($_SESSION['DisplayStyle'])){
			$DisplayStyle=$_SESSION['DisplayStyle'];
		} else {
			$DisplayStyle="I";
		}
	if($DisplayStyle=="T") {
			?> <a href="display_toggle.php?T=I">(Timeline View)</a>&nbsp; <?
		}
		else {
			?> <a href="display_toggle.php?T=T">(Grid View)</a>&nbsp; <?
	}
}

if (login_check()) {

	if (isset($_SESSION['NSFW'])){
		$NSFW=$_SESSION['NSFW'];
	} else {
		$NSFW="N";
	}
	if($NSFW=="Y") {
		?> <a href="nsfw_toggle.php?N=No&U=<?php echo esc_url($_SERVER['REQUEST_URI']); ?>">(NSFW Shown)</a>&nbsp; <?
	}
	else {
		?> <a href="nsfw_toggle.php?N=Yes&U=<?php echo esc_url($_SERVER['REQUEST_URI']); ?>">(NSFW Hidden)</a>&nbsp; <?
	}
}

if (login_check()) {
		$userName=$_SESSION['username'];
		$userID=$_SESSION['user_id'];
?>
<a href="settings.php">Settings</a>&nbsp;
<a href="upload.php">Upload</a>&nbsp;
<a href="u.php?id=<?=$userID?>"><?=$userName?></a>&nbsp;
<a href="logout.php">Logout</a>

<?php
	}
else {
	?>
		<a href="https://yna-signup.auth.us-west-2.amazoncognito.com/login?response_type=code&client_id=5h9g4gpmipec6gmaiqmk0dcso6&redirect_uri=https%3A%2F%2Fyna.solfire.com%2Fcognito.php">Login / Register</a>
	<?php
	}
?>
