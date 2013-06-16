<?php
/*
// Author: Fredrik Åhman
// Course: PHPMVC @ BTH
// File: default.tpl.php
// Desc: Default template file for my Lydia based framework called Konrad.
*/
?>
<!DOCTYPE html>
<html lang='sv'>
<head>
	<meta charset='UTF-8'>
	<title><?php echo $title; ?></title>
	<link rel='shortcut icon' href="<?php echo $favicon; ?>">
	<link rel='stylesheet' href="<?php echo $stylesheet; ?>">
</head>
<body>
<div id='top'>
	<div id='header'>
		<div id="login">
			<?php echo login_menu(); ?>
		</div>
		<div>
			<a href="<?php echo base_url(); ?>"><?php echo $header;?></a>
		</div>
		<div><?php echo get_messages_from_session(); ?></div>
	</div>
</div>
<div id='wrapper'>
	<div id='main'>
		<?php echo @$main; // @ ignores any error message ?>
		
		<?php render_views(); ?>
		<?php /*echo get_debug();*/ ?>
	</div>
	<div>
		<?php echo $footer; ?>
	</div>
</div>
</body>
</html>