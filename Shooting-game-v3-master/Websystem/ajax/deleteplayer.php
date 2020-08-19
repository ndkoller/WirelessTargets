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
require '../includes/config.php'; 

$id = $_POST['id'];



try {
    $stmt = $db->prepare('DELETE FROM activeplayers WHERE id = :id');
    $stmt->execute(array(
':id' => $id
));
$echodata = array('error' => 'false', 'errorcode' => '0', 'error1' => '');
echo json_encode($echodata);
} catch (PDOException $e) {
    $error[] = $e->getMessage();
$echodata = array('error' => 'true', 'errorcode' => '1', 'error1' => $e->getMessage());
echo json_encode($echodata);
}

?>