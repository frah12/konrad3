<?php
// Author: Fredrik Åhman
// Course: PHPMVC @ BTH
// File: CViewContainer.php
// Desc: Container for different views

/**
 * Controller class CViewContainer
 * Creates/Renders the site's view, and can even check i a internal layout region has a view.
 */


class CViewContainer{

	// Member variables
	private $data=array();
	private $views=array();
	

	// Construct Destruct
	public function __construct(){
		;
	}
	
	// Methods
	
	/**
	 * Public function GetData
	 * return $this->data
	 */
	public function GetData(){
		return $this->data;
	}
	
	/**
	 * Public function SetTitle
	 * Uses SetVariable to set title on specified value
	 * Parameters: value
	 */
	public function SetTitle($value){
		$this->SetVariable('title', $value);
	}
	
	/**
	 * Public function SetVariable
	 * Set variables that ought to ba available for theme engine
	 * Parameters: key, value
	 * Sets value for $this->data[$key]
	 */
	public function SetVariable($key, $value){
		$this->data[$key]=$value;
	}
	
	/**
	 * Public function AddInclude.
	 * Sets $this->view[$region][]
	 * Parameters: file, variables (array), region
	 * Return $this
	 */
	public function AddInclude($file, $variables=array(), $region='default'){
		$this->views[$region][]=array('type'=>'include', 'file'=>$file, 'variables'=>$variables);
		return $this;
	}

	/**
	 * Public function AddString
	 * Parameters string, varaibles (array), region
	 * return: $this
	 */
	public function AddString($string, $variables=array(), $region='default'){
		$this->views[$region][]=array('type'=>'string', 'string'=>$string, 'variables'=>$variables);
		return $this;
	}

	/**
	 * public function Render
	 * Render specified region
	 * Parameters: region
	 */
	public function Render($region='default'){
		if(!isset($this->views[$region])){ // REGION HAS NOT BEEN SET! WHY???
			return;
		}
		foreach($this->views[$region] as $view){
			switch($view['type']){
				case 'include' :
					extract($view['variables']); include($view['file']);
					break;
				case 'string' :
					extract($view['variables']);
					echo $view['string'];
					break;
			}
		}
	}
	/**
	 * Public function RegionHasView
	 * Parameters: region
	 * Return Either true or false
	 */
	public function RegionHasView($region){
		if(is_array($region)){
			foreach($region as $val){
				if(isset($this->views[$val])){
					return true;
				}
			}
			return false;
		}else{
			return(isset($this->views[$region]));
		}
	}
		
	/**
	 * Public function AddStyle
	 * Adds inline style
	 * Parameters: value
	 * Return $this
	 */
	public function AddStyle($value){
		if(isset($this->data['inline_style'])){
			$this->data['inline_style'] .= $value;
		}else{
			$this->data['inline_style'] = $value;
		}
		return $this;
	}
}
?>