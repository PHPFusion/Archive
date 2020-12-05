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
require fusion_langdir."members-profile.php";

require "navigation.php";
require "sidebar.php";
echo "</td>
<td valign=\"top\" class=\"bodybg\">\n";

$result = dbquery("SELECT * FROM ".$fusion_prefix."users WHERE user_id='$lookup'");
$data = dbarray($result);
opentable(LAN_210.$data[user_name]);
echo "<table align=\"center\" cellpadding=\"0\" cellspacing=\"0\" class=\"body\">
<tr>
<td align=\"center\" width=\"110\">";
if ($data[user_avatar]) {
	echo "<img src=\"avatars/$data[user_avatar]\">";
} else {
	echo LAN_211;
}
echo "</td>
<td width=\"100\">
".LAN_212."<br>
".LAN_213."<br>
".LAN_214."<br>
".LAN_215."<br>
".LAN_216."<br>
".LAN_217."<br>
".LAN_218."<br>
".LAN_219."<br>
</td>
<td>\n";
if ($data[user_hide_email] != "1") {
	echo "<a href=\"mailto:$data[user_email]\">$data[user_email]</a><br>\n";
} else {
	echo LAN_230."<br>\n";
}
if ($data[user_location]) {
	echo "$data[user_location]<br>\n";
} else {
	echo LAN_231."<br>\n";
}
if ($data[user_icq]) {
	echo "<a href=\"http://web.icq.com/wwp?Uin=$data[user_icq]\">$data[user_icq]</a><br>\n";
} else {
	echo LAN_231."<br>\n";
}
if ($data[user_msn]) {
	echo "<a href=\"mailto:$data[user_msn]\">$data[user_msn]</a><br>\n";
} else {
	echo LAN_231."<br>\n";
}
if ($data[user_yahoo]) {
	echo "<a href=\"http://uk.profiles.yahoo.com/$data[user_yahoo]\">$data[user_yahoo]</a><br>\n";
} else {
	echo LAN_231."<br>\n";
}
if ($data[user_web]) {
	if (!strstr($data[user_web], "http://")) { $urlprefix = "http://"; }
	echo "<a href=\"".$urlprefix."$data[user_web]\">$data[user_web]</a><br>\n";
} else {
	echo LAN_231."<br>\n";
}
echo strftime("%d.%m.%y", $data[user_joined]+($settings[timeoffset]*3600))."<br>\n";
if ($data[user_lastvisit] != 0) {
	echo strftime("%d.%m.%y", $data[user_lastvisit]+($settings[timeoffset]*3600))."\n";
} else {
	echo LAN_232."\n";
}
echo "</td>
</tr>
<tr>
<td align=\"center\" colspan=\"3\">\n";
if ($data[user_id] != $userdata[user_id]) {
	echo "<br><a href=\"sendmessage.php?user_id=$data[user_id]\">".LAN_240."</a>\n";
}
echo "</td>
</tr>
</table>\n";
closetable();

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