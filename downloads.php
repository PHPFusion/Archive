<?
require "header.php";
require "subheader.php";

echo "<tr><td width=\"170\" valign=\"top\" class=\"sideborder\">\n";
require "navigation.php";
require "sidebar.php";
echo "</td><td valign=\"top\">\n";
if (empty($catid)) {
	opentable("Downloads");
	echo "Welcome to the Downloads area. $settings[sitename] does not host any files
listed here. I simply provide an easier way for you to download an item by providing
a link to the manufacturers website.<br><br>\n";
	$result = dbquery("SELECT * FROM downloads WHERE dltype='category' ORDER BY title ASC");
	$rows = dbrows($result);
	if ($rows != 0) {
		$columns = 2;
		$counter = 0;
		$tab = "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">\n<tr>";
		$result = dbquery("SELECT * FROM downloads WHERE dltype='category' ORDER BY title ASC");
		while($data = dbarray($result)) {
			$result2 = dbquery("SELECT count(dlcat) FROM downloads WHERE dlcat='$data[dlid]'");
			$num = dbresult($result2, 0);
			if($counter % $columns == 0) {
				$tab .= "</tr>\n<tr>";
			}
			$tab .= "<td align=\"center\" valign=\"top\" width=\"40%\"><a href=\"$PHP_SELF?catid=$data[dlid]\">$data[title]</a> <span class=\"small2\">($num)</span>";
			if ($data[details] != "") {
				$tab .= "\n<br><span class=\"small\">$data[details]</span>";
			}
			$tab .= "</td>";
			$counter = $counter + 1;
		}
		$tab .= "</tr>\n</table>\n";
		echo $tab;
	} else {
		echo "<br><br>No Categories have been defined\n";
	}
} else {
	$result = dbquery("SELECT * FROM downloads WHERE dltype='category' AND dlid='$catid'");
	$data = dbarray($result);
	$title = stripslashes($data[title]);
	opentable("Downloads: $title");
	$result = dbquery("SELECT * FROM downloads WHERE dltype='file' AND dlcat='$catid' ORDER BY title ASC");
	$rows = dbrows($result);
	if ($rows != 0) {
		echo "<table width=\"100%\">\n<tr class=\"content\"><td>\n";
		$i = 1;
		while ($data = dbarray($result)) {
			$dateposted = gmdate("d/m/Y", $data[posted]);
			if ($data[posted]+604800 > $servertime) {
				$new = "&nbsp;<span class=\"alt2\">New</span>";
			} else {
				$new = "";
			}
			echo "<div><a href=\"$PHP_SELF?catid=$catid&dlid=$data[dlid]\">".stripslashes($data[title])."</a> - $data[filesize]$new</div>\n";
if ($data[details] != "") {
	echo "<div>$data[details]</div>\n";
}
echo "<div class=\"small\"><font class=\"alt\">O/S:</font> $data[opsys]<br>
<font class=\"alt\">Version:</font> $data[version] | <font class=\"alt\">Posted:</font> $dateposted | <font class=\"alt\">Downloads:</font> $data[downloads]</div>
";
			if ($i != $rows) {
				echo "<br>\n";
			} else {
				echo "\n";
			}
			$i++;
		}
		echo "</td></tr>\n</table>\n";
	}
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