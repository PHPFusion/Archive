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
@require_once "fusion_config.php";
require_once "fusion_core.php";
require_once "subheader.php";
include FUSION_LANGUAGES.FUSION_LAN."messages.php";
require_once "side_left.php";

if (iMEMBER) {
	if (isset($_POST['del_sel'])) {
		if (is_array($_POST['del_message'])) {
			foreach($_POST['del_message'] as $key=>$message_id) {
				$result = dbquery("DELETE FROM ".$fusion_prefix."messages WHERE message_id='$message_id' AND message_to='".$userdata['user_id']."'");
			}
		}
	}
	if (isset($_POST['del_all'])) {
		$result = dbquery("DELETE FROM ".$fusion_prefix."messages WHERE message_to='".$userdata['user_id']."' AND message_read='1'");
	}
	if (!isset($step)) {
		opentable(LAN_400);
		$result = dbquery(
			"SELECT * FROM ".$fusion_prefix."messages INNER JOIN ".$fusion_prefix."users ON
			".$fusion_prefix."messages.message_from=".$fusion_prefix."users.user_id
			WHERE message_to='".$userdata['user_id']."' ORDER BY message_datestamp DESC"
		);
		if (dbrows($result) != 0) {
			$i = 0;
			echo "<form name='message_form' method='post' action='".FUSION_SELF."'>
<table width='100%' cellpadding='0' cellspacing='1' class='tbl-border'>
<tr>
<td width='20' class='tbl2'></td>
<td class='tbl2'>".LAN_401."</td>
<td class='tbl2'>".LAN_402."</td>
<td width='110' class='tbl2'>".LAN_403."</td>
</tr>\n";
			while ($data = dbarray($result)) {
				if ($i % 2 == 0) { $row_color = "tbl1"; } else { $row_color = "tbl2"; }
				echo "<tr>\n<td class='$row_color'><input type='checkbox' name='del_message[$i]' value='".$data['message_id']."'></td>\n";
				echo "<td class='$row_color'><a href='messages.php?step=read&message_id=".$data['message_id']."'>";
				if ($data['message_read'] != 1) {
					echo "<b>".$data['message_subject']."</b>";
				} else {
					echo $data['message_subject'];
				}
				echo "</a></td>
<td class='$row_color'><a href='profile.php?lookup=$data[message_from]'>".$data['user_name']."</a></td>
<td class='$row_color'>".showdate("shortdate", $data['message_datestamp'])."</td>
</tr>\n";
				$i++;
			}
			echo "</table>
<center><br>
<input type='submit' name='del_sel' value='".LAN_404."' class='button' OnClick='return DeleteSel();'>
<input type='submit' name='del_all' value='".LAN_405."' class='button' OnClick='return DeleteAll();'>
</center>
</form>\n";
		} else {
			echo "<center><br>
".LAN_410."<br><br>
</center>\n";
		}
		closetable();
		echo "<script>
function DeleteSel() {
	return confirm('".LAN_411."');
}
function DeleteAll() {
	return confirm('".LAN_412."');
}
</script>\n";
	} elseif ($step == "read") {
		if (!isset($message_id) || !isNum($message_id)) { header("Location: index.php"); exit; }
		$result = dbquery(
			"SELECT * FROM ".$fusion_prefix."messages INNER JOIN ".$fusion_prefix."users
			ON ".$fusion_prefix."messages.message_from=".$fusion_prefix."users.user_id
			WHERE message_to='".$userdata['user_id']."' AND message_id='$message_id'"
		);
		if (dbrows($result) != 0) {
			$data = dbarray($result);
			$message = nl2br($data['message_message']);
			if ($data['message_smileys'] == "y") $message = parsesmileys($message);
			$message = parseubb($message);
			if ($data['message_read'] == 0) {
				$result = dbquery("UPDATE ".$fusion_prefix."messages SET message_read='1' WHERE message_id='$message_id'");
			}
			opentable(LAN_510);
			echo "<table width='100%' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td><span class='alt'>".LAN_490."</span> ".$userdata['user_name']."<br>
<span class='alt'>".LAN_491."</span> ".$data['user_name']."<br>
<span class='alt'>".LAN_492."</span> ".showdate("shortdate", $data['message_datestamp'])."<br>
<span class='alt'>".LAN_493."</span> ".$data['message_subject']."<br><br>
<span class='alt'>".LAN_494."</span><br>
$message</td>
</tr>
<tr>
<td align='center' colspan='2'><br>
<a href='messages.php?step=send&user_id=".$data['message_from']."&reply_id=".$data['message_id']."'>".LAN_520."</a><br><br>
<a href='messages.php'>".LAN_521."</a></td>
</tr>
</table>\n";
			closetable();
		}
	} elseif ($step == "send") {
		if ($userdata['user_id'] == $user_id || !$user_id || !isNum($user_id)) { header("Location: index.php"); exit; }
		$result = dbquery("SELECT * FROM ".$fusion_prefix."users WHERE user_id='$user_id'");
		if (!dbrows($result)) { header("Location:index.php"); exit; }
		$data = dbarray($result);
		if (isset($_POST['send_message'])) {
			$error = "";
			$subject = stripinput($_POST['subject']);
			$subject = trim(chop(str_replace("&nbsp;", "", $subject)));
			$message = stripinput($_POST['message']);
			$message = trim(chop(str_replace("&nbsp;", "", $message)));
			if ($subject == "") $error .= "<span class='alt'>".LAN_452."</span><br>\n";
			if ($message == "") $error .= "<span class='alt'>".LAN_453."</span><br>\n";
			if ($error == "") {
				$presubject = $subject;
				$premessage = nl2br($message);
				$smileys = isset($_POST['disable_smileys']) ? "n" : "y";
				$premessage = parseubb($premessage);
				$subject = addslashes($subject);
				$message = addslashes($message);
				$result = dbquery("INSERT INTO ".$fusion_prefix."messages VALUES('', '".$data['user_id']."', '".$userdata['user_id']."', '$subject', '$message', '$smileys', '0', '".time()."')");
				opentable(LAN_430);
				echo "<table width='100%' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td><span class='alt'>".LAN_490."</span> ".$data['user_name']."<br>
<span class='alt'>".LAN_491."</span> ".$userdata['user_name']."<br>
<span class='alt'>".LAN_492."</span> ".showdate("shortdate", time())."<br>
<span class='alt'>".LAN_493."</span> $presubject<br><br>
<span class='alt'>".LAN_494."</span><br>
".($smileys == "y" ? parsesmileys($premessage) : $premessage)."</td></tr>
<tr><td colspan='2'><br><div align='center'>
".LAN_450."<br><br>
<a href='index.php'>".LAN_451."</a></div></td>
</tr>
</table>\n";
				closetable();
			} else {
				opentable(LAN_430);
				echo "<center><br>
	".LAN_454."<br><br>
	$error<br>
	<a href='".FUSION_SELF."'>".LAN_455."</a>.<br><br>
	</center>\n";
				closetable();
			}
		} else {
			if (isset($reply_id)) {
				$result2 = dbquery(
					"SELECT * FROM ".$fusion_prefix."messages INNER JOIN ".$fusion_prefix."users
					ON ".$fusion_prefix."messages.message_from=".$fusion_prefix."users.user_id
					WHERE message_to='$userdata[user_id]' and message_id='$reply_id'"
				);
				$data2 = dbarray($result2);
				$recipient = $data2['user_name'];
				if ($data2['message_subject'] != "") {
					if (!strstr($data2['message_subject'], "RE: ")) {
						$subject = "RE: ".$data2['message_subject'];
					} else {
						$subject = $data2['message_subject'];
					}
				}
			} else {
				$recipient = $data['user_name'];
			}
			opentable(LAN_430);
			echo "<form name='inputform' method='post' action='".FUSION_SELF."?step=send&user_id=$user_id'>
<table align='center' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td><span class='alt'>".LAN_470."</span> $recipient<br><br>
".LAN_471."<span style='color:#ff0000'>*</span><br>
<input type='text' name='subject' value='".(isset($subject) ? $subject : "")."' maxlength='32' class='textbox' style='width:200px;'><br><br>
".LAN_472."<span style='color:#ff0000'>*</span><br>
<textarea name='message' cols='80' rows='10' class='textbox' style='width:400px'></textarea><br>
<center>
<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onClick=\"addText('message', '[b]', '[/b]');\">
<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onClick=\"addText('message', '[i]', '[/i]');\">
<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onClick=\"addText('message', '[u]', '[/u]');\">
<input type='button' value='url' class='button' style='width:30px;' onClick=\"addText('message', '[url]', '[/url]');\">
<input type='button' value='mail' class='button' style='width:35px;' onClick=\"addText('message', '[mail]', '[/mail]');\">
<input type='button' value='img' class='button' style='width:30px;' onClick=\"addText('message', '[img]', '[/img]');\">
<input type='button' value='center' class='button' style='width:45px;' onClick=\"addText('message', '[center]', '[/center]');\">
<input type='button' value='small' class='button' style='width:40px;' onClick=\"addText('message', '[small]', '[/small]');\">
<input type='button' value='code' class='button' style='width:40px;' onClick=\"addText('message', '[code]', '[/code]');\">
<input type='button' value='quote' class='button' style='width:45px;' onClick=\"addText('message', '[quote]', '[/quote]');\">
<br><br>
".displaysmileys("message")."
<br><br>
<input type='checkbox' name='disable_smileys' value='y'>".LAN_474."
<br><br>
<input type='submit' name='send_message' value='".LAN_473."' class='button'>
</center>
</td>
</tr>
</table>
</form>\n";
			closetable();
		}
	}
} else {
	opentable(LAN_400);
	echo LAN_413."\n";
	closetable();
}

require_once "side_right.php";
require_once "footer.php";
?>