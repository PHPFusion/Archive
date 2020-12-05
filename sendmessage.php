<?
require "header.php";
require "subheader.php";

echo "<tr><td width=\"170\" valign=\"top\" class=\"sideborder\">\n";
require "navigation.php";
require "sidebar.php";
echo "</td><td valign=\"top\">\n";
if ($userdata[username] != "") {
	$result = dbquery("SELECT * FROM users WHERE userid='$user'");
	$data = dbarray($result);
	if (isset($_POST['sendmessage'])) {
		$subject = htmlentities($subject);
		$subject = str_replace("&nbsp;", "", $subject);
		$subject = trim($subject);
		$message = htmlentities($message);
		$message = str_replace("&nbsp;", "", $message);
		$message = trim($message);
		if ($subject == "") {
			$error = "&middot;&nbsp;<span class=\"alt2\">You must specify a Subject</span><br>\n";
		}
		if ($message == "") {
			$error .= "&middot;&nbsp;<span class=\"alt2\">You must specify a Message</span><br>\n";
		}
		if (!$error) {
			$presubject = stripslashes($subject);
			$premessage = nl2br(stripslashes($message));
			$subject = addslashes($subject);
			$message = addslashes($message);
			$datesent = gmdate("F d Y", $servertime)." at ".gmdate("H:i", $servertime);
			$result = dbquery("INSERT INTO messages VALUES('', '$data[userid]', '$userdata[userid]', '$subject', '$message', '0', '$servertime')");
			opentable("Send Private Message");
			echo "<table align=\"center\" width=\"450\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<tr><td><span class=\"alt\">To:</span> $data[username]<br>
<span class=\"alt\">From:</span> $userdata[username]<br>
<span class=\"alt\">Recieved:</span> $datesent<br>
<span class=\"alt\">Subject:</span> $presubject<br><br>
<span class=\"alt\">Message:</span><br>
$premessage</td></tr>
<tr><td colspan=\"2\"><br><div align=\"center\">
Your Message has been sent<br><br>
<a href=\"index.php\">Return to $settings[sitename] Home</a></div></td></tr>
</table>\n";
			closetable();
		} else {
			opentable("Send Private Message");
			echo "<table align=\"center\" width=\"400\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<tr><td>Your message was not sent for the following reason(s)<br><br>
$error<br>
Please try again.</td></tr>
</table>\n";
			closetable();
		}
	} else {
		if (isset($replyid)) {
			$result2 = dbquery("SELECT * FROM messages WHERE fromuser='$user' and mid='$replyid'");
			$data2 = dbarray($result2);
			if ($data2[subject] != "") {
				if (!strstr($data2[subject], "RE: ")) {
					$subject = "RE: ".stripslashes($data2[subject]);
				} else {
					$subject = stripslashes($data2[subject]);
				}
			}
		}
		opentable("Send Private Message");
		echo "<table align=\"center\" width=\"450\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<form name=\"userform\" method=\"post\" action=\"$PHP_SELF?user=$user\">
<tr><td><span class=\"alt\">To:</span> $data[username]<br><br>
Subject:<font color=\"red\">*&nbsp</font><br>
<input type=\"textbox\" name=\"subject\" value=\"$subject\" maxlength=\"32\" class=\"textbox\" style=\"width: 200px;\"><br><br>
Message:<font color=\"red\">*&nbsp</font><br>
<textarea name=\"message\" rows=\"10\" class=\"textbox\" style=\"width: 440px\"></textarea></td></tr>
<tr><td colspan=\"2\"><br><div align=\"center\">
<input type=\"submit\" name=\"sendmessage\" value=\"Send Message\" class=\"button\" style=\"width: 100px;\"></div>
</td></tr>
</form>
</table>\n";
		closetable();
	}
} else {
	opentable("Read Private Message");
	echo "Members Only\n";
	closetable();
}
echo "</td><td width=\"170\" valign=\"top\" class=\"sideborder\">\n";
require "userinfo.php";
require "showpoll.php";
require "shoutbox.php";
echo "</td></tr>
</table>\n";

require "footer.php";
?>