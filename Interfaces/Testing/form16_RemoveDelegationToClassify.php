<?php
$id = var_get('id');
$mess= sprintf(i_PleaseConfirmClassifyRightRemoving, $id);

echo $mess;
$Yes = '<a href="'.$PAGE_LINK.'&a=206&id='.$id.'">'.i_Delete.'</a>';
$No = '<a href="#" onclick="Modalbox.hide(); return false;">'.i_Cancel.'</a>';
echo "<table><tr height=\"30\"><td></td><td></td></tr>";
echo '<tr><td>'.$Yes.'</td><td>'.$No.'</td></tr></table>';
?>