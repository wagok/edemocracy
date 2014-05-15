<?php
$id = var_get('id');
$ParentElement = new Classifications();
$ParentElement->GetByID($id);
if (!$ParentElement->Selected) {
	die;
}

echo '<form method="post" action="'.$PAGE_LINK.'&a=202&id='.$ParentElement->id.'">'.
'<p>'.i_EnterClassificationElementData.':</p>
<table class="NoBorderTable" width="100%">';

$formInfo = sprintf (i_CreatingChildClassificElemFrom, $ParentElement->id, $ParentElement->Title);
echo '<tr>
	<td width="100%" align="center" colspan="2">'.
$formInfo.
	'</td>
</tr>';

echo '<tr>
	<td width="100%" align="center" colspan="2">
	<label for="Title">'.i_ClassificationElementTitle.':</label><input type="text" name="Title" id="Title" cols="80" length="255"></td>
</tr>
<tr>
	<td width="100%" align="center" colspan="2">
	'.i_InsertClassificationElementDescriptionHere.':
	<textarea rows="10" cols="80" name="Description" id="Description"></textarea></td>
</tr>
<tr>
	<td width="100%" align="center" colspan="2">
	'.i_InsertClassificationElementInformationHere.':
	<textarea rows="10" cols="80" name="Information" id="Information"></textarea></td>
</tr>
<tr>
	<td align="center" width="50%"><input type="submit" name="Save" value="'.i_Save.'">
	</td><td align="center" width="50%"><input type="button" name="Cancel" value="'.i_Cancel.'" onclick="Modalbox.hide(); return false;"></td>
</tr>
</form>';
?>