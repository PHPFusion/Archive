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
require fusion_langdir."lostpassword.php";

require "navigation.php";
require "sidebar.php";
echo "</td>
<td valign=\"top\" class=\"bodybg\">\n";

opentable(LAN_200);
if (isset($_POST['send_password'])) {
	$result = dbquery("SELECT * FROM ".$fusion_prefix."users WHERE user_email='$email'");
	if (dbrows($result) != 0) {
		$data = dbarray($result);
		for ($i=0;$i<=7;$i++) {
			$newpass .= chr(rand(97, 122));
		}
		$message = "<html>
<head>
<title>$settings[sitename]</title>
<style type=\"text/css\">
<!--
.alt {color:#550000;}
a {color:#000055;text-decoration:none;}
a:hover {color:#000055;text-decoration:underline;}
body {font-family:Verdana,Tahoma,Arial,sans-serif;font-size:10px;}
-->
</style>
</head>
<body bgcolor=\"#ffffff\" text=\"#000000\">
".LAN_201."<br>
<a href=\"mailto:$settings[siteemail]\">$settings[siteusername]</a>.
</body>
</html>";
		$to = "$data[user_name] <$email>";
		$subject = "New Password for $settings[sitename]";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
		$headers .= "From: $settings[sitename] <$settings[siteemail]>\r\n";
		$headers .= "X-Sender: <$settings[siteemail]>\r\n";
		$headers .= "X-Mailer: PHP\r\n";
		$headers .= "X-Priority: 3\r\n";
		$headers .= "Return-Path: <$settings[siteemail]>";
		mail($to, $subject, $message, $headers);
		$result = dbquery("UPDATE ".$fusion_prefix."users SET user_password=md5('$newpass') WHERE user_id='$data[user_id]'");
		echo "<center><br>
".LAN_202."<br><br>
<a href=\"index.php\">".LAN_203."</a><br><br>
</center>\n";
	} else {
		echo "<center><br>
".LAN_204."<br>
".LAN_205."<br>
<br>
<a href=\"index.php\">".LAN_203."</a><br><br>
</center>\n";
	}
} else {
	echo "<form name=\"passwordform\" method=\"post\" action=\"$PHP_SELF\">
<center>".LAN_206."<br>
<br>
<input type=\"textbox\" name=\"email\" class=\"textbox\" maxlength=\"100\" style=\"width:200px;\"><br>
<br>
<input type=\"submit\" name=\"send_password\" value=\"".LAN_207."\" class=\"button\" style=\"width:100px;\"></center>
</form>\n";
}
closetable();

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