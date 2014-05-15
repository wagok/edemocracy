<?php
$id = var_get('id');
$SignActionLink = '<a href="'.$PAGE_LINK.'&a=601&id='.$id.'"><img src="Interfaces/Testing/Images/Checked-Box-Yes.jpg"></a>';
$UnsignActionLink = '<a href="'.$PAGE_LINK.'&a=602&id='.$id.'"><img src="Interfaces/Testing/Images/Checked-Box-No.jpg"></a>';
echo '<table align="center" class="NoBorderTable">';
echo '<tr><td>'.$SignActionLink.'</td><td width="40"></td><td>'.$UnsignActionLink.'</td></tr>
<tr><td align="center">'.i_Pro.'</td><td></td><td align="center">'.i_Con.'</td></tr></table>';
?>