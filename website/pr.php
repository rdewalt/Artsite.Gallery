<?php
require_once 'header.inc';
include_once 'library.inc';

$Success=false;
if ( isset($_GET['h']) )  {
    $hash = filter_input(INPUT_GET, 'h', FILTER_SANITIZE_STRING);
	$dbh=getDBH();
	$sql="select email from pass_reset where code=:hash and submitted > now()";
    if ($stmt = $dbh->prepare($sql)) {
	    $stmt->bindParam(':hash', $hash);
	    $stmt->execute();
	    $foo=$stmt->fetchAll();
	    if (count($foo) == 1) {
	    	$email=$foo[0]['email'];
		    $Success=true;
			}
	    else {
        	$Success=false;
	    }
    }
}
if ($Success)  { ?>

        <script type="text/JavaScript" src="js/sha512.js"></script>
        <script type="text/JavaScript" src="js/forms.js"></script>

<br clear='all'><span style='font-size:200%;margin: 20px;'>Reset your Password:</span><div style="margin:20px;">
        <form action="pwr.php" method="post" name="reset_form" >
        <br><input type="hidden" name="prh" value="<?=$hash?>">
		<br>Password: <input type="password" name="password" id="password"/>
		<br>Confirm Password: <input type="password" name="Confpassword" id="Confpassword" onChange="pwcheck(this.form, this.form.password, this.form.Confpassword);">
            <br>
            <input type="button" value="Reset Password" onclick="this.form.Confpassword.value='hunter2';formhash(this.form, this.form.password);"/>
        </form>
</div>
<?}
else { print "<br clear='all'><span style='font-size:200%;'>Oops...</span><br><p>Something Broke... The code may have expired, or you have already used it. <a href='/resetpass.php'>[Click Here for a new code.]</a>";}
?>
<div class="clearbox"></div>
<?php
require_once 'footer.inc';
?>