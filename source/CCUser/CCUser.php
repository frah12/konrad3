<?php
// Author: Fredrik Åhman
// Course: PHPMVC @ BTH
// File: CCUser.php
// Desc: A controller to handle user login, view, and edit profile.

/**
 * Controller class CCUser
 * extends CObject and implements IController
 */

class CCUser extends CObject implements IController {

	// Member variables

	/**
	 * Construct
	 */
	public function __construct() {
		parent::__construct();
	}

	// Methods

	/**
	 * Public function Index. Implements IController interface.
	 * Shows user profile information.
	 */
	public function Index() {
		$this->views->SetTitle("User index");
		$this->views->AddInclude(__DIR__ . "/index.tpl.php", array("is_authenticated"=>$this->user['isAuthenticated'], "user"=>$this->user));
	}

	/**
	 * Public function Login.
	 * The loginform with callback
	 */
	public function Login() {
		$form = new CFormUserLogin($this);
		if($form->Check() === false) {
			$this->AddMessage("notice", "One or more entries did not validate.");
			$this->RedirectToController('login');
		}
		$this->views->SetTitle("Login");
        $this->views->AddInclude(__DIR__ . "/login.tpl.php", array("login_form" =>$form, "allow_create_user"=>CKonrad::Instance()->config['create_new_users'], "create_user_url"=>$this->CreateUrl(null, "create"))); 
	}
	
	/**
	 * Public function DoLogin
	 * Callback function to process loginform details.
	 * Parameters: form.
	 */
	public function DoLogin($form) {
		if($this->user->Login($form['acronym']['value'], $form['password']['value'])){
			$this->AddMessage("success", "Welcome {$this->user['name']}.");
			$this->RedirectToController('profile');
		}else{
			$this->AddMessage("notice", "Failed to login. User acronym or e-mail or password didn't match.");
			$this->RedirectToController("login");
		}
	}
	
	/**
	 * Public function Logout.
	 * Logout function.
	 */
	public function Logout() {
		$this->user->Logout();
		$this->RedirectToController();
	}
	
	/**
	 * Public function Init
	 * Initialize the user database by this->user->init().
	 */
	public function Init() {
		$this->user->Manage('install');
		$this->RedirectToController();
	}
	
	/**
	 * Public function Profile.
	 * Used for viewing and editing the user profile
	 */
	public function Profile() {
		$form = new CFormUserProfile($this, $this->user);
				
		if($form->Check() === false){
			$this->AddMessage("notice", "One or more fields did not validate to process form.");
			$this->RedirectToController("profile");
		}
		
		$this->views->SetTitle("User Profile");
		$this->views->AddInclude(__DIR__ . "/profile.tpl.php",
			array("is_authenticated"=>$this->user["isAuthenticated"],
			"user"=>$this->user, "profile_form"=>$form->GetHTML()));
	}
	
	/**
	 * Public function DoChangePassword
	 * Callback function for processing change password form
	 * Parameters: form
	 */
	public function DoChangePassword($form){
		if($form['password']['value'] != $form['password1']['value'] OR empty($form['password']['value']) OR empty($form['password1']['value'])){
		$this->AddMessage("error", "Password not match");
		}else{
			$ret=$this->user->ChangePassword($form['password']['value']);
			$this->AddMessage($ret, "You're password was changed.", "Failed to change password.");
		}
		$this->RedirectToController("profile");
  }	

	/**
	 * Public function DoProfileSave
	 * Callback function for processing user profile form
	 * Parameters: form
	 */
	public function DoProfileSave($form) {
		$this->user['name']=$form['name']['value'];
		$this->user['email']=$form['email']['value'];
		$ret=$this->user->Save();
		$this->AddMessage($ret, "Profile updated.", "Failed to update profile.");
		$this->RedirectToController("profile");
	}
  
	/**
	 * Public function Create
	 * Create new user form
	 */
	public function Create(){
		if(!$this->user['isAuthenticated']){
			$this->RedirectToController('login');
		}elseif($this->user['isAuthenticated'] AND $this->user['acronym'] != 'root'){
			$this->RedirectToController('login');
		}else{
			$form = new CFormUserCreate($this);
			if($form->Check() === false){
				$this->AddMessage("notice", "Please: input all fields.");
				$this->RedirectToController("Create");
			}
		
			$this->views->SetTitle("Create user");
			$this->views->AddInclude(__DIR__ . "/create.tpl.php", array("form"=>$form->GetHTML()));
		}
	}


	/**
	 * Public function DoCreate
	 * Callback function to process create user account form
	 * Parameters: form
	 */
	public function DoCreate($form){
		if($form['password']['value'] != $form['password1']['value'] OR empty($form['password']['value']) OR empty($form['password1']['value'])){
			$this->AddMessage("error", "Wrong password.");
			$this->RedirectToController("create");
		}elseif($this->user->Create($form['acronym']['value'], $form['password']['value'], $form['name']['value'], $form['email']['value'])){
			$this->AddMessage("success", "You've successfully created a new account: {$form['acronym']['value']}.");
			$this->user->Login($form['acronym']['value'], $form['password']['value']);
			$this->RedirectToController('profile');
		}else{
			$this->AddMessage('notice', "Failed to create account.");
			$this->RedirectToController('create');
		}
	}
	
	/**
	 * Public function DoCreate
	 * Function to delete all the users and their directories
	 * Parameters:
	 */

} 
?>