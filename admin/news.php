<?
/*
-------------------------------------------------------
	PHP Fusion X3
	-----------------------------------------------
	© Nick Jones 2002-2004
	http://www.digitaldominion.co.uk
	nick@digitaldominion.co.uk
	-----------------------------------------------
	Released under the terms and conditions of the
	GNU General Public License (http://gnu.org).
-------------------------------------------------------
*/
require "header.php";
require fusion_basedir."subheader.php";
require fusion_langdir."admin/admin_news-articles.php";

require "navigation.php";
require "userinfo.php";
echo "</td>
<td valign=\"top\" class=\"bodybg\">\n";

if ($userdata[user_mod] > "1") {
	if (isset($_POST['save'])) {
		$subject = addslashes($subject);
		$body = addslashes($body);
		if ($body2 != "") {
			$body2 = addslashes($body2);
		}
		if (isset($news_id)) {
			$result = dbquery("UPDATE ".$fusion_prefix."news SET news_subject='$subject', news_news='$body', news_extended='$body2' WHERE news_id='$news_id'");
			opentable(LAN_200);
			echo "<center><br>
".LAN_201."<br><br>
<a href=\"news.php\">".LAN_202."</a><br><br>
<a href=\"index.php\">".LAN_203."</a><br><br>
</center>\n";
			closetable();
		} else {
			$result = dbquery("INSERT INTO ".$fusion_prefix."news VALUES('', '$subject', '$body', '$body2', '$userdata[user_name]', '$userdata[user_email]', '".time()."', '0', '0')");
			opentable(LAN_204);
			echo "<center><br>
".LAN_205."<br><br>
<a href=\"news.php\">".LAN_202."</a><br><br>
<a href=\"index.php\">".LAN_203."</a><br><br>
</center>\n";
			closetable();
		}
	} else if (isset($_POST['delete'])) {
		$result = dbquery("DELETE FROM ".$fusion_prefix."news WHERE news_id='$news_id'");
		$result = dbquery("DELETE FROM ".$fusion_prefix."comments WHERE news_id='$news_id'");
		opentable(LAN_206);
		echo "<center><br>
".LAN_207."<br><br>
<a href=\"news.php\">".LAN_202."</a><br><br>
<a href=\"index.php\">".LAN_203."</a><br><br>
</center>\n";
		closetable();
	} else {
		if (isset($_POST['preview'])) {
			$subject = stripslashes($subject);
			$body = stripslashes($body);
			$bodypreview = nl2br($body);
			$bodypreview = str_replace("src=\"", "src=\"../", $bodypreview);
			if ($body2 != "") {
				$body2 = stripslashes($body2);
				$body2preview = nl2br($body2);
				$body2preview = str_replace("src=\"", "src=\"../", $body2preview);
			}
			opentable($subject);
			echo "$bodypreview\n";
			closetable();
			if ($body2preview != "") {
				tablebreak();
				opentable($subject);
				echo "$body2preview\n";
				closetable();
			}
			tablebreak();
		}
		$result = dbquery("SELECT * FROM ".$fusion_prefix."news ORDER BY news_datestamp DESC");
		if (dbrows($result) != 0) {
			while ($data = dbarray($result)) {
				if (isset($news_id)) {
					if ($news_id == $data[news_id]) { $sel = " selected"; } else { $sel = ""; }
				}
				$editlist .= "<option value=\"$data[news_id]\"$sel>".stripslashes($data[news_subject])."</option>\n";
			}
		}
		opentable(LAN_208);
		echo "<form name=\"selectform\" method=\"post\" action=\"$PHP_SELF\">
<center>
<select name=\"news_id\" class=\"textbox\" style=\"width:200px;\">
$editlist</select>
<input type=\"submit\" name=\"edit\" value=\"".LAN_209."\" class=\"button\" style=\"width:60px;\">
<input type=\"submit\" name=\"delete\" value=\"".LAN_210."\" class=\"button\" style=\"width:60px;\">
</center>
</form>\n";
		closetable();
		tablebreak();
		if (isset($_POST['edit'])) {
			$result = dbquery("SELECT * FROM ".$fusion_prefix."news WHERE news_id='$news_id'");
			if (dbrows($result) != 0) {
				$data = dbarray($result);
				$subject = stripslashes($data[news_subject]);
				$body = stripslashes($data[news_news]);
				$body2 = stripslashes($data[news_extended]);
			}
		}
		if (isset($news_id)) {
			$action = $PHP_SELF."?news_id=$news_id";
			opentable(LAN_200);
		} else {
			$action = $PHP_SELF;
			opentable(LAN_204);
		}
		echo "<table align=\"center\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<form name=\"editform\" method=\"post\" action=\"$action\">
<tr>
<td width=\"100\">".LAN_211."</td><td width=\"80%\"><input type=\"textbox\" name=\"subject\" value=\"$subject\" class=\"textbox\" style=\"width: 250px;\"></td>
</tr>
<tr>
<td width=\"100\">".LAN_212."</td><td width=\"80%\"><textarea name=\"body\" cols=\"95\" rows=\"5\" class=\"textbox\" onselect=\"updatePos(this);\" onkeyup=\"updatePos(this);\" onclick=\"updatePos(this);\" ondblclick=\"updatePos(this);\">$body</textarea></td>
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
<td width=\"100\">".LAN_213."</td><td><textarea name=\"body2\" cols=\"95\" rows=\"10\" class=\"textbox\" onselect=\"updatePos(this);\" onkeyup=\"updatePos(this);\" onclick=\"updatePos(this);\" ondblclick=\"updatePos(this);\">$body2</textarea></td>
</tr>
<tr>
<td></td><td>
<input type=\"button\" value=\"b\" class=\"button\" style=\"font-weight:bold;width:25px;\" onClick=\"AddText2('<b>', '</b>');\">
<input type=\"button\" value=\"i\" class=\"button\" style=\"font-style:italic;width:25px;\" onClick=\"AddText2('<i>', '</i>');\">
<input type=\"button\" value=\"u\" class=\"button\" style=\"text-decoration:underline;width:25px;\" onClick=\"AddText2('<u>', '</u>');\">
<input type=\"button\" value=\"link\" class=\"button\" style=\"width:35px;\" onClick=\"AddText2('<a href=\u0022\u0022 target=\u0022_blank\u0022>', '</a>');\">
<input type=\"button\" value=\"img\" class=\"button\" style=\"width:35px;\" onClick=\"insertText2('<img src=\u0022images/\u0022 align=\u0022left\u0022>');\">
<input type=\"button\" value=\"center\" class=\"button\" style=\"width:45px;\" onClick=\"AddText2('<center>', '</center>');\">
<input type=\"button\" value=\"small\" class=\"button\" style=\"width:40px;\" onClick=\"AddText2('<span class=\u0022small\u0022>', '</span>');\">
<input type=\"button\" value=\"small2\" class=\"button\" style=\"width:45px;\" onClick=\"AddText2('<span class=\u0022small2\u0022>', '</span>');\">
<input type=\"button\" value=\"alt\" class=\"button\" style=\"width:25px;\" onClick=\"AddText2('<span class=\u0022alt\u0022>', '</span>');\"><br>
</td>
</tr>
<tr>
<td align=\"center\" colspan=\"2\"><br><input type=\"submit\" name=\"preview\" value=\"".LAN_214."\" class=\"button\" style=\"width: 95px;\">
<input type=\"submit\" name=\"save\" value=\"".LAN_215."\" class=\"button\" style=\"width: 95px;\"></td>
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