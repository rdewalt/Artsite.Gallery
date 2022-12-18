<?php
function getDBH() 
{
	try {  $dbh = new PDO('mysql:host=db.internal.solfire.com;dbname=yna', 'yna', '86753091024');
	    } catch (PDOException $e) {
	        print "Error!: " . $e->getMessage() . "<br/>"; die();
		}
	return $dbh;
}

?>
