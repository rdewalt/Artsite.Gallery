<?
include_once ("db_library.php");

function getUser($UserIdent)
	{
		$dbh=getDBH();
		$sql = "select * from user where UserIdent = :UserIdent";
		$sth = $dbh->prepare($sql);
		$sth->bindParam(':UserIdent',$UserIdent);
		$sth->execute();
		$foo=$sth->fetchAll();
		return $foo[0];
	}

function createUser($UID,$Email,$Endpoint)
	{
		$dbh=getDBH();
		$sql= "insert into User values (NULL,:UID,:email,:endpoint,NULL)";
		$sth = $dbh->prepare($sql);
		$sth->bindParam(':UID',$UID);
		$sth->bindParam(':email',$Email);
		$sth->bindParam(':endpoint',$Endpoint);
		$sth->execute();
		return true;
	}

?>