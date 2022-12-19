<?php
include_once 'library.inc';
login_check();
if ( isset($_POST['func']) && $_POST['func'] =="Please Wait" && ($_POST['class']=="I" || $_POST['class']=="U") &&  isset($_SESSION['user_id']))
{
    $dbh=getDBH();

    $Comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);
    $ID  = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $Class = filter_input(INPUT_POST, 'class', FILTER_SANITIZE_STRING);
    $funct = filter_input(INPUT_POST, 'func', FILTER_SANITIZE_STRING);

preg_match_all('#:([A-Za-z0-9]+):#',$Comment,$matches);
if (count($matches[1]) > 0 ){
$foo="'".implode($matches[1],"','")."'";
$InnerSQL="select id, username from users where username in ($foo)";
    if ($stmt = $dbh->prepare($InnerSQL)) {
        $stmt->execute();
        $foo=$stmt->fetchAll();
        if (count($foo) >= 1) {
            foreach($foo as $x) {
                $Comment=str_replace(":".$x['username'].":","<a href='u.php?id=".$x['id']."'>".$x['username']."</a>",$Comment);
            }
        }
    }
}

    // This one does the >#<(INT) comment link.
    $Comment=preg_replace('#^(\\>\\#)(\\d+)#', '<a href="#CID$2">$1$2</a>',$Comment);

	$UserID=$_SESSION['user_id'];
	$sql="insert into comments values (NULL,:ID,:Class,now(),:UserID,:WhatSaid)";
    if ($stmt = $dbh->prepare($sql)) {
        $stmt->bindParam(':UserID', $UserID);
        $stmt->bindParam(':ID', $ID);
        $stmt->bindParam(':Class', $Class);
        $stmt->bindParam(':WhatSaid', $Comment);
        $stmt->execute();
    }
    switch ($Class)
    {
    	case "I":
    		header ("Location: /i.php?id=$ID");
    		break;
    	case "U":
    		header ("Location: /u.php?id=$ID");
    		break;
    }

}
else {
header ("Location: /");
}
?>