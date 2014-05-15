<?php
$id = var_get('id');
$Location = new Locations();
$Location->GetByID($id);
if ($Location->Selected) {
echo '<form method="post" action="'.$PAGE_LINK.'&a=702&id='.$Location->id.'">'.
'<p>'.i_EnterLocationElementData.':</p>
<table class="NoBorderTable" width="100%">';
echo '<tr>
	<td width="100%" align="center" colspan="2">
	<label for="Title">'.i_LocationElementTitle.':</label><input type="text" name="Title" id="Title" cols="80" length="255"
	value="'.$Location->Title.'"></td>
</tr>
<tr>
	<td width="100%" align="center" colspan="2">
	'.i_InsertLocationElementDescriptionHere.':
	<textarea rows="10" cols="80" name="Description" id="Description">'.$Location->Description.'</textarea></td>
</tr>
<tr>
	<td align="center" width="50%"><input type="submit" name="Save" value="'.i_Save.'">
	</td><td align="center" width="50%"><input type="button" name="Cancel" value="'.i_Cancel.'" onclick="Modalbox.hide(); return false;"></td>
</tr>
</form>';}
?>