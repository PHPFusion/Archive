<?
$result = dbquery("SELECT * FROM sitelinks ORDER BY roworder");
if (dbrows($result) != 0) {
	while($data = dbarray($result)) {
		$navlinks .= "<a href=\"$data[linkurl]\">$data[linkname]</a><br>\n";
	}
} else {
	$navlinks = "No Links defined\n";
}
opentables("Navigation");
echo $navlinks;
closetable();
opentables("Users Online");
$result = dbquery("SELECT * FROM online WHERE username='Visitor'");
$visitors = dbrows($result);
echo "<span class=\"small\">";
if ($visitors != 0) {
	echo "<div align=\"left\">$visitors Visitor(s) Online</div>\n";
} else {
	echo "<div align=\"left\">No Visitors Online</div>\n";
}
$result = dbquery("SELECT * FROM online WHERE username!='Visitor'");
$members = dbrows($result);
if ($members != 0) {
	$i = 1;
	echo "<div align=\"left\">Members Online: ";
	while($data = dbarray($result)) {
		echo "<a href=\"profile.php?lookup=$data[userid]\">$data[username]</a>";
		if ($i != $members) {
			echo ", ";
		}
		$i++;
	}
	echo "</div>\n";
} else {
	echo "<div align=\"left\">No Members Online</div>\n";
}
$total = $visitors+$members;
if ($total == 1) {
	echo "<div align=\"left\">$total User Online</div>\n";
} else {
	echo "<div align=\"left\">$total Users Online</div>\n";
}
$result = dbquery("SELECT * FROM users ORDER BY joined DESC");
$total = dbrows($result);
$data = dbarray($result);
echo "<br>Total Members: ".$total."<br>
Newest Member: <a href=\"profile.php?lookup=$data[userid]\">".$data[username]."</a></span>";
closetable();
?>
