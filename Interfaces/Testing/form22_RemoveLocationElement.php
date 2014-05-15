<?php
$id = var_get('id');
$Location = new Locations();
$Location->GetByID($id);
if (!$Location->Selected) {
	die;
}

	// Удаление ветви классификации
	$mess= sprintf(i_PleaseConfirmRemovingLocationElement, $Location->id, 
	$Location->Title, ($Location->right_key-$Location->left_key-1)/2);
	$Yes = '<a href="'.$PAGE_LINK.'&a=703&id='.$id.'">'.i_Delete.'</a>';

echo $mess;

$No = '<a href="#" onclick="Modalbox.hide(); return false;">'.i_Cancel.'</a>';
echo "<table><tr height=\"30\"><td></td><td></td></tr>";
echo '<tr><td>'.$Yes.'</td><td>'.$No.'</td></tr></table>';
?>