<?php
echo '<form method="post" action="'.$PAGE_LINK.'&a=301">'.
'<p>'.i_EnterInitiativeData.':</p>
<table class="NoBorderTable" width="100%">
<tr>
	<td width="100%" align="center" colspan="2">
	<select  name="Location" id="Location">';
$Loc = new Locations();
$Loc->GetByID(Econgress::$Member->memberLocation);
$Loc->SelectAllParents();
while ($Loc->Selected) {
	echo '<option value="'.$Loc->id.'">'.$Loc->Title.'</option>';
	
	$Loc->Next();	
}
echo '</select></td>
</tr>
<tr>
	<td width="100%" align="center" colspan="2">
	<label for="Title">'.i_InitiativeTitle.':</label><input type="text" name="Title" id="Title" cols="80" length="255"></td>
</tr>
<tr>
	<td width="100%" align="center" colspan="2">
	'.i_InsertInitiativeDescriptionHere.':
	<textarea rows="10" cols="80" name="Description" id="Description"></textarea></td>
</tr>
<tr>
	<td width="100%" align="center" colspan="2">
	'.i_InsertInitiativeInformationHere.':
	<textarea rows="10" cols="80" name="Information" id="Information"></textarea></td>
</tr>
<tr>
	<td align="center" width="50%"><input type="submit" name="Save" value="'.i_Save.'">
	</td><td align="center" width="50%"><input type="button" name="Cancel" value="'.i_Cancel.'" onclick="Modalbox.hide(); return false;"></td>
</tr>
</form>';
?>