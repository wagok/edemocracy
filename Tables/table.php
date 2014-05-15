<?php
class Table_Column {
	public $Name;
	public $Title;
	public $ColumnTags = false;
	public $Width = false;
	function __construct($Name, $Title = 0) {
		$this->Name = $Name;
		$this->Title = $Title;
	}

}

class Table_Row {
	public $Row = array ();
	protected $index;
	function __construct($columns) {
		foreach ( $columns as $val ) {
			$this->Row [$val->Name] = '';
		}
	}
	function IndexOf() {
		return $this->index;
	}
	function SetIndex($num) {
		$this->index = $num;
	}
	function __set($name, $data) {
		if (array_key_exists($name, $this->Row)) {
			$this->Row [$name] = $data;
		} else {
			throw new Exception ( 'Table_Row class:There is not such collumn - ' . $name, 1005 );
		}
	}
	function __get($name) {
		if (array_key_exists($name, $this->Row)) {
			return $this->Row [$name];
		} else {
			throw new Exception ( 'Table_Row class:There is not such collumn - ' . $name, 1005 );
		}
	}
}

class Table_Rows_Iterator implements Iterator {
	private $owner;
	private $cur = false;
	function __construct($owner) {
		$this->owner = $owner;
		$this->rewind ();
	}
	function rewind() {
		if (count ( $this->owner->Rows ) > 0) {
			$this->cur = 0;
		}
	}
	function valid() {
		return isset ( $this->owner->Rows [$this->cur] );
	}
	function current() {
		return $this->owner->Rows [$this->cur];
	}
	function key() {
		return $this->cur;
	}
	function next() {
		$this->cur ++;
	}
}

class TableSortElem {
	public $ColumnName; 
	public $Order; // Asc / Desc
	function __construct($columnName, $order){
		$this->ColumnName = trim($columnName);
		$this->Order = (trim($order)=='Desc'?'Desc':'Asc');
	}
}

class Table implements IteratorAggregate, ArrayAccess {
	// error Codes from 1001 - 1099;
	public $Columns;
	public $Rows;
	
	static $currentSortOrder;
	
	function __construct() {
		$this->Columns = array ();
		$this->Rows = array ();
		$this->RowsCol = 0;
	}

	function Sort($columnsOrder) { // 'ColName {}Asc/Desc, ColName {}/Desc');
		$colOrderArr = explode(',',$columnsOrder);
		self::$currentSortOrder = array();
		foreach ($colOrderArr as $curOrder){
			$curOrderArr = explode(' ',trim($curOrder));
			self::$currentSortOrder[] = new TableSortElem($curOrderArr[0],
			isset($curOrderArr[1])?$curOrderArr[1]:'');
		}
	}
	
	
	
	function AddColumn($colName, $colTitle = 0) {
		
		// Проверим ддопустимости имени колонки
		if (is_numeric ( $colName )) {
			throw new Exception ( 'Table class: Columns name must be a string, not number!', 1001 );
		}
		if (! is_string ( $colName )) {
			throw new Exception ( 'Table class: Columns name must be a string!', 1003 );
		}
		// Проверим уникальность имени колонки
		foreach ( $this->Columns as $col ) {
			if ($col->Name == $colName) {
				throw new Exception ( 'Table class: Not unique column name!', 1002 );
			}
		}
		// Создадим колонку
		$this->Columns [] = new Table_Column ( $colName, $colTitle );
		// Добавим колонку в строки
		foreach ( $this->Rows as $row ) {
			$row->Row [$colName] = '';
		}
	}
	
	function DeleteColumn($num) {
		if (isset ( $this->Columns [$num] )) {
			unset ( $this->Columns [$num] );
			//Переиндексируем
			$temp = array();
			$this->Columns = array_merge($temp,$this->Columns);
			foreach ( $this->Rows as $row ) {
				unset ( $row->Row [$num] );
			//Переиндексируем
			$row->Row = array_merge($temp, $row->Row);	
			}
		
		}
	
	}
	
	function Add() {
		$newRow = new Table_Row ( $this->Columns );
		$this->Rows [] = $newRow;
		$newRow->SetIndex ( count ( $this->Rows ) - 1 );
		return $newRow;
	}
	
	function Delete($row) {
	
	}
	
	//***************************************************************************
	//  Array Accessa
	function offsetExists($offset) {
		$offset = strtolower ( $offset );
		return isset ( $this->Rows [$offset] );
	}
	function offsetGet($offset) {
		$offset = strtolower ( $offset );
		return $this->Rows [$offset];
	}
	function offsetSet($offset, $data) {
		throw new Exception ( 'Table class: You can not use Table[...] as leftvalue!', 1004 );
	}
	function offsetUnset($offset) {
		$this->Delete ( $offset );
	}
	// Array Access 
	//***************************************************************************	
	

	//---------------------------------------------------------------------------
	// Itaretor Agregate
	

	function getIterator() {
		return new Table_Rows_Iterator ( $this );
	}
	
	// Itaretor Agregate	
	//---------------------------------------------------------------------------
	

	function Load($res) {
		global $Tables_Columns_Titles;
		if ($currRow = mysql_fetch_assoc ( $res )) {
			$newStr = $this->Add ();
			foreach ( $currRow as $col => $val ) {
				$this->AddColumn ( $col, isset($Tables_Columns_Titles[$col])?
										 $Tables_Columns_Titles[$col]:$col );
				$newStr->Row [$col] = $val;
			}
			while ( $currRow = mysql_fetch_assoc ( $res ) ) {
				$newStr = $this->Add ();
				$newStr->Row = $currRow;
			}
		}
	
	}

}


?>