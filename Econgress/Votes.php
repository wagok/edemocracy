<?php
class Votes extends SPRDB {
	public $Initiative, $Member, $DelegateBackFrom, $Classification, $ProAndCon, $Passive;
	function GetTableName() {
		return 'Votes';
	}
	function GetFieldsList() {
		return parent::GetFieldsList().', Initiative, Member, DelegateBackFrom, Classification, Passive, ProAndCon';
	}
}
?>