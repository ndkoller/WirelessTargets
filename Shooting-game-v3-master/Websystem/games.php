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

	<title><?=gettext('Select game')?></title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
	<link href="https://fonts.googleapis.com/css?family=Anton%7CHind:400,700%7CMontserrat:400,700" rel="stylesheet">
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
</head>
<body>
	<div class="site-wrapper">
		<div id="js-preloader-overlay" class="preloader-overlay">
			<div id="js-preloader" class="preloader"></div>
		</div>
		<div class="site-overlay"></div>
		<div class="page-heading jarallax" data-jarallax data-speed="0.2">
			<div class="page-heading__inner">
				<div class="container">
					<h1 class="page-heading__title">Arduino Shooting Game</h1>
					<ol class="breadcrumb page-heading__breadcrumb">
						<li class="breadcrumb-item"><a href="getbatstatus.php"><?=gettext('Battery status')?></a></li>
						<li class="breadcrumb-item"><a href="highscore.php"><?=gettext('Highscore')?></a></li>
					</ol>
				</div>
			</div>
		</div>
		<main class="site-content">
		<div class="section-content">
			<div class="container">
				<div class="row">
					<div class="col-md-10 ml-md-auto mr-md-auto">
						<div class="rooms rooms--list">

						<?php
$stmt = $db->prepare('SELECT * FROM games');
$stmt->execute();
$result = $stmt->fetchAll();
foreach ($result as $row) { ?>
		
							<div class="room">
								<div class="room__body box">
									<header class="room__header">
										<div class="room__complexity rating-icons">
											<div class="rating-icons__placeholder">
												<i class="ion-locked"></i><i class="ion-locked"></i><i class="ion-locked"></i><i class="ion-locked"></i><i class="ion-locked"></i>
											</div>
											<div class="rating-icons__active">
											<?php if ($row['ghard'] == '1') { ?>
												<i class="ion-locked"></i>
												<?php } else if ($row['ghard'] == '2') {  ?>
												<i class="ion-locked"></i><i class="ion-locked"></i>
												<?php } else if ($row['ghard'] == '3') {  ?>
												<i class="ion-locked"></i><i class="ion-locked"></i><i class="ion-locked"></i>
												<?php } else if ($row['ghard'] == '4') {  ?>
												<i class="ion-locked"></i><i class="ion-locked"></i><i class="ion-locked"></i><i class="ion-locked"></i>
												<?php } else {  ?>
												<i class="ion-locked"></i><i class="ion-locked"></i><i class="ion-locked"></i><i class="ion-locked"></i><i class="ion-locked"></i>
												<?php }  ?>
											</div>
										</div>
										<h2 class="room__title"><a href="#"><?php echo $row['gname'];?></a></h2>
										<div class="room__meta">
											<div class="room__meta-item">
												<i class="ion-person-stalker"></i> <?php echo $row['plfrom'];?>-<?php echo $row['plto'];?>
											</div>
											<div class="room__meta-item">
											<?php if ($row['gtype'] == '1' || $row['gtype'] == '3') { ?><i class="icon-target"></i><?php } else {  ?><i class="icon-clock"></i><?php }  ?> <?php echo $row['gamount'];?>
											</div>
											<div class="room__meta-item">
												<i class="ion-location"></i> <?php if ($row['gtype'] == '1') { ?><?=gettext('Quickdraw')?><?php } else if ($row['gtype'] == '2') {  ?><?=gettext('Timed mode')?><?php } else {  ?><?=gettext('Rapidfire')?><?php }  ?>
											</div>
										</div>
										
									</header>
									<div class="room__excerpt">
										<p><?php echo $row['gdesk'];?> </p>
									</div>
									<footer class="room__footer">
										<a href="selectplayers.php?id=<?php echo $row['id'];?>" class="btn btn-primary"><?=gettext('Play')?></a>
									</footer>
									</div>
								<figure class="room__img">
									
									<a href="#"><?php if ($row['gtype'] == '1') { ?><img src="assets/img/backgrounds/quick.jpg" alt=""></a><?php } else if ($row['gtype'] == '2') {  ?><img src="assets/img/backgrounds/timed.jpg" alt=""></a><?php } else {  ?><img src="assets/img/backgrounds/rapidfire.jpg" alt=""></a><?php }  ?>
								</figure>
							</div>
							<?php }?>
		
							
		
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
	<script src="assets/js/core.js"></script>
	<script src="assets/js/init.js"></script>
	<script>
	var funcnumber = 1;
	
	setInterval(function(){ 
    //Update every secound
	jQuery.ajax({
            type: "POST",
            url: "ajax/ean.php",
            data: {
                id: '0'
            },
            datatype: 'html',
            success: function(data) {
                var mydata = $.parseJSON(data);
                var fel = mydata.error;
                var kod = mydata.errorcode;
                var kod1 = mydata.error1;
                var eanscan = mydata.eanscan;
                var code = mydata.code;
                if (fel == "true") {
                    if (kod == "1") {
                        alert('<?=gettext('Database error');?>: ' + kod1);
                    }
                } else if (fel == "false") {
					if (eanscan == '1') {
						$(location).attr('href','ean.php?code='+code)
				}
                }
            }
        });   
}, 1000); 
	</script>

</body>
</html>
