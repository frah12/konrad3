<?php
// Author: Fredrik Åhman
// Course: PHPMVC @ BTH
// File: sidebar.tpl.php
// Desc: A template sidebar for viewing html pages
?>
<h1>Links to pages:</h1>

<?php

foreach($contents as $name=>$file){
	echo "<p><a href='", create_url("pages/view/{$file}"), "'>{$name}</a></p>";
}

?>