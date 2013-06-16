<?php
// Author: Fredrik Åhman
// Course: PHPMVC @ BTH
// File: default.tpl.php
// Desc: Default template for grid with less
?>
<!DOCTYPE html>
<html lang='sv'>

	<head>
		<meta charset='UTF-8'>
		<title><?php echo $title; ?></title>
		<link rel='shortcut icon' href='<?php echo $favicon; ?>'>
		<link rel='stylesheet' href='<?php echo $stylesheet; ?>'>
	</head>
<body>
	<div id='wrap-header'>
		<div id='header'>
			<div id='login-menu'>
				<?php echo login_menu(); ?>
			</div>
			<div id='banner'>
				<a href='<?php echo base_url();?>'>Konrad</a>
				<p class='site-title'><a href='<?php echo base_url(); ?>'><?php echo $header; ?></a></p>
			</div>
		</div>
	</div>
	
	<div id='wrap-main'>
		<div id='main' role='main'>
			<?php echo get_messages_from_session(); ?>
			<?=@$main?>
			<?php echo render_views();?>
		</div>
	</div>
	
	<div id='wrap-footer'>
		<div id='footer'>
			<?php echo $footer; ?>
			<?php echo get_debug(); ?>
		</div>
	</div>
</body>
</html>