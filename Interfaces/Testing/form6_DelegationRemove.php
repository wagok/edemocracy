<?php
$id = var_get('id');
$RemoveLink = '<a href="'.$PAGE_LINK.'&a=402&id='.$id.'">'.i_Delete.'</a>';
$CancelLink = '<a href="'.$PAGE_LINK.'" onclick="Modalbox.hide(); return false;">'.i_Cancel.'</a>';
echo "<table><tr height=\"30\"><td></td><td></td></tr>";
echo '<tr><td>'.$RemoveLink.'</td><td>'.$CancelLink.'</td></tr></table>';
?>