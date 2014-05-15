<?php
class ReglOper extends SPRDB {
	public $time_start;
	public $Operation, $StartTime, $Duration, $Result;

	function GetTableName() {
		return 'ReglOper';
	}
	function GetFieldsList() {
		return parent::GetFieldsList().'Operation, StartTime, Duration, Result';
	}
	function Start() {
		$this->StartTime = time();
		$this->time_start = microtime(1);
	}
	function End() {
		$time_end = microtime(1);
		$this->Duration = $time_end - $time_start;
	}

}

?>