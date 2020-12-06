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

include LOCALE.LOCALESET."admin/weblinks.php";

if (!checkrights("WC")) fallback("../index.php");
if (isset($cat_id) && !isNum($cat_id)) fallback(FUSION_SELF);
if (!isset($step)) $step = "";

if ($step == "delete") {
	$result = dbquery("SELECT * FROM ".$db_prefix."weblinks WHERE weblink_cat='$cat_id'");
	if (dbrows($result) != 0) {
		opentable($locale['400']);
		echo "<center><br>
".$locale['401']."<br>
<span class='small'>".$locale['402']."</span><br><br>
<a href='weblink_cats.php'>".$locale['403']."</a><br><br>
<a href='index.php'>".$locale['404']."</a><br><br>
</center>\n";
	} else {
		$result = dbquery("DELETE FROM ".$db_prefix."weblink_cats WHERE weblink_cat_id='$cat_id'");
		opentable($locale['400']);
		echo "<center><br>
".$locale['405']."<br><br>
<a href='weblink_cats.php'>".$locale['403']."</a><br><br>
<a href='index.php'>".$locale['404']."</a><br><br>
</center>\n";
	}
	closetable();
} else {
	if (isset($_POST['save_cat'])) {
		$cat_name = stripinput($_POST['cat_name']);
		$cat_description = stripinput($_POST['cat_description']);
		$cat_access = isNum($_POST['cat_access']) ? $_POST['cat_access'] : "0";
		if ($step == "edit") {
			$result = dbquery("UPDATE ".$db_prefix."weblink_cats SET weblink_cat_name='$cat_name', weblink_cat_description='$cat_description', weblink_cat_access='$cat_access' WHERE weblink_cat_id='$cat_id'");
		} else {
			$result = dbquery("INSERT INTO ".$db_prefix."weblink_cats VALUES('', '$cat_name', '$cat_description', '$cat_access')");
		}
		redirect("weblink_cats.php");
	}
	if ($step == "edit") {
		$result = dbquery("SELECT * FROM ".$db_prefix."weblink_cats WHERE weblink_cat_id='$cat_id'");
		$data = dbarray($result);
		$cat_name = $data['weblink_cat_name'];
		$cat_description = $data['weblink_cat_description'];
		$cat_access = $data['weblink_cat_access'];
		$formaction = FUSION_SELF."?step=edit&cat_id=".$data['weblink_cat_id'];
		opentable($locale['420']);
	} else {
		$cat_name = "";
		$cat_description = "";
		$cat_access = "";
		$formaction = FUSION_SELF;
		opentable($locale['421']);
	}
	$user_groups = getusergroups(); $access_opts = ""; $sel = "";
	while(list($key, $user_group) = each($user_groups)){
		$sel = ($cat_access == $user_group['0'] ? " selected" : "");
		$access_opts .= "<option value='".$user_group['0']."'$sel>".$user_group['1']."</option>\n";
	}
	echo "<form name='addcat' method='post' action='$formaction'>
<table align='center' width='400' cellspacing='0' cellpadding='0'>
<tr>
<td width='130' class='tbl'>".$locale['430']."</td>
<td class='tbl'><input type='text' name='cat_name' value='$cat_name' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td width='130' class='tbl'>".$locale['431']."</td>
<td class='tbl'><input type='text' name='cat_description' value='$cat_description' class='textbox' style='width:250px;'></td>
</tr>
<tr>
<td width='130' class='tbl'>".$locale['433']."</td>
<td class='tbl'><select name='cat_access' class='textbox' style='width:150px;'>
$access_opts</select></td>
</tr>
<tr>
<td align='center' colspan='2' class='tbl'>
<input type='submit' name='save_cat' value='".$locale['432']."' class='button'></td>
</tr>
</table>
</form>\n";
	closetable();
	tablebreak();
	opentable($locale['440']);
	echo "<table align='center' width='400' cellspacing='1' cellpadding='0' class='tbl-border'>\n";
	$result = dbquery("SELECT * FROM ".$db_prefix."weblink_cats ORDER BY weblink_cat_name");
	if (dbrows($result) != 0) {
		$i = 0;
		echo "<tr>
<td class='tbl2'>".$locale['441']."</td>
<td align='center' class='tbl2'>".$locale['445']."</td>
<td align='right' width='60' class='tbl2'>".$locale['442']."</td>
</tr>\n";
		while ($data = dbarray($result)) {
			$cell_color = ($i % 2 == 0 ? "tbl1" : "tbl2");
			echo "<tr>
<td class='$cell_color'><a href='".FUSION_SELF."?step=edit&cat_id=".$data['weblink_cat_id']."'>".$data['weblink_cat_name']."</a><br>
<span class='small'>".trimlink($data['weblink_cat_description'], 45)."</span></td>
<td align='center' class='$cell_color'>".getgroupname($data['weblink_cat_access'])."</td>
<td align='right' class='$cell_color'><a href='".FUSION_SELF."?step=delete&cat_id=".$data['weblink_cat_id']."'>".$locale['443']."</a></td>
</tr>\n";
			$i++;
		}
		echo "</table>\n";
	} else {
		echo "<tr><td align='center' class='tbl1'>".$locale['444']."</td></tr>\n</table>\n";
	}
	closetable();
}

echo "</td>\n";
require_once BASEDIR."footer.php";
?>