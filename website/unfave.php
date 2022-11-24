<?php
include_once ("library.inc");
login_check();
if (isset($_GET['I']) )
{
    $I = filter_input(INPUT_GET, 'I', FILTER_VALIDATE_INT);
			$dbh=getDBH();
	    	$sql="delete from image_faves where UserID=:UserID and ImageID=:ImageID limit 1";
	    if ($stmt = $dbh->prepare($sql)) {
	        $stmt->bindParam(':UserID', $_SESSION['user_id']);
	        $stmt->bindParam(':ImageID', $I);
    	    $foo=$stmt->execute();
		}
}
?>