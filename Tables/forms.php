<?php
require_once 'Tables.php';

class form {
	public $Elements;
	public $Method;
	public $Action;
	public $FormTag;
	function __construct($act,$method='post',$formTags=''){
		 $this->Elements = new Table();
		 $this->Elements->AddColumn('Name');
		 $this->Elements->AddColumn('Title');
		 $this->Elements->AddColumn('Type');
		 $this->Elements->AddColumn('Value');
		 $this->Elements->AddColumn('Tag');
		 $this->Method = $method;
		 $this->Action = $act;
		 $this->FormTag =$formTags;
	}
	function AddElement($Name, $Title, $Type='text', $Value='', $Tag='') {
		$elem = $this->Elements->Add();
		$elem->Name = $Name;
		$elem->Title= $Title;
		$elem->Type= $Type;
		$elem->Value= $Value;
		$elem->Tag = $Tag;
	}
	static function var_get($varname){
		global $_REQUEST;
		if (isset ( $_REQUEST [$varname] )) {
		return $_REQUEST [$varname];
	} else {
		return '';
	}
	}
	function fromHTML(){
		foreach ($this->Elements as $elem) {
			//	switch ($elem->Type) {
							
			//	}
			$elem->Value = self::var_get($elem->Name);
			$result[$elem->Name]=self::var_get($elem->Name);
		}
		return $result;
	}
	function toHTML() {
	$str = '<form method="'.$this->Method.'" action="'.$this->Action.'" '.$this->FormTag.'>';
	$str .='<table>';
	foreach ($this->Elements as $elem) {
		switch ($elem->Type) {
			case 'select':
			$str .= '<tr><td><label for="'.$elem->Name.'">'.$elem->Title.'</label></td>';
			$str .= '<td><select name="'.$elem->Name.'" id="'.$elem->Name.'" size="1">';
			
			foreach ($elem->Value as $opt) {
				$selected = $opt['selected']?' selected':'';
				$str.='<option value="'.$opt['value'].'" '.$selected.'>'.$opt['title'].'</option>';
			}
			$str .= '</select></td></tr>';
			break;	
			default:
			$str .= '<tr><td>'.
					'<label for="'.$elem->Name.'">'.$elem->Title.'</label></td>'.
					'<td><input type="'.$elem->Type.'" name="'.$elem->Name.
					'" id="'.$elem->Name.'" value="'.$elem->Value.'" '.$elem->Tag.'></td></tr>';
		}
	}
	
	$str .='</table>';
	$str .='</form>';
	return $str;
	}
}
class form_elem {
	function __construct($Name, $Title, $Type='text', $Value='', $Tag='') {
			switch ($Type) {
			case 'select':
			$str .= '<td><label for="'.$Name.'">'.$Title.'</label></td>';
			$str .= '<td><select name="'.$Name.'" id="'.$Name.'" size="1"></td>';
			
			foreach ($Value as $opt) {
				$selected = $opt['selected']?' selected':'';
				$str.='<option value="'.$opt['value'].'" '.$selected.'>'.$opt['title'].'</option>';
			}
			$str .= '</select>';
			break;	
			default:
			$str .= '<td><label for="'.$Name.'">'.$Title.'</label></td>'.
					'<td><input type="'.$Type.'" name="'.$Name.
					'" id="'.$Name.'" value="'.$Value.'" '.$Tag.'></td>';
		}
		echo $str;		
	}
}
?>