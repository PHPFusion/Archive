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
require fusion_langdir."admin/admin_downloads.php";

require "navigation.php";
require "userinfo.php";
echo "</td>
<td valign=\"top\" class=\"bodybg\">\n";

if ($userdata[user_mod] > "1") {
	$result = dbquery("SELECT * FROM ".$fusion_prefix."download_cats");
	if (dbrows($result) != 0) {
		if ($action == "delete") {
			$result = dbquery("DELETE FROM ".$fusion_prefix."downloads WHERE download_id='$download_id'");
			opentable(LAN_260);
			echo "<center><br>
".LAN_261."<br><br>
<a href=\"downloads.php\">".LAN_262."</a><br><br>
<a href=\"index.php\">".LAN_263."</a><br><br>
</center>\n";
			closetable();
		} else {
			if (isset($_POST['save_download'])) {
				if ($action == "edit") {
					$download_title = stripinput($download_title);
					$download_description = stripinput($download_description);
					$result = dbquery("UPDATE ".$fusion_prefix."downloads SET download_title='$download_title', download_description='$download_description', download_url='$download_url', download_cat='$download_cat', download_license='$download_license', download_os='$download_sys', download_version='$download_version', download_filesize='$download_filesize', download_datestamp='".time()."' WHERE download_id='$download_id'");
					unset($action, $download_title, $download_description, $download_url, $download_cat, $download_license, $download_os, $download_version, $download_filesize);
				} else {
					$download_title = stripinput($download_title);
					$download_description = stripinput($download_description);
					$result = dbquery("INSERT INTO ".$fusion_prefix."downloads VALUES('', '$download_title', '$download_description', '$download_url', '$download_cat', '$download_license', '$download_sys', '$download_version', '$download_filesize', '".time()."', '0')");
					unset($action, $download_title, $download_description, $download_url, $download_cat, $download_license, $download_os, $download_version, $download_filesize);
				}
			}
			if ($action == "edit") {
				$result = dbquery("SELECT * FROM ".$fusion_prefix."downloads WHERE download_id='$download_id'");
				$data = dbarray($result);
				$download_title = stripslashes($data[download_title]);
				$download_description = stripslashes($data[download_description]);
				$download_url = $data[download_url];
				$download_license = $data[download_license];
				$download_os = $data[download_os];
				$download_version = $data[download_version];
				$download_filesize = $data[download_filesize];
				$formaction = "$PHP_SELF?action=edit&download_id=$data[download_id]";
				opentable(LAN_270);
			} else {
				$formaction = "$PHP_SELF";
				opentable(LAN_271);
			}
			$result2 = dbquery("SELECT * FROM ".$fusion_prefix."download_cats ORDER BY download_cat_name");
			if (dbrows($result2) != 0) {
				while ($data2 = dbarray($result2)) {
					if ($action == "edit") {
						if ($data[download_cat] == $data2[download_cat_id]) { $sel = " selected"; } else { $sel = ""; }
					}
					$editlist .= "<option value=\"$data2[download_cat_id]\"$sel>".stripslashes($data2[download_cat_name])."</option>\n";
				}
			}
			echo "<table align=\"center\" width=\"400\" cellpadding=\"0\" cellspacing=\"0\" class=\"body\">
<form name=\"addcat\" method=\"post\" action=\"$formaction\">
<tr>
<td width=\"80\">".LAN_280."</td>
<td><input type=\"textbox\" name=\"download_title\" value=\"$download_title\" class=\"textbox\" style=\"width:200px;\"></td>
</tr>
<tr>
<td width=\"80\">".LAN_281."</td>
<td><input type=\"textbox\" name=\"download_description\" value=\"$download_description\" class=\"textbox\" style=\"width:250px;\"></td>
</tr>
<tr>
<td width=\"80\">".LAN_282."</td>
<td><input type=\"textbox\" name=\"download_url\" value=\"$download_url\" class=\"textbox\" style=\"width:250px;\"></td>
</tr>
<tr>
<td width=\"80\">".LAN_283."</td>
<td><select name=\"download_cat\" class=\"textbox\" style=\"width:150px;\">
$editlist</select></td>
</tr>
<tr>
<td width=\"80\">".LAN_284."</td>
<td><input type=\"textbox\" name=\"download_license\" value=\"$download_license\" class=\"textbox\" style=\"width:150px;\"></td>
</tr>
<tr>
<td width=\"80\">".LAN_285."</td>
<td><input type=\"textbox\" name=\"download_sys\" value=\"$download_os\" class=\"textbox\" style=\"width:150px;\"></td>
</tr>
<tr>
<td width=\"80\">".LAN_286."</td>
<td><input type=\"textbox\" name=\"download_version\" value=\"$download_version\" class=\"textbox\" style=\"width:75px;\"></td>
</tr>
<tr>
<td width=\"80\">".LAN_287."</td>
<td><input type=\"textbox\" name=\"download_filesize\" value=\"$download_filesize\" class=\"textbox\" style=\"width:75px;\"></td>
</tr>
<tr>
<td align=\"center\" colspan=\"2\">
<input type=\"submit\" name=\"save_download\" value=\"".LAN_288."\" class=\"button\"></td>
</tr>
</form>
</table>\n";
			closetable();
			tablebreak();
			opentable(LAN_300);
			echo "<table align=\"center\" width=\"400\" cellpadding=\"0\" cellspacing=\"0\" class=\"body\">\n";
			$result = dbquery("SELECT * FROM ".$fusion_prefix."download_cats ORDER BY download_cat_name");
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
<td colspan=\"2\" class=\"altbg\">".stripslashes($data[download_cat_name])."</td>
</tr>\n";
					$result2 = dbquery("SELECT * FROM ".$fusion_prefix."downloads WHERE download_cat='$data[download_cat_id]' ORDER BY download_title");
					if (dbrows($result2) != 0) {
						while ($data2 = dbarray($result2)) {
							echo "<tr>
<td><a href=\"$data2[download_url]\" target=\"_blank\">".stripslashes($data2[download_title])."</a></td>
<td width=\"75\"><a href=\"$PHP_SELF?action=edit&download_id=$data2[download_id]\">".LAN_303."</a> -
<a href=\"$PHP_SELF?action=delete&download_id=$data2[download_id]\">".LAN_304."</a></td>
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
<a href=\"download_cats.php\">".LAN_307."<br><br></td>
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
<a href=\"download_cats.php\">".LAN_310."</a>".LAN_311."</center>\n";
		closetable();
	}
}

echo "</td>
</tr>
</table>\n";

require "../footer.php";
?>