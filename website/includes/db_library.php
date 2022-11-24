<?php
function getDBH() 
{
	try {  $dbh = new PDO('mysql:host=localhost;dbname=updere', 'upd', 'W!kD3Y9X45wZ34V3c');
	    } catch (PDOException $e) {
	        print "Error!: " . $e->getMessage() . "<br/>"; die();
		}
	return $dbh;
}

?>
