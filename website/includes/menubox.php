<?php
//Placeholder Bits

if ($_SERVER['SCRIPT_NAME']!='/u.php')
{
	if (isset($_SESSION['DisplayStyle'])){
			$DisplayStyle=$_SESSION['DisplayStyle'];
		} else {
			$DisplayStyle="I";
		}
	if($DisplayStyle=="T") {
			?> <a href="display_toggle.php?T=I">(Timeline)</a>&nbsp; <?
		}
		else {
			?> <a href="display_toggle.php?T=T">(Grid)</a>&nbsp; <?
	}
}

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
		<a href="login_page.php">Login</a>
		<a href="register.php">Register</a>
	<?php
	}
?>
