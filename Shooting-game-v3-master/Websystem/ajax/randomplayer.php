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

//Get a random player from activeplayer
$stmt = $db->prepare('SELECT * FROM activeplayers WHERE isplayed = :isplayed ORDER BY RAND() LIMIT 1');
$stmt->execute(array(':isplayed' => '0'));
$result = $stmt->fetchAll();
foreach ($result as $row) { 
$fullname = $row['fullname'];
$game = $row['game'];
$isactive = $row['isactive'];
$id = $row['id'];
}

//Lets activate that player
try {
    $stmt = $db->prepare('UPDATE activeplayers SET isactive = :isactive WHERE id = :id');
    $stmt->execute(array(
':isactive' => '1',
':id' => $id
));
$echodata = array('error' => 'false', 'errorcode' => '0', 'error1' => '', 'playname' => $fullname);
echo json_encode($echodata);
} catch (PDOException $e) {
    $error[] = $e->getMessage();
$echodata = array('error' => 'true', 'errorcode' => '1', 'error1' => $e->getMessage(), 'playname' => '');
echo json_encode($echodata);
}