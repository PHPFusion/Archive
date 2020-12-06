<?php
/*---------------------------------------------------+
| PHP-Fusion 6 Content Management System
+----------------------------------------------------+
| Copyright © 2002 - 2005 Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the
| GNU General Public License. For details refer to
| the included gpl.txt file or visit http://gnu.org
+----------------------------------------------------*/
require_once "maincore.php";
require_once "subheader.php";
require_once "side_left.php";

include LOCALE.LOCALESET."guestbook.php";

if (!isset($action)) $action = "";

if ($action == "delete") {
	if (iADMIN) {
		$result = dbquery("DELETE FROM ".$db_prefix."guestbook WHERE guestbook_id='$guestbook_id'");
		redirect(FUSION_SELF);
	}
}
if (isset($_POST['guest_submit'])) {
	if ($_POST['guest_name'] != "" && $_POST['guest_email'] != "" && $_POST['guest_message'] != "") {
		$guest_name = trim(stripinput($_POST['guest_name']));
		$guest_email = trim(stripinput($_POST['guest_email']));
		$guest_weburl = trim(stripinput($_POST['guest_weburl']));
		$guest_webtitle = trim(stripinput($_POST['guest_webtitle']));
		$guest_message = trim(stripinput($_POST['guest_message']));
		if ($guest_name != "" && $guest_message != "") {
			if ($action == "edit") {
				if (iADMIN) {
					$result = dbquery("UPDATE ".$db_prefix."guestbook SET guestbook_name='$guest_name', guestbook_email='$guest_email', guestbook_weburl='$guest_weburl', guestbook_webtitle='$guest_webtitle', guestbook_message='$guest_message' WHERE guestbook_id='$guestbook_id'");
				}
			} else {
					$result = dbquery("INSERT INTO ".$db_prefix."guestbook VALUES('', '$guest_name', '$guest_email', '$guest_weburl', '$guest_webtitle', '$guest_message', '".time()."', '".USER_IP."')");
			}
		}
		redirect(FUSION_SELF);
	}
}
if ($action == "edit") {
	if (iADMIN) {
		$result = dbquery("SELECT * FROM ".$db_prefix."guestbook WHERE guestbook_id='$guestbook_id'");
		$data = dbarray($result);
		$guestbook_name = $data['guestbook_name'];
		$guestbook_email = $data['guestbook_email'];
		$guestbook_weburl = $data['guestbook_weburl'];
		$guestbook_webtitle = $data['guestbook_webtitle'];
		$guestbook_message = $data['guestbook_message'];
		$formaction = FUSION_SELF."?action=edit&amp;guestbook_id=$guestbook_id";
	}
} else {
	$guestbook_name = "";
	$guestbook_email = "";
	$guestbook_weburl = "";
	$guestbook_webtitle = "";
	$guestbook_message = "";
	$formaction = FUSION_SELF;
}
opentable($locale['400']);
echo "<form name='inputform' method='post' action=$formaction>
<table align='center' cellpadding='0' cellspacing='0'>
<tr>
<td align='right' class='tbl'>".$locale['401']."</td>
<td class='tbl'><input type='text' name='guest_name' value='$guestbook_name' class='textbox' style='width:200px'></td>
</tr>
<tr>
<td align='right' class='tbl'>".$locale['402']."</td>
<td class='tbl'><input type='text' name='guest_email' value='$guestbook_email' class='textbox' style='width:200px'></td>
</tr>
<tr>
<td align='right' class='tbl'>".$locale['403']."</td>
<td class='tbl'><input type='text' name='guest_weburl' value='$guestbook_weburl' class='textbox' style='width:200px'></td>
</tr>
<tr>
<td align='right' class='tbl'>".$locale['404']."</td>
<td class='tbl'><input type='text' name='guest_webtitle' value='$guestbook_webtitle' class='textbox' style='width:200px'></td>
</tr>
<tr>
<td align='right' valign='top' class='tbl'>".$locale['405']."</td>
<td class='tbl'><textarea name='guest_message' rows='5' class='textbox' style='width:250px'>$guestbook_message</textarea><br><br>
".displaysmileys("guest_message")."
</td>
</tr>
<tr>
<td class='tbl'></td>
<td class='tbl'><input type='submit' name='guest_submit' value='".$locale['406']."' class='button'>
<input type='reset' name='guest_reset' value='".$locale['407']."' class='button'></td>
</tr>
</table>
</form>\n";
closetable();
tablebreak();
opentable($locale['420']);
$result = dbquery("SELECT * FROM ".$db_prefix."guestbook");
$rows = dbrows($result);
if (!isset($rowstart) || !isNum($rowstart)) $rowstart = 0;
if ($rows != 0) {
	tablebreak();
	$i = 1;
	$result = dbquery("SELECT * FROM ".$db_prefix."guestbook ORDER BY guestbook_datestamp DESC LIMIT $rowstart,10");
	$numrows = dbrows($result);
	while ($data = dbarray($result)) {
		if ($data['guestbook_weburl']) {
			$weburl = str_replace("http://", "", $data['guestbook_weburl']);
			$web = "\n | <a href='http://$weburl' target='_blank'>";
			if ($data['guestbook_webtitle']) { 
				$web .= $data['guestbook_webtitle']."</a>\n";
			} else {
				$web .= $weburl."</a>\n";
			}
		} else {
			$web = "";
		}
		echo "<table align='center' cellpadding='0' cellspacing='1' width='80%' class='tbl-border'>
<tr>
<td class='tbl2'>
<table cellpadding='0' cellspacing='0' width='100%'>
<tr>
<td class='small'><b><a href='mailto:".$data['guestbook_email']."'>".$data['guestbook_name']."</a></b>$web</td>\n";
		if (iADMIN && $data['guestbook_ip'] != "0.0.0.0") echo "<td align='right' class='small'>IP: ".$data['guestbook_ip']."</td>\n";
		echo "</tr>
</table>
</td>
</tr>
<tr>
<td class='tbl1'>".parsesmileys(parseubb($data['guestbook_message']))."</td>
</tr>
<tr>
<td class='tbl2'>
<table cellpadding='0' cellspacing='0' width='100%'>
<tr>
<td class='small'>
".showdate("longdate", $data['guestbook_datestamp'])."</td>\n";
		if (iADMIN) {
			echo "<td align='right' class='small'><a href='".FUSION_SELF."?action=edit&amp;guestbook_id=".$data['guestbook_id']."'>".$locale['421']."</a> |
<a href='".FUSION_SELF."?action=delete&amp;guestbook_id=".$data['guestbook_id']."' onClick='return DeleteMessage();'>".$locale['422']."</a></td>\n";
		}
		echo "</tr>
</table>
</td>
</tr>
</table>\n";
		if ($i != $numrows) echo "<br>\n";
		$i++;
	}
	tablebreak();
} else {
	echo "<center><br>\n".$locale['423']."\n<br><br></center>\n";
}
closetable();

if ($rows != 0) echo "<div align='center' style='margin-top:5px;'>\n".makePageNav($rowstart,10,$rows,3,FUSION_SELF."?")."\n</div>\n";

echo "<script>
function DeleteMessage() {
	return confirm(\"".$locale['424']."\");
}
</script>\n";

require_once "side_right.php";
require_once "footer.php";
?>