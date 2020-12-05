<?
/*
---------------------------------------------------
	PHP-Fusion - Content Management System
	-------------------------------------------
	© Nick Jones (Digitanium) 2002-2004
	http://www.php-fusion.co.uk
	nick@php-fusion.co.uk
	-------------------------------------------
	Released under the terms and conditions of
	the GNU General Public License (Version 2)
---------------------------------------------------
*/
@require "fusion_config.php";
require "header.php";
require "subheader.php";
require fusion_langdir."weblinks.php";
require "side_left.php";

if (isset($weblink_id)) {
	$result = dbquery("UPDATE ".$fusion_prefix."weblinks SET weblink_count=weblink_count+1 WHERE weblink_id='$weblink_id'");
	$result = dbquery("SELECT weblink_url FROM ".$fusion_prefix."weblinks WHERE weblink_id='$weblink_id'");
	$data = dbarray($result);
	header ("Location:$data[weblink_url]");
}
if (empty($cat_id)) {
	opentable(LAN_400);
	$result = dbquery("SELECT * FROM ".$fusion_prefix."weblink_cats ORDER BY weblink_cat_name");
	$rows = dbrows($result);
	if ($rows != 0) {
		$columns = 2;
		$counter = 0;
		echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<tr>\n";
		while($data = dbarray($result)) {
			if ($counter != 0) {
				if($counter % $columns == 0) echo "</tr>\n<tr>\n";
			}
			$result2 = dbquery("SELECT count(weblink_cat) FROM ".$fusion_prefix."weblinks WHERE weblink_cat='$data[weblink_cat_id]'");
			$num = dbresult($result2, 0);
			echo "<td align=\"center\" valign=\"top\"><a href=\"$PHP_SELF?cat_id=$data[weblink_cat_id]\">$data[weblink_cat_name]</a> <span class=\"small2\">($num)</span>";
			if ($data[weblink_cat_description] != "") {
				echo "<br>
<span class=\"small\">".stripslashes($data[weblink_cat_description])."</span>";
			}
			echo "</td>\n";
			$counter++;
		}
		echo "</tr>
</table>\n";
	} else {
		echo "<center><br>
".LAN_430."<br><br>
</center>\n";
	}
	closetable();
} else {
	$result = dbquery("SELECT * FROM ".$fusion_prefix."weblink_cats WHERE weblink_cat_id='$cat_id'");
	$data = dbarray($result);
	$cat_name = stripslashes($data[weblink_cat_name]);
	opentable(LAN_400.": $cat_name");
	$itemsperpage = 15;
	$result = dbquery("SELECT * FROM ".$fusion_prefix."weblinks WHERE weblink_cat='$cat_id'");
	$rows = dbrows($result);
	if (!$rowstart) {
		$rowstart = 0;
	}
	if ($rows != 0) {
		$result = dbquery("SELECT * FROM ".$fusion_prefix."weblinks WHERE weblink_cat='$cat_id' ORDER BY weblink_name LIMIT $rowstart,$itemsperpage");
		$numrows = dbrows($result);
		$i = 1;
		while ($data = dbarray($result)) {
			$dateposted = strftime("%d.%m.%y", $data[weblink_datestamp]+($settings[timeoffset]*3600));
			if ($data[weblink_datestamp]+604800 > time()+($settings[timeoffset]*3600)) {
				$new = " <span class=\"small\">".LAN_410."</span>";
			} else {
				$new = "";
			}
			echo "<a href=\"$PHP_SELF?cat_id=$cat_id&weblink_id=$data[weblink_id]\" target=\"_blank\">".stripslashes($data[weblink_name])."</a>$new<br>\n";
			if ($data[weblink_description] != "") {
				echo stripslashes($data[weblink_description])."<br>\n";
			}
			echo "<span class=\"small\"><font class=\"alt\">".LAN_411."</font> $dateposted |
<font class=\"alt\">".LAN_412."</font> $data[weblink_count]</span>\n";
			if ($i != $numrows) {
				echo "<br><br>\n";
			} else {
				echo "\n";
			}
			$i++;
		}
		closetable();
		echo "<div align='center' style='margin-top:5px;'>
".makePageNav($rowstart,$itemsperpage,$rows,3,"small","$PHP_SELF?cat_id=$cat_id&")."
</div>\n";
	} else {
		echo LAN_431."\n";
		closetable();
	}
}

require "side_right.php";
require "footer.php";
?>