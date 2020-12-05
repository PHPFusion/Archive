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
require fusion_langdir."admin/admin_panels.php";
require "navigation.php";

if (!Admin()) header("Location: ../index.php");

if (SuperAdmin()) {
	$levels = array(USER0, USER1, USER2, USER3, USER4);
	if ($step == "refresh") {
		$i = 1;
		$result = dbquery("SELECT * FROM ".$fusion_prefix."panels WHERE panel_side='l' ORDER BY panel_order");
		while ($data = dbarray($result)) {
			$result2 = dbquery("UPDATE ".$fusion_prefix."panels SET panel_order='$i' WHERE panel_id='$data[panel_id]'");
			$i++;
		}
		$i = 1;
		$result = dbquery("SELECT * FROM ".$fusion_prefix."panels WHERE panel_side='r' ORDER BY panel_order");
		while ($data = dbarray($result)) {
			$result2 = dbquery("UPDATE ".$fusion_prefix."panels SET panel_order='$i' WHERE panel_id='$data[panel_id]'");
			$i++;
		}
	}
	if ($step == "delete") {
		$data = dbarray(dbquery("SELECT * FROM ".$fusion_prefix."panels WHERE panel_id='$panel_id'"));
		$result = dbquery("DELETE FROM ".$fusion_prefix."panels WHERE panel_id='$panel_id'");
		$result = dbquery("UPDATE ".$fusion_prefix."panels SET panel_order=panel_order-1 WHERE panel_side='$side' AND panel_order>='$data[panel_order]'");
		header("Location: panels.php");
	}
	if ($step == "setstatus") {
		$result = dbquery("UPDATE ".$fusion_prefix."panels SET panel_status='$status' WHERE panel_id='$panel_id'");
	}
	if ($step == "mup") {
		$data = dbarray(dbquery("SELECT * FROM ".$fusion_prefix."panels WHERE panel_side='$side' AND panel_order='$order'"));
		$result = dbquery("UPDATE ".$fusion_prefix."panels SET panel_order=panel_order+1 WHERE panel_id='$data[panel_id]'");
		$result = dbquery("UPDATE ".$fusion_prefix."panels SET panel_order=panel_order-1 WHERE panel_id='$panel_id'");
		header("Location: panels.php");
	}
	if ($step == "mdown") {
		$data = dbarray(dbquery("SELECT * FROM ".$fusion_prefix."panels WHERE panel_side='$side' AND panel_order='$order'"));
		$result = dbquery("UPDATE ".$fusion_prefix."panels SET panel_order=panel_order-1 WHERE panel_id='$data[panel_id]'");
		$result = dbquery("UPDATE ".$fusion_prefix."panels SET panel_order=panel_order+1 WHERE panel_id='$panel_id'");
		header("Location: panels.php");
	}
	if ($step == "mleft") {
		$result = dbquery("SELECT * FROM ".$fusion_prefix."panels WHERE panel_side='l' ORDER BY panel_order DESC LIMIT 1");
		if (dbrows($result) != 0) { $data = dbarray($result); $neworder = $data[panel_order] + 1; } else { $neworder = 1; }
		$result = dbquery("UPDATE ".$fusion_prefix."panels SET panel_side='l', panel_order='$neworder' WHERE panel_id='$panel_id'");
		$result = dbquery("UPDATE ".$fusion_prefix."panels SET panel_order=panel_order-1 WHERE panel_side='r' AND panel_order>='$order'");
		header("Location: panels.php");
	}
	if ($step == "mright") {
		$result = dbquery("SELECT * FROM ".$fusion_prefix."panels WHERE panel_side='r' ORDER BY panel_order DESC LIMIT 1");
		if (dbrows($result) != 0) { $data = dbarray($result); $neworder = $data[panel_order] + 1; } else { $neworder = 1; }
		$result = dbquery("UPDATE ".$fusion_prefix."panels SET panel_side='r', panel_order='$neworder' WHERE panel_id='$panel_id'");
		$result = dbquery("UPDATE ".$fusion_prefix."panels SET panel_order=panel_order-1 WHERE panel_side='l' AND panel_order>='$order'");
		header("Location: panels.php");
	}
	opentable(LAN_400);
	tablebreak();
	echo "<table align='center' border='0' cellpadding='0' cellspacing='0' class='forum-border'>
<tr>
<td>
<table align='center' border='0' cellpadding='0' cellspacing='1' width='100%'>
<tr>
<td class='forum2'>".LAN_401."</td>
<td class='forum2' colspan='2'>".LAN_402."</td>
<td class='forum2'>".LAN_403."</td>
<td class='forum2'>".LAN_404."</td>
<td class='forum2'>".LAN_405."</td>
<td class='forum2'>".LAN_406."</td>
</tr>\n";

$result = dbquery("SELECT * FROM ".$fusion_prefix."panels WHERE panel_side='l' ORDER BY panel_order");
$numrows = dbrows($result);
$i = 1;
while ($data = dbarray($result)) {
	if ($numrows != 1) {
		$up = $data[panel_order] - 1;
		$down = $data[panel_order] + 1;
		if ($i == 1) {
			$up_down = " <a href='$PHP_SELF?step=mdown&panel_id=$data[panel_id]&side=l&order=$down'><img src='".fusion_themedir."images/down.gif' border='0' /></a>";
		} else if ($i < $numrows) {
			$up_down = " <a href='$PHP_SELF?step=mup&panel_id=$data[panel_id]&side=l&order=$up'><img src='".fusion_themedir."images/up.gif' border='0' /></a>\n";
			$up_down .= " <a href='$PHP_SELF?step=mdown&panel_id=$data[panel_id]&side=l&order=$down'><img src='".fusion_themedir."images/down.gif' border='0' /></a>";
		} else {
			$up_down = " <a href='$PHP_SELF?step=mup&panel_id=$data[panel_id]&side=l&order=$up'><img src='".fusion_themedir."images/up.gif' border='0' /></a>";
		}
	} else {
		$up_down = "";
	}
	$panel_access = $data[panel_access];
	echo "<tr>
<td class='forum1'>$data[panel_name]</td>
<td class='forum1'>".LAN_420."</td>
<td class='forum1'><a href='$PHP_SELF?step=mright&panel_id=$data[panel_id]&order=$data[panel_order]'><img src='".fusion_themedir."images/right.gif' border='0' /></a></td>
<td class='forum1'>$data[panel_order]$up_down</td>
<td class='forum1'>";
	if ($data[panel_type] == "file") { echo LAN_422; } else { echo LAN_423; }
	echo "</td>
<td class='forum1'>$levels[$panel_access]</td>
<td align='center' class='forum1'>\n";
	if ($data[panel_filename] != "user_info.php") {
		echo "[<a href='panel_editor.php?step=edit&panel_id=$data[panel_id]&side=l'>".LAN_434."</a>]\n";
		if ($data[panel_status] == 0) {
			echo "[<a href='$PHP_SELF?step=setstatus&status=1&panel_id=$data[panel_id]'>".LAN_435."</a>]\n";
		} else {
			echo "[<a href='$PHP_SELF?step=setstatus&status=0&panel_id=$data[panel_id]'>".LAN_436."</a>]\n";
		}
		echo "[<a href='$PHP_SELF?step=delete&panel_id=$data[panel_id]&side=l' onClick=\"return DeleteItem()\">".LAN_437."</a>]\n";
	}
echo "</td>
</tr>\n";
	$i++;
}
$result = dbquery("SELECT * FROM ".$fusion_prefix."panels WHERE panel_side='r' ORDER BY panel_order");
$numrows = dbrows($result);
$i = 1;
while ($data = dbarray($result)) {
	if ($numrows != 1) {
		$up = $data[panel_order] - 1;
		$down = $data[panel_order] + 1;
		if ($i == 1) {
			$up_down = " <a href='$PHP_SELF?step=mdown&panel_id=$data[panel_id]&side=r&order=$down'><img src='".fusion_themedir."images/down.gif' border='0' /></a>";
		} else if ($i < $numrows) {
			$up_down = " <a href='$PHP_SELF?step=mup&panel_id=$data[panel_id]&side=r&order=$up'><img src='".fusion_themedir."images/up.gif' border='0' /></a>\n";
			$up_down .= " <a href='$PHP_SELF?step=mdown&panel_id=$data[panel_id]&side=r&order=$down'><img src='".fusion_themedir."images/down.gif' border='0' /></a>";
		} else {
			$up_down = " <a href='$PHP_SELF?step=mup&panel_id=$data[panel_id]&side=r&order=$up'><img src='".fusion_themedir."images/up.gif' border='0' /></a>";
		}
	} else {
		$up_down = "";
	}
	$panel_access = $data[panel_access];
	echo "<tr>
<td class='forum1'>$data[panel_name]</td>
<td class='forum1'>".LAN_421."</td>
<td class='forum2'><a href='$PHP_SELF?step=mleft&panel_id=$data[panel_id]&order=$data[panel_order]'><img src='".fusion_themedir."images/left.gif' border='0' /></a></td>
<td class='forum1'>$data[panel_order]$up_down</td>
<td class='forum1'>";
	if ($data[panel_type] == "file") { echo LAN_422; } else { echo LAN_423; }
	echo "</td>
<td class='forum1'>$levels[$panel_access]</td>
<td align='center' class='forum1'>\n";
	if ($data[panel_filename] != "user_info.php") {
		echo "[<a href='panel_editor.php?step=edit&panel_id=$data[panel_id]&side=r'>".LAN_434."</a>]\n";
		if ($data[panel_status] == 0) {
			echo "[<a href='$PHP_SELF?step=setstatus&status=1&panel_id=$data[panel_id]'>".LAN_435."</a>]\n";
		} else {
			echo "[<a href='$PHP_SELF?step=setstatus&status=0&panel_id=$data[panel_id]'>".LAN_436."</a>]\n";
		}
		echo "[<a href='$PHP_SELF?step=delete&panel_id=$data[panel_id]&side=r' onClick=\"return DeleteItem()\">".LAN_437."</a>]\n";
	}
echo "</td>
</tr>\n";
	$i++;
}
	echo "</table>
</td>
</tr>
</table>
<div align='center'><br />
[ <a href='panel_editor.php'>".LAN_438."</a> ]
[ <a href='$PHP_SELF?step=refresh'>".LAN_439."</a> ]</div>\n";

	tablebreak();
	closetable();
	echo "<script>
function DeleteItem()
{
	return confirm(\"".LAN_440."\");
}
</script>\n";
}

echo "</td>\n";
require "../footer.php";
?>