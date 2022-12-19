<?php
// Image specific Library
class image
{

   function __construct() {
   }

   function __destruct() {
   }

   function Load($ID)
   {
   		$dbh=getDBH();
		$sql = "select * from images where ImageID = :ID limit 1";
		$sth = $dbh->prepare($sql);
		$sth->bindParam(':ID',$ID);
		$sth->execute();
		$this->data=$sth->fetch(PDO::FETCH_ASSOC);
		$this->buildvars();
   }

   function ShortLoad($ID)
   {
   		$dbh=getDBH();
		$sql = "select * from images where ShortID = :ID limit 1";
		$sth = $dbh->prepare($sql);
		$sth->bindParam(':ID',$ID);
		$sth->execute();
		$this->data=$sth->fetch(PDO::FETCH_ASSOC);
		$this->buildvars();

   }

   function getArtist($UserIdent)
   {
		$dbh=getDBH();
		$sql = "select users.username, users.email, shard,images.Medium from users left outer join UserIcon on users.id=UserIcon.UserID left outer join images on UserIcon.imageid=images.ImageID where users.id = :UserIdent";
		$sth = $dbh->prepare($sql);
		$sth->bindParam(':UserIdent',$UserIdent);
		$sth->execute();
		$foo=$sth->fetch(PDO::FETCH_ASSOC);
		$this->Artist=$foo['username'];
		if (is_null($foo['shard'])) {
		$avatar = "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $foo['email'] ) ) ) . "?d=mm&s=100";
		}
		else
		{
			$avatar="/A/".$foo['shard']."/".$foo['Medium'];
		}
		$this->ArtistID=$UserIdent;
		$this->ArtistAvatar=$avatar;
   }


   function buildvars()
   {
		$this->ID=$this->data['ImageID']; 
		$this->ShortID=$this->data['ShortID']; 
		// URLs to the image
		$this->Image="/A/".$this->data['shard']."/".$this->data['Medium'];
		$this->FullImage="/A/".$this->data['shard']."/".$this->data['Filename'];
		$this->Thumbnail="/A/".$this->data['shard']."/".$this->data['Thumbnail'];
		$this->Description=bb_parse($this->data['Description']);
		$this->Title=$this->data['Title'];
		$this->Keywords=$this->data['Keywords'];
		$this->Views=$this->data['ViewCount'];
		$this->Faves=$this->data['FaveCount'];
		$this->UploadDate=$this->data['UploadDate'];
		$this->Dimensions=$this->data['width'] . "x" . $this->data['height'];
		$this->getArtist($this->data['UserID']);
   }


   function AddView()
   {
		$dbh=getDBH();
		$sql = "insert DELAYED into image_views values (:ID)";
		$sth = $dbh->prepare($sql);
		$sth->bindParam(':ID',$this->ID);
		$sth->execute();
   }

}

?>