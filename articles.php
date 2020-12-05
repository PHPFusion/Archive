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
include FUSION_LANGUAGES.FUSION_LAN."articles.php";
include "side_left.php";

if (!$cat_id) {
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
			$num = dbcount("(article_cat)", "articles", "article_cat='".$data['article_cat_id']."'");
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
		echo "<center><br>\n".LAN_401."<br><br>\n</center>\n";
	}
} else {
	if (!isNum($cat_id)) { header("Location:".$PHP_SELF); exit; }
	if ($data = dbarray(dbquery("SELECT article_cat_id,article_cat_name FROM ".$fusion_prefix."article_cats WHERE article_cat_id='$cat_id'"))) {
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
	} else {
		header("Location:".$PHP_SELF); exit;
	}
}
closetable();

include "side_right.php";
include FUSION_INFUSIONS."eXtreme_message/eXtreme_message_01.php";
include "footer.php";
?>
