<?php
/*--------------------------------------------+
| PHP-Fusion 5 - Content Management System    |
|---------------------------------------------|
| author: Nick Jones (Digitanium) © 2002-2005 |
| web: http://www.php-fusion.co.uk            |
| email: nick@php-fusion.co.uk                |
|---------------------------------------------|
| Released under the terms and conditions of  |
| the GNU General Public License (Version 2)  |
+--------------------------------------------*/
@require_once "../fusion_config.php";
require_once "../fusion_core.php";
require_once FUSION_BASE."subheader.php";
require_once FUSION_ADMIN."navigation.php";

include FUSION_LANGUAGES.FUSION_LAN."admin/admin_admins.php";
include FUSION_ADMIN."admin_panels.php";

if (!checkrights("R")) { header("Location:../index.php"); exit; }

if (isset($_POST['add_admin'])) {
	if (isset($_POST['all_rights'])) {
		$sql = dbquery("UPDATE ".$fusion_prefix."users SET user_level='251', user_rights='1.2.3.4.5.6.7.8.9.A.B.C.D.E.F.G.H.I.J.K.L.M.N.O.P.Q.R.S' WHERE user_id='".$_POST['user_id']."'");
	} else {
		$sql = dbquery("UPDATE ".$fusion_prefix."users SET user_level='251' WHERE user_id='".$_POST['user_id']."'");
	}
	header("Location: ".FUSION_SELF);
}

if (isset($remove)) {
	if (isNum($remove) && $remove != "1") {
		$sql = dbquery("UPDATE ".$fusion_prefix."users SET user_level='250', user_rights='' WHERE user_id='$remove' AND user_level='251'");
	}
	header("Location: ".FUSION_SELF);
}

if (isset($_POST['update_admin'])) {
	if (!isNum($user_id) || $user_id == "1") { header("Location: ".FUSION_SELF); exit; };
	if (isset($_POST['rights'])) {
		$user_rights = "";
		for ($i = 0;$i < count($_POST['rights']);$i++) {
			$user_rights .= $_POST['rights'][$i];
			if ($i != (count($_POST['rights'])-1)) $user_rights .= ".";
		}
		$sql = dbquery("UPDATE ".$fusion_prefix."users SET user_rights='$user_rights' WHERE user_id='$user_id' AND user_level='251'");
	} else {
		$sql = dbquery("UPDATE ".$fusion_prefix."users SET user_rights='' WHERE user_id='$user_id' AND user_level='251'");
	}
	header("Location: ".FUSION_SELF);
}

opentable(LAN_400);
$sql = dbquery("SELECT * FROM ".$fusion_prefix."users WHERE user_level>='251' ORDER BY user_level DESC, user_name");
echo "<table width='100%' cellspacing='0' cellpadding='0'>
<tr>
<td class='tbl2'>".LAN_401."</td>
<td class='tbl2'>".LAN_402."</td>
<td align='right' class='tbl2'>".LAN_403."</td>
</tr>\n";
while ($data = dbarray($sql)) {
	echo "<tr>
<td class='tbl1'>".$data['user_name'].($data['user_level'] == "252" ? " (".getuserlevel($data['user_level']).")" : "")."</td>
<td class='tbl1'>".($data['user_rights'] ? str_replace(".", "", $data['user_rights']) : "".LAN_405."")."</td>
<td align='right' class='tbl1'>\n";
	if ($data['user_level'] != "252") {
		echo "<a href='".FUSION_SELF."?edit=".$data['user_id']."'>".LAN_406."</a> |\n";
		echo "<a href='".FUSION_SELF."?remove=".$data['user_id']."'>".LAN_407."</a>\n";
	}
	echo "</td>\n</tr>\n";
}
$sql = dbquery("SELECT * FROM ".$fusion_prefix."users WHERE user_level<'251' ORDER BY user_name");
if (dbrows($sql)) {
	echo "<form name='adminform' method='post' action='".FUSION_SELF."'>
<tr>
<td align='center' colspan='3' class='tbl1'><hr>
<select name='user_id' class='textbox'>\n";
	while ($data = dbarray($sql)) {
		echo "<option value='".$data['user_id']."'>".$data['user_name']."</option>\n";
	}
	echo "</select>
<input type='submit' name='add_admin' value='".LAN_410."' class='button'><br>
<input type='checkbox' name='all_rights' value='1'> ".LAN_411."
</td>
</tr>
</form>\n";
}
echo "</table>\n";
closetable();
tablebreak();
if (isset($edit)) {
	if (!isNum($edit) || $edit == "1") { header("Location: ".FUSION_SELF); exit; };
	$sql = dbquery("SELECT * FROM ".$fusion_prefix."users WHERE user_id='$edit' AND user_level='251' ORDER BY user_id");
	if (dbrows($sql)) {
		$data = dbarray($sql);
		$user_rights = explode(".", $data['user_rights']);
		opentable(LAN_420);
		$columns = 3; $counter = 0;
		echo "<form name='adminform' method='post' action='".FUSION_SELF."?user_id=$edit'>\n";
		echo "<table align='center' width='100%' cellspacing='0' cellpadding='0' class='tbl'>\n<tr>\n";
		while(list($key, $admin_info) = each($admin_panels)){
			if ($counter != 0) if ($counter % $columns == 0) echo "</tr>\n<tr>\n";
		        echo "<td><input type='checkbox' name='rights[]' value='".$admin_info[2]."'".(in_array($admin_info[2], $user_rights) ? " checked" : "")."> ".$admin_info[0]."</td>\n";
			$counter++;
		}
		echo "</tr>\n<tr>\n<td align='center' colspan='3'><hr><input type='submit' name='update_admin' value='".LAN_421."' class='button'></td>\n";
		echo "</tr>\n</table>\n</form>\n";
		closetable();
	}
}

echo "</td>\n";
require_once FUSION_BASE."footer.php";
?>