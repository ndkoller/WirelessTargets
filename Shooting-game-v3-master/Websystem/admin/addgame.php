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
include '../i18n_setup.php';
$errorcode = 0;
$savecode = 0;
$editcode = 0;
if ($_GET["edit"] == "1" || $_POST["update"] == "1") {
    $editcode = 1;
    if ($_GET["edit"] == "") {
        $idnumber = $_POST["idno"];
    } else {
        $idnumber = $_GET["id"];
    }
    //Edit the target so get the values
    $stmt = $db->prepare('SELECT * FROM games WHERE id = :id');
$stmt->execute(array(':id' => $idnumber));
$result = $stmt->fetchAll();
foreach ($result as $row) {
$gname = $row["gname"];
$gamount = $row["gamount"];
$gtype = $row["gtype"];
$gdesk = $row["gdesk"];
$ghard = $row["ghard"];
$plfrom = $row["plfrom"];
$plto = $row["plto"];
$upid = $row["id"];
}
}

if ($editcode == 1) {
    //We are in edit mode
    if ($_POST["update"] == "1") {
        $errorcode = 0;
        $savecode = 0;
        if ($_POST["name"] == "" || $_POST["gamount"] == "" || $_POST["desc"] == "" || $_POST["pfrom"] == "" || $_POST["pto"] == "") {
            $errorcode = 1; //No fields are set
        } else if (!is_numeric($_POST["gamount"]) || !is_numeric($_POST["pfrom"]) || !is_numeric($_POST["pto"])) {
            $errorcode = 2; //No numbers
        }
    
        if ($errorcode == 1 || $errorcode == 2) {
            $savecode = 0;
        } else {
            $savecode = 1;
            //We can save now 
        }
        if ($savecode == 1) {
            $stmt = $db->prepare('UPDATE games SET gname = :gname, gamount = :gamount, gtype = :gtype, gdesk = :gdesk, ghard = :ghard, plfrom = :plfrom, plto = :plto WHERE id = :id');
            $stmt->execute(array(
                ':gname' => $_POST["name"],
                ':gamount' => $_POST["gamount"],
                ':gtype' => $_POST["gtype"],
                ':gdesk' => $_POST["desc"],
                ':ghard' => $_POST["ghard"],
                ':plfrom' => $_POST["pfrom"],
                ':plto' => $_POST["pto"],
        ':id' => $_POST["idno"]
        ));
        }
    }
    } else {

if ($_POST["save"] == "1") {
    $errorcode = 0;
    $savecode = 0;
    
    if ($_POST["name"] == "" || $_POST["gamount"] == "" || $_POST["desc"] == "" || $_POST["pfrom"] == "" || $_POST["pto"] == "") {
        $errorcode = 1; //No fields are set
    } else if (!is_numeric($_POST["gamount"]) || !is_numeric($_POST["pfrom"]) || !is_numeric($_POST["pto"])) {
        $errorcode = 2; //No numbers
    }

    if ($errorcode == 1 || $errorcode == 2) {
        $savecode = 0;
    } else {
        $savecode = 1;
        //We can save now 
    }
    if ($savecode == 1) {
        //Add target to database
        $stmt = $db->prepare('INSERT INTO games (gname,gamount,gtype,gdesk,ghard,plfrom,plto) VALUES (:gname, :gamount, :gtype, :gdesk, :ghard, :plfrom, :plto)');
        $stmt->execute(array(
        ':gname' => $_POST["name"],
        ':gamount' => $_POST["gamount"],
        ':gtype' => $_POST["gtype"],
        ':gdesk' => $_POST["desc"],
        ':ghard' => $_POST["ghard"],
        ':plfrom' => $_POST["pfrom"],
        ':plto' => $_POST["pto"],
        ));
    }

} }
?>
<html>

<head>
    <title><?=gettext('Add game');?></title>
    <style>
    form {
        /* Center the form on the page */
        margin: 0 auto;
        width: 400px;
        /* Form outline */
        padding: 1em;
        border: 1px solid #CCC;
        border-radius: 1em;
    }

    ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    form li+li {
        margin-top: 1em;
    }

    label {
        /* Uniform size & alignment */
        display: inline-block;
        width: 90px;
        text-align: right;
    }

    input,
    textarea {
        /* To make sure that all text fields have the same font settings
     By default, textareas have a monospace font */
        font: 1em sans-serif;

        /* Uniform text field size */
        width: 300px;
        box-sizing: border-box;

        /* Match form field borders */
        border: 1px solid #999;
    }

    input:focus,
    textarea:focus {
        /* Additional highlight for focused elements */
        border-color: #000;
    }

    textarea {
        /* Align multiline text fields with their labels */
        vertical-align: top;

        /* Provide space to type some text */
        height: 5em;
    }

    .button {
        /* Align buttons with the text fields */
        padding-left: 90px;
        /* same size as the label elements */
    }

    button {
        /* This extra margin represent roughly the same space as the space
     between the labels and their text fields */
        margin-left: .5em;
    }
    </style>
</head>

<body>
    <p><a href="index.php"><?=gettext('Go home');?></a></p>
    <form action="addgame.php" method="post">
    <?php if ($editcode == 1) { ?>
        <?php if ($errorcode == 1) { ?>
            

            <p><?=gettext('You need to fill out all fields');?></p>
            <?php } else if ($errorcode == 2) { ?>
            <p><?=gettext('Amount, From and To can only be numbers');?></p>
            <?php } else {
           if ($savecode == 1){ ?>
            <p><?=gettext('Game is updated to the system');?></p>
            <?php } 
       }  ?>

<ul>
            <li>
                <label for="name"><?=gettext('Game Name');?>:</label>
                <input type="text" id="name" name="name"
                    <?php if ($errorcode == 1 || $errorcode == 2  || $savecode == 1) { ?>value="<?php echo $_POST["name"];?>" <?php } else { ?> value="<?php echo $gname;?>" <?php } ?>>
            </li>
            <li>
                <label for="gtype"><?=gettext('Type');?>:</label>
                <select name="gtype" id="gtype">
                <?php if ($errorcode == 1 || $errorcode == 2  || $savecode == 1) { ?>
                    <?php if ($_POST["gtype"] == "1") { ?>
                    <option value="1" selected><?=gettext('Quicktime');?></option>
                    <option value="2"><?=gettext('Timed mode');?></option>
                    <option value="3"><?=gettext('Rapidfire');?></option>
                    <?php } ?>
                    <?php if ($_POST["gtype"] == "2") { ?>
                        <option value="1"><?=gettext('Quicktime');?></option>
                    <option value="2" selected><?=gettext('Timed mode');?></option>
                    <option value="3"><?=gettext('Rapidfire');?></option>
                    <?php } ?>
                    <?php if ($_POST["gtype"] == "3") { ?>
                        <option value="1"><?=gettext('Quicktime');?></option>
                    <option value="2"><?=gettext('Timed mode');?></option>
                    <option value="3" selected><?=gettext('Rapidfire');?></option>
                    <?php } ?>
                <?php } else { ?>

                    <?php if ($gtype == "1") { ?>
                    <option value="1" selected><?=gettext('Quicktime');?></option>
                    <option value="2"><?=gettext('Timed mode');?></option>
                    <option value="3"><?=gettext('Rapidfire');?></option>
                    <?php } ?>
                    <?php if ($gtype == "2") { ?>
                        <option value="1"><?=gettext('Quicktime');?></option>
                    <option value="2" selected><?=gettext('Timed mode');?></option>
                    <option value="3"><?=gettext('Rapidfire');?></option>
                    <?php } ?>
                    <?php if ($gtype == "3") { ?>
                        <option value="1"><?=gettext('Quicktime');?></option>
                    <option value="2"><?=gettext('Timed mode');?></option>
                    <option value="3" selected><?=gettext('Rapidfire');?></option>
                    <?php } ?>
                <?php } ?>
                </select>

            </li>
            <li>
                <label for="gamount"><?=gettext('Amount');?>:</label>
                <input type="text" id="gamount" name="gamount"
                    <?php if ($errorcode == 1 || $errorcode == 2  || $savecode == 1) { ?>value="<?php echo $_POST["gamount"];?>" <?php } else { ?> value="<?php echo $gamount;?>" <?php } ?>>

            </li>
            <li>
                <label for="desc"><?=gettext('Description');?>:</label>
                <textarea id="desc" name="desc"><?php if ($errorcode == 1 || $errorcode == 2  || $savecode == 1) { echo $_POST["desc"];?><?php } else { ?><?php echo $gdesk;?> <?php } ?></textarea>
            </li>
            <li>
                <label for="ghard"><?=gettext('Hard');?>:</label>
                <select name="ghard" id="ghard">
                <?php if ($errorcode == 1 || $errorcode == 2  || $savecode == 1) { ?>
                    <?php if ($_POST["ghard"] == "1") { ?>
                    <option value="1" selected>1 (<?=gettext('Easy');?>)</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5 (<?=gettext('Hard');?>)</option>
                    <?php } ?>
                    <?php if ($_POST["ghard"] == "2") { ?>
                        <option value="1">1 (<?=gettext('Easy');?>)</option>
                    <option value="2" selected>2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5 (<?=gettext('Hard');?>)</option>
                    <?php } ?>
                    <?php if ($_POST["ghard"] == "3") { ?>
                        <option value="1">1 (<?=gettext('Easy');?>)</option>
                    <option value="2">2</option>
                    <option value="3" selected>3</option>
                    <option value="4">4</option>
                    <option value="5">5 (<?=gettext('Hard');?>)</option>
                    <?php } ?>
                    <?php if ($_POST["ghard"] == "4") { ?>
                        <option value="1">1 (<?=gettext('Easy');?>)</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4" selected>4</option>
                    <option value="5">5 (<?=gettext('Hard');?>)</option>
                    <?php } ?>
                    <?php if ($_POST["ghard"] == "5") { ?>
                        <option value="1">1 (<?=gettext('Easy');?>)</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5" selected>5 (<?=gettext('Hard');?>)</option>
                    <?php } ?>

                <?php } else { ?>
                    <?php if ($ghard == "1") { ?>
                    <option value="1" selected>1 (<?=gettext('Easy');?>)</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5 (<?=gettext('Hard');?>)</option>
                    <?php } ?>
                    <?php if ($ghard == "2") { ?>
                        <option value="1">1 (<?=gettext('Easy');?>)</option>
                    <option value="2" selected>2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5 (<?=gettext('Hard');?>)</option>
                    <?php } ?>
                    <?php if ($ghard == "3") { ?>
                        <option value="1">1 (<?=gettext('Easy');?>)</option>
                    <option value="2">2</option>
                    <option value="3" selected>3</option>
                    <option value="4">4</option>
                    <option value="5">5 (<?=gettext('Hard');?>)</option>
                    <?php } ?>
                    <?php if ($ghard == "4") { ?>
                        <option value="1">1 (<?=gettext('Easy');?>)</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4" selected>4</option>
                    <option value="5">5 (<?=gettext('Hard');?>)</option>
                    <?php } ?>
                    <?php if ($ghard == "5") { ?>
                        <option value="1">1 (<?=gettext('Easy');?>)</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5" selected>5 (<?=gettext('Hard');?>)</option>
                    <?php } ?>
                    <?php } ?>
                </select>

            </li>
            <li>
                <label for="pfrom"><?=gettext('From');?>:</label>
                <input type="text" id="pfrom" name="pfrom"
                    <?php if ($errorcode == 1 || $errorcode == 2  || $savecode == 1) { ?>value="<?php echo $_POST["pfrom"];?>" <?php } else { ?> value="<?php echo $plfrom;?>" <?php } ?>>

            </li>
            <li>
                <label for="pto"><?=gettext('To');?>:</label>
                <input type="text" id="pto" name="pto"
                    <?php if ($errorcode == 1 || $errorcode == 2  || $savecode == 1) { ?>value="<?php echo $_POST["pto"];?>" <?php } else { ?> value="<?php echo $plto;?>" <?php } ?>>

            </li>
            <input type="hidden" name="update" value="1">
            <input type="hidden" name="idno" value="<?php echo $upid; ?>">
            <li class="button">
                <button type="submit"><?=gettext('Update Game');?></button>
            </li>
        </ul>

<?php  } else { ?>
    
    <?php if ($errorcode == 1) { ?>
            

        <p><?=gettext('You need to fill out all fields');?></p>
        <?php } else if ($errorcode == 2) { ?>
        <p><?=gettext('Amount, From and To can only be numbers');?></p>
        <?php } else {
       if ($savecode == 1){ ?>
        <p><?=gettext('Game is added to the system');?></p>
        <?php } 
   }  ?>



        <ul>
            <li>
                <label for="name"><?=gettext('Game Name');?>:</label>
                <input type="text" id="name" name="name"
                    <?php if ($errorcode == 1 || $errorcode == 2) { ?>value="<?php echo $_POST["name"];?>" <?php } ?>>
            </li>
            <li>
                <label for="gtype"><?=gettext('Type');?>:</label>
                <select name="gtype" id="gtype">
                <?php if ($errorcode == 1 || $errorcode == 2) { ?>
                    <?php if ($_POST["gtype"] == "1") { ?>
                    <option value="1" selected><?=gettext('Quicktime');?></option>
                    <option value="2"><?=gettext('Timed mode');?></option>
                    <option value="3"><?=gettext('Rapidfire');?></option>
                    <?php } ?>
                    <?php if ($_POST["gtype"] == "2") { ?>
                        <option value="1"><?=gettext('Quicktime');?></option>
                    <option value="2" selected><?=gettext('Timed mode');?></option>
                    <option value="3"><?=gettext('Rapidfire');?></option>
                    <?php } ?>
                    <?php if ($_POST["gtype"] == "3") { ?>
                        <option value="1"><?=gettext('Quicktime');?></option>
                    <option value="2"><?=gettext('Timed mode');?></option>
                    <option value="3" selected><?=gettext('Rapidfire');?></option>
                    <?php } ?>
                <?php } else { ?>

                    <option value="1"><?=gettext('Quicktime');?></option>
                    <option value="2"><?=gettext('Timed mode');?></option>
                    <option value="3"><?=gettext('Rapidfire');?></option>
                <?php } ?>
                </select>

            </li>
            <li>
                <label for="gamount"><?=gettext('Amount');?>:</label>
                <input type="text" id="gamount" name="gamount"
                    <?php if ($errorcode == 1 || $errorcode == 2) { ?>value="<?php echo $_POST["gamount"];?>" <?php } ?>>

            </li>
            <li>
                <label for="desc"><?=gettext('Description');?>:</label>
                <textarea id="desc" name="desc"><?php if ($errorcode == 1 || $errorcode == 2) { echo $_POST["desc"];?><?php } ?></textarea>
            </li>
            <li>
                <label for="ghard"><?=gettext('Hard');?>:</label>
                <select name="ghard" id="ghard">
                <?php if ($errorcode == 1 || $errorcode == 2) { ?>
                    <?php if ($_POST["ghard"] == "1") { ?>
                    <option value="1" selected>1 (<?=gettext('Easy');?>)</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5 (<?=gettext('Hard');?>)</option>
                    <?php } ?>
                    <?php if ($_POST["ghard"] == "2") { ?>
                        <option value="1">1 (<?=gettext('Easy');?>)</option>
                    <option value="2" selected>2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5 (<?=gettext('Hard');?>)</option>
                    <?php } ?>
                    <?php if ($_POST["ghard"] == "3") { ?>
                        <option value="1">1 (<?=gettext('Easy');?>)</option>
                    <option value="2">2</option>
                    <option value="3" selected>3</option>
                    <option value="4">4</option>
                    <option value="5">5 (<?=gettext('Hard');?>)</option>
                    <?php } ?>
                    <?php if ($_POST["ghard"] == "4") { ?>
                        <option value="1">1 (<?=gettext('Easy');?>)</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4" selected>4</option>
                    <option value="5">5 (<?=gettext('Hard');?>)</option>
                    <?php } ?>
                    <?php if ($_POST["ghard"] == "5") { ?>
                        <option value="1">1 (<?=gettext('Easy');?>)</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5" selected>5 (<?=gettext('Hard');?>)</option>
                    <?php } ?>

                <?php } else { ?>
                    <option value="1">1 (<?=gettext('Easy');?>)</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5 (<?=gettext('Hard');?>)</option>
                    <?php } ?>
                </select>

            </li>
            <li>
                <label for="pfrom"><?=gettext('From');?>:</label>
                <input type="text" id="pfrom" name="pfrom"
                    <?php if ($errorcode == 1 || $errorcode == 2) { ?>value="<?php echo $_POST["pfrom"];?>" <?php } ?>>

            </li>
            <li>
                <label for="pto"><?=gettext('To');?>:</label>
                <input type="text" id="pto" name="pto"
                    <?php if ($errorcode == 1 || $errorcode == 2) { ?>value="<?php echo $_POST["pto"];?>" <?php } ?>>

            </li>
            <input type="hidden" name="save" value="1">
            <li class="button">
                <button type="submit"><?=gettext('Add Game');?></button>
            </li>
        </ul>
                <?php } ?>
    </form>
    <center>
        <h2><?=gettext('Info');?>:</h2>
        <p><?=gettext('Game Name: Is a short name of the game.');?></p>
        <p><?=gettext('Type: What type of game it is.');?></p>
        <p><?=gettext('Amount: How many hit or secounds for the game. Quicktime and rapidfire uses hits (example 10 rounds or hits) and timed mode uses secounds (60 secounds for one minute play)');?></p>
        <p><?=gettext('Description: A longer info about the game.');?></p>
        <p><?=gettext('Hard: How hard is the game to play.');?></p>
        <p><?=gettext('From: From how many players (Example 1 if it possible to play without any other players)');?></p>
        <p><?=gettext('To: How many max players you can be in this game.');?></p>
    </center>
</body>

</html>