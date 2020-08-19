<?php
/*
	Arduino Shooting Game
	Developed and coded by Andreas Olsson
	This system is free to use, modify if you need, but i cannot give you support then.
	To use this system you need Arduino and Raspberry Pi.
	Follow my facebook page for info on updates on the system.
	
	Please visit my blog page for more info and links to facebook and how to build it:
		https://shootinggameblog.wordpress.com
	
	If you need support, contact me thru the facebook page in English or Swedish
	If you find some bugs, please inform me.
*/
require_once '../includes/config.php';
//Calculate winners



//Now winning player
$stmt1 = $db->prepare('SELECT * FROM activetimed WHERE garesults = (SELECT max(garesults) FROM activetimed WHERE isdone = :isdone AND isadded = :isadded)');
    $stmt1->execute(array(':isdone' => '1',
        ':isadded' => '1'));
    $result1 = $stmt1->fetchAll();
    foreach ($result1 as $row1) {
        $stmt = $db->prepare('UPDATE activetimed SET winall = :winall WHERE id = :id');
        $stmt->execute(array(
            ':winall' => '1',
            ':id' => $row1['id'],
        ));
    }

    $echodata = array('error' => 'false', 'errorcode' => '0', 'error1' => '');
    echo json_encode($echodata);

