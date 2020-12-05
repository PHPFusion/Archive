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
require fusion_langdir."faq.php";
require "side_left.php";

if (!$cat_id) {
	opentable(LAN_400);
	$result = dbquery("SELECT * FROM ".$fusion_prefix."faq_cats ORDER BY faq_cat_name");
	$rows = dbrows($result);
	if ($rows != 0) {
		$columns = 2;
		$counter = 0;
		echo "<table width='100%' cellspacing='0' cellpadding='0' class='tbl'>\n<tr>\n";
		while($data = dbarray($result)) {
			if ($counter != 0) {
				if($counter % $columns == 0) echo "</tr>\n<tr>\n";
			}
			$result2 = dbquery("SELECT count(faq_id) FROM ".$fusion_prefix."faqs WHERE faq_cat_id='".$data['faq_cat_id']."'");
			$num = dbresult($result2, 0);
			echo "<td align='center' valign='top'><a href='$PHP_SELF?cat_id=".$data['faq_cat_id']."'>".$data['faq_cat_name']."</a> <span class='small2'>($num)</span></td>\n";
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
	$result = dbquery("SELECT * FROM ".$fusion_prefix."faq_cats WHERE faq_cat_id='$cat_id'");
	$data = dbarray($result);
	opentable(LAN_401.": ".$data['faq_cat_name']);
	$itemsperpage = 15;
	$result = dbquery("SELECT * FROM ".$fusion_prefix."faqs WHERE faq_cat_id='$cat_id'");
	$rows = dbrows($result);
	if (!$rowstart) {
		$rowstart = 0;
	}
	if ($rows != 0) {
		$result = dbquery("SELECT * FROM ".$fusion_prefix."faqs WHERE faq_cat_id='$cat_id' ORDER BY faq_id LIMIT $rowstart,$itemsperpage");
		$numrows = dbrows($result);
		$i = 1;
		while ($data = dbarray($result)) {
			echo "<b>".$data['faq_question']."</b><br>
".nl2br(stripslashes($data['faq_answer']));
			if ($i != $numrows) {
				echo "<br><br>\n";
			} else {
				echo "\n";
			}
			$i++;
		}
		closetable();
		echo "<div align='center' style='margin-top:5px;'>
".makePageNav($rowstart,$itemsperpage,$rows,3,"$PHP_SELF?cat_id=$cat_id&")."
</div>\n";
	} else {
		echo LAN_411."\n";
		closetable();
	}
}

require "side_right.php";
require "footer.php";
?>