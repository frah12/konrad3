<?php
// Author: Fredrik Åhman
// Course: PHPMVC @ BTH
// File: index.tpl.php
// Desc: Index template for test theme controller.
?>
<h1>A theme</h1>
<p>This controller helps in theme developing and testing.<p>
<p>Current theme is: <?php echo $theme_name; ?></p>

<p>The list of methods for theme developing and testing.</p>
<ul>
<?php
	foreach($methods as $method){
		echo "<li><a href='", create_url($method), "'>", $method, "</a></li>\n";
	}
?>
</ul>

