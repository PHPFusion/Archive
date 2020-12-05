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
include FUSION_LANGUAGES.FUSION_LAN."faq.php";
require_once "side_left.php";

if (!isset($cat_id)) {
	opentable(LAN_400);
	$result = dbquery("SELECT * FROM ".$fusion_prefix."faq_cats ORDER BY faq_cat_name");
	$rows = dbrows($result);
	if ($rows != 0) {
		$columns = 2; $counter = 0;
		echo "<table width='100%' cellspacing='0' cellpadding='0' class='tbl'>\n<tr>\n";
		while($data = dbarray($result)) {
			if ($counter != 0) {
				if ($counter % $columns == 0) echo "</tr>\n<tr>\n";
			}
			$num = dbcount("(faq_id)", "faqs", "faq_cat_id='".$data['faq_cat_id']."'");
			echo "<td align='center' valign='top'><a href='".FUSION_SELF."?cat_id=".$data['faq_cat_id']."'>".$data['faq_cat_name']."</a> <span class='small2'>($num)</span></td>\n";
			$counter++;
		}
		echo "</tr>\n</table>\n";
	} else {
		echo "<center><br>
".LAN_410."<br><br>
</center>\n";
	}
	closetable();
} else {
	if (!isNum($cat_id)) { header("Location: ".FUSION_SELF); exit; }
	if ($data = dbarray(dbquery("SELECT * FROM ".$fusion_prefix."faq_cats WHERE faq_cat_id='$cat_id'"))) {
		opentable(LAN_401.": ".$data['faq_cat_name']);
		$rows = dbcount("(*)", "faqs", "faq_cat_id='$cat_id'");
		if (!isset($rowstart)) $rowstart = 0;
		if ($rows != 0) {
			$result = dbquery("SELECT * FROM ".$fusion_prefix."faqs WHERE faq_cat_id='$cat_id' ORDER BY faq_id LIMIT $rowstart,15");
			$numrows = dbrows($result);
			$i = 1;
			while ($data = dbarray($result)) {
				echo "<b>".$data['faq_question']."</b><br>\n".nl2br(stripslashes($data['faq_answer']));
				echo ($i != $numrows ? "<br><br>\n" : "\n");
				$i++;
			}
			closetable();
			echo "<div align='center' style='margin-top:5px;'>".makePageNav($rowstart,15,$rows,3,FUSION_SELF."?cat_id=$cat_id&")."\n</div>\n";
		} else {
			echo LAN_411."\n";
			closetable();
		}
	} else {
		header("Location: ".FUSION_SELF); exit;
	}
}

require_once "side_right.php";
require_once "footer.php";
?>