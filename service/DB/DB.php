<?php

require_once('DB_Config.php');

class DB{
	public 		$pdo,
			   	$result,
			   	$lastInsertId,
			   	$tables  = array(),
			   	$columnsName = array(),
			    $columnsValues = array();	

	protected 	$dns,
				$query='',
				$where_query = null;
	private 	$join = '';
	
	public function __construct(){
		global $config;

		try{
			$this->dns = 'mysql:host='.$config['db']['host'] .';dbname='.$config['db']['dbname'] ;
			$this->pdo = new PDO($this->dns,$config['db']['username'],$config['db']['password']);
		
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			//save all table name in our variable;
			$tables= $this->pdo->query('SHOW TABLES');
			$this->tables = $tables->fetchAll(PDO::FETCH_COLUMN); 
		}catch(PDOExceptions $e){
			throw new Exception("Error In Connecting to Database: ".$e->getMessage(), 1);
		}
		
	}


	public function select($tblname,$columns=array()){
		
		if($this->table_exists($tblname)){
			
			$strSelectquery ='';
			if($columns == null){
				$strSelectquery = 'SELECT * FROM '.$tblname;
			}else {
				$strSelectquery = 'SELECT '.implode(',',$columns).' FROM '.$tblname;
			}
			
			$this->query = $strSelectquery;
		}
		
		
		return $this;
	}
	
	public function insert($tblname,$columnsAndValues){
		
		if($this->table_exists($tblname)){
			foreach ($columnsAndValues as $column => $value) {
		
				array_push($this->columnsName,$column);
				array_push($this->columnsValues,$value);
			}
			$val = array();
			for($i = 0 ; $i < count($this->columnsValues) ; $i++){
				array_push($val,'?');
			}
			$this->query = 'INSERT INTO '.$tblname.'('.implode(',',$this->columnsName).') VALUES ('.implode(',',$val).')';
				
		}
		return $this;
	}
	public function update($tblname,$columnsAndValues){
		
		if($this->table_exists($tblname)){
			foreach ($columnsAndValues as $column => $value) {
		
				array_push($this->columnsName,$column);
				array_push($this->columnsValues,$value);
			}
			$col = array();
			for($i = 0 ; $i < count($this->columnsValues) ; $i++){
				array_push($col,'?');
			}
			$this->columnsName[count($this->columnsName) -1] .=" = ? " ;
			
			$this->query = 'UPDATE '.$tblname.' SET '.implode(' = ?, ',$this->columnsName).' ';
		}
		
		return $this;
	}
	public function delete($tblname){
		
		if($this->table_exists($tblname)){
			$this->query = 'DELETE FROM '.$tblname.' ';
		}
	
		return $this;
	}
	public function table_exists($tblName){
		return in_array($tblName,$this->tables)? true: false;
	}
	public function where($col_withoperator,$value){
		if(empty($this->where_query)){
			$this->where_query ='WHERE '.$col_withoperator.' ? ';
		}else if(isset($this->where_query)){
			$this->where_query .=' AND '.$col_withoperator.' ? ';
		}
		array_push($this->columnsValues, $value);
		return $this;
	}
	//This method will be updated later
	public function or_where($col_withoperator,$value){
	
		if(empty($this->where_query)){
			$this->where_query ='WHERE '.$col_withoperator.' ? ';
		}else if(isset($this->where_query)){
			$this->where_query .=' OR '.$col_withoperator.' ? ';
		}
		array_push($this->columnsValues, $value);
		return $this;
	}
	public function and_where($col_withoperator,$value){
		$this->where($col_withoperator,$value);
		return $this;
	}
	public function raw_query($query){
		
		$this->query = $query;
		return $this;
	}
	public function execute(){
		try{
			
			if(isset($this->where_query)  && !empty($this->where_query)){
				$this->query .=" ".$this->where_query;
			}
			
			if(preg_match('/^SELECT/i',$this->query)){
			
				$this->statement = $this->pdo->prepare($this->query);
				$this->result = $this->statement->execute($this->columnsValues);
				if($this->result){
					$this->result = $this->statement->fetchAll();
				}
				
			}else{
				$this->statement = $this->pdo->prepare($this->query);
				$this->result =	$this->statement->execute($this->columnsValues);
				$this->lastInsertId = $this->pdo->lastInsertId();
			}
			$this->sql= $this->query;
			$this->resetAttributes();
		}catch(PDOExceptions $e){
			throw new Exception("Error In Connecting to Database: ".$e->getMessage(), 1);
		}
		return $this->result;
	}
	private function resetAttributes(){
		if(isset($this->where_query) || !empty(trim($this->where_query))){
			$this->where_query='';
		}
		if(isset($this->query) || !empty(trim($this->query))){
			$this->query ='';
		}
		if(isset($this->columnsName) || !empty($this->columnsName)){
			$this->columnsName = array();
		}
		if(isset($this->columnsValues) || !empty($this->columnsValues)){
			$this->columnsValues = array();
		}
		if(isset($this->join) || !empty($this->join)){
			$this->join = '';
		}
		
	}
}

$db = new DB();

