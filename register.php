<?
/*
-------------------------------------------------------
	PHP-Fusion
	-----------------------------------------------
	� Nick Jones 2002-2004
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
require fusion_langdir."register.php";

require "navigation.php";
require "sidebar.php";
echo "</td>
<td valign=\"top\" class=\"bodybg\">\n";

$show_image = "on"; // Set to "off" to disable.
$send_mail = "on"; // Set to "off" to disable.

if (isset($_POST['register'])) {
	$username = trim(chop(str_replace("&nbsp;", "", $_POST['username'])));
	if ($username == "" || $_POST['password'] == "" || $_POST['email'] == "") {
		$error .= LAN_210."<br>\n";
	}
	if (!preg_match("/^[-0-9A-Z@\s]+$/i", $username)) {
		$error .= LAN_211."<br>\n";
	}
	if (preg_match("/^[0-9A-Z]+$/i", $_POST['password'])) {
		if ($_POST['password2'] != $_POST['password']) {
			$error .= LAN_212."<br>\n";
		}
	} else {
		$error .= LAN_213."<br>\n";
	}
	if (!preg_match("/^[-0-9A-Z_\.]+@([-0-9A-Z_\.]+\.)+([0-9A-Z]){2,4}$/i", $_POST['email'])) {
		$error .= LAN_214."<br>\n";
	}
	$result = dbquery("SELECT * FROM ".$fusion_prefix."temp WHERE temp_dec='".$_POST['user_code']."'");
	if (dbrows($result) == 0) {
		$error .= LAN_215."<br>\n";
	}
	if ($_POST['icq']) {
		if (!preg_match("/^[0-9]+$/i", $_POST['icq'])) {
			$error .= LAN_216."<br>\n";
		}
	}
	if ($_POST['msn']) {
		if (!preg_match("/^[-0-9A-Z_\.]+@([-0-9A-Z_\.]+\.)+([0-9A-Z]){2,4}$/i", $_POST['msn'])) {
			$error .= LAN_217."<br>\n";
		}
	}
	if ($_POST['yahoo']) {
		if (!preg_match("/^[_0-9A-Z]+$/i", $_POST['yahoo'])) {
			$error .= LAN_218."<br>\n";
		}
	}
	$_POST['location'] = stripinput($_POST['location']);
	$_POST['web'] = stripinput($_POST['web']);
	$_POST['signature'] = stripinput($_POST['signature']);
	$result = dbquery("SELECT * FROM ".$fusion_prefix."users WHERE user_name='$username'");
	if (dbrows($result) != 0) {
		$error = LAN_219."<br>";
	}
	$result = dbquery("SELECT * FROM ".$fusion_prefix."users WHERE user_email='".$_POST['email']."'");
	if (dbrows($result) != 0) {
		$error = LAN_220."<br>";
	}
	if ($error == "") {
		if ($send_mail == "on") {
			$message = "<html>
<head>
<title>$settings[sitename]</title>
<style type=\"text/css\">
<!--
.alt {color:#000066;}
a {color:#000066;text-decoration:none;}
a:hover {color:#000066;text-decoration:underline;}
body {font-family:Verdana,Tahoma,Arial,sans-serif;font-size:12px;}
-->
</style>
</head>
<body bgcolor=\"#ffffff\" text=\"#000000\">
".LAN_230."
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
			if (mail($to, $subject, $message, $headers)) {
				$result = dbquery("INSERT INTO ".$fusion_prefix."users VALUES('', '$username', md5('$password'), '$email', '$hide_email', '$location', '$icq', '$msn', '$yahoo', '$web', '', '$signature', '0', '".time()."', '0', '0', '0')");
				opentable(LAN_240);
				echo "<center><br>
".LAN_241."<br><br>
</center>\n";
				closetable();
			} else {
				opentable(LAN_242);
				echo "<center><br>
".LAN_243."<br><br>
</center>\n";
				closetable();
			}
		} else {
			$result = dbquery("INSERT INTO ".$fusion_prefix."users VALUES('', '$username', md5('$password'), '$email', '$hide_email', '$location', '$icq', '$msn', '$yahoo', '$web', '', '$signature', '0', '".time()."', '0', '0', '0')");
			opentable(LAN_240);
			echo "<center><br>
".LAN_241."<br><br>
</center>\n";
			closetable();
		}
	} else {
		opentable(LAN_242);
		echo "<center><br>
".LAN_244."<br><br>
$error<br>
<a href=\"$PHP_SELF\">".LAN_245."</a></div></br>\n";
		closetable();
	}
} else {
	mt_srand ((double)microtime()*1000000);
	$maxran = 1000000;
	$rand_num = mt_rand(0, $maxran);
	$result = dbquery("INSERT INTO ".$fusion_prefix."temp VALUES('', ".time().", md5('$rand_num'), '$rand_num')");
	opentable(LAN_200);
	echo "<center>
".LAN_250."
</center><br>
<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" class=\"body\">
<form name=\"registerform\" method=\"post\" action=\"$PHP_SELF\">
<tr>
<td>".LAN_251."<font color=\"red\">*</font></td>
<td><input type=\"textbox\" name=\"username\" maxlength=\"50\" class=\"textbox\" style=\"width:200px;\"></td>
</tr>
<tr>
<td>".LAN_252."<font color=\"red\">*</font></td>
<td><input type=\"password\" name=\"password\" maxlength=\"20\" class=\"textbox\" style=\"width:200px;\"></td>
</tr>
<tr>
<td>".LAN_253."<font color=\"red\">*</font></td>
<td><input type=\"password\" name=\"password2\" maxlength=\"20\" class=\"textbox\" style=\"width:200px;\"></td>
</tr>
<tr>
<td>".LAN_254."<font color=\"red\">*</font></td>
<td><input type=\"textbox\" name=\"email\" maxlength=\"100\" class=\"textbox\" style=\"width:200px;\"></td>
</tr>
<tr>
<td>".LAN_255."</td>
<td><input type=\"radio\" name=\"hide_email\" value=\"1\">".LAN_256."<input type=\"radio\" name=\"hide_email\" value=\"0\" checked>".LAN_257."</td>
</tr>
<tr>
<td>".LAN_258."</td>
<td>";
	if ($show_image == "on") {
		echo "<img src=\"fusion_core/create_img.php?img_code=".md5($rand_num)."\">";
	} else {
		echo "<b>$rand_num</b>";
	}
	echo "</td>
</tr>\n";
	unset($rand_num);
	echo "<tr>
<td>".LAN_259."</td>
<td><input type=\"textbox\" name=\"user_code\" class=\"textbox\" style=\"width:100px\"></td>
</tr>
<td>
<tr>
<td>".LAN_260."</td>
<td><input type=\"textbox\" name=\"location\" maxlength=\"50\" class=\"textbox\" style=\"width:200px;\"></td>
</tr>
<tr>
<td>".LAN_261."</td>
<td><input type=\"textbox\" name=\"icq\" maxlength=\"15\" class=\"textbox\" style=\"width:200px;\"></td>
</tr>
<tr>
<td>".LAN_262."</td>
<td><input type=\"textbox\" name=\"msn\" maxlength=\"100\" class=\"textbox\" style=\"width:200px;\"></td>
</tr>
<tr>
<td>".LAN_263."</td>
<td>
<input type=\"textbox\" name=\"yahoo\" maxlength=\"100\" class=\"textbox\" style=\"width:200px;\"></td>
</tr>
<tr>
<td>".LAN_264."</td>
<td><input type=\"textbox\" name=\"web\" maxlength=\"100\" class=\"textbox\" style=\"width:200px;\"></td>
</tr>
<tr>
<td valign=\"top\">".LAN_265."</td>
<td><textarea name=\"signature\" rows=\"5\" class=\"textbox\" style=\"width:295px\" onselect=\"updatePos(this);\" onkeyup=\"updatePos(this);\" onclick=\"updatePos(this);\" ondblclick=\"updatePos(this);\"></textarea><br>
<input type=\"button\" value=\"b\" class=\"button\" style=\"font-weight:bold;width:25px;\" onClick=\"AddText('b');\">
<input type=\"button\" value=\"i\" class=\"button\" style=\"font-style:italic;width:25px;\" onClick=\"AddText('i');\">
<input type=\"button\" value=\"u\" class=\"button\" style=\"text-decoration:underline;width:25px;\" onClick=\"AddText('u');\">
<input type=\"button\" value=\"url\" class=\"button\" style=\"width:30px;\" onClick=\"AddText('url');\">
<input type=\"button\" value=\"mail\" class=\"button\" style=\"width:35px;\" onClick=\"AddText('mail');\">
<input type=\"button\" value=\"img\" class=\"button\" style=\"width:40px;\" onClick=\"AddText('img');\">
<input type=\"button\" value=\"center\" class=\"button\" style=\"width:45px;\" onClick=\"AddText('center');\">
<input type=\"button\" value=\"small\" class=\"button\" style=\"width:40px;\" onClick=\"AddText('small');\"></td>
</tr>
<tr>
<td align=\"center\" colspan=\"2\"><br>
<input type=\"submit\" name=\"register\" value=\"".LAN_266."\" class=\"button\">
</td>
</tr>
</form>
</table>";
	closetable();
	echo "<script language=\"JavaScript\">
var editBody = document.registerform.signature;
function insertText(theText) {
	if (editBody.createTextRange && editBody.curPos) {
		editBody.curPos.text = theText;
	} else {
		editBody.value += theText;
	}
	editBody.focus();
}
function AddText(wrap) {
	if (editBody.curPos) {
		insertText(\"[\" + wrap + \"]\" + editBody.curPos.text + \"[/\" + wrap + \"]\");
	} else {
		insertText(\"[\" + wrap + \"][/\" + wrap + \"]\");
	}
}
function updatePos(obj) {
	if (obj.createTextRange) {
		obj.curPos = document.selection.createRange().duplicate();
	}
}
</script>\n";
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