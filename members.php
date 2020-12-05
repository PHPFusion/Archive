<?
require "header.php";
require "subheaderjs.php";

echo "<tr><td width=\"170\" valign=\"top\" class=\"sideborder\">\n";
require "navigation.php";
require "sidebar.php";
echo "</td><td valign=\"top\">\n";
opentable("Member List");
if ($userdata[username] != "") {
	$result = dbquery("SELECT * FROM users");
	while ($data = dbarray($result)) {
		if ($data[userid] != $userdata[userid]) {
			$memberslist .= "<tr><td><a href=\"profile.php?lookup=$data[userid]\">$data[username]</a></td>\n";
		} else {
			$memberslist .= "<tr><td>$data[username]</td>\n";
		}
		$memberslist .= "<td>$data[mod]</td></tr>";
	}
	echo "<table align=\"center\" width=\"450\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<tr><td colspan=\"2\">Click on a user to see more information</td><tr>
<tr><td><span class=\"alt\">Username</span></td><td width=\"130\"><span class=\"alt\">Moderator Level</span></td></tr>
$memberslist
</table>\n";
} else {
	echo "Members Only\n";
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