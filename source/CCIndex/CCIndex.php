<?php
// Author: Fredrik Åhman
// Course: PHPMVC @ BTH
// File: CCIndex.php
// Desc: Standard controller layout

/**
 * Controller class CCIndex
 * Extends CObject
 * Implements IController
 */
class CCIndex extends CObject implements IController{

	// Member variables
	
	/**
	 * Construct
	 */
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * Public function Index
	 * Implementing interface IController.
	 */
	public function Index(){
		$this->views->SetTitle('Index');
		$this->views->AddInclude(__DIR__ . '/index.tpl.php', array(), 'primary');
		$this->views->AddInclude(__DIR__ . '/sidebar.tpl.php', array(), 'sidebar');
	}
}
?>