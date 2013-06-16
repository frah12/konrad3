<?php
// Author: Fredrik Åhman
// Course: PHPMVC @ BTH
// File: bootstrap.php
// Desc: My Lydia based framwork called Konrad.

/**
 * Function exception_handler
 * A simple but smart exception handler.
 * Parameters: exception
 */
function exception_handler($exception){
	echo "<h3>Konrad: Uncaught exception: </h3><p>" . $exception->getMessage() . "</p>";
	echo "<pre>" . $exception->getTraceAsString() . "</pre>";
}

set_exception_handler('exception_handler');

/**
 * Function htmlent
 * Uses the character encoding setting in config.php
 * Parameters: str, flags
 * Parameter flags default to ENT__COMPAT
 */
function htmlent($str, $flags = ENT_COMPAT){
	return htmlentities($str, $flags, CKonrad::Instance()->config['character_encoding']);
}

/**
 * Function autoload
 * Enable auto-load of class declarations
 */
function autoload($aClassName){
	$classFile="/source/{$aClassName}/{$aClassName}.php";
	$file1=KONRAD_INSTALL_PATH . $classFile;
	$file2=KONRAD_SITE_PATH . $classFile;
	if(is_file($file1)){
		require_once($file1);
	} elseif(is_file($file2)){
		require_once($file2);
	}
}
spl_autoload_register('autoload');

/**
* Function getIncludeContents
* Helper, include a file and store it in a string. Make $vars available to the included file.
* Parameters: filename, vars (array)
* Returns false on woopsy
*/
function getIncludeContents($filename, $vars=array()) {

  if (is_file($filename)) {
    ob_start();
    extract($vars);
    include $filename;
    return ob_get_clean();
  }
  return false;
}

/**
 * Function makeClickable
 * Makes user's links in content clickable
 * Parameters: text
 * Returns the clickable content
 */
function makeClickable($text){
	return preg_replace_callback('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', create_function('$matches', 'return "<a href=\'{$matches[0]}\'>{$matches[0]}</a>";'), $text);
}

/**
* Function bbcode2html
* Function to add simple BBCode type formatting
* Parameters: text
* Returns preg_replace(search, replace, text)
*/
function bbcode2html($text){
	$search = array(
		'/\[b\](.*?)\[\/b\]/is',
		'/\[i\](.*?)\[\/i\]/is',
		'/\[u\](.*?)\[\/u\]/is',
		'/\[img\](https?.*?)\[\/img\]/is',
		'/\[url\](https?.*?)\[\/url\]/is',
		'/\[url=(https?.*?)\](.*?)\[\/url\]/is');
		
	$replace = array(
		'<strong>$1</strong>',
		'<em>$1</em>',
		'<u>$1</u>',
		'<img src="$1" />',
		'<a href="$1">$1</a>',
		'<a href="$1">$2</a>');
	
	return preg_replace($search, $replace, $text);
}

function createUserDir($path=null, $user=null){
	$dir=$path . $user;
	if(!is_file($dir)){
		mkdir($dir, 0777);
		chmod($dir, 0777);
		$file="{$dir}/{$user}.config.php";
		copy('site/site.config.php', $file) or die("Couldn't copy file.");
		chmod($file, 0666);
		$file="{$dir}/{$user}.style.css";
		copy('site/site.style.css', $file) or die("Couldn't copy file.");
		chmod($file, 0666);
		
		return 1;
    }else{
    	return 0;
    }
}

function deleteUserDir($path=null, $user=null){
	$dir=$path . $user;
	//echo __DIR__ . "<br>";
	//echo $dir;
	return is_file($dir)?@unlink($dir):array_map('deleteUserDir',glob($dir.'/*'))==@rmdir($dir); // nicked from php.net  for recursively unlink directory and file
}



?>