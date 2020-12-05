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
require fusion_langdir."admin/admin_weblinks.php";
require "navigation.php";

if (!Admin()) header("Location: ../index.php");

if (Admin()) {
	$result = dbquery("SELECT * FROM ".$fusion_prefix."weblink_cats");
	if (dbrows($result) != 0) {
		if ($step == "delete") {
			$result = dbquery("DELETE FROM ".$fusion_prefix."weblinks WHERE weblink_id='$weblink_id'");
			header("Location: weblinks.php?weblink_cat_id=$weblink_cat_id");
		}
		if (isset($_POST['save_link'])) {
			$weblink_name = stripinput($weblink_name);
			$weblink_description = stripinput($weblink_description);
			if ($step == "edit") {
				$result = dbquery("UPDATE ".$fusion_prefix."weblinks SET weblink_name='$weblink_name', weblink_description='$weblink_description', weblink_url='$weblink_url', weblink_cat='$weblink_cat', weblink_datestamp='".time()."' WHERE weblink_id='$weblink_id'");
				header("Location: weblinks.php?weblink_cat_id=$weblink_cat");
			} else {
				$result = dbquery("INSERT INTO ".$fusion_prefix."weblinks VALUES('', '$weblink_name', '$weblink_description', '$weblink_url', '$weblink_cat', '".time()."', '0')");
				header("Location: weblinks.php?weblink_cat_id=$weblink_cat");
			}
		}
		if ($step == "edit") {
			$result = dbquery("SELECT * FROM ".$fusion_prefix."weblinks WHERE weblink_id='$weblink_id'");
			$data = dbarray($result);
			$weblink_name = $data[weblink_name];
			$weblink_description = $data[weblink_description];
			$weblink_url = $data[weblink_url];
			$formaction = "$PHP_SELF?step=edit&weblink_cat_id=$weblink_cat_id&weblink_id=$weblink_id";
			opentable(LAN_470);
		} else {
			$formaction = "$PHP_SELF";
			$weblink_url = "http://";
			opentable(LAN_471);
		}
		$result2 = dbquery("SELECT * FROM ".$fusion_prefix."weblink_cats ORDER BY weblink_cat_name");
		if (dbrows($result2) != 0) {
			while ($data2 = dbarray($result2)) {
				if ($step == "edit") {
					if ($data[weblink_cat] == $data2[weblink_cat_id]) { $sel = " selected"; } else { $sel = ""; }
				}
				$editlist .= "<option value=\"$data2[weblink_cat_id]\"$sel>$data2[weblink_cat_name]</option>\n";
			}
		}
		echo "<form name=\"addcat\" method=\"post\" action=\"$formaction\">
<table align=\"center\" width=\"400\" cellpadding=\"0\" cellspacing=\"0\" class=\"body\">
<tr>
<td width=\"80\">".LAN_480."</td>
<td><input type=\"text\" name=\"weblink_name\" value=\"$weblink_name\" class=\"textbox\" style=\"width:200px;\"></td>
</tr>
<tr>
<td width=\"80\">".LAN_481."</td>
<td><input type=\"text\" name=\"weblink_description\" value=\"$weblink_description\" class=\"textbox\" style=\"width:250px;\"></td>
</tr>
<tr>
<td width=\"80\">".LAN_482."</td>
<td><input type=\"text\" name=\"weblink_url\" value=\"$weblink_url\" class=\"textbox\" style=\"width:250px;\"></td>
</tr>
<tr>
<td width=\"80\">".LAN_483."</td>
<td><select name=\"weblink_cat\" class=\"textbox\" style=\"width:150px;\">
$editlist</select></td>
</tr>
<tr>
<td align=\"center\" colspan=\"2\">
<input type=\"submit\" name=\"save_link\" value=\"".LAN_484."\" class=\"button\"></td>
</tr>
</table>
</form>\n";
		closetable();
		tablebreak();
		opentable(LAN_500);
		echo "<table align=\"center\" width=\"400\" cellpadding=\"0\" cellspacing=\"0\" class=\"body\">\n";
		$result = dbquery("SELECT * FROM ".$fusion_prefix."weblink_cats ORDER BY weblink_cat_name");
		if (dbrows($result) != 0) {
			echo "<tr>
<td class=\"altbg\">".LAN_501."</td>
<td align=\"right\" class=\"altbg\">".LAN_502."</td>
</tr>
<tr>
<td colspan=\"2\" height=\"1\"></td>
</tr>\n";
			while ($data = dbarray($result)) {
				if ($data[weblink_cat_id] == $weblink_cat_id) { $p_img = "off"; $div = ""; } else { $p_img = "on"; $div = "style=\"display:none\""; }
				echo "<tr>
<td class=\"altbg\">$data[weblink_cat_name]</td>
<td class=\"altbg\" align=\"right\"><img onclick=\"javascript:flipBox('$data[weblink_cat_id]')\" name=\"b_$data[weblink_cat_id]\" border=\"0\" src=\"".fusion_themedir."images/panel_".$p_img.".gif\"></td>
</tr>\n";
				$result2 = dbquery("SELECT * FROM ".$fusion_prefix."weblinks WHERE weblink_cat='$data[weblink_cat_id]' ORDER BY weblink_name");
				if (dbrows($result2) != 0) {
					echo "<tr>
<td colspan=\"2\">
<div id=\"box_$data[weblink_cat_id]\"$div>
<table align=\"center\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" class=\"body\">\n";
					while ($data2 = dbarray($result2)) {
						echo "<tr>
<td><a href=\"$data2[weblink_url]\" target=\"_blank\">$data2[weblink_name]</a></td>
<td width=\"75\"><a href=\"$PHP_SELF?step=edit&weblink_cat_id=$data[weblink_cat_id]&weblink_id=$data2[weblink_id]\">".LAN_503."</a> -
<a href=\"$PHP_SELF?step=delete&weblink_cat_id=$data[weblink_cat_id]&weblink_id=$data2[weblink_id]\"' onClick=\"return DeleteItem()\">".LAN_504."</a></td>
</tr>\n";
					}
					echo "</table>
</div>
</td>
</tr>\n";
				} else {
					echo "<tr>
<td colspan=\"2\">
<div id=\"box_$data[weblink_cat_id]\" style=\"display:none\">
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
<a href=\"weblink_cats.php\">".LAN_507."<br><br></td>
</tr>
</table>\n";
		}
		closetable();
	} else {
		opentable(LAN_500);
		echo "<center>".LAN_508."<br>
".LAN_509."<br>
<br>
<a href=\"weblink_cats.php\">".LAN_510."</a>".LAN_511."</center>\n";
		closetable();
	}
}

echo "</td>\n";
require "../footer.php";
?>