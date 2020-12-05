<?
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
@include "fusion_config.php";
include "fusion_core.php";
include "subheader.php";
include "side_left.php";
include FUSION_LANGUAGES.FUSION_LAN."weblinks.php";

if (isset($weblink_id)) {
	$result = dbquery("UPDATE ".$fusion_prefix."weblinks SET weblink_count=weblink_count+1 WHERE weblink_id='$weblink_id'");
	$result = dbquery("SELECT weblink_url FROM ".$fusion_prefix."weblinks WHERE weblink_id='$weblink_id'");
	$data = dbarray($result);
	header ("Location:".$data['weblink_url']);
}
if (!isset($cat_id)) {
	opentable(LAN_400);
	$result = dbquery("SELECT * FROM ".$fusion_prefix."weblink_cats ORDER BY weblink_cat_name");
	$rows = dbrows($result);
	if ($rows != 0) {
		$columns = 2; $counter = 0;
		echo "<table width='100%' cellspacing='0' cellpadding='0' class='tbl'>\n<tr>\n";
		while($data = dbarray($result)) {
			if ($counter != 0) if ($counter % $columns == 0) echo "</tr>\n<tr>\n";
			$num = dbcount("(weblink_cat)", "weblinks", "weblink_cat='".$data['weblink_cat_id']."'");
			echo "<td align='center' valign='top'><a href='$PHP_SELF?cat_id=".$data['weblink_cat_id']."'>".$data['weblink_cat_name']."</a> <span class='small2'>($num)</span>";
			if ($data['weblink_cat_description'] != "") echo "<br>\n<span class='small'>".$data['weblink_cat_description']."</span>";
			echo "</td>\n";
			$counter++;
		}
		echo "</tr>\n</table>\n";
	} else {
		echo "<center><br>\n".LAN_430."<br><br>\n</center>\n";
	}
	closetable();
} else {
	if (!isNum($cat_id)) { header("Location:".$PHP_SELF); exit; }
	if ($data = dbarray(dbquery("SELECT * FROM ".$fusion_prefix."weblink_cats WHERE weblink_cat_id='$cat_id'"))) {
		opentable(LAN_400.": ".$data['weblink_cat_name']);
		$rows = dbcount("(*)", "weblinks", "weblink_cat='$cat_id'");
		if (!$rowstart) $rowstart = 0;
		if ($rows != 0) {
			$result = dbquery("SELECT * FROM ".$fusion_prefix."weblinks WHERE weblink_cat='$cat_id' ORDER BY weblink_name LIMIT $rowstart,15");
			$numrows = dbrows($result); $i = 1;
			while ($data = dbarray($result)) {
				if ($data['weblink_datestamp']+604800 > time()+($settings['timeoffset']*3600)) {
					$new = " <span class='small'>".LAN_410."</span>";
				} else {
					$new = "";
				}
				echo "<a href='$PHP_SELF?cat_id=$cat_id&weblink_id=".$data['weblink_id']."' target='_blank'>".$data['weblink_name']."</a>$new<br>\n";
				if ($data['weblink_description'] != "") echo $data['weblink_description']."<br>\n";
				echo "<span class='small'><font class='alt'>".LAN_411."</font> ".showdate("%d.%m.%y", $data['weblink_datestamp'])." |
<span class='alt'>".LAN_412."</span> ".$data['weblink_count']."</span>\n";
				echo ($i != $numrows ? "<br><br>\n" : "\n"); $i++;
			}
			closetable();
			echo "<div align='center' style='margin-top:5px;'>\n".makePageNav($rowstart,15,$rows,3,"$PHP_SELF?cat_id=$cat_id&")."\n</div>\n";
		} else {
			echo LAN_431."\n";
			closetable();
		}
	} else {
		header("Location:".$PHP_SELF); exit;
	}
}

include "side_right.php";
include FUSION_INFUSIONS."eXtreme_message/eXtreme_message_01.php";
include "footer.php";
?>
