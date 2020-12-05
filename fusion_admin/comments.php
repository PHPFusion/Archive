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
require fusion_langdir."admin/admin_comments.php";
require "navigation.php";

if (!Admin()) header("Location: ../index.php");

if (Admin()) {
	if ($article_id) {
		$query = "comment_item_id='$article_id' AND comment_type='A'";
		$del_query = "article_id='$article_id'";
		$url = "article_id=$article_id";
		$type = "articles SET article_comments='";
	} else if ($news_id) {
		$query = "comment_item_id='$news_id' AND comment_type='N'";
		$del_query = "news_id='$news_id'";
		$url = "news_id=$news_id";
		$type = "news SET news_comments='";
	}
	if (isset($_POST['save_comment'])) {
		$comment_message = stripinput($comment_message);
		$result = dbquery("UPDATE ".$fusion_prefix."comments SET comment_message='$comment_message' WHERE comment_id='$comment_id'");
		header("Location: comments.php?$url");
	}
	if ($step == "delete") {
		$result = dbquery("DELETE FROM ".$fusion_prefix."comments WHERE comment_id='$comment_id'");
		$count = dbrows(dbquery("SELECT * FROM ".$fusion_prefix."comments WHERE $query"));
		$result = dbquery("UPDATE ".$fusion_prefix.$type."$count' WHERE $del_query");
		if ($count == 0) $url = "";
		header("Location: comments.php?$url");
	}
	$result = dbquery("SELECT * FROM ".$fusion_prefix."articles WHERE article_comments!='0' ORDER BY article_datestamp DESC");
	if (dbrows($result) != 0) {
		while ($data = dbarray($result)) {
			if (isset($article_id)) { if ($article_id == $data[article_id]) { $sel = " selected"; } else { $sel = ""; }}
			$article_list .= "<option value='$data[article_id]'$sel>$data[article_subject] (".$data[article_comments].")</option>\n";
		}
	}
	$result = dbquery("SELECT * FROM ".$fusion_prefix."news WHERE news_comments!='0' ORDER BY news_datestamp DESC");
	if (dbrows($result) != 0) {
		while ($data = dbarray($result)) {
			if (isset($news_id)) { if ($news_id == $data[news_id]) { $sel = " selected"; } else { $sel = ""; }}
			$news_list .= "<option value='$data[news_id]'$sel>$data[news_subject] (".$data[news_comments].")</option>\n";
		}
	}
	opentable(LAN_400);
	echo "<table align='center' cellpadding='0' cellspacing='0' class='body'>
<form name='articles_form' method='post' action='$PHP_SELF'>
<tr>
<td>".LAN_402."<br />
<select name='article_id' class='textbox' style='width:250px;'>
$article_list</select>
<input type='submit' name='view' value='".LAN_401."' class='button' />
</td>
</form>
</tr>
<form name='news_form' method='post' action='$PHP_SELF?'>
<tr>
<td>".LAN_403."<br />
<select name='news_id' class='textbox' style='width:250px;'>
$news_list</select>
<input type='submit' name='view' value='".LAN_401."' class='button' />
</td>
</tr>
</form>
</table>\n";
	closetable();
	if ($step == "edit") {
		$data = dbarray(dbquery("SELECT * FROM ".$fusion_prefix."comments WHERE comment_id=$comment_id"));
		tablebreak();
		opentable(LAN_420);
		echo "<form name='editcomment' method='post' action='$PHP_SELF?comment_id=$comment_id&$url'>
<table align='center' width='400' cellpadding='0' cellspacing='0' class='body'>
<tr>
<td align='center'><textarea name='comment_message' rows='5' class='textbox' style='width:400px' onselect='updatePos(this);' onkeyup='updatePos(this);' onclick='updatePos(this);' ondblclick='updatePos(this);'>$data[comment_message]</textarea><br>
<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onClick=\"AddText('b');\" />
<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onClick=\"AddText('i');\" />
<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onClick=\"AddText('u');\" />
<input type='button' value='url' class='button' style='width:30px;' onClick=\"AddText('url');\" />
<input type='button' value='mail' class='button' style='width:35px;' onClick=\"AddText('mail');\" />
<input type='button' value='img' class='button' style='width:30px;' onClick=\"AddText('img');\" />
<input type='button' value='center' class='button' style='width:45px;' onClick=\"AddText('center');\" />
<input type='button' value='small' class='button' style='width:40px;' onClick=\"AddText('small');\" />
</tr>
<tr>
<td align='center'><input type='submit' name='save_comment' value='".LAN_421."' class='button' /></td>
</tr>
</table>
</form>\n";
		closetable();
		echo "<script language='JavaScript'>
var editBody = document.editcomment.comment_message;
function insertText(theText) {
	if (editBody.createTextRange && editBody.curPos) {
		editBody.curPos.text = theText;
	} else {
		editBody.value += theText;
	}
	editBody.focus();
}
function AddText(wrap) {
	if (editBody.curPos) {
		insertText(\"[\" + wrap + \"]\" + editBody.curPos.text + \"[/\" + wrap + \"]\");
	} else {
		insertText(\"[\" + wrap + \"][/\" + wrap + \"]\");
	}
}
function updatePos(obj) {
	if (obj.createTextRange) {
		obj.curPos = document.selection.createRange().duplicate();
	}
}
</script>\n";
	}
	if ($article_id || $news_id) {
		tablebreak();
		opentable(LAN_430);
		echo "<table width='100%' cellpadding='0' cellspacing='0'>\n";
		$result = dbquery("SELECT * FROM ".$fusion_prefix."comments WHERE $query");
		$numrows = dbrows($result);
		$i = 1;
		while ($data = dbarray($result)) {
			$postee = explode(".", $data[comment_name]);
			if ($postee[0] != 0) {
				$comment_name = "<a href='../profile.php?lookup=$postee[0]'>$postee[1]</a>";
			} else {
				$comment_name = "$postee[1]";
			}
			echo "<tr>
<td colspan='2'>$comment_name</td>
</tr>
<tr>
<td colspan='2'>".parseubb(parsesmileys($data[comment_message]))."</td>
</tr>
<tr>
<td class='small'><a href='$PHP_SELF?step=edit&comment_id=$data[comment_id]&$url'>".LAN_431."</a> |
<a href='$PHP_SELF?step=delete&comment_id=$data[comment_id]&$url' onClick='return DeleteItem()'>".LAN_432."</a></td>
<td align='right' class='small'>".strftime($settings[longdate], $data[comment_datestamp]+($settings[timeoffset]*3600))."</td></tr>\n";
			if ($i != $numrows) { echo "<tr>\n<td colspan='2' height='10'></td>\n</tr>\n"; }
			$i++;
		}
		echo "</table>\n";
		closetable();
		echo "<script>
function DeleteItem()
{
	return confirm(\"".LAN_433."\");
}
</script>\n";
	}
}

echo "</td>\n";
require "../footer.php";
?>