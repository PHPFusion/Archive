<?
require "header.php";
require "subheader.php";

echo "<tr><td width=\"170\" valign=\"top\" class=\"sideborder\">\n";
require "navigation.php";
require "sidebar.php";
echo "</td><td valign=\"top\">\n";
$result = dbquery("SELECT * FROM users WHERE userid='$lookup'");
$data = dbarray($result);
opentable("User Profile");
echo "<b>$data[username]</b><br><br>
<span class=\"alt\">Joined:</span> ".gmdate("m.d.Y", $data[joined])."<br>\n";
if ($data[location]) {
	echo "<span class=\"alt\">Location:</span> $data[location]<br>\n";
} else {
	echo "<span class=\"alt\">Location:</span> Not Specified<br>\n";
}
if ($data[icq]) {
	echo "<span class=\"alt\">ICQ#:</span> <a href=\"http://web.icq.com/wwp?Uin=$data[icq]\">$data[icq]</a><br>\n";
} else {
	echo "<span class=\"alt\">ICQ#:</span> Not Specified<br>\n";
}
if ($data[msn]) {
	echo "<span class=\"alt\">MSN ID:</span> <a href=\"mailto:$data[msn]\">$data[msn]</a><br>\n";
} else {
	echo "<span class=\"alt\">MSN ID:</span> Not Specified<br>\n";
}
if ($data[yahoo]) {
	echo "<span class=\"alt\">Yahoo ID:</span> <a href=\"http://uk.profiles.yahoo.com/$data[yahoo]\">$data[yahoo]</a><br>\n";
} else {
	echo "<span class=\"alt\">Yahoo ID:</span> Not Specified<br>\n";
}
if ($data[web]) {
	echo "<span class=\"alt\">Web URL:</span> <a href=\"$data[web]\">$data[web]</a><br>\n";
} else {
	echo "<span class=\"alt\">Web URL:</span> Not Specified<br>\n";
}
if ($data[userid] != $userdata[userid]) {
	echo "<br><a href=\"sendmessage.php?user=$data[userid]\">Send Private Message</a>\n";
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