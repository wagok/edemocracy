<?php
$id = var_get('id');
$SignActionLink = '<a href="'.$PAGE_LINK.'&a=501&id='.$id.'"><img src="Interfaces/Testing/Images/sign.gif"></a>';
$UnsignActionLink = '<a href="'.$PAGE_LINK.'&a=502&id='.$id.'"><img src="Interfaces/Testing/Images/crossed_sign.gif"></a>';
echo '<table align="center" class="NoBorderTable">';
echo '<tr><td>'.$SignActionLink.'</td><td width="40"></td><td>'.$UnsignActionLink.'</td></tr>
<tr><td align="center">'.i_Sign.'</td><td></td><td align="center">'.i_Decline.'</td></tr></table>';
?>