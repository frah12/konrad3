<?php
// Author: Fredrik Åhman
// Course: PHPMVC @ BTH
// File: CFormConfig.php
// Desc: View and edit user profile form

/**
 * Class CFormConfig
 * Extends CForm
 * Used to install and change site config
 */

class CFormConfig extends CForm{
	
	// Member variables
	
	/**
	 * Construct
	 * Parameters: config (array)
	 */
	 public function __construct($object, $content=null){
		parent::__construct();
	 	
		$this->AddElement(new CFormElementTextArea("title", array('value'=>$content)));
	  	$this->AddElement(new CFormElementSubmit("save", array("callback"=>array($object, "DoConfigSave"))));
	  }  
}
?>