<?
/*
---------------------------------------------------
	PHP-Fusion - Content Management System
	-------------------------------------------
	© Nick Jones (Digitanium) 2002-2004
	http://www.php-fusion.co.uk
	nick@php-fusion.co.uk
	-------------------------------------------
	Released under the terms and conditions of
	the GNU General Public License (Version 2)
---------------------------------------------------
*/
@require "../fusion_config.php";
require "../header.php";
require fusion_basedir."subheader.php";
require fusion_langdir."admin/admin_main.php";
require fusion_langdir."admin/admin_faq.php";
require "navigation.php";

if (!Admin()) header("Location: ../index.php");

if (Admin()) {
	if ($action == "delete" && $t == "cat") {
		opentable(LAN_400);
		$result = dbquery("SELECT * FROM ".$fusion_prefix."faqs WHERE faq_cat_id='$faq_cat_id'");
		if (dbrows($result) == 0) {
			$result = dbquery("DELETE FROM ".$fusion_prefix."faq_cats WHERE faq_cat_id='$faq_cat_id'");
			echo "<center><br>
".LAN_401."<br><br>
<a href='$PHP_SELF'>".LAN_402."</a><br><br>
<a href='index.php'>".LAN_403."</a><br><br>
</center>\n";
		} else {
			echo "<center><br>
".LAN_404."<br>
<span class='small'>".LAN_405."</span><br><br>
<a href='$PHP_SELF'>".LAN_402."</a><br><br>
<a href='index.php'>".LAN_403."</a><br><br>
</center>\n";
		}
		closetable();
	} else {
		if ($action == "delete" && $t == "faq") {
			$faq_count = dbrows(dbquery("SELECT count(faq_id) FROM ".$fusion_prefix."faqs WHERE faq_id='$faq_id'"));
			$result = dbquery("DELETE FROM ".$fusion_prefix."faqs WHERE faq_id='$faq_id'");
			if ($faq_count != 0) {
				header("Location: ".$PHP_SELF."?faq_cat_id=$faq_cat_id");
			} else {
				header("Location: ".$PHP_SELF);
			}
		}
		if (isset($_POST[save_cat])) {
			$faq_cat_name = stripinput($faq_cat_name);
			if ($action == "edit" && $t == "cat") {
				$result = dbquery("UPDATE ".$fusion_prefix."faq_cats SET faq_cat_name='$faq_cat_name' WHERE faq_cat_id='$faq_cat_id'");
			} else {
				if ($faq_cat_name != "") {
					$result = dbquery("INSERT INTO ".$fusion_prefix."faq_cats VALUES('', '$faq_cat_name')");
				}
			}
			header("Location: ".$PHP_SELF);
		}
		if (isset($_POST[save_faq])) {
			$faq_question = stripinput($faq_question);
			$faq_answer = addslashes($faq_answer);
			if ($action == "edit" && $t == "faq") {
				$result = dbquery("UPDATE ".$fusion_prefix."faqs SET faq_question='$faq_question', faq_answer='$faq_answer' WHERE faq_id='$faq_id'");
			} else {
				if ($faq_question != "" && $faq_answer != "") {
					$result = dbquery("INSERT INTO ".$fusion_prefix."faqs VALUES('', '$faq_cat', '$faq_question', '$faq_answer')");
				}
			}
			header("Location: ".$PHP_SELF."?faq_cat_id=$faq_cat");
		}
		if ($action == "edit") {
			if ($t == "cat") {
				$faq_cat_data = dbarray(dbquery("SELECT * FROM ".$fusion_prefix."faq_cats WHERE faq_cat_id='$faq_cat_id'"));
				$faq_cat_action = "$PHP_SELF?action=edit&faq_cat_id=".$faq_cat_data['faq_cat_id']."&t=cat";
				$faq_cat_title = LAN_421;
				$faq_title = LAN_422;
				$faq_action = "$PHP_SELF";
			} else if ($t == "faq") {
				$faq_data = dbarray(dbquery("SELECT * FROM ".$fusion_prefix."faqs WHERE faq_id='$faq_id'"));
				$faq_cat_title = LAN_420;
				$faq_title = LAN_423;
				$faq_action = "$PHP_SELF?action=edit&faq_id=".$faq_data['faq_id']."&t=faq";
				$faq_cat_action = "$PHP_SELF";
			}
		} else {
			$faq_cat_title = LAN_420;
			$faq_cat_action = "$PHP_SELF";
			$faq_title = LAN_422;
			$faq_action = "$PHP_SELF";
		}
		if ($t != "faq") {
			opentable($faq_cat_title);
			echo "<form name='add_faq_cat' method='post' action='$faq_cat_action'>
<table align='center'>
<tr>
<td>".LAN_440.":&nbsp;</td>
<td><input type='text' name='faq_cat_name' value='".$faq_cat_data['faq_cat_name']."' class='textbox' style='width:210px'></td>
</tr>
<td align='center' colspan='2'><input type='submit' name='save_cat' value='".LAN_441."' class='button'></td>
</tr>
</table>
</form>\n";	
			closetable();
		}
		if ($t != "cat") {
			$result2 = dbquery("SELECT * FROM ".$fusion_prefix."faq_cats ORDER BY faq_cat_name");
			if (dbrows($result2) != 0) {
				if (!$t) tablebreak();
				while ($data2 = dbarray($result2)) {
					if ($action == "edit" && $t == "faq") {
						if ($data2['faq_cat_id'] == $faq_data['faq_cat_id']) { $sel = " selected"; } else { $sel = ""; }
					}
					$cat_opts .= "<option value='".$data2['faq_cat_id']."'$sel>".$data2['faq_cat_name']."</option>\n";
				}
				opentable($faq_title);
				echo "<form name='add_faq' method='post' action='$faq_action'>
<table align='center' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td>".LAN_460.":&nbsp;</td>
<td><select name='faq_cat' class='textbox' style='width:250px;'>
$cat_opts</select></td>
</tr>
<tr>
<td>".LAN_461.":&nbsp;</td>
<td><input type='text' name='faq_question' value='".$faq_data['faq_question']."' class='textbox' style='width:345px'></td>
</tr>
<tr>
<td valign='top'>".LAN_462.":&nbsp;</td>
<td><textarea name='faq_answer' cols='65' rows='5' class='textbox' onselect='updatePos(this);' onkeyup='updatePos(this);' onclick='updatePos(this);' ondblclick='updatePos(this);'>".stripslashes($faq_data['faq_answer'])."</textarea></td>
</tr>
<tr>
<td></td><td>
<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onClick=\"AddText('<b>', '</b>');\">
<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onClick=\"AddText('<i>', '</i>');\">
<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onClick=\"AddText('<u>', '</u>');\">
<input type='button' value='link' class='button' style='width:35px;' onClick=\"AddText('<a href=\'\' target=\'_blank\'>', '</a>');\">
<input type='button' value='img' class='button' style='width:35px;' onClick=\"insertText('<img src=\'fusion_images/\' style=\'margin:5px;\' align=\'left\'>');\">
<input type='button' value='center' class='button' style='width:45px;' onClick=\"AddText('<center>', '</center>');\">
<input type='button' value='small' class='button' style='width:40px;' onClick=\"AddText('<span class=\'small\'>', '</span>');\">
<input type='button' value='small2' class='button' style='width:45px;' onClick=\"AddText('<span class=\'small2\'>', '</span>');\">
<input type='button' value='alt' class='button' style='width:25px;' onClick=\"AddText('<span class=\'alt\'>', '</span>');\"><br>
</td>
</tr>
<tr>
<td align='center' colspan='2'><input type='submit' name='save_faq' value='".LAN_463."' class='button'></td>
</tr>
</table>
</form>\n";	
				closetable();
			echo "<script language=\"JavaScript\">
var editBody = document.add_faq.faq_answer;
function insertText(theText) {
	if (editBody.createTextRange && editBody.curPos) {
		editBody.curPos.text = theText;
	} else {
		editBody.value += theText;
	}
	editBody.focus();
}
function AddText(wrap,unwrap) {
	if (editBody.curPos) {
		insertText(wrap + editBody.curPos.text + unwrap);
	} else {
		insertText(wrap + unwrap);
	}
}
function updatePos(obj) {
	if (obj.createTextRange) {
		obj.curPos = document.selection.createRange().duplicate();
	}
}
</script>\n";
			}
		}
	}
	tablebreak();
	opentable(LAN_480);
	$result = dbquery("SELECT * FROM ".$fusion_prefix."faq_cats ORDER BY faq_cat_name");
	if (dbrows($result) != 0) {
		echo "<table align='center' width='400' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td class='altbg'>".LAN_481."</td>
<td align='right' class='altbg'>".LAN_482."</td>
</tr>
<tr>
<td colspan='2' height='1'></td>
</tr>\n";
		while ($data = dbarray($result)) {
			if ($data['faq_cat_id'] == $faq_cat_id) { $p_img = "off"; $div = ""; } else { $p_img = "on"; $div = "style='display:none'"; }
			echo "<tr>
<td class='altbg'><img onclick=\"javascript:flipBox('".$data['faq_cat_id']."')\" name='b_".$data['faq_cat_id']."' border='0' src='".fusion_themedir."images/panel_".$p_img.".gif'> ".$data['faq_cat_name']."</td>
<td class='altbg' align='right' style='font-weight:normal;'><a href='$PHP_SELF?action=edit&faq_cat_id=".$data['faq_cat_id']."&t=cat'>".LAN_483."</a> -
<a href='$PHP_SELF?action=delete&faq_cat_id=".$data['faq_cat_id']."&t=cat' onClick='return DeleteItem()'>".LAN_484."</a></td>
</tr>\n";
			$result2 = dbquery("SELECT * FROM ".$fusion_prefix."faqs WHERE faq_cat_id='".$data['faq_cat_id']."' ORDER BY faq_id");
			if (dbrows($result2) != 0) {
				echo "<tr>
<td colspan='2'>
<div id='box_".$data['faq_cat_id']."'$div>
<table width='100%' cellspacing='0' cellpadding='0' class='tbl'>\n";
				while ($data2 = dbarray($result2)) {
					echo "<tr>
<td>".$data2['faq_question']."</td>
<td align='right'><a href='$PHP_SELF?action=edit&faq_cat_id=".$data['faq_cat_id']."&faq_id=".$data2['faq_id']."&t=faq'>".LAN_483."</a> -
<a href='$PHP_SELF?action=delete&faq_cat_id=".$data['faq_cat_id']."&faq_id=".$data2['faq_id']."&t=faq' onClick='return DeleteItem()'>".LAN_484."</a></td>
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
<td>".LAN_485."</td>
</tr>
</table>
</div>
</td>
</tr>\n";
			}
		}
		echo "</table>\n";
	} else {
		echo "<center>".LAN_486."<br>
</center>\n";
	}
	closetable();
}

echo "</td>\n";
require "../footer.php";
?>