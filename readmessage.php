<?
require "header.php";
require "subheader.php";

echo "<tr><td width=\"170\" valign=\"top\" class=\"sideborder\">\n";
require "navigation.php";
require "sidebar.php";
echo "</td><td valign=\"top\">\n";
if ($userdata[username] != "") {
	$result = dbquery("SELECT * FROM messages WHERE touser='$userdata[userid]' AND mid='$mid'");
	if (dbrows($result) != 0) {
		$data = dbarray($result);
		$result2 = dbquery("SELECT * FROM users WHERE userid='$data[fromuser]'");
		$data2 = dbarray($result2);
		$subject = stripslashes($data[subject]);
		$message = nl2br(stripslashes($data[message]));
		$recieved = gmdate("F d Y", $servertime)." at ".gmdate("H:i", $servertime);
		if ($data[isread] == 0) {
			$result = dbquery("UPDATE messages SET isread='1' WHERE mid='$mid'");
		}
		opentable("Read Private Message");
		echo "<table align=\"center\" width=\"450\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<tr><td><span class=\"alt\">To:</span> $userdata[username]<br>
<span class=\"alt\">From:</span> $data2[username]<br>
<span class=\"alt\">Recieved:</span> $recieved<br>
<span class=\"alt\">Subject:</span> $subject<br><br>
<span class=\"alt\">Message:</span><br>
$message</td></tr>
<tr><td colspan=\"2\"><br><div align=\"center\">
<a href=\"sendmessage.php?user=$data[fromuser]&replyid=$data[mid]\">Reply to Message</a><br><br>
<a href=\"messages.php\">Return to Private Messages Inbox</a></div></td></tr>
</table>\n";
		closetable();
	}
} else {
	opentable("Read Private Message");
	echo "Members Only\n";
	closetable();
}
echo "</td><td width=\"170\" valign=\"top\" class=\"sideborder\">\n";
require "userinfo.php";
require "showpoll.php";
require "shoutbox.php";
echo "</td></tr>
</table>\n";

require "footer.php";
?>