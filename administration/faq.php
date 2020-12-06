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
include LOCALE.LOCALESET."admin/faq.php";

if (!checkrights("FQ")) fallback("../index.php");
if (isset($faq_cat_id) && !isNum($faq_cat_id)) fallback(FUSION_SELF);
if (isset($faq_id) && !isNum($faq_id)) fallback(FUSION_SELF);
if (!isset($action)) $action = "";
if (!isset($t)) $t = "";

if ($action == "delete" && $t == "cat") {
	opentable($locale['400']);
	$result = dbquery("SELECT * FROM ".$db_prefix."faqs WHERE faq_cat_id='$faq_cat_id'");
	if (dbrows($result) == 0) {
		$result = dbquery("DELETE FROM ".$db_prefix."faq_cats WHERE faq_cat_id='$faq_cat_id'");
		echo "<center><br>
".$locale['401']."<br><br>
<a href='".FUSION_SELF."'>".$locale['402']."</a><br><br>
<a href='index.php'>".$locale['403']."</a><br><br>
</center>\n";
	} else {
		echo "<center><br>
".$locale['404']."<br>
<span class='small'>".$locale['405']."</span><br><br>
<a href='".FUSION_SELF."'>".$locale['402']."</a><br><br>
<a href='index.php'>".$locale['403']."</a><br><br>
</center>\n";
	}
	closetable();
} else {
	if ($action == "delete" && $t == "faq") {
		$faq_count = dbcount("(faq_id)", "faqs", "faq_id='$faq_id'");
		$result = dbquery("DELETE FROM ".$db_prefix."faqs WHERE faq_id='$faq_id'");
		if ($faq_count != 0) {
			header("Location: ".FUSION_SELF."?faq_cat_id=$faq_cat_id");
		} else {
			header("Location: ".FUSION_SELF);
		}
	}
	if (isset($_POST['save_cat'])) {
		$faq_cat_name = stripinput($_POST['faq_cat_name']);
		if ($action == "edit" && $t == "cat") {
			$result = dbquery("UPDATE ".$db_prefix."faq_cats SET faq_cat_name='$faq_cat_name' WHERE faq_cat_id='$faq_cat_id'");
		} else {
			if ($faq_cat_name != "") {
				$result = dbquery("INSERT INTO ".$db_prefix."faq_cats VALUES('', '$faq_cat_name')");
			}
		}
		header("Location: ".FUSION_SELF);
	}
	if (isset($_POST['save_faq'])) {
		$faq_question = stripinput($_POST['faq_question']);
		$faq_answer = addslash($_POST['faq_answer']);
		if ($action == "edit" && $t == "faq") {
			$result = dbquery("UPDATE ".$db_prefix."faqs SET faq_cat_id='$faq_cat', faq_question='$faq_question', faq_answer='$faq_answer' WHERE faq_id='$faq_id'");
		} else {
			if ($faq_question != "" && $faq_answer != "") {
				$result = dbquery("INSERT INTO ".$db_prefix."faqs VALUES('', '$faq_cat', '$faq_question', '$faq_answer')");
			}
		}
		header("Location: ".FUSION_SELF."?faq_cat_id=$faq_cat");
	}
	if ($action == "edit") {
		if ($t == "cat") {
			$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."faq_cats WHERE faq_cat_id='$faq_cat_id'"));
			$faq_cat_id = $data['faq_cat_id'];
			$faq_cat_name = $data['faq_cat_name'];
			$faq_cat_title = $locale['421'];
			$faq_cat_action = FUSION_SELF."?action=edit&faq_cat_id=$faq_cat_id&t=cat";
			// --------------------- //
			$faq_question = "";
			$faq_answer = "";
			$faq_title = $locale['422'];
			$faq_action = FUSION_SELF;
		} else if ($t == "faq") {
			$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."faqs WHERE faq_id='$faq_id'"));
			$faq_cat_name = "";
			$faq_cat_title = $locale['420'];
			$faq_cat_action = FUSION_SELF;
			// --------------------- //
			$faq_id = $data['faq_id'];
			$faq_question = $data['faq_question'];
			$faq_answer = $data['faq_answer'];
			$faq_title = $locale['423'];
			$faq_action = FUSION_SELF."?action=edit&faq_id=$faq_id&t=faq";
		}
	} else {
		$faq_cat_name = "";
		$faq_cat_title = $locale['420'];
		$faq_cat_action = FUSION_SELF;
		$faq_question = "";
		$faq_answer = "";
		$faq_title = $locale['422'];
		$faq_action = FUSION_SELF;
	}
	if (!isset($t) || $t != "faq") {
		opentable($faq_cat_title);
		echo "<form name='add_faq_cat' method='post' action='$faq_cat_action'>
<table align='center' cellpadding='0' cellspacing='0'>
<tr>
<td class='tbl'>".$locale['440'].":&nbsp;</td>
<td class='tbl'><input type='text' name='faq_cat_name' value='$faq_cat_name' class='textbox' style='width:210px'></td>
</tr>
<td align='center' colspan='2' class='tbl'><input type='submit' name='save_cat' value='".$locale['441']."' class='button'></td>
</tr>
</table>
</form>\n";	
		closetable();
	}
	if (!isset($t) || $t != "cat") {
		$cat_opts = ""; $sel = "";
		$result2 = dbquery("SELECT * FROM ".$db_prefix."faq_cats ORDER BY faq_cat_name");
		if (dbrows($result2) != 0) {
			if (!$t) tablebreak();
			while ($data2 = dbarray($result2)) {
				if ($action == "edit" && $t == "faq")  $sel = ($data2['faq_cat_id'] == $faq_cat_id ? " selected" : "");
				$cat_opts .= "<option value='".$data2['faq_cat_id']."'$sel>".$data2['faq_cat_name']."</option>\n";
			}
			opentable($faq_title);
			echo "<form name='inputform' method='post' action='$faq_action'>
<table align='center' cellspacing='0' cellpadding='0'>
<tr>
<td class='tbl'>".$locale['460'].":&nbsp;</td>
<td class='tbl'><select name='faq_cat' class='textbox' style='width:250px;'>
$cat_opts</select></td>
</tr>
<tr>
<td class='tbl'>".$locale['461'].":&nbsp;</td>
<td class='tbl'><input type='text' name='faq_question' value='$faq_question' class='textbox' style='width:335px'></td>
</tr>
<tr>
<td valign='top' class='tbl'>".$locale['462'].":&nbsp;</td>
<td class='tbl'><textarea name='faq_answer' rows='5' class='textbox' style='width:335px;'>".phpentities(stripslashes($faq_answer))."</textarea></td>
</tr>
<tr>
<td class='tbl'></td><td class='tbl'>
<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onClick=\"addText('faq_answer', '<b>', '</b>');\">
<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onClick=\"addText('faq_answer', '<i>', '</i>');\">
<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onClick=\"addText('faq_answer', '<u>', '</u>');\">
<input type='button' value='link' class='button' style='width:35px;' onClick=\"addText('faq_answer', '<a href=\'\' target=\'_blank\'>', '</a>');\">
<input type='button' value='img' class='button' style='width:35px;' onClick=\"insertText('faq_answer', '<img src=\'images/\' style=\'margin:5px;\' align=\'left\'>');\">
<input type='button' value='center' class='button' style='width:45px;' onClick=\"addText('faq_answer', '<center>', '</center>');\">
<input type='button' value='small' class='button' style='width:40px;' onClick=\"addText('faq_answer', '<span class=\'small\'>', '</span>');\">
<input type='button' value='small2' class='button' style='width:45px;' onClick=\"addText('faq_answer', '<span class=\'small2\'>', '</span>');\">
<input type='button' value='alt' class='button' style='width:25px;' onClick=\"addText('faq_answer', '<span class=\'alt\'>', '</span>');\">
</td>
</tr>
<tr>
<td align='center' colspan='2' class='tbl'><br>
<input type='submit' name='save_faq' value='".$locale['463']."' class='button'></td>
</tr>
</table>
</form>\n";	
			closetable();
		}
	}
}
tablebreak();
opentable($locale['480']);
$result = dbquery("SELECT * FROM ".$db_prefix."faq_cats ORDER BY faq_cat_name");
if (dbrows($result) != 0) {
	echo "<table align='center' width='400' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td class='tbl2'>".$locale['481']."</td>
<td align='right' class='tbl2'>".$locale['482']."</td>
</tr>
<tr>
<td colspan='2' height='1'></td>
</tr>\n";
	while ($data = dbarray($result)) {
		if (!isset($faq_cat_id)) $faq_cat_id = "";
		if ($data['faq_cat_id'] == $faq_cat_id) { $p_img = "off"; $div = ""; } else { $p_img = "on"; $div = "style='display:none'"; }
		echo "<tr>
<td class='tbl2'><img onclick=\"javascript:flipBox('".$data['faq_cat_id']."')\" name='b_".$data['faq_cat_id']."' border='0' src='".THEME."images/panel_".$p_img.".gif'> ".$data['faq_cat_name']."</td>
<td class='tbl2' align='right' style='font-weight:normal;'><a href='".FUSION_SELF."?action=edit&faq_cat_id=".$data['faq_cat_id']."&t=cat'>".$locale['483']."</a> -
<a href='".FUSION_SELF."?action=delete&faq_cat_id=".$data['faq_cat_id']."&t=cat' onClick='return DeleteItem()'>".$locale['484']."</a></td>
</tr>\n";
		$result2 = dbquery("SELECT * FROM ".$db_prefix."faqs WHERE faq_cat_id='".$data['faq_cat_id']."' ORDER BY faq_id");
		if (dbrows($result2) != 0) {
			echo "<tr>
<td colspan='2'>
<div id='box_".$data['faq_cat_id']."'$div>
<table width='100%' cellspacing='0' cellpadding='0' class='tbl'>\n";
			while ($data2 = dbarray($result2)) {
				echo "<tr>
<td>".$data2['faq_question']."</td>
<td align='right'><a href='".FUSION_SELF."?action=edit&faq_cat_id=".$data['faq_cat_id']."&faq_id=".$data2['faq_id']."&t=faq'>".$locale['483']."</a> -
<a href='".FUSION_SELF."?action=delete&faq_cat_id=".$data['faq_cat_id']."&faq_id=".$data2['faq_id']."&t=faq' onClick='return DeleteItem()'>".$locale['484']."</a></td>
</tr>
<tr>
<td colspan='2' class='small2'>".trimlink(stripinput($data2['faq_answer']), 60)."</td>
</tr>\n";
			}
			echo "</table>
</div>
</td>
</tr>\n";
		} else {
			echo "<tr>
<td colspan='2'>
<div id='box_".$data['faq_cat_id']."' style='display:none'>
<table width='100%' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td>".$locale['485']."</td>
</tr>
</table>
</div>
</td>
</tr>\n";
		}
	}
	echo "</table>\n";
} else {
	echo "<center>".$locale['486']."<br>\n</center>\n";
}
closetable();

echo "</td>\n";
require_once BASEDIR."footer.php";
?>