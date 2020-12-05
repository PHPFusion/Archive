<?php
/*--------------------------------------------+
| PHP-Fusion 5 - Content Management System    |
|---------------------------------------------|
| author: Nick Jones (Digitanium) © 2002-2005 |
| web: http://www.php-fusion.co.uk            |
| email: nick@php-fusion.co.uk                |
|---------------------------------------------|
| Released under the terms and conditions of  |
| the GNU General Public License (Version 2)  |
+--------------------------------------------*/
@require_once "fusion_config.php";
require_once "fusion_core.php";
require_once "subheader.php";
require_once "side_left.php";
include FUSION_LANGUAGES.FUSION_LAN."downloads.php";

if (isset($download_id)) {
	$result = dbquery("UPDATE ".$fusion_prefix."downloads SET download_count=download_count+1 WHERE download_id='$download_id'");
	$result = dbquery("SELECT download_url FROM ".$fusion_prefix."downloads WHERE download_id='$download_id'");
	$data = dbarray($result);
	header("Location: ".$data['download_url']);
}
if (!isset($cat_id)) {
	opentable(LAN_400);
	$result = dbquery("SELECT * FROM ".$fusion_prefix."download_cats ORDER BY download_cat_name");
	$rows = dbrows($result);
	if ($rows != 0) {
		$columns = 2; $counter = 0;
		echo "<table width='100%' cellspacing='0' cellpadding='0' class='tbl'>\n<tr>\n";
		while ($data = dbarray($result)) {
			if ($counter != 0) if($counter % $columns == 0) echo "</tr>\n<tr>\n";
			$num = dbcount("(download_cat)", "downloads", "download_cat='".$data['download_cat_id']."'");
			echo "<td align='center' valign='top'><a href='".FUSION_SELF."?cat_id=".$data['download_cat_id']."'>".$data['download_cat_name']."</a> <span class='small2'>($num)</span>";
			if ($data['download_cat_description'] != "") echo "<br>\n<span class='small'>".$data['download_cat_description']."</span>";
			echo "</td>\n";
			$counter++;
		}
		echo "</tr>\n</table>\n";
	} else {
		echo "<center><br>\n".LAN_430."<br><br>\n</center>\n";
	}
	closetable();
} else {
	if (!isNum($cat_id)) { header("Location: ".FUSION_SELF); exit; }
	if ($data = dbarray(dbquery("SELECT * FROM ".$fusion_prefix."download_cats WHERE download_cat_id='$cat_id'"))) {
		opentable(LAN_400.": ".$data['download_cat_name']);
		$rows = dbcount("(*)", "downloads", "download_cat='$cat_id'");
		if (!isset($rowstart)) $rowstart = 0;
		if ($rows != 0) {
			$result = dbquery("SELECT * FROM ".$fusion_prefix."downloads WHERE download_cat='$cat_id' ORDER BY download_title LIMIT $rowstart,15");
			$numrows = dbrows($result);
			$i = 1;
			while ($data = dbarray($result)) {
				if ($data['download_datestamp']+604800 > time()+($settings['timeoffset']*3600)) {
					$new = " <span class='small'>".LAN_410."</span>";
				} else {
					$new = "";
				}
				echo "<a href='".FUSION_SELF."?cat_id=$cat_id&download_id=".$data['download_id']."' target='_blank'>".$data['download_title']."</a> - ".$data['download_filesize']." $new<br>\n";
				if ($data['download_description'] != "") echo $data['download_description']."<br>\n";
				echo "<span class='small'><font class='alt'>".LAN_411."</font> ".$data['download_license']." |
<font class='alt'>".LAN_412."</font> ".$data['download_os']." |
<font class='alt'>".LAN_413."</font> ".$data['download_version']."<br>
<font class='alt'>".LAN_414."</font> ".showdate("%d.%m.%y", $data['download_datestamp'])." |
<font class='alt'>".LAN_415."</font> ".$data['download_count']."</span>\n";
				if ($i != $numrows) {
					echo "<br><br>\n";
				} else {
					echo "\n";
				}
				$i++;
			}
			closetable();
			echo "<div align='center' style='margin-top:5px;'>\n".makePageNav($rowstart,15,$rows,3,FUSION_SELF."?cat_id=$cat_id&")."\n</div>\n";
		} else {
			echo LAN_431."\n";
			closetable();
		}
	} else {
		header("Location: ".FUSION_SELF); exit;
	}
}

require_once "side_right.php";
require_once "footer.php";
?>