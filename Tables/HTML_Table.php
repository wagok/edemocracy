<?php
require_once 'table.php';
class Table_HTML_RowsCells_Tags {
	public $RowsTags;
	public $CellsTags;
}

class Table_HTML_Design {
	public $tableTags;
	public $tbodyTags;
	public $headerTags;
	public $bodyTags;
	public $footerTags;
	
	public $includeHeader;
	public $includeFooter;
	
	public $evenRowsTags;
	public $oddRowsTags;
	function __construct() {
		$this->headerTags = new Table_HTML_RowsCells_Tags ( );
		$this->bodyTags = new Table_HTML_RowsCells_Tags ( );
		$this->footerTags = new Table_HTML_RowsCells_Tags ( );
		$this->evenRowsTags = new Table_HTML_RowsCells_Tags ( );
		$this->oddRowsTags = new Table_HTML_RowsCells_Tags ( );
	}
}

class HTMLtable {
	public $Source = false;
	public $Design;
	public $Columns = array ();
	public $OnRowOutput = false;
	public $OnCellOutput = false;

	
	function __construct() {
		$this->Design = new Table_HTML_Design ( );
	}
	function setSource(Table $source) {
		$this->Source = $source;
	}
	function CreateColumns() {
		if ($this->Source != false) {
			foreach ( $this->Source->Columns as $col ) {
				$this->Columns [] = clone $col;
			}
		
		}
	}
	function ChangeColumns($col1, $col2) {
		if (is_numeric ( $col1 ) && is_numeric ( $col2 )) {
			$temp = $this->Columns [$col1];
			$this->Columns [$col1] = $this->Columns [$col2];
			$this->Columns [$col2] = $temp;
		}
	}
	function AddColumn($colName, $colTitle = 0) {
		
		// Подключим колонку источника 
		// Пробуем по индексу
		if (is_numeric ( $colName ) && isset ( $this->Source->Columns [$colName] )) {
			$this->Columns [] = clone $this->Source->Columns [$colName];
		} else {
			// Пробуем по имени	
			foreach ( $this->Source->Columns as $col ) {
				if ($colName == $col->Name) {
					$this->Columns [] = clone $col;
				}
			}
		}
	}
	function SetColumnTag($colName, $Tag){
		// Пробуем по индексу
		if (is_numeric ( $colName ) && isset ( $this->Columns [$colName] )) {
			$this->Columns [$colName]->ColumnTags = $Tag;
		} else {
			// Пробуем по имени	
			foreach ( $this->Columns as $col ) {
				if ($colName == $col->Name) {
					$col->ColumnTags = $Tag;
				}
			}
		}		 
	}
	
	function DeleteColumn($num) {
		if (isset ( $this->Columns [$num] )) {
			unset ( $this->Columns [$num] );
		} else {
			// Пробуем по имени	
		foreach ( $this->Columns as $key => $col ) {
				if ($num == $col->Name) {
					unset($this->Columns[$key]);
				}
		}
		}
		// Переиндексируем массив
		$temp = array ();
		$this->Columns = array_merge ( $temp, $this->Columns );
	}
	function toHTML() {
		$str = '<table  ' . $this->Design->tableTags . '><thead>';
		if ($this->Design->includeHeader) {
			$str .= '<tr ' . $this->Design->headerTags->rowsTags . '>';
			foreach ( $this->Columns as $Col ) {
				$str .= '<th ' . $this->Design->headerTags->cellsTags .($Col->Width==false?'':' width="'.$Col->Width.'" '). '>' . $Col->Title . '</th>';
			}
			$str .= '</tr></thead><tbody '.$this->Design->tbodyTags.' >';
		}
		$odd = true;
		foreach ( $this->Source as $currRow ) {
			
			if ($this->OnRowOutput != false) {
				// Если определена пользовательская функция при выводе строки, то сохраним копию 
				// глобального дизайна
				$tempBodyDesign = clone $this->Design;
				if (call_user_func ( $this->OnRowOutput, $currRow, $odd, $this->Design )) {
					continue;
				}
			}
			if ($odd) {
				$str .= '<tr ' . $this->Design->bodyTags->rowsTags . ' ' . $this->Design->oddRowsTags->rowsTags . '>';
				$odd = false;
			} else {
				$str .= '<tr ' . $this->Design->bodyTags->rowsTags . ' ' . $this->Design->evenRowsTags->rowsTags . '>';
				$odd = true;
			}
			foreach ( $this->Columns as $Col ) {
				if ($this->OnCellOutput != false) {
					// Если определена пользовательская функция при выводе ячейки, 
					// то сохраним копию глобального дизайна
					$tempBodyDesignCell = clone $this->Design;
					call_user_func ( $this->OnCellOutput, $currRow, $Col, $odd, $this->Design );
				}
				
				$str .= '<td ' . $this->Design->bodyTags->cellsTags .' '.$Col->ColumnTags. '>' . $currRow->Row [$Col->Name] . '</td>';
				
				if ($this->OnRowOutput != false) {
					// Объект глобального дизайна мог быть изменен
					// восстановим его для следующих колонок
					$this->Design = $tempBodyDesignCell;
				}
			}
			$str .= '</tr>';
			if ($this->OnRowOutput != false) {
				// Объект глобального дизайна мог быть изменен
				// восстановим его для следующих строк
				$this->Design = $tempBodyDesign;
			}
		}
		if ($this->Design->includeFooter) {
			$str .= '<tr ' . $this->Design->footerTags->rowsTags . '>';
			foreach ( $this->Columns as $Col ) {
				$str .= '<td ' . $this->Design->headerTags->cellsTags . '>&nbsp;' . '</td>';
			}
			$str .= '</tr>';
		}
		$str .= '</tbody></table>';
		return $str;
	}
}
?>