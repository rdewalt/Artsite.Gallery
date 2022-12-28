<?php

require_once 'library.inc';
require_once 'vendor/autoload.php';

use Aws\Iam\IamClient;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;

login_check();

if (isset($_POST['funct'])=='Submit') {
if ($_FILES['Image']['error']==0) {
$Image_name=$_FILES['Image']['name'];
$Extension=explode(".",$Image_name);
$Ext=strtolower($Extension[count($Extension)-1]);
	//   Extensions to add later.
	//   or $Ext=="rtf" or $Ext=="txt" or $Ext=="mp3" or $Ext=="ogg"
if (!($Ext=="png" or $Ext=="gif" or $Ext=="jpg" or $Ext=="jpeg")) {
		header ("Location: /upload.php?e=BadThingImage");
		exit();
}

    $Title = filter_input(INPUT_POST, 'ImageTitle', FILTER_SANITIZE_STRING);
    $Keywords = filter_input(INPUT_POST, 'ImageKeywords', FILTER_SANITIZE_STRING);
    $Description = filter_input(INPUT_POST, 'desc', FILTER_SANITIZE_STRING);
	$Category=$_POST['category']; $Folder=$_POST['Folder'];
	$NSFW=$_POST['Adult'];
	if (isset($_POST['skip'])) {$SkipRecent=$_POST['skip']; }

	$OriginalFilename=$_FILES['Image']['name'];
	$Shard=$_SESSION["folder"];
	$UserID=$_SESSION['user_id'];
	$dbh=getDBH();
	
	list($width, $height, $type, $attr) = getimagesize($_FILES['Image']['tmp_name']);
	$sql="insert into images (ImageID,UserID,Title,FolderID,Category,NSFW,OriginalFilename,State,Shard,height,width,Keywords,Description,UploadDate) values 
	(NULL,:UserID,:Title,:FolderID,:Category,:NSFW,:OriginalFilename,'N',:Shard,:height,:width,:Keywords,:Description,now())";
	// "N" for state is "NEW" (not ready)
    if ($stmt = $dbh->prepare($sql)) {
        $stmt->bindParam(':UserID', $UserID);
        $stmt->bindParam(':Title', $Title);
        $stmt->bindParam(':FolderID', $Folder);
        $stmt->bindParam(':Category', $Category);
        $stmt->bindParam(':NSFW', $NSFW);
        $stmt->bindParam(':Shard', $Shard);
        $stmt->bindParam(':height', $height);
        $stmt->bindParam(':width', $width);
        $stmt->bindParam(':OriginalFilename', $OriginalFilename);
        $stmt->bindParam(':Keywords', $Keywords);
        $stmt->bindParam(':Description', $Description);
        $stmt->execute();
    }

    $ImageRecordID=$dbh->lastInsertId();
    $ShortImage=toBase($ImageRecordID);
    $Filename=$OriginalFilename;
    $TempFile=$_FILES['Image']['tmp_name'];


	$s3Client = new S3Client([
        'profile' => 'default',
        'region' => 'us-west-2',
        'version' => '2006-03-01'
    ]);
    $bucket= "yna-images"; 
    $s3Client ->putObject(array(
        'Bucket' => $bucket,
        'Key'    => $Shard . $ShortImage . "." . $Ext,
        'SourceFile'   => $TempFile,
        'ACL'    => 'public-read'
       ));


//	copy ("$TempFile","$GDir/$Filename");
// 	$ThumbFile="$GDir/$Thumbnail";


if ($SkipRecent=="Y") {$State="Y";} else {$State="L";}
$sql="update images set ShortID=:ShortID, Filename=:Filename, State=:State where ImageID=:ImageID and UserID=:UserID";
    if ($stmt = $dbh->prepare($sql)) {
        $stmt->bindParam(':ImageID', $ImageRecordID);
        $stmt->bindParam(':UserID', $UserID);
        $stmt->bindParam(':ShortID', $ShortImage);
        $stmt->bindParam(':State', $State);
        $stmt->bindParam(':Filename', $Filename);
        $stmt->execute();
    }

// Now, if this was a Category 36 upload (My User Icon)  we set this ID to be our User Icon.
//create table UserIcon (UserID int unsigned, imageid int unsigned, username varchar(30) ) ;

    if ($Category==36) {
	    	$sql="insert into UserIcon values (:UserID,:ImageID,:UserName, now() ) on DUPLICATE KEY UPDATE imageid=:ImageID";
	    if ($stmt = $dbh->prepare($sql)) {
	        $stmt->bindParam(':UserID', $_SESSION['user_id']);
	        $stmt->bindParam(':ImageID', $ImageRecordID);
	        $stmt->bindParam(':UserName', $_SESSION['username']);
    	    $stmt->execute();
	    }
    }

}

}

// If you get down here and nothing has exploded, a winner is you.
		header ("Location: /upload.php?e=Success");
?>
