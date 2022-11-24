<?php
function getDBH() 
{
	try {  $dbh = new PDO('mysql:host=localhost;dbname=yart', 'root', 'DevDatabasePassword');
	    } catch (PDOException $e) {
	        print "Error!: " . $e->getMessage() . "<br/>"; die();
		}
	return $dbh;
}

/*
	Update "Image Views" count.
*/
		$dbh=getDBH();
		$sql = "select ImageID as I, count(ImageID) as C from image_views group by ImageID";
		$sth = $dbh->prepare($sql);
		$sth->execute();
		$foo=$sth->fetchAll();
		foreach($foo as $f)
			{
		$sql = "update images set ViewCount=ViewCount + ".$f['C']." where ImageID = ".$f['I'];
		$sth = $dbh->prepare($sql);
		$sth->execute();
			}

		$sql = "truncate image_views";
		$sth = $dbh->prepare($sql);
		$sth->execute();

/*
	Update "Favorite Views" count.
*/
		$sql = "select ImageID as I ,count(ImageID) as C from image_faves group by ImageID";
		$sth = $dbh->prepare($sql);
		$sth->execute();
		$foo=$sth->fetchAll();
		foreach($foo as $f)
			{
		$sql = "update images set FaveCount=".$f['C']." where ImageID = ".$f['I'];
		$sth = $dbh->prepare($sql);
		$sth->execute();
			}

?>