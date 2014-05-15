<?php
$attr = var_get('attr');
if ($attr=='') {
	if ($_SESSION['LastActionResult']===true) {
		echo i_PasswordHasChanged.'<br>';
		echo '<input type="button" onclick="Modalbox.hide(); window.location.reload();" name="OK" id="OK" value="'.i_OK.'">';	
	} else {
		$modalChangePass = new ModalBox("{$PAGE_LINK}&f=19&attr=8",i_ChangeUserPassword,250);
		echo i_PasswordHasNotChanged.'<br>';
		echo $_SESSION['LastActionResult'].'<br>';
		echo '<div align="center"><input type="button" '.$modalChangePass.'  name="Cancel" id="Cancel" value="'.i_ReTry.'">';		
		echo '<input type="button" onclick="Modalbox.hide(); window.location.reload();" name="Cancel" id="Cancel" value="'.i_Cancel.'"></div>';		
	}
} else {
$modalChangePass = new ModalBox("{$PAGE_LINK}&a=104&f=19",i_ChangeUserPassword,250,0,0,true);
echo '<div align="center">';
$newform = new form('#',"post",' name="myform" id="myform" ');
$newform->AddElement('oldPass',i_oldPassword.':','password','');
$newform->AddElement('newPass',i_newPassword.':','password','');
$newform->AddElement('newPass2',i_reNewPassord.':','password','');
$newform->AddElement('save','','submit',i_Save,$modalChangePass);
echo $newform->toHTML();
echo '</div>';
}
?>