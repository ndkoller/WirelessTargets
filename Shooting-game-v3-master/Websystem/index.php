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
$stmt = $db->prepare('UPDATE settings SET sendover = :sendover WHERE id = :id');
$stmt->execute(array(
':sendover' => '1',
':id' => '1'
));

?>

<!DOCTYPE html>
<html lang="zxx">
<head>
	<title><?=gettext('Setting up')?></title>
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
								<h4><?=gettext('Setting up the game')?></h4>
								<div id="setup">
								<h5><?=gettext('Confirm targets')?></h5></div>
							<div id="resu"></div>
								
								
								
		
							</div>
						</div>
						
					</div>
				</div>
		
			</div>
		</div>
		
		</main>

		

	</div>
	<script src="assets/vendor/jquery/jquery.min.js"></script>
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
	var funcnumber = 1;
	
	setInterval(function(){ 
    //Update every secound
	jQuery.ajax({
            type: "POST",
            url: "ajax/checkstartup.php",
            data: {
                id: funcnumber
            },
            datatype: 'html',
            success: function(data) {
                var mydata = $.parseJSON(data);
                var fel = mydata.error;
                var kod = mydata.errorcode;
                var kod1 = mydata.error1;
                var senddone = mydata.senddone;
                var testdone = mydata.testdone;
                if (fel == "true") {
                    if (kod == "1") {
                        alert('<?=gettext('Database error');?>: ' + kod1);
                    }
                } else if (fel == "false") {
					if (senddone == '1' && testdone == '0') {
					$("#setup").html('<h5><?=gettext("Targets Confirmed")?></h5><h5><?=gettext("Testing communication")?></h5>');
					funcnumber = "2";
				}
				if (senddone == '1' && testdone == '2'){
					$("#setup").html('<h5><?=gettext("Targets Confirmed")?></h5><h5><?=gettext("Communication problem. Trying again.")?></h5>');
					
				}
				if (senddone == '1' && testdone == '1') {
					$("#setup").html('<h5><?=gettext("Targets Confirmed")?></h5><h5><?=gettext("Communication Confirmed")?></h5>');
					jQuery.ajax({
            type: "POST",
            url: "ajax/resetstartup.php",
            data: {
                id: '1'
            },
            datatype: 'html',
            success: function(data) {
                var mydata = $.parseJSON(data);
                var fel = mydata.error;
                var kod = mydata.errorcode;
                var kod1 = mydata.error1;
                if (fel == "true") {
                    if (kod == "1") {
                        alert('<?=gettext('Database error');?>: ' + kod1);
                    }
                } else if (fel == "false") {
					$(location).attr('href','games.php')
                }
            }
        }); 
					
					
				} 
                }
            }
        });   
}, 1000); 
	</script>

</body>
</html>
