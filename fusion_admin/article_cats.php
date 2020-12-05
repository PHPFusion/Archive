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
@require "../fusion_config.php";
require "../header.php";
require fusion_basedir."subheader.php";
require fusion_langdir."admin/admin_main.php";
require fusion_langdir."admin/admin_news-articles.php";
require "navigation.php";

if (!Admin()) header("Location: ../index.php");

if (SuperAdmin()) {
	if ($action == "delete") {
		$result = dbquery("SELECT * FROM ".$fusion_prefix."articles WHERE article_cat='$cat_id'");
		if (dbrows($result) != 0) {
			opentable(LAN_450);
			echo "<center><br>
".LAN_451."<br>
<span class=\"small\">".LAN_452."</span><br><br>
<a href=\"article_cats.php\">".LAN_453."</a><br><br>
<a href=\"index.php\">".LAN_403."</a><br><br>
</center>\n";
		} else {
			$result = dbquery("DELETE FROM ".$fusion_prefix."article_cats WHERE article_cat_id='$cat_id'");
			opentable(LAN_450);
			echo "<center><br>
".LAN_454."<br><br>
<a href=\"article_cats.php\">".LAN_453."</a><br><br>
<a href=\"index.php\">".LAN_403."</a><br><br>
</center>\n";
		}
		closetable();
	} else {
		if (isset($_POST['save_cat'])) {
			if ($action == "edit") {
				$cat_name = stripinput($cat_name);
				$cat_description = stripinput($cat_description);
				$result = dbquery("UPDATE ".$fusion_prefix."article_cats SET article_cat_name='$cat_name', article_cat_description='$cat_description' WHERE article_cat_id='$cat_id'");
				unset($action, $cat_name, $cat_description, $cat_id);
			} else {
				$cat_name = stripinput($cat_name);
				$cat_description = stripinput($cat_description);
				$result = dbquery("INSERT INTO ".$fusion_prefix."article_cats VALUES('', '$cat_name', '$cat_description')");
				unset($cat_name, $cat_description);
			}
		}
		if ($action == "edit") {
			$result = dbquery("SELECT * FROM ".$fusion_prefix."article_cats WHERE article_cat_id='$cat_id'");
			$data = dbarray($result);
			$cat_name = stripslashes($data[article_cat_name]);
			$cat_description = stripslashes($data[article_cat_description]);
			$formaction = "$PHP_SELF?action=edit&cat_id=$data[article_cat_id]";
			opentable(LAN_455);
		} else {
			$formaction = "$PHP_SELF";
			opentable(LAN_456);
		}
		echo "<form name=\"addcat\" method=\"post\" action=\"$formaction\">
<table align=\"center\" width=\"400\" cellpadding=\"0\" cellspacing=\"0\" class=\"body\">
<tr>
<td width=\"130\">".LAN_457."</td>
<td><input type=\"text\" name=\"cat_name\" value=\"$cat_name\" class=\"textbox\" style=\"width:200px;\"></td>
</tr>
<tr>
<td width=\"130\">".LAN_458."</td>
<td><input type=\"text\" name=\"cat_description\" value=\"$cat_description\" class=\"textbox\" style=\"width:250px;\"></td>
</tr>
<tr>
<td align=\"center\" colspan=\"2\">
<input type=\"submit\" name=\"save_cat\" value=\"".LAN_459."\" class=\"button\"></td>
</tr>
</table>
</form>\n";
		closetable();
		tablebreak();
		opentable(LAN_460);
		echo "<table align=\"center\" width=\"400\" cellpadding=\"0\" cellspacing=\"0\" class=\"body\">\n";
		$result = dbquery("SELECT * FROM ".$fusion_prefix."article_cats ORDER BY article_cat_name");
		if (dbrows($result) != 0) {
			echo "<tr>
<td class=\"altbg\">".LAN_461."</td>
<td align=\"right\" widtb=\"60\" class=\"altbg\">".LAN_462."</td>
</tr>\n";
			while ($data = dbarray($result)) {
				echo "<tr>
<td><a href=\"$PHP_SELF?action=edit&cat_id=$data[article_cat_id]\">".stripslashes($data[article_cat_name])."</a><br>
<span class=\"small\">".stripslashes($data[article_cat_description])."</span></td>
<td align=\"right\" valign=\"top\"><a href=\"$PHP_SELF?action=delete&cat_id=$data[article_cat_id]\">".LAN_463."</a></td>
</tr>\n";
			}
			echo "</table>\n";
		} else {
			echo "<tr><td align=\"center\">".LAN_464."</td></tr>
</table>\n";
		}
		closetable();
	}
}

echo "</td>\n";
require "../footer.php";
?>