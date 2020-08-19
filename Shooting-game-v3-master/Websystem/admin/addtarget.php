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
    $stmt = $db->prepare('SELECT * FROM targets WHERE id = :id');
$stmt->execute(array(':id' => $idnumber));
$result = $stmt->fetchAll();
foreach ($result as $row) {
$targid = $row["targid"];
$tarname = $row["tarname"];
$sendid = $row["sendid"];
$upid = $row["id"];
}
}

if ($editcode == 1) {
//We are in edit mode
if ($_POST["update"] == "1") {
    $errorcode = 0;
    $savecode = 0;
    if ($_POST["name"] == "" || $_POST["tarid"] == "" || $_POST["sendid"] == "") {
        $errorcode = 1; //No fields are set
    } else if (!is_numeric($_POST["tarid"]) || !is_numeric($_POST["sendid"])) {
        $errorcode = 2; //No numbers
    }

    if ($errorcode == 1 || $errorcode == 2) {
        $savecode = 0;
    } else {
        $savecode = 1;
        //We can save now 
    }
    if ($savecode == 1) {
        $stmt = $db->prepare('UPDATE targets SET targid = :targid, tarname = :tarname, sendid = :sendid WHERE id = :id');
        $stmt->execute(array(
    ':targid' => $_POST["tarid"],
    ':tarname' => $_POST["name"],
    ':sendid' => $_POST["sendid"],
    ':id' => $_POST["idno"]
    ));
    }
}
} else {
//We are in add mode
if ($_POST["save"] == "1") {
    $errorcode = 0;
    $savecode = 0;
    
    if ($_POST["name"] == "" || $_POST["tarid"] == "" || $_POST["sendid"] == "") {
        $errorcode = 1; //No fields are set
    } else if (!is_numeric($_POST["tarid"]) || !is_numeric($_POST["sendid"])) {
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
        $stmt = $db->prepare('INSERT INTO targets (tarname,targid,sendid,testok,sendok,batstatus,batok) VALUES (:tarname, :targid, :sendid, :testok, :sendok, :batstatus, :batok)');
        $stmt->execute(array(
        ':tarname' => $_POST["name"],
        ':targid' => $_POST["tarid"],
        ':sendid' => $_POST["sendid"],
        ':testok' => "0",
        ':sendok' => "0",
        ':batstatus' => "0",
        ':batok' => "0",
        ));
    }

}
}
?>
<html>

<head>
    <title><?=gettext('Add target');?></title>
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
    <form action="addtarget.php" method="post">
    <?php if ($editcode == 1) { ?>
        <?php if ($errorcode == 1) { ?>
        
        <p><?=gettext('You need to fill out all fields');?></p>
   <?php } else if ($errorcode == 2) { ?>
        <p><?=gettext('Target ID and Send ID can only be numbers');?></p>
   <?php } else {
       if ($savecode == 1){ ?>
       <p><?=gettext('Target is updated to the system');?></p>
  <?php } 
   }  ?>
        <ul>
            <li>
                <label for="name"><?=gettext('Target Name');?>:</label>
   <input type="text" id="name" name="name" <?php if ($errorcode == 1 || $errorcode == 2 || $savecode == 1) { ?>value="<?php echo $_POST["name"];?>"<?php } else { ?> value="<?php echo $tarname;?>" <?php } ?>>
            </li>
            <li>
                <label for="tarid"><?=gettext('Target ID');?>:</label>
                <input type="text" id="tarid" name="tarid" <?php if ($errorcode == 1 || $errorcode == 2 || $savecode == 1) { ?>value="<?php echo $_POST["tarid"];?>"<?php } else { ?> value="<?php echo $targid;?>" <?php } ?>>
            </li>
            <li>
                <label for="sendid"><?=gettext('Send ID');?>:</label>
                <input type="text" id="sendid" name="sendid" <?php if ($errorcode == 1 || $errorcode == 2 || $savecode == 1) { ?>value="<?php echo $_POST["sendid"];?>"<?php } else { ?> value="<?php echo $sendid;?>" <?php } ?>>
            </li>
            <input type="hidden" name="update" value="1">
            <input type="hidden" name="idno" value="<?php echo $upid; ?>">
            <li class="button">
                <button type="submit"><?=gettext('Update Target');?></button>
            </li>
        </ul>

  <?php  } else { ?>

    <?php if ($errorcode == 1) { ?>
        
        <p><?=gettext('You need to fill out all fields');?></p>
   <?php } else if ($errorcode == 2) { ?>
        <p><?=gettext('Target ID and Send ID can only be numbers');?></p>
   <?php } else {
       if ($savecode == 1){ ?>
       <p><?=gettext('Target is added to the system');?></p>
  <?php } 
   }  ?>
   

    
        <ul>
            <li>
                <label for="name"><?=gettext('Target Name');?>:</label>
   <input type="text" id="name" name="name" <?php if ($errorcode == 1 || $errorcode == 2) { ?>value="<?php echo $_POST["name"];?>"<?php } ?>>
            </li>
            <li>
                <label for="tarid"><?=gettext('Target ID');?>:</label>
                <input type="text" id="tarid" name="tarid" <?php if ($errorcode == 1 || $errorcode == 2) { ?>value="<?php echo $_POST["tarid"];?>"<?php } ?>>
            </li>
            <li>
                <label for="sendid"><?=gettext('Send ID');?>:</label>
                <input type="text" id="sendid" name="sendid" <?php if ($errorcode == 1 || $errorcode == 2) { ?>value="<?php echo $_POST["sendid"];?>"<?php } ?>>
            </li>
            <input type="hidden" name="save" value="1">
            <li class="button">
                <button type="submit"><?=gettext('Add Target');?></button>
            </li>
        </ul>

        <?php } ?>
    </form>
</body>

</html>