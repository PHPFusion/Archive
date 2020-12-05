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

require "navigation.php";
require "sidebar.php";
echo "</td>
<td valign=\"top\" class=\"bodybg\">\n";

opentable(LAN_106);
$shoutsperpage = 20;
$result = dbquery("SELECT * FROM ".$fusion_prefix."shoutbox");
$rows = dbrows($result);
if (!$rowstart) {
	$rowstart = 0;
}
echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n";
if ($rows != 0) {
	$totalpages = ceil($rows / $shoutsperpage);	
	$currentpage = $rowstart / $shoutsperpage + 1;
	$result = dbquery("SELECT * FROM ".$fusion_prefix."shoutbox ORDER BY shout_datestamp DESC LIMIT $rowstart,$shoutsperpage");
	$numrows = dbrows($result);
	$i = 1;
	while ($data = dbarray($result)) {
		$postee = explode(".", $data[shout_name]);
		echo "<tr>
<td colspan=\"2\">";
		if ($postee[0] != 0) {
			echo "<a href=\"profile.php?lookup=$postee[0]\">$postee[1]</a>";
		} else {
			echo "$postee[1]";
		}
		echo "</td>
</tr>
<tr>
<td>".parsesmileys(stripslashes($data[shout_message]))."</td>
</tr>
<tr>
<td align=\"right\" class=\"small\">".strftime($settings[shortdate], $data[shout_datestamp]+($settings[timeoffset]*3600))."</td>
</tr>\n";
	}
} else {
	echo "<tr>
<td><center><br>
".LAN_107."<br><br>
</center></td>
</tr>\n";
}
echo "</table>\n";
closetable();
if ($rowstart >= $shoutsperpage) {
	$start = $rowstart - $shoutsperpage;
	$prev = "<a href=\"$PHP_SELF?rowstart=$start\" class=\"white\">".LAN_50."</a>";
}
if ($rowstart + $shoutsperpage < $rows) {
	$start = $rowstart + $shoutsperpage;
	$next = "<a href=\"$PHP_SELF?rowstart=$start\" class=\"white\">".LAN_51."</a>";
}
if ($prev != "" || $next != "") {
	$current = LAN_52.$currentpage.LAN_53.$totalpages;
	tablebreak();
	prevnextbar($prev,$current,$next);
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