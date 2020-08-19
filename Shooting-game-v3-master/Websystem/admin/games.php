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
$deletemode = 0;
if ($_GET["delete"] == "1") {
  //Enable delete mode
  $deletemode = 1;
  //Get name of target
  $stmt = $db->prepare('SELECT * FROM games WHERE id = :id');
$stmt->execute(array(':id' => $_GET["id"]));
$result = $stmt->fetchAll();
foreach ($result as $row) {
$tarname = $row["gname"];
$upid = $row["id"];
}

}

if ($_GET["delok"] == "1") {
  //Delete from table
  $deletemode = 2;
  $stmt = $db->prepare('DELETE FROM games WHERE id = :id');
    $stmt->execute(array(
':id' => $_GET["id"]
));
header('games.php');
}

?>
<html>
    <head>
        <title><?=gettext('Games');?></title>
        <style>
            html {
  font-family: sans-serif;
}

table {
  border-collapse: collapse;
  border: 2px solid rgb(200,200,200);
  letter-spacing: 1px;
  font-size: 0.8rem;
}

td, th {
  border: 1px solid rgb(190,190,190);
  padding: 10px 20px;
}

th {
  background-color: rgb(235,235,235);
}

td {
  text-align: center;
}

tr:nth-child(even) td {
  background-color: rgb(250,250,250);
}

tr:nth-child(odd) td {
  background-color: rgb(245,245,245);
}

caption {
  padding: 10px;
}
        </style>
    </head>
    <body>
    <p><a href="index.php"><?=gettext('Go home');?></a></p>
      <?php if ($deletemode == 1) { ?>
      <center>
        <h2><?=gettext('Do you want to delete game');?> <?php echo $tarname; ?> ?</h2>
        <a href="games.php?id=<?php echo $upid;?>&delok=1"><?=gettext('Yes');?></a> - <a href="games.php"><?=gettext('No');?></a>
      </center> <?php } ?>
    <table>
  <tr>
    <td><?=gettext('Name');?></td>
    <td><?=gettext('Amount');?></td>
    <td><?=gettext('Gametype');?></td>
    <td><?=gettext('Menu');?></td>
  </tr>
  <?php
$stmt = $db->prepare('SELECT * FROM games');
$stmt->execute();
$result = $stmt->fetchAll();
foreach ($result as $row) { ?>
  <tr>
    <td><?php echo $row['gname'];?></td>
    <td><?php echo $row['gamount'];?></td>
    <td>
    <?php if ($row['gtype'] == "1") {
      echo gettext('Quicktime');
    } else if ($row['gtype'] == "2") {
      echo gettext('Timed mode');
    } else {
      echo gettext('Rapidfire');
    } ?></td>
    <td><a href="addgame.php?id=<?php echo $row['id'];?>&edit=1"><?=gettext('Edit');?></a> - <a href="games.php?id=<?php echo $row['id'];?>&delete=1"><?=gettext('Delete');?></a></td>
  </tr>
<?php } ?>
  
</table>
    </body>
</html>