<?php
function var_get($varname) {
	global $_REQUEST;
	if (isset ( $_REQUEST [$varname] )) {
		return $_REQUEST [$varname];
	} else {
		return '';
	}
}
class URLparams {
	public $interface;
	public $page; 
	public $form;
	public $action;
	function LoadCurrent() {
		$this->page = var_get('p');
		$this->interface = var_get('i');
		$this->form = var_get('f');
		$this->action = var_get('a');
	}
	function __toString() {
		
		if (!isset($this->interface) || !is_numeric($this->interface)) {
		$this->interface='0';
		}
		$str = FILE_NAME.'?i='.$this->interface;
		if (isset($this->page) && $this->page!='') {
			$str.= '&p='.$this->page;
		}
		if (isset($this->form) && $this->form!='') {
			$str.= '&f='.$this->form;
		}
		if (isset($this->action) && $this->action!='') {
			$str.= '&a='.$this->action;
		}
		return $str;
	}
}

function ob_linearize($text) {
//	mb_internal_encoding("UTF-8");
//	mb_regex_encoding("UTF-8");
//	return mb_ereg_replace('/[\r\n\s]+/s',' ',trim($text));
	//return preg_replace('/[\r\n\s]+/s',' ',trim($text));
	return $text;
//	return preg_replace("/(<\/?)(\w+)([^>]*>)/e","'\\1'.strtoupper('\\2').'\\3'",$text);
	
}

function timer($period) {
	// входной формат "день.месяц.год часы:минуты:секунды" 
	// формат может быть как 00 так и 0 
	//$period = trim ( $period );
	//list ($date,$time) = explode(' ',$period);
	//list ( $day, $months, $year ) = explode ( '.', $date );
	//list ($hours,$minuts,$secunds) = explode (':',$time);
	$d = date_parse($period);
	return $d['second']+($d['minute']*60)+($d['hour']*3600)+($d['day']*86400)+($d['month']*2592000)+($d['year']*31536000);
}


?>