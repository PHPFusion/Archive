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
require fusion_langdir."downloads.php";
require "side_left.php";

if (isset($download_id)) {
	$result = dbquery("UPDATE ".$fusion_prefix."downloads SET download_count=download_count+1 WHERE download_id='$download_id'");
	$result = dbquery("SELECT download_url FROM ".$fusion_prefix."downloads WHERE download_id='$download_id'");
	$data = dbarray($result);
	header ("Location:$data[download_url]");
}
if (empty($cat_id)) {
	opentable(LAN_400);
	$result = dbquery("SELECT * FROM ".$fusion_prefix."download_cats ORDER BY download_cat_name");
	$rows = dbrows($result);
	if ($rows != 0) {
		$columns = 2;
		$counter = 0;
		echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<tr>\n";
		while ($data = dbarray($result)) {
			if ($counter != 0) {
				if($counter % $columns == 0) echo "</tr>\n<tr>\n";
			}
			$result2 = dbquery("SELECT count(download_cat) FROM ".$fusion_prefix."downloads WHERE download_cat='$data[download_cat_id]'");
			$num = dbresult($result2, 0);
			echo "<td align=\"center\" valign=\"top\"><a href=\"$PHP_SELF?cat_id=$data[download_cat_id]\">$data[download_cat_name]</a> <span class=\"small2\">($num)</span>";
			if ($data[download_cat_description] != "") {
				echo "<br>
<span class=\"small\">".stripslashes($data[download_cat_description])."</span>";
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
	$result = dbquery("SELECT * FROM ".$fusion_prefix."download_cats WHERE download_cat_id='$cat_id'");
	$data = dbarray($result);
	$cat_name = stripslashes($data[download_cat_name]);
	opentable(LAN_400." - $cat_name");
	$itemsperpage = 15;
	$result = dbquery("SELECT * FROM ".$fusion_prefix."downloads WHERE download_cat='$cat_id'");
	$rows = dbrows($result);
	if (!$rowstart) {
		$rowstart = 0;
	}
	if ($rows != 0) {
		$totalpages = ceil($rows / $itemsperpage);	
		$currentpage = $rowstart / $itemsperpage + 1;
		$result = dbquery("SELECT * FROM ".$fusion_prefix."downloads WHERE download_cat='$cat_id' ORDER BY download_title LIMIT $rowstart,$itemsperpage");
		$numrows = dbrows($result);
		$i = 1;
		while ($data = dbarray($result)) {
			$dateposted = strftime("%d.%m.%y", $data[download_datestamp]+($settings[timeoffset]*3600));
			if ($data[download_datestamp]+604800 > time()+($settings[timeoffset]*3600)) {
				$new = " <span class=\"small\">".LAN_410."</span>";
			} else {
				$new = "";
			}
			echo "<a href=\"$PHP_SELF?cat_id=$cat_id&download_id=$data[download_id]\" target=\"_blank\">".stripslashes($data[download_title])."</a> - $data[download_filesize]$new<br>\n";
			if ($data[download_description] != "") {
				echo stripslashes($data[download_description])."<br>\n";
			}
			echo "<span class=\"small\"><font class=\"alt\">".LAN_411."</font> $data[download_license] |
<font class=\"alt\">".LAN_412."</font> $data[download_os] |
<font class=\"alt\">".LAN_413."</font> $data[download_version]<br>
<font class=\"alt\">".LAN_414."</font> $dateposted |
<font class=\"alt\">".LAN_415."</font> $data[download_count]</span>\n";
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