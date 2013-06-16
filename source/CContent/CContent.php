<?php
// Author: Fredrik Åhman
// Course: PHPMVC @ BTH
// File: CContent.php
// Desc: A controller to manage content. Create, edit, list, save, and delete.

class CContent extends CObject implements IController{

	// Member variables
	
	/**
	 * Construct Destruct
	 */
	public function __construct(){
		parent::__construct();
	}
 
	// Methods

	/**
	 * Public function Index
	 * List all content in the content database table.
	 */
	public function Index(){
		$content = new CMContent();
		$this->views->SetTitle('Content Controller Index');
		$this->views->AddInclude(__DIR__ . '/index.tpl.php', array('contents'=>$content->ListAll()));
    }
	
	/**
	 * Public function Init
	 * Initialize the content database.
	 */
	public function Init(){
		$content= new CMContent();
		$content->Manage('install');
		$this->RedirectToController();
	}    
    
     /**
     * Public function Edit
     * Edit selected contet or create new content if argument is missing
	 * Parameters: id
     */
	public function Edit($id=null) {
		$content = new CMContent($id);
		$form = new CFormContent($content);
		$status = $form->Check();
		
		if($status === false){
			$this->AddMessage('notice', 'The form could not be processed.');
			$this->RedirectToController('edit', $id);
		}elseif($status === true){
			$this->RedirectToController('edit', $content['id']);
		}
		
		$title = isset($id) ? 'Edit' : 'Create';
		$this->views->SetTitle("{$title} content: {$id}");
		$this->views->AddInclude(__DIR__ . '/edit.tpl.php', array(
			'user'=>$this->user,
			'contents'=>$content,
			'form'=>$form));
	}
   
    /**
     * Public function Create
     * Create new content
     */
     public function Create(){
     	$this->Edit();
     }
    
}
?>