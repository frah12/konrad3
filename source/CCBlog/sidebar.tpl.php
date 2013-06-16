<?php
// Author: Fredrik Åhman
// Course: PHPMVC @ BTH
// File: sidebar.tpl.php
// Desc: Sidebar template for blog.
?>
<h1>Sidebar</h1>
<hr>
<h2>Write new post</h2>

<?php
	echo "<p><a href='" . create_url("blog/edit") . "'>New post</a></p>";
?>