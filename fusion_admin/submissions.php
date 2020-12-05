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
@require "../fusion_config.php";
require "../header.php";
require fusion_basedir."subheader.php";
require fusion_langdir."admin/admin_submissions.php";

require "navigation.php";
require "userinfo.php";
echo "</td>
<td valign=\"top\" class=\"bodybg\">\n";

if ($userdata[user_mod] > "1") {
	if ($stage == "" || $stage == "1") {
		if ($t == "l") {
			if (isset($delete)) {
				opentable(LAN_200);
				$result = dbquery("DELETE FROM ".$fusion_prefix."submitted_links WHERE sublink_id='$delete'");
				echo "<br><div align=\"center\">".LAN_201."<br><br>
<a href=\"$PHP_SELF\">".LAN_202."</a><br><br>
<a href=\"index.php\">".LAN_203."</a></div><br>\n";
				closetable();
			}
		} else if ($t == "n") {
			if (isset($delete)) {
				opentable(LAN_260);
				$result = dbquery("DELETE FROM ".$fusion_prefix."submitted_news WHERE subnews_id='$delete'");
				echo "<br><div align=\"center\">".LAN_261."<br><br>
<a href=\"$PHP_SELF\">".LAN_262."</a><br><br>
<a href=\"index.php\">".LAN_263."</a></div><br>\n";
				closetable();
			}
		} else if ($t == "a") {
			if (isset($delete)) {
				opentable(LAN_310);
				$result = dbquery("DELETE FROM ".$fusion_prefix."submitted_articles WHERE sub_article_id='$delete'");
				echo "<br><div align=\"center\">".LAN_311."<br><br>
<a href=\"$PHP_SELF\">".LAN_312."</a><br><br>
<a href=\"index.php\">".LAN_313."</a></div><br>\n";
				closetable();
			}
		} else {
			$result = dbquery("SELECT * FROM ".$fusion_prefix."submitted_links ORDER BY sublink_datestamp DESC");
			if (dbrows($result) != "0") {
				while ($data = dbarray($result)) {
					$links .= "<tr><td>$data[sublink_sitename]</td>
<td align=\"right\" width=\"100\"><span class=\"small\"><a href=\"$PHP_SELF?stage=2&t=l&sublink_id=$data[sublink_id]\">".LAN_220."</a></span> |
<span class=\"small\"><a href=\"$PHP_SELF?t=l&delete=$data[sublink_id]\">".LAN_221."</a></span></td></tr>";
				}
			} else {
				$links = "<tr><td colspan=\"2\">".LAN_222."</td></tr>";
			}
			$result = dbquery("SELECT * FROM ".$fusion_prefix."submitted_news ORDER BY subnews_datestamp DESC");
			if (dbrows($result) != "0") {
				while ($data = dbarray($result)) {
					$news .= "<tr><td>".stripslashes($data[subnews_subject])."</td>
<td align=\"right\" width=\"100\"><span class=\"small\"><a href=\"$PHP_SELF?stage=2&t=n&subnews_id=$data[subnews_id]\">".LAN_280."</a></span> |
<span class=\"small\"><a href=\"$PHP_SELF?t=n&delete=$data[subnews_id]\">".LAN_281."</a></span></td></tr>";
				}
			} else {
				$news = "<tr><td colspan=\"2\">".LAN_282."</td></tr>";
			}
			$result = dbquery("SELECT * FROM ".$fusion_prefix."submitted_articles ORDER BY sub_article_datestamp DESC");
			if (dbrows($result) != "0") {
				while ($data = dbarray($result)) {
					$articles .= "<tr><td>".stripslashes($data[sub_article_subject])."</td>
<td align=\"right\" width=\"100\"><span class=\"small\"><a href=\"$PHP_SELF?stage=2&t=a&sub_article_id=$data[sub_article_id]\">".LAN_320."</a></span> |
<span class=\"small\"><a href=\"$PHP_SELF?t=a&delete=$data[sub_article_id]\">".LAN_321."</a></span></td></tr>";
				}
			} else {
				$articles = "<tr><td colspan=\"2\">".LAN_322."</td></tr>";
			}
			opentable(LAN_204);
			echo "<table align=\"center\" width=\"400\" cellpadding=\"0\" cellspacing=\"0\" class=\"body\">
<tr><td colspan=\"2\" class=\"altbg\">".LAN_223."</td></tr>
$links
<tr><td colspan=\"2\" height=\"5\"></td></tr>
<tr><td colspan=\"2\" class=\"altbg\">".LAN_283."</td></tr>
$news
<tr><td colspan=\"2\" class=\"altbg\">".LAN_323."</td></tr>
$articles
</table>\n";
			closetable();
		}
	}
	if ($stage == "2" && $t == "l") {
		if (isset($_POST['add'])) {
			$result = dbquery("INSERT INTO ".$fusion_prefix."weblinks VALUES('', '$link_sitename', '$link_description', '$link_url', '$link_category', '".time()."', '0')");
			$result = dbquery("DELETE FROM ".$fusion_prefix."submitted_links WHERE sublink_id='$sublink_id'");
			opentable(LAN_230);
			echo "<br><div align=\"center\">".LAN_231."<br><br>
<a href=\"$PHP_SELF\">".LAN_202."</a><br><br>
<a href=\"index.php\">".LAN_203."</a></div><br>\n";
			closetable();
		} else if (isset($_POST['delete'])) {
			opentable(LAN_232);
			$result = dbquery("DELETE FROM ".$fusion_prefix."submitted_links WHERE sublink_id='$sublink_id'");
			echo "<br><div align=\"center\">".LAN_233."<br><br>
<a href=\"$PHP_SELF\">".LAN_202."</a><br><br>
<a href=\"index.php\">".LAN_203."</a></div><br>\n";
			closetable();
		} else {
			$result = dbquery("SELECT * FROM ".$fusion_prefix."weblink_cats ORDER BY weblink_cat_name");
			if (dbrows($result) != 0) {
				while($data = dbarray($result)) {
					$opts .= "<option value=\"$data[weblink_cat_id]\">$data[weblink_cat_name]</option>\n";
				}
			} else {
				$opts .= "<option value=\"0\">".LAN_234."</option>\n";
			}
			$result = dbquery("SELECT * FROM ".$fusion_prefix."submitted_links WHERE sublink_id='$sublink_id'");
			$data = dbarray($result);
			$posted = gmdate($dateformat, $data[sublink_datestamp]);
			opentable(LAN_240);
			echo "<table align=\"center\" width=\"450\" cellpadding=\"0\" cellspacing=\"0\" class=\"body\">
<tr>
<td align=\"center\">".LAN_241."<a href=\"mailto:$data[sublink_email]\">$data[sublink_name]</a>".LAN_242."$posted</td>
</tr>
<tr>
<td align=\"center\"><a href=\"$data[sublink_url]\" target=\"_blank\">$data[sublink_sitename]</a> ($data[sublink_url])</td>
</tr>
<tr>
<td align=\"center\"><span class=\"alt\">".LAN_243."</span> $data[sublink_category]</td>
</tr>
<form name=\"publish\" method=\"post\" action=\"$PHP_SELF?stage=2&t=l&sublink_id=$sublink_id\">
<tr>
<td align=\"center\"><br>
".LAN_244."<br><br>
<table>
<tr>
<td>".LAN_245."</td>
<td><input type=\"textbox\" name=\"link_sitename\" value=\"$data[sublink_sitename]\" class=\"textbox\" style=\"width:200px\"></td>
</tr>
<tr>
<td>".LAN_246."</td>
<td><input type=\"textbox\" name=\"link_url\" value=\"$data[sublink_url]\" class=\"textbox\" style=\"width:300px\"></td>
</tr>
<tr>
<td>".LAN_247."</td>
<td><input type=\"textbox\" name=\"link_description\" value=\"$data[sublink_description]\" class=\"textbox\" style=\"width:300px\"></td>
</tr>
<tr>
<td>".LAN_243."</td>
<td><select name=\"link_category\" class=\"textbox\" style=\"width:200px\">
$opts</select></td>
</tr>
</table>
<input type=\"submit\" name=\"add\" value=\"".LAN_248."\"><br><br>
".LAN_249."<br>
<input type=\"submit\" name=\"delete\" value=\"".LAN_250."\">
</td>
</tr>
</form>
</table>";
			closetable();
		}
	}
	if ($stage == "2" && $t == "n") {
		if (isset($_POST['publish'])) {
			$result = dbquery("SELECT * FROM ".$fusion_prefix."submitted_news WHERE subnews_id='$subnews_id'");
			$data = dbarray($result);
			$subject = stripinput($data[subnews_subject]);
			$news = addslashes($data[subnews_news]);
			if ($data[subnews_extended] != "") {
				$extendednews = addslashes($data[subnews_extended]);
			}
			$result = dbquery("INSERT INTO ".$fusion_prefix."news VALUES('', '$subject', '$news', '$extendednews', '$data[subnews_breaks]', '$userdata[user_name]', '$userdata[user_email]', '".time()."', '0', '0')");
			$result = dbquery("DELETE FROM ".$fusion_prefix."submitted_news WHERE subnews_id='$subnews_id'");
			opentable(LAN_290);
			echo "<br><div align=\"center\">".LAN_291."<br><br>
<a href=\"$PHP_SELF\">".LAN_202."</a><br><br>
<a href=\"index.php\">".LAN_203."</a></div><br>\n";
			closetable();
		} else if (isset($_POST['delete'])) {
			opentable(LAN_292);
			$result = dbquery("DELETE FROM ".$fusion_prefix."submitted_news WHERE subnews_id='$subnews_id'");
			echo "<br><div align=\"center\">".LAN_293."<br><br>
<a href=\"$PHP_SELF\">".LAN_202."</a><br><br>
<a href=\"index.php\">".LAN_203."</a></div><br>\n";
			closetable();
		} else {	
			$result = dbquery("SELECT * FROM ".$fusion_prefix."submitted_news WHERE subnews_id='$subnews_id'");
			$data = dbarray($result);
			$subject = stripslashes($data[subnews_subject]);
			$news = stripslashes($data[subnews_news]);
			if ($data[subnews_extended] != "") {
				$extendednews = stripslashes($data[subnews_extended]);
			}
			if ($data[subnews_breaks] == "y") {
				$news = nl2br($news);
				if ($data[subnews_extended] != "") {
					$extendednews = nl2br($extendednews);
				}
			}
			opentable($subject);
			echo $news;
			closetable();
			if ($extendednews != "") {
				tablebreak();
				opentable($subject);
				echo $extendednews;
				closetable();
			}
			tablebreak();
			opentable(LAN_300);
			echo "<form name=\"publish\" method=\"post\" action=\"$PHP_SELF?sub=submissions&stage=2&t=n&subnews_id=$subnews_id\">
<center>
".LAN_301."<br>
<input type=\"submit\" name=\"publish\" value=\"".LAN_302."\"><br><br>
".LAN_303."<br>
<input type=\"submit\" name=\"delete\" value=\"".LAN_304."\">
</center>
</form>\n";
			closetable();
		}
	}
	if ($stage == "2" && $t == "a") {
		if (isset($_POST['publish'])) {
			$result = dbquery("SELECT * FROM ".$fusion_prefix."submitted_articles WHERE sub_article_id='$sub_article_id'");
			$data = dbarray($result);
			$subject = stripinput($data[sub_article_subject]);
			$description = addslashes($data[sub_article_description]);
			$article = addslashes($data[sub_article_body]);
			$result = dbquery("INSERT INTO ".$fusion_prefix."articles VALUES('', '$data[sub_article_cat]', '$subject', '$description', '$article', '$data[sub_article_breaks]', '$userdata[user_name]', '$userdata[user_email]', '".time()."', '0', '0')");
			$result = dbquery("DELETE FROM ".$fusion_prefix."submitted_articles WHERE sub_article_id='$sub_article_id'");
			opentable(LAN_330);
			echo "<br><div align=\"center\">".LAN_331."<br><br>
<a href=\"$PHP_SELF\">".LAN_202."</a><br><br>
<a href=\"index.php\">".LAN_203."</a></div><br>\n";
			closetable();
		} else if (isset($_POST['delete'])) {
			opentable(LAN_332);
			$result = dbquery("DELETE FROM ".$fusion_prefix."submitted_articles WHERE sub_article_id='$sub_article_id'");
			echo "<br><div align=\"center\">".LAN_333."<br><br>
<a href=\"$PHP_SELF\">".LAN_202."</a><br><br>
<a href=\"index.php\">".LAN_203."</a></div><br>\n";
			closetable();
		} else {	
			$result = dbquery("SELECT * FROM ".$fusion_prefix."submitted_articles WHERE sub_article_id='$sub_article_id'");
			$data = dbarray($result);
			$subject = stripslashes($data[sub_article_subject]);
			$description = stripslashes($data[sub_article_description]);
			$article = stripslashes($data[sub_article_body]);
			if ($data[sub_article_breaks] == "y") {
				$article = nl2br($data[sub_article_body]);
			}
			opentable($subject);
			echo $description;
			closetable();
			tablebreak();
			opentable($subject);
			echo $article;
			closetable();
			tablebreak();
			opentable(LAN_340);
			echo "<form name=\"publish\" method=\"post\" action=\"$PHP_SELF?sub=submissions&stage=2&t=a&sub_article_id=$sub_article_id\">
<center>
".LAN_341."<br>
<input type=\"submit\" name=\"publish\" value=\"".LAN_342."\"><br><br>
".LAN_343."<br>
<input type=\"submit\" name=\"delete\" value=\"".LAN_344."\">
</center>
</form>\n";
			closetable();
		}
	}
}

echo "</td>
</tr>
</table>\n";

require "../footer.php";
?>