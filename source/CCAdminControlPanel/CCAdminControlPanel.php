<?php
// Author: Fredrik Åhman
// Course: PHPMVC @ BTH
// File: CCAdminControlPanel.php
// Desc: Administrator's control panel för administrative management


class CCAdminControlPanel extends CObject implements IController{
	
	// Member variables
	
	/**
	 * Construct destruct
	 */
	public function __construct(){
		parent::__construct();
	}	
	  
	// Methods
	
	/**
	 * Public function Index
	 * Shows user profile information
	 */
	public function Index(){
		
	  	$this->views->SetTitle("Administrator's Control Panel");
	  	if(!$this->user['isAuthenticated']){
	  		$this->RedirectTo('user', 'login');
	  	}else{
	  		$result = new CMUser();
	  		$this->views->AddInclude(__DIR__ . "/index.tpl.php", array('is_authenticated'=>$this->user['isAuthenticated'], 'user'=>$this->user, 'users'=>$result->FetchAllUsers()), 'primary');
	  		$this->views->AddInclude(__DIR__ . '/sidebar.tpl.php', array('user'=>$this->user), 'sidebar');
	  	}
	}
	
	/**
	 * Public function ManageUser.
	 * Used for viewing and editing the user profile
	 */
	public function ManageUser($acronym) {
		$result = new CMUser();
		$user=$result->FetchUser($acronym);
		
		$form = new CFormUserManage($this, $user);
				
		if($form->Check() === false){
			$this->AddMessage("notice", "One or more fields did not validate to process form.");
			$this->RedirectToController("profile");
		}
		
		$this->views->SetTitle("Manage User");
		$this->views->AddInclude(__DIR__ . "/user.tpl.php",
			array("user"=>$user, "manage_form"=>$form->GetHTML()));
	}
	
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
	
	public function DoDeleteUser($form){
		if(!$this->user['isAuthenticated']){
			$this->RedirectToController('login');
		}elseif($this->user['isAuthenticated'] AND $this->user['acronym'] != 'root'){
			$this->RedirectToController('login');
		}else{
			$object = new CMUser();
			$result=null;
    		$result = $object->DeleteUser($form['acronym']['value']);
    		deleteUserDir("site/users/", $form['acronym']['value']); // function found in ckonrad/bootsrap.
    		$this->AddMessage('success', "User {$form['acronym']['value']} was deleted.", "Failed to delete user.");
		}
			$this->RedirectToController();	
	}
	
	
	public function DoChangePassword($form){
		if(!$this->user['isAuthenticated']){
			$this->RedirectToController('login');
		}elseif($this->user['isAuthenticated'] AND $this->user['acronym'] != 'root'){
			$this->RedirectToController('login');
		}else{
			if($form['password']['value'] != $form['password1']['value'] OR empty($form['password']['value']) OR empty($form['password1']['value'])){
			$this->AddMessage("error", "Password not match");
			$this->RedirectToController("admin/manageuser/{$form['acronym']['value']}");
			}else{
				$result=$this->user->ChangePassword($form['password']['value'], $form['acronym']['value']);
				$this->AddMessage($result, "You're password was changed.", "Failed to change password.");
			}
			$this->RedirectToController();	
		}
	}
	
	public function UserConfig($acronym=null){
		$url=CKonrad::Instance()->request->base_url . "site/site.config";
		if(!$this->user['isAuthenticated']){
			$this->RedirectToController('user/login');
		}elseif(strcmp($this->user['acronym'], $acronym) == 0){
			$file_content="";
			$file_content= file_get_contents($url);
			echo $file_content;			
			//$form = new CFormConfig($this, "TESTESTESTESTEST");			
	//		$this->views->SetTitle("User's site config");
//			$this->views->AddInclude(__DIR__ . "/config.tpl.php", array('contents'=>$file_content, 'form'=>$form->GetHTML()));
		}else{
			echo CKonrad::Instance()->request->base_url;
			//print_r(scandir("site/users/{$acronym}"));
			//$this->RedirectToController();
		}	
	}
	
		/**
	 * Public function DoCreate
	 * Callback function to process create user account form
	 * Parameters: form
	 */
	public function DoCreate($form){
		$object = new CMUser();
		if($form['password']['value'] != $form['password1']['value'] OR empty($form['password']['value']) OR empty($form['password1']['value'])){
			$this->AddMessage("error", "Wrong password.");
			$this->RedirectToController("create");
		}elseif($object->Create($form['acronym']['value'], $form['password']['value'], $form['name']['value'], $form['email']['value'])){
			$this->AddMessage("success", "You've successfully created a new account for: {$form['acronym']['value']}.");
			$this->RedirectToController();
		}else{
			$this->AddMessage('notice', "Failed to create account.");
			$this->RedirectToController('create');
		}
	}
	
	
	public function DoConfigSave(){
		;
	}
	
}
?>