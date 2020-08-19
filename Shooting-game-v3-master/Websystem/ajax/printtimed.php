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

$gamecode = $_POST['id'];
$gtype = $_POST['gtype'];
$actgame = $_POST['actgame'];

$stmt = $db->prepare('INSERT INTO printjob (gametype,gamecode,startprint,activegame) VALUES (:gametype, :gamecode, :startprint, :activegame)');
    $stmt->execute(array(
':gametype' => $gtype,
':gamecode' => $gamecode,
':startprint' => '1',
':activegame' => $actgame,
));

$echodata = array('error' => 'false', 'errorcode' => '0', 'error1' => '');
    echo json_encode($echodata);