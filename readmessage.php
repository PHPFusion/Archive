<?
/*
-------------------------------------------------------
	PHP-Fusion
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
require fusion_langdir."messages.php";

require "navigation.php";
require "sidebar.php";
echo "</td>
<td valign=\"top\" class=\"bodybg\">\n";

if ($userdata[user_name] != "") {
	$result = dbquery("SELECT * FROM ".$fusion_prefix."messages WHERE message_to='$userdata[user_id].$userdata[user_name]' AND message_id='$message_id'");
	if (dbrows($result) != 0) {
		$data = dbarray($result);
		$postee = explode(".", $data[message_from]);
		$subject = $data[message_subject];
		$message = nl2br($data[message_message]);
		$message = parsesmileys($message);
		$recieved = strftime($settings[shortdate], $data[message_datestamp]+($settings[timeoffset]*3600));
		if ($data[message_read] == 0) {
			$result = dbquery("UPDATE ".$fusion_prefix."messages SET message_read='1' WHERE message_id='$message_id'");
		}
		opentable(LAN_310);
		echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<tr>
<td><span class=\"alt\">".LAN_290."</span> $userdata[user_name]<br>
<span class=\"alt\">".LAN_291."</span> $postee[1]<br>
<span class=\"alt\">".LAN_292."</span> $recieved<br>
<span class=\"alt\">".LAN_293."</span> $subject<br><br>
<span class=\"alt\">".LAN_294."</span><br>
$message</td>
</tr>
<tr>
<td align=\"center\" colspan=\"2\"><br>
<a href=\"sendmessage.php?user_id=$postee[0]&reply_id=$data[message_id]\">".LAN_320."</a><br><br>
<a href=\"messages.php\">".LAN_321."</a></td>
</tr>
</table>\n";
		closetable();
	}
} else {
	opentable(LAN_310);
	echo LAN_213."\n";
	closetable();
}

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