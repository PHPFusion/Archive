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
require fusion_langdir."messages.php";

require "navigation.php";
require "sidebar.php";
echo "</td>
<td valign=\"top\" class=\"bodybg\">\n";

if ($userdata[user_name] != "") {
	if (isset($delete)) {
		$result = dbquery("DELETE FROM ".$fusion_prefix."messages WHERE message_to='$userdata[user_id].$userdata[user_name]' AND message_id='$delete'");
	}
	opentable(LAN_200);
	echo "<table align=\"center\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">\n";
	$result = dbquery("SELECT * FROM ".$fusion_prefix."messages WHERE message_to='$userdata[user_id].$userdata[user_name]' ORDER BY message_datestamp DESC");
	if (dbrows($result) != 0) {
		echo "<tr>
<td width=\"40\"></td>
<td>".LAN_201."</td>
<td>".LAN_202."</td>
<td width=\"110\">".LAN_203."</td>
</tr>\n";
		while ($data = dbarray($result)) {
			$postee = explode(".", $data[message_from]);
			$recieved = strftime($settings[shortdate], $data[message_datestamp]+($settings[timeoffset]*3600));
			if ($data[message_read] == 1) {
				echo "<tr>
<td class=\"small\"><a href=\"$PHP_SELF?delete=$data[message_id]\" onClick=\"return DeleteMessage();\">".LAN_204."</a></td>\n";
				}else {
				echo "<tr>
<td class=\"small\">".LAN_204."</td>\n";
			}
			echo "<td><a href=\"readmessage.php?message_id=$data[message_id]\">";
			if ($data[message_read] != 1) {
				echo "<b>".stripslashes($data[message_subject])."</b>";
			} else {
				echo stripslashes($data[message_subject]);
			}
			echo "</a></td>
<td><a href=\"profile.php?lookup=$postee[0]\">$postee[1]</a></td>
<td>$recieved</td>
</tr>\n";
		}
	} else {
		echo "<tr>
<td>".LAN_210."</td>
</tr>\n";
	}
	echo "</table>\n";
	closetable();
	echo "<script>
function DeleteMessage() {
	return confirm(\"".LAN_211."\");
}
function DeleteAll() {
	return confirm(\"".LAN_212."\");
}
</script>\n";
} else {
	opentable(LAN_200);
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