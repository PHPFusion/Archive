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
require_once "side_left.php";
include FUSION_LANGUAGES.FUSION_LAN."contact.php";
include FUSION_INCLUDES."sendmail_include.php";

if (isset($_POST['sendmessage'])) {
	$mailname = stripinput($_POST['mailname']);
	$email = stripinput($_POST['email']);
	if ($mailname == "") {
		$error .= "· <span class='alt'>".LAN_420."</span><br>\n";
	}
	if ($email == "") {
		$error .= "· <span class='alt'>".LAN_421."</span><br>\n";
	}
	if ($_POST['subject'] == "") {
		$error .= "· <span class='alt'>".LAN_422."</span><br>\n";
	}
	if ($_POST['message'] == "") {
		$error .= "· <span class='alt'>".LAN_423."</span><br>\n";
	}
	if (!$error) {
		$subject = stripslash($_POST['subject']);
		$message = stripslash($_POST['message']);
		sendemail($settings['siteusername'],$settings['siteemail'],$mailname,$email,$subject,$message);
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
<form name='userform' method='post' action='".FUSION_SELF."'>
<table align='center' width='100%' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td width='90'>".LAN_402."</td>
<td><input type='text' name='mailname' maxlength='64' class='textbox' style='width: 200px;'></td>
</tr>
<tr>
<td width='90'>".LAN_403."</td>
<td><input type='text' name='email' maxlength='128' class='textbox' style='width: 200px;'></td>
</tr>
<tr>
<td width='90'>".LAN_404."</td>
<td><input type='text' name='subject' maxlength='32' class='textbox' style='width: 200px;'></td>
</tr>
<tr><td valign='top' width='90'>".LAN_405."</td>
<td><textarea name='message' rows='10' class='textbox' style='width: 320px'></textarea></td>
</tr>
<tr>
<td colspan='2'><div align='center'>
<input type='submit' name='sendmessage' value='".LAN_406."' class='button'></div>
</td>
</tr>
</table>
</form>\n";
	closetable();
}

require_once "side_right.php";
require_once "footer.php";
?>