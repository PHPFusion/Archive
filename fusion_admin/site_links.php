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
require fusion_langdir."admin/admin_sitelinks.php";
require "navigation.php";

if (!Admin()) header("Location: ../index.php");

if (SuperAdmin()) {
	if ($action == "delete") {
		opentable(LAN_400);
			$data = dbarray(dbquery("SELECT * FROM ".$fusion_prefix."site_links WHERE link_id='$link_id'"));
			$result = dbquery("UPDATE ".$fusion_prefix."site_links SET link_order=link_order-1 WHERE link_order>'$data[link_order]'");
			$result = dbquery("DELETE FROM ".$fusion_prefix."site_links WHERE link_id='$link_id'");
		echo "<center><br>
".LAN_401."<br><br>
<a href=\"$PHP_SELF\">".LAN_402."</a><br><br>
<a href=\"index.php\">".LAN_403."</a><br><br>
</center>\n";
		closetable();
	} else {
		if (isset($_POST['savelink'])) {
			if ($action == "edit") {
				$data = dbarray(dbquery("SELECT * FROM ".$fusion_prefix."site_links WHERE link_id='$link_id'"));
				if($link_order<$data[link_order]){
					$result = dbquery("UPDATE ".$fusion_prefix."site_links SET link_order=link_order+1 WHERE link_order>='$link_order' AND link_order<'$data[link_order]'");
				} else if ($link_order>$data[link_order]){
					$result = dbquery("UPDATE ".$fusion_prefix."site_links SET link_order=link_order-1 WHERE link_order>'$data[link_order]' AND link_order<='$link_order'");
				}				
				$link_name = stripinput($link_name);
				$result = dbquery("UPDATE ".$fusion_prefix."site_links SET link_name='$link_name', link_url='$link_url', link_order='$link_order' WHERE link_id='$link_id'");
				unset($action, $link_name, $link_url, $link_order);
			} else {
				$link_name = stripinput($link_name);
				$result = dbquery("UPDATE ".$fusion_prefix."site_links SET link_order=link_order+1 WHERE link_order>='$link_order'");	
				$result = dbquery("INSERT INTO ".$fusion_prefix."site_links VALUES('', '$link_name', '$link_url', '$link_order')");
				unset($action, $link_name, $link_url, $link_order);
			}
		}
		if ($action == "edit") {
			$result = dbquery("SELECT * FROM ".$fusion_prefix."site_links WHERE link_id='$link_id'");
			$data = dbarray($result);
			$link_name = $data[link_name];
			$link_url = $data[link_url];
			$link_order = $data[link_order];
			$formaction = "$PHP_SELF?action=edit&link_id=$data[link_id]";
			opentable(LAN_404);
		} else {
			$formaction = "$PHP_SELF";
			opentable(LAN_405);
		}
		echo "<form name=\"layoutform\" method=\"post\" action=\"$formaction\">
<table align=\"center\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<tr>
<td>".LAN_406."</td>
<td><input type=\"text\" name=\"link_name\" value=\"$link_name\" maxlength=\"100\" class=\"textbox\" style=\"width: 150px;\"></td>
<td>".LAN_407."</td>
<td><input type=\"text\" name=\"link_order\"  value=\"$link_order\" maxlength=\"2\" class=\"textbox\" style=\"width: 40px;\"></td>
</tr>
<tr>
<td>".LAN_408."</td>
<td colspan=\"3\"><input type=\"text\" name=\"link_url\" value=\"$link_url\" maxlength=\"200\" class=\"textbox\" style=\"width:245px;\"></td>
</tr>
<tr>
<td align=\"center\" colspan=\"4\"><input type=\"submit\" name=\"savelink\" value=\"".LAN_409."\" class=\"button\"></td>
</tr>
</table>
</form>\n";
		closetable();
		tablebreak();
		opentable(LAN_410);
		echo "<table align=\"center\" width=\"500\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<tr>
<td class=\"altbg\">".LAN_411."</td>
<td class=\"altbg\">".LAN_412."</td>
<td align=\"center\" class=\"altbg\">".LAN_413."</td>
<td width=\"75\" class=\"altbg\">".LAN_414."</td>
</tr>\n";
		$result = dbquery("SELECT * FROM ".$fusion_prefix."site_links ORDER BY link_order");
		if (dbrows($result) != 0) {
			while($data = dbarray($result)) {
				echo "<tr>
<td>$data[link_name]</td><td>$data[link_url]</td>
<td align=\"center\">$data[link_order]</td>
<td><a href=\"$PHP_SELF?action=edit&link_id=$data[link_id]\">".LAN_415."</a> -
<a href=\"$PHP_SELF?action=delete&link_id=$data[link_id]\">".LAN_416."</a></td>
</tr>\n";
			}
		} else {
			echo "<tr colspan=\"4\">
<td>".LAN_417."</td>
</tr>\n";
		}
		echo "</table>\n";
		closetable();
	}
}

echo "</td>\n";
require "../footer.php";
?>