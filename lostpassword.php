<?php
/*---------------------------------------------------+
| PHP-Fusion 6 Content Management System
+----------------------------------------------------+
| Copyright © 2002 - 2005 Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the
| GNU General Public License. For details refer to
| the included gpl.txt file or visit http://gnu.org
+----------------------------------------------------*/
require_once "maincore.php";
require_once "subheader.php";
require_once "side_left.php";
require_once INCLUDES."sendmail_include.php";
include LOCALE.LOCALESET."lostpassword.php";

opentable($locale['400']);
if (isset($_POST['send_password'])) {
	$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_email='$email'");
	if (dbrows($result) != 0) {
		$data = dbarray($result);
		for ($i=0;$i<=7;$i++) { $new_pass .= chr(rand(97, 122)); }
		$mailbody = str_replace("[NEW_PASS]", $new_pass, $locale['401']);
		$mailbody = str_replace("[USER_NAME]", $data['user_name'], $mailbody);
		sendemail($data['user_name'],$_POST['email'],$settings['siteusername'],$settings['siteemail'],"New password for ".$settings['sitename'],$mailbody);
		$result = dbquery("UPDATE ".$db_prefix."users SET user_password=md5('$new_pass') WHERE user_id='".$data['user_id']."'");
		echo "<center><br>
".$locale['402']."<br><br>
<a href='index.php'>".$locale['403']."</a><br><br>
</center>\n";
	} else {
		echo "<center><br>
".$locale['404']."<br>
".$locale['405']."<br>
<br>
<a href='index.php'>".$locale['403']."</a><br><br>
</center>\n";
	}
} else {
	echo "<form name='passwordform' method='post' action='".FUSION_SELF."'>
<center>".$locale['406']."<br>
<br>
<input type='text' name='email' class='textbox' maxlength='100' style='width:200px;'><br>
<br>
<input type='submit' name='send_password' value='".$locale['407']."' class='button'></center>
</form>\n";
}
closetable();

require_once "side_right.php";
require_once "footer.php";
?>