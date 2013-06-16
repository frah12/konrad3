<?php
// Author: Fredrik Åhman
// Course: PHPMVC @ BTH
// File: index.tpl.php
// Desc: Index template for grid with less and semantics . gs
?>
<!DOCTYPE html>
<html lang='sv'>

	<head>
		<meta charset='UTF-8'>
		<title><?php echo $title; ?></title>
		<link rel='shortcut icon' href='<?php echo theme_url($favicon); ?>'>
		<link rel='stylesheet' href='<?php echo theme_url($stylesheet); ?>'>
<?php
	if(isset($inline_style)){
		echo "\n<style>", $inline_style, "</style>\n";
	}
?>
	</head>
<body>

	<div id='outer-wrap-header'>
		<div id='inner-wrap-header'>
			<div id='header'>
				<div id='login-menu'>
					<?php echo login_menu(); ?>
				</div>
				<div id='banner'>
					<a href='<?php echo base_url();?>'>Konrad</a>
					<span id='site-title' style="background-image:url('<?php echo theme_url($logo); ?>');"><a href='<?php echo base_url(); ?>'><?php echo $header; ?></a></span>
					<span id='site-slogan'><?php echo slogan(); ?></span>
				</div>
<?php
	if(region_has_content('navbar')){
		echo "<div id='navbar'>", render_views('navbar'), "</div>\n";
	}
?>
			</div>
		</div>
	</div>

<?php
	if(region_has_content('flash')){
		echo "<div id='outer-wrap-flash'>\n<div id='inner-wrap-flash'>\n";
		echo "<div id='flash'>\n", render_views('flash'), "\n</div>\n";
		echo "</div>\n</div>\n";
	}

/*
	if(region_has_content('featured-first', 'featured-middle', 'featured-last')){
		echo "<div id='outer-wrap-featured'>\n<div id='inner-wrap-featured'>\n";
		echo "<div id='featured-first'>\n", render_views('featured-first'), "</div>\n";
		echo "<div id='featured-middle'>\n", render_views('featured-middle'), "</div>\n";
		echo "<div id='featured-last'>\n", render_views('featured-last'), "</div>\n";
		echo "</div>\n</div>\n";
	}
*/
?>

	<div id='outer-wrap-main'>
		<div id='inner-wrap-main'>
			<div id='primary'>
				<?php echo get_messages_from_session(); ?>
				<?=@$main; ?>
				<?=render_views('primary');?>
				<?=render_views(); ?>
			</div>
			<div id='sidebar'>
				<?=render_views('sidebar');?>
			</div>
		</div>
	</div>

<?php
	if(region_has_content('triptych-first', 'triptych-middle', 'triptych-last')){
		echo "<div id='outer-wrap-triptych'>\n<div id='inner-wrap-triptych'>\n";
		echo "<div id='triptych-first'>", render_views('triptych-first'), "</div>\n";
		echo "<div id='triptych-middle'>", render_views('triptych-middle'), "</div>\n";
		echo "<div id='triptych-last'>", render_views('triptych-last'), "</div>\n";
		echo "</div>\n</div>\n";
	}
	
	if(region_has_content('footer-column-one','footer-column-two','footer-column-three', 'footer-column-four')){
		echo "<div id='outer-wrap-footer-column'>\n<div id='inner-wrap-footer-column'>\n";
		echo "<div id='footer-column-one'>", render_views('footer-column-one'), "</div>\n";
		echo "<div id='footer-column-two'>", render_views('footer-column-two'), "</div>\n";
		echo "<div id='footer-column-three'>", render_views('footer-column-three'), "</div>\n";
		echo "<div id='footer-column-four'>", render_views('footer-column-four'), "</div>\n";
		echo "</div>\n</div>\n";
	}
?>
<!-- could do an if has region for footer too, since not everyone necessarily wants a footer too. -->
	<div id='outer-wrap-footer'>
		<div id='inner-wrap-footer'>
			<div id='footer'><?php echo $footer; echo tools(); /* echo get_debug();*/ ?></div>
		</div>
	</div>

</body>
</html>