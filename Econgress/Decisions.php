<?php
class Decisions extends SPRDB {
	public $Initiative, $votesPro, $votesCon;
	function GetTableName() {
		return 'Decisions';
	}
	function GetFieldsList() {
		return parent::GetFieldsList().', Initiative, votesPro, votesCon';
	}
}
?>