<?php
if (isset($_REQUEST['noteText']) && var_get('SaveNote')!='') {
	$_SESSION['UserNotes'] = substr($_REQUEST['noteText'],0,100000);
	header('Location: '.$PAGE_LINK);
	exit();
}
echo '<form method="post" action="'.$PAGE_LINK.'&a=302">'.
'<p>'.i_InsertYourNotesHere.':</p><table class="NoBorderTable" width="100%"><tr><td width="100%" align="center" colspan="2">'.
'<textarea rows="10" cols="80" name="noteText">';
if (isset($_SESSION['UserNotes'])) {
	ob_end_flush();
	echo $_SESSION['UserNotes'];
	ob_start("ob_linearize");
}
echo '</textarea></td></tr><tr><td align="center" width="50%"><input type="submit" name="SaveNote" value="'.i_Save.'">
</td><td align="center" width="50%"><input type="button" name="Cancel" value="'.i_Cancel.'" onclick="Modalbox.hide(); return false;"></td></tr></form>';
?>