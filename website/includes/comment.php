<?php
// Comment Specific Library


// Default to Image Comments, and No Limit.
function GetComments($WhichID,$WhichType = 'I',$Limit = 0)
{
    $dbh=getDBH();
	$sql="select comments.*, users.username, users.email, shard,images.Medium from comments join users on comments.WhoSaid = users.id left outer join UserIcon on users.id=UserIcon.UserID left outer join images on UserIcon.imageid=images.ImageID where comments.WhichID=:ImageID and WhichType=:ImageType";
	if ($Limit > 0) {
		$sql .=" limit $Limit";
	}
    $sth = $dbh->prepare($sql);
    $sth->bindParam(':ImageID',$WhichID);
    $sth->bindParam(':ImageType',$WhichType);
    $sth->execute();
    $foo=$sth->fetchAll();
    return $foo;
}

