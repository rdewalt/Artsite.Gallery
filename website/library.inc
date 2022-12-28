<?php

require 'vendor/autoload.php';

use Aws\Iam\IamClient;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;

$SITENAME="YNA: You Need Art";
$SITEHTTPS="https://yna.solfire.com/";
$SITEURL="yna.solfire.com";
global $ICONS; 
$ICONS['filefolder']="<span class=\"fileFolder\">&#128194;</span>";
$ICONS['configure']="<span class=\"setupIcon\">&#9881;</span>";

//sec_session_start();

include_once ("includes/configs.php");
include_once ("includes/db_library.php");
include_once ("includes/login_functions.php");
include_once ("includes/bbcode_basic.php");
include_once ("includes/image.php");
include_once ("includes/comment.php");

session_start();

function esc_url($url) {

    if ('' == $url) {
        return $url;
    }

    $url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);

    $strip = array('%0d', '%0a', '%0D', '%0A');
    $url = (string) $url;

    $count = 1;
    while ($count) {
        $url = str_replace($strip, '', $url, $count);
    }

    $url = str_replace(';//', '://', $url);

    $url = htmlentities($url);

    $url = str_replace('&amp;', '&#038;', $url);
    $url = str_replace("'", '&#039;', $url);

    if ($url[0] !== '/') {
        // We're only interested in relative links from $_SERVER['PHP_SELF']
        return '';
    } else {
        return $url;
    }
}

function toBase($num, $b=62) {
  $base='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $r = $num  % $b ;
  $res = $base[$r];
  $q = floor($num/$b);
  while ($q) {
    $r = $q % $b;
    $q =floor($q/$b);
    $res = $base[$r].$res;
  }
  return $res;
}

function toTen($num, $b=62) {
  $base='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $limit = strlen($num);
  $res=strpos($base,$num[0]);
  for($i=1;$i<$limit;$i++) {
    $res = $b * $res + strpos($base,$num[$i]);
  }
  return $res;
}

function getArtist($UserIdent)
   {
    $dbh=getDBH();
    $sql = "select users.username, users.email, shard,images.Medium from users left outer join UserIcon on users.id=UserIcon.UserID left outer join images on UserIcon.imageid=images.ImageID where users.id = :UserIdent";
    $sth = $dbh->prepare($sql);
    $sth->bindParam(':UserIdent',$UserIdent);
    $sth->execute();
    $foo=$sth->fetch(PDO::FETCH_ASSOC);
    $bar['username']=$foo['username'];
    if (is_null($foo['shard'])) {
    $avatar = "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $foo['email'] ) ) ) . "?d=mm&s=100";
    }
    else
    {
      $avatar="/A/".$foo['shard']."/".$foo['Medium'];
    }
    $bar['UserID']=$UserIdent;
    $bar['Avatar']=$avatar;

   }

function getArtistIcon($UserIdent)
   {
    $dbh=getDBH();
    $sql = "select users.username, users.email, shard,images.Medium from users left outer join UserIcon on users.id=UserIcon.UserID left outer join images on UserIcon.imageid=images.ImageID where users.id = :UserIdent";
    $sth = $dbh->prepare($sql);
    $sth->bindParam(':UserIdent',$UserIdent);
    $sth->execute();
    $foo=$sth->fetch(PDO::FETCH_ASSOC);
    if (is_null($foo['shard'])) {
      $avatar = "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $foo['email'] ) ) ) . "?d=mm&s=100";
    }
    else
    {
      $avatar="/A/".$foo['shard']."/".$foo['Medium'];
    }
    return $avatar;
   }

?>