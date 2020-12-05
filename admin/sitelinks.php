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
require fusion_langdir."admin/admin_sitelinks.php";

require "navigation.php";
require "userinfo.php";
echo "</td>
<td valign=\"top\" class=\"bodybg\">\n";

if ($userdata[user_mod] > "2") {
	if ($action == "delete") {
		opentable(LAN_200);
		$result = dbquery("DELETE FROM ".$fusion_prefix."site_links WHERE link_id='$link_id'");
		echo "<center><br>
".LAN_201."<br><br>
<a href=\"sitelinks.php\">".LAN_202."</a><br><br>
<a href=\"index.php\">".LAN_203."</a><br><br>
</center>\n";
		closetable();
	} else {
		if (isset($_POST['savelink'])) {
			if ($action == "edit") {
				$link_name = addslashes($link_name);
				$result = dbquery("UPDATE ".$fusion_prefix."site_links SET link_name='$link_name', link_url='$link_url', link_order='$link_order' WHERE link_id='$link_id'");
				unset($action, $link_name, $link_url, $link_order);
			} else {
				$link_name = addslashes($link_name);
				$result = dbquery("INSERT INTO ".$fusion_prefix."site_links VALUES('', '$link_name', '$link_url', '$link_order')");
				unset($action, $link_name, $link_url, $link_order);
			}
		}
		if ($action == "edit") {
			$result = dbquery("SELECT * FROM ".$fusion_prefix."site_links WHERE link_id='$link_id'");
			$data = dbarray($result);
			$link_name = stripslashes($data[link_name]);
			$link_url = $data[link_url];
			$link_order = $data[link_order];
			$formaction = "$PHP_SELF?action=edit&link_id=$data[link_id]";
			opentable(LAN_204);
		} else {
			$formaction = "$PHP_SELF";
			opentable(LAN_205);
		}
		echo "<table align=\"center\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<form name=\"layoutform\" method=\"post\" action=\"$formaction\">
<tr>
<td>".LAN_206."</td>
<td><input type=\"textbox\" name=\"link_name\" value=\"$link_name\" maxlength=\"100\" class=\"textbox\" style=\"width: 150px;\"></td>
<td>".LAN_207."</td>
<td><input type=\"textbox\" name=\"link_order\"  value=\"$link_order\" maxlength=\"2\" class=\"textbox\" style=\"width: 40px;\"></td>
</tr>
<tr>
<td>".LAN_208."</td>
<td colspan=\"3\"><input type=\"textbox\" name=\"link_url\" value=\"$link_url\" maxlength=\"200\" class=\"textbox\" style=\"width:245px;\"></td>
</tr>
<tr>
<td align=\"center\" colspan=\"4\"><input type=\"submit\" name=\"savelink\" value=\"".LAN_209."\" class=\"button\" style=\"width: 100px;\"></td>
</tr>
</form>
</table>\n";
		closetable();
		tablebreak();
		opentable(LAN_210);
		echo "<table align=\"center\" width=\"500\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<tr>
<td class=\"altbg\">".LAN_211."</td>
<td class=\"altbg\">".LAN_212."</td>
<td align=\"center\" class=\"altbg\">".LAN_213."</td>
<td width=\"75\" class=\"altbg\">".LAN_214."</td>
</tr>\n";
		$result = dbquery("SELECT * FROM ".$fusion_prefix."site_links ORDER BY link_order");
		if (dbrows($result) != 0) {
			while($data = dbarray($result)) {
				echo "<tr>
<td>$data[link_name]</td><td>$data[link_url]</td>
<td align=\"center\">$data[link_order]</td>
<td><a href=\"$PHP_SELF?action=edit&link_id=$data[link_id]\">".LAN_215."</a> -
<a href=\"$PHP_SELF?action=delete&link_id=$data[link_id]\">".LAN_216."</a></td>
</tr>\n";
			}
		} else {
			echo "<tr colspan=\"4\">
<td>".LAN_217."</td>
</tr>\n";
		}
		echo "</table>\n";
		closetable();
	}
}

echo "</td>
</tr>
</table>\n";

require "../footer.php";
?>