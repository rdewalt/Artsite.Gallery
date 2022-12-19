<?php



function ShowFolders() {
	global $ICONS; $FolderIcon=$ICONS['filefolder'];
		print "<div class=\"userPageArticle\">Artwork Folders<hr>";
		$dbh=getDBH();
		$PUID=$_GET['id'];
		$sql = "select FolderID, FolderName from ArtFolders where ParentFolder is NULL and UserID = :UserIdent";
		$sth = $dbh->prepare($sql);
		$sth->bindParam(':UserIdent',$PUID);
		$sth->execute();
		$foo=$sth->fetchAll();
		if ( count($foo)==0) { print "<B>No Folders Found!</B>";} 
		foreach ($foo as $Folder) {
?>
<div class="FolderList">
<?=$FolderIcon?><div class="FolderListTitle"><?=$Folder['FolderName']?></div>
</div>

<?
		}
		print "</div>";

}


?>