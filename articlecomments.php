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
require "subheader.php";
require fusion_langdir."comments.php";

if (empty($page)) {
	$page = 0;
}
		
if (isset($_POST['postcomment'])) {
	if ($settings[guestposts] == "1") {
		if ($userdata[user_name] != "") {
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
		$message = addslashes($message);
		$result = dbquery("INSERT INTO ".$fusion_prefix."comments VALUES('', '$article_id', '0', '$commentname', '$message', '".time()."', '$user_ip')");
		$result = dbquery("UPDATE ".$fusion_prefix."articles SET article_comments=article_comments+1 WHERE article_id='$article_id'");
	}
}

require "navigation.php";
require "sidebar.php";
echo "</td>
<td valign=\"top\" class=\"bodybg\">\n";

$result = dbquery("SELECT * FROM ".$fusion_prefix."articles WHERE article_id='$article_id'");
if (dbrows($result) != 0) {
	$data = dbarray($result);
	$article = stripslashes($data[article_article]);
	$article = explode("<--PAGEBREAK-->", $article);
	$pagecount = count($article) - 1;
	$article_subject = stripslashes($data[article_subject]);
	$article_date = strftime($settings[longdate], $data[article_datestamp]+($settings[timeoffset]*3600));
	$article_info = array("0", $data[article_email], $data[article_name], $article_date,
	$data[article_breaks], $data[article_comments], $data[article_reads]);
	render_article($article_subject, $article[$page], $article_info);
	if (count($article) > 1) {
		tablebreak();
		if ($page > 0) {
			$prevpage = $page - 1;
			$prev = "<a href=\"$PHP_SELF?article_id=$article_id&page=$prevpage\" class=\"white\">".LAN_50."</a>";
		}
		if ($page < $pagecount) {
			$nextpage = $page + 1;
			$next = "<a href=\"$PHP_SELF?article_id=$article_id&page=$nextpage\" class=\"white\">".LAN_51."</a>";
		}
		$currentpage = $page + 1;
		$current = LAN_52.$currentpage.LAN_53.count($article);
		prevnextbar($prev,$current,$next);
	}
}
tablebreak();
opentable(LAN_200);
$result = dbquery("SELECT * FROM ".$fusion_prefix."comments WHERE article_id='$article_id' ORDER BY comment_datestamp ASC");
$i = 1;
if (dbrows($result) != 0) {
	while ($data = dbarray($result)) {
		$message = stripslashes($data[comment_message]);
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
	echo LAN_201."\n";
}
closetable();
tablebreak();
opentable(LAN_202);
if ($settings[guestposts] == "1") {
	echo "<table align=\"center\" width=\"400\" cellpadding=\"0\" cellspacing=\"0\" class=\"body\">
<form name=\"postcomment\" method=\"post\" action=\"$PHP_SELF?article_id=$article_id\">
<tr>
<td>".LAN_203."</td>
</tr>
<tr>
<td><input type=\"text\" name=\"commentname\" value=\"$userdata[user_name]\" maxlength=\"32\" class=\"textbox\" style=\"width: 150px;\"></td>
</tr>
<tr>
<td>".LAN_204."</tr>
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
<a href=\"javascript:insertText(':)');\"><img src=\"".fusion_basedir."images/smiley/smile.gif\" border=\"0\"></a>
<a href=\"javascript:insertText(';)');\"><img src=\"".fusion_basedir."images/smiley/wink.gif\" border=\"0\"></a>
<a href=\"javascript:insertText(':|');\"><img src=\"".fusion_basedir."images/smiley/frown.gif\" border=\"0\"></a>
<a href=\"javascript:insertText(':(');\"><img src=\"".fusion_basedir."images/smiley/sad.gif\" border=\"0\"></a>
<a href=\"javascript:insertText(':o');\"><img src=\"".fusion_basedir."images/smiley/shock.gif\" border=\"0\"></a>
<a href=\"javascript:insertText(':p');\"><img src=\"".fusion_basedir."images/smiley/pfft.gif\" border=\"0\"></a>
<a href=\"javascript:insertText('B)');\"><img src=\"".fusion_basedir."images/smiley/cool.gif\" border=\"0\"></a>
<a href=\"javascript:insertText(':D');\"><img src=\"".fusion_basedir."images/smiley/grin.gif\" border=\"0\"></a>
<a href=\"javascript:insertText(':@');\"><img src=\"".fusion_basedir."images/smiley/angry.gif\" border=\"0\"></a></td>
</tr>
<tr>
<td align=\"center\">
<input type=\"submit\" name=\"postcomment\" value=\"".LAN_202."\" class=\"button\" style=\"width: 100px;\"></td>
</tr>
</form>
</table>\n";
} else {
	if ($userdata[user_name] != "") {
		echo "<table align=\"center\" width=\"400\" cellpadding=\"0\" cellspacing=\"0\" class=\"body\">
<form name=\"postcomment\" method=\"post\" action=\"$PHP_SELF?article_id=$article_id\">
<tr>
<td>".LAN_204."</tr>
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
<a href=\"javascript:insertText(':)');\"><img src=\"".fusion_basedir."images/smiley/smile.gif\" border=\"0\"></a>
<a href=\"javascript:insertText(';)');\"><img src=\"".fusion_basedir."images/smiley/wink.gif\" border=\"0\"></a>
<a href=\"javascript:insertText(':|');\"><img src=\"".fusion_basedir."images/smiley/frown.gif\" border=\"0\"></a>
<a href=\"javascript:insertText(':(');\"><img src=\"".fusion_basedir."images/smiley/sad.gif\" border=\"0\"></a>
<a href=\"javascript:insertText(':o');\"><img src=\"".fusion_basedir."images/smiley/shock.gif\" border=\"0\"></a>
<a href=\"javascript:insertText(':p');\"><img src=\"".fusion_basedir."images/smiley/pfft.gif\" border=\"0\"></a>
<a href=\"javascript:insertText('B)');\"><img src=\"".fusion_basedir."images/smiley/cool.gif\" border=\"0\"></a>
<a href=\"javascript:insertText(':D');\"><img src=\"".fusion_basedir."images/smiley/grin.gif\" border=\"0\"></a>
<a href=\"javascript:insertText(':@');\"><img src=\"".fusion_basedir."images/smiley/angry.gif\" border=\"0\"></a></td>
</tr>
<tr>
<td align=\"center\">
<input type=\"submit\" name=\"postcomment\" value=\"".LAN_202."\" class=\"button\" style=\"width: 100px;\"></td>
</tr>
</form>
</table>\n";
	} else {
		echo LAN_205."\n";
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

echo "</td>
<td width=\"170\" valign=\"top\" class=\"srborder\">\n";
require "userinfo.php";
require "poll.php";
require "shoutbox.php";
echo "</td>
</tr>
</table>\n";

require "footer.php";
?>