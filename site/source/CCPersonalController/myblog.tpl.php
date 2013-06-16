<?php
// Author: Fredrik Åhman
// Course: PHPMVC @ BTH
// File: blog.tpl.php
// Desc: Test blog page
?>
<h1>Blog</h1>

<?php
	if($contents != null){
		foreach($contents as $content){
			echo "<h2>", htmlentities($content['title']), "</h2>";
			echo "<p class='smaller-text'><em>Posted on: ", $content['created'], " by ", $content['owner'], "</em></p>";
			echo "<p>", filter_data($content['data'], $content['filter']), "</p>";
			echo "<p class='smaller-text silent'><a href='", create_url("content/edit/{$content['id']}"), "'>edit</a></p>";
		}
	}else{
		echo "<p>No posts.</p>";
	}
?>