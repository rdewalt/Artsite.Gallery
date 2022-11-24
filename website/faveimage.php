<?php
include_once 'library.inc';
login_check();
if ( isset($_GET['I']) )
{
    $I = filter_input(INPUT_GET, 'I', FILTER_VALIDATE_INT);
	$dbh=getDBH();
	    	$sql="insert into image_faves values (:UserID, :ImageID) on DUPLICATE KEY UPDATE ImageID=ImageID";
	    if ($stmt = $dbh->prepare($sql)) {
	        $stmt->bindParam(':UserID', $_SESSION['user_id']);
	        $stmt->bindParam(':ImageID', $I);
    	    $stmt->execute();
		}
}
?>