<?php
// Author: Fredrik Åhman
// Course: PHPMVC @ BTH
// File: CRequest.php
// Desc: Parse an url into controller, method, and parameters, for Konrad

/**
 * Controller class CRequest
 * Takes care of managing urls
 */

class CRequest{

	// Member variables
	
	public $cleanUrl;
	public $querystringUrl;
	
	/**
	 * Construct
	 * defualt = 0				=> index.php/controller/method/arg1/arg2/...
	 * clean = 1				=> controller/method/arg1/arg2/...
	 * querystring = 2		=> index.php?q=controller/method/arg1/arg2/...
	 * Parameter: urlType
	 */
	
	public function __construct($urlType=0){
		//echo $urlType . "<br>";
		$this->cleanUrl = $urlType = 1 ? true : false;
		$this->querystringUrl = $urlType = 2 ? true : false;
	}
	
	// Methods

	/**
	 * Public function Init.
	 * Parses current url request and stores result
	 * Parameters: baseUrl, routing
	 */
	public function Init($baseUrl = null, $routing=null){
		
		$requestUri = $_SERVER['REQUEST_URI'];
		$scriptName = $_SERVER['SCRIPT_NAME'];
		
		// compare URI with NAME. If match leave as current request
		$i=0;
		$len=min(strlen($requestUri), strlen($scriptName));
		while($i<$len AND $requestUri[$i] == $scriptName[$i]){
			$i++;
		}
		
		$request=trim(substr($requestUri, $i), '/');
		
		// everything after base_url is query, cept optional querystring
		$pos=strpos($request, '?');
		
		if($pos !== false){
			$request=substr($request, 0, $pos);
		}
		
		if(empty($request) AND isset($_GET['q'])){
			$request=trim($_GET['q']);
		}
		
		$routed_from=null; // Is used by menu method in ckonrad to remember the choosen option
		if(is_array($routing) AND isset($routing[$request]) AND $routing[$request]['enabled']){
			$routed_from=$request; // so routed_from contains the correct link
			$request=$routing[$request]['url'];
		}
				
		$splits=explode('/', $request);
		
		// Set controller, method, and argument
		$controller=!empty($splits[0]) ? $splits[0] : 'index';
		$method=!empty($splits[1]) ? $splits[1] : 'index';
		$arguments=$splits;
		
		// unset because they've been used and contain controller and method parts
		unset($arguments[0], $arguments[1]);
		
		// Prepare to create current_url and base_url
		$currentUrl = $this->GetCurrentUrl();
		
		$parts = parse_url($currentUrl); // gets the scheme, host, user, pass, path, query parts of url into an assoc array.
		$baseUrl = !empty($baseUrl) ? $baseUrl : "{$parts['scheme']}://{$parts['host']}" . (isset($parts['port']) ? ":{$parts['port']}" : '') . rtrim(dirname($scriptName), '/');
				
				
		// Store it
		$this->base_url = rtrim($baseUrl, '/') . '/';
		$this->current_url = $currentUrl;
		$this->request_uri = $requestUri;
		$this->script_name = $scriptName;
		$this->splits = $splits;
		$this->controller = $controller;
		$this->method = $method;
		$this->arguments = $arguments;
		$this->request=$request;
		$this->routed_from=$routed_from;
	
	}
	
	/**
	 * Public function GetCurrentUrl
	 * Gets the url to current page. This is a common method of doing it, so remember.
	 * Returns: url.
	 */
	public function GetCurrentUrl(){
		$url = "http";
		$url .= (@$_SERVER["HTTPS"] == "on") ? 's' : ''; // checks if https is on if so adds an s to http
		$url .= "://";
		$serverPort = ($_SERVER['SERVER_PORT'] == '80') ? '' : (($_SERVER['SERVER_PORT'] == 443 && @$_SERVER['HTTPS'] == 'on') ? '' : ":{$_SERVER['SERVER_PORT']}");
		$url .= $_SERVER['SERVER_NAME'] . $serverPort . htmlspecialchars($_SERVER['REQUEST_URI']); // converts special character to html specific
		
		return $url;
		
	}
	
	/**
	 * Public function CreateUrl
	 * Create the prefered url type
	 * Parameters: url, method, arguments
	 * Return created url
	 */
	public function CreateUrl($url=null, $method=null, $arguments=null){
		// Leave if alrigth
		if(!empty($url) AND (strpos($url, "://") OR $url[0] == "/")){
			return $url;
		}
		
		// if empty get controller
		if(empty($url) AND (!empty($method) OR !empty($arguments))){
			$url = $this->controller;
		}
		
		
		// if empty get method
		if(empty($method) AND !empty($arguments)){
			$method= $this->method;
		}
		
		
		// Make url by other styles
		
		$prepend = $this->base_url;
		if($this->cleanUrl){
			;
		} elseif($this->querystringUrl){
			$prepend .= "index.php?q=";
		}else{
			$prepend .= "index.php/";
		}
		
		$method = empty($method) ? null : "/" . trim($method, '/');
		$url = trim($url, '/');
		$arguments = empty($arguments) ? null : "/" . trim($arguments, '/');
		
		return $prepend . rtrim("{$url}{$method}{$arguments}", '/');
	}

}
?>