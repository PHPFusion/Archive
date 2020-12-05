<?
require "header.php";
require "subheader.php";

if (isset($_POST['postcomment'])) {
	if ($settings[visitorcomments] == "yes") {
		$commentname = stripinput($commentname);
	} else {
		$commentname = $userdata[username];
	}
	$message = stripinput($message);
	if ($commentname !="" && $message != "") {
		$message = addslashes($message);
		$result = dbquery("INSERT INTO newscomments VALUES('', '$nid', '$commentname', '$message', '$servertime')");
		$result = dbquery("UPDATE news SET comments=comments+1 WHERE nid='$nid'");
	}
} else {
	$result = dbquery("UPDATE news SET reads=reads+1 WHERE nid='$nid'");
}

echo "<tr><td width=\"170\" valign=\"top\" class=\"sideborder\">\n";
require "navigation.php";
require "sidebar.php";
echo "</td><td valign=\"top\">\n";

$result = dbquery("SELECT * FROM news WHERE nid='$nid'");
$data = dbarray($result);
$subject = stripslashes($data[subject]);
if ($data[extendednews] != "") {
	$extendednews = stripslashes($data[extendednews]);
	$extendednews = nl2br($extendednews);
} else {
	$news = stripslashes($data[news]);
	$news = nl2br($news);
}
$postdate = gmdate("F d Y", $data[posted]);
$posttime = gmdate("H:i", $data[posted]);
opentablex();
echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr><td class=\"newstitle\">$subject<br>
<span class=\"newssubtitle\">Posted by <a href=\"mailto:$data[postemail]\" class=\"x\">".$data[postname]."</a> on $postdate at $posttime</span></td></tr>\n";
if ($data[extendednews] != "") {
	echo "<tr><td class=\"newsbody\">$extendednews</td></tr>\n";
} else {
	echo "<tr><td class=\"newsbody\">$news</td></tr>\n";
}
echo "</table>\n";
closetablex();
tablebreak();
opentable("Comments");
$result = dbquery("SELECT * FROM newscomments WHERE itemid='$nid' ORDER BY posted ASC");
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
	echo "<form name=\"postcomment\" method=\"post\" action=\"$PHP_SELF?nid=$nid\">
Name:<br>
<input type=\"text\" name=\"commentname\" value=\"$userdata[username]\" maxlength=\"32\" class=\"textbox\" style=\"width: 150px;\"><br>
Messsge:<br>
<input type=\"text\" name=\"message\" maxlength=\"255\" class=\"textbox\" style=\"width: 100%;\"><br>
<div align=\"center\"><br>
<input type=\"submit\" name=\"postcomment\" value=\"post comment\" class=\"button\" style=\"width: 100px;\"></div>
</form>\n";
} else {
	if ($userdata[username] != "") {
		echo "<form name=\"postcomment\" method=\"post\" action=\"$PHP_SELF?nid=$nid\">
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