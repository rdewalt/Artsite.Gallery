 <?php
$frontpage=true;
require_once 'header.inc';
?><br clear="all">
<h2>Amazingly Useful Yet Pre-Alpha Website.</h2>

<p>If you can read this, its because I askedyou to check things out for me.</p>

<p>Yes, there is a horridly huge amount of things to do still.</p>
<hr>
<div class="outercontainer">
<div class="frontpageimages">
<?php

if (true) {  
	
// Eventually this will be the 'my feed' / Recent / Recommend list.

// Front page "Last x images" by default if not logged in
// Otherwise show Last 50 with Blocklist/Category Filters.
   		$dbh=getDBH();
   		$sql="select users.username as username, ImageID, Title, shard, Filename, Description, UploadDate from images,users where UserID=users.id and State='L' and NSFW='N' order by UploadDate desc limit 50";
   		if (isset($_SESSION['NSFW'])) {
	   		if ($_SESSION['NSFW']=='Y')
	   		{
	   		$sql="select users.username as username, ImageID, Title, shard, Filename, Description, UploadDate from images,users where UserID=users.id and State='L' order by UploadDate desc limit 50";
	   		}
   		}
		$sth=$dbh->prepare($sql);
		$sth->execute();
		$imgs=$sth->fetchAll();
}

if (isset($_SESSION['DisplayStyle'])){
		$DisplayStyle=$_SESSION['DisplayStyle'];
	} else {$DisplayStyle="I";}

// Twitter Style
	if ($DisplayStyle=="T") {
		foreach ($imgs as $i) {
?>
 <div class="frontpageimageTimeline" id="<?=$i['ImageID']?>">
<div class="ImageBlock"><a href="i.php?id=<?=$i['ImageID']?>"><img class="frontpageimageTimeline" src="https://cdn2.yna.solfire.com/<?=$i['shard']?>/<?=$i['Filename']?>"></a></div>
<div class="ImageHeader"><span class="ArtistName"><?=$i['username']?></span> - <span class="ImageTitle"><?=$i['Title']?></span> - <span class="ImageUploadDate"><?=$i['UploadDate']?></span><br></div>
<div class="ImageDescriptionDiv"><span class="ImageDescriptionText"><?=$i['Description']?></span></div>
 </div><hr>
<?
		}
}
// Instagram Style
else {
		foreach ($imgs as $i) {
?>
 <div class="frontpageimageGrid" id="<?=$i['ImageID']?>">
<a href="i.php?id=<?=$i['ImageID']?>"><img class="frontpageimageGrid" src="/A/<?=$i['shard']?>/<?=$i['Filename']?>"></a>
 </div>
<?
		}
}

?>
</div></div>
<hr>
<?
require_once 'footer.inc';
?>
