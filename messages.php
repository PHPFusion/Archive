<?
require "header.php";
require "subheader.php";

echo "<tr><td width=\"170\" valign=\"top\" class=\"sideborder\">\n";
require "navigation.php";
require "sidebar.php";
echo "</td><td valign=\"top\">\n";
if ($userdata[username] != "") {
	if (isset($delete)) {
		opentable("Delete Private Messages");
		$result = dbquery("DELETE FROM messages WHERE touser='$userdata[userid]' AND mid='$delete'");
		echo "<br><div align=\"center\">The Private Message has been deleted<br><br>
<a href=\"messages.php\">Return to Private Messages Inbox</a><br><br>
<a href=\"index.php\">Return to $settings[sitename] Home</a></div><br>\n";
		closetable();
	} else {
		opentable("Private Messages Inbox");
		$result = dbquery("SELECT * FROM messages WHERE touser='$userdata[userid]' ORDER BY posted DESC");
		if (dbrows($result) != 0) {
			$inbox .= "<tr><td width=\"50\"></td><td><span class=\"alt2\">Subject</span></td><td width=\"150\"><span class=\"alt2\">From</span></td><td width=\"120\"><span class=\"alt2\">Date Recieved</span></td></tr>\n";
			while ($data = dbarray($result)) {
				$result2 = dbquery("SELECT * FROM users WHERE userid='$data[fromuser]'");
				$data2 = dbarray($result2);
				$recieved = "<span class=\"alt2\">".gmdate("d.m.Y", $data[posted])."</span> at <span class=\"alt2\">".gmdate("H:i", $data[posted])."</span>";
				if ($data[isread] == 1) {
					$inbox .= "<tr><td class=\"small\"><a href=\"$PHP_SELF?delete=$data[mid]\">Delete</a></td>\n";
				}else {
					$inbox .= "<tr><td class=\"small\">Delete</td>\n";
				}
				$inbox .= "<td><a href=\"readmessage.php?mid=$data[mid]\">";
				if ($data[isread] != 1) {
					$inbox .= "<b>".stripslashes($data[subject])."</b>";
				} else {
					$inbox .= stripslashes($data[subject]);
				}
				$inbox .= "</a></td>
<td><a href=\"profile.php?lookup=$data2[userid]\">$data2[username]</a></td><td>$recieved</td></tr>\n";
			}
		} else {
			$inbox .= "<tr><td>Your Inbox is empty.</td></tr>\n";
		}
		echo "<table align=\"center\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
	$inbox
	</table>\n";
		closetable();
	}
} else {
	opentable("Private Messages Inbox");
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