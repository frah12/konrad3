<?php
// Author: Fredrik Åhman
// Course: PHPMVC @ BTH
// File: CHTMLPurifier.php
// Desc: A class to integrate htmlpurifier to konrad

/**
 * Class to integrate CHTMLPurifier to konrad framework
 */
class CHTMLPurifier {

	// Member variables
	public static $instance = null;

	// Methods
	
	 
	/**
	 * Public static function Purify
	 * Make an instance of HTMLPurifier if it doesn't exist.
	 * Uses singleton patter, but has not enabled caching.
	 * If caching, store cache-files in site/data directory (which must be writable.)
	 * Parameters: text
	 * Returns: self::$instance->purify($text)
	 */
	public static function Purify($text){
		if(!self::$instance){
			require_once(__DIR__.'/htmlpurifier-4.5.0-standalone/HTMLPurifier.standalone.php');
			$config = HTMLPurifier_Config::createDefault();
			$config->set('Cache.DefinitionImpl', null);
			self::$instance = new HTMLPurifier($config);
		}
	
		return self::$instance->purify($text);
	}
 
 
}
?>