<?php
// Author: Fredrik Åhman
// Course: PHPMVC @ BTH
// File: index.tpl.php
// Desc: View for the Index controller
?>
<h1>This is the Index page</h1>

<h2>Download and install Konrad</h2>
<p>You can download and install Konrad from github.</p>
<p>From a console/terminal, change directory into the directory you wish to install Konrad too then clone Konrad from Github, liks so:</p>
<blockquote>
<code>git clone git://github.com/frah12/konrad3.git</code>
</blockquote>
<p>
Follow these steps to configure your site:
</p>
<ol>
<li>Edit <code>.htaccess</code>-file in Konrad's root directory so that the line: <code>RewriteBase</code> reflect your web server path to where you installed Konrad so it looks something like this: <code>/~user/installation/directory/konrad3</code>. If that doesn't work you might need to enter the full path including the web server address, like so: <code>http://yourwebserver:port/installation/directory/path/konrad3</code></li>
<li>You need to make these directories and file(s) writable:</li>
	<ul>
		<li>konrad3/site/data (chmod 757)</li>
		<li>konrad3/site/data/ckonrad.sqlite (chmod 666)</li>
		<li>konrad3/site/users (chmod 757)</li>
		<li>konrad3/themes/grid (chmod 757) to make <code>less</code>-system and <code>semantic</code>-grid to work.</li>
	</ul>
<li>To change logo, title of the site, and footer you need to edit the konrad3/site/config.php file at section <em>Themes</em>.</li>
<li>To change the navigation menu you need to edit the konrad3/site/config.php file at section <em>Menu</em>.</li>
<li>Favicon and logo images are stored in the <code>konrad3/themes/grid</code>-directory by default.</li>
<li>Click the login-link in the upper right corner to login as <em>root</em> using default password <em>root</em>, then click the menu-<em>admin</em> link to get to the administrator's page. (If you can't login in point your web browser to: <code>address/path/to/installation/directory/user/init</code>). When loged in as root and on the administrator's control panel you should see users and and database actions in the primary frame, and certain tasks available in the sidebar.</li>
<li>If you are logged in as root, it means the user database is initialized. You should now initialize the content database for the blog. When logged in as root, from the administrator's control panel, click the <em>Init content database...</em>-link under database actions.</li>
<li>Whilst logged in as root, click the menu-<em>blog</em> link to get to your blog section. You should see some default blog posts. In the sidebar you can click the <em>New post</em> link to write a new blog post.</li>
<li>To add static <code>html</code>-pages. Write your <code>html</code>-pages containing only your text and html-markup that goes between the <code><body></body></code> tags and just drop your <code>HTML</code>-files in the <code>site/pages</code>-directory, and the <code>CCPages</code>-controller will do the rest to load the links to the pages in the <em>sidebar</em> and your content will load in the <em>primary</em> frame when clicked. See more by pointing your browser to <code>pages/</code>.</li>
</ol>