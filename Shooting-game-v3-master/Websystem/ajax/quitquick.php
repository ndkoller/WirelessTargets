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

$stmt = $db->prepare('SELECT * FROM activequick');
$stmt->execute();
$result = $stmt->fetchAll();
foreach ($result as $row) {
    $stmt1 = $db->prepare('SELECT * FROM activeplayers WHERE id = :id');
$stmt1->execute(array(':id' => $row['gplayer']));
$result1 = $stmt1->fetchAll();
foreach ($result1 as $row1) {

    $stmt = $db->prepare('INSERT INTO savedquick (garound,garesult,isdone,isadded,gplayer,winusr,winall,gamecode,fullname,gametype,gamedate, gametime) VALUES (:garound, :garesult, :isdone, :isadded, :gplayer, :winusr, :winall, :gamecode, :fullname, :gametype, :gamedate, :gametime)');
    $stmt->execute(array(
':garound' => $row['garound'],
':garesult' => $row['garesult'],
':isdone' => $row['isdone'],
':isadded' => $row['isadded'],
':gplayer' => $row['gplayer'],
':winusr' => $row['winusr'],
':winall' => $row['winall'],
':gamecode' => $gamecode,
':fullname' => $row1['fullname'],
':gametype' => $row1['game'],
':gamedate' => $row['gamedate'],
':gametime' => $row['gametime'],
));

}

}

//Delete from all active tables
$stmt = $db->prepare('DELETE FROM activeplayers');
    $stmt->execute();
    $stmt = $db->prepare('DELETE FROM activequick');
    $stmt->execute();
    $stmt = $db->prepare('DELETE FROM activegame');
    $stmt->execute();

    $echodata = array('error' => 'false', 'errorcode' => '0', 'error1' => '');
    echo json_encode($echodata);