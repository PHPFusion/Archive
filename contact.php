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
@require "fusion_config.php";
require "header.php";
require "subheader.php";
require fusion_langdir."contact.php";
require "side_left.php";

if (isset($_POST[sendmessage])) {
	$mailname = stripinput($mailname);
	$email = stripinput($email);
	$subject = stripinput($subject);
	$message = stripinput($message);
	if ($mailname == "") {
		$error .= "· <span class=\"alt\">".LAN_420."</span><br>\n";
	}
	if ($email == "") {
		$error .= "· <span class=\"alt\">".LAN_421."</span><br>\n";
	}
	if ($subject == "") {
		$error .= "· <span class=\"alt\">".LAN_422."</span><br>\n";
	}
	if ($message == "") {
		$error .= "· <span class=\"alt\">".LAN_423."</span><br>\n";
	}
	if (!$error) {
		$mailname = stripslashes($mailname);
		$subject = stripslashes($subject);
		$message = stripslashes($message);
		$to = "$settings[sitename] <".$settings[siteemail].">";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/plain\r\n";
		$headers .= "From: $mailname <".$email.">\r\n";
		$headers .= "X-Sender: <".$email.">\r\n";
		$headers .= "X-Mailer: PHP\r\n";
		$headers .= "X-Priority: 3\r\n";
		$headers .= "Return-Path: <".$email.">";
		mail($to, $subject, $message, $headers);
		opentable(LAN_400);
		echo "<center><br>
".LAN_440."<br><br>
".LAN_441."</center><br>\n";
		closetable();
	} else {
		opentable(LAN_400);
		echo "<center><br>
".LAN_442."<br><br>
$error<br>
".LAN_443."</center><br>\n";
		closetable();
	}
} else {
	opentable(LAN_400);
	echo LAN_401."<br><br>
<form name=\"userform\" method=\"post\" action=\"$PHP_SELF\">
<table align=\"center\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<tr>
<td width=\"90\">".LAN_402."</td>
<td><input type=\"text\" name=\"mailname\" maxlength=\"64\" class=\"textbox\" style=\"width: 200px;\"></td>
</tr>
<tr>
<td width=\"90\">".LAN_403."</td>
<td><input type=\"text\" name=\"email\" maxlength=\"128\" class=\"textbox\" style=\"width: 200px;\"></td>
</tr>
<tr>
<td width=\"90\">".LAN_404."</td>
<td><input type=\"text\" name=\"subject\" maxlength=\"32\" class=\"textbox\" style=\"width: 200px;\"></td>
</tr>
<tr><td valign=\"top\" width=\"90\">".LAN_405."</td>
<td><textarea name=\"message\" rows=\"10\" class=\"textbox\" style=\"width: 320px\"></textarea></td>
</tr>
<tr>
<td colspan=\"2\"><div align=\"center\">
<input type=\"submit\" name=\"sendmessage\" value=\"".LAN_406."\" class=\"button\"></div>
</td>
</tr>
</table>
</form>\n";
	closetable();
}

require "side_right.php";
require "footer.php";
?>