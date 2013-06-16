<?php
// Author: Fredrik Åhman
// Course: PHPMVC @ BTH
// File: CCPersonalController.php
// Desc: User specific controller for testing


class CCPersonalController extends CObject implements IController{
	
	/**
	 * Membet variables
	 */
	
	/**
	 * Construct Destruct
	 */
	public function __construct(){
		parent::__construct();
		if(file_exists('site/site.config.php')){
   			require('site/site.config.php');
		}
	}
	
	/**
	 * Methods
	 */
	
	/**
	 * Implementing Index interface
	 */
	
	public function Index(){
		$contents= new CMContent();
		$this->views->SetTitle('About MyPage' . htmlentities($contents['title']));
		$this->views->AddInclude(__DIR__ . '/page.tpl.php', array('contents'=>$contents));
	}

	
	public function Blog(){
		$contents =new CMContent();
		$this->views->SetTitle('My blog');
		$this->views->AddInclude(__DIR__ . '/myblog.tpl.php', array(
			'contents'=>$contents->ListAll(array(
				'type'=>'post', 'order-by'=>'title', 'order-order'=>'DESC'))));
	}


	public function Guestbook(){
		$guestbook = new CMGuestbook();
		$form = new CFormMyGuestbook($guestbook);
		$status = $form->Check();
		if($status === false){
			$this->AddMessage('notice', 'The form could not be processed.');
			$this->RedirectToControllerMethod();
		}elseif($status === true){
			$this->RedirectToControllerMethod();
		}
		$this->views->SetTitle('My Guestbook');
		$this->views->AddInclude(__DIR__ . '/guestbook.tpl.php', array(
			'entries'=>$guestbook->ShowAll(),
			'form'=>$form));
	}

} // End class


class CFormMyGuestbook extends CForm{

	/**
	 * Member variables
	 */
	private $object;

	/**
	 * Construct Destruct
	 */
	public function __construct($object){
		parent::__construct();
		$this->object = $object;
		$this->AddElement(new CFormElementTextarea('data', array('label'=>'Kluddra:')));
		$this->AddElement(new CFormElementSubmit('add', array('callback'=>array($this, 'DoAdd'), 'callback-args'=>array($object))));
	}
	
	/**
	 * Methods
	 */
	
	public function DoAdd($form, $object){
		return $object->Add(strip_tags($form['data']['value']));
	}
} // End class
?>