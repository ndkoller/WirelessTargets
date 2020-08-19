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


?>

<!DOCTYPE html>
<html lang="zxx">
<head>

	<title><?=gettext('Highscore')?></title>
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
							<?php $stmt0 = $db->prepare('SELECT * FROM games');
									$stmt0->execute();
									$result0 = $stmt0->fetchAll();
									foreach ($result0 as $row0) { 

										?>

								<h4><?=gettext('Highscore for game')?> <?php echo $row0['gname']; ?></h4>
								<?php if ($row0['gtype'] == 1) {
									$stmt = $db->prepare('SELECT * FROM savedquick WHERE garesult = (SELECT MIN(garesult) FROM savedquick WHERE gametype = :gametype)');
									$stmt->execute(array(':gametype' => $row0['id']));
									$result = $stmt->fetchAll();
									foreach ($result as $row) { 
										?>


			<span class="appt-block bookedClearFix approved">
			<span class="status-block"><i class="booked-icon booked-icon-radio-checked"></i>
			<span><?php echo $row['gamedate']; ?></span></span>
	
		<i class="booked-icon booked-icon-clock"></i><strong><?php echo $row['fullname']; ?> :</strong>  <?=gettext("Round");?> <?php echo $row['garound'];?> <?=gettext("of");?> <?php echo $row0['gamount'];?> : <?php echo $row['garesult']; ?> <?=gettext("secounds");?>
									
								</span>
<?php } ?> 
<?php } else if ($row0['gtype'] == 2) { 
	$stmt = $db->prepare('SELECT * FROM savedtimed WHERE garesults = (SELECT MAX(garesults) FROM savedtimed WHERE gametype = :gametype)');
	$stmt->execute(array(':gametype' => $row0['id']));
	$result = $stmt->fetchAll();
	foreach ($result as $row) {  
		?>
	
			<span class="appt-block bookedClearFix approved">
			<span class="status-block"><i class="booked-icon booked-icon-radio-checked"></i>
			<span><?php echo $row['gamedate']; ?></span></span>
	
		<i class="booked-icon booked-icon-clock"></i><strong><?php echo $row['fullname']; ?> :</strong> <?php echo $row['garesults'];?> <?=gettext("hits on");?> <?php echo $row0['gamount'];?> <?=gettext("secounds");?>
									
								</span>

<?php } } else if ($row0['gtype'] == 3) { 
	$stmt = $db->prepare('SELECT * FROM savedrapid WHERE garesults = (SELECT MIN(garesults) FROM savedrapid WHERE gametype = :gametype)');
	$stmt->execute(array(':gametype' => $row0['id']));
	$result = $stmt->fetchAll();
	foreach ($result as $row) {  
		?>
	
			<span class="appt-block bookedClearFix approved">
			<span class="status-block"><i class="booked-icon booked-icon-radio-checked"></i>
			<span><?php echo $row['gamedate']; ?></span></span>
	
		<i class="booked-icon booked-icon-clock"></i><strong><?php echo $row['fullname']; ?> :</strong> <?php echo $row0['gamount'];?> <?=gettext("hits on");?> <?php echo $row['garesults'];?> <?=gettext("secounds");?>
									
								</span>

<?php } } } ?>							
						
	



		<div class="section-heading section-heading--divider-top section-heading--right">
					<h2 class="section-heading__title"><?=gettext("Menu");?></h2>
					<a href="#" onclick="quit()" id="quitIt" class="btn btn-default btn-sm btn-outline-secondary cancel"><?=gettext('Back')?></a>
					
				</div>
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
	function quit() {
		$(location).attr('href','games.php')

	}


	</script>

</body>
</html>
