<?php
// Значения констант
$RatingTime = Econgress::$Constants->GetValue('RatingTime');
$RatingTimeDate = date_parse($RatingTime); 
$RatingTimeStr =  $RatingTimeDate['year'].'-'.$RatingTimeDate['month'].'-'.$RatingTimeDate['day'].' '.
				$RatingTimeDate['hour'].':'.$RatingTimeDate['minute'].':'.$RatingTimeDate['second'];
$MinRatingPercent = Econgress::$Constants->GetValue('MinRatingPercent');
$ClassificationTime = Econgress::$Constants->GetValue('ClassificationTime');
$ClassificationTimeDate = date_parse($ClassificationTime); 
$ClassificationTimeStr = $ClassificationTimeDate['year'].'-'.$ClassificationTimeDate['month'].'-'.$ClassificationTimeDate['day'].' '.
				$ClassificationTimeDate['hour'].':'.$ClassificationTimeDate['minute'].':'.$ClassificationTimeDate['second'];
$MinVotingsMembersPercent = Econgress::$Constants->GetValue('MinVotingsMembersPercent');
$TimeBetweenReLocations = Econgress::$Constants->GetValue('TimeBetweenReLocations');
$TimeBetweenReLocationsDate = date_parse($TimeBetweenReLocations);
$TimeBetweenReLocationsStr = $TimeBetweenReLocationsDate['year'].'-'.$TimeBetweenReLocationsDate['month'].'-'.$TimeBetweenReLocationsDate['day'].' '.
				$TimeBetweenReLocationsDate['hour'].':'.$TimeBetweenReLocationsDate['minute'].':'.$TimeBetweenReLocationsDate['second'];

echo '<form method="post" action="'.$PAGE_LINK.'&p=11&a=806">'.
'<fieldset><legend>'.i_Constants.'</legend>';
echo '<table class="NoBorderTable"><tr>';
new form_elem('RatingTime',i_RatingTime.' ('.$RatingTimeStr.')','text',$RatingTime);
echo '<td width="20"></td>';
new form_elem('MinRatingPercent',i_MinRatingPercent,'text',$MinRatingPercent);
echo '</tr><tr>';
new form_elem('ClassificationTime',i_ClassificationTime.' ('.$ClassificationTimeStr.')','text',$ClassificationTime);
echo '<td width="20"></td>';
new form_elem('MinVotingsMembersPercent',i_MinVotingsMembersPercent,'text',$MinVotingsMembersPercent);
echo '</tr><tr>';
new form_elem('TimeBetweenReLocations',i_TimeBetweenReLocations.' ('.$TimeBetweenReLocationsStr.')','text',$TimeBetweenReLocations);
echo '<td></td><td></td><td align="right"><input type="submit" name="save_constants" value="'.i_Save.'">&nbsp;&nbsp;</td>';
echo '</tr></table></fieldset></form>';

// Операции с пользователями
$modalChangeLocation = new ModalBox("{$PAGE_LINK}&f=24",i_UserReLocation);
$modalGroupReLocate =  new ModalBox("{$PAGE_LINK}&f=25",i_GroupRelocation);
$modalNewUserFrom = new ModalBox("{$PAGE_LINK}&f=26",i_NewUser);

echo '<fieldset><legend>'.i_MembersOperations.'</legend>';
echo '<table class="NoBorderTable">';
echo '<tr><td><a href="#"'.$modalChangeLocation.'>'.i_AdminUserRelocation.'</a></td></tr>';
echo '<tr><td><a href="#"'.$modalGroupReLocate.'>'.i_GroupRelocation.'</a></td></tr>';
echo '<tr><td><a href="#"'.$modalNewUserFrom.'>'.i_NewUser.'</a></td></tr>';
echo '</table></fieldset>';

echo '<fieldset><legend>'.i_ReglamentOperations.'</legend>';
echo '<table class="NoBorderTable">';
echo '<tr><td><a href="'."{$PAGE_LINK}&p=11&a=901".'">'.i_RatingReglament.'</a></td></tr>';
echo '<tr><td><a href="'."{$PAGE_LINK}&p=11&a=902".'">'.i_VotingReglament.'</a></td></tr>';
echo '</table></fieldset>';

?>