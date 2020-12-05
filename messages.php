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
require fusion_langdir."messages.php";
require "side_left.php";

if (Member()) {
	if (isset($delete)) {
		if ($delete == "all") {
			$result = dbquery("DELETE FROM ".$fusion_prefix."messages WHERE message_to='$userdata[user_id]' AND message_read='1'");
		} else {
			$result = dbquery("DELETE FROM ".$fusion_prefix."messages WHERE message_to='$userdata[user_id]' AND message_id='$delete'");
		}
	}
	opentable(LAN_400);
	$result = dbquery("SELECT * FROM ".$fusion_prefix."messages INNER JOIN ".$fusion_prefix."users ON ".$fusion_prefix."messages.message_from=".$fusion_prefix."users.user_id WHERE message_to='$userdata[user_id]' ORDER BY message_datestamp DESC");
	if (dbrows($result) != 0) {
		echo "<table align=\"center\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<tr>
<td width=\"40\"></td>
<td>".LAN_401."</td>
<td>".LAN_402."</td>
<td width=\"110\">".LAN_403."</td>
</tr>\n";
		while ($data = dbarray($result)) {
			$recieved = strftime($settings[shortdate], $data[message_datestamp]+($settings[timeoffset]*3600));
			if ($data[message_read] == 1) {
				echo "<tr>
<td class=\"small\"><a href=\"$PHP_SELF?delete=$data[message_id]\" onClick=\"return DeleteMessage();\">".LAN_404."</a></td>\n";
				}else {
				echo "<tr>
<td class=\"small\">".LAN_404."</td>\n";
			}
			echo "<td><a href=\"readmessage.php?message_id=$data[message_id]\">";
			if ($data[message_read] != 1) {
				echo "<b>".$data[message_subject]."</b>";
			} else {
				echo $data[message_subject];
			}
			echo "</a></td>
<td><a href=\"profile.php?lookup=$data[message_from]\">$data[user_name]</a></td>
<td>$recieved</td>
</tr>\n";
		}
		echo "</table>
<center><br>
<a href=\"$PHP_SELF?delete=all\" onClick=\"return DeleteAll();\">".LAN_405."</a>
</center>\n";
	} else {
		echo "<center><br>
".LAN_410."<br><br>
</center>\n";
	}
	closetable();
	echo "<script>
function DeleteMessage() {
	return confirm(\"".LAN_411."\");
}
function DeleteAll() {
	return confirm(\"".LAN_412."\");
}
</script>\n";
} else {
	opentable(LAN_400);
	echo LAN_413."\n";
	closetable();
}

require "side_right.php";
require "footer.php";
?>