<?
/*--------------------------------------------+
| PHP-Fusion 5 - Content Management System    |
|---------------------------------------------|
| author: Nick Jones (Digitanium) � 2002-2005 |
| web: http://www.php-fusion.co.uk            |
| email: nick@php-fusion.co.uk                |
|---------------------------------------------|
| Released under the terms and conditions of  |
| the GNU General Public License (Version 2)  |
+--------------------------------------------*/
@include "../fusion_config.php";
include "../fusion_core.php";
include FUSION_BASE."subheader.php";
include FUSION_LANGUAGES.FUSION_LAN."admin/admin_main.php";
include FUSION_LANGUAGES.FUSION_LAN."admin/admin_sitelinks.php";
include FUSION_ADMIN."navigation.php";

if (!iADMIN) { header("Location:../index.php"); exit; }

if (iSUPERADMIN) {
	if ($action == "delete") {
		opentable(LAN_400);
			$data = dbarray(dbquery("SELECT * FROM ".$fusion_prefix."site_links WHERE link_id='$link_id'"));
			$result = dbquery("UPDATE ".$fusion_prefix."site_links SET link_order=link_order-1 WHERE link_order>'".$data['link_order']."'");
			$result = dbquery("DELETE FROM ".$fusion_prefix."site_links WHERE link_id='$link_id'");
		echo "<center><br>
".LAN_401."<br><br>
<a href='$PHP_SELF'>".LAN_402."</a><br><br>
<a href='index.php'>".LAN_403."</a><br><br>
</center>\n";
		closetable();
	} else {
		if (isset($_POST['savelink'])) {
			$link_name = stripinput($_POST['link_name']);
			$link_url = stripinput($_POST['link_url']);
			$link_position = $_POST['link_position'];
			if ($action == "edit") {
				$data = dbarray(dbquery("SELECT link_order FROM ".$fusion_prefix."site_links WHERE link_id='$link_id'"));
				if($link_order < $data['link_order']){
					$result = dbquery("UPDATE ".$fusion_prefix."site_links SET link_order=link_order+1 WHERE link_order>='$link_order' AND link_order<'$data[link_order]'");
				} else if ($link_order > $data['link_order']){
					$result = dbquery("UPDATE ".$fusion_prefix."site_links SET link_order=link_order-1 WHERE link_order>'".$data['link_order']."' AND link_order<='$link_order'");
				}				
				$result = dbquery("UPDATE ".$fusion_prefix."site_links SET link_name='$link_name', link_url='$link_url', link_visibility='$link_visibility', link_position='$link_position', link_order='$link_order' WHERE link_id='$link_id'");
				header("Location:".$PHP_SELF);
			} else {
				if(!$link_order) $link_order=dbresult(dbquery("SELECT MAX(link_order) FROM ".$fusion_prefix."site_links"),0)+1;
				$result = dbquery("UPDATE ".$fusion_prefix."site_links SET link_order=link_order+1 WHERE link_order>='$link_order'");	
				$result = dbquery("INSERT INTO ".$fusion_prefix."site_links VALUES('', '$link_name', '$link_url', '$link_visibility', '$link_position', '$link_order')");
				header("Location:".$PHP_SELF);
			}
		}
		if ($action == "edit") {
			$result = dbquery("SELECT * FROM ".$fusion_prefix."site_links WHERE link_id='$link_id'");
			$data = dbarray($result);
			$link_name = $data['link_name'];
			$link_url = $data['link_url'];
			$link_visibility = $data['link_visibility'];
			$link_order = $data['link_order'];
			$pos1_check = ($data['link_position']=="1" ? " checked" : "");
			$pos2_check = ($data['link_position']=="2" ? " checked" : "");
			$pos3_check = ($data['link_position']=="3" ? " checked" : "");
			$formaction = "$PHP_SELF?action=edit&link_id=".$data['link_id'];
			opentable(LAN_410);
		} else {
			$pos1_check = " checked";
			$formaction = "$PHP_SELF";
			opentable(LAN_411);
		}
		$i=0;
		$visibility = array(USER0,USER1,USER2,USER3,USER4);
		while ($visibility[$i]) {
			if ($action == "edit") {
				if ($link_visibility == $i) { $sel = " selected"; } else { $sel = ""; }
			}
			$visibility_opts .= "<option value='$i'$sel>".$visibility[$i]."</option>\n";
			$i++;
		}
echo "<form name='layoutform' method='post' action='$formaction'>
<table align='center' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td>".LAN_420."</td>
<td><input type='text' name='link_name' value='$link_name' maxlength='100' class='textbox' style='width:240px;'></td>
</tr>
<tr>
<td>".LAN_421."</td>
<td><input type='text' name='link_url' value='$link_url' maxlength='200' class='textbox' style='width:240px;'></td>
</tr>
<tr>
<td>".LAN_422."</td>
<td><select name='link_visibility' class='textbox'>
$visibility_opts</select>
".LAN_423."
<input type='text' name='link_order'  value='$link_order' maxlength='2' class='textbox' style='width:40px;'></td>
</tr>
<tr>
<td valign='top'>".LAN_424."</td>
<td><input type='radio' name='link_position' value='1'$pos1_check> ".LAN_425."<br>
<input type='radio' name='link_position' value='2'$pos2_check> ".LAN_426."<br>
<input type='radio' name='link_position' value='3'$pos3_check> ".LAN_427."</td>
</tr>
<tr>
<td align='center' colspan='2'>
<input type='submit' name='savelink' value='".LAN_428."' class='button'></td>
</tr>
</table>
</form>\n";
		closetable();
		tablebreak();
		opentable(LAN_412);
		echo "<table align='center' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td class='tbl2'>".LAN_430."</td>
<td align='center' class='tbl2'>".LAN_431."</td>
<td align='center' class='tbl2'>".LAN_432."</td>
<td align='right' class='tbl2'>".LAN_433."</td>
</tr>\n";
		$result = dbquery("SELECT * FROM ".$fusion_prefix."site_links ORDER BY link_order");
		if (dbrows($result) != 0) {
			while($data = dbarray($result)) {
				echo "<tr>\n<td>";
				if ($data['link_position'] == 3) echo "<i>";
				if ($data['link_name'] != "---" && $data['link_url'] == "---") {
					echo "<b>".$data['link_name']."</b>\n";
				} else if ($data['link_name'] == "---" && $data['link_url'] == "---") {
					echo "<hr>\n";
				} else {
					if (strstr($data['link_url'], "http://") || strstr($data['link_url'], "https://")) {
						echo "<img src='".FUSION_THEME."images/bullet.gif'> <a href='".$data['link_url']."'>".$data['link_name']."</a>\n";
					} else {
						echo "<img src='".FUSION_THEME."images/bullet.gif'> <a href='".FUSION_BASE.$data['link_url']."'>".$data['link_name']."</a>\n";
					}
				}
				if ($data['link_position'] == 3) echo "</i>";
				echo "</td>
<td align='center'>".$visibility[$data['link_visibility']]."</td>
<td align='center'>".$data['link_order']."</td>
<td align='right'><a href='$PHP_SELF?action=edit&link_id=".$data['link_id']."'>".LAN_434."</a> -
<a href='$PHP_SELF?action=delete&link_id=".$data['link_id']."'>".LAN_435."</a></td>
</tr>\n";
			}
		} else {
			echo "<tr colspan='4'>
<td>".LAN_436."</td>
</tr>\n";
		}
		echo "</table>\n";
		closetable();
	}
}

echo "</td>\n";
include "../footer.php";
?>