<?php
// Author: Fredrik Åhman
// Course: PHPMVC @ BTH
// File: index.tpl.php
// Desc: Index template fort blog controller.
?>
<h1>Blog index</h1>
<hr>
<h2>Content by Post</h2>

<?php
	if($contents != null){
		foreach($contents as $post){
			echo "<h3>", htmlent($post['title']), "</h3>";
			echo "<h5>Posted on: ", $post['created'], " by ", $post['owner'], "</h5>";
			echo"<p>" . filter_data($post['data']) . "</p>";
			echo "<p><a href='" . create_url("blog/edit/{$post['id']}") . "'>edit</a></p>";
		}
		echo "</ul>";
	}else{
		echo "<p>No posts.</p>";
	}
?>