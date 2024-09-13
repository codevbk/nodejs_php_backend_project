<?php
namespace Classes;
use \PDO;
//use Classes\ErrorExceptionHandler;
/* */
class Database {
	private $db;
	private $connected = false;
	private $parameters;
	private $values;
	private $sqlQuery;
	private $log;
	/* */

    private $pdoConnection;
    //protected $errorExceptionHandler;

	public function __construct(){ 	
		//$this->errorExceptionHandler = new ErrorExceptionHandler();
		/* */
		$this->parameters = array();
		$this->values = array();
	}
	
	public function connect($hostname,$database,$username,$password,$encoding,$port,$ssl){
		$dsn = 'mysql:dbname='.$database.';host='.$hostname.';port='.$port.';charset='.$encoding;
		try{
			if($ssl == true){
				$this->pdoConnection = new PDO($dsn, $username, $password, $options);
			}else{
				$this->pdoConnection = new PDO($dsn, $username, $password);
			}
			
			$this->pdoConnection->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES ".$encoding);
			$this->pdoConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->pdoConnection->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
			$this->connected = true;
			/* */
		}catch (Exception $ex){
			//$this->errorExceptionHandler->exception($e);
			/* */
		}
	}
	
	public function pdo_escape($value){
		if(is_array($value))
			return array_map(__METHOD__, $value);

		if(!empty($value) && is_string($value)) {
			return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $value);
		}

		return $value;
	}
	
	public function close_connection(){
		$this->pdoConnection = null;
	}
	
	public function debug(){
		return $this->sqlQuery->debugDumpParams();
	}
	
	public function error(){
		return $this->sqlQuery->errorInfo();
	}
	
	public function row_count(){
		return $this->sqlQuery->rowCount();
	}

	public function last_id() {
		return $this->pdoConnection->lastInsertId();
	}	
	
	private function initialize($query,$parameters=null,$values=null){
		if(!$this->connected) { $this->connect(); }
		/* */
		try {
				$this->sqlQuery = $this->pdoConnection->prepare($query);
				if(isset($parameters) && !empty($parameters)) {
					if(is_array($parameters) && count($parameters) > 1){
						foreach($parameters as $param_index => $param){
							$this->sqlQuery->bindParam($param,$values[$param_index]);
						}	
					}else{
						$this->sqlQuery->bindParam($parameters,$values);
					}

				}
				/* */
				if(!empty($this->parameters)) {
					foreach($this->parameters as $param_index => $param){
						$this->sqlQuery->bindParam($param,$this->values[$param_index]);
					}		
				}
				/* */
				$this->sqlQuery->execute();	
			}catch(Exception $e){
                //$this->errorExceptionHandler->exception($e);
                /* */
			}
			$this->parameters = array();
			$this->values = array();
	}
	
	/* */
	
	private function get_pdo_data_type($value){
		$data_types = array("integer","string");
		$pdo_data_types = array("PARAM_INT","PARAM_STR");		
		$data_type = gettype($value);
		$data_type_bool = array_search($data_type, $data_types);
		if($data_type_bool !== false){
			return $pdo_data_types[$data_type_bool];
		}
	}

	public function bind($parameter,$value){
		$this->parameters[sizeof($this->parameters)] = ":".$parameter;
		$this->values[sizeof($this->values)] = $value;
	}
	
	public function select($custom=null,$columns=null,$table=null,$condition=null,$mode = PDO::FETCH_ASSOC){
		if(!$this->connected) { $this->connect(); }
		/* */
		$output = null;
		if($custom == null){
			if(isset($columns) && !empty($columns) && isset($table) && !empty($table)){
				if(is_array($columns) && count($columns) > 1){
					$column_array = implode("`, `", $columns);
					$select_query = "SELECT `".$column_array."` FROM `".$table."` ".(isset($condition) && !empty($condition) ? "WHERE ".$condition : "");
					$this->initialize($select_query);
					$output = $this->sqlQuery->fetchAll($mode);
				}else{
					$select_query = "SELECT `".$columns."` FROM `".$table."` ".(isset($condition) && !empty($condition) ? "WHERE ".$condition : "");
					$this->initialize($select_query);
					$output = $this->sqlQuery->fetchAll($mode);
				}
			}else{
				/* */
			}
			/* */
		}else{
			$select_query = $custom;
			$this->initialize($select_query);
			$output = $this->sqlQuery->fetch($mode);
		}
		$this->close_connection();
		return $output;
	}
	
	/* */
	
	/* */

	/* */
	
}




?>