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
require fusion_langdir."admin/admin_downloads.php";
require "navigation.php";

if (!Admin()) header("Location: ../index.php");

if (Admin()) {
	$result = dbquery("SELECT * FROM ".$fusion_prefix."download_cats");
	if (dbrows($result) != 0) {
		if ($step == "delete") {
			$result = dbquery("DELETE FROM ".$fusion_prefix."downloads WHERE download_id='$download_id'");
			header("Location: downloads.php?download_cat_id=$download_cat_id");
		}
		if (isset($_POST['save_download'])) {
			$download_title = stripinput($download_title);
			$download_description = stripinput($download_description);
			if ($step == "edit") {
				$result = dbquery("UPDATE ".$fusion_prefix."downloads SET download_title='$download_title', download_description='$download_description', download_url='$download_url', download_cat='$download_cat', download_license='$download_license', download_os='$download_sys', download_version='$download_version', download_filesize='$download_filesize', download_datestamp='".time()."' WHERE download_id='$download_id'");
				header("Location: downloads.php?download_cat_id=$download_cat");
			} else {
				$result = dbquery("INSERT INTO ".$fusion_prefix."downloads VALUES('', '$download_title', '$download_description', '$download_url', '$download_cat', '$download_license', '$download_sys', '$download_version', '$download_filesize', '".time()."', '0')");
				header("Location: downloads.php?download_cat_id=$download_cat");
			}
		}
		if ($step == "edit") {
			$result = dbquery("SELECT * FROM ".$fusion_prefix."downloads WHERE download_id='$download_id'");
			$data = dbarray($result);
			$download_title = $data[download_title];
			$download_description = $data[download_description];
			$download_url = $data[download_url];
			$download_license = $data[download_license];
			$download_os = $data[download_os];
			$download_version = $data[download_version];
			$download_filesize = $data[download_filesize];
			$formaction = "$PHP_SELF?step=edit&download_cat_id=$download_cat_id&download_id=$download_id";
			opentable(LAN_470);
		} else {
			$formaction = "$PHP_SELF";
			opentable(LAN_471);
		}
		$result2 = dbquery("SELECT * FROM ".$fusion_prefix."download_cats ORDER BY download_cat_name");
		if (dbrows($result2) != 0) {
			while ($data2 = dbarray($result2)) {
				if ($step == "edit") {
					if ($data[download_cat] == $data2[download_cat_id]) { $sel = " selected"; } else { $sel = ""; }
				}
				$editlist .= "<option value=\"$data2[download_cat_id]\"$sel>$data2[download_cat_name]</option>\n";
			}
		}
		echo "<form name=\"addcat\" method=\"post\" action=\"$formaction\">
<table align=\"center\" width=\"400\" cellpadding=\"0\" cellspacing=\"0\" class=\"body\">
<tr>
<td width=\"80\">".LAN_480."</td>
<td><input type=\"text\" name=\"download_title\" value=\"$download_title\" class=\"textbox\" style=\"width:200px;\"></td>
</tr>
<tr>
<td width=\"80\">".LAN_481."</td>
<td><input type=\"text\" name=\"download_description\" value=\"$download_description\" class=\"textbox\" style=\"width:250px;\"></td>
</tr>
<tr>
<td width=\"80\">".LAN_482."</td>
<td><input type=\"text\" name=\"download_url\" value=\"$download_url\" class=\"textbox\" style=\"width:250px;\"></td>
</tr>
<tr>
<td width=\"80\">".LAN_483."</td>
<td><select name=\"download_cat\" class=\"textbox\" style=\"width:150px;\">
$editlist</select></td>
</tr>
<tr>
<td width=\"80\">".LAN_484."</td>
<td><input type=\"text\" name=\"download_license\" value=\"$download_license\" class=\"textbox\" style=\"width:150px;\"></td>
</tr>
<tr>
<td width=\"80\">".LAN_485."</td>
<td><input type=\"text\" name=\"download_sys\" value=\"$download_os\" class=\"textbox\" style=\"width:150px;\"></td>
</tr>
<tr>
<td width=\"80\">".LAN_486."</td>
<td><input type=\"text\" name=\"download_version\" value=\"$download_version\" class=\"textbox\" style=\"width:75px;\"></td>
</tr>
<tr>
<td width=\"80\">".LAN_487."</td>
<td><input type=\"text\" name=\"download_filesize\" value=\"$download_filesize\" class=\"textbox\" style=\"width:75px;\"></td>
</tr>
<tr>
<td align=\"center\" colspan=\"2\">
<input type=\"submit\" name=\"save_download\" value=\"".LAN_488."\" class=\"button\"></td>
</tr>
</table>
</form>\n";
		closetable();
		tablebreak();
		opentable(LAN_500);
		echo "<table align=\"center\" width=\"400\" cellpadding=\"0\" cellspacing=\"0\" class=\"body\">\n";
		$result = dbquery("SELECT * FROM ".$fusion_prefix."download_cats ORDER BY download_cat_name");
		if (dbrows($result) != 0) {
			echo "<tr>
<td class=\"altbg\">".LAN_501."</td>
<td align=\"right\" class=\"altbg\">".LAN_502."</td>
</tr>
<tr>
<td colspan=\"2\" height=\"1\"></td>
</tr>\n";
			while ($data = dbarray($result)) {
				if ($data[download_cat_id] == $download_cat_id) { $p_img = "off"; $div = ""; } else { $p_img = "on"; $div = "style=\"display:none\""; }
				echo "<tr>
<td class=\"altbg\">$data[download_cat_name]</td>
<td class=\"altbg\" align=\"right\"><img onclick=\"javascript:flipBox('$data[download_cat_id]')\" name=\"b_$data[download_cat_id]\" border=\"0\" src=\"".fusion_themedir."images/panel_".$p_img.".gif\"></td>
</tr>\n";
				$result2 = dbquery("SELECT * FROM ".$fusion_prefix."downloads WHERE download_cat='$data[download_cat_id]' ORDER BY download_title");
				if (dbrows($result2) != 0) {
					echo "<tr>
<td colspan=\"2\">
<div id=\"box_$data[download_cat_id]\"".$div.">
<table align=\"center\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" class=\"body\">\n";
					while ($data2 = dbarray($result2)) {
						echo "<tr>
<td><a href=\"$data2[download_url]\" target=\"_blank\">$data2[download_title]</a></td>
<td align=\"right\" width=\"100\"><a href=\"$PHP_SELF?step=edit&download_cat_id=$data[download_cat_id]&download_id=$data2[download_id]\">".LAN_503."</a> -
<a href=\"$PHP_SELF?step=delete&download_cat_id=$data[download_cat_id]&download_id=$data2[download_id]\" onClick=\"return DeleteItem()\">".LAN_504."</a></td>
</tr>\n";
					}
					echo "</table>
</div>
</td>
</tr>\n";
				} else {
					echo "<tr>
<td colspan=\"2\">
<div id=\"box_$data[download_cat_id]\" style=\"display:none\">
<table align=\"center\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" class=\"body\">
<tr>
<td>".LAN_505."</td>
</tr>
</table>
</div>
</td>
</tr>\n";
				}
			}
			echo "</table>\n";
			echo "<script>
function DeleteItem()
{
	return confirm(\"".LAN_460."\");
}
</script>\n";
		} else {
			echo "<tr>
<td align=\"center\"><br>
".LAN_506."<br><br>
<a href=\"download_cats.php\">".LAN_507."<br><br></td>
</tr>
</table>\n";
		}
		closetable();
	} else {
		opentable(LAN_500);
		echo "<center>".LAN_508."<br>
".LAN_509."<br>
<br>
<a href=\"download_cats.php\">".LAN_510."</a>".LAN_511."</center>\n";
		closetable();
	}
}

echo "</td>\n";
require "../footer.php";
?>