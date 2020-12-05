<?
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
@include "fusion_config.php";
include "fusion_core.php";
include "subheader.php";
include "side_left.php";
include FUSION_LANGUAGES.FUSION_LAN."lostpassword.php";
include FUSION_INCLUDES."sendmail_include.php";

opentable(LAN_400);
if (isset($_POST['send_password'])) {
	$result = dbquery("SELECT * FROM ".$fusion_prefix."users WHERE user_email='$email'");
	if (dbrows($result) != 0) {
		$data = dbarray($result);
		for ($i=0;$i<=7;$i++) { $new_pass .= chr(rand(97, 122)); }
		$message = str_replace("[NEW_PASS]", $new_pass, LAN_401);
		$message = str_replace("[USER_NAME]", $data['user_name'], $message);
		sendemail($data['user_name'], $_POST['email'], "New password for ".$settings['sitename'], $message);
		$result = dbquery("UPDATE ".$fusion_prefix."users SET user_password=md5('$new_pass') WHERE user_id='".$data['user_id']."'");
		echo "<center><br>
".LAN_402."<br><br>
<a href='index.php'>".LAN_403."</a><br><br>
</center>\n";
	} else {
		echo "<center><br>
".LAN_404."<br>
".LAN_405."<br>
<br>
<a href='index.php'>".LAN_403."</a><br><br>
</center>\n";
	}
} else {
	echo "<form name='passwordform' method='post' action='$PHP_SELF'>
<center>".LAN_406."<br>
<br>
<input type='text' name='email' class='textbox' maxlength='100' style='width:200px;'><br>
<br>
<input type='submit' name='send_password' value='".LAN_407."' class='button'></center>
</form>\n";
}
closetable();

include "side_right.php";
include "footer.php";
?>