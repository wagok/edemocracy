<?php
class SPRDB {
	static $DB;
	protected $curRes;
	public $isNew, $Selected, $id, $Author, $addDate, $Deleted;
	
	function GetTableName() {
	}
	function GetFieldsList() {
		return 'Author, addDate, Deleted';
	}
	
	protected function QueryAndFill($sql) {
		$this->curRes = mysql_query ( $sql, self::$DB ) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );
		if (mysql_num_rows ( $this->curRes ) > 0) {
			$this->Selected = true;
			$this->isNew = false;
			$temp = mysql_fetch_assoc ( $this->curRes );
			foreach ( $temp as $key => $value ) {
				$this->$key = $value;
			}
			return true;
		} else {
			$this->Selected = false;
			$this->isNew = false;
			return false;
		}
	}
	
	static function qs($value) {
		if (get_magic_quotes_gpc ()) {
			$value = stripslashes ( $value );
		}
		if (! is_numeric ( $value )) {
			$value = "'" . mysql_real_escape_string ( $value ) . "'";
		}
		return $value;
	}
	static function Init($DataBase) {
		self::$DB = $DataBase;
	}
	function GetByID($id) {
		$sql = sprintf ( 'SELECT * from %s WHERE id=%s', $this->GetTableName (), self::qs ( $id ) );
		
		return $this->QueryAndFill ( $sql );
	}
	function __construct() {
		$this->isNew = false;
		$this->Selected = false;
	}
	function Create() {
		$this->isNew = true;
		$this->Selected = true;
		$this->addDate = Date ( 'c' );
		$this->Deleted = false;
		return $this;
	}
	function Delete() {
		$this->Deleted = true;
		$this->Save();
		$this->Selected = false;
	}
	function Save() {
		if (! $this->Selected) {
			return true;
		}
		$fieldsList = explode ( ',', $this->GetFieldsList () );
		
		if ($this->isNew == true) {
			
			$sql = 'INSERT INTO ' . $this->GetTableName () . ' SET ';
			$separ = '';
			foreach ( $fieldsList as $key ) {
				$sql .= $separ;
				$key = trim ( $key );
				$sql .= $key . '=' . self::qs ( $this->$key );
				$separ = ',';
			}
			mysql_query ( $sql, self::$DB ) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );
			$this->id = mysql_insert_id ( self::$DB );
			$this->isNew = false;
		} else {
			$sql = 'UPDATE ' . $this->GetTableName () . ' SET ';
			$separ = '';
			foreach ( $fieldsList as $key ) {
				$sql .= $separ;
				$key = trim ( $key );
				$sql .= $key . '=' . self::qs ( $this->$key );
				$separ = ',';
			}
			$sql .= ' WHERE id=' . self::qs ( $this->id );
			mysql_query ( $sql, self::$DB ) or die ( "Query failed : " . mysql_error () . '<br>Query:' . $sql );
		}
		$this->Modified = false;
		return true;
	}
	function Find() {
		$fieldsList = explode ( ',', $this->GetFieldsList () );
		$sql = sprintf ( 'SELECT * from %s WHERE Deleted <> true ', $this->GetTableName () );
		foreach ( $fieldsList as $key ) {
			$key = trim ( $key );
			if (isset ( $this->$key ) && $this->$key != '') {
				$sql .= ' and ' . $key . '=' . self::qs ( $this->$key );
			}
		}
		return $this->QueryAndFill ( $sql );
	}
	function Next() {
		if (isset ( $this->curRes ) && $this->curRes != '' && ($temp = mysql_fetch_assoc ( $this->curRes ))) {
			foreach ( $temp as $key => $value ) {
				$this->$key = $value;
			}
			$this->Selected = true;
			$this->isNew = false;
			return true;
		} else {
			$this->Selected = false;
			$this->isNew = false;
			return false;
		}
	}
	function __toString() {
		$fieldsList = explode ( ',', $this->GetFieldsList () );
		$str = 'Object ' . $this->GetTableName () . ' #' . $this->id . '<br>{<br>';
		foreach ( $fieldsList as $key ) {
			$key = trim ( $key );
			if (isset ( $this->$key )) {
				$str .= $key . ' = ' . self::qs ( $this->$key ) . '<br>';
			}
		}
		$str .= '}<br>';
		return $str;
	}
}
?>