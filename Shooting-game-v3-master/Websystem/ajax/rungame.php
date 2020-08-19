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
$gameid = $_POST['id'];

//Get active game
$stmt = $db->prepare('SELECT * FROM activegame');
$stmt->execute();
$result = $stmt->fetchAll();
foreach ($result as $row) { 
$gameid = $row['gameid'];
$tableid = $row['id'];
}
//Get gametype
$stmt = $db->prepare('SELECT * FROM games WHERE id = :id');
$stmt->execute(array(':id' => $gameid));
$result = $stmt->fetchAll();
foreach ($result as $row) { 
$gametype = $row['gtype'];
}
$stmt = $db->prepare('UPDATE activeplayers SET runnow = :runnow WHERE isactive = :isactive');
$stmt->execute(array(
':runnow' => '1',
':isactive' => '1'
));
//Update game to active
try {
    $stmt = $db->prepare('UPDATE activegame SET beginplay = :beginplay WHERE id = :id');
    $stmt->execute(array(
':beginplay' => '1',
':id' => $tableid
));
$echodata = array('error' => 'false', 'errorcode' => '0', 'error1' => '', 'gamemode' => $gametype);
echo json_encode($echodata);
} catch (PDOException $e) {
    $error[] = $e->getMessage();
$echodata = array('error' => 'true', 'errorcode' => '1', 'error1' => $e->getMessage(), 'gamemode' => '');
echo json_encode($echodata);
}