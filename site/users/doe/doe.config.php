<?php
// Author: Fredrik Åhman
// Course: PHPMVC @ BTH
// File: site.config.php
// Desc: Site users main config file.

$footer="<p>My Page Footer</p>";
$title="My pageegap yM";
$this->config['theme']['path']='site/themes/mytheme';
$this->config['theme']['stylesheet']='style.css';

$this->config['theme']['data']['header']='My Page Header';
$this->config['theme']['data']['slogan']='nagolSSlogan';
$this->config['theme']['data']['favicon']='favicon_penguin.ico';
$this->config['theme']['data']['logo']='';
$this->config['theme']['data']['logo_width']=0;
$this->config['theme']['data']['logo_height']=0;
$this->config['theme']['data']['footer']=$footer;

$this->config['menus']['navbar']=array(
	'home'=>array('url'=>'mypage/home', 'label'=>'Home'),
	'blog'=>array('url'=>'mypage/blog', 'label'=>'Blog'),
	'somepage'=>array('url'=>'site/source/CCPersonalController/somepage.tpl.php', 'label'=>'Somepage'));
?>