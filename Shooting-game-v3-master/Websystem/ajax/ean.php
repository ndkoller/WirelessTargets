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
//For scanning EAN code
$stmt = $db->prepare('SELECT * FROM settings WHERE eancodescan = :ean');
$stmt->execute(array(':ean' => '1'));
$existrow=$stmt->rowCount();
$result = $stmt->fetchAll();
foreach ($result as $row) { 
$eancodescan = $row['eancodescan'];
$eancode = $row['eancode'];
}

if ($eancodescan == '1') {
    $echodata = array('error' => 'false', 'errorcode' => '0', 'error1' => '', 'eanscan' => '1', 'code' => $eancode);
    echo json_encode($echodata);
} else {
    $echodata = array('error' => 'false', 'errorcode' => '0', 'error1' => '', 'eanscan' => '0', 'code' => '');
    echo json_encode($echodata);
}