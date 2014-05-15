<?php
class ClassificatedInitiatives extends SPRDB {
	public $Initiative, $Classification;
	function GetTableName() {
		return 'ClassificatedInitiatives';
	}
	function GetFieldsList() {
		return parent::GetFieldsList().', Initiative, Classification';
	}
}
?>