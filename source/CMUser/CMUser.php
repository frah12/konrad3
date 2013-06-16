<?php
// Author: Fredrik Åhman
// Course: PHPMVC @ BTH
// File: CMUser.php
// Desc: CMUser handles user authentication

/**
 * Model class User
 * Extends CObject, implements IHasSQL, ArrayAccess, IModule
 */
class CMUser extends CObject implements IHasSQL, ArrayAccess, IModule{
	
	// Member variables
	public $profile=array();
	
	/**
	 * Construct. Checks if user is authenticated or not, and sets id accordingly.
	 * Parameters: ko (object)
	 */
	public function __construct($ko=null){
		parent::__construct($ko); // gets access to parent
		
		$profile=$this->session->GetAuthenticatedUser();
		$this->profile=is_null($profile) ? array() : $profile;
		$this["isAuthenticated"]=is_null($profile) ? false : true;
		if(!$this['isAuthenticated']){
			$this['id']=1;
			$this['acronym']='anonymous';
		}
	}

	/**
	 * Implementation of ArrayAccess: OffsetSet, -Exist, -Unset, -Get.
	 * Parameters: offset, value
	 */
	public function offsetSet($offset, $value){
		if(is_null($offset)){
			$this->profile[]=$value;
		}else{
 			$this->profile[$offset]=$value;
		}
	}
	
	public function offsetExists($offset){
		return isset($this->profile[$offset]);
	}
	
	public function offsetUnset($offset){
		unset($this->profile[$offset]);
	}
	
	public function offsetGet($offset){
		return isset($this->profile[$offset]) ? $this->profile[$offset] : null;
	}

/**
 * Public function Manage.
 * Implementing IModule interface.
 * Initialize and create the database and tables
 * Parameters: action.
 */
	public function Manage($action=null){
		switch($action){
			case 'install' :
				try{
					// Drop tables
					$this->db->ExecuteQuery(self::SQL("drop table user"));
					$this->db->ExecuteQuery(self::SQL("drop table user2groups"));
					$this->db->ExecuteQuery(self::SQL("drop table groups"));
		
					// Create tables
					$this->db->ExecuteQuery(self::SQL("create table user"));
					$this->db->ExecuteQuery(self::SQL("create table groups"));
					$this->db->ExecuteQuery(self::SQL("create table user2groups"));

					// Insert intitial data
					// acronym,name,email,algorithm,salt,password
					$password=$this->CreatePassword("root");
					$this->db->ExecuteQuery(self::SQL("insert into user"), array("root", "Administrator", "root@foobarwebb.se", $password['algorithm'], $password['salt'], $password['password']));
					$idRootUser=$this->db->LastInsertId();
		
					$password=$this->CreatePassword("doe");
					$this->db->ExecuteQuery(self::SQL("insert into user"), array("doe", "John/Jane Doe", "doe@foobarwebb.se", $password['algorithm'], $password['salt'], $password['password']));
					$idDoeUser = $this->db->LastInsertId();
					if(!is_file("site/users/doe")){
						mkdir("site/users/doe", 0777);
						chmod("site/users/doe", 0777);
						$file="site/users/doe/doe.config.php";
						copy('site/site.config.php', $file) or die("Couldn't copy file.");
						chmod($file, 0666);
						$file="site/users/doe/doe.style.css";
						copy('site/site.style.css', $file) or die("Couldn't copy file.");
						chmod($file, 0666);
					}
							
					$this->db->ExecuteQuery(self::SQL("insert into groups"), array("admin", "Administrator Group"));
					$idAdminGroup = $this->db->LastInsertId();
					$this->db->ExecuteQuery(self::SQL("insert into groups"), array("user", "User Groups"));
					$idUserGroup = $this->db->LastInsertId();		
		
					// Insert users into groups
					$this->db->ExecuteQuery(self::SQL("insert into user2groups"), array($idRootUser, $idAdminGroup));
					$this->db->ExecuteQuery(self::SQL("insert into user2groups"), array($idRootUser, $idUserGroup));
					$this->db->ExecuteQuery(self::SQL("insert into user2groups"), array($idDoeUser, $idUserGroup));
		
					// Return succes Message				
					return array(	'success',
					'Successfully created database and tables. Created default administrator as root:root, and test user as doe:doe.');
				} catch(Exception $e){
					die("$e<br/>Failed to open database: " . $this->config['database'][0]['dsn']);
				}
				break;
			default :
				throw new Exception('Unsupported action for this module.');
				break;
			}
		}
		
	// Methods
	
	/**
	 * Public static function SQL.
	 * Implementation of IHasSQL interface
	 * Parameters: key.
	 * Returns: queries[key] (array)
	 */
	public static function SQL($key=null) {
    	$queries = array(
    	'drop table user'=>"DROP TABLE IF EXISTS User;",
    	'drop table groups'=>"DROP TABLE IF EXISTS Groups;",
    	'drop table user2groups'=>"DROP TABLE IF EXISTS User2Groups;",
    	'create table user'=>"CREATE TABLE IF NOT EXISTS User (id INTEGER PRIMARY KEY, acronym TEXT KEY NOT NULL UNIQUE, name TEXT, email TEXT, algorithm TEXT, salt TEXT, password TEXT, created DATETIME default (datetime('now')), updated DATETIME default NULL);",
      'create table groups'=>"CREATE TABLE IF NOT EXISTS Groups (id INTEGER PRIMARY KEY, acronym TEXT KEY, name TEXT, created DATETIME default (datetime('now')), updated DATETIME default NULL);",
      'create table user2groups'=>"CREATE TABLE IF NOT EXISTS User2Groups (idUser INTEGER, idGroups INTEGER, created DATETIME default (datetime('now')), PRIMARY KEY(idUser, idGroups));",
		'insert into groups'=>"INSERT INTO Groups (acronym,name) VALUES (?,?);",
		'insert into user2groups'=>"INSERT INTO User2Groups (idUser,idGroups) VALUES (?,?);",    	
    	'insert into user'=>"INSERT INTO User (acronym,name,email,algorithm,salt,password) VALUES (?,?,?,?,?,?);",
		'get group memberships'=>"SELECT * FROM Groups AS g INNER JOIN User2Groups AS ug ON g.id=ug.idGroups WHERE ug.idUser=?;",
		'select all user'=>"SELECT * FROM User;",
		'select user'=>"SELECT * FROM User WHERE acronym=?;",
		'delete user'=>"DELETE FROM User WHERE acronym=?;",
		'delete user from user2groups'=>"DELETE FROM User2Groups WHERE idUser=?;",
    	'check user password'=>"SELECT * FROM User WHERE acronym=? OR email=?;",
		'update profile'=>"UPDATE User SET name=?, email=?, updated=datetime('now') WHERE id=?;",
		'update password'=>"UPDATE User SET algorithm=?, salt=?, password=?, updated=datetime('now') WHERE acronym=?;"); // end queries array WHERE ug.idUser=?
    	
    	if(!isset($queries[$key])) {
			throw new Exception("SQL query did not exist. The key: {$key}, was not found.");
		}
		return $queries[$key];
	}
	
	/**
	 * Public function Login
	 * Authenticate user with email or acronym, and password.
	 * Success; Store in $_SESSION.
	 * Parameters: acronymOrEmail, password.
	 * Returns true or false
	 */
	public function Login($acronymOrEmail, $password) {
		$user = $this->db->SelectAndFetchAll(self::SQL("check user password"),
			array($acronymOrEmail, $acronymOrEmail));
		
		$user = (isset($user[0])) ? $user[0] : null;
		if(!$user){
			return false;
		}elseif(!$this->CheckPassword($password, $user['algorithm'], $user['salt'], $user['password'])){
			return false;
		}
		
		unset($user['password']);
		unset($user['salt']);
		unset($user['password']);
		
		if($user) {
			$user['isAuthenticated']=true;
			$user['groups']=$this->db->SelectAndFetchAll(self::SQL("get group memberships"), array($user['id']));
			foreach($user['groups'] as $choice){
				if($choice['id'] == 1){
					$user['hasRoleAdmin']=true;
				}
				if($choice['id'] == 2){
					$user['hasRoleUser']=true;
				}
			}
			$this->profile=$user;
			$this->session->SetAuthenticatedUser($this->profile);
		}	
		return ($user != null);
	}
	
	/**
	 * Logout and unset user $_SESSION
	 */
	public function Logout() {
		$this->session->UnsetAuthenticatedUser();
		$this->session->AddMessage("success", "You have logged out.");
	}

	/**
	 * Public function Save
	 * Updates profile using predefined SQL statement "update profile".
	 * Returns: this->db-rowcount if true
	 */
	public function Save(){
		$this->db->ExecuteQuery(self::SQL("update profile"), array($this['name'], $this['email'],
		$this['id']));
		$this->session->SetAuthenticatedUser($this->profile);
		return $this->db->RowCount() === 1;
	}
  
  
	/**
	 * Public function ChangePassword.
	 * Used for changing password
	 * Parameters: password.
	 * Returns: this->db->RowCount if true
	 */
	public function ChangePassword($password, $acronym=null) {
		$password=$this->CreatePassword($password);
		$this->db->ExecuteQuery(self::SQL('update password'), array($password['algorithm'], $password['salt'], $password['password'], $acronym));
		return $this->db->RowCount() === 1;
	}

	
	/**
	 * Public function CreatePassword.
	 * Used for creating password. Possible algorithms: sha1salt, md5salt, sha1, md5, plain.
	 * Plain parameter being the password.
	 * Parameters: plain, algorithm
	 * Returns: password
	 */
 
	public function CreatePassword($plain, $algorithm=null){
		$password=array("algorithm"=>($algorithm ? $algorithm : CKonrad::Instance()->config['hashing_algorithm']), "salt"=>null);
		
		switch($password['algorithm']){
			case 'sha1salt' :
				$password['salt']=sha1(microtime());
				$password['password']=sha1($password['salt'] . $plain);
				break;
			case 'md5salt' :
				$password['salt']=md5(microtime());
				$password['password']=md5($password['salt'] . $plain);
				break;
			case 'sha1' :
				$password['password']=sha1($plain);
				break;
			case 'md5' :
				$password['password']=md5($plain);
				break;
			case 'plain' :
				$password['password']=$plain;
				break;
			default :
				throw new Exception('Unknown hashing algorithm');
		}
		
		return $password;
	}
	
	/**
	 * Public function CheckPassword
	 * Checks password. Parameter plain being tested against parameter password
	 * Possible algorithms: sha1salt, md5salt, sha1, md5, plain.
	 * Parameters: plain, algorithm, salt, password
	 * Returns: true exact match
	 */
	public function CheckPassword($plain, $algorithm, $salt, $password) {
		switch($algorithm){
			case 'sha1salt' :
				return $password === sha1($salt.$plain);
				break;
			case 'md5salt'	:
				return $password === md5($salt.$plain);
				break;
			case 'sha1' :
				return $password === sha1($plain);
				break;
			case 'md5' :
				return $password === md5($plain);
				break;
			case 'plain' :
				return $password === $plain;
				break;
			default :
				throw new Exception('Unknown hashing algorithm');
		}
	}

	/**
	 * Public function Create.
	 * Used for creating a new user account.
	 * Parameters: acronym, pwd, name, email
	 * Returns: true OR false
	 */
	public function Create($acronym, $pwd, $name, $email){
		$password=$this->CreatePassword($pwd);
        try{
        	$this->db->ExecuteQuery(self::SQL("insert into user"), array($acronym, $name, $email, $password['algorithm'], $password['salt'], $password['password']));
        } catch(Exception $e){
        	$this->AddMessage('error', "User already exists.");
        	//throw new exception('User already exists!', 0, $e);
        }
        if($this->db->RowCount() == 0){
        	$this->AddMessage('error', "Failed to create user.");
        	return false;
        }
       if(!is_file("site/users/{$acronym}")){
			mkdir("site/users/{$acronym}", 0777);
			chmod("site/users/{$acronym}", 0777);
			$file="site/users/{$acronym}/{$acronym}.config.php";
			copy('site/site.config.php', $file) or die("Couldn't copy file.");
			chmod($file, 0666);
			$file="site/users/{$acronym}/{$acronym}.style.css";
			copy('site/site.style.css', $file) or die("Couldn't copy file.");
			chmod($file, 0666);
		}
		
		//createUserDir("site/users", $acronym); // function found in ckonrad/bootstrap.php
		
       return true;
    }
    
    /**
	 * Public function FetchAllUsers.
	 * Used to fetch all users from database
	 * Parameters: 
	 * Returns: result (array)
	 */
   public function FetchAllUsers(){
    	$result=null;
    	try{
    		$result = $this->db->SelectAndFetchAll(self::SQL('select all user'));
        } catch(Exception $e){
        	//$this->AddMessage('error', "{$e}");
        	throw new exception('ERROR!', 0, $e);
        }
        return $result;
    }
    
     /**
	 * Public function FetchUser.
	 * Used to fetch a user from database
	 * Parameters: acronym
	 * Returns: result (array)
	 */
	public function FetchUser($acronym=null){
    	$result=null;
    	try{
    		$result = $this->db->ExecuteQuery(self::SQL('select user'), array($acronym));
        } catch(Exception $e){
        	//$this->AddMessage('error', "{$e}");
        	throw new exception('ERROR!', 0, $e);
        }
        return $result;
    }
    
     /**
	 * Public function DeleteUser.
	 * Used to delete a user from database
	 * Parameters: acronym
	 * Returns: result
	 */
    public function DeleteUser($acronym=null){
    	$result=null;
    	try{
    		$user = $this->db->ExecuteQuery(self::SQL('select user'), array($acronym));
    		$result= $this->db->ExecuteQuery(self::SQL('delete user from user2groups'), array($user['id']));
    		$result = $this->db->ExecuteQuery(self::SQL('delete user'), array($acronym));
    		if(is_file("site/users/{$acronym}")){
    			rrmdir("site/users/{$acronym}");
			}
        } catch(Exception $e){
        	//$this->AddMessage('error', "{$e}");
        	throw new exception('ERROR!', 0, $e);
        }
        return $result;
    }
   
}
?>