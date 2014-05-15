<?php
class Delegations extends SPRDB {
	public $delegateFrom, $delegateTo, $delegateType, $Classification, $Exclude;
	function GetTableName() {
		return 'Delegations';
	}
	function GetFieldsList() {
		return parent::GetFieldsList().', delegateFrom, delegateTo, delegateType, Classification, Exclude';
	}
}
?>