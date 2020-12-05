<?
/*
-------------------------------------------------------
	PHP-Fusion
	-----------------------------------------------
	© Nick Jones 2002-2004
	http://www.digitaldominion.co.uk
	nick@digitaldominion.co.uk
	-----------------------------------------------
	Released under the terms and conditions of the
	GNU General Public License (http://gnu.org).
-------------------------------------------------------
*/
@require "fusion_config.php";
require "header.php";
require "subheader.php";
require fusion_langdir."messages.php";

require "navigation.php";
require "sidebar.php";
echo "</td>
<td valign=\"top\" class=\"bodybg\">\n";

if ($userdata[user_name] != "") {
	$result = dbquery("SELECT * FROM ".$fusion_prefix."users WHERE user_id='$user_id'");
	$data = dbarray($result);
	if (isset($_POST['send_message'])) {
		$subject = stripinput($subject);
		$subject = trim(chop(str_replace("&nbsp;", "", $subject)));
		$message = stripinput($message);
		$message = trim(chop(str_replace("&nbsp;", "", $message)));
		if ($subject == "") {
			$error = "<span class=\"alt\">".LAN_252."</span><br>\n";
		}
		if ($message == "") {
			$error .= "<span class=\"alt\">".LAN_253."</span><br>\n";
		}
		if (!$error) {
			$presubject = $subject;
			$premessage = nl2br($message);
			$premessage = parsesmileys($premessage);
			$subject = addslashes($subject);
			$message = addslashes($message);
			$datesent = strftime($settings[shortdate], time()+($settings[timeoffset]*3600));
			$result = dbquery("INSERT INTO ".$fusion_prefix."messages VALUES('', '$data[user_id].$data[user_name]', '$userdata[user_id].$userdata[user_name]', '$subject', '$message', '0', '".time()."')");
			opentable(LAN_230);
			echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<tr>
<td><span class=\"alt\">".LAN_290."</span> $data[user_name]<br>
<span class=\"alt\">".LAN_291."</span> $userdata[user_name]<br>
<span class=\"alt\">".LAN_292."</span> $datesent<br>
<span class=\"alt\">".LAN_293."</span> $presubject<br><br>
<span class=\"alt\">".LAN_294."</span><br>
$premessage</td></tr>
<tr><td colspan=\"2\"><br><div align=\"center\">
".LAN_250."<br><br>
<a href=\"index.php\">".LAN_251."</a></div></td>
</tr>
</table>\n";
			closetable();
		} else {
			opentable(LAN_230);
			echo "<center><br>
".LAN_254."<br><br>
$error<br>
<a href=\"$PHP_SELF\">".LAN_255."</a>.<br><br>
</center>\n";
			closetable();
		}
	} else {
		if (isset($reply_id)) {
			$result2 = dbquery("SELECT * FROM ".$fusion_prefix."messages WHERE message_to='$userdata[user_id].$userdata[user_name]' and message_id='$reply_id'");
			$data2 = dbarray($result2);
			$recipient = explode(".", $data2[message_from]);
			if ($data2[message_subject] != "") {
				if (!strstr($data2[message_subject], "RE: ")) {
					$subject = "RE: ".$data2[message_subject];
				} else {
					$subject = $data2[message_subject];
				}
			}
		} else {
			$recipient = array($user_id, $data[user_name]);
		}
		opentable(LAN_230);
		echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<form name=\"userform\" method=\"post\" action=\"$PHP_SELF?user_id=$user_id\">
<tr>
<td><span class=\"alt\">".LAN_270."</span> $recipient[1]<br><br>
".LAN_271."<font color=\"red\">*</font><br>
<input type=\"textbox\" name=\"subject\" value=\"$subject\" maxlength=\"32\" class=\"textbox\" style=\"width:200px;\"><br><br>
".LAN_272."<font color=\"red\">*</font><br>
<textarea name=\"message\" rows=\"10\" class=\"textbox\" style=\"width:100%\"></textarea></td>
</tr>
<tr>
<td colspan=\"2\"><br><div align=\"center\">
<input type=\"submit\" name=\"send_message\" value=\"".LAN_273."\" class=\"button\"></div>
</td>
</tr>
</form>
</table>\n";
		closetable();
	}
} else {
	opentable(LAN_230);
	echo LAN_213."\n";
	closetable();
}

echo "</td>
<td width=\"170\" valign=\"top\" class=\"srborder\">\n";
require "userinfo.php";
require "poll.php";
require "shoutbox.php";
echo "</td>
</tr>
</table>\n";

require "footer.php";
?>