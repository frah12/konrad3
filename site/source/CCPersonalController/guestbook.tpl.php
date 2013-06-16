<?php
// Author: Fredrik Åhman
// Course: PHPMVC @ BTH
// File: guestbook.tpl.php
// Desc: Test guestbook page
?>
<h1>My Guestbook</h1>

<?php echo $form->GetHTML(); ?>

<h2>Latest messages</h2>

<?php
	foreach($entries as $entry){
		echo "<div style='background-color:#73D216; margin-bottom:1em;padding:1em;'>";
		echo "<p>Posted at: ", $entry['posted'], "</p>";
		echo "<p>", htmlentities($entry['comment']), "</p>";
		echo "</div>";
	}
?>