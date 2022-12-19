<?php
require_once 'header.inc';
include_once 'library.inc';
include_once 'includes/userwall.php';

// TODO: placeholder for user left page bit.
// SO VERY Placeholder.
$LeftItems= array("Last 20");
$RightItems=array("Status Box","Wall");
global $PUID;

function ShowItems($WhichItems) { foreach($WhichItems as $LI) {ShowItem($LI);}}

function ShowItem($Which) { // this is going to get /far/ more in depth.
	switch ($Which) {
	case "Wall" :
		ShowWall();
		break;
	case "Last 20" :
		LastTwenty();
		break;
	default:
		print "<div class=\"userPageArticle\">$Which<hr></div>";
	break;
    }
}

function LastTwenty()
{
global $PUID;
		print "<div class=\"userPageArticle\">LAST 20 Uploads<hr>";
		$dbh=getDBH();
		$sql = "select * from images where State='L' and UserID = :UserIdent and NSFW='N' order by UploadDate desc limit 20";

   		if (isset($_SESSION['NSFW'])) {
	   		if ($_SESSION['NSFW']=='Y')
	   		{
	   		$sql="select * from images where State='L' and UserID = :UserIdent order by UploadDate desc limit 20";
	   		}
   		}


		$sth = $dbh->prepare($sql);
		$sth->bindParam(':UserIdent',$PUID);
		$sth->execute();
		$foo=$sth->fetchAll();
		foreach ($foo as $f)
		{
		?>
		<style>div.userpageimage {
			float: left;
			min-width: 150px;
			min-height: 150px;
			padding: 5px;
			margin: 5px;
			}
			</style>
			<div class="userpageimage">
			<a href="i.php?id=<?=$f['ImageID']?>"><img class="frontpageimage" src="/A/<?=$f['shard']?>/<?=$f['Thumbnail']?>"></a>
			</div>
		<?
		}
		print "</div>";
}

		$PUID=$_GET['id'];
		$dbh=getDBH();
		$sql = "select username from users where id = :UserIdent or username=:UserIdent";
		$sth = $dbh->prepare($sql);
		$sth->bindParam(':UserIdent',$PUID);
		$sth->execute();
		$foo=$sth->fetchAll();
		if ( count($foo)<1 )  { header ("Location: /"); }
		$ArtistAvatar=getArtistIcon($PUID);
		$Username=$foo[0]['username'];



?>

<br clear="All">
<br clear="all">
<style>
td.userpage{
	min-width:450px;
	width: 50%;
	vertical-align: top;
}
</style>
<?

		$dbh=getDBH();
		$sql = "select * from images where UserID=:UserIdent and Category=37 order by UploadDate desc limit 1";
		$sth = $dbh->prepare($sql);
		$sth->bindParam(':UserIdent',$PUID);
		$sth->execute();
		$Art=$sth->fetchAll();
		if ( count($Art)>=1)
		{
		$bkg="/A/".$Art[0]['shard']."/".$Art[0]['Filename'];
		} else
		$bkg="/images/banner.jpg";


?>
<div class="UserHeaderBanner" style="background-image: url('<?=$bkg?>');">
<div class="userPageinfobox">User Page for <?=$Username?></div>
<div class="userPageinfoboxAvatar" style="margin: 10px;"><img src="<?=$ArtistAvatar?>"></div>
</div>
<table width="99%" cellpadding="3" border="0">
<td class="userpage">
		<?php ShowItems($LeftItems);?>
</td>
<td class="userpage">
		<?php ShowItems($RightItems);?>
</td>
</table>
<div class="clearbox"></div>
<?php
require_once 'footer.inc';
?>