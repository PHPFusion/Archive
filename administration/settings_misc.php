<?php
/*---------------------------------------------------+
| PHP-Fusion 6 Content Management System
+----------------------------------------------------+
| Copyright (c) 2005 Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the
| GNU General Public License. For details refer to
| the included gpl.txt file or visit http://gnu.org
+----------------------------------------------------*/
require_once "../maincore.php";
require_once BASEDIR."subheader.php";
require_once ADMIN."navigation.php";
include LOCALE.LOCALESET."admin/settings.php";

if (!checkrights("S6")) fallback("../index.php");

if (isset($_POST['savesettings'])) {
	$result = dbquery("UPDATE ".$db_prefix."settings SET
		bad_words_enabled='".(isNum($_POST['bad_words_enabled']) ? $_POST['bad_words_enabled'] : "0")."',
		bad_words='".$_POST['bad_words']."',
		bad_word_replace='".$_POST['bad_word_replace']."',
		guestposts='".(isNum($_POST['guestposts']) ? $_POST['guestposts'] : "0")."',
		numofshouts='".(isNum($_POST['numofshouts']) ? $_POST['numofshouts'] : "10")."',
		maintenance='".(isNum($_POST['maintenance']) ? $_POST['maintenance'] : "0")."',
		maintenance_message='".$_POST['maintenance_message']."'
	");
	redirect(FUSION_SELF);
}

$settings2 = dbarray(dbquery("SELECT * FROM ".$db_prefix."settings"));

opentable($locale['650']);

	echo "<table width='500' align='center' cellpadding='0' cellspacing='1' class='tbl-border'>\n<tr>
<td width='25%' align='center' class='tbl2'><span class='small'><a href='settings_photo.php' title='".$locale['600']."'>".$locale['751']."</a></span></td>
<td width='50%' align='center' class='tbl1'><span class='small'><b>".$locale['650']."</b></span></td>
<td width='25%' align='center' class='tbl2'><span class='small'><a href='settings_messages.php' title='".$locale['700']."'>".$locale['752']."</a></span></td>
</tr>\n</table><br>\n";

	echo "<form name='settingsform' method='post' action='".FUSION_SELF."'>
<table width='500' align='center' cellspacing='0' cellpadding='0'>
<tr>
<td width='50%' class='tbl'>".$locale['659']."</td>
<td width='50%' class='tbl'><select name='bad_words_enabled' class='textbox'>
<option value='1'".($settings2['bad_words_enabled'] == "1" ? " selected" : "").">".$locale['508']."</option>
<option value='0'".($settings2['bad_words_enabled'] == "0" ? " selected" : "").">".$locale['509']."</option>
</select></td>
</tr>
<tr><td valign='top' width='50%' class='tbl'>".$locale['651']."<br>
<span class='small2'>".$locale['652']."<br>
".$locale['653']."</small></td>
<td width='50%' class='tbl'><textarea name='bad_words' rows='5' class='textbox' style='width:250px;'>".$settings2['bad_words']."</textarea></td>
</tr>
<tr>
<td width='50%' class='tbl'>".$locale['654']."</td>
<td width='50%' class='tbl'><input type='text' name='bad_word_replace' value='".$settings2['bad_word_replace']."' maxlength='128' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td width='50%' class='tbl'>".$locale['655']."</td>
<td width='50%' class='tbl'><select name='guestposts' class='textbox'>
<option value='1'".($settings2['guestposts'] == "1" ? " selected" : "").">".$locale['508']."</option>
<option value='0'".($settings2['guestposts'] == "0" ? " selected" : "").">".$locale['509']."</option>
</select></td>
</tr>
<tr>
<td width='50%' class='tbl'>".$locale['656']."</td>
<td width='50%' class='tbl'>
<select name='numofshouts' class='textbox'>
<option".($settings2['numofshouts'] == 5 ? " selected" : "").">5</option>
<option".($settings2['numofshouts'] == 10 ? " selected" : "").">10</option>
<option".($settings2['numofshouts'] == 15 ? " selected" : "").">15</option>
<option".($settings2['numofshouts'] == 20 ? " selected" : "").">20</option>
</select>
</td>
</tr>
<tr>
<td width='50%' class='tbl'>".$locale['657']."</td>
<td width='50%' class='tbl'><select name='maintenance' class='textbox' style='width:50px;'>
<option value='1'".($settings2['maintenance'] == "1" ? " selected" : "").">".$locale['502']."</option>
<option value='0'".($settings2['maintenance'] == "0" ? " selected" : "").">".$locale['503']."</option>
</select></td>
</tr>
<tr><td valign='top' width='50%' class='tbl'>".$locale['658']."</td>
<td width='50%' class='tbl'><textarea name='maintenance_message' rows='5' class='textbox' style='width:250px;'>".stripslashes($settings2['maintenance_message'])."</textarea></td>
</tr>
<tr>
<td align='center' colspan='2' class='tbl'><br>
<input type='submit' name='savesettings' value='".$locale['750']."' class='button'></td>
</tr>
</table>
</form>\n";
closetable();

echo "</td>\n";
require_once BASEDIR."footer.php";
?>