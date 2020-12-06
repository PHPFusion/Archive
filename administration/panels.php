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
include LOCALE.LOCALESET."admin/panels.php";

if (!checkrights("P")) fallback("../index.php");
if (isset($panel_id) && !isNum($panel_id)) fallback(FUSION_SELF);
if (!isset($step)) $step = "";

if ($step == "refresh") {
	$i = 1;
	$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='1' ORDER BY panel_order");
	while ($data = dbarray($result)) {
		$result2 = dbquery("UPDATE ".$db_prefix."panels SET panel_order='$i' WHERE panel_id='".$data['panel_id']."'");
		$i++;
	}
	$i = 1;
	$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='2' ORDER BY panel_order");
	while ($data = dbarray($result)) {
		$result2 = dbquery("UPDATE ".$db_prefix."panels SET panel_order='$i' WHERE panel_id='".$data['panel_id']."'");
		$i++;
	}
	$i = 1;
	$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='3' ORDER BY panel_order");
	while ($data = dbarray($result)) {
		$result2 = dbquery("UPDATE ".$db_prefix."panels SET panel_order='$i' WHERE panel_id='".$data['panel_id']."'");
		$i++;
	}
}
if ($step == "delete") {
	$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_id='$panel_id'"));
	$result = dbquery("DELETE FROM ".$db_prefix."panels WHERE panel_id='$panel_id'");
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_order=panel_order-1 WHERE panel_side='$side' AND panel_order>='".$data['panel_order']."'");
	redirect("panels.php");
}
if ($step == "setstatus") {
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_status='$status' WHERE panel_id='$panel_id'");
}
if ($step == "mup") {
	$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='$side' AND panel_order='$order'"));
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_order=panel_order+1 WHERE panel_id='".$data['panel_id']."'");
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_order=panel_order-1 WHERE panel_id='$panel_id'");
	redirect("panels.php");
}
if ($step == "mdown") {
	$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='$side' AND panel_order='$order'"));
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_order=panel_order-1 WHERE panel_id='".$data['panel_id']."'");
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_order=panel_order+1 WHERE panel_id='$panel_id'");
	redirect("panels.php");
}
if ($step == "mleft") {
	$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='1' ORDER BY panel_order DESC LIMIT 1");
	if (dbrows($result) != 0) { $data = dbarray($result); $neworder = $data['panel_order'] + 1; } else { $neworder = 1; }
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_side='1', panel_order='$neworder' WHERE panel_id='$panel_id'");
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_order=panel_order-1 WHERE panel_side='3' AND panel_order>='$order'");
	redirect("panels.php");
}
if ($step == "mright") {
	$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='3' ORDER BY panel_order DESC LIMIT 1");
	if (dbrows($result) != 0) { $data = dbarray($result); $neworder = $data['panel_order'] + 1; } else { $neworder = 1; }
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_side='3', panel_order='$neworder' WHERE panel_id='$panel_id'");
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_order=panel_order-1 WHERE panel_side='1' AND panel_order>='$order'");
	redirect("panels.php");
}
opentable($locale['400']);
tablebreak();
echo "<table align='center' border='0' cellpadding='0' cellspacing='0' class='tbl-border'>
<tr>
<td>
<table align='center' border='0' cellpadding='0' cellspacing='1' width='100%'>
<tr>
<td class='tbl2'>".$locale['401']."</td>
<td class='tbl2' colspan='2'>".$locale['402']."</td>
<td class='tbl2'>".$locale['403']."</td>
<td class='tbl2'>".$locale['404']."</td>
<td class='tbl2'>".$locale['405']."</td>
<td class='tbl2'>".$locale['406']."</td>
</tr>\n";
// Left Panels
$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='1' ORDER BY panel_order");
$numrows = dbrows($result);
$i = 1;
while ($data = dbarray($result)) {
if ($numrows != 1) {
	$up = $data['panel_order'] - 1;
	$down = $data['panel_order'] + 1;
	if ($i == 1) {
		$up_down = " <a href='".FUSION_SELF."?step=mdown&panel_id=".$data['panel_id']."&side=1&order=$down'><img src='".THEME."images/down.gif' border='0'></a>";
	} else if ($i < $numrows) {
		$up_down = " <a href='".FUSION_SELF."?step=mup&panel_id=".$data['panel_id']."&side=1&order=$up'><img src='".THEME."images/up.gif' border='0'></a>\n";
		$up_down .= " <a href='".FUSION_SELF."?step=mdown&panel_id=".$data['panel_id']."&side=1&order=$down'><img src='".THEME."images/down.gif' border='0'></a>";
	} else {
		$up_down = " <a href='".FUSION_SELF."?step=mup&panel_id=".$data['panel_id']."&side=1&order=$up'><img src='".THEME."images/up.gif' border='0'></a>";
	}
} else {
	$up_down = "";
}
echo "<tr>
<td class='tbl1'>".$data['panel_name']."</td>
<td class='tbl1'>".$locale['420']."</td>
<td class='tbl1'><a href='".FUSION_SELF."?step=mright&panel_id=".$data['panel_id']."&order=".$data['panel_order']."'><img src='".THEME."images/right.gif' border='0'></a></td>
<td class='tbl1'>".$data['panel_order']."$up_down</td>
<td class='tbl1'>";
echo ($data['panel_type'] == "file" ? $locale['423'] : $locale['424']);
echo "</td>
<td class='tbl1'>".getgroupname($data['panel_access'])."</td>
<td align='center' ".($data['panel_filename'] != "user_info_panel" ? "class='tbl1'" : "class='tbl2'").">\n";
if ($data['panel_filename'] != "user_info_panel") {
	echo "[<a href='panel_editor.php?step=edit&panel_id=".$data['panel_id']."&side=1'>".$locale['434']."</a>]\n";
	if ($data['panel_status'] == 0) {
		echo "[<a href='".FUSION_SELF."?step=setstatus&status=1&panel_id=".$data['panel_id']."'>".$locale['435']."</a>]\n";
	} else {
		echo "[<a href='".FUSION_SELF."?step=setstatus&status=0&panel_id=".$data['panel_id']."'>".$locale['436']."</a>]\n";
	}
	echo "[<a href='".FUSION_SELF."?step=delete&panel_id=".$data['panel_id']."&side=1' onClick='return DeleteItem()'>".$locale['437']."</a>]\n";
}
echo "</td>
</tr>\n";
$i++;
}
// Center Panels
$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='2' ORDER BY panel_order");
$numrows = dbrows($result);
$i = 1;
while ($data = dbarray($result)) {
if ($numrows != 1) {
	$up = $data['panel_order'] - 1;
	$down = $data['panel_order'] + 1;
	if ($i == 1) {
		$up_down = " <a href='".FUSION_SELF."?step=mdown&panel_id=".$data['panel_id']."&side=2&order=$down'><img src='".THEME."images/down.gif' border='0'></a>";
	} else if ($i < $numrows) {
		$up_down = " <a href='".FUSION_SELF."?step=mup&panel_id=".$data['panel_id']."&side=2&order=$up'><img src='".THEME."images/up.gif' border='0'></a>\n";
		$up_down .= " <a href='".FUSION_SELF."?step=mdown&panel_id=".$data['panel_id']."&side=2&order=$down'><img src='".THEME."images/down.gif' border='0'></a>";
	} else {
		$up_down = " <a href='".FUSION_SELF."?step=mup&panel_id=".$data['panel_id']."&side=2&order=$up'><img src='".THEME."images/up.gif' border='0'></a>";
	}
} else {
	$up_down = "";
}
echo "<tr>
<td class='tbl1'>".$data['panel_name']."</td>
<td colspan='2' class='tbl1'>".$locale['421']."</td>
<td class='tbl1'>".$data['panel_order']."$up_down</td>
<td class='tbl1'>";
echo ($data['panel_type'] == "file" ? $locale['423'] : $locale['424']);
echo "</td>
<td class='tbl1'>".getgroupname($data['panel_access'])."</td>
<td align='center' ".($data['panel_filename'] != "user_info_panel" ? "class='tbl1'" : "class='tbl2'").">\n";
if ($data['panel_filename'] != "user_info_panel") {
	echo "[<a href='panel_editor.php?step=edit&panel_id=".$data['panel_id']."&side=2'>".$locale['434']."</a>]\n";
	if ($data['panel_status'] == 0) {
		echo "[<a href='".FUSION_SELF."?step=setstatus&status=1&panel_id=".$data['panel_id']."'>".$locale['435']."</a>]\n";
	} else {
		echo "[<a href='".FUSION_SELF."?step=setstatus&status=0&panel_id=".$data['panel_id']."'>".$locale['436']."</a>]\n";
	}
	echo "[<a href='".FUSION_SELF."?step=delete&panel_id=".$data['panel_id']."&side=2' onClick='return DeleteItem()'>".$locale['437']."</a>]\n";
}
echo "</td>
</tr>\n";
$i++;
}
// Right Panels
$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='3' ORDER BY panel_order");
$numrows = dbrows($result);
$i = 1;
while ($data = dbarray($result)) {
if ($numrows != 1) {
	$up = $data['panel_order'] - 1;
	$down = $data['panel_order'] + 1;
	if ($i == 1) {
		$up_down = " <a href='".FUSION_SELF."?step=mdown&panel_id=".$data['panel_id']."&side=3&order=$down'><img src='".THEME."images/down.gif' border='0'></a>";
	} else if ($i < $numrows) {
		$up_down = " <a href='".FUSION_SELF."?step=mup&panel_id=".$data['panel_id']."&side=3&order=$up'><img src='".THEME."images/up.gif' border='0'></a>\n";
		$up_down .= " <a href='".FUSION_SELF."?step=mdown&panel_id=".$data['panel_id']."&side=3&order=$down'><img src='".THEME."images/down.gif' border='0'></a>";
	} else {
		$up_down = " <a href='".FUSION_SELF."?step=mup&panel_id=".$data['panel_id']."&side=3&order=$up'><img src='".THEME."images/up.gif' border='0'></a>";
	}
} else {
	$up_down = "";
}
echo "<tr>
<td class='tbl1'>".$data['panel_name']."</td>
<td class='tbl1'>".$locale['422']."</td>
<td class='tbl2'><a href='".FUSION_SELF."?step=mleft&panel_id=".$data['panel_id']."&order=".$data['panel_order']."'><img src='".THEME."images/left.gif' border='0'></a></td>
<td class='tbl1'>".$data['panel_order']."$up_down</td>
<td class='tbl1'>";
echo ($data['panel_type'] == "file" ? $locale['423'] : $locale['424']);
echo "</td>
<td class='tbl1'>".getgroupname($data['panel_access'])."</td>
<td align='center' ".($data['panel_filename'] != "user_info_panel" ? "class='tbl1'" : "class='tbl2'").">\n";
if ($data['panel_filename'] != "user_info_panel") {
	echo "[<a href='panel_editor.php?step=edit&panel_id=".$data['panel_id']."&side=3'>".$locale['434']."</a>]\n";
	if ($data['panel_status'] == 0) {
		echo "[<a href='".FUSION_SELF."?step=setstatus&status=1&panel_id=".$data['panel_id']."'>".$locale['435']."</a>]\n";
	} else {
		echo "[<a href='".FUSION_SELF."?step=setstatus&status=0&panel_id=".$data['panel_id']."'>".$locale['436']."</a>]\n";
	}
	echo "[<a href='".FUSION_SELF."?step=delete&panel_id=".$data['panel_id']."&side=3' onClick='return DeleteItem()'>".$locale['437']."</a>]\n";
}
echo "</td>
</tr>\n";
$i++;
}
echo "</table>
</td>
</tr>
</table>
<div align='center'><br>
[ <a href='panel_editor.php'>".$locale['438']."</a> ]
[ <a href='".FUSION_SELF."?step=refresh'>".$locale['439']."</a> ]</div>\n";

tablebreak();
closetable();
echo "<script>
function DeleteItem() {
	return confirm('".$locale['440']."');
}
</script>\n";

echo "</td>\n";
require_once BASEDIR."footer.php";
?>