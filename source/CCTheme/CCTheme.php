<?php
// Author: Fredrik Åhman
// Course: PHPMVC @ BTH
// File: CCThemes.php
// Desc: Controller class to test themes

/**
 * Controller class CCTheme
 * Extends CObject
 * Implements IController
 */
class CCTheme extends CObject implements IController{

	// Member variables
	
	/**
	 * Construct
	 */
	public function __construct(){
		parent::__construct();
	}
 
	// Methods
	
	/**
	 * Public function Index.
	 * Implementing IController interface.
	 */
	public function Index(){
	
		$rc = new ReflectionClass(__CLASS__);
		$methods = $rc->getMethods(ReflectionMethod::IS_PUBLIC);
		$items = array();
		
			foreach($methods as $method){
				if($method->name != '__construct' && $method->name != '__destruct' && $method->name != 'Index'){
					$items[] = $this->request->controller . '/' . mb_strtolower($method->name);
				}
			}
			
			$this->views->SetTitle('Theme');
			$this->views->AddInclude(__DIR__ . '/index.tpl.php', array('theme_name'=>$this->config['theme']['name'], 'methods'=>$items));
		}
	
	/**
	 * Public function SomeRegions
	 * Add content into some regions.
	 */
	public function SomeRegions(){
		$this->views->SetTitle('Display contents in some regions');
		$this->views->AddString('The primary region', array(), 'primary');
		
		if(func_num_args()){
			foreach(func_get_args() as $val){
				$this->views->AddString("This is region: $val", array(), $val);
				$this->views->AddStyle('#'.$val.'{background-color:hsla(0,0%,90%,0.5);}');
			}
		}
	}
	
	/**
	 * Public function AllRegions
	 * Add content to all regions
	 */
	public function AllRegions(){
		$this->views->SetTitle('Display contents in all regions, to show all regions');
		foreach($this->config['theme']['regions'] as $region){
			$this->views->AddString("This is region: {$region}", array(), $region);
			$this->views->AddStyle('#' . $region . '{background-color:hsla(0,0%,90%,0.5);}');
		}
  }
  	
  	/**
  	 * Public function H1H6
  	 * Nifty method to add a bunch of ipsum.
  	 */
	public function H1H6(){
		$this->views->SetTitle('Ipsum');
		$this->views->AddInclude(__DIR__ . '/h1h6.tpl.php', array(), 'primary');
	}
}
?>