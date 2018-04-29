<?php
namespace core\module;
class MySQLdrivers{
	private $_driver;
	private $_host;
	private $_user;
	private $_pass;
	private $_base;
	
	private $connect_link;
	
	public function __construct($driver,$host,$user,$pass,$base){
		$this->_driver = $driver;
		$this->_host = $host;
		$this->_user = $user;
		$this->_pass = $pass;
		$this->_base = $base;
		$this->connect();
	}
	private function connect(){
		switch($this->_driver){
			case "MySQL":
			$this->connect_link = mysql_connect($this->_host,$this->_user,$this->_pass);
			mysql_select_db($this->_base,$this->connect_link);
			break;
			case "MySQLi":
			$this->connect_link =  mysqli_connect($this->_host,$this->_user,$this->_pass,$this->_base);
			break;
		}
	}
	public function query($string){
		switch($this->_driver){
			case "MySQL":
			$query = mysql_query($string);
			break;
			case "MySQLi":
			$query = mysqli_query($this->connect_link,$string);
			break;
		}
		return $query;
	}
	public function fetch_assoc($query){
		switch($this->_driver){
			case "MySQL":
			$fetch_assoc = mysql_fetch_assoc($query);
			break;
			case "MySQLi":
			$fetch_assoc = mysqli_fetch_assoc($query);
			break;
		}
		return $fetch_assoc;
	}
	public function fetch_all($query){
		$result = array();
		while($row = $this->fetch_assoc($query)){
			$result[] = $row;
		}
		return $result;
	}
	public function fetch_array($query){
		switch($this->_driver){
			case "MySQL":
			$fetch_array = mysql_fetch_array($query);
			break;
			case "MySQLi":
			$fetch_array = mysqli_fetch_array($this->connect_link,$query);
			break;
		}
		return $fetch_array;
	}
	public function num_rows($query){
		switch($this->_driver){
			case "MySQL":
			$num_rows = mysql_num_rows($query);
			break;
			case "MySQLi":
			$num_rows = mysqli_num_rows($query);
			break;
		}
		return $num_rows;
	}
	public function real_escape_string($string){
		switch($this->_driver){
			case "MySQL":
			$real_escape_string = mysql_real_escape_string($string);
			break;
			case "MySQLi":
			$real_escape_string = mysqli_real_escape_string($this->connect_link,$string);
			break;
		}
		return $real_escape_string;
	}
}

?>
