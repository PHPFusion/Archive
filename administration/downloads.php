<?php
/*---------------------------------------------------+
|	PHP-Fusion 6 Content Management System
+---------------------------------------------------------+
|	Copyright (c) 2005 Nick Jones
|	http://www.php-fusion.co.uk/
+---------------------------------------------------------+
|	Released under the terms & conditions of v2 of the
|	GNU General Public License. For details refer to
|	the included gpl.txt file or visit http://gnu.org
+---------------------------------------------------------*/
require_once "../maincore.php";
require_once BASEDIR."subheader.php";
require_once ADMIN."navigation.php";
include LOCALE.LOCALESET."admin/downloads.php";

if (!checkrights("D")) fallback("../index.php");
if (isset($download_id) && !isNum($download_id)) fallback(FUSION_SELF);
if (!isset($step)) $step = "";

$result = dbquery("SELECT * FROM ".$db_prefix."download_cats");
if (dbrows($result) != 0) {
	if ($step == "delete") {
		$result = dbquery("DELETE FROM ".$db_prefix."downloads WHERE download_id='$download_id'");
		redirect("downloads.php?download_cat_id=$download_cat_id");
	}
	if (isset($_POST['save_download'])) {
		$download_title = stripinput($_POST['download_title']);
		$download_description = stripinput($_POST['download_description']);
		$download_url = stripinput($_POST['download_url']);
		$download_license = stripinput($_POST['download_license']);
		$download_os = stripinput($_POST['download_os']);
		$download_version = stripinput($_POST['download_version']);
		$download_filesize = stripinput($_POST['download_filesize']);
		if ($step == "edit") {
			$result = dbquery("UPDATE ".$db_prefix."downloads SET download_title='$download_title', download_description='$download_description', download_url='$download_url', download_cat='$download_cat', download_license='$download_license', download_os='$download_os', download_version='$download_version', download_filesize='$download_filesize', download_datestamp='".time()."' WHERE download_id='$download_id'");
			redirect("downloads.php?download_cat_id=$download_cat");
		} else {
			$result = dbquery("INSERT INTO ".$db_prefix."downloads VALUES('', '$download_title', '$download_description', '$download_url', '$download_cat', '$download_license', '$download_os', '$download_version', '$download_filesize', '".time()."', '0')");
			redirect("downloads.php?download_cat_id=$download_cat");
		}
	}
	if ($step == "edit") {
		$result = dbquery("SELECT * FROM ".$db_prefix."downloads WHERE download_id='$download_id'");
		$data = dbarray($result);
		$download_title = $data['download_title'];
		$download_description = $data['download_description'];
		$download_url = $data['download_url'];
		$download_license = $data['download_license'];
		$download_os = $data['download_os'];
		$download_version = $data['download_version'];
		$download_filesize = $data['download_filesize'];
		$formaction = FUSION_SELF."?step=edit&download_cat_id=$download_cat_id&download_id=$download_id";
		opentable($locale['470']);
	} else {
		$download_title = ""; $download_description = ""; $download_url = ""; $download_license = "";
		$download_os = ""; $download_version = ""; $download_filesize = "";
		$formaction = FUSION_SELF;
		opentable($locale['471']);
	}
	$editlist = ""; $sel = "";
	$result2 = dbquery("SELECT * FROM ".$db_prefix."download_cats ORDER BY download_cat_name");
	if (dbrows($result2) != 0) {
		while ($data2 = dbarray($result2)) {
			if ($step == "edit") $sel = ($data['download_cat'] == $data2['download_cat_id'] ? " selected" : "");
			$editlist .= "<option value='".$data2['download_cat_id']."'$sel>".$data2['download_cat_name']."</option>\n";
		}
	}
	echo "<form name='inputform' method='post' action='$formaction'>
<table align='center' width='460' cellspacing='0' cellpadding='0'>
<tr>
<td width='80' class='tbl'>".$locale['480']."</td>
<td class='tbl'><input type='text' name='download_title' value='$download_title' class='textbox' style='width:380px;'></td>
</tr>
<tr>
<td width='80' class='tbl'>".$locale['481']."</td>
<td class='tbl'><textarea name='download_description' rows='5' class='textbox' style='width:380px;'>".$download_description."</textarea></td>
</tr>
<tr>
<td class='tbl'></td><td class='tbl'>
<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onClick=\"addText('download_description', '[b]', '[/b]');\">
<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onClick=\"addText('download_description', '[i]', '[/i]');\">
<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onClick=\"addText('download_description', '[u]', '[/u]');\">
<input type='button' value='url' class='button' style='width:30px;' onClick=\"addText('download_description', '[url]', '[/url]');\">
<input type='button' value='mail' class='button' style='width:35px;' onClick=\"addText('download_description', '[mail]', '[/mail]');\">
<input type='button' value='img' class='button' style='width:30px;' onClick=\"addText('download_description', '[img]', '[/img]');\">
<input type='button' value='center' class='button' style='width:45px;' onClick=\"addText('download_description', '[center]', '[/center]');\">
<input type='button' value='small' class='button' style='width:40px;' onClick=\"addText('download_description', '[small]', '[/small]');\">
<input type='button' value='code' class='button' style='width:40px;' onClick=\"addText('download_description', '[code]', '[/code]');\">
<input type='button' value='quote' class='button' style='width:45px;' onClick=\"addText('download_description', '[quote]', '[/quote]');\">
</td>
</tr>
<tr>
<td width='80' class='tbl'>".$locale['482']."</td>
<td class='tbl'><input type='text' name='download_url' value='$download_url' class='textbox' style='width:380px;'></td>
</tr>
<tr>
<td width='80' class='tbl'>".$locale['483']."</td>
<td class='tbl'><select name='download_cat' class='textbox'>
$editlist</select></td>
</tr>
<tr>
<td width='80' class='tbl'>".$locale['484']."</td>
<td class='tbl'><input type='text' name='download_license' value='$download_license' class='textbox' style='width:150px;'></td>
</tr>
<tr>
<td width='80' class='tbl'>".$locale['485']."</td>
<td class='tbl'><input type='text' name='download_os' value='$download_os' class='textbox' style='width:150px;'></td>
</tr>
<tr>
<td width='80' class='tbl'>".$locale['486']."</td>
<td class='tbl'><input type='text' name='download_version' value='$download_version' class='textbox' style='width:150px;'></td>
</tr>
<tr>
<td width='80' class='tbl'>".$locale['487']."</td>
<td class='tbl'><input type='text' name='download_filesize' value='$download_filesize' class='textbox' style='width:150px;'></td>
</tr>
<tr>
<td align='center' colspan='2' class='tbl'>
<input type='submit' name='save_download' value='".$locale['488']."' class='button'></td>
</tr>
</table>
</form>\n";
	closetable();
	tablebreak();
	opentable($locale['500']);
	echo "<table align='center' width='400' cellspacing='0' cellpadding='0' class='tbl'>\n";
	$result = dbquery("SELECT * FROM ".$db_prefix."download_cats ORDER BY download_cat_name");
	if (dbrows($result) != 0) {
		echo "<tr>
<td class='tbl2'>".$locale['501']."</td>
<td align='right' class='tbl2'>".$locale['502']."</td>
</tr>
<tr>
<td colspan='2' height='1'></td>
</tr>\n";
		while ($data = dbarray($result)) {
			if (!isset($download_cat_id)) $download_cat_id = "";
			if ($data['download_cat_id'] == $download_cat_id) { $p_img = "off"; $div = ""; } else { $p_img = "on"; $div = "style='display:none'"; }
			echo "<tr>
<td class='tbl2'>".$data['download_cat_name']."</td>
<td class='tbl2' align='right'><img onclick=\"javascript:flipBox('".$data['download_cat_id']."')\" name='b_".$data['download_cat_id']."' border='0' src='".THEME."images/panel_".$p_img.".gif'></td>
</tr>\n";
			$result2 = dbquery("SELECT * FROM ".$db_prefix."downloads WHERE download_cat='".$data['download_cat_id']."' ORDER BY download_title");
			if (dbrows($result2) != 0) {
				echo "<tr>
<td colspan='2'>
<div id='box_".$data['download_cat_id']."'".$div.">
<table width='100%' cellspacing='0' cellpadding='0' class='tbl'>\n";
				while ($data2 = dbarray($result2)) {
					echo "<tr>
<td><a href='".$data2['download_url']."' target='_blank'>".$data2['download_title']."</a></td>
<td align='right' width='100'><a href='".FUSION_SELF."?step=edit&download_cat_id=".$data['download_cat_id']."&download_id=".$data2['download_id']."'>".$locale['503']."</a> -
<a href='".FUSION_SELF."?step=delete&download_cat_id=".$data['download_cat_id']."&download_id=".$data2['download_id']."' onClick='return DeleteItem()'>".$locale['504']."</a></td>
</tr>\n";
				}
				echo "</table>
</div>
</td>
</tr>\n";
			} else {
				echo "<tr>
<td colspan='2'>
<div id='box_".$data['download_cat_id']."' style='display:none'>
<table width='100%' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td>".$locale['505']."</td>
</tr>
</table>
</div>
</td>
</tr>\n";
			}
		}
		echo "</table>\n";
		echo "<script>
function DeleteItem()
{
return confirm('".$locale['460']."');
}
</script>\n";
	} else {
		echo "<tr>
<td align='center'><br>
".$locale['506']."<br><br>
<a href='download_cats.php'>".$locale['507']."<br><br></td>
</tr>
</table>\n";
	}
	closetable();
} else {
	opentable($locale['500']);
	echo "<center>".$locale['508']."<br>
".$locale['509']."<br><br>
<a href='download_cats.php'>".$locale['510']."</a>".$locale['511']."</center>\n";
	closetable();
}

echo "</td>\n";
require_once BASEDIR."footer.php";
?>