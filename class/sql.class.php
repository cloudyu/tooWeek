<?php
class sql{
	private $_host = '';
	private $_username = '';
	private $_password = '';
	private $_database = '';
	private $_link = '';
	private $_debug = CY_DEBUG;
	private $_query = '';
	public function __construct(){
		$this->connect();
	}
	public function __destruct(){
		$this->close();
	}
	public function Query($query, $table, $variable = array()){
		/*
		Query('SELECT * FROM __table__ WHERE id = $0, mail=$1', 'temp', array(3, 'admin1'))
		查询为
		SELECT * FROM cy_temp WHERE id = 3'
		 */
		$this->_query = $query;
		$this->isAlive();
		$query = str_replace('__table__', CY_PREFIX . $table, $query);
		for($i = 0; $i < count($variable); ++$i){
			$query = str_replace('$' . $i, $this->check($variable[$i]), $query);
		}
		return $this->Debug(mysql_query($query));
	}
	public function AffectedRows(){
		return $this->Debug(mysql_affected_rows ($_link));
	}
	public function FetchArray($result){
		return $this->Debug(mysql_fetch_array($result));
	}
	public function FetchRow($result){
		return $this->Debug(mysql_fetch_row($result));
	}
	public function FetchObject($result){
		return $this->Debug(mysql_fetch_object($result, $offset));
	}
	public function FetchField($result, $offset){//获取每个字段
		return $this->Debug(mysql_fetch_field  ($result ,$offset));
	}
	public function FetchLengths($result){//获取上一次每个字段内容的长度
		return $this->Debug(mysql_fetch_lengths($result));
	}
	public function NumRows($result){//获取查询结构行数
		return $this->Debug(mysql_num_rows($result));
	}
	public function DataSeek($result, $rowNumber){
		return $this->Debug(mysql_data_seek ($result ,$rowNumber));
	}

	private function Debug($result){
		if(!$result){
			if(mysql_errno() == 0){//空结果返回false
				return false;
			}
			if($this->_debug){
				die('sql error: ' . mysql_error() . '; erron: ' . mysql_errno(). '; query: ' . $this->_query);
			}else{
				die('程序出现错误');
			}
		}
		return $result;
	}


	public function InsertId(){
		return mysql_insert_id ($this->_link);
	}

	private function Check($value){

		if (get_magic_quotes_gpc()){
			$value = stripslashes($value);
		}
		$value = "'" . @mysql_real_escape_string($value) . "'";

		return $value;
	}
	private function Connect(){
		$this->_link = $this->Debug(@mysql_connect (CY_SQL_HOST.':'.CY_SQL_PORT, CY_SQL_USERNAME, CY_SQL_PASSWORD));
		if(defined('CY_CHARSET')){
			$this->Debug(mysql_set_charset(CY_CHARSET, $this->_link));
		}
		$this->Debug(mysql_select_db(CY_SQL_DATABASE, $this->_link));
	}
	private function Close(){
		if ($this->_link){
			@mysql_close($this->_link);
			unset($this->_link);
		}
	}

	private function isAlive(){
		if(!$this->_link){
			$this->connect();
		}
		return mysql_ping($this->_link);
	}
}
?>