<?php
// Author: Fredrik Åhman
// Course: PHPMVC @ BTH
// File: functions.php
// Desc: Global theme functions for my Lydia based framework called Konrad. Helpers for theming. Availability: all themes.

/**
 * Function base_url
 * create url by prepending the base url.
 * Parameters: url
 * Returns: url
 */
function base_url($url=null){

	return CKonrad::Instance()->request->base_url . trim($url, '/');
	
}

/**
 * Function current_url
 * Returns: current_url
 */
function current_url(){
	return CKonrad::Instance()->request->current_url;
}

/**
 * Function create_url
 * Parameters: url, method, arguments
 * Returns: url
*/
function create_url($url=null, $method=null, $arguments=null){
	return CKonrad::Instance()->request->CreateUrl($url, $method, $arguments);
}

/**
 * Function theme_url
 * Parameters: url
 * Returns: url
 */
function theme_url($url){
	return create_url(CKonrad::Instance()->themeUrl . "/{$url}");
}

/**
 * Function theme_parent_url
 * Prepend the url to the parent directory
 * Parameters: url
 * Returns: url to parent theme
 */
function theme_parent_url($url) {
  return create_url(CKonrad::Instance()->themeParentUrl . "/{$url}");
}


/**
 * Function get_debug
 * Prints the debugging info that is defined in the function
 */
function get_debug(){
	$ko = CKonrad::Instance();
	
	$html = null;
	if(isset($ko->config['debug']['display-konrad']) AND $ko->config['debug']['display-konrad'] == true){
		$html = "<hr><h3>Debug information</h3>";
		$html .= "<p>Innehåll i CKonrad:</p>";
		$html .= "<pre>" . htmlentities(print_r($ko, true)) . "</pre>";
	
		if(isset($ko->config['debug']['db-num-queries']) AND $ko->config['debug']['db-num-queries'] AND isset($ko->db)){
			$html .= "<p>Database made" . $ko->db->GetNumQueries() . " number of queries.</p>";
		}
		if(isset($ko->config['debug']['db-queries']) AND $ko->config['debug']['db-queries'] AND isset($ko->db)){
			$html .= "<p>The following queries were made: </p>";
			$html .= "<pre>" . implode('<br>', $ko->db->GetQueries()) . "</pre>";
		}
		if(isset($ko->config['debug']['timer']) AND $ko->config['debug']['timer']){
			$html .= "<p>Page loaded in " . round(microtime(true) - $ko->timer['first'], 5)*1000 . " msecs.</p>";
		}
		if(isset($ko->config['debug']['konrad']) AND $ko->config['debug']['konrad']){
			$html .= "<hr><h3>Debuginformation</h3><p>Content of CKonrad:</p><pre>" . htmlent(print_r($ko, true)) . "</pre>";
		}
		if(isset($ko->config['debug']['session']) AND $ko->config['debug']['session']){
			$html .= "<hr><h3>SESSION</h3><p>Content of CKonrad->session:</p><pre>" . htmlent(print_r($ko->session, true)) . "</pre>";
			$html .= "<p>Content of \$_SESSION:</p><pre>" . htmlent(print_r($_SESSION, true)) . "</pre>";
		}
	}
	return $html;
}

/**
 * Function render_views
 * Wrapper function for CKonrad::Instance()-views->Render($region)
 * Parameters: region
 * Returns: the rendered region
 */
function render_views($region='default'){
	return CKonrad::Instance()->views->Render($region);
}

/**
 * Function get_messages_from_session
 * Wrapper fuinction to get messagtes from session
 * Returns: html
 */
function get_messages_from_session(){
	$messages = CKonrad::Instance()->session->GetMessages();
	$html = null;
	if(!empty($messages)){
		foreach($messages as $message){
			//$valid = array("info", "notice", "success", "warning", "error", "alert");
			//$class = (in_array($message['type'], $valid)) ? $message['type'] : "info";
			$html .= $message['message'];
			//echo "<div class='{$class}'>{$message['message']}</div>\n";
		}
	}
	return $html;		
}
	
	
/**
 * Funciton get_gravatar
 * Fetches a users avatar based on the user's email, aka Gravatar.
 * Parameters: size
 * Returns: url to gravatar
 */
function get_gravatar($size=null){
	return 'http://www.gravatar.com/avatar/' . md5(strtolower(trim(CKonrad::Instance()->user['email']))) . '.jpg?' . ($size ? "s=$size" : null);
}

/**
 * Function login_menu
 * Login menu to show if logged in or not
 */
function login_menu(){
	$ko=CKonrad::Instance();
	
	if($ko->user['isAuthenticated']){
		$items="<a href='" . create_url('user/profile') . "'><img class='gravatar' src='" . get_gravatar(20) . "' alt=''> " . $ko->user['acronym'] . "</a> ";
		if($ko->user['hasRoleAdministrator']){
			$items .= "<a href='" . create_url('acp') . "'>acp</a> ";
		}
		$items .= "<a href='" . create_url('user/logout') . "'>logout</a> ";
	}else{
		$items = "<a href='" . create_url('user/login') . "'>login</a> ";
	}
	return "<nav id='function-login-menu'>{$items}</nav>";
}

/**
 * Function filter_data
 * Wrapper function for CMContent::Filter()
 * Used for fiiltering out html and script nasties.
 * Parameters: data, filter
 * Returns: filtered content
 */
function filter_data($data){
	return CMContent::Filter($data);
}

/**
 * Function region_has_content
 * Wrapper function to check if a region has content or not.
 */
function region_has_content($region='default' /*...*/){
	return CKonrad::Instance()->views->RegionHasView(func_get_args());
}

/**
 * Function tools
 * Returns an html string of links to the tools of the trade, as it were.
 */
function tools(){
	global $ko;
	return<<<EOD
<p>Tools: 
<a href="http://validator.w3.org/check/referer">html5</a> | 
<a href="http://jigsaw.w3.org/css-validator/check/referer?profile=css3">css3</a> | 
<a href="http://jigsaw.w3.org/css-validator/check/referer?profile=css21">css21</a> | 
<a href="http://validator.w3.org/unicorn/check?ucn_uri=referer&amp;ucn_task=conformance">unicorn</a> | 
<a href="http://validator.w3.org/checklink?uri={$ko->request->current_url}">links</a> | 
<a href="http://qa-dev.w3.org/i18n-checker/index?async=false&amp;docAddr={$ko->request->current_url}">i18n</a> | <a href="source.php">Källkod</a>
</p>
EOD;
}

function slogan(){
	$ko = CKonrad::Instance();
	return $ko->config['theme']['data']['slogan'];
}

function rrmdir($path)
{
  return is_file($path) ? @unlink($path) : array_map('rrmdir',glob($path.'/*'))==@rmdir($path);
}


?>