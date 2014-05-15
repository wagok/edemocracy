<?php
$newform = new form($PAGE_LINK .'&a=807&p=11');
$newform->AddElement('userName',i_userName.':','text','');
$newform->AddElement('firstName',i_firstName.':','text','');
$newform->AddElement('lastName',i_lastName.':','text','');
$newform->AddElement('newPass',i_newPassword.':','password','');
$newform->AddElement('newPass2',i_reNewPassord.':','password','');
$newform->AddElement('Email',i_Email.':','text','');
$newform->AddElement('Language',i_Language.':','select',array(
array(value=>'english',title=>'English', ''),
array(value=>'russian',title=>'Russian', ''),
array(value=>'german',title=>'German', '') ));
$newform->AddElement('save',i_Save,'submit',i_Save);
echo $newform->toHTML();

?>