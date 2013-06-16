<?php
// Author: Fredrik Åhman
// Course: PHPMVC @ BTH
// File: CKonrad.php
// Desc: Main Class for my lydia based framework

/**
 * Main class CKonrad for this lydia based framwork
 * Implements ISingleton
 */
 
class CKonrad implements ISingleton{
	
	// Member variables
	private static $instance=null;
	public $config = array();
	public $request;
	public $data;
	public $db;
	public $views;
	public $session;
	public $timer = array();
	public $user;
	
	
	/**
	 * Construct
	 * Creates reference to $ko to be used directly in site/config.php
	 */
	protected function __construct(){
		// use site specific config.php and create areference to $ko to be used by config.php
		$ko=&$this; // Makes $ko usable directly in config.php
		
		require(KONRAD_SITE_PATH . '/config.php');
		
		// Create DB object
		if(isset($this->config['database'][0]['dsn'])){
			$this->db = new CDatabase($this->config['database'][0]['dsn']);
		}
		
		// Date time
		date_default_timezone_set($this->config['timezone']);
		
		// Start a named session
		session_name($this->config['session_name']);
		session_start();
		$this->session = new CSession($this->config['session_key']);
		$this->session->PopulateFromSession();
		
		$this->views = new CViewContainer();
		
		// User object
		$this->user=new CMUser($this);
	}
	
	/**
	 * Public static function Instancs to implement singleton pattern.
	 * Returns: self::$instance
	 */
	public static function Instance(){
		if(self::$instance==null){
			self::$instance = new CKonrad();
		}
		return self::$instance;
	}
	
	// Methods
	
	/**
	 * Public function FronControllerRoute
	 * Directs the sites traffic to proper controller
	 */
	public function FrontControllerRoute(){
	
		// 1 mush current url into controller, method, and parameters
		$this->request = new CRequest($this->config['url_type']);
		$this->request->Init($this->config['base_url'], $this->config['routing']);
		
		$controller=$this->request->controller;
		$method=$this->request->method;
		$arguments=$this->request->arguments;
		
			// Controller enabled in config?
			$controllerExists=isset($this->config['controllers'][$controller]);
			$controllerEnabled=false;
			$className=false;
			$classExists=false;
			
			if($controllerExists){
				$controllerEnabled=($this->config['controllers'][$controller]['enabled'] == true);
				$className=$this->config['controllers'][$controller]['class'];
				$classExists = class_exists($className);
			}
			

		// 2 is there such methods in the controller class
		if($controllerExists AND $controllerEnabled AND $classExists){
			$rc = new ReflectionClass($className);
			
			if($rc->implementsInterface('IController')){
				$formattedMethod = str_replace(array('_', '-'), '', $method);
				
				if($rc->hasMethod($formattedMethod)){
					$controllerObj = $rc->newInstance();
					$methodObj = $rc->getMethod($formattedMethod);
					if($methodObj->isPublic()){
						$methodObj->invokeArgs($controllerObj, $arguments);
					} else {
						die("<br>404. " . get_class() . " error: Controller method not public.");
					}
				} else {
					die('<br>404. ' . get_class() . 'error, controller does not contain method.');
				}
			} else {
				die('<br>404. ' . get_class() . 'error, controller does not implement IController.');
			}
		} else {
				die('<br>404. Sorry. Page was not found.');
		}
	
		//$this->data['debug'] = "REQUEST_URI : {$_SERVER['REQUEST_URI']}\n";
		//$this->data['debug'] .= "SCRIPTE_NAME : {$_SERVER['SCRIPT_NAME']}\n";
	}
	
	/**
	 * Public function ThemeEngineRender
	 * Renders site with the selected theme that is configured in site/config.php
	 */
	Public function ThemeEngineRender(){
		 // Save to session first
		 $this->session->StoreInSession();
		 
		// Check if theme's enabled, if it is then bail.
		if(!isset($this->config['theme'])){
			return;
		}
		
		// Get path and setting for theme.
		$themeUrl=$this->request->base_url . $this->config['theme']['path'];
		$themePath=KONRAD_INSTALL_PATH . "/" . $this->config['theme']['path'];
		
		
		// Should parent theme be inherited
		$parentPath=null;
		$parentUrl=null;
		if(isset($this->config['theme']['parent'])){
			$parentPath=KONRAD_INSTALL_PATH . '/' . $this->config['theme']['parent'];
			$parentUrl=$this->request->base_url . $this->config['theme']['parent'];
		}
		
		// Add stylesheet path to $ko->data array.
		$this->data['stylesheet'] = $this->config['theme']['stylesheet'];
		
		// Make theme urls available to $ko
		$this->themeUrl=$themeUrl;
		$this->themeParentUrl=$parentUrl;
		
		// if menu is defined map it
		if(is_array($this->config['theme']['menu_to_region'])){
			foreach($this->config['theme']['menu_to_region'] as $option => $choice){
				$this->views->AddString($this->DrawMenu($option), array(), $choice);
			}
		}
		
		
		// Include the global functions.php and the functions.php that are part of the theme
		$ko = &$this;
		include(KONRAD_INSTALL_PATH . "/themes/functions.php");
		
		// functions.php from parent theme
		if($parentPath){
			if(is_file("{$parentPath}/functions.php")){
				include "{$parentPath}/functions.php";
			}
		}
		// current theme functions.php
		if(is_file("{$themePath}/functions.php")){
			include "{$themePath}/functions.php";
		}
		
		// Extract $ko->data to each variable and hand over to template file.
		extract($this->data);
		//print_r($this->data);
		extract($this->views->GetData());
		// extract any theme specific static data
		if(isset($this->config['theme']['data'])){
			extract($this->config['theme']['data']);
		}
		// if template file is configured use it otherwise use default
		$templateFile=(isset($this->config['theme']['template_file'])) ? $this->config['theme']['template_file'] : 'default.tpl.php';
				
		if(is_file("{$themePath}/{$templateFile}")){
			include("{$themePath}/{$templateFile}");
		}elseif(is_file("{$parentPath}/{$templateFile}")){
			include("{$parentPath}/{$templateFile}");
		}else{
			throw new Exception('No such template file.');
		}
			
	}
	
	/**
	 * Public function DrawMenu
	 * Draws the menu array submitted.
	 *
	 * Parameter: menu
	 */
	public function DrawMenu($menu){
		$items = null;
		if(isset($this->config['menus'][$menu])){
			foreach($this->config['menus'][$menu] as $option){
				$selected = null;
				if($option['url'] == $this->request->request || $option['url'] == $this->request->routed_from){
					$selected=" class='selected'";
				}
				$items .= "<li><a {$selected} href='" . $this->request->CreateUrl($option['url']) . "'>{$option['label']}</a></li>\n";
			}
		}else{
			throw new Exception('No such menu.');
		}
		
		return "<ul class='menu {$menu}'>\n{$items}</ul>\n";
	}
	
} // End class
?>