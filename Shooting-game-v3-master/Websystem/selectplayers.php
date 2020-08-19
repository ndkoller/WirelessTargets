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

$stmt = $db->prepare('SELECT * FROM games WHERE id = :gameid');
$stmt->execute(array(':gameid' => $_GET["id"]));
$result = $stmt->fetchAll();
foreach ($result as $row) { 
$playfrom = $row['plfrom'];
$playto = $row['plto'];
}
?>

<!DOCTYPE html>
<html lang="zxx">
<head>

	<title><?=gettext('Select players')?></title>
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
								<h4><?=gettext('Add players')?></h4>

								<?php for ($i = 0; $i < $playto; $i++) { ?>
		
								<span id="classachange_<?php echo $i; ?>" class="appt-block bookedClearFix pending">
									<span class="status-block"><i id="classchange_<?php echo $i; ?>" class="booked-icon booked-icon-radio-unchecked"></i> <span id="text_<?php echo $i; ?>"><?=gettext('Avalible')?></span></span>
									<i class="booked-icon booked-icon-clock"></i><strong id="playName_<?php echo $i; ?>"><?=gettext('No player')?></strong>
									<span class="booked-cal-buttons">
										<a href="#" id="addB_<?php echo $i; ?>" class="btn btn-primary btn-sm hiddenInput_<?php echo $i; ?>"><?=gettext('Add player')?></a>
										<a href="#" id="removeB_<?php echo $i; ?>" onclick="delete_<?php echo $i; ?>()" style="display:none;" class="btn btn-default btn-sm btn-outline-secondary cancel"><?=gettext('Remove player')?></a>
										<input id="hidden_<?php echo $i; ?>" class="dark" type="text" style="display:none;">
										<input id="added_<?php echo $i; ?>" type="hidden" name="added_<?php echo $i; ?>" value="">
									</span>
								</span>
								<?php } ?>
								
				<div class="section-heading section-heading--divider-top section-heading--right">
					<h2 class="section-heading__title"><?=gettext('Options')?></h2>
					<a href="#" onclick="startGame(<?php echo $_GET['id'];?>)" id="startPlaying" class="btn btn-primary btn-sm"><?=gettext('Play')?></a>
					<a href="#" onclick="stopGame(<?php echo $_GET['id'];?>)" id="stopPlaying" class="btn btn-default btn-sm btn-outline-secondary cancel"><?=gettext('Cancel')?></a>
					
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
	function stopGame(d) {
		jQuery.ajax({
            type: "POST",
            url: "ajax/stopgame.php",
            data: {
                id: d
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
	
	function startGame(p) {

		jQuery.ajax({
            type: "POST",
            url: "ajax/startgame.php",
            data: {
                id: p
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
                    } else if (kod == "2") {
						alert('<?=gettext('You need to have');?> <?php echo $playfrom; ?> <?=gettext('players to play this game.');?>');
					}
                } else if (fel == "false") {
                    $(location).attr('href','activegame.php')
                }
            }
        });

	}

	<?php for ($i = 0; $i < $playto; $i++) { ?>
	
		function delete_<?php echo $i; ?>() {

			jQuery.ajax({
            type: "POST",
            url: "ajax/deleteplayer.php",
            data: {
                id: $("#added_<?php echo $i; ?>").val()
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
                    $("#removeB_<?php echo $i; ?>").hide();
					$("#added_<?php echo $i; ?>").val('');
					$("#addB_<?php echo $i; ?>").show();
					$("#playName_<?php echo $i; ?>").html('<?=gettext('No player');?>');
					$( "#classchange_<?php echo $i; ?>" ).removeClass( 'booked-icon-radio-checked' );
					$( "#classchange_<?php echo $i; ?>" ).addClass( 'booked-icon-radio-unchecked' );
					$( "#classachange_<?php echo $i; ?>" ).removeClass( 'approved' );
					$( "#classachange_<?php echo $i; ?>" ).addClass( 'pending' );
					$("#text_<?php echo $i; ?>").html('<?=gettext('Avalible');?>');
                }
            }
        });

		}

	$('.hiddenInput_<?php echo $i; ?>').click(function(){
	$('#hidden_<?php echo $i; ?>').getkeyboard().reveal();
	return false;
});
// Initialize keyboard script on hidden input
// set "position.of" to the same link as above
$('#hidden_<?php echo $i; ?>')
	.keyboard({
		language     : "en", //en for english sv for swedish
		layout   : 'qwerty', //qwerty for english swedish-qwerty for swedish
		position : {
			of : $('.hiddenInput_<?php echo $i; ?>'),
			my : 'center top',
			at : 'center top'
		},
		accepted : function(event, keyboard, el) {
			jQuery.ajax({
            type: "POST",
            url: "ajax/addplayer.php",
            data: {
                id: '<?php echo $_GET["id"];?>',
				name: el.value
            },
            datatype: 'html',
            success: function(data) {
                var mydata = $.parseJSON(data);
                var fel = mydata.error;
                var kod = mydata.errorcode;
                var kod1 = mydata.error1;
                var laid = mydata.laid;
                if (fel == "true") {
                    if (kod == "1") {
                        alert('<?=gettext('Database error');?>: ' + kod1);
                    }
                } else if (fel == "false") {
                    $("#removeB_<?php echo $i; ?>").show();
					$("#added_<?php echo $i; ?>").val(laid);
					$("#addB_<?php echo $i; ?>").hide();
					$("#playName_<?php echo $i; ?>").html(el.value);
					$( "#classchange_<?php echo $i; ?>" ).removeClass( 'booked-icon-radio-unchecked' );
					$( "#classchange_<?php echo $i; ?>" ).addClass( 'booked-icon-radio-checked' );
					$( "#classachange_<?php echo $i; ?>" ).removeClass( 'pending' );
					$( "#classachange_<?php echo $i; ?>" ).addClass( 'approved' );
					$("#text_<?php echo $i; ?>").html('<?=gettext('Saved');?>');
                }
            }
        });
  }
	})
	.addTyping();

<?php } ?>
	</script>

</body>
</html>
