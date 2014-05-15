<?php
$loginform = new form('econgress.php?i=0&p=0&f=0&a=101');
$loginform->AddElement('userName','User:');
$loginform->AddElement('password','Password:','password');
$loginform->AddElement('Login','','submit','LogIn');

if ($GURL->action=='101') { 
	$res = $loginform->fromHTML();
	if (Econgress::LogIn($res['userName'],$res['Password'])) {
		$GURL->action = '';
		header('Location: '.SITE_PATH.$GURL);
		exit();
	} else {
		echo 'Wrong user name or password';
	}
} else
{
	echo $loginform->toHTML();
}

?>