<?php
// Author: Fredrik Åhman
// Course: PHPMVC @ BTH
// File: CFormBlog.php
// Desc: Controller to the content forms

/**
 * Class CFormBlog
 * Extends CForm
 * Creates the form for writing content.
 */
class CFormBlog extends CForm{
	
	// Member variables
	
	private $content;

	/**
	 * Construct
	 * Parameters: content
	 */
	 public function __construct($content){
		parent::__construct();
		$this->content = $content;
		$save = isset($content['id']) ? 'save' : 'create';
		$this->AddElement(new CFormElementHidden('id', array('value'=>$content['id'])));
		$this->AddElement(new CFormElementHidden('type', array('value'=>'post')));
		$this->AddElement(new CFormElementText('title', array('value'=>$content['title'])));
		$this->AddElement(new CFormElementText('key', array('value'=>$content['key'])));
		$this->AddElement(new CFormElementTextarea('data', array('label'=>'Content:', 'value'=>$content['data'])));
		$this->AddElement(new CFormElementSubmit($save, array('callback'=>array($this, 'DoSave'), 'callback-args'=>array($content))));
		if($save == 'save'){
			$this->AddElement(new CFormElementSubmit('delete', array('callback'=>array($this, 'DoDelete'), 'callback-args'=>array($content))));
		}
	
		$this->SetValidation('title', array('not_empty'));
		$this->SetValidation('key', array('not_empty'));
	 }
 
 	
 	// Methods
 	
 	/**
	 * Public function DoSave
	 * Callback function to save content to database
	 * Parameters: form, content
	 * Returns: content->Save()
	 */
	public function DoSave($form, $content) {
		$content['id']=$form['id']['value'];
		$content['title']=$form['title']['value'];
		$content['key']=$form['key']['value'];
		$content['data']=$form['data']['value'];
		$content['type']=$form['type']['value'];
		$content['filter']=$form['filter']['value'];
		
		return $content->Save();
	}
	
	/**
	 * Public function DoDelete
	 * Callback function to delete content from database
	 * Parameters: form, content
	 * Returns: content->Delete()
	 */
	public function DoDelete($form, $content) {
		$content['id']=$form['id']['value'];
				
		return $content->Delete();
	}
	
 
}
?>