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

$stmt = $db->prepare('SELECT * FROM activeplayers WHERE isactive = :isactive');
$stmt->execute(array(':isactive' => '1'));
$result = $stmt->fetchAll();
foreach ($result as $row) { 
$gameid = $row['game'];
$playid = $row['id'];
}

$stmt = $db->prepare('SELECT * FROM activequick WHERE isdone = :isdone AND isadded = :isadded AND gplayer = :gplayer');
$stmt->execute(array(':isdone' => '1',
':isadded' => '0',
':gplayer' => $playid));
$result = $stmt->fetchAll();
$existrow=$stmt->rowCount();
foreach ($result as $row) { 
$round = $row['garound'];
$result = $row['garesult'];
$roundid = $row['id'];
}

if ($existrow > '0') {
//Mark as added
try {
    $stmt = $db->prepare('UPDATE activequick SET isadded = :isadded WHERE id = :id');
    $stmt->execute(array(
':isadded' => '1',
':id' => $roundid
));
$echodata = array('error' => 'false', 'errorcode' => '0', 'error1' => '', 'round' => $round, 'result' => $result, 'done' => '1');
echo json_encode($echodata);
} catch (PDOException $e) {
    $error[] = $e->getMessage();
$echodata = array('error' => 'true', 'errorcode' => '1', 'error1' => $e->getMessage(), 'round' => '', 'result' => '', 'done' => '1');
echo json_encode($echodata);
}
} else {
    $echodata = array('error' => 'false', 'errorcode' => '0', 'error1' => '', 'round' => '', 'result' => '', 'done' => '0');
    echo json_encode($echodata);
}

