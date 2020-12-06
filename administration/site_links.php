<?php
/*---------------------------------------------------+
| PHP-Fusion 6 Content Management System
+----------------------------------------------------+
| Copyright (c) 2005 Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the
| GNU General Public License. For details refer to
| the included gpl.txt file or visit http://gnu.org
+----------------------------------------------------*/
require_once "../maincore.php";
require_once BASEDIR."subheader.php";
require_once ADMIN."navigation.php";
include LOCALE.LOCALESET."admin/sitelinks.php";

if (!checkrights("SL")) fallback("../index.php");
if (isset($link_id) && !isNum($link_id)) fallback(FUSION_SELF);
if (!isset($action)) $action = "";

if ($action == "delete") {
	opentable($locale['400']);
		$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."site_links WHERE link_id='$link_id'"));
		$result = dbquery("UPDATE ".$db_prefix."site_links SET link_order=link_order-1 WHERE link_order>'".$data['link_order']."'");
		$result = dbquery("DELETE FROM ".$db_prefix."site_links WHERE link_id='$link_id'");
	echo "<center><br>
".$locale['401']."<br><br>
<a href='".FUSION_SELF."'>".$locale['402']."</a><br><br>
<a href='index.php'>".$locale['403']."</a><br><br>
</center>\n";
	closetable();
} else {
	if (isset($_POST['savelink'])) {
		$link_name = stripinput($_POST['link_name']);
		$link_url = stripinput($_POST['link_url']);
		$link_position = $_POST['link_position'];
		$link_window = $_POST['link_window'];
		if ($action == "edit") {
			$data = dbarray(dbquery("SELECT link_order FROM ".$db_prefix."site_links WHERE link_id='$link_id'"));
			if($link_order < $data['link_order']){
				$result = dbquery("UPDATE ".$db_prefix."site_links SET link_order=link_order+1 WHERE link_order>='$link_order' AND link_order<'$data[link_order]'");
			} else if ($link_order > $data['link_order']){
				$result = dbquery("UPDATE ".$db_prefix."site_links SET link_order=link_order-1 WHERE link_order>'".$data['link_order']."' AND link_order<='$link_order'");
			}				
			$result = dbquery("UPDATE ".$db_prefix."site_links SET link_name='$link_name', link_url='$link_url', link_visibility='$link_visibility', link_position='$link_position', link_window='$link_window', link_order='$link_order' WHERE link_id='$link_id'");
			redirect(FUSION_SELF);
		} else {
			if(!$link_order) $link_order=dbresult(dbquery("SELECT MAX(link_order) FROM ".$db_prefix."site_links"),0)+1;
			$result = dbquery("UPDATE ".$db_prefix."site_links SET link_order=link_order+1 WHERE link_order>='$link_order'");	
			$result = dbquery("INSERT INTO ".$db_prefix."site_links VALUES('', '$link_name', '$link_url', '$link_visibility', '$link_position', '$link_window', '$link_order')");
			redirect(FUSION_SELF);
		}
	}
	if ($action == "edit") {
		$result = dbquery("SELECT * FROM ".$db_prefix."site_links WHERE link_id='$link_id'");
		$data = dbarray($result);
		$link_name = $data['link_name'];
		$link_url = $data['link_url'];
		$link_visibility = $data['link_visibility'];
		$link_order = $data['link_order'];
		$pos1_check = ($data['link_position']=="1" ? " checked" : "");
		$pos2_check = ($data['link_position']=="2" ? " checked" : "");
		$pos3_check = ($data['link_position']=="3" ? " checked" : "");
		$window_check = ($data['link_window']=="1" ? " checked" : "");
		$formaction = FUSION_SELF."?action=edit&link_id=".$data['link_id'];
		opentable($locale['410']);
	} else {
		$link_name = "";
		$link_url = "";
		$link_visibility = "";
		$link_order = "";
		$pos1_check = " checked";
		$pos2_check = "";
		$pos3_check = "";
		$window_check = "";
		$formaction = FUSION_SELF;
		opentable($locale['411']);
	}
	$visibility_opts = ""; $sel = "";
	$user_groups = getusergroups();
	while(list($key, $user_group) = each($user_groups)){
		$sel = ($link_visibility == $user_group['0'] ? " selected" : "");
		$visibility_opts .= "<option value='".$user_group['0']."'$sel>".$user_group['1']."</option>\n";
	}
	echo "<form name='layoutform' method='post' action='$formaction'>
<table align='center' cellspacing='0' cellpadding='0'>
<tr>
<td class='tbl'>".$locale['420']."</td>
<td class='tbl'><input type='text' name='link_name' value='$link_name' maxlength='100' class='textbox' style='width:240px;'></td>
</tr>
<tr>
<td class='tbl'>".$locale['421']."</td>
<td class='tbl'><input type='text' name='link_url' value='$link_url' maxlength='200' class='textbox' style='width:240px;'></td>
</tr>
<tr>
<td class='tbl'>".$locale['422']."</td>
<td class='tbl'><select name='link_visibility' class='textbox'>
$visibility_opts</select>
".$locale['423']."
<input type='text' name='link_order'  value='$link_order' maxlength='2' class='textbox' style='width:40px;'></td>
</tr>
<tr>
<td valign='top' class='tbl'>".$locale['424']."</td>
<td class='tbl'><input type='radio' name='link_position' value='1'$pos1_check> ".$locale['425']."<br>
<input type='radio' name='link_position' value='2'$pos2_check> ".$locale['426']."<br>
<input type='radio' name='link_position' value='3'$pos3_check> ".$locale['427']."<hr>
<input type='checkbox' name='link_window' value='1'$window_check> ".$locale['428']."</td>
</tr>
<tr>
<td align='center' colspan='2' class='tbl'>
<input type='submit' name='savelink' value='".$locale['429']."' class='button'></td>
</tr>
</table>
</form>\n";
	closetable();
	tablebreak();
	opentable($locale['412']);
	echo "<table align='center' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td class='tbl2'>".$locale['430']."</td>
<td align='center' class='tbl2'>".$locale['431']."</td>
<td align='center' class='tbl2'>".$locale['432']."</td>
<td align='right' class='tbl2'>".$locale['433']."</td>
</tr>\n";
	$result = dbquery("SELECT * FROM ".$db_prefix."site_links ORDER BY link_order");
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
					echo "<img src='".THEME."images/bullet.gif'> <a href='".$data['link_url']."'>".$data['link_name']."</a>\n";
				} else {
					echo "<img src='".THEME."images/bullet.gif'> <a href='".BASEDIR.$data['link_url']."'>".$data['link_name']."</a>\n";
				}
			}
			if ($data['link_position'] == 3) echo "</i>";
			echo "</td>
<td align='center'>".getgroupname($data['link_visibility'])."</td>
<td align='center'>".$data['link_order']."</td>
<td align='right'><a href='".FUSION_SELF."?action=edit&link_id=".$data['link_id']."'>".$locale['434']."</a> -
<a href='".FUSION_SELF."?action=delete&link_id=".$data['link_id']."'>".$locale['435']."</a></td>
</tr>\n";
		}
	} else {
		echo "<tr colspan='4'>\n<td>".$locale['436']."</td>\n</tr>\n";
	}
	echo "</table>\n";
	closetable();
}

echo "</td>\n";
require_once BASEDIR."footer.php";
?>