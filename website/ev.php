<?php
require_once 'header.inc';
include_once 'library.inc';

$Success=false;
if ( isset($_GET['h']) )  {
    $hash = filter_input(INPUT_GET, 'h', FILTER_SANITIZE_STRING);
	$dbh=getDBH();
	$sql="select email from email_hashes where code=:hash limit 1";
    if ($stmt = $dbh->prepare($sql)) {
	    $stmt->bindParam(':hash', $hash);
	    $stmt->execute();
	    $foo=$stmt->fetchAll();
	    if (count($foo) == 1) {
	    	$email=$foo[0]['email'];
			$sql="update users set role='C' where email=:email and role='U'";
		    if ($stmt = $dbh->prepare($sql)) {
		    $stmt->bindParam(':email', $email);
		    $stmt->execute();
	    	$email=$foo[0]['email'];
			}
			$sql="delete from email_hashes where code=:hash limit 1";
		    if ($stmt = $dbh->prepare($sql)) {
		    $stmt->bindParam(':hash', $hash);
		    $stmt->execute();
		    $Success=true;
			}
		}
	    else {
        	$Success=false;
	    }
    }
}
if ($Success)  { print "<br clear='all'><span style='font-size:200%;'>Success!</span><br><p>Your e-mail is verified, Thank you. <a href='/'>[Click Here]</a>";}
else { print "<br clear='all'><span style='font-size:200%;'>Oops...</span><br><p>Something Broke... The code may have expired, or you have already verified this account. <a href='/'>[Click Here]</a>";}
?>
<div class="clearbox"></div>
<?php
require_once 'footer.inc';
?>