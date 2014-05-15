<?php
$curPage = clone $GURL; 
$curPage->page="";
$PAGE_LINK = SITE_PATH.$curPage;
	$NotesModelLink = new ModalBox(SITE_PATH.$GURL."&f=88",i_QuickNotes,380);
echo '<table cellpadding="2" cellspacing="2">
<tr>'.
'<td><a href="'.$PAGE_LINK.'&p=1" style="font-weight: bolder; font-size: 11px;">'.i_MainPage.'</a></td>'.'<td>&nbsp;</td>'.
'<td><a href="'.$PAGE_LINK.'&p=2" style="font-weight: bolder; font-size: 11px;">'.i_InitiativesSignsPage.'</a></td>'.'<td>&nbsp;</td>'.
'<td><a href="'.$PAGE_LINK.'&p=3" style="font-weight: bolder; font-size: 11px;">'.i_Voting.'</a></td>'.'<td>&nbsp;</td>'.
'<td><a href="'.$PAGE_LINK.'&p=12" style="font-weight: bolder; font-size: 11px;">'.i_Decisions.'</a></td>'.'<td>&nbsp;</td>'.
'<td><a href="'.$PAGE_LINK.'&p=4" style="font-weight: bolder; font-size: 11px;">'.i_MyInitiativesPage.'</a></td>'.'<td>&nbsp;</td>'.
'<td><a href="'.$PAGE_LINK.'&p=5" style="font-weight: bolder; font-size: 11px;">'.i_DelegationsPage.'</a></td>'.'<td>&nbsp;</td>'.
'<td><a href="'.$PAGE_LINK.'&p=6" style="font-weight: bolder; font-size: 11px;">'.i_ToClassifyPage.'</a></td>'.'<td>&nbsp;</td>'.
'<td><a href="'.$PAGE_LINK.'&p=7" style="font-weight: bolder; font-size: 11px;">'.i_ClassificationPage.'</a></td>'.'<td>&nbsp;</td>'.
'<td><a href="'.$PAGE_LINK.'&p=8" style="font-weight: bolder; font-size: 11px;">'.i_ProfilePage.'</a></td>'.'<td>&nbsp;</td>'.
'<td><a href="'.$PAGE_LINK.'&p=9" style="font-weight: bolder; font-size: 11px;">'.i_Members.'</a></td>'.'<td>&nbsp;</td>'.
'<td><a href="'.$PAGE_LINK.'&p=10" style="font-weight: bolder; font-size: 11px;">'.i_LocationsTree.'</a></td>'.'<td>&nbsp;</td>'.
'<td><a href="'.$PAGE_LINK.'&p=11" style="font-weight: bolder; font-size: 11px;">'.i_AdministrativePage.'</a></td>'.'<td>&nbsp;</td>'.
'<td><a href="#" '.$NotesModelLink.' style="font-weight: bolder; font-size: 11px;">'.i_QuickNotes.'</a></td>'.'<td>&nbsp;</td>'.
'<td><a href="'.$PAGE_LINK.'&a=102" style="font-weight: bolder; font-size: 11px;">'.i_Logout.'</a></td>'.'<td>&nbsp;</td>'.
'</tr></table>';
echo 'Current user:' . Econgress::$Member->userName . ' (' . Econgress::$Member->firstName . ' ' . Econgress::$Member->lastName . ').<br>';
?>
