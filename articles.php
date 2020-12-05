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
require fusion_langdir."articles.php";
require "side_left.php";

if (empty($cat_id)) {
	opentable(LAN_400);
	$result = dbquery("SELECT * FROM ".$fusion_prefix."article_cats ORDER BY article_cat_name");
	$rows = dbrows($result);
	if ($rows != 0) {
		$columns = 2;
		$counter = 0;
		echo "<table width='100%'>\n<tr>\n";
		while($data = dbarray($result)) {
			if ($counter != 0) {
				if($counter % $columns == 0) echo "</tr>\n<tr>\n";
			}
			$result2 = dbquery("SELECT count(article_cat) FROM ".$fusion_prefix."articles WHERE article_cat='".$data['article_cat_id']."'");
			$num = dbresult($result2, 0);
			echo "<td align='center' valign='top' width='40%'><a href='$PHP_SELF?cat_id=$data[article_cat_id]'>".$data['article_cat_name']."</a> <span class='small2'>($num)</span>";
			if ($data[article_cat_description] != "") {
				echo "<br>
<span class='small'>".$data['article_cat_description']."</span>";
			}
			echo "</td>\n";
			$counter++;
		}
		echo "</tr>\n</table>\n";
	} else {
		echo "<table width='100%' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td align='center'>".LAN_401."</td>
</tr>
</table>\n";
	}
} else {
	$result = dbquery("SELECT * FROM ".$fusion_prefix."article_cats WHERE article_cat_id='$cat_id'");
	$data = dbarray($result);
	opentable(LAN_400.": ".$data['article_cat_name']);
	$result = dbquery("SELECT * FROM ".$fusion_prefix."articles WHERE article_cat='$cat_id' ORDER BY article_name");
	$rows = dbrows($result);
	if ($rows != 0) {
		echo "<table width='100%' cellspacing='0' cellpadding='0' class='tbl'>\n<tr>\n<td>";
		$i = 1;
		while ($data = dbarray($result)) {
			$dateposted = strftime("%d.%m.%y", $data['article_datestamp']+($settings['timeoffset']*3600));
			if ($data['article_datestamp']+604800 > time()+($settings['timeoffset']*3600)) {
				$new = "&nbsp;<span class='small'>[".LAN_402."]</span>";
			} else {
				$new = "";
			}
			echo "<div><a href='readarticle.php?article_id=".$data['article_id']."'>".$data['article_subject']."</a>$new</div>
<div>".stripslashes($data['article_snippet'])."</div>\n";
			if ($i != $rows) {
				echo "<br>\n";
			} else {
				echo "\n";
			}
			$i++;
		}
		echo "</td>\n</tr>\n</table>\n";
	} else {
		echo "<center>".LAN_403."</center>\n";
	}
}
closetable();

require "side_right.php";
require "footer.php";
?>