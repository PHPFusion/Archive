<?
$result = dbquery("SELECT * FROM sitelinks ORDER BY roworder");
if (dbrows($result) != 0) {
	while($data = dbarray($result)) {
		$navlinks .= "<a href=\"../$data[linkurl]\">$data[linkname]</a><br>\n";
	}
} else {
	$navlinks = "No Links defined\n";
}
opentables("Navigation");
echo $navlinks;
closetable();
opentables("Your Details");
if ($userdata[username] != "") {
	$result = dbquery("SELECT count(mid) FROM messages WHERE touser='$userdata[userid]' AND isread='0'");
	$num = dbresult($result, 0);
	echo "<b>$userdata[username]</b><br><br>
<a href=\"../editprofile.php\">Edit Profile</a><br>
<a href=\"../messages.php\">Private Messages</a> ($num)<br>
<a href=\"../members.php\">Members List</a><br>\n";
	if ($userdata[mod] == "Administrator") {
		echo "<a href=\"../admin/index.php\">Admin Panel</a><br>\n";
	}
echo "<a href=\"../index.php?logout=yes\">Logout</a>\n";
} else {
	echo "<b>Visitor</b><br><br>
<a href=\"../register.php\">Register</a><br>
<a href=\"../login.php\">Login</a>\n";
}
closetable();
opentables("Users Online");
$result = dbquery("SELECT * FROM online WHERE username='Visitor'");
$visitors = dbrows($result);
echo "<span class=\"small\">";
if ($visitors != 0) {
	echo "<div align=\"left\" class=\"small\">$visitors Visitor(s) Online</div>\n";
} else {
	echo "<div align=\"left\" class=\"small\">No Visitors Online</div>\n";
}
$result = dbquery("SELECT * FROM online WHERE username!='Visitor'");
$members = dbrows($result);
if ($members != 0) {
	$i = 1;
	echo "<div align=\"left\" class=\"small\">Members Online: ";
	while($data = dbarray($result)) {
		echo "<a href=\"../profile.php?lookup=$data[userid]\">$data[username]</a>";
		if ($i != $members) {
			echo ", ";
		}
		$i++;
	}
	echo "</div>\n";
} else {
	echo "<div align=\"left\" class=\"smalltext\">No Members Online</div>\n";
}
$total = $visitors+$members;
if ($total == 1) {
	echo "<div align=\"left\" class=\"small\">$total User Online</div>\n";
} else {
	echo "<div align=\"left\" class=\"small\">$total Users Online</div>\n";
}
$result = dbquery("SELECT * FROM users ORDER BY joined DESC");
$total = dbrows($result);
$data = dbarray($result);
echo "<br>Total Members: ".$total."<br>
Newest Member: <a href=\"../profile.php?lookup=$data[userid]\">".$data[username]."</a></span>";
closetable();
tablebar();
?>
