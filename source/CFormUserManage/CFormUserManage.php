<?php
// Author: Fredrik Åhman
// Course: PHPMVC @ BTH
// File: CFormUserManage.php
// Desc: For administrator to manage user account

/**
 * Class CFormUserManage
 * Extends CForm
 * Creates the user profile form
 */

class CFormUserManage extends CForm{
	
	// Member variables
	
	/**
	 * Construct
	 * Parameters: object (object), user
	 */
	 public function __construct($object, $user){
		parent::__construct();
	 	
		$this->AddElement(new CFormElementText('acronym', array('readonly'=>true, 'value'=>$user['acronym'])));
	  	$this->AddElement(new CFormElementPassword('password'));
	  	$this->AddElement(new CFormElementPassword('password1', array('label'=>'Password again:')));
	  	$this->AddElement(new CFormElementSubmit('change_password', array('callback'=>array($object, 'DoChangePassword'))));
	  	
	  	$this->AddElement(new CFormElementText('name', array('readonly'=>true, 'value'=>$user['name'])));
	  	$this->AddElement(new CFormElementText('email', array('readonly'=>true, 'value'=>$user['email'])));
	  	$this->AddElement(new CFormElementSubmit('delete', array("callback"=>array($object, 'DoDeleteUser'))));
	  }  
}
?>