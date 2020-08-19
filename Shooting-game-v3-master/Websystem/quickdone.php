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

function generateRandomString($length = 12) {
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$gamecode = generateRandomString();
$codeok = 0;
while ($codeok == 0) {
$stmt = $db->prepare('SELECT * FROM savedrapid WHERE gamecode = :gamecode');
$stmt->execute(array(':gamecode' => $gamecode));
$existrow=$stmt->rowCount();

$stmt1 = $db->prepare('SELECT * FROM savedtimed WHERE gamecode = :gamecode');
$stmt1->execute(array(':gamecode' => $gamecode));
$existrow1=$stmt1->rowCount();

$stmt2 = $db->prepare('SELECT * FROM savedquick WHERE gamecode = :gamecode');
$stmt2->execute(array(':gamecode' => $gamecode));
$existrow2=$stmt2->rowCount();

if ($existrow > '0' && $existrow1 > '0' && $existrow2 > '0') {
    $gamecode = generateRandomString();  
} else {
    $codeok = 1;
}

}

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

$stmt = $db->prepare('SELECT * FROM activequick WHERE winall = :winall');
$stmt->execute(array(':winall' => '1'));
$result = $stmt->fetchAll();
foreach ($result as $row) {
	$gplayer = $row['gplayer'];
	$stmt1 = $db->prepare('SELECT * FROM activeplayers WHERE id = :id');
$stmt1->execute(array(':id' => $gplayer));
$result1 = $stmt1->fetchAll();
foreach ($result1 as $row1) {
	$winnername = $row1['fullname'];
}
}
?>

<!DOCTYPE html>
<html lang="zxx">
<head>

	<title><?=gettext('Game done')?></title>
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
								<h4><?=gettext('Results for game')?> <?php echo $gamename; ?></h4>
							<?php
$stmt = $db->prepare('SELECT * FROM activeplayers WHERE isplayed = :isplayed');
$stmt->execute(array(':isplayed' => '1'));
$result = $stmt->fetchAll();
foreach ($result as $row) { ?>
	<h5><?php echo $row['fullname'];?></h5>
   <?php $stmt1 = $db->prepare('SELECT * FROM activequick WHERE isdone = :isdone AND isadded = :isadded AND gplayer = :gplayer ORDER BY id ASC');
    $stmt1->execute(array(':isdone' => '1',
        ':isadded' => '1',
        ':gplayer' => $row['id']));
    $result1 = $stmt1->fetchAll();
    foreach ($result1 as $row1) {
        ?>

		
		<?php if ($row1['winusr'] == '1') { ?>
			<span class="appt-block bookedClearFix approved">
			<span class="status-block"><i class="booked-icon booked-icon-radio-checked"></i>
			<span><?=gettext('Best results')?></span></span>
	<?php	} else { ?>
		<span class="appt-block bookedClearFix pending">
		<span class="status-block"><i class="booked-icon booked-icon-radio-unchecked"></i>
		<span><?=gettext('Played')?></span></span>
		<?php } ?>
									 
									<i class="booked-icon booked-icon-clock"></i><strong><?=gettext("Round");?> <?php echo $row1['garound']; ?> <?=gettext("of");?> <?php echo $rounds;?>:</strong> <?php echo $row1['garesult']; ?> <?=gettext("secounds");?>
									
								</span>
								

	<?php }}?>
		<div class="section-heading section-heading--divider-top section-heading--left">
					<h2 class="section-heading__title"><?=gettext("Winner");?></h2>
					<h5><?php echo $winnername; ?></h5>
				</div>
				<div class="section-heading section-heading--divider-top section-heading--right">
					<h2 class="section-heading__title"><?=gettext("Menu");?></h2>
					<a href="#" onclick="quitGame()" id="quitGame" class="btn btn-primary btn-sm"><?=gettext('Exit')?></a>
					<a href="#" onclick="printGame()" id="printGame" class="btn btn-default btn-sm btn-outline-secondary cancel"><?=gettext('Print out')?></a>
					
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
	function quitGame() {
		jQuery.ajax({
            type: "POST",
            url: "ajax/quitquick.php",
            data: {
                id: '<?php echo $gamecode; ?>'
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

	function printGame() {
		jQuery.ajax({
            type: "POST",
            url: "ajax/printquick.php",
            data: {
                id: '<?php echo $gamecode; ?>',
				gtype: '1',
				actgame: '1'
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
                    alert('<?=gettext('Starting printing, Wait for the print to be done before you exit the game.');?>');
                }
            }
        });
	}

	</script>

</body>
</html>
