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

$result = dbquery("SELECT * FROM ".$fusion_prefix."articles ORDER BY article_datestamp DESC");
if (dbrows($result) != 0) {
	while ($data = dbarray($result)) {
		if (isset($article_id)) {
			if ($article_id == $data[article_id]) { $sel = " selected"; } else { $sel = ""; }
		}
		$editlist .= "<option value=\"$data[article_id]\"$sel>".stripslashes($data[article_subject])."</option>\n";
	}
}

require "navigation.php";
require "userinfo.php";
echo "</td>
<td valign=\"top\" class=\"bodybg\">\n";

if ($userdata[user_mod] > "1") {
	$result = dbquery("SELECT * FROM ".$fusion_prefix."article_cats");
	if (dbrows($result) != 0) {
		if (isset($_POST['save'])) {
			$subject = stripinput($subject);
			$subject = addslashes($subject);
			$body = addslashes($body);
			$body2 = addslashes($body2);
			if ($linebreaks == "yes") { $breaks = "y"; }
			if (isset($article_id)) {
				if ($subject != "" && $body2 != "") {
					$result = dbquery("UPDATE ".$fusion_prefix."articles SET article_cat='$article_cat', article_subject='$subject', article_snippet='$body', article_article='$body2', article_breaks='$breaks' WHERE article_id='$article_id'");
				}
				opentable(LAN_300);
				echo "<center><br>
".LAN_301."<br><br>
<a href=\"articles.php\">".LAN_302."</a><br><br>
<a href=\"index.php\">".LAN_303."</a><br><br>
</center>\n";
				closetable();
			} else {
				if ($subject != "" && $body2 != "") {
					$result = dbquery("INSERT INTO ".$fusion_prefix."articles VALUES('', '$article_cat', '$subject', '$body', '$body2', '$breaks', '0', '$userdata[user_name]', '$userdata[user_email]', '".time()."', '0')");
				}
				opentable(LAN_304);
				echo "<center><br>
".LAN_305."<br><br>
<a href=\"articles.php\">".LAN_302."</a><br><br>
<a href=\"index.php\">".LAN_303."</a><br><br>
</center>\n";
				closetable();
			}
		} else if (isset($_POST['delete'])) {
			$result = dbquery("DELETE FROM ".$fusion_prefix."articles WHERE article_id='$article_id'");
			$result = dbquery("DELETE FROM ".$fusion_prefix."comments WHERE article_id='$article_id'");
			opentable(LAN_306);
			echo "<center><br>
".LAN_307."<br><br>
<a href=\"articles.php\">".LAN_302."</a><br><br>
<a href=\"index.php\">".LAN_303."</a><br><br>
</center>\n";
			closetable();
		} else {
			if (isset($_POST['preview'])) {
				$subject = stripinput($subject);
				$subject = stripslashes($subject);
				$body = stripslashes($body);
				$body2 = stripslashes($body2);
				$bodypreview = str_replace("src=\"", "src=\"../", $body);
				$body2preview = str_replace("src=\"", "src=\"../", $body2);
				if ($linebreaks == "yes") {
					$breaks = " checked";
					$bodypreview = nl2br($bodypreview);
					$body2preview = nl2br($body2preview);
				}
				opentable($subject);
				echo "$bodypreview\n";
				closetable();
				tablebreak();
				opentable($subject);
				echo "$body2preview\n";
				closetable();
				tablebreak();
			}
			opentable(LAN_308);
			echo "<form name=\"selectform\" method=\"post\" action=\"$PHP_SELF\">
<center>
<select name=\"article_id\" class=\"textbox\" style=\"width:200px;\">
$editlist</select>
<input type=\"submit\" name=\"edit\" value=\"".LAN_309."\" class=\"button\" style=\"width:60px;\">
<input type=\"submit\" name=\"delete\" value=\"".LAN_310."\" class=\"button\" style=\"width:60px;\">
</center>
</form>\n";
			closetable();
			tablebreak();
			if (isset($_POST['edit'])) {
				$result = dbquery("SELECT * FROM ".$fusion_prefix."articles WHERE article_id='$article_id'");
				if (dbrows($result) != 0) {
					$data = dbarray($result);
					$article_cat = $data[article_cat];
					$subject = stripslashes($data[article_subject]);
					$body = stripslashes($data[article_snippet]);
					$body2 = stripslashes($data[article_article]);
					if ($data[article_breaks] == "y") { $breaks = " checked"; }
				}
			}
			if (isset($article_id)) {
				$action = $PHP_SELF."?article_id=$article_id&article_cat=$article_id";
				opentable(LAN_300);
			} else {
				$action = $PHP_SELF;
				opentable(LAN_304);
			}
			$result = dbquery("SELECT * FROM ".$fusion_prefix."article_cats ORDER BY article_cat_name DESC");
			if (dbrows($result) != 0) {
				while ($data = dbarray($result)) {
					if (isset($article_id)) {
						if ($article_cat == $data[article_cat_id]) { $sel = " selected"; } else { $sel = ""; }
					}
					$catlist .= "<option value=\"$data[article_cat_id]\"$sel>".stripslashes($data[article_cat_name])."</option>\n";
				}
			}
			echo "<table align=\"center\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<form name=\"editform\" method=\"post\" action=\"$action\">
<tr>
<td width=\"100\">".LAN_311."</td>
<td><select name=\"article_cat\" class=\"textbox\" style=\"width:150px;\">
$catlist</select></td>
</tr>
<tr>
<td width=\"100\">".LAN_312."</td>
<td><input type=\"textbox\" name=\"subject\" value=\"$subject\" class=\"textbox\" style=\"width:250px;\"></td>
</tr>
<tr>
<td valign=\"top\" width=\"100\">".LAN_313."</td>
<td><textarea name=\"body\" cols=\"95\" rows=\"5\" class=\"textbox\" onselect=\"updatePos(this);\" onkeyup=\"updatePos(this);\" onclick=\"updatePos(this);\" ondblclick=\"updatePos(this);\">$body</textarea></td>
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
<td valign=\"top\" width=\"100\">".LAN_314."</td>
<td><textarea name=\"body2\" cols=\"95\" rows=\"10\" class=\"textbox\" onselect=\"updatePos(this);\" onkeyup=\"updatePos(this);\" onclick=\"updatePos(this);\" ondblclick=\"updatePos(this);\">$body2</textarea></td>
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
<td align=\"center\" colspan=\"2\"><br>
<input type=\"checkbox\" name=\"linebreaks\" value=\"yes\"$breaks>".LAN_315."<br><br>
<input type=\"submit\" name=\"preview\" value=\"".LAN_316."\" class=\"button\" style=\"width:95px;\">
<input type=\"submit\" name=\"save\" value=\"".LAN_317."\" class=\"button\" style=\"width: 95px;\"></td>
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
	} else {
		opentable(LAN_318);
		echo "<center>".LAN_319."<br>
".LAN_320."<br>
<a href=\"article_cats.php\">".LAN_321."</a>".LAN_322."</center>\n";
		closetable();
	}
}

echo "</td>
</tr>
</table>\n";

require "../footer.php";
?>