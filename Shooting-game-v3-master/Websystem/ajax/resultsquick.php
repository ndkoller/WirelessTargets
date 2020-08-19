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

//First winning round each player

$stmt = $db->prepare('SELECT * FROM activeplayers WHERE isplayed = :isplayed');
$stmt->execute(array(':isplayed' => '1'));
$result = $stmt->fetchAll();
foreach ($result as $row) {
    $stmt1 = $db->prepare('SELECT * FROM activequick WHERE garesult = (SELECT MIN(garesult) FROM activequick WHERE isdone = :isdone AND isadded = :isadded AND gplayer = :gplayer)');
    $stmt1->execute(array(':isdone' => '1',
        ':isadded' => '1',
        ':gplayer' => $row['id']));
    $result1 = $stmt1->fetchAll();
    foreach ($result1 as $row1) {
        $stmt = $db->prepare('UPDATE activequick SET winusr = :winusr WHERE id = :id');
        $stmt->execute(array(
            ':winusr' => '1',
            ':id' => $row1['id'],
        ));

    }

}

//Now winning player
$stmt1 = $db->prepare('SELECT * FROM activequick WHERE garesult = (SELECT MIN(garesult) FROM activequick WHERE isdone = :isdone AND isadded = :isadded)');
    $stmt1->execute(array(':isdone' => '1',
        ':isadded' => '1'));
    $result1 = $stmt1->fetchAll();
    foreach ($result1 as $row1) {
        $stmt = $db->prepare('UPDATE activequick SET winall = :winall WHERE id = :id');
        $stmt->execute(array(
            ':winall' => '1',
            ':id' => $row1['id'],
        ));
    }

    $echodata = array('error' => 'false', 'errorcode' => '0', 'error1' => '');
    echo json_encode($echodata);

