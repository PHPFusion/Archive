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
include LOCALE.LOCALESET."admin/admins.php";

if (!checkrights("AD")) fallback("../index.php");

if (isset($_POST['add_admin'])) {
	$user_id = isNum($_POST['user_id']) ? $_POST['user_id'] : "0";
	if (isset($_POST['all_rights'])) {
		$result = dbquery("UPDATE ".$db_prefix."users SET user_level='102', user_rights='A.AC.AD.B.C.CP.DB.DC.D.FQ.F.IM.I.IP.M.N.P.PH.PI.PO.S.SL.S1.S2.S3.S4.S5.S6.S7.SU.UG.U.W.WC' WHERE user_id='$user_id'");
	} else {
		$result = dbquery("UPDATE ".$db_prefix."users SET user_level='102' WHERE user_id='$user_id'");
	}
	redirect(FUSION_SELF);
}

if (isset($remove)) {
	if (isNum($remove) && $remove != "1") {
		$result = dbquery("UPDATE ".$db_prefix."users SET user_level='101', user_rights='' WHERE user_id='$remove' AND user_level='102'");
	}
	redirect(FUSION_SELF);
}

if (isset($_POST['update_admin'])) {
	if (!isNum($user_id) || $user_id == "1") fallback(FUSION_SELF);
	if (isset($_POST['rights'])) {
		$user_rights = "";
		for ($i = 0;$i < count($_POST['rights']);$i++) {
			$user_rights .= stripinput($_POST['rights'][$i]);
			if ($i != (count($_POST['rights'])-1)) $user_rights .= ".";
		}
		$result = dbquery("UPDATE ".$db_prefix."users SET user_rights='$user_rights' WHERE user_id='$user_id' AND user_level='102'");
	} else {
		$result = dbquery("UPDATE ".$db_prefix."users SET user_rights='' WHERE user_id='$user_id' AND user_level='102'");
	}
	redirect(FUSION_SELF);
}

opentable($locale['400']);
$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_level>='102' ORDER BY user_level DESC, user_name");
echo "<table width='100%' cellspacing='0' cellpadding='0'>
<tr>
<td class='tbl2'>".$locale['401']."</td>
<td class='tbl2'>".$locale['402']."</td>
<td align='right' class='tbl2'>".$locale['403']."</td>
</tr>\n";
while ($data = dbarray($result)) {
	echo "<tr>
<td class='tbl1'>".$data['user_name'].($data['user_level'] == "103" ? " (".getuserlevel($data['user_level']).")" : "")."</td>
<td class='tbl1'>".($data['user_rights'] ? str_replace(".", " ", $data['user_rights']) : "".$locale['405']."")."</td>
<td align='right' width='100' class='tbl1'>\n";
	if ($data['user_level'] != "103") {
		echo "<a href='".FUSION_SELF."?edit=".$data['user_id']."'>".$locale['406']."</a> |\n";
		echo "<a href='".FUSION_SELF."?remove=".$data['user_id']."'>".$locale['407']."</a>\n";
	}
	echo "</td>\n</tr>\n";
}
$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_level<'102' ORDER BY user_name");
if (dbrows($result)) {
	echo "<form name='adminform' method='post' action='".FUSION_SELF."'>
<tr>
<td align='center' colspan='3' class='tbl1'><hr>
<select name='user_id' class='textbox'>\n";
	while ($data = dbarray($result)) {
		echo "<option value='".$data['user_id']."'>".$data['user_name']."</option>\n";
	}
	echo "</select>
<input type='submit' name='add_admin' value='".$locale['410']."' class='button'><br>
<input type='checkbox' name='all_rights' value='1'> ".$locale['411']."
</td>
</tr>
</form>\n";
}
echo "</table>\n";
closetable();
tablebreak();
if (isset($edit)) {
	if (!isNum($edit) || $edit == "1") fallback(FUSION_SELF);
	$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_id='$edit' AND user_level='102' ORDER BY user_id");
	if (dbrows($result)) {
		$data = dbarray($result);
		$user_rights = explode(".", $data['user_rights']);
		$result2 = dbquery("SELECT * FROM ".$db_prefix."admin WHERE admin_page!='4' ORDER BY admin_page ASC,admin_title");
		opentable($locale['420']);
		$columns = 2; $counter = 0; $page = 1;
		$admin_page = array($locale['421'],$locale['422'],$locale['423']);
		echo "<form name='adminform' method='post' action='".FUSION_SELF."?user_id=$edit'>\n";
		echo "<table align='center' width='400' cellpadding='0' cellspacing='1' class='tbl-border'>\n";
		echo "<tr>\n<td colspan='2' class='tbl2'>".$admin_page['0']."</td>\n</tr>\n<tr>\n";
		while ($data2 = dbarray($result2)) {
			if ($page != $data2['admin_page']) {
				echo ($counter % $columns == 0 ? "</tr>\n" : "<td width='50%' class='tbl1'></td>\n</tr>\n");
				echo "<tr>\n<td colspan='2' class='tbl2'>".$admin_page[$page]."</td>\n</tr>\n<tr>\n";
				$page++; $counter = 0;
			}
			if ($counter != 0 && ($counter % $columns == 0)) echo "</tr>\n<tr>\n";
		        echo "<td width='50%' class='tbl1'><input type='checkbox' name='rights[]' value='".$data2['admin_rights']."'".(in_array($data2['admin_rights'], $user_rights) ? " checked" : "")."> ".$data2['admin_title']."</td>\n";
			$counter++;
		}
		echo "</tr>\n<tr>\n<td align='center' colspan='2' class='tbl1'><input type='submit' name='update_admin' value='".$locale['424']."' class='button'></td>\n";
		echo "</tr>\n</table>\n</form>\n";
		closetable();
	}
}

echo "</td>\n";
require_once BASEDIR."footer.php";
?>