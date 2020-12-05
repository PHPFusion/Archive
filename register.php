<?
require "header.php";
require "subheader.php";

echo "<tr><td width=\"170\" valign=\"top\" class=\"sideborder\">\n";
require "navigation.php";
require "sidebar.php";
echo "</td><td valign=\"top\">\n";
if (isset($HTTP_POST_VARS['register'])) {
	$username = stripinput($username);
	$password1 = stripinput($password1);
	$password2 = stripinput($password2);
	$email = stripinput($email);
	$icq = stripinput($icq);
	$yahoo = stripinput($yahoo);
	$msn = stripinput($msn);
	if ($username != "") {
		$result = dbquery("SELECT * FROM users WHERE username='$username'");
		if (dbrows($result) != 0) {
			$error = "Username in use.<br>";
		}
	} else {
		$error .= "You did not specify a Username.<br>";
	}
	if ($password1 != "") {
		if ($password1 != $password2) {
			$error .= "Passwords do not match.<br>";
		}
	} else {
		$error .= "you did not specify a password.<br>";
	}
	if ($email == "") {
		$error .= "You did not specify your Email Address.<br>";
	}
	if ($error == "") {
		$message = "<html>
<head>
<title>$settings[sitename]</title>
<style type=\"text/css\">
<!--
.alt1 {color: #7878c8;}
.alt2 {color: #ffbb22;}
a {color: #7878c8; text-decoration: none;}
a:hover {color: #7878c8; text-decoration: underline;}
body {font-family: Verdana, Tahoma, Arial, sans-serif; font-size: 8pt;}
-->
</style>
</head>
<body bgcolor=\"#000000\" text=\"#ffffff\">
<center><span class=\"alt2\">$settings[sitename]</span></center>
<p>Hello $username,<br>
<br>
Welcome to the community, here are your login details:<br>
<br>
<span class=\"alt2\">Username:</span> <span class=\"alt1\">$username</span><br>
<span class=\"alt2\">Password:</span> <span class=\"alt1\">$password1</span><br>
<br>
Please keep note of these details as we cannot send you
a forgotten password due to the encryption method used.<br>
<br>
Thank you.<br>
<a href=\"mailto:$settings[siteemail]\">$settings[siteusername]</a>.
</body>
</html>";
		$to = "$username <$email>";
		$subject = "Welcome to $settings[sitename]";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
		$headers .= "From: $settings[sitename] <$settings[siteemail]>\r\n";
		$headers .= "X-Sender: <$settings[siteemail]>\r\n";
		$headers .= "X-Mailer: PHP\r\n";
		$headers .= "X-Priority: 3\r\n";
		$headers .= "Return-Path: <$settings[siteemail]>";
		mail($to, $subject, $message, $headers);
		$result = dbquery("INSERT INTO users VALUES(md5('$username'), '$username', md5('$password1'), '$email', '$location', '$icq', '$msn', '$yahoo', '$web', '', '0', '$servertime', '0', 'Member')");
		opentable("Registration Successful");
		echo "<br><div align=\"center\">Your registration was succussful and your details have been Emailed</div></br>\n";
		closetable();
	} else {
		opentable("Registration Failed");
		echo "<br><div align=\"center\">Registration failed for the following reason(s):<br><br>
$error<br>
<a href=\"$PHP_SELF\">Please Try Again</a></div></br>\n";
		closetable();
	}
} else {
	opentable("Register");
	echo "<table align=\"center\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<form name=\"registerform\" method=\"post\" action=\"$PHP_SELF\">
<tr><td colspan=\"2\">Become a Member of $settings[sitename] by filling in
this form. Registration is completely free and provides extra options including:<br>
<br>
· Post Comments on any News item or Article.<br>
· Interact with other members in our Forums.<br>
· Cast your vote on our regularly updated Polls.<br>
· Send and Recieve Private Messages.<br>
<br>
Sign up now and become part of a growing community of PC users. Personal Information is kept
strictly confidential, we do not sell or pass on any information to other parties. Please do
not use special characters in any of the following fields.<br><br>
</td></tr>
<tr><td>Username:<font color=\"red\">*</font></td>
<td><input type=\"textbox\" name=\"username\" maxlength=\"32\" class=\"textbox\" style=\"width: 250px;\"></td></tr>
<tr><td>Password:<font color=\"red\">*</font></td>
<td><input type=\"password\" name=\"password1\" maxlength=\"32\" class=\"textbox\" style=\"width: 250px;\"></td></tr>
<tr><td>Repeat Password:<font color=\"red\">*</font></td>
<td><input type=\"password\" name=\"password2\" maxlength=\"32\" class=\"textbox\" style=\"width: 250px;\"></td></tr>
<tr><td>Email Address:<font color=\"red\">*</font></td>
<td><input type=\"textbox\" name=\"email\" maxlength=\"64\" class=\"textbox\" style=\"width: 250px;\"></td></tr>
<tr><td>Location:</td>
<td><input type=\"textbox\" name=\"location\" maxlength=\"32\" class=\"textbox\" style=\"width: 250px;\"></td></tr>
<tr><td>ICQ Number:</td>
<td><input type=\"textbox\" name=\"icq\" maxlength=\"12\" class=\"textbox\" style=\"width: 250px;\"></td></tr>
<tr><td>MSN Address:</td>
<td><input type=\"textbox\" name=\"msn\" maxlength=\"64\" class=\"textbox\" style=\"width: 250px;\"></td></tr>
<tr><td>Yahoo ID:</td>
<td><input type=\"textbox\" name=\"yahoo\" maxlength=\"64\" class=\"textbox\" style=\"width: 250px;\"></td></tr>
<tr><td>Web Address:</td>
<td><input type=\"textbox\" name=\"web\" maxlength=\"64\" class=\"textbox\" style=\"width: 250px;\"></td></tr>
<tr><td colspan=\"2\"><div align=\"center\"><br>
<input type=\"submit\" name=\"register\" value=\"Register\" class=\"button\" style=\"width: 100px;\"></div>
</td></tr>
</form>
</table>";
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