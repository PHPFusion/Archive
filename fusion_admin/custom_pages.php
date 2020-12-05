<?
/*
-------------------------------------------------------
	PHP-Fusion
	-----------------------------------------------
	© Nick Jones 2002-2004
	http://www.digitaldominion.co.uk
	nick@digitaldominion.co.uk
	-----------------------------------------------
	Released under the terms and conditions of the
	GNU General Public License (http://gnu.org).
-------------------------------------------------------
*/
@require "../fusion_config.php";
require "../header.php";
require fusion_basedir."subheader.php";
require fusion_langdir."admin/admin_custom_pages.php";

require "navigation.php";
require "userinfo.php";
echo "</td>
<td valign=\"top\" class=\"bodybg\">\n";

if ($userdata[user_mod] > "2") {
	if (isset($_POST['save'])) {
		$title = stripinput($title);
		$body = stripslashes($body);
		if (isset($page_id)) {
			$result = dbquery("UPDATE ".$fusion_prefix."custom_pages SET page_title='$title' WHERE page_id='$page_id'");
			$file = fopen(fusion_basedir."fusion_pages/".$page_id, "wb");
			$write = fwrite($file, $body);
			fclose($file);
			opentable(LAN_200);
			echo "<center><br>
".LAN_201."<br><br>
".LAN_202."<br>
<a href=\"../fusion_pages/index.php?page_id=$page_id\">fusion_pages/index.php?page_id=$page_id</a><br><br>
<a href=\"custom_pages.php\">".LAN_203."</a><br><br>
<a href=\"index.php\">".LAN_204."</a><br><br>
</center>\n";
			closetable();
		} else {
			$result = dbquery("INSERT INTO ".$fusion_prefix."custom_pages VALUES('', '$title')");
			$file_id = mysql_insert_id();
			$file = fopen(fusion_basedir."fusion_pages/$file_id", "wb");
			$write = fwrite($file, $body);
			fclose($file);
			$result = dbquery("SELECT * FROM ".$fusion_prefix."site_links ORDER BY link_order DESC LIMIT 1");
			$data = dbarray($result);
			$link_order = $data[link_order] + 1;
			$result = dbquery("INSERT INTO ".$fusion_prefix."site_links VALUES('', '$title', 'fusion_pages/index.php?page_id=$file_id', '$link_order')");
			opentable(LAN_204);
			echo "<center><br>
".LAN_206."<br><br>
".LAN_202."<br>
<a href=\"../fusion_pages/index.php?page_id=$file_id\">fusion_pages/index.php?page_id=$file_id</a><br><br>
<a href=\"custom_pages.php\">".LAN_203."</a><br><br>
<a href=\"index.php\">".LAN_204."</a><br><br>
</center>\n";
			closetable();
		}
	} else if (isset($_POST['delete'])) {
		$result = dbquery("DELETE FROM ".$fusion_prefix."custom_pages WHERE page_id='$page_id'");
		$result = dbquery("DELETE FROM ".$fusion_prefix."site_links WHERE link_url='fusion_pages/index.php?page_id=$page_id'");
		unlink(fusion_basedir."fusion_pages/$page_id");
		opentable(LAN_206);
		echo "<center><br>
".LAN_208."<br><br>
<a href=\"custom_pages.php\">".LAN_203."</a><br><br>
<a href=\"index.php\">".LAN_204."</a><br><br>
</center>\n";
		closetable();
	} else {
		if (isset($_POST['preview'])) {
			$title = stripinput($title);
			$body = stripslashes($body);
			$file = fopen(fusion_basedir."fusion_pages/temp","wb");
			$write = fwrite($file, $body);
			fclose($file);
			opentable($title);
			require fusion_basedir."fusion_pages/temp";
			unlink(fusion_basedir."fusion_pages/temp");
			closetable();
			tablebreak();
		}
		$result = dbquery("SELECT * FROM ".$fusion_prefix."custom_pages ORDER BY page_title DESC");
		if (dbrows($result) != 0) {
			while ($data = dbarray($result)) {
				if (isset($page_id)) {
					if ($page_id == $data[page_id]) { $sel = " selected"; } else { $sel = ""; }
				}
				$editlist .= "<option value=\"$data[page_id]\"$sel>".stripslashes($data[page_title])."</option>\n";
			}
		}
		opentable(LAN_220);
		echo "<form name=\"selectform\" method=\"post\" action=\"$PHP_SELF\">
<center>
<select name=\"page_id\" class=\"textbox\" style=\"width:200px;\">
$editlist</select>
<input type=\"submit\" name=\"edit\" value=\"".LAN_221."\" class=\"button\">
<input type=\"submit\" name=\"delete\" value=\"".LAN_222."\" class=\"button\">
</center>
</form>\n";
		closetable();
		tablebreak();
		if (isset($_POST['edit'])) {
			$result = dbquery("SELECT * FROM ".$fusion_prefix."custom_pages WHERE page_id='$page_id'");
			if (dbrows($result) != 0) {
				$data = dbarray($result);
				$title = $data[page_title];
				$file = fopen(fusion_basedir."fusion_pages/".$data[page_id],"rb");
				$body = fread($file, filesize(fusion_basedir."fusion_pages/".$data[page_id]));
				fclose($file);
			}
		}
		if (isset($page_id)) {
			$action = $PHP_SELF."?page_id=$page_id";
			opentable(LAN_200);
		} else {
			$action = $PHP_SELF;
			opentable(LAN_205);
		}
		echo "<table align=\"center\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<form name=\"editform\" method=\"post\" action=\"$action\">
<tr>
<td width=\"100\">".LAN_230."</td><td width=\"80%\"><input type=\"textbox\" name=\"title\" value=\"$title\" class=\"textbox\" style=\"width: 250px;\"></td>
</tr>
<tr>
<td width=\"100\">".LAN_231."</td><td width=\"80%\"><textarea name=\"body\" cols=\"95\" rows=\"15\" class=\"textbox\" onselect=\"updatePos(this);\" onkeyup=\"updatePos(this);\" onclick=\"updatePos(this);\" ondblclick=\"updatePos(this);\">$body</textarea></td>
</tr>
<tr>
<td></td><td>
<input type=\"button\" value=\"b\" class=\"button\" style=\"font-weight:bold;width:25px;\" onClick=\"AddText('<b>', '</b>');\">
<input type=\"button\" value=\"i\" class=\"button\" style=\"font-style:italic;width:25px;\" onClick=\"AddText('<i>', '</i>');\">
<input type=\"button\" value=\"u\" class=\"button\" style=\"text-decoration:underline;width:25px;\" onClick=\"AddText('<u>', '</u>');\">
<input type=\"button\" value=\"link\" class=\"button\" style=\"width:35px;\" onClick=\"AddText('<a href=\u0022\u0022 target=\u0022_blank\u0022>', '</a>');\">
<input type=\"button\" value=\"img\" class=\"button\" style=\"width:35px;\" onClick=\"insertText('<img src=\u0022images/\u0022 align=\u0022left\u0022>');\">
<input type=\"button\" value=\"center\" class=\"button\" style=\"width:45px;\" onClick=\"AddText('<center>', '</center>');\">
<input type=\"button\" value=\"small\" class=\"button\" style=\"width:40px;\" onClick=\"AddText('<span class=\u0022small\u0022>', '</span>');\">
<input type=\"button\" value=\"small2\" class=\"button\" style=\"width:45px;\" onClick=\"AddText('<span class=\u0022small2\u0022>', '</span>');\">
<input type=\"button\" value=\"alt\" class=\"button\" style=\"width:25px;\" onClick=\"AddText('<span class=\u0022alt\u0022>', '</span>');\"><br>
</td>
</tr>
<tr>
<td align=\"center\" colspan=\"2\"><br>
<input type=\"submit\" name=\"preview\" value=\"".LAN_232."\" class=\"button\">
<input type=\"submit\" name=\"save\" value=\"".LAN_233."\" class=\"button\"></td>
</tr>
</form>
</table>\n";
		closetable();
		echo "<script language=\"JavaScript\">
var editBody = document.editform.body;
var editBody2 = document.editform.body2;
function insertText(theText) {
	if (editBody.createTextRange && editBody.curPos) {
		editBody.curPos.text = theText;
	} else {
		editBody.value += theText;
	}
	editBody.focus();
}
function insertText2(theText) {
	if (editBody2.createTextRange && editBody2.curPos) {
		editBody2.curPos.text = theText;
	} else {
		editBody2.value += theText;
	}
	editBody2.focus();
}
function AddText(wrap,unwrap) {
	if (editBody.curPos) {
		insertText(wrap + editBody.curPos.text + unwrap);
	} else {
		insertText(wrap + unwrap);
	}
}
function AddText2(wrap,unwrap) {
	if (editBody2.curPos) {
		insertText2(wrap + editBody2.curPos.text + unwrap);
	} else {
		insertText2(wrap + unwrap);
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

echo "</td>
</tr>
</table>\n";

require "../footer.php";
?>