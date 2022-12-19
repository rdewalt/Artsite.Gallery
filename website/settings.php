<?php
require_once 'header.inc';
include_once 'library.inc';
?>

<style> li { margin: 5px; } </style>

<h2>Basic Account Settings Piece.</h2>
<UL style="margin:30px;"><b>Items that go here:</b>

<?

	$UserID=$_SESSION['user_id'];
    $dbh=getDBH();
    $sql="SELECT ss FROM tfa WHERE tfa.id = :UserID";
    if ($stmt = $dbh->prepare($sql)) {
        $stmt->bindParam(':UserID', $UserID);
        $stmt->execute();
        $foo=$stmt->fetchAll();
        if (count($foo) == 1) {
?>
	<li>2-Factor Authentication is already enabled for your account.</li>
<?
                }
else
	{
	?>
		<li><a href="2fa_setup.php">[Enable Two Factor Authentication]</a> - Secure your account further than normal.  Needs a OTP Applet (i.e. Google Authenticator)</li>
	<?
	}
} else
	{
	?>
		<li><a href="2fa_setup.php">[Enable Two Factor Authentication]</a> - Secure your account further than normal.  Needs a OTP Applet (i.e. Google Authenticator)</li>
	<?
	}
	?>
<li><a href="resetpass.php">[Reset/Change Your Password]</a> - Yes, You are using the 'reset password' system, but this adds a layer of security to the system by also validating through your e-mail too.</li>
<li>Uploaded Image Edit</li>
<li>User Profile Edit</li>
<li>Show/Block Categories</li>
<li>Image Folder Edit</li>
<li>Journal Management</li>
<li>Ban|Block Management</li>
</UL>
<?php
include_once 'footer.inc';
?>