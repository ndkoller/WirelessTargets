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
//Get the min players ammount.
$stmt = $db->prepare('SELECT * FROM games WHERE id = :gameid');
$stmt->execute(array(':gameid' => $_POST["id"]));
$result = $stmt->fetchAll();
foreach ($result as $row) { 
$playfrom = $row['plfrom'];
$playto = $row['plto'];
}
//Count added players
$stmt = $db->prepare('SELECT * FROM activeplayers');
$stmt->execute();
$players=$stmt->rowCount();

if ($players < $playfrom) {
    //Give error code for to low players (its errorcode 2)
    $echodata = array('error' => 'true', 'errorcode' => '2', 'error1' => '');
    echo json_encode($echodata);
} else {
    //All good, lets go on to enable the game.
    try {
        $stmt = $db->prepare('INSERT INTO activegame (gameid,isdone) VALUES (:gameid, :isdone)');
        $stmt->execute(array(
    ':gameid' => $gameid,
    ':isdone' => '0',
    ));
    $echodata = array('error' => 'false', 'errorcode' => '0', 'error1' => '');
    echo json_encode($echodata);
    } catch (PDOException $e) {
        $error[] = $e->getMessage();
    $echodata = array('error' => 'true', 'errorcode' => '1', 'error1' => $e->getMessage());
    echo json_encode($echodata);
    }
}