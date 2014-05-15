<?php
$id = var_get('id');
$Classification = new Classifications();
$Classification->GetByID($id);
if (!$Classification->Selected) {
	die;
}
if ($Classification->Classification == $Classification->id) {
	// Удаление всей классификации
	$Yes = '<a href="'.$PAGE_LINK.'&a=204&id='.$id.'">'.i_Delete.'</a>';
	$mess= sprintf(i_PleaseConfirmRemovingWholeClassification, $Classification->id, 
	$Classification->Title, ($Classification->right_key-$Classification->left_key-1)/2);
	
} else {
	// Удаление ветви классификации
	$mess= sprintf(i_PleaseConfirmRemovingClassificationElement, $Classification->id, 
	$Classification->Title, ($Classification->right_key-$Classification->left_key-1)/2);
	$Yes = '<a href="'.$PAGE_LINK.'&a=203&id='.$id.'">'.i_Delete.'</a>';
}
echo $mess;

$No = '<a href="#" onclick="Modalbox.hide(); return false;">'.i_Cancel.'</a>';
echo "<table><tr height=\"30\"><td></td><td></td></tr>";
echo '<tr><td>'.$Yes.'</td><td>'.$No.'</td></tr></table>';
?>