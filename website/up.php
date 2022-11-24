<?php

include_once 'library.inc';
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
if ($_FILES['Thumbnail']['error']==0) {
	$Thumb_name=$_FILES['Thumbnail']['name'];
	$ThumbExtension=explode(".",$Thumb_name);
	$ThumbExt=strtolower($ThumbExtension[count($ThumbExtension)-1]);
	if (!($ThumbExt=="png" or $ThumbExt=="gif" or $ThumbExt=="jpg" or $ThumbExt=="jpeg")) {
			header ("Location: /upload.php?e=BadThingThumbnail");
			exit();
	}
}

if ($_FILES['Thumbnail']['error']==0) { $ThumbnailYes=true; } else { $ThumbnailYes = false;}

    $Title = filter_input(INPUT_POST, 'ImageTitle', FILTER_SANITIZE_STRING);
    $Keywords = filter_input(INPUT_POST, 'ImageKeywords', FILTER_SANITIZE_STRING);
    $Description = filter_input(INPUT_POST, 'desc', FILTER_SANITIZE_STRING);
	$Category=$_POST['category']; $Folder=$_POST['Folder'];
	$NSFW=$_POST['Adult'];
	if (isset($_POST['skip'])) {$SkipRecent=$_POST['skip']; }
	$OriginalFilename=$_FILES['Image']['name'];
	$Shard=substr(md5($_FILES['Image']['tmp_name'].$_SESSION['login_string']),2,2); // Just needs to be random.
	$GDir="/var/www/html/A/".$Shard;
	@mkdir ($GDir,0755,true);
	$UserID=$_SESSION['user_id'];
	$dbh=getDBH();
	list($width, $height, $type, $attr) = getimagesize($_FILES['Image']['tmp_name']);
	$sql="insert into images (ImageID,UserID,Title,FolderID,Category,NSFW,OriginalFilename,State,Shard,height,width,Keywords,Description,UploadDate) values (NULL,:UserID,:Title,:FolderID,:Category,:NSFW,:OriginalFilename,'N',:Shard,:height,:width,:Keywords,:Description,now())";
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
    $Filename=$ShortImage.".".$Ext;
	if ($ThumbnailYes) {
	    $Thumbnail="T_".$ShortImage.".".$ThumbExt;
	}
	else
	{
	    $Thumbnail="T_".$ShortImage.".".$Ext;
	}
    $TempFile=$_FILES['Image']['tmp_name'];
    copy ("$TempFile","$GDir/$Filename");
    $ThumbFile="$GDir/$Thumbnail";


/// Thumbnail Handling Code Here
if ($ThumbnailYes) { // Has Thumbnail
    $TempThumb=$_FILES['Thumbnail']['tmp_name'];
    copy ("$TempThumb","$ThumbFile");
	}  else
	{  // Has no thumbnail.
		if ( $Ext=="png" or $Ext=="gif" or $Ext=="jpg" or $Ext=="jpeg" ) {
			    copy ("$TempFile","$ThumbFile");
			    $ThumbExt=$Ext;
			   }
	}



if ( $Ext=="png" or $Ext=="gif" or $Ext=="jpg" or $Ext=="jpeg" or $ThumbnailYes ) {

if ($width>450 or $height>150)
{
	// Load Image
		switch ($ThumbExt) {
			case "png" :
			$img=imagecreatefrompng($ThumbFile);
			break;
			case "gif" :
			$img=imagecreatefromgif($ThumbFile);
			break;
			case "jpeg":
			case "jpg":
			$img=imagecreatefromjpeg($ThumbFile);
			break;
		}
//	RESCALE to 150x150;
		$img3=imagescale($img,450,-1);
		$img2=imagecrop($img3, ['x' => 0, 'y' => 0, 'width' => 450, 'height' => 200]);
	// Now Save it back out;
		switch ($ThumbExt) {
			case "png" :
			imagepng($img2, $ThumbFile);
			break;
			case "gif" :
			imagegif($img2, $ThumbFile);
			break;
			case "jpeg":
			case "jpg":
			imagejpeg($img2, $ThumbFile);
			break;
		}
	}
	$MediumFile=false;
	// Okay, if the image is greater than 800 wide, we make a "medium" version intermediary.
	if ($width>800)  {
		$MediumFile=true; $MediumFilename="m_$Filename";
		// Load Image
			switch ($Ext) {
				case "png" :
				$img=imagecreatefrompng("$GDir/$Filename");
				break;
				case "gif" :
				$img=imagecreatefromgif("$GDir/$Filename");
				break;
				case "jpeg":
				case "jpg":
				$img=imagecreatefromjpeg("$GDir/$Filename");
				break;
			}
//	RESCALE to 800 wide, store with m_$filename.

			$img2=imagescale($img,800,-1);
		// Now Save it back out;
			switch ($Ext) {
				case "png" :
				imagepng($img2, "$GDir/$MediumFilename");
				break;
				case "gif" :
				imagegif($img2, "$GDir/$MediumFilename");
				break;
				case "jpeg":
				case "jpg":
				imagejpeg($img2, "$GDir/$MediumFilename");
				break;
			}

	}  else
	// If the original is less than 800 wide, we don't need a "medium" state.
	{ $MediumFilename=$Filename; }
}

// And now we need to update the database entry with the right filename things.
// And set the image "Live"
		if (isSet($SkipRecent)){
	        if ($SkipRecent=="Y") {
	        	$State="S";
	        } else
	        {
	        	$State="L";
	    	}
    	} else 	{
	        	$State="L";
	    }


$sql="update images set ShortID=:ShortID, Filename=:Filename, State=:State, Medium=:Medium, Thumbnail=:Thumbnail where ImageID=:ImageID and UserID=:UserID";
    if ($stmt = $dbh->prepare($sql)) {
        $stmt->bindParam(':ImageID', $ImageRecordID);
        $stmt->bindParam(':UserID', $UserID);
        $stmt->bindParam(':ShortID', $ShortImage);
        $stmt->bindParam(':State', $State);
        $stmt->bindParam(':Filename', $Filename);
        $stmt->bindParam(':Medium', $MediumFilename);
        $stmt->bindParam(':Thumbnail', $Thumbnail);
        $stmt->execute();
    }

// Now, if this was a Category 36 upload (My User Icon)  we set this ID to be our User Icon.

//create table UserIcon (UserID int unsigned, imageid int unsigned, username varchar(30) ) ;

    if ($Category==36) {
	    	$sql="insert into UserIcon values (:UserID,:ImageID,:UserName, now() ) on DUPLICATE KEY UPDATE imageid=:ImageID";
	    if ($stmt = $dbh->prepare($sql)) {
	        $stmt->bindParam(':UserID', $UserID);
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
