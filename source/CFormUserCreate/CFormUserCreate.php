<?php
// Author: Fredrik Åhman
// Course: PHPMVC @ BTH
// File: CFormUserCreate.php
// Desc: Put out the form for createing a user account

/**
 * Class CFormUserCreate
 * Extends CForm
 * Creates the user form used for creating new user
 */

class CFormUserCreate extends CForm{
	
	// Member variables
	
	/**
	 * Construct
	 * Parameters: object
	 */
	public function __construct($object){
	  	parent::__construct();
	  	
	  	$this->AddElement(new CFormElementText("acronym", array("required"=>true)));
	  	$this->AddElement(new CFormElementPassword("password", array("required"=>true)));
	  	$this->AddElement(new CFormElementPassword("password1", array("required"=>true, "label"=>"Password again:")));
	  	$this->AddElement(new CFormElementText("name", array("required"=>true)));
	  	$this->AddElement(new CFormElementText("email", array("required"=>true)));
	  	$this->AddElement(new CFormElementSubmit("create", array("callback"=>array($object, "DoCreate"))));
	  	
	  	$this->SetValidation("acronym", array("not_empty"));
	  	$this->SetValidation("password", array("not_empty"));
	  	$this->SetValidation("password1", array("not_empty"));
	  	$this->SetValidation("name", array("not_empty"));
	  	$this->SetValidation("email", array("not_empty"));
	  }  
}
?>