<?php
require_once 'header.inc';
include_once 'library.inc';
        $tags = str_replace("|",", ",'b|i|u|size|color|center|quote|url'); 

$img=new image();

if (isset($_GET['id']))
	{
		$PUID=$_GET['id'];
		$img->Load($PUID);
	}
	elseif (isset($_GET['sid']))
	{
		$PUID=$_GET['sid'];
		$img->ShortLoad($PUID);

	}
	else { header("Location: /");}
if ($img->data==false) { header("Location: /");}
$img->AddView();

if (isset( $_SESSION['loggedin'] ) ) {
	$dbh=getDBH();
    $sql = "SELECT * from image_faves WHERE UserID = :UserID and ImageID=:ImageID";
    if ($stmt = $dbh->prepare($sql)) {
        $stmt->bindParam(':UserID', $_SESSION['user_id']);
        $stmt->bindParam(':ImageID', $PUID );
        $stmt->execute();
        $foo=$stmt->fetchAll();
        if (count($foo) == 1) {
                	$IFavedThis=true;
		}
        else {
                	$IFavedThis=false;
        }
    }
}
?>
<script>
image=1;

function SwapImage()
{
	if (image==1)
	{
		document.getElementById('MainImage').src="<?=$img->FullImage?>";
		image=0;
	}
	else
	{
		document.getElementById('MainImage').src="<?=$img->Image?>";
		image=1;
	}
}

<?php if (isset( $_SESSION['loggedin'] ) && $IFavedThis==false ) {?>
function faveImage()
{ 
    xmlhttp=new XMLHttpRequest();
    xmlhttp.open("GET","faveimage.php?I=<?=$PUID?>",true);
    xmlhttp.send();
    document.getElementById("faveImage").innerHTML="Favorited! |";
}
<?}?>

<?php if (isset( $_SESSION['loggedin'] ) && $IFavedThis==true ) {?>
function UnFave()
{ 
    xmlhttp=new XMLHttpRequest();
    xmlhttp.open("GET","unfave.php?I=<?=$PUID?>",true);
    xmlhttp.send();
    document.getElementById("faveImage").innerHTML="(Removed!) |";
}
<?}?>
<?php if (isset( $_SESSION['loggedin'] ) ) {?>
function RepComment($ID)
{
	document.getElementById('CommentBox').value=">#"+$ID + "\n\n";
	document.getElementById('CommentBox').focus();	
}
<?}?>
</script>

<br>
<div class="imagemain">
<div class="imageboxTitle">
 <?=$img->Title?> by <?=$img->Artist?> - Uploaded: <?=$img->UploadDate?>
</div>
<div class="imageitself">
<center><!-- fuck you CSS.  Be that way and I'm using a center tag. -->
<img src="<?=$img->Image?>" id="MainImage" onClick="SwapImage();" alt="<?=$img->Title?> by <?=$img->Artist?> (Click To Toggle Size)"><br>
<?php if (isset( $_SESSION['loggedin'] ) && $IFavedThis==false ) {
?> <span id="faveImage"?>
<a onClick="faveImage();">Favorite Image</a> | 
</span>
<?
}
elseif (isset( $_SESSION['loggedin'] ) )
{
?> <span id="faveImage"?>
In Favorites! <a onClick="UnFave();" onMouseOver="this.innerHTML='(Remove)';" onMouseOut="this.innerHTML='(X)';">(X)</a> | 
</span>
<?
}
?>
<a onClick="SwapImage();">Toggle View</a> | 
<a href="https://arttic.us/<?=$img->ShortID?>" onMouseOver="this.innerHTML='https://arttic.us/<?=$img->ShortID?>';" onMouseOut="this.innerHTML='Quick Link';">Quick Link</a> | 
<a href="<?=$img->FullImage?>" onMouseOver="this.innerHTML='https://arttic.us/<?=$img->FullImage?>';" onMouseOut="this.innerHTML='Direct Link';">Direct Link</a><br><br>
</center>
</div>
<div class="postimage">
<div class="imagedetails">

</div>
 <?=$img->Title?>
 <hr>
<div class="imagepageArtistAvatarBox">
<a href="/u.php?id=<?=$img->ArtistID?>"><img src="<?=$img->ArtistAvatar?>" alt="<?=$img->Artist?>"></a>
<hr>
<ul>Details:<br>
<li>W x H: <?=$img->Dimensions?></li>
<li>Views: <?=$img->Views?></li>
<li>Faves: <?=$img->Faves?></li>
<li><span onMouseOver="this.innerHTML='Keywords: <br><?=preg_replace("/, /",',<br>',preg_replace("/[^A-Za-z0-9, ]/", '', $img->Keywords));?>';">Keywords: (*)</span></li>
</ul>
</div>
<?=$img->Description?>
</div>
<div class="clearbox"></div>
<br>

<div class="commentmaster">
Image Comments:<?if (isset( $_SESSION['loggedin'] ) ) {?> - <a href="#CommentBox">Post a Comment</a><?}?><hr>
<?
$foo=GetComments($PUID);
foreach($foo as $f)
{
		if (is_null($f['shard'])) {
		$avatar = "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $f['email'] ) ) ) . "?d=mm&s=100";
		}
		else
		{
			$avatar="/A/".$f['shard']."/".$f['Medium'];
		}

?><a name="CID<?=$f['CommentID']?>"></a>
<a href="u.php?id=<?=$f['WhoSaid']?>"><img style="align:left; float:left; padding: 0px;" src="<?=$avatar?>" alt="<?=$f['username']?>""></a>
&nbsp;<?=$f['username']?> - <?=$f['WhenSaid']?> | CID:<?=$f['CommentID']?><?if (isset( $_SESSION['loggedin'] ) ) {?><span onClick="RepComment(<?=$f['CommentID']?>);">(Reply)</span><?}?><hr>
<span style="padding: 10px;"><?=bb_parse($f['WhatSaid'])?></span>
<br clear="all"><br><hr>
<?
}
?>
</div>
<br>
<?if (isset( $_SESSION['loggedin'] ) ) {?>
<a name="CommentBox"></a>	<div class="commentbox">
<center><span style="font-size:150%;">Leave a Comment</span></center><hr>
<span style="padding:5px;"><b>BBCODE tags available:</b> <?=$tags?></span><br>
<script>
function GoForm()
{
	if (document.getElementById("submitbuton").value=="Submit Comment"){ document.getElementById("submitbuton").value="Please Wait"; return true; } else { return false; }
}
</script>
<form method="post" action="postcomment.php" onSubmit="return GoForm();">
<textarea name="comment" cols="100" rows="10" id="CommentBox" wrap="hard"></textarea>
<br>
<input type="hidden" name="id" value="<?=$PUID?>">
<input type="hidden" name="class" value="I">
<input type="submit" id="submitbuton" name="func" value="Submit Comment">
</form>
	</div>
<? } else {?>
	<div class="commentbox">
		<center><span style="font-size:150%;">You must be logged in to leave a Comment</span></center>
	</div>
<?}?>


</div>
<div class="clearbox"></div>
<?php
require_once 'footer.inc';
?>
