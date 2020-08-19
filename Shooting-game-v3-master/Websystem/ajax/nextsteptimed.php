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

$stmt = $db->prepare('SELECT * FROM activeplayers WHERE isactive = :isactive AND isplayed = :isplayed');
$stmt->execute(array(':isactive' => '0',
':isplayed' => '0'));
$result = $stmt->fetchAll();
$existrow=$stmt->rowCount();

if ($existrow > '0') {
    $echodata = array('error' => 'false', 'errorcode' => '0', 'error1' => '', 'players' => '1');
    echo json_encode($echodata);
} else {
$echodata = array('error' => 'false', 'errorcode' => '0', 'error1' => '', 'players' => '0');
echo json_encode($echodata);
}