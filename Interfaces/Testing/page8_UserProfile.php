<?php
$PAGE_LINK = SITE_PATH.$GURL;
unset($actionResult);
$memberLoc = new Locations();
$memberLoc->GetByID(Econgress::$Member->memberLocation);
if ($memberLoc->Selected) {
	$Loc = $memberLoc->Title;
} else {
	$Loc = '';
}
$memberLocID = Econgress::$Member->memberLocation;
$modalChangeNameLink = new ModalBox("{$PAGE_LINK}&f=17",i_ChangeUserData);
$modalChangePass = new ModalBox("{$PAGE_LINK}&f=19&attr=8",i_ChangeUserPassword,250);
$ChangeNameLink = '<a href="" '.$modalChangeNameLink.'><img src="Interfaces/Testing/Images/change_medium.png"></a>';
$modalChangeLocation = new ModalBox("{$PAGE_LINK}&f=18&id={$memberLocID}",i_UserReLocation);
$ChangeLocationLink = '<a href=""'.$modalChangeLocation.'><img src="Interfaces/Testing/Images/location.png"></a>';
$ChangePassLink = '<a href="" '.$modalChangePass.'><img src="Interfaces/Testing/Images/password.png"></a>';
$nextReLocation = Date("F j, Y, g:i a",strtotime(Econgress::$Member->LocationDate)+
		   			timer(Econgress::$Constants->GetValue('TimeBetweenReLocations')));

$page = 
'<table style="font-size:12px;">'.
'<tr>'.
'<td align="right">'.i_userName.':</td><td>'.Econgress::$Member->userName.'</td>'.
'<td rowspan="8" width="10"></td>'.
'<td rowspan="3">'.$ChangeNameLink.'</td></tr>'.
'<tr>'.
'<td align="right">'.i_firstName.':</td><td>'.Econgress::$Member->firstName.'</td></tr>'.
'<tr>'.
'<td align="right">'.i_lastName.':</td><td>'.Econgress::$Member->lastName.'</td></tr>'.  
'<tr>'.
'<td align="right">'.i_Email.':</td><td>'.Econgress::$Member->Email.'</td>'.
'<td rowspan="2">'.$ChangePassLink.'</td></tr>'.
'<tr>'.
'<td align="right">'.i_Language.':</td><td>'.Econgress::$Member->Language.'</td></tr>'.
'<tr>'.
'<td align="right">'.i_LocationDate.':</td><td>'.date("F j, Y, g:i a", strtotime(Econgress::$Member->LocationDate)).'</td>
<td rowspan="3">'.$ChangeLocationLink.'</td></tr>'.
'<tr>'.
'<td align="right">'.i_memberLocation.':</td><td>'.$Loc.'</td></tr>'.
'<tr>'.
'<td align="right">'.i_NextReLocationNotEarly.':</td><td>'.$nextReLocation.'</td></tr>'.


'</table>';

echo '<table height="100%"><tr><td height="400" align="center" valign="top">'.
'<div style="overflow: auto; max-height:250px;">'.$page.'</div></td></tr>';
echo '<tr><td height="110" valign="bottom">'.SysMessagesTable(100).'</td></tr></table>';

?>