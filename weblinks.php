<?
require "header.php";
require "subheader.php";

echo "<tr><td width=\"170\" valign=\"top\" class=\"sideborder\">\n";
require "navigation.php";
require "sidebar.php";
echo "</td><td valign=\"top\">\n";
opentable("Web Links");
echo "Here at are links to some of the best and most informative websites on the internet. The numbers
in brackets show how many people have visited these sites. If you would like to see your website, or any
other particularly good website listed here, please fill in the <a href=\"submitlink\">Submit Link</a> form.<br><br>\n";
$result = dbquery("SELECT * FROM weblinks WHERE linktype='category' ORDER BY linkorder");
$rows = dbrows($result);
if ($rows != 0) {
	$columns = 3;
	$counter = 0;
	$tab = "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">\n<tr>";
	$result = dbquery("SELECT * FROM weblinks WHERE linktype='category' ORDER BY linkorder");
	while($data = dbarray($result)) {
		if($counter % $columns == 0) {
			$tab .= "</tr>\n<tr>";
		}
		$tab .= "<td valign=\"top\" width=\"33%\"><div align=\"left\" class=\"small\">$data[linkname]</div>";
		$result2 = dbquery("SELECT * FROM weblinks WHERE parentlink='$data[wlid]' AND linktype='weblink' ORDER BY linkorder");
		if (dbrows($result2) != 0) {
			$i = 1;
			$tab .= "<div align=\"left\">";
			while ($data2 = dbarray($result2)) {
				$tab .= "<a href=\"$_SELF?wlid=$data2[wlid]\" target=\"_blank\">$data2[linkname]</a> <span class=\"small2\">($data2[referals])</span>";
				if ($i != dbrows($result2)) {
					$tab .= "<br>\n";
				} else {
					$tab .= "\n</div>\n";
				}
				$i++;
			}
		} else {
			echo "No Web Links defined\n";
		}
		$tab .= "</td>";
		$counter = $counter + 1;
	}
	$tab .= "</tr>\n</table>\n";
	echo $tab;
} else {
	echo "<br><br>No Categories have been defined\n";
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