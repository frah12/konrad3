<?php
// Author: Fredrik Åhman
// Course: PHPMVC @ BTH
// File: index.tpl.php
// Desc: View for the Index controller
?>
<div class='box'>
	<h4>Tasks:</h4>
	<p>Skriv en instruktioner istället, ta bort denna rad sen.</p>
	<ol>
<?php
if(strcmp($user['acronym'], 'root') == 0){
echo "<li>Manage users</li>\n<ul>\n";
echo "<li><a href='", create_url('admin') , "'>View users</a></li>\n";
echo "<li><a href='", create_url("admin/create") , "'>Create user</li>\n";
echo "<li>Delete user: Manage user 'View'</li>\n";
echo "</ul>";
}else{
echo "<li>Config site</li>\n<ul>\n";
echo "<li><a href='", create_url("admin/userconfig/{$user['acronym']}") , "'>Edit {$user['acronym']}'s config</a></li>\n";
echo "<li><a href='", create_url("admin/userconfig/{$user['acronym']}") , "'>Edit {$user['acronym']}'s stylesheet</a></li>\n";
echo "</ul>";
}


?>
	</ol>

</div>