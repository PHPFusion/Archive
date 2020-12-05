<?
/*
-------------------------------------------------------
	PHP-Fusion
	-----------------------------------------------
	© Nick Jones 2002-2004
	http://www.digitaldominion.co.uk
	nick@digitaldominion.co.uk
	-----------------------------------------------
	Released under the terms and conditions of the
	GNU General Public License (http://gnu.org).
-------------------------------------------------------
*/
@require "fusion_config.php";
require "header.php";
require "subheader.php";
require fusion_langdir."members-profile.php";

require "navigation.php";
require "sidebar.php";
echo "</td>
<td valign=\"top\" class=\"bodybg\">\n";

opentable(LAN_200);
if ($userdata[user_name] != "") {
	$itemsperpage = 20;
	$result = dbquery("SELECT * FROM ".$fusion_prefix."users");
	$rows = dbrows($result);
	if (!$rowstart) {
		$rowstart = 0;
	}
	if ($rows != 0) {
		echo "<table align=\"center\" valign=\"top\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<tr>
<td class=\"altbg\"><span>".LAN_201."</td>
<td align=\"right\" class=\"altbg\">".LAN_202."</td>
</tr>\n";
		$totalpages = ceil($rows / $itemsperpage);	
		$currentpage = $rowstart / $itemsperpage + 1;
		$result = dbquery("SELECT * FROM ".$fusion_prefix."users ORDER BY user_name LIMIT $rowstart,$itemsperpage");
		while ($data = dbarray($result)) {
			if ($data[user_id] != $userdata[user_id]) {
				echo "<tr>
<td><a href=\"profile.php?lookup=$data[user_id]\">$data[user_name]</a></td>\n";
			} else {
				echo "<tr>
<td>$data[user_name]</td>\n";
			}
			echo "<td align=\"right\">".getmodlevel($data[user_mod])."</td>
</tr>";
		}
		echo "</table>\n";
		if ($rowstart >= $itemsperpage) {
			$start = $rowstart - $itemsperpage;
			$prev = "<a href=\"$PHP_SELF?rowstart=$start\" class=\"white\">".LAN_50."</a>";
		}
		if ($rowstart + $itemsperpage < $rows) {
			$start = $rowstart + $itemsperpage;
			$next = "<a href=\"$PHP_SELF?rowstart=$start\" class=\"white\">".LAN_51."</a>";
		}
		if ($prev != "" || $next != "") {
			$current = LAN_52.$currentpage.LAN_53.$totalpages;
			tablebreak();
			prevnextbar($prev,$current,$next);
		}
	}
} else {
	echo "Members Only\n";
}
closetable();

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