<?php
// Author: Fredrik Åhman
// Course: PHPMVC @ BTH
// File: login.tpl.php
// Desc: A login form to login
?>
<h1>Login</h1>
<p>Login or create a new account.</p>
<?php
	echo $login_form->GetHTML(array('start'));
	
	echo "</form>";
?>