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
?>
<html>
    <head>
        <title><?=gettext('Admin');?></title>
    </head>
    <body>
        <a href="addtarget.php"><?=gettext('Add target');?></a> - <a href="addgame.php"><?=gettext('Add game');?></a> - <a href="targets.php"><?=gettext('Targets');?></a> - <a href="games.php"><?=gettext('Games');?></a>
    </body>
</html>