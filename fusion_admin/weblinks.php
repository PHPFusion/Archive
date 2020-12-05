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
require fusion_langdir."admin/admin_weblinks.php";

require "navigation.php";
require "userinfo.php";
echo "</td>
<td valign=\"top\" class=\"bodybg\">\n";

if ($userdata[user_mod] > "1") {
	$result = dbquery("SELECT * FROM ".$fusion_prefix."weblink_cats");
	if (dbrows($result) != 0) {
		if ($action == "delete") {
			$result = dbquery("DELETE FROM ".$fusion_prefix."weblinks WHERE weblink_id='$weblink_id'");
			opentable(LAN_260);
			echo "<center><br>
".LAN_261."<br><br>
<a href=\"weblinks.php\">".LAN_262."</a><br><br>
<a href=\"index.php\">".LAN_263."</a><br><br>
</center>\n";
			closetable();
		} else {
			if (isset($_POST['save_link'])) {
				if ($action == "edit") {
					$weblink_name = stripinput($weblink_name);
					$weblink_description = stripinput($weblink_description);
					$result = dbquery("UPDATE ".$fusion_prefix."weblinks SET weblink_name='$weblink_name', weblink_description='$weblink_description', weblink_url='$weblink_url', weblink_cat='$weblink_cat', weblink_datestamp='".time()."' WHERE weblink_id='$weblink_id'");
					unset($action, $weblink_name, $weblink_description, $weblink_url, $weblink_cat);
				} else {
					$weblink_name = stripinput($weblink_name);
					$weblink_description = stripinput($weblink_description);
					$result = dbquery("INSERT INTO ".$fusion_prefix."weblinks VALUES('', '$weblink_name', '$weblink_description', '$weblink_url', '$weblink_cat', '".time()."', '0')");
					unset($action, $weblink_name, $weblink_description, $weblink_url, $weblink_cat);
				}
			}
			if ($action == "edit") {
				$result = dbquery("SELECT * FROM ".$fusion_prefix."weblinks WHERE weblink_id='$weblink_id'");
				$data = dbarray($result);
				$weblink_name = stripslashes($data[weblink_name]);
				$weblink_description = stripslashes($data[weblink_description]);
				$weblink_url = $data[weblink_url];
				$formaction = "$PHP_SELF?action=edit&weblink_id=$data[weblink_id]";
				opentable(LAN_270);
			} else {
				$formaction = "$PHP_SELF";
				$weblink_url = "http://";
				opentable(LAN_271);
			}
			$result2 = dbquery("SELECT * FROM ".$fusion_prefix."weblink_cats ORDER BY weblink_cat_name");
			if (dbrows($result2) != 0) {
				while ($data2 = dbarray($result2)) {
					if ($action == "edit") {
						if ($data[weblink_cat] == $data2[weblink_cat_id]) { $sel = " selected"; } else { $sel = ""; }
					}
					$editlist .= "<option value=\"$data2[weblink_cat_id]\"$sel>".stripslashes($data2[weblink_cat_name])."</option>\n";
				}
			}
			echo "<table align=\"center\" width=\"400\" cellpadding=\"0\" cellspacing=\"0\" class=\"body\">
<form name=\"addcat\" method=\"post\" action=\"$formaction\">
<tr>
<td width=\"80\">".LAN_280."</td>
<td><input type=\"textbox\" name=\"weblink_name\" value=\"$weblink_name\" class=\"textbox\" style=\"width:200px;\"></td>
</tr>
<tr>
<td width=\"80\">".LAN_281."</td>
<td><input type=\"textbox\" name=\"weblink_description\" value=\"$weblink_description\" class=\"textbox\" style=\"width:250px;\"></td>
</tr>
<tr>
<td width=\"80\">".LAN_282."</td>
<td><input type=\"textbox\" name=\"weblink_url\" value=\"$weblink_url\" class=\"textbox\" style=\"width:250px;\"></td>
</tr>
<tr>
<td width=\"80\">".LAN_283."</td>
<td><select name=\"weblink_cat\" class=\"textbox\" style=\"width:150px;\">
$editlist</select></td>
</tr>
<tr>
<td align=\"center\" colspan=\"2\">
<input type=\"submit\" name=\"save_link\" value=\"".LAN_284."\" class=\"button\"></td>
</tr>
</form>
</table>\n";
			closetable();
			tablebreak();
			opentable(LAN_300);
			echo "<table align=\"center\" width=\"400\" cellpadding=\"0\" cellspacing=\"0\" class=\"body\">\n";
			$result = dbquery("SELECT * FROM ".$fusion_prefix."weblink_cats ORDER BY weblink_cat_name");
			if (dbrows($result) != 0) {
				echo "<tr>
<td class=\"altbg\">".LAN_301."</td>
<td width=\"75\" class=\"altbg\">".LAN_302."</td>
</tr>
<tr>
<td colspan=\"2\" height=\"5\"></td>
</tr>\n";
				while ($data = dbarray($result)) {
					echo "<tr>
<td colspan=\"2\" class=\"altbg\">".stripslashes($data[weblink_cat_name])."</td>
</tr>\n";
					$result2 = dbquery("SELECT * FROM ".$fusion_prefix."weblinks WHERE weblink_cat='$data[weblink_cat_id]' ORDER BY weblink_name");
					if (dbrows($result2) != 0) {
						while ($data2 = dbarray($result2)) {
							echo "<tr>
<td><a href=\"$data2[weblink_url]\" target=\"_blank\">".stripslashes($data2[weblink_name])."</a></td>
<td width=\"75\"><a href=\"$PHP_SELF?action=edit&weblink_id=$data2[weblink_id]\">".LAN_303."</a> -
<a href=\"$PHP_SELF?action=delete&weblink_id=$data2[weblink_id]\">".LAN_304."</a></td>
</tr>\n";
						}
					} else {
						echo "<tr>
<td colspan=\"2\">".LAN_305."</td>
</tr>\n";
					}
				}
				echo "</table>\n";
			} else {
				echo "<tr>
<td align=\"center\"><br>
".LAN_306."<br><br>
<a href=\"weblink_cats.php\">".LAN_307."<br><br></td>
</tr>
</table>\n";
			}
			closetable();
		}
	} else {
		opentable(LAN_300);
		echo "<center>".LAN_308."<br>
".LAN_309."<br>
<br>
<a href=\"weblink_cats.php\">".LAN_310."</a>".LAN_311."</center>\n";
		closetable();
	}
}

echo "</td>
</tr>
</table>\n";

require "../footer.php";
?>