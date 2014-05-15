<?php
$id = var_get('id');
$class = new Classifications();
$class->GetByID($id);
if ($class->Selected) {
echo '<form method="post" action="'.$PAGE_LINK.'&a=209&id='.$id.'">'.
'<p>'.i_InsertClassificationInformationHere.':</p><table class="NoBorderTable" width="100%"><tr><td width="100%" align="center" colspan="2">'.
'<textarea rows="10" cols="80" name="Information">';
	ob_end_flush();
	echo $class->Information;
	ob_start("ob_linearize");

echo '</textarea></td></tr><tr><td align="center" width="50%"><input type="submit" name="Save" value="'.i_Save.'">
</td><td align="center" width="50%"><input type="button" name="Cancel" value="'.i_Cancel.'" onclick="Modalbox.hide(); return false;"></td></tr></form>';
}
?>