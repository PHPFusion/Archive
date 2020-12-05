<?
require "header.php";
require "subheader.php";

if ($userdata[username] != "") {
	$result = dbquery("UPDATE users SET lastvisit='$servertime' WHERE userid='$userdata[userid]'");
}
if (empty($lastvisited)) { $lastvisited = $servertime; }

$result = dbquery("SELECT * FROM forums WHERE forumtype='category' ORDER BY forumorder");
if (dbrows($result) != 0) {
	while ($data = dbarray($result)) {
		$table .= "<tr><td colspan=\"5\" class=\"forumc1\">$data[forumname]</td></tr>\n";
		$result2 = dbquery("SELECT * FROM forums WHERE forumtype='forum' and fup='$data[fid]' ORDER BY forumorder");
		if (dbrows($result2) != 0) {
			while ($data2 = dbarray($result2)) {
				$result3 = dbquery("SELECT count(fid) FROM posts WHERE fid='$data2[fid]' and posted > '$lastvisited'");
        			$num = dbresult($result3, 0);
        			if ($num > 0) { $folder = "<img src=\"images/foldernew.gif\">";
        			} else { $folder = "<img src=\"images/folder.gif\">"; }
				$table .= "<tr><td align=\"center\" width=\"25\" class=\"forumf1\">$folder</td><td class=\"forumf2\">
<a href=\"viewforum.php?fid=$data2[fid]&fup=$data2[fup]\">$data2[forumname]</a><br>
<span class=\"small\">$data2[forumdetails]</span></td>
<td align=\"center\" width=\"50\" class=\"forumf2\">$data2[threads]</td>
<td align=\"center\" width=\"50\" class=\"forumf2\">$data2[posts]</td>
<td width=\"150\" class=\"forumf2\">";
if ($data2[lastpost] == 0) {
	$table .= "No Posts</td></tr>\n";
} else {
	$lastpost = gmdate("m.d.Y", $data2[lastpost])." at ".gmdate("H:i", $data2[lastpost]);
	$result3 = dbquery("SELECT * FROM users WHERE userid='$data2[lastuser]'");
	if (dbrows($result3) != 0) {
		$data3 = dbarray($result3);
		$table .= "$lastpost<br>
<span class=\"small\">by <a href=\"../profile.php?lookup=$data3[userid]\">$data3[username]</a></span></td></tr>\n";
	}
}
			}
		}
	}
}
echo "<tr><td width=\"170\" valign=\"top\" class=\"sideborder\">\n";
require "navigation.php";
echo "</td><td valign=\"top\">\n";
opentable("Discussion Forum");
echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr><td colspan=\"2\" class=\"forumh1\">Forum</td>
<td align=\"center\" width=\"50\" class=\"forumh2\">Threads</td>
<td align=\"center\" width=\"50\" class=\"forumh2\">Posts</td>
<td width=\"150\" class=\"forumh2\">Last Post</td></tr>
$table
</table>
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr><td class=\"forum\"><br>
<img src=\"images/foldernew.gif\" align=\"left\"> - Forum with new posts since last visit<br>
<img src=\"images/folder.gif\" align=\"left\"> - Forum with no new posts since last visit</td></tr>
</table>\n";
closetable();
echo "</td></tr>
</table>\n";

require "../footer.php";
?>