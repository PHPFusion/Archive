<?
require "header.php";
require "subheader.php";

if (empty($page)) {
	$page = 0;
}
		
if (isset($_POST['postcomment'])) {
	if ($settings[visitorcomments] == "yes") {
		$commentname = stripinput($commentname);
	} else {
		$commentname = $userdata[username];
	}
	$message = stripinput($message);
	if ($commentname !="" && $message != "") {
		$message = addslashes($message);
		$result = dbquery("INSERT INTO articlecomments VALUES('', '$aid', '$commentname', '$message', '$servertime')");
		$result = dbquery("UPDATE articles SET comments=comments+1 WHERE aid='$aid'");
	}
}

echo "<tr><td width=\"170\" valign=\"top\" class=\"sideborder\">\n";
require "navigation.php";
require "sidebar.php";
echo "</td><td valign=\"top\">\n";
$result = dbquery("SELECT * FROM articles WHERE aid='$aid'");
if (dbrows($result) != 0) {
	$data = dbarray($result);
	$subject = stripslashes($data[subject]);
	$filename = $data[filename];
	$file = fOpen("articles/$filename","rb");
	$article = fRead($file, fileSize("articles/$filename"));
	fClose($file);
	if ($data[breaks] == "y") { $article = nl2br($article); }
	$postdate = gmdate("F d Y", $data[posted]);
	$posttime = gmdate("H:i", $data[posted]);
	$article = explode("<--PAGEBREAK-->", $article);
	$pagecount = count($article) - 1;
	opentablex();
	echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr><td colspan=\"2\" class=\"newstitle\">$subject<br>
<span class=\"newssubtitle\">Posted by <a href=\"mailto:$data[postemail]\" class=\"x\">$data[postname]</a> on $postdate at $posttime</span></td></tr>
<tr><td colspan=\"2\" class=\"newsbody\">$article[$page]</td></tr>
</table>\n";
	closetablex();
	if (count($article) > 1) {
		tablebreak();
		if ($page > 0) {
			$prevpage = $page - 1;
			$prev = "<a href=\"$PHP_SELF?aid=$aid&page=$prevpage\" class=\"x\">Prev</a>";
		}
		if ($page < $pagecount) {
			$nextpage = $page + 1;
			$next = "<a href=\"$PHP_SELF?aid=$aid&page=$nextpage\" class=\"x\">Next</a>";
		}
		$currentpage = $page + 1;
		opentablex();
		echo "<table width=\"100%\" class=\"nextprev\">
<tr><td width=\"60\" class=\"small2\">$prev</td>
<td align=\"center\" class=\"small2\">Page $currentpage of ".count($article)."</td>
<td width=\"60\" align=\"right\" class=\"small2\">$next</td></tr>
</table>\n";
		closetablex();
	}
}
tablebreak();
opentable("Comments");
$result = dbquery("SELECT * FROM articlecomments WHERE itemid='$aid' ORDER BY posted ASC");
$i = 1;
if (dbrows($result) != 0) {
	while ($data = dbarray($result)) {
		$posted = gmdate("F d Y", $data[posted])." at ".gmdate("H:i", $data[posted]);
		echo "<div align=\"left\">".stripslashes($data[message])."</div>
<div class=\"small\">&middot;&nbsp;$data[name] on $posted</div>\n";
		if ($i != dbrows($result)) {
			echo "<br>\n";
		} else {
			echo "\n";
		}
		$i++;
	}
} else {
	echo "No Comments have been Posted.\n";
}
closetable();
tablebreak();
opentable("Post Comment");
if ($settings[visitorcomments] == "yes") {
	echo "<form name=\"postcomment\" method=\"post\" action=\"$PHP_SELF?aid=$aid\">
Name:<br>
<input type=\"text\" name=\"commentname\" value=\"$userdata[username]\" maxlength=\"32\" class=\"textbox\" style=\"width: 150px;\"><br>
Messsge:<br>
<input type=\"text\" name=\"message\" maxlength=\"255\" class=\"textbox\" style=\"width: 100%;\"><br>
<div align=\"center\"><br>
<input type=\"submit\" name=\"postcomment\" value=\"post comment\" class=\"button\" style=\"width: 100px;\"></div>
</form>\n";
} else {
	if ($userdata[username] != "") {
		echo "<form name=\"postcomment\" method=\"post\" action=\"$PHP_SELF?aid=$aid\">
Name:<br>
<font class=\"alt2\">$userdata[username]</font><br>
Message:<br>
<input type=\"text\" name=\"message\" maxlength=\"255\" class=\"textbox\" style=\"width: 100%;\"><br>
<div align=\"center\"><br>
<input type=\"submit\" name=\"postcomment\" value=\"post comment\" class=\"button\" style=\"width: 100px;\"></div>
</form>\n";
	} else {
		echo "<font class=\"alt2\">Please <a href=\"login.php\">Login</a> to Post a Comment</font>\n";
	}
}
closetable();
echo "</td><td width=\"170\" valign=\"top\" class=\"sideborder\">\n";
require "userinfo.php";
require "showpoll.php";
require "shoutbox.php";
echo "</td></tr>
</table>\n";

require "footer.php";
?>