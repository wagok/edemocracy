<?php
class ModalBox {
	public $url;
	public $title;
	public $width;
	public $height;
	public $opacity;
	public $serialize;
	function __construct($url,$title,$width=0,$height=0, $opacity=0, $serialize=false) {
		$this->url = $url;
		$this->title = $title;
		$this->width = $width;
		$this->height= $height;
		$this->opacity=$opacity;
		$this->serialize=$serialize;
	}
	function __toString() {
		$width = $this->width!=0?(', width: '.$this->width):'';
		$height = $this->height!=0?(', height: '.$this->height):'';
		$opacity = $this->opacity!=0?(', overlayOpacity: '.$this->opacity):', overlayOpacity: 0.3';
		$serialize = $this->serialize?(",params: Form.serialize('myform')"):'';
		$str = "onclick=\"Modalbox.show('{$this->url}', {title:'{$this->title}'{$width}{$height}{$opacity}{$serialize}});return false;\"";
		return $str;
	}
}
?>