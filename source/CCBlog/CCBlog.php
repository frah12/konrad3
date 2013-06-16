<?php
// Author: Fredrik Åhman
// Course: PHPMVC @ BTH
// File: CCBlog.php
// Desc: A controller clas for a blog

/**
 * Controller class Blog. Used to display content by post.
 */

class CCBlog extends CObject implements IController{

	// Member variables
	
	/**
	 * Construct
	 */
	public function __construct(){
		parent::__construct();
	}
	
	// Methods 
	
	/**
	 * Public function Index. Implements IController
	 * Display all content of post-type.
	 * Parameters: $controller=null, $method=null, $arguments=null)
	 */
	public function Index(){
		$this->RedirectToController('user');
 	}
 	
 	/**
     * Public function Edit
     * Edit selected contet or create new content if argument is missing
	 * Parameters: id
     */
	public function Edit($id=null) {
		if(!$this->user['isAuthenticated']){
			$this->RedirectToController('../user/login');
		}else{
			$content = new CMContent($id);
			$form = new CFormBlog($content);
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
	}
   
    /**
     * Public function Create
     * Create new content. Wrapper method for Edit
     */
     public function Create(){
     	$this->Edit();
     }
 	
 	public function User($acronym=null){
 		 	if($acronym){
				$user_object=new CMUser();
				$user= $user_object->FetchUser($acronym);
				$content = new CMContent();
							
				$this->views->SetTitle("{$acronym}'s Blog");
				$this->views->AddInclude(__DIR__ . '/index.tpl.php', array('contents'=>$content->ListAll(array('id'=>$user['id'], 'order-order'=>'DESC'), 'primary')));
		}else{
			$content = new CMContent();
			$this->views->SetTitle('The Blog');
			$this->views->AddInclude(__DIR__ . '/index.tpl.php', array('contents'=>$content->ListAll(array('type'=>'post', 'order-by'=>'title', 'order-order'=>'DESC'))), 'primary');
		}
		//echo $acronym, " : ", $this->user['acronym'];
		if(isset($this->user['isAuthenticated'])){
			if(strcmp($this->user['acronym'], "{$acronym}") == 0){
				$this->views->AddInclude(__DIR__ . '/sidebar.tpl.php', array(), 'sidebar');
			}
		}
		if(isset($this->user['isAuthenticated'])){
			if(strcmp($this->user['acronym'], 'root') == 0){
				$this->views->AddInclude(__DIR__ . '/sidebar.tpl.php', array(), 'sidebar');
			}
		}
 	}
 	
}
?>