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
require "header.php";
require "subheader.php";
require fusion_langdir."contact.php";

require "navigation.php";
require "sidebar.php";
echo "</td>
<td valign=\"top\" class=\"bodybg\">\n";

if (isset($_POST[sendmessage])) {
	$mailname = stripinput($mailname);
	$email = stripinput($email);
	$subject = stripinput($subject);
	$message = stripinput($message);
	if ($mailname == "") {
		$error .= "· <span class=\"alt\">".LAN_220."</span><br>\n";
	}
	if ($email == "") {
		$error .= "· <span class=\"alt\">".LAN_221."</span><br>\n";
	}
	if ($subject == "") {
		$error .= "· <span class=\"alt\">".LAN_222."</span><br>\n";
	}
	if ($message == "") {
		$error .= "· <span class=\"alt\">".LAN_223."</span><br>\n";
	}
	if (!$error) {
		$mailname = stripslashes($mailname);
		$subject = stripslashes($subject);
		$message = nl2br(stripslashes($message));
		$to = "$settings[siteemail] <$settings[siteemail]>";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
		$headers .= "From: $mailname <$email>\r\n";
		$headers .= "X-Sender: <$email>\r\n";
		$headers .= "X-Mailer: PHP\r\n";
		$headers .= "X-Priority: 3\r\n";
		$headers .= "Return-Path: <$email>";
		mail($to, $subject, $message, $headers);
		opentable(LAN_200);
		echo "<center><br>
".LAN_240."<br><br>
".LAN_241."</center><br>\n";
		closetable();
	} else {
		opentable(LAN_200);
		echo "<center><br>
".LAN_242."<br><br>
$error<br>
".LAN_243."</center><br>\n";
		closetable();
	}
} else {
	opentable(LAN_200);
	echo LAN_201."<br><br>
<table align=\"center\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<form name=\"userform\" method=\"post\" action=\"$PHP_SELF\">
<tr>
<td width=\"90\">".LAN_202."</td>
<td><input type=\"textbox\" name=\"mailname\" maxlength=\"64\" class=\"textbox\" style=\"width: 200px;\"></td>
</tr>
<tr>
<td width=\"90\">".LAN_203."</td>
<td><input type=\"textbox\" name=\"email\" maxlength=\"128\" class=\"textbox\" style=\"width: 200px;\"></td>
</tr>
<tr>
<td width=\"90\">".LAN_204."</td>
<td><input type=\"textbox\" name=\"subject\" maxlength=\"32\" class=\"textbox\" style=\"width: 200px;\"></td>
</tr>
<tr><td valign=\"top\" width=\"90\">".LAN_205."</td>
<td><textarea name=\"message\" rows=\"10\" class=\"textbox\" style=\"width: 320px\"></textarea></td>
</tr>
<tr>
<td colspan=\"2\"><div align=\"center\">
<input type=\"submit\" name=\"sendmessage\" value=\"".LAN_206."\" class=\"button\" style=\"width: 100px;\"></div>
</td>
</tr>
</form>
</table>\n";
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