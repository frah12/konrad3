<?php
// Author: Fredrik Åhman
// Course: PHPMVC @ BTH
// File: CSession.php
// Desc: An API for SESSION

/**
 * Controller class CSession
 */

class CSession{

	// Member variables
	
	private $key;
	private $data=array();
	private $flash=null;
	
	/**
	 * Construct
	 * Sets this->key
	 * Parameters: key
	 */
	public function __construct($key){
		$this->key=$key;
	}
	
	// Methods
		 
	/**
	 * Public function __set
	 * Set key=>value to session data array
	 * Parameters: key, value
	 */
	public function __set($key, $value){
		$this->data[$key]=$value;
	}
	
	/**
	 * Public function __get
	 * Get key=>value from session data array
	 * Parameters: key
	 * Return: key's value if it is set
	 */
	public function __get($key){
		return isset($this->data[$key]) ? $this->data[$key] : null;
	}
	
	/**
	 * Public function SetFlash
	 * Set this->data['flash'][key] value
	 * Parameters: key, value
	 */
	public function SetFlash($key, $value){
		$this->data['flash'][$key]=$value;
	}
	
	/**
	 * Public function GetFlash
	 * Get value from flash key
	 * Parameters: key
	 */
	public function GetFlash($key){
		return isset($this->flash[$key]) ? $this->flash[$key] : null;
	}
	
	/**
	 * Public function StoreInSession
	 * Stores this->data in $_SESSION[this->key]
	 */
	public function StoreInSession(){
		$_SESSION[$this->key] = $this->data;
	}
	
	/**
	 * Public function AddMessage
	 * Adds message to this->data[flash][] array by type
	 * Parameters: type, message
	 */
	public function AddMessage($type, $message){
		$this->data['flash']['message'][] = array('type'=>$type, 'message'=>$message);
	}
	
	/**
	 * Public function GetMessages
	 * Return flash[message] array
	 */
	public function GetMessages(){
		return isset($this->flash['message']) ? $this->flash['message'] : null;
	}
	
	/**
	 * Public function PopulateFromSession
	 * Populates this->data and possibly this->flash arrays from Session
	 */
	public function PopulateFromSession(){
		if(isset($_SESSION[$this->key])){
			$this->data=$_SESSION[$this->key];
			if(isset($this->data['flash'])){
				$this->flash=$this->data['flash'];
				unset($this->data['flash']);
			}
		}
	}
	
	/**
	 * Public function SetAuthenticatedUser
	 * Sets data[authenticated_user] with profile
	 * Parameters: profile
	 */
	public function SetAuthenticatedUser($profile){
		$this->data['authenticated_user'] = $profile;
	}
	
	/**
	 * Public function UnsetAuthenticatedUser
	 * Unsets data[authenticated_user]
	 */
	public function UnsetAuthenticatedUser(){
		unset($this->data['authenticated_user']);
	}
	
	/**
	 * Public function GetAuthenticatedUser
	 * Returns this->authenticated_user
	 */
	public function GetAuthenticatedUser(){
		return $this->authenticated_user;
	}
}
?>