<?php

function ShowWall() {
	$PUID=$_GET['id'];
	global $ICONS; $FolderIcon=$ICONS['filefolder'];
	print "<div class=\"userPageWall\"><center>Shout Wall Thing</center><hr style='height:1px;background-color:black;'>";
	$UID=$_GET['id'];
	$foo=GetComments($UID,'U','20');
        $tags = str_replace("|",", ",'b|i|u|size|color|center|quote|url');

if (isset( $_SESSION['loggedin'] ) ) {?><script>
function RepComment($ID)
{
	document.getElementById('CommentBox').value=">#"+$ID + "\n\n";
	document.getElementById('CommentBox').focus();
}
</script><?}

foreach($foo as $f)
{
		if (is_null($f['shard'])) {
		$avatar = "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $f['email'] ) ) ) . "?d=mm&s=100";
		}
		else
		{
			$avatar="/A/".$f['shard']."/".$f['Medium'];
		}

?><a name="CID<?=$f['CommentID']?>">
<a href="u.php?id=<?=$f['WhoSaid']?>"><img style="align:left; float:left; padding: 0px;" src="<?=$avatar?>" alt="<?=$f['username']?>""></a>
&nbsp;<?=$f['username']?> - <?=$f['WhenSaid']?> | CID:<?=$f['CommentID']?><span onClick="RepComment(<?=$f['CommentID']?>);">(Reply)</span><hr style='height:1px;background-color:black;'>
<span style="margin: 10px;"><?=bb_parse($f['WhatSaid'])?></span>
<br clear="all"><hr style='height:1px;background-color:black;'>
<?
}
 		print "</div><br>";

if (isset( $_SESSION['loggedin'] ) ) {?>
<a name="userPageWall"></a>	<div class="commentbox">
<center><span style="font-size:100%;">Leave a Shout on the Wall</span></center><hr>
<span style="padding:5px;"><b>BBCODE tags available:</b> <?=$tags?></span><br>
<script>
function GoForm()
{
	if (document.getElementById("submitbuton").value=="Post To Wall"){ document.getElementById("submitbuton").value="Please Wait"; return true; } else { return false; }
}
</script>
<form method="post" action="postcomment.php" onSubmit="return GoForm();">
<textarea name="comment" cols="80" rows="10" id="CommentBox" wrap="hard"></textarea>
<br>
<input type="hidden" name="id" value="<?=$PUID?>">
<input type="hidden" name="class" value="U">
<input type="submit" id="submitbuton" name="func" value="Post To Wall">
</form>
	</div>
<? } else {?>
	<div class="commentbox">
		<center><span style="font-size:150%;">You must be logged in to leave a Comment</span></center>
	</div>
<?}
}
?>
