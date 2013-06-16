<?php
// Author: Fredrik Åhman
// Course: PHPMVC @ BTH
// File: CCPage.php
// Desc: A controller to manage content of page type

/**
 * Controller class CCPage is used to manage content by page
 */


class CCPages extends CObject implements IController{

	// Member variables
	
	/**
	 * Construct
	 */
	public function __construct(){
		parent::__construct();
	}
 
	// Methods
	
	/**
	 * Public function Index.
	 * Implementation of IController.
	 * List all content in content table.
	 */
	public function Index(){
		$pages=$this->ReadFiles();
	
		$this->views->SetTitle('Pages');
		$this->views->AddInclude(__DIR__ . '/index.tpl.php', array('contents'=>$pages), 'primary');
		$this->views->AddInclude(__DIR__ . '/sidebar.tpl.php', array('contents'=>$pages), 'sidebar');
    }
    
    public function View($file){
		$this->views->AddInclude("site/pages/{$file}", array(), 'primary');
		
		$pages=$this->ReadFiles();
		$this->views->AddInclude(__DIR__ . '/sidebar.tpl.php', array('contents'=>$pages), 'sidebar');
    }
    
    protected function ReadFiles(){
    	$pages=array();
		if ($handle = opendir('site/pages/')) {
			$filesVektor = array(null);
			$i = 0;
			while (false !== ($entry = readdir($handle))) {
				$filesVektor[$i++] = $entry;
			}
			if (sort($filesVektor)) {
				foreach ($filesVektor as $file) {
					if($file == "." OR $file == ".."){
					}else{
						$name = preg_replace('/\..*$/', '', $file); /* tar bort allt från . och därefter i filnamnet fr webmasterworld */
						$pages[$name]=$file;
					}
				}
			}
			closedir($handle);
		}
    	return $pages;
    }
    
}
?>