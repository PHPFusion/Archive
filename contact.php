<?
require "header.php";
require "subheader.php";

echo "<tr><td width=\"170\" valign=\"top\" class=\"sideborder\">\n";
require "navigation.php";
require "sidebar.php";
echo "</td><td valign=\"top\">\n";
if (isset($_POST[sendmessage])) {
	$name = stripinput($name);
	$email = stripinput($email);
	$subject = stripinput($subject);
	$message = stripinput($message);
	if ($name == "") {
		$error = "&middot;&nbsp;<span class=\"alt2\">You must specify a Name</span><br>\n";
	}
	if ($email == "") {
		$error = "&middot;&nbsp;<span class=\"alt2\">You must specify an Email Address</span><br>\n";
	}
	if ($subject == "") {
		$error = "&middot;&nbsp;<span class=\"alt2\">You must specify a Subject</span><br>\n";
	}
	if ($message == "") {
		$error .= "&middot;&nbsp;<span class=\"alt2\">You must specify a Message</span><br>\n";
	}
	if (!$error) {
		$name = stripslashes($name);
		$subject = stripslashes($subject);
		$message = nl2br(stripslashes($message));
		$to = "$settings[siteemail] <$settings[siteemail]>";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
		$headers .= "From: $name <$email>\r\n";
		$headers .= "X-Sender: <$email>\r\n";
		$headers .= "X-Mailer: PHP\r\n";
		$headers .= "X-Priority: 3\r\n";
		$headers .= "Return-Path: <$email>";
		mail($to, $subject, $message, $headers);
		opentable("Contact Me");
		echo "<div align=\"center\"><br>
Your Message has been sent<br><br>
Thank You</div><br>\n";
		closetable();
	} else {
		opentable("Contact Me", "content");
		echo "<table align=\"center\" width=\"400\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<tr><td>Your message was not sent for the following reason(s)<br><br>
$error<br>
Please try again.</td></tr>
</table>\n";
		closetable();
	}
} else {
	opentable("Contact Me");
	echo "There are many ways you can contact me, you can Email me diectly
at <a href=\"mailto:$settings[siteemail]\">$settings[siteemail]</a>. If you are a Member you can
send me a <a href=\"sendmessage.php?user=fd144f65f19df1c7adaa116c44ce2617\">Private Message</a>.
Alternatively, you can fill in the form on this page, this sends your message to me via Email.<br><br>
<table align=\"center\" width=\"450\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<form name=\"userform\" method=\"post\" action=\"$PHP_SELF\">
<tr><td width=\"90\">Name:&nbsp</td>
<td><input type=\"textbox\" name=\"name\" maxlength=\"64\" class=\"textbox\" style=\"width: 200px;\"></td></tr>
<tr><td width=\"90\">Email Address:&nbsp</td>
<td><input type=\"textbox\" name=\"email\" maxlength=\"128\" class=\"textbox\" style=\"width: 200px;\"></td></tr>
<tr><td width=\"90\">Subject:&nbsp</td>
<td><input type=\"textbox\" name=\"subject\" maxlength=\"32\" class=\"textbox\" style=\"width: 200px;\"></td></tr>
<tr><td valign=\"top\" width=\"90\">Message:&nbsp</td>
<td><textarea name=\"message\" rows=\"10\" class=\"textbox\" style=\"width: 320px\"></textarea></td></tr>
<tr><td colspan=\"2\"><div align=\"center\">
<input type=\"submit\" name=\"sendmessage\" value=\"Send Message\" class=\"button\" style=\"width: 100px;\"></div>
</td></tr>
</form>
</table>\n";
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