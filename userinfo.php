<?
opentables("Your Details");
if ($userdata[username] != "") {
	$result = dbquery("SELECT count(mid) FROM messages WHERE touser='$userdata[userid]' AND isread='0'");
	$num = dbresult($result, 0);
	echo "<b>$userdata[username]</b><br><br>
<a href=\"editprofile.php\">Edit Profile</a><br>
<a href=\"messages.php\">Private Messages</a> ($num)<br>
<a href=\"members.php\">Members List</a><br>\n";
	if ($userdata[mod] == "Administrator") {
		echo "<a href=\"admin/index.php\">Admin Panel</a><br>\n";
	}
echo "<a href=\"index.php?logout=yes\">Logout</a>\n";
} else {
	echo "<b>Visitor</b><br><br>
<a href=\"register.php\">Register</a><br>
<a href=\"login.php\">Login</a>\n";
}
closetable();
?>