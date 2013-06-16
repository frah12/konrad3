<?php
// Author: Fredrik Åhman
// Course: PHPMVC @ BTH
// File: CDatabase.php
// Desc: Class for Database API

/**
 * Class CDatabase.
 * This controller class intermediate with the prefered database configured in site/config.php.
 * In this case sqlite.
 */

class CDatabase{
	
	 //Member variables

	private $db=null;
	private $stmt=null;
	private static $numQueries=0; //use self:: to access static members this sets numner of queries
	private static $queries = array(); // all queries are stored in this one
	
	/**
	 * Construct
	 * Parameters: dsn, username, password, driver_options (array).
	 */
	public function __construct($dsn, $username='', $password='', $driver_options=array()){
		//echo $dsn;
		$this->db = new PDO($dsn, $username, $password, $driver_options);
		$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	
	/**
	 * Destruct
	 * Empty
	 */
	public function __destruct(){
		;
	}
	
	
	// Methods
		
	/**
	 * Public function SetAttributes, used to set database attribute.
	 * Parameters: attribute, value
	 * return: $this->db->setAttribute($attribute, $value)
	 */
	public function SetAttributes($attribute, $value){
		return $this->db->setAttribute($attribute, $value);
	}
	
	// Get functions
	
	/**
	 * Public function GetNumQueries
	 * return: self::numQueries
	 */
	public function GetNumQueries(){
		return self::$numQueries;
	}
	
	/**
	 * Public function GetQueries
	 * return: self::Queries
	 */
	public function GetQueries(){
		return self::$queries; // private static use self::
	}
	
	/**
	 * Public function SelectAndFetchAll
	 * Executes an sql statement and fetch all return
	 * Parameters: query, params (array)
	 * Return: fetchAll(PDO::FETCH_ASSOC)
	 */
	public function SelectAndFetchAll($query, $params=array()){
		$this->stmt = $this->db->prepare($query);
		self::$queries[]=$query;
		self::$numQueries++;
		$this->stmt->execute($params);
		return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	/**
	 * Public function ExecuteQuery
	 * Parameters: query, params (array).
	 * Return: $this->stmt->execute($params)
	 */
	public function ExecuteQuery($query, $params=array()){
		$this->stmt=$this->db->prepare($query);
		self::$queries[]=$query;
		self::$numQueries++;
		$this->stmt->execute($params);
		
		return $this->stmt->fetch(PDO::FETCH_ASSOC);
	}
	
	/**
	 * Public function LastInsertId.
	 * To get the last inserted id.
	 * Return: $this->db->lastInsertId
	 */
	public function LastInsertId(){
		return $this->db->lastInsertId();
	}
	
	/**
	 * Public function RowCount
	 * Returns affected row count from last insert, update, or delete queries
	 */
	public function RowCount(){
		return is_null($this->stmt) ? $this->stmt : $this->stmt->rowCount();
	}
}
?>