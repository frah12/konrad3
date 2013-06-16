<?php
// Author: Fredrik Åhman
// Course: PHPMVC @ BTH
// File: index.php
// Desc: My Lydia based site.
// Three parts: BOOTSRAP, FRONTCONTROLLER ROUTE, and THEME ENGINE RENDER

// BOOTSTRAP

// site/application directory
define('KONRAD_INSTALL_PATH', dirname(__FILE__));
define('KONRAD_SITE_PATH', KONRAD_INSTALL_PATH . '/site'); 

require(KONRAD_INSTALL_PATH . '/source/CKonrad/bootstrap.php');

$ko = CKonrad::Instance();


// FRONTCONTROLLER ROUTE
$ko->FrontControllerRoute();


// Theme Engine render

$ko->ThemeEngineRender();

?>