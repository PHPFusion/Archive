<?
/*
-------------------------------------------------------
	PHP Fusion X3
	-----------------------------------------------
	© Nick Jones 2002-2004
	http://www.digitaldominion.co.uk
	nick@digitaldominion.co.uk
	-----------------------------------------------
	Released under the terms and conditions of the
	GNU General Public License (http://gnu.org).
-------------------------------------------------------
*/
require "header.php";
require fusion_basedir."subheader.php";
require fusion_langdir."admin/admin_downloads.php";

require "navigation.php";
require "userinfo.php";
echo "</td>
<td valign=\"top\" class=\"bodybg\">\n";

if ($userdata[user_mod] > "2") {
	if ($action == "delete") {
		$result = dbquery("SELECT * FROM ".$fusion_prefix."downloads WHERE download_cat='$cat_id'");
		if (dbrows($result) != 0) {
			opentable(LAN_200);
			echo "<center><br>
".LAN_201."<br>
<span class=\"small\">".LAN_202."</span><br><br>
<a href=\"download_cats.php\">".LAN_203."</a><br><br>
<a href=\"index.php\">".LAN_204."</a><br><br>
</center>\n";
		} else {
			$result = dbquery("DELETE FROM ".$fusion_prefix."download_cats WHERE download_cat_id='$cat_id'");
			opentable("Delete Download Category");
			echo "<center><br>
".LAN_205."<br><br>
<a href=\"download_cats.php\">".LAN_203."</a><br><br>
<a href=\"index.php\">".LAN_204."</a><br><br>
</center>\n";
		}
		closetable();
	} else {
		if (isset($_POST['save_cat'])) {
			if ($action == "edit") {
				$cat_name = addslashes($cat_name);
				$cat_description = addslashes($cat_description);
				$result = dbquery("UPDATE ".$fusion_prefix."download_cats SET download_cat_name='$cat_name', download_cat_description='$cat_description' WHERE download_cat_id='$cat_id'");
				unset($action, $cat_name, $cat_description, $cat_id);
			} else {
				$cat_name = addslashes($cat_name);
				$cat_description = addslashes($cat_description);
				$result = dbquery("INSERT INTO ".$fusion_prefix."download_cats VALUES('', '$cat_name', '$cat_description')");
				unset($cat_name, $cat_description);
			}
		}
		if ($action == "edit") {
			$result = dbquery("SELECT * FROM ".$fusion_prefix."download_cats WHERE download_cat_id='$cat_id'");
			$data = dbarray($result);
			$cat_name = stripslashes($data[download_cat_name]);
			$cat_description = stripslashes($data[download_cat_description]);
			$formaction = "$PHP_SELF?action=edit&cat_id=$data[download_cat_id]";
			opentable(LAN_220);
		} else {
			$formaction = "$PHP_SELF";
			opentable(LAN_221);
		}
		echo "<table align=\"center\" width=\"400\" cellpadding=\"0\" cellspacing=\"0\" class=\"body\">
<form name=\"addcat\" method=\"post\" action=\"$formaction\">
<tr>
<td width=\"130\">".LAN_230."</td>
<td><input type=\"textbox\" name=\"cat_name\" value=\"$cat_name\" class=\"textbox\" style=\"width:200px;\"></td>
</tr>
<tr>
<td width=\"130\">".LAN_231."</td>
<td><input type=\"textbox\" name=\"cat_description\" value=\"$cat_description\" class=\"textbox\" style=\"width:250px;\"></td>
</tr>
<tr>
<td align=\"center\" colspan=\"2\">
<input type=\"submit\" name=\"save_cat\" value=\"".LAN_232."\" class=\"button\" style=\"width:100px;\"></td>
</tr>
</form>
</table>\n";
		closetable();
		tablebreak();
		opentable(LAN_240);
		echo "<table align=\"center\" width=\"400\" cellpadding=\"0\" cellspacing=\"0\" class=\"body\">\n";
		$result = dbquery("SELECT * FROM ".$fusion_prefix."download_cats ORDER BY download_cat_name");
		if (dbrows($result) != 0) {
			echo "<tr>
<td class=\"altbg\">".LAN_241."</td>
<td align=\"right\" widtb=\"60\" class=\"altbg\">".LAN_242."</td>
</tr>\n";
			while ($data = dbarray($result)) {
				echo "<tr>
<td><a href=\"$PHP_SELF?action=edit&cat_id=$data[download_cat_id]\">".stripslashes($data[download_cat_name])."</a><br>
<span class=\"small\">".stripslashes($data[download_cat_description])."</span></td>
<td align=\"right\" valign=\"top\"><a href=\"$PHP_SELF?action=delete&cat_id=$data[download_cat_id]\">".LAN_243."</a></td>
</tr>\n";
			}
			echo "</table>\n";
		} else {
			echo "<tr><td align=\"center\">".LAN_244."</td></tr>
</table>\n";
		}
		closetable();
	}
}

echo "</td>
</tr>
</table>\n";

require "../footer.php";
?>