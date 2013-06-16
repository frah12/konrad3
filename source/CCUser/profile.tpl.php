<?php
// Author: Fredrik Åhman
// Course: PHPMVC @ BTH
// File: profile.tpl.php
// Desc: A view and form for the profile
?>
<h1>Profile</h1>
<p>Show and edit user profile form.</p>
<?php
if($is_authenticated){
	echo $profile_form;
	echo "<p>Account created at" . $user['created'] . ". Last updated at" . $user['updated'] . ".</p>";
	echo "<p>Group membership in" . count($user['groups']) . " group(s).</p>";
	echo "<ul>";
	foreach($user['groups'] as $group){
		echo "<li>" . $group['name'] . "</li>";
	}
	echo "</ul>";
	}else{
		echo "<p>Not authenticated. Anonymous user.</p>";
	}
?>