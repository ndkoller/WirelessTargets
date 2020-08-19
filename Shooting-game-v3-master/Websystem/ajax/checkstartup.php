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
$jobwork = $_POST['id'];
$stmt = $db->prepare('SELECT * FROM targets WHERE sendok = :sendok');
$stmt->execute(array(':sendok' => '0'));
$existrow=$stmt->rowCount();
$stmt = $db->prepare('SELECT * FROM targets WHERE testok = :testok');
$stmt->execute(array(':testok' => '0'));
$testok=$stmt->rowCount();
$stmt = $db->prepare('SELECT * FROM targets WHERE testok = :testok');
$stmt->execute(array(':testok' => '2'));
$testproblem=$stmt->rowCount();
if ($jobwork == "1") {
if ($existrow > "0") {
    $echodata = array('error' => 'false', 'errorcode' => '0', 'error1' => '', 'senddone' => '0', 'testdone' => '0');
    echo json_encode($echodata);
} else {
    $echodata = array('error' => 'false', 'errorcode' => '0', 'error1' => '', 'senddone' => '1', 'testdone' => '0');
    echo json_encode($echodata);
}
} else {
    if ($testok > "0") {
        $echodata = array('error' => 'false', 'errorcode' => '0', 'error1' => '', 'senddone' => '1', 'testdone' => '0');
        echo json_encode($echodata);
    } else {
        if ($testproblem > "0") {
            $stmt = $db->prepare('UPDATE targets SET testok = :testok, sendok = :sendok');
            $stmt->execute(array(
            ':testok' => '0',
            ':sendok' => '1'

            ));
            $stmt = $db->prepare('UPDATE settings SET testtargets = :testok WHERE id = :id');
            $stmt->execute(array(
            ':testok' => '1',
            ':id' => '1'

            ));
        $echodata = array('error' => 'false', 'errorcode' => '0', 'error1' => '', 'senddone' => '1', 'testdone' => '2');
        echo json_encode($echodata);
        } else {
        $echodata = array('error' => 'false', 'errorcode' => '0', 'error1' => '', 'senddone' => '1', 'testdone' => '1');
        echo json_encode($echodata);
    }
    }
}