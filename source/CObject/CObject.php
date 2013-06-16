<?php
// Author: Fredrik Åhman
// Course: PHPMVC @ BTH
// File: CObject.php
// Desc: To make common resources availble through $this.

/**
 * Controllera class CObject
 * Holds an instance of CKonrad to enable the use of $this in subclasses
 * Protected members are availble all out.
 */
 
class CObject{

	// Member variables
	
	protected $config;
	protected $request;
	protected $data;
	protected $db;
	protected $views;
	protected $session;
	protected $user;
	
	
	/**
	 * Construct
	 * Stores ko object data to equivalent this->*
	 * Parameters: ko (object)
	 */
	protected function __construct($ko=null){
		if(!$ko){
			$ko=CKonrad::Instance();
		}
		
		//$ko = CKonrad::Instance(); // remove lest loop eternal. 
		$this->config = &$ko->config;
		$this->request = &$ko->request;
		$this->data = &$ko->data;
		$this->db = &$ko->db;
		$this->views = &$ko->views;
		$this->session = &$ko->session;
		$this->user=&$ko->user;
	}
	
	
	// Methods
	
	/**
	 * Protectec function RedirectTo
	 * Redirect to "url", where url can be url or controller with or without method specified.
	 * Parameters: url, method
	 */	
	protected function RedirectTo($url=null, $method=null){
		$ko = CKonrad::Instance();
		if(isset($ko->config['debug']['db-num-queries']) AND $ko->config['debug']['db-num-queries'] AND isset($ko->db)) {
      		$this->session->SetFlash('database_numQueries', $this->db->GetNumQueries());
		}
		if(isset($ko->config['debug']['db-queries']) AND $ko->config['debug']['db-queries'] AND isset($ko->db)) {
      		$this->session->SetFlash('database_queries', $this->db->GetQueries());
		}
    	if(isset($ko->config['debug']['timer']) AND $ko->config['debug']['timer']) {
			$this->session->SetFlash('timer', $ko->timer);
		}
		$this->session->StoreInSession();
		header('Location: ' . $this->request->CreateUrl($url, $method));
	}
	
	/**
	 * Protected function RedirectToController
	 * Redirect within current controller.
	 * Parameters: method
	 */
	
	protected function RedirectToController($method=null){
		$this->RedirectTo($this->request->controller, $method);
	}
	
	
	/**
	 * Protected function AddMessage
	 * Sets message to session using $this->session->AddMessage
	 * Parameters: type, message, alternative
	 */
	protected function AddMessage($type, $message, $alternative=null){
		if($type === false){
		$type="error";
		$message=$alternative;
	}elseif($type === true){
		$type="success";
	}
    $this->session->AddMessage($type, $message);
  }

	/**
	 * Public function RedirectToControllerMethod
	 * Uses protected function RedirectTo
	 * Parameters: controller, method, arguments
	 */
	public function RedirectToControllerMethod($controller=null, $method=null, $arguments=null){
		$controller = is_null($controller) ? $this->request->controller : null;
		$method = is_null($method) ? $this->request->method : null;
		$this->RedirectTo($this->request->CreateUrl($controller, $method, $arguments));
	}


	/**
	 * Protected function CreateUrl
	 * Wrapper for controller class CRequest method CreateUrl, where Url can be controller
	 * Parameters: url, method, arguments
	 */
	protected function CreateUrl($url=null, $method=null, $arguments=null){
		return $this->request->CreateUrl($url, $method, $arguments);
	}
	
}
?>