<?php
// Author: Fredrik Åhman
// Course: PHPMVC @ BTH
// File: index.tpl.php
// Desc: View for viewing user profile information
?>



<?php
deleteUserDir("site/users/", "doe}");
if(strcmp($user['acronym'], 'root') == 0){
echo "<h1>Administrator's control panel</h1>";
echo<<<EOD
<table>
	<tr>
		<th>Id</th>
		<th>Acronym</th>
		<th>Name</th>
		<th>E-mail</th>
		<th>Manage</th>
	</tr>
EOD;
	foreach($users as $u=>$content){
		echo "<tr><td>{$content['id']}</td><td>{$content['acronym']}</td><td>{$content['name']}</td><td>{$content['email']}</td><td><a href='", create_url("admin/manageuser/{$content['acronym']}"), "'>View</a></td></tr>";
	}

	echo "</table>";
	
	echo "<hr>";
	echo "<h2>Database actions</h2>";
	echo "<ul>";
	echo "<li><a href='", create_url('content/init'), "'>Init content database. Create tables for blog and sample blog posts</a></li>";
	echo "<li><a href='" . create_url("user/init") . "'>Init user database. Create tables for user and groups, and default administrator and one default user.</a></li>";
	echo "</ul>";
}else{
echo "<h1>User's admin panel</h1>";
echo "<p>What the user should be able to manage</p>";
}
?>
