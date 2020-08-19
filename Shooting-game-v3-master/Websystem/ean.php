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
$stmt = $db->prepare('UPDATE settings SET eancodescan = :eancodescan');
$stmt->execute(array(
':eancodescan' => '0'

));
$gamecode = $_GET['code'];
$gamemode = 0;
$namePlayers = array();

//check where code exist
$stmt = $db->prepare('SELECT * FROM savedrapid WHERE gamecode = :gamecode');
$stmt->execute(array(':gamecode' => $gamecode));
$rapidgame=$stmt->rowCount();

$stmt = $db->prepare('SELECT * FROM savedtimed WHERE gamecode = :gamecode');
$stmt->execute(array(':gamecode' => $gamecode));
$timedgame=$stmt->rowCount();

$stmt = $db->prepare('SELECT * FROM savedquick WHERE gamecode = :gamecode');
$stmt->execute(array(':gamecode' => $gamecode));
$quickgame=$stmt->rowCount();

if ($rapidgame > 0) {
$gamemode = 3;
$stmt = $db->prepare('SELECT * FROM savedrapid WHERE gamecode = :gamecode');
$stmt->execute(array(':gamecode' => $gamecode));
$result = $stmt->fetchAll();
foreach ($result as $row) {
    $gameid = $row['gametype'];
}
} else if ($timedgame > 0) {
	$gamemode = 2;
	$stmt = $db->prepare('SELECT * FROM savedtimed WHERE gamecode = :gamecode');
$stmt->execute(array(':gamecode' => $gamecode));
$result = $stmt->fetchAll();
foreach ($result as $row) {
    $gameid = $row['gametype'];
}
} else if ($quickgame > 0) {
$gamemode = 1;
$stmt = $db->prepare('SELECT * FROM savedquick WHERE gamecode = :gamecode');
$stmt->execute(array(':gamecode' => $gamecode));
$result = $stmt->fetchAll();
foreach ($result as $row) {
    $gameid = $row['gametype'];
}
} else {
	$gamemode = 0; //Don't exist
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

	<title><?=gettext('Game History')?></title>
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
								<?php if ($gamemode == 1) {
									$winquickname = "";
									$stmt = $db->prepare('SELECT * FROM savedquick WHERE gamecode = :gamecode');
									$stmt->execute(array(':gamecode' => $gamecode));
									$result = $stmt->fetchAll();
									foreach ($result as $row) { 
										if ($row['winall'] == '1') {
											$winquickname = $row['fullname'];
										}
										if (!in_array($row['fullname'], $namePlayers))
  
  {
	array_push($namePlayers, $row['fullname']);
  }
										?>

<?php if ($row['winusr'] == '1') { ?>
			<span class="appt-block bookedClearFix approved">
			<span class="status-block"><i class="booked-icon booked-icon-radio-checked"></i>
			<span><?=gettext('Best results')?></span></span>
	<?php	} else { ?>
		<span class="appt-block bookedClearFix pending">
		<span class="status-block"><i class="booked-icon booked-icon-radio-unchecked"></i>
		<span><?=gettext('Played')?></span></span>
		<?php } ?>
		<i class="booked-icon booked-icon-clock"></i><strong><?php echo $row['fullname']; ?> :</strong>  <?=gettext("Round");?> <?php echo $row['garound'];?> <?=gettext("of");?> <?php echo $rounds;?> : <?php echo $row['garesult']; ?> <?=gettext("secounds");?>
									
								</span>
<?php } ?> 
<h5><?=gettext('Winner:')?> <?php echo $winquickname; ?></h5>
<?php } else if ($gamemode == 2) { 
	$stmt = $db->prepare('SELECT * FROM savedtimed WHERE gamecode = :gamecode');
	$stmt->execute(array(':gamecode' => $gamecode));
	$result = $stmt->fetchAll();
	foreach ($result as $row) {  
		if (!in_array($row['fullname'], $namePlayers))
  
  {
	array_push($namePlayers, $row['fullname']);
  }?>
	<?php if ($row['winall'] == '1') { ?>
			<span class="appt-block bookedClearFix approved">
			<span class="status-block"><i class="booked-icon booked-icon-radio-checked"></i>
			<span><?=gettext('Winner')?></span></span>
	<?php	} else { ?>
		<span class="appt-block bookedClearFix pending">
		<span class="status-block"><i class="booked-icon booked-icon-radio-unchecked"></i>
		<span><?=gettext('Played')?></span></span>
		<?php } ?>
		<i class="booked-icon booked-icon-clock"></i><strong><?php echo $row['fullname']; ?> :</strong> <?php echo $row['garesults'];?> <?=gettext("hits on");?> <?php echo $rounds;?> <?=gettext("secounds");?>
									
								</span>

<?php } } else if ($gamemode == 3) { 
	$stmt = $db->prepare('SELECT * FROM savedrapid WHERE gamecode = :gamecode');
	$stmt->execute(array(':gamecode' => $gamecode));
	$result = $stmt->fetchAll();
	foreach ($result as $row) {  
		if (!in_array($row['fullname'], $namePlayers))
  
  {
	array_push($namePlayers, $row['fullname']);
  }?>
	<?php if ($row['winall'] == '1') { ?>
			<span class="appt-block bookedClearFix approved">
			<span class="status-block"><i class="booked-icon booked-icon-radio-checked"></i>
			<span><?=gettext('Winner')?></span></span>
	<?php	} else { ?>
		<span class="appt-block bookedClearFix pending">
		<span class="status-block"><i class="booked-icon booked-icon-radio-unchecked"></i>
		<span><?=gettext('Played')?></span></span>
		<?php } ?>
		<i class="booked-icon booked-icon-clock"></i><strong><?php echo $row['fullname']; ?> :</strong> <?php echo $rounds;?> <?=gettext("hits on");?> <?php echo $row['garesults'];?> <?=gettext("secounds");?>
									
								</span>

<?php } } ?>							
						
	<h4><?=gettext('Best results for player')?></h4>
	<?php 
	$arrayLength = count($namePlayers);
	$i = 0;
        while ($i < $arrayLength)
        {
			
			$stmt = $db->prepare('SELECT * FROM savedquick WHERE garesult = (SELECT MIN(garesult) FROM savedquick WHERE fullname = :fullname)');
			$stmt->execute(array(':fullname' => $namePlayers[$i]));
			$result = $stmt->fetchAll();
			foreach ($result as $row) { 
				$stmt1 = $db->prepare('SELECT * FROM games WHERE id = :id');
$stmt1->execute(array(':id' => $row['gametype']));
$result1 = $stmt1->fetchAll();
foreach ($result1 as $row1) {
    $gametype1 = $row1['gtype'];
    $gamename1 = $row1['gname'];
    $rounds1 = $row1['gamount'];
}?>
				<span class="appt-block bookedClearFix pending">
		<span class="status-block"><i class="booked-icon booked-icon-radio-unchecked"></i>
		<span><?=gettext('Played')?></span></span>
		
		<i class="booked-icon booked-icon-clock"></i><strong><?php echo $row['fullname']; ?> <?=gettext("in game");?> <?php echo $gamename1; ?> :</strong>  <?=gettext("Round");?> <?php echo $row['garound'];?> <?=gettext("of");?> <?php echo $rounds1;?> : <?php echo $row['garesult']; ?> <?=gettext("secounds");?>
									
								</span>
			<?php }

			$stmt = $db->prepare('SELECT * FROM savedtimed WHERE garesults = (SELECT MAX(garesults) FROM savedtimed WHERE fullname = :fullname)');
			$stmt->execute(array(':fullname' => $namePlayers[$i]));
			$result = $stmt->fetchAll();
			foreach ($result as $row) { 
				$stmt1 = $db->prepare('SELECT * FROM games WHERE id = :id');
$stmt1->execute(array(':id' => $row['gametype']));
$result1 = $stmt1->fetchAll();
foreach ($result1 as $row1) {
    $gametype1 = $row1['gtype'];
    $gamename1 = $row1['gname'];
    $rounds1 = $row1['gamount'];
}?>
				<span class="appt-block bookedClearFix pending">
		<span class="status-block"><i class="booked-icon booked-icon-radio-unchecked"></i>
		<span><?=gettext('Played')?></span></span>
		
		<i class="booked-icon booked-icon-clock"></i><strong><?php echo $row['fullname']; ?> <?=gettext("in game");?> <?php echo $gamename1; ?> :</strong> <?php echo $row['garesults'];?> <?=gettext("hits on");?> <?php echo $rounds1;?> <?=gettext("secounds");?>
									
								</span>
			<?php }

			$stmt = $db->prepare('SELECT * FROM savedrapid WHERE garesults = (SELECT MIN(garesults) FROM savedrapid WHERE fullname = :fullname)');
			$stmt->execute(array(':fullname' => $namePlayers[$i]));
			$result = $stmt->fetchAll();
			foreach ($result as $row) { 
				//Get gametype
$stmt1 = $db->prepare('SELECT * FROM games WHERE id = :id');
$stmt1->execute(array(':id' => $row['gametype']));
$result1 = $stmt1->fetchAll();
foreach ($result1 as $row1) {
    $gametype1 = $row1['gtype'];
    $gamename1 = $row1['gname'];
    $rounds1 = $row1['gamount'];
}
				?>
				<span class="appt-block bookedClearFix pending">
		<span class="status-block"><i class="booked-icon booked-icon-radio-unchecked"></i>
		<span><?=gettext('Played')?></span></span>
		
		<i class="booked-icon booked-icon-clock"></i><strong><?php echo $row['fullname']; ?> <?=gettext("in game");?> <?php echo $gamename1; ?> :</strong> <?php echo $rounds1;?> <?=gettext("hits on");?> <?php echo $row['garesults'];?> <?=gettext("secounds");?>
									
								</span>
			<?php }
			
			
            $i++;
        }
	?>



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
