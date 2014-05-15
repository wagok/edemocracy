<?php
class delegationsToClassify extends SPRDB {
	public $Classification, $delegateTo;
	function GetTableName() {
		return 'DelegationsToClassify';
	}
	function GetFieldsList() {
		return parent::GetFieldsList().', Classification, delegateTo';
	}
}
?>