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

include FUSION_LANGUAGES.FUSION_LAN."admin/admin_blacklist.php";

if (!checkrights("3")) { header("Location:../index.php"); exit; }
		
if (isset($step) && $step == "delete") {
	opentable(LAN_400);
	$result = dbquery("DELETE FROM ".$fusion_prefix."blacklist WHERE blacklist_id='$blacklist_id'");
	echo "<center><br>
".LAN_401."<br><br>
<a href='".FUSION_SELF."'>".LAN_402."</a><br><br>
<a href='index.php'>".LAN_403."</a><br><br>
</center>\n";
	closetable();
} else {
	if (isset($_POST['blacklist_user'])) {
		$blacklist_ip = stripinput($_POST['blacklist_ip']);
		$blacklist_email = stripinput($_POST['blacklist_email']);
		$blacklist_reason = stripinput($_POST['blacklist_reason']);
		if ($step == "edit") {
			$result = dbquery("UPDATE ".$fusion_prefix."blacklist SET blacklist_ip='$blacklist_ip', blacklist_email='$blacklist_email', blacklist_reason='$blacklist_reason' WHERE blacklist_id='$blacklist_id'");
		} else {
			$result = dbquery("INSERT INTO ".$fusion_prefix."blacklist VALUES('', '$blacklist_ip', '$blacklist_email', '$blacklist_reason')");
		}
		header("Location: ".FUSION_SELF);
	}
	if (isset($step) && $step == "edit") {
		$data = dbarray(dbquery("SELECT * FROM ".$fusion_prefix."blacklist WHERE blacklist_id='$blacklist_id'"));
		$blacklist_ip = $data['blacklist_ip'];
		$blacklist_email = $data['blacklist_email'];
		$blacklist_reason = $data['blacklist_reason'];
		$form_title = LAN_421;
		$form_action = FUSION_SELF."?step=edit&blacklist_id=".$data['blacklist_id'];
	} else {
		$blacklist_ip = ""; $blacklist_email = ""; $blacklist_reason = "";
		$form_title = LAN_420;
		$form_action = FUSION_SELF;;
	}
	opentable($form_title);
	echo "<table align='center' width='450' cellpadding='0' cellspacing='0' class='tbl'>
<tr>
<td colspan='2'>".LAN_440."
<hr></td>
</tr>
</table>
<form name='blacklist_form' method='post' action='$form_action'>
<table align='center' width='450' cellpadding='0' cellspacing='0' class='tbl'>
<tr>
<td>".LAN_441."</td>
<td><input type='text' name='blacklist_ip' value='$blacklist_ip' class='textbox' style='width:150px'></td>
</tr>
<tr>
<td>".LAN_442."</td>
<td><input type='text' name='blacklist_email' value='$blacklist_email' class='textbox' style='width:250px'></td>
</tr>
<tr>
<td valign='top'>".LAN_443."</td>
<td><textarea name='blacklist_reason' cols='46' rows='3' class='textbox'>$blacklist_reason</textarea></td>
</tr>
<tr>
<td align='center' colspan='2'><br>
<input type='submit' name='blacklist_user' value='".LAN_444."' class='button'></td>
</tr>
</table>
</form>";
	closetable();
	tablebreak();
	opentable(LAN_460);
	$sql = dbquery("SELECT * FROM ".$fusion_prefix."blacklist");
	if (dbrows($sql) != 0) {
		echo "<table width='400' align='center' cellpadding='0' cellspacing='0'>
<tr>
<td class='tbl2'>".LAN_461."</td>
<td align='right' class='tbl2'>".LAN_462."</td>
</tr>\n";
		while ($data = dbarray($sql)) {
			echo "<tr>
<td>".($data['blacklist_ip'] ? $data['blacklist_ip'] : $data['blacklist_email'])."<br>
<span class='small2'>".$data['blacklist_reason']."</span></td>
<td align='right'><a href='".FUSION_SELF."?step=edit&blacklist_id=".$data['blacklist_id']."'>".LAN_463."</a> -
<a href='".FUSION_SELF."?step=delete&blacklist_id=".$data['blacklist_id']."'>".LAN_464."</a></td>
</tr>\n";
		}
		echo "</table>\n";
	} else {
		echo "<center><br>\n".LAN_465."<br><br>\n</center>\n";
	}
	closetable();
}

echo "</td>\n";
require_once FUSION_BASE."footer.php";
?>