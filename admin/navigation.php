<?
opentables("Navigation");
if ($userdata[username] == "") {
	echo "<form name=\"login\" method=\"post\" action=\"index.php\">
Please enter your Username and Password:<br><br>
Username<br>
<input type=\"textbox\" name=\"username\" class=\"textbox\" maxlength=\"32\" style=\"width: 100%;\"><br>
Password:<br>
<input type=\"password\" name=\"password\" class=\"textbox\" maxlength=\"16\" style=\"width: 100%;\"><br>
<input type=\"submit\" name=\"login\" value=\"Login\" class=\"button\">
</form>\n";
} else {
	echo "<a href=\"index.php\">Admin Home</a><br>
<hr>
<a href=\"cp.php?sub=settings\">Site Settings</a><br>
<a href=\"cp.php?sub=sitelinks\">Edit Site Links</a><br>
<a href=\"cp.php?sub=weblinks\">Edit Web Links</a><br>
<a href=\"cp.php?sub=downloads\">Downloads</a><br>
<a href=\"cp.php?sub=forumadmin\">Forum Admin</a><br>
<a href=\"cp.php?sub=shoutboxadmin\">Shoutbox Admin</a><br>
<a href=\"cp.php?sub=submissions\">Submissions</a>
<hr>
<a href=\"cp.php?sub=addnews\">Add News</a><br>
<a href=\"cp.php?sub=addarticle\">Add Article</a><br>
<a href=\"cp.php?sub=addpoll\">Add Poll</a><br>
<hr>
<a href=\"cp.php?sub=editnews\">Edit News</a><br>
<a href=\"cp.php?sub=editarticle\">Edit Article</a><br>
<a href=\"cp.php?sub=editpoll\">Edit Poll</a><br>
<hr>
<a href=\"cp.php?sub=upgrade\">Upgrade</a><br>
<a href=\"index.php?logout=yes\">Logout</a><br>
<hr>
<a href=\"../index.php\">Return to Site</a>";
}
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
$result = dbquery("SELECT * FROM online WHERE username='visitor'");
$visitors = dbrows($result);
echo "<span class=\"small\">";
if ($visitors != 0) {
	echo "<div align=\"left\" class=\"small\">$visitors Visitor(s) Online</div>\n";
} else {
	echo "<div align=\"left\" class=\"small\">No Visitors Online</div>\n";
}
$result = dbquery("SELECT * FROM online WHERE username!='visitor'");
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
	echo "<div align=\"left\" class=\"small\">No Members Online</div>\n";
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
