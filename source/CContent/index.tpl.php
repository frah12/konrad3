<?php
// Author: Fredrik Åhman
// Course: PHPMVC @ BTH
// File: index.tpl.php
// Desc: Index template for content controller.
?>
<h1>Content Index</h1>
<hr>
<h2>Content</h2>

<?php
	if($contents != null){
		echo "<ul>";
		
		foreach($contents as $post){
			echo "<li>{$post['id']} | ", htmlEnt($post['title']), " by: ", $post['owner'], ". <a href='" . create_url("content/edit/{$post['id']}") . "'>edit</a> | <a href='", create_url("page/view/{$post['id']}"), "'>view</a>
			</li>";
		}
		echo "</ul>";
	}else{
		echo "<p>No content exists.</p>";
	}
?>
<h2>Actions</h2>
<ul>
	<li><a href="<?php  echo create_url('content/init'); ?>">Init database, create tables and sample content</a></li>
	<li><a href="<?php echo create_url('content/create'); ?>">Create new content</a></li>
	<li><a href='<?php echo create_url('blog'); ?>'>View as blog</a></li>
</ul>


