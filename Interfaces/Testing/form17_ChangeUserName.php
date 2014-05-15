<?php
$newform = new form($PAGE_LINK .'&a=107');
$newform->AddElement('firstName',i_firstName.':','text',Econgress::$Member->firstName);
$newform->AddElement('lastName',i_lastName.':','text',Econgress::$Member->lastName);
$newform->AddElement('Email',i_Email.':','text',Econgress::$Member->Email);
$newform->AddElement('Language',i_Language.':','select',array(
array(value=>'english',title=>'English', selected=>(Econgress::$Member->Language=='english')?true:false),
array(value=>'russian',title=>'Russian', selected=>(Econgress::$Member->Language=='russian')?true:false),
array(value=>'german',title=>'German', selected=>(Econgress::$Member->Language=='german')?true:false) ));
$newform->AddElement('save',i_Save,'submit',i_Save);
echo $newform->toHTML();
?>