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
require_once 'includes/config.php';
include 'i18n_setup.php';

$stmt = $db->prepare('SELECT * FROM activegame');
$stmt->execute();
$result = $stmt->fetchAll();
foreach ($result as $row) { 
$gameid = $row['gameid'];
$tableid = $row['id'];
}
//Get gametype
$stmt = $db->prepare('SELECT * FROM games WHERE id = :id');
$stmt->execute(array(':id' => $gameid));
$result = $stmt->fetchAll();
foreach ($result as $row) { 
$gametype = $row['gtype'];
$gamename = $row['gname'];
$rounds = $row['gamount'];
}
?>

<!DOCTYPE html>
<html lang="zxx">
<head>

	<title><?=gettext('Playing')?></title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
	<link href="https://fonts.googleapis.com/css?family=Anton%7CHind:400,700%7CMontserrat:400,700" rel="stylesheet">
	<link href="assets/vendor/jquery-ui/ui-darkness/jquery-ui.min.css" rel="stylesheet">
	<link href="assets/vendor/jquery-ui/ui-darkness/theme.css" rel="stylesheet">
	<link href="assets/vendor/bootstrap/css/bootstrap.css" rel="stylesheet">
	<link href="assets/vendor/animate/animate.css" rel="stylesheet">
	<link href="assets/vendor/aos/aos.css" rel="stylesheet">
	<link href="assets/font-icon/simple-line-icons/css/simple-line-icons.css" rel="stylesheet">
	<link href="assets/font-icon/ionicons/css/ionicons.min.css" rel="stylesheet">
	<link href="assets/vendor/magnific-popup/magnific-popup.css" rel="stylesheet">
	<link href="assets/vendor/slick/slick.css" rel="stylesheet">
	<link href="assets/css/style.css" rel="stylesheet">
	<link href="assets/css/booked.css" rel="stylesheet">
	<link href="assets/css/custom.css" rel="stylesheet">
	<link href="assets/js/keyboard/css/keyboard.min.css" rel="stylesheet">

</head>
<body>

	<div class="site-wrapper">

		<div id="js-preloader-overlay" class="preloader-overlay">
			<div id="js-preloader" class="preloader"></div>
		</div>

		<div class="site-overlay"></div>
		<main class="site-content">
		
		<div class="section-content">
			<div class="container">
		
				<div class="row">
					<div class="col-lg-8 ml-lg-auto mr-lg-auto">
		
						<div id="booked-profile-page" class="booked-shortcode box">
							<div class="booked-profile-appt-list">
								<h4><?=gettext('Results for the game')?> <?php echo $gamename; ?></h4>
							<div id="resu"></div>
								
								
								
		
							</div>
						</div>
						
					</div>
				</div>
		
			</div>
		</div>
		
		</main>

		

	</div><script src="assets/vendor/jquery/jquery.min.js"></script>
	<script src="assets/vendor/jquery/jquery-migrate.min.js"></script>
	
	<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script defer src="assets/font-icon/font-awesome/js/all.min.js"></script>

	<script src="assets/vendor/jquery-ui/jquery-ui.min.js"></script>
	<script src="assets/js/core.js"></script>
	
	<script src="assets/js/init.js"></script>
	<script src="assets/js/keyboard/js/jquery.keyboard.js"></script>
	<script src="assets/js/keyboard/js/jquery.keyboard.extension-all.min.js"></script>
	<script src="assets/js/keyboard/languages/sv.js"></script>
	<script src="assets/js/keyboard/layouts/swedish.min.js"></script>

	<script>
	function nextStep() {
		jQuery.ajax({
            type: "POST",
            url: "ajax/nextsteptimed.php",
            data: {
                id: '1'
            },
            datatype: 'html',
            success: function(data) {
                var mydata = $.parseJSON(data);
                var fel = mydata.error;
                var kod = mydata.errorcode;
                var kod1 = mydata.error1;
                var players = mydata.players;
                if (fel == "true") {
                    if (kod == "1") {
                        alert('<?=gettext('Database error');?>: ' + kod1);
                    }
                } else if (fel == "false") {
					if (players == '1') {
						$(location).attr('href','activegame.php')
				} else {
					$(location).attr('href','resultstimed.php')
				}
                }
            }
        });   
	}
	setInterval(function(){ 
    //Update every secound
	jQuery.ajax({
            type: "POST",
            url: "ajax/checktimed.php",
            data: {
                id: '1'
            },
            datatype: 'html',
            success: function(data) {
                var mydata = $.parseJSON(data);
                var fel = mydata.error;
                var kod = mydata.errorcode;
                var kod1 = mydata.error1;
                var result = mydata.result;
                var isdone = mydata.done;
                if (fel == "true") {
                    if (kod == "1") {
                        alert('<?=gettext('Database error');?>: ' + kod1);
                    }
                } else if (fel == "false") {
					if (isdone == '1') {
					$("#resu").append('<span class="appt-block bookedClearFix approved"><span class="status-block"><i class="booked-icon booked-icon-radio-checked"></i> <?=gettext("Played");?></span><i class="booked-icon booked-icon-clock"></i><strong>'+result+' <?=gettext("hits");?></strong> <?=gettext("on");?> <?php echo $rounds; ?> <?=gettext("secounds.");?></span> ');
					setTimeout(nextStep, 10000);
				}
                }
            }
        });   
}, 1000);
	</script>

</body>
</html>
