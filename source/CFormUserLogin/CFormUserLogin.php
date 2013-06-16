<?php
// Author: Fredrik Åhman
// Course: PHPMVC @ BTH
// File: CFormUserLogin.php
// Desc: Put out the login form

/**
 * Class CFormUserLogin
 * Extends CForm
 * Creates the login form.
 */
class CFormUserLogin extends CForm{

	// Member variables
	
	/**
	 * Construct
	 * Parameters: object (object)
	 */
	  public function __construct($object){
	  	parent::__construct();
	  	
	  	$this->AddElement(new CFormElementText("acronym"));
	  	$this->AddElement(new CFormElementPassword("password"));
	 	$this->AddElement(new CFormElementSubmit("login", array("callback"=>array($object, "DoLogin"))));
	  	
	  	$this->SetValidation("acronym", array("not_empty"));
	  	$this->SetValidation("password", array("not_empty"));
	  }  
}
?>