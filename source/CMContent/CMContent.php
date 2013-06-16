<?php
// Author: Fredrik Åhman
// Course: PHPMVC @ BTH
// File: CMContent.php
// Desc: A modell for CContent

/**
 * Model class CMContent.
 * Extends CObject, implements IHasSQL and ArrayAccess
 */

class CMContent extends CObject implements IHasSQL, ArrayAccess{

	// Member variables
	public $data;
	 
	/**
	 * Construct
	 * Loads content by id or initialize this->data as array.
	 * Parameters: id
	 */
	public function __construct($id=null){
		parent::__construct();
		
		if ($id){
			$this->LoadById($id);
		}else{
			$this->data=array();
		}
	}
	
	/**
	 * Array access implementation.
	 * OffsetSet, -Exists, -Unset, -Get
	 */
	public function offsetSet($offset, $value){
		if (is_null($offset)){
			$this->data[] = $value;
		}else{
			$this->data[$offset] = $value;
		}
	}
	
	public function offsetExists($offset){
		return isset($this->data[$offset]);
	}
	
	public function offsetUnset($offset){
		unset($this->data[$offset]);
	}
	
	public function offsetGet($offset){
		return isset($this->data[$offset]) ? $this->data[$offset] : null;
	}	
	
	/**
	 * Public static function SQL
	 * Implementing IHasSQL interface.
	 * Parameters: key, arguments
	 * Returns: queries[key] (array)
	 */	
	public static function SQL($key=null, $arguments=null){
		$order_by= isset($arguments['order-by']) ? $arguments['order-by'] : 'id';
		$order_order=isset($arguments['order-order']) ? $arguments['order-order'] : 'ASC';
		$queries=array(
		'drop table content'=>"DROP TABLE IF EXISTS Content;",
		'create table content'=>"CREATE TABLE IF NOT EXISTS Content (id INTEGER PRIMARY KEY, key TEXT KEY, type TEXT, title TEXT, data TEXT, idUser INT, created DATETIME default (datetime('now')), updated DATETIME default NULL, deleted DATETIME default NULL, FOREIGN KEY(idUser) REFERENCES User(id));",
		'insert content'=>"INSERT INTO Content (key,type,title,data, idUser) VALUES (?, ?, ?, ?, ?);",
		'select * by id'=>"SELECT c.*, u.acronym as owner FROM Content AS c INNER JOIN User as u ON c.idUser=u.id WHERE c.idUser=?;",
		'select * by key'=>"SELECT c.*, u.acronym as owner FROM Content AS c INNER JOIN User as u ON c.idUser=u.id WHERE c.key=?;",
		'select *'=>"SELECT c.*, u.acronym as owner FROM Content AS c INNER JOIN User as u ON c.idUser=u.id;",
		'select * by type'=>"SELECT c.*, u.acronym as owner FROM Content AS c INNER JOIN User as u ON c.idUser=u.id WHERE type=? ORDER BY {$order_by} {$order_order};",
		'update content'=>"UPDATE Content SET key=?, type=?, title=?, data=?, updated=datetime('now') WHERE id=?;",
		'delete content'=>"DELETE FROM Content WHERE id=?;");
		
		if(!isset($queries[$key])){
			throw new Exception("No such SQL query, key '$key' was not found.");
		}
		
		return $queries[$key];
	}	  
	  
	// Methods

	/**
	 * Public function Manage
	 * Init the database and create initial tables.
	 */
	public function Manage($action=null){
		switch($action){
			case 'install' :
				try {
					$this->db->ExecuteQuery(self::SQL('drop table content'));
					$this->db->ExecuteQuery(self::SQL('create table content'));
					$this->db->ExecuteQuery(self::SQL('insert content'), array('hello-world', 'post', 'Hello World', 'This is a demo post.',  $this->user['user']));
					$this->db->ExecuteQuery(self::SQL('insert content'), array('hello-world-again', 'post', 'Hello World Again', 'This is another demo post.', $this->user['id']));
					$this->db->ExecuteQuery(self::SQL('insert content'), array('hello-world-once-more', 'post', 'Hello World Once More', 'This is one more demo post.', $this->user['id']));
					$this->db->ExecuteQuery(self::SQL('insert content'), array('This is ROOT', 'post', 'Admin blog', "This is the administrators blog", $this->user['id']));
		
					$this->AddMessage('success', 'Successfully created the database tables and created a default "Hello World" blog post, owned by you.');
	    		}catch(Exception $e){
    				die("$e <br> Failed to open database: " . $this->config['database'][0]['dsn']);
 				}
 				break;
     		default :
     			throw new Exception('Unsupported action for this module.');
     			break;
     	}
     }
 
 	 /**
	  * Public function Save
	  * Saves content to database.
	  * Returns: rowcount true
	  */
	public function Save(){
		$msg = null;
		if($this['id']){
			$this->db->ExecuteQuery(self::SQL('update content'), array($this['key'], $this['type'],
			$this['title'], $this['data'], $this['id']));
			$msg = 'update';
		}else{
			$this->db->ExecuteQuery(self::SQL('insert content'), array($this['key'], $this['type'],
			$this['title'], $this['data'], $this->user['id']));
			$this['id'] = $this->db->LastInsertId();
			$msg = 'create';
		}
			$rowcount = $this->db->RowCount();
		if($rowcount){
			$this->AddMessage('success', "Successfully {$msg}d content '{$this['key']}'.");
		}else{
			$this->AddMessage('error', "Failed to {$msg} content '{$this['key']}'.");
		}
		return $rowcount === 1;
	}
	
	/**
	 * Public function delete
	 * Optional button to delete current content being edited
	 */
	public function Delete(){
		$msg = null;
		if($this['id']){
			$this->db->ExecuteQuery(self::SQL('delete content'), array($this['id']));
			$msg = 'delete';
		}
		$rowcount = $this->db->RowCount();
		if($rowcount){
			$this->AddMessage('success', "Successfully {$msg}d content '{$this['key']}'.");
		}else{
			$this->AddMessage('error', "Failed to {$msg} content '{$this['key']}'.");
		}
		return $rowcount === 1;
	}
	
	 
	/**
	  * Public function ListAll
	  * Loads database content into an array.
	  * Returns: Content array or null
	  */
	public function ListAll($arguments=null){
		try{
			if(isset($arguments) AND isset($arguments['id'])){
				return $this->db->SelectAndFetchAll(self::SQL('select * by id'), array($arguments['id']));
			}
			if(isset($arguments) AND isset($arguments['type'])){
				return $this->db->SelectAndFetchAll(self::SQL('select * by type'), array($arguments['type']));
			}else{
				return $this->db->SelectAndFetchAll(self::SQL('select *'), $arguments);
			}
		}catch(Exception $e){
			echo $e;
			return null;
		}
	}
	  
	/**
	 * Public function LoadById
	 * Loads content from database by id into data array.
	 * Parameters: id
	 * Returns: true if successfull.
	 */
	public function LoadById($id){
		$result = $this->db->SelectAndFetchAll(self::SQL('select * by id'), array($id));
	
		if(empty($result)) {
			$this->AddMessage('error', "Failed to load content with id '$id'.");
			return false;
		}else{
			$this->data = $result[0];
		}
		
		return true;
	}

	/**
	 * Public function Filter
	 * Removes html and script nasties from submitted content.
	 * Adds filter for bbcode
	 * bbcode: [b], [i], [u], [img], [url]
	 */
	public static function Filter($data){
		$data = nl2br(bbcode2html(htmlent($data)));
				
		return $data;
	}

	/**
	 * Public function GetFilteredData
	 * Fetches filtered data
	 * Return filtered data.
	 */
	public function GetFilteredData(){
		return $this->Filter($this['data']);
	}
}
?>