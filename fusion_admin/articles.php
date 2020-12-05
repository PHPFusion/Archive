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
require fusion_langdir."admin/admin_news-articles.php";
require "navigation.php";

if (!Admin()) header("Location: ../index.php");

if (Admin()) {
	$result = dbquery("SELECT * FROM ".$fusion_prefix."article_cats");
	if (dbrows($result) != 0) {
		if (isset($_POST['save'])) {
			$subject = stripinput($subject);
			$body = addslashes($body);
			$body2 = addslashes($body2);
			if (isset($_POST['line_breaks'])) { $breaks = "y"; }
			if (isset($article_id)) {
				$result = dbquery("UPDATE ".$fusion_prefix."articles SET article_cat='$article_cat', article_subject='$subject', article_snippet='$body', article_article='$body2', article_breaks='$breaks' WHERE article_id='$article_id'");
				opentable(LAN_500);
				echo "<center><br>
".LAN_501."<br><br>
<a href='articles.php'>".LAN_502."</a><br><br>
<a href='index.php'>".LAN_503."</a><br><br>
</center>\n";
				closetable();
			} else {
				$result = dbquery("INSERT INTO ".$fusion_prefix."articles VALUES('', '$article_cat', '$subject', '$body', '$body2', '$breaks', '0', '".$userdata['user_id']."', '".time()."', '0')");
				opentable(LAN_504);
				echo "<center><br>
".LAN_505."<br><br>
<a href='articles.php'>".LAN_502."</a><br><br>
<a href='index.php'>".LAN_503."</a><br><br>
</center>\n";
				closetable();
			}
		} else if (isset($_POST['delete'])) {
			$result = dbquery("DELETE FROM ".$fusion_prefix."articles WHERE article_id='$article_id'");
			$result = dbquery("DELETE FROM ".$fusion_prefix."comments WHERE comment_item_id='$article_id' and comment_type='A'");
			opentable(LAN_506);
			echo "<center><br>
".LAN_507."<br><br>
<a href='articles.php'>".LAN_502."</a><br><br>
<a href='index.php'>".LAN_503."</a><br><br>
</center>\n";
			closetable();
		} else {
			if (isset($_POST['preview'])) {
				$subject = stripinput($subject);
				$body = stripslashes($body);
				$body2 = stripslashes($body2);
				$bodypreview = str_replace("src='", "src='../", $body);
				$body2preview = str_replace("src='", "src='../", $body2);
				if (isset($_POST['line_breaks'])) {
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
			opentable(LAN_508);
			$result = dbquery("SELECT * FROM ".$fusion_prefix."articles ORDER BY article_datestamp DESC");
			if (dbrows($result) != 0) {
				while ($data = dbarray($result)) {
					if (isset($article_id)) {
						if ($article_id == $data['article_id']) { $sel = " selected"; } else { $sel = ""; }
					}
					$editlist .= "<option value='".$data['article_id']."'$sel>".$data['article_subject']."</option>\n";
				}
			}
			echo "<form name='selectform' method='post' action='$PHP_SELF'>
<center>
<select name='article_id' class='textbox' style='width:250px;'>
$editlist</select>
<input type='submit' name='edit' value='".LAN_509."' class='button'>
<input type='submit' name='delete' value='".LAN_510."' class='button'>
</center>
</form>\n";
			closetable();
			tablebreak();
			if (isset($_POST['edit'])) {
				$result = dbquery("SELECT * FROM ".$fusion_prefix."articles WHERE article_id='$article_id'");
				if (dbrows($result) != 0) {
					$data = dbarray($result);
					$article_cat = $data['article_cat'];
					$subject = $data['article_subject'];
					$body = stripslashes($data['article_snippet']);
					$body2 = stripslashes($data['article_article']);
					if ($data['article_breaks'] == "y") { $breaks = " checked"; }
				}
			}
			if (isset($article_id)) {
				$action = $PHP_SELF."?article_id=$article_id&article_cat=$article_cat";
				opentable(LAN_500);
			} else {
				$action = $PHP_SELF;
				opentable(LAN_504);
			}
			$result = dbquery("SELECT * FROM ".$fusion_prefix."article_cats ORDER BY article_cat_name DESC");
			while ($data = dbarray($result)) {
				if (isset($article_cat)) {
					if ($article_cat == $data['article_cat_id']) { $sel = " selected"; } else { $sel = ""; }
				}
				$catlist .= "<option value='".$data['article_cat_id']."'$sel>".$data['article_cat_name']."</option>\n";
			}
			echo "<script language='Javascript'>
function ValidateForm(frm) {
	if(frm.subject.value=='') {
		alert('".LAN_550."');
		return false;
	}
}
</script>
<form name='editform' method='post' action='$action' onSubmit='return ValidateForm(this)'>
<table align='center' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td width='100'>".LAN_511."</td>
<td><select name='article_cat' class='textbox' style='width:250px;'>
$catlist</select></td>
</tr>
<tr>
<td width='100'>".LAN_512."</td>
<td><input type='text' name='subject' value='$subject' class='textbox' style='width:250px;'></td>
</tr>
<tr>
<td valign='top' width='100'>".LAN_513."</td>
<td><textarea name='body' cols='95' rows='5' class='textbox' onselect='updatePos(this);' onkeyup='updatePos(this);' onclick='updatePos(this);' ondblclick='updatePos(this);'>$body</textarea></td>
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
<td valign='top' width='100'>".LAN_514."</td>
<td><textarea name='body2' cols='95' rows='10' class='textbox' onselect='updatePos(this);' onkeyup='updatePos(this);' onclick='updatePos(this);' ondblclick='updatePos(this);'>$body2</textarea></td>
</tr>
<tr>
<td></td><td>
<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onClick=\"AddText2('<b>', '</b>');\">
<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onClick=\"AddText2('<i>', '</i>');\">
<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onClick=\"AddText2('<u>', '</u>');\">
<input type='button' value='link' class='button' style='width:35px;' onClick=\"AddText2('<a href=\'\' target=\'_blank\'>', '</a>');\">
<input type='button' value='img' class='button' style='width:35px;' onClick=\"insertText2('<img src=\'fusion_images/\' style=\'margin:5px;\' align=\'left\'>');\">
<input type='button' value='center' class='button' style='width:45px;' onClick=\"AddText2('<center>', '</center>');\">
<input type='button' value='small' class='button' style='width:40px;' onClick=\"AddText2('<span class=\'small\'>', '</span>');\">
<input type='button' value='small2' class='button' style='width:45px;' onClick=\"AddText2('<span class=\'small2\'>', '</span>');\">
<input type='button' value='alt' class='button' style='width:25px;' onClick=\"AddText2('<span class=\'alt\'>', '</span>');\">
<input type='button' value='new page' class='button' style='width:60px;' onClick=\"insertText2('<--PAGEBREAK-->');\">
</td>
</tr>
<tr>
<td align='center' colspan='2'><br>
<input type='checkbox' name='line_breaks' value='y'$breaks>".LAN_515."<br><br>
<input type='submit' name='preview' value='".LAN_516."' class='button'>
<input type='submit' name='save' value='".LAN_517."' class='button'></td>
</tr>
</table>
</form>\n";
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
		opentable(LAN_518);
		echo "<center>".LAN_519."<br>
".LAN_520."<br>
<a href='article_cats.php'>".LAN_521."</a>".LAN_522."</center>\n";
		closetable();
	}
}

echo "</td>\n";
require "../footer.php";
?>