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
include LOCALE.LOCALESET."admin/blacklist.php";

if (!checkrights("B")) fallback("../index.php");
if (isset($article_id) && !isNum($article_id)) fallback("index.php");

if (isset($step) && $step == "delete") {
	opentable($locale['400']);
	$result = dbquery("DELETE FROM ".$db_prefix."blacklist WHERE blacklist_id='$blacklist_id'");
	echo "<center><br>
".$locale['401']."<br><br>
<a href='".FUSION_SELF."'>".$locale['402']."</a><br><br>
<a href='index.php'>".$locale['403']."</a><br><br>
</center>\n";
	closetable();
} else {
	if (isset($_POST['blacklist_user'])) {
		$blacklist_ip = stripinput($_POST['blacklist_ip']);
		$blacklist_email = stripinput($_POST['blacklist_email']);
		$blacklist_reason = stripinput($_POST['blacklist_reason']);
		if ($step == "edit") {
			$result = dbquery("UPDATE ".$db_prefix."blacklist SET blacklist_ip='$blacklist_ip', blacklist_email='$blacklist_email', blacklist_reason='$blacklist_reason' WHERE blacklist_id='$blacklist_id'");
		} else {
			$result = dbquery("INSERT INTO ".$db_prefix."blacklist VALUES('', '$blacklist_ip', '$blacklist_email', '$blacklist_reason')");
		}
		redirect(FUSION_SELF);
	}
	if (isset($step) && $step == "edit") {
		$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."blacklist WHERE blacklist_id='$blacklist_id'"));
		$blacklist_ip = $data['blacklist_ip'];
		$blacklist_email = $data['blacklist_email'];
		$blacklist_reason = $data['blacklist_reason'];
		$form_title = $locale['421'];
		$form_action = FUSION_SELF."?step=edit&blacklist_id=".$data['blacklist_id'];
	} else {
		$blacklist_ip = ""; $blacklist_email = ""; $blacklist_reason = "";
		$form_title = $locale['420'];
		$form_action = FUSION_SELF;
	}
	opentable($form_title);
	echo "<table align='center' width='450' cellpadding='0' cellspacing='0'>
<tr>
<td colspan='2' class='tbl'>".$locale['440']."
<hr></td>
</tr>
</table>
<form name='blacklist_form' method='post' action='$form_action'>
<table align='center' width='450' cellpadding='0' cellspacing='0'>
<tr>
<td class='tbl'>".$locale['441']."</td>
<td class='tbl'><input type='text' name='blacklist_ip' value='$blacklist_ip' class='textbox' style='width:150px'></td>
</tr>
<tr>
<td class='tbl'>".$locale['442']."</td>
<td class='tbl'><input type='text' name='blacklist_email' value='$blacklist_email' class='textbox' style='width:250px'></td>
</tr>
<tr>
<td valign='top' class='tbl'>".$locale['443']."</td>
<td class='tbl'><textarea name='blacklist_reason' cols='46' rows='3' class='textbox'>$blacklist_reason</textarea></td>
</tr>
<tr>
<td align='center' colspan='2' class='tbl'><br>
<input type='submit' name='blacklist_user' value='".$locale['444']."' class='button'></td>
</tr>
</table>
</form>";
	closetable();
	tablebreak();
	opentable($locale['460']);
	$result = dbquery("SELECT * FROM ".$db_prefix."blacklist");
	if (dbrows($result) != 0) {
		echo "<table width='400' align='center' cellpadding='0' cellspacing='0'>
<tr>
<td class='tbl2'>".$locale['461']."</td>
<td align='right' class='tbl2'>".$locale['462']."</td>
</tr>\n";
		while ($data = dbarray($result)) {
			echo "<tr>
<td>".($data['blacklist_ip'] ? $data['blacklist_ip'] : $data['blacklist_email'])."<br>
<span class='small2'>".$data['blacklist_reason']."</span></td>
<td align='right'><a href='".FUSION_SELF."?step=edit&blacklist_id=".$data['blacklist_id']."'>".$locale['463']."</a> -
<a href='".FUSION_SELF."?step=delete&blacklist_id=".$data['blacklist_id']."'>".$locale['464']."</a></td>
</tr>\n";
		}
		echo "</table>\n";
	} else {
		echo "<center><br>\n".$locale['465']."<br><br>\n</center>\n";
	}
	closetable();
}

echo "</td>\n";
require_once BASEDIR."footer.php";
?>