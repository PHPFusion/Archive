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
require fusion_langdir."weblinks.php";

if (isset($weblink_id)) {
	$result = dbquery("UPDATE ".$fusion_prefix."weblinks SET weblink_count=weblink_count+1 WHERE weblink_id='$weblink_id'");
	$result = dbquery("SELECT weblink_url FROM ".$fusion_prefix."weblinks WHERE weblink_id='$weblink_id'");
	$data = dbarray($result);
	header ("Location:$data[weblink_url]");
}

require "navigation.php";
require "sidebar.php";
echo "</td>
<td valign=\"top\" class=\"bodybg\">\n";

if (empty($cat_id)) {
	opentable(LAN_200);
	$result = dbquery("SELECT * FROM ".$fusion_prefix."weblink_cats ORDER BY weblink_cat_name");
	$rows = dbrows($result);
	if ($rows != 0) {
		$columns = 2;
		$counter = 0;
		$thisrow = 1;
		echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<tr>\n";
		while($data = dbarray($result)) {
			$result2 = dbquery("SELECT count(weblink_cat) FROM ".$fusion_prefix."weblinks WHERE weblink_cat='$data[weblink_cat_id]'");
			$num = dbresult($result2, 0);
			echo "<td align=\"center\" valign=\"top\" width=\"40%\"><a href=\"$PHP_SELF?cat_id=$data[weblink_cat_id]\">$data[weblink_cat_name]</a> <span class=\"small2\">($num)</span>";
			if ($data[weblink_cat_description] != "") {
				echo "<br>
<span class=\"small\">".stripslashes($data[weblink_cat_description])."</span>";
			}
			echo "</td>\n";
			if ($rows != $thisrow) {
				if($counter % $columns) echo "</tr>\n<tr>\n";
			}
			$counter++;
			$thisrow++;
		}
		echo "</tr>
</table>\n";
	} else {
		echo "<center><br>
".LAN_230."<br><br>
</center>\n";
	}
	closetable();
} else {
	$result = dbquery("SELECT * FROM ".$fusion_prefix."weblink_cats WHERE weblink_cat_id='$cat_id'");
	$data = dbarray($result);
	$cat_name = stripslashes($data[weblink_cat_name]);
	opentable(LAN_200.": $cat_name");
	$itemsperpage = 15;
	$result = dbquery("SELECT * FROM ".$fusion_prefix."weblinks WHERE weblink_cat='$cat_id'");
	$rows = dbrows($result);
	if (!$rowstart) {
		$rowstart = 0;
	}
	if ($rows != 0) {
		$totalpages = ceil($rows / $itemsperpage);	
		$currentpage = $rowstart / $itemsperpage + 1;
		$result = dbquery("SELECT * FROM ".$fusion_prefix."weblinks WHERE weblink_cat='$cat_id' ORDER BY weblink_name LIMIT $rowstart,$itemsperpage");
		$numrows = dbrows($result);
		$i = 1;
		while ($data = dbarray($result)) {
			$dateposted = strftime("%d.%m.%y", $data[weblink_datestamp]+($settings[timeoffset]*3600));
			if ($data[weblink_datestamp]+604800 > time()+($settings[timeoffset]*3600)) {
				$new = " <span class=\"small\">".LAN_210."</span>";
			} else {
				$new = "";
			}
			echo "<a href=\"$PHP_SELF?cat_id=$cat_id&weblink_id=$data[weblink_id]\" target=\"_blank\">".stripslashes($data[weblink_name])."</a>$new<br>\n";
			if ($data[weblink_description] != "") {
				echo stripslashes($data[weblink_description])."<br>\n";
			}
			echo "<span class=\"small\"><font class=\"alt\">".LAN_211."</font> $dateposted |
<font class=\"alt\">".LAN_212."</font> $data[weblink_count]</span>\n";
			if ($i != $numrows) {
				echo "<br><br>\n";
			} else {
				echo "\n";
			}
			$i++;
		}
		closetable();
		if ($rowstart >= $itemsperpage) {
			$start = $rowstart - $itemsperpage;
			$prev = "<a href=\"$PHP_SELF?cat_id=$cat_id&rowstart=$start\" class=\"white\">".LAN_50."</a>";
		}
		if ($rowstart + $itemsperpage < $rows) {
			$start = $rowstart + $itemsperpage;
			$next = "<a href=\"$PHP_SELF?cat_id=$cat_id&rowstart=$start\" class=\"white\">".LAN_51."</a>";
		}
		if ($prev != "" || $next != "") {
			$current = LAN_52.$currentpage.LAN_53.$totalpages;
			tablebreak();
			prevnextbar($prev,$current,$next);
		}
	} else {
		echo LAN_231."\n";
		closetable();
	}
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