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
@require "fusion_config.php";
require "header.php";
require "subheader.php";
require fusion_langdir."comments.php";
require "side_left.php";

if (!$rowstart) $rowstart = 0;
if (isset($_POST['postcomment'])) {
	if ($settings[guestposts] == "1") {
		if (Member()) {
			$commentname = $userdata[user_id].".".$userdata[user_name];
		} else {
			$commentname = stripinput($commentname);
			if ($commentname!="") { $commentname = "0.".$commentname; }
		}
	} else {
		$commentname = $userdata[user_id].".".$userdata[user_name];
	}
	$message = stripinput($message);
	if ($commentname !="" && $message != "") {
		$result = dbquery("INSERT INTO ".$fusion_prefix."comments VALUES('', '$article_id', 'A', '$commentname', '$message', '".time()."', '$user_ip')");
		$result = dbquery("UPDATE ".$fusion_prefix."articles SET article_comments=article_comments+1 WHERE article_id='$article_id'");
	}
}
$result = dbquery("SELECT * FROM ".$fusion_prefix."articles WHERE article_id='$article_id'");
if (dbrows($result) != 0) {
	$data = dbarray($result);
	$article = stripslashes($data[article_article]);
	$article = explode("<--PAGEBREAK-->", $article);
	$pagecount = count($article);
	$article_subject = $data[article_subject];
	$article_date = strftime($settings[longdate], $data[article_datestamp]+($settings[timeoffset]*3600));
	$article_info = array("0", $data[article_email], $data[article_name], $article_date,
	$data[article_breaks], $data[article_comments], $data[article_reads]);
	render_article($article_subject, $article[$rowstart], $article_info);
	if (count($article) > 1) {
		$itemsperpage = 1;
		$rows = $pagecount;
		echo "<div align='center' style='margin-top:5px;'>
".makePageNav($rowstart,$itemsperpage,$rows,3,"small","$PHP_SELF?article_id=$article_id&page=$nextpage&")."
</div>\n";
	}
}
tablebreak();
opentable(LAN_400);
$result = dbquery("SELECT * FROM ".$fusion_prefix."comments WHERE comment_item_id='$article_id' AND comment_type='A' ORDER BY comment_datestamp ASC");
$i = 1;
if (dbrows($result) != 0) {
	while ($data = dbarray($result)) {
		$message = $data[comment_message];
		$message = parseubb($message);
		$message = parsesmileys($message);
		$postee = explode(".", $data[comment_name]);
		echo "<span class=\"shoutboxname\">";
		if ($postee[0] != 0) {
			echo "<a href=\"profile.php?lookup=$postee[0]\">$postee[1]</a>";
		} else {
			echo "$postee[1]";
		}
		echo "</span><br>
$message<br>
<span class=\"shoutboxdate\">".strftime($settings[longdate], $data[comment_datestamp]+($settings[timeoffset]*3600))."</span>\n";
		if ($i != dbrows($result)) {
			echo "<br><br>\n";
		} else {
			echo "\n";
		}
		$i++;
	}
} else {
	echo LAN_401."\n";
}
closetable();
tablebreak();
opentable(LAN_402);
if ($settings[guestposts] == "1") {
	echo "<form name=\"postcomment\" method=\"post\" action=\"$PHP_SELF?article_id=$article_id\">
<table align=\"center\" width=\"400\" cellpadding=\"0\" cellspacing=\"0\" class=\"body\">
<tr>
<td>".LAN_403."</td>
</tr>
<tr>
<td><input type=\"text\" name=\"commentname\" value=\"$userdata[user_name]\" maxlength=\"32\" class=\"textbox\" style=\"width: 150px;\"></td>
</tr>
<tr>
<td>".LAN_404."</tr>
<tr>
<td align=\"center\"><textarea name=\"message\" rows=\"5\" class=\"textbox\" style=\"width:400px\" onselect=\"updatePos(this);\" onkeyup=\"updatePos(this);\" onclick=\"updatePos(this);\" ondblclick=\"updatePos(this);\"></textarea><br>
<input type=\"button\" value=\"b\" class=\"button\" style=\"font-weight:bold;width:25px;\" onClick=\"AddText('b');\">
<input type=\"button\" value=\"i\" class=\"button\" style=\"font-style:italic;width:25px;\" onClick=\"AddText('i');\">
<input type=\"button\" value=\"u\" class=\"button\" style=\"text-decoration:underline;width:25px;\" onClick=\"AddText('u');\">
<input type=\"button\" value=\"url\" class=\"button\" style=\"width:30px;\" onClick=\"AddText('url');\">
<input type=\"button\" value=\"mail\" class=\"button\" style=\"width:35px;\" onClick=\"AddText('mail');\">
<input type=\"button\" value=\"img\" class=\"button\" style=\"width:30px;\" onClick=\"AddText('img');\">
<input type=\"button\" value=\"center\" class=\"button\" style=\"width:45px;\" onClick=\"AddText('center');\">
<input type=\"button\" value=\"small\" class=\"button\" style=\"width:40px;\" onClick=\"AddText('small');\">
<br><br>
<a href=\"javascript:insertText(':)');\"><img src=\"".fusion_basedir."fusion_images/smiley/smile.gif\" border=\"0\"></a>
<a href=\"javascript:insertText(';)');\"><img src=\"".fusion_basedir."fusion_images/smiley/wink.gif\" border=\"0\"></a>
<a href=\"javascript:insertText(':|');\"><img src=\"".fusion_basedir."fusion_images/smiley/frown.gif\" border=\"0\"></a>
<a href=\"javascript:insertText(':(');\"><img src=\"".fusion_basedir."fusion_images/smiley/sad.gif\" border=\"0\"></a>
<a href=\"javascript:insertText(':o');\"><img src=\"".fusion_basedir."fusion_images/smiley/shock.gif\" border=\"0\"></a>
<a href=\"javascript:insertText(':p');\"><img src=\"".fusion_basedir."fusion_images/smiley/pfft.gif\" border=\"0\"></a>
<a href=\"javascript:insertText('B)');\"><img src=\"".fusion_basedir."fusion_images/smiley/cool.gif\" border=\"0\"></a>
<a href=\"javascript:insertText(':D');\"><img src=\"".fusion_basedir."fusion_images/smiley/grin.gif\" border=\"0\"></a>
<a href=\"javascript:insertText(':@');\"><img src=\"".fusion_basedir."fusion_images/smiley/angry.gif\" border=\"0\"></a></td>
</tr>
<tr>
<td align=\"center\">
<input type=\"submit\" name=\"postcomment\" value=\"".LAN_402."\" class=\"button\"></td>
</tr>
</table>
</form>\n";
} else {
	if (Member()) {
		echo "<form name=\"postcomment\" method=\"post\" action=\"$PHP_SELF?article_id=$article_id\">
<table align=\"center\" width=\"400\" cellpadding=\"0\" cellspacing=\"0\" class=\"body\">
<tr>
<td>".LAN_404."</tr>
<tr>
<td align=\"center\"><textarea name=\"message\" rows=\"5\" class=\"textbox\" style=\"width:400px\" onselect=\"updatePos(this);\" onkeyup=\"updatePos(this);\" onclick=\"updatePos(this);\" ondblclick=\"updatePos(this);\"></textarea><br>
<input type=\"button\" value=\"b\" class=\"button\" style=\"font-weight:bold;width:25px;\" onClick=\"AddText('b');\">
<input type=\"button\" value=\"i\" class=\"button\" style=\"font-style:italic;width:25px;\" onClick=\"AddText('i');\">
<input type=\"button\" value=\"u\" class=\"button\" style=\"text-decoration:underline;width:25px;\" onClick=\"AddText('u');\">
<input type=\"button\" value=\"url\" class=\"button\" style=\"width:30px;\" onClick=\"AddText('url');\">
<input type=\"button\" value=\"mail\" class=\"button\" style=\"width:35px;\" onClick=\"AddText('mail');\">
<input type=\"button\" value=\"img\" class=\"button\" style=\"width:30px;\" onClick=\"AddText('img');\">
<input type=\"button\" value=\"center\" class=\"button\" style=\"width:45px;\" onClick=\"AddText('center');\">
<input type=\"button\" value=\"small\" class=\"button\" style=\"width:40px;\" onClick=\"AddText('small');\">
<br><br>
<a href=\"javascript:insertText(':)');\"><img src=\"".fusion_basedir."fusion_images/smiley/smile.gif\" border=\"0\"></a>
<a href=\"javascript:insertText(';)');\"><img src=\"".fusion_basedir."fusion_images/smiley/wink.gif\" border=\"0\"></a>
<a href=\"javascript:insertText(':|');\"><img src=\"".fusion_basedir."fusion_images/smiley/frown.gif\" border=\"0\"></a>
<a href=\"javascript:insertText(':(');\"><img src=\"".fusion_basedir."fusion_images/smiley/sad.gif\" border=\"0\"></a>
<a href=\"javascript:insertText(':o');\"><img src=\"".fusion_basedir."fusion_images/smiley/shock.gif\" border=\"0\"></a>
<a href=\"javascript:insertText(':p');\"><img src=\"".fusion_basedir."fusion_images/smiley/pfft.gif\" border=\"0\"></a>
<a href=\"javascript:insertText('B)');\"><img src=\"".fusion_basedir."fusion_images/smiley/cool.gif\" border=\"0\"></a>
<a href=\"javascript:insertText(':D');\"><img src=\"".fusion_basedir."fusion_images/smiley/grin.gif\" border=\"0\"></a>
<a href=\"javascript:insertText(':@');\"><img src=\"".fusion_basedir."fusion_images/smiley/angry.gif\" border=\"0\"></a></td>
</tr>
<tr>
<td align=\"center\">
<input type=\"submit\" name=\"postcomment\" value=\"".LAN_402."\" class=\"button\"></td>
</tr>
</table>
</form>\n";
	} else {
		echo LAN_405."\n";
	}
}
closetable();
	echo "<script language=\"JavaScript\">
var editBody = document.postcomment.message;
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

require "side_right.php";
require "footer.php";
?>