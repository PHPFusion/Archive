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
if (!defined("IN_FUSION")) { header("Location:../../index.php"); exit; }

if (iMEMBER) {
	openside($userdata['user_name']);
	$msg_count = dbcount("(message_id)", "messages", "message_to='".$userdata['user_id']."' AND message_read='0'");
echo "<img src='".FUSION_THEME."images/bullet.gif'> <a href='".FUSION_BASE."edit_profile.php' class='side'>".LAN_80."</a><br>
<img src='".FUSION_THEME."images/bullet.gif'> <a href='".FUSION_BASE."messages.php' class='side'>".LAN_81."</a><br>
<img src='".FUSION_THEME."images/bullet.gif'> <a href='".FUSION_BASE."members.php' class='side'>".LAN_82."</a><br>\n";
	if (iADMIN) {
		echo "<img src='".FUSION_THEME."images/bullet.gif'> <a href='".FUSION_ADMIN."index.php' class='side'>".LAN_83."</a><br>\n";
	}
	echo "<img src='".FUSION_THEME."images/bullet.gif'> <a href='".FUSION_BASE."index.php?logout=yes' class='side'>".LAN_84."</a>\n";
	if ($msg_count) echo "<br><br><center><b><a href='".FUSION_BASE."messages.php' class='side'>".sprintf(LAN_85, $msg_count).($msg_count == 1 ? LAN_86 : LAN_87)."</a></b></center>\n";
} else {
	openside(LAN_60);
	echo "<div align='center'>".(isset($loginerror) ? $loginerror : "")."
<form name='loginform' method='post' action='".FUSION_SELF."'>
".LAN_61."<br>
<input type='text' name='user_name' class='textbox' style='width:100px'><br>
".LAN_62."<br>
<input type='password' name='user_pass' class='textbox' style='width:100px'><br>
<input type='checkbox' name='remember_me' value='y'>".LAN_63."<br><br>
<input type='submit' name='login' value='".LAN_64."' class='button'><br>
</form>
<br>\n";
	if ($settings['enable_registration']) {
		echo "<img src='".FUSION_THEME."images/bullet.gif'> <a href='".FUSION_BASE."register.php' class='side'>".LAN_65."</a><br>\n";
	}
	echo "<img src='".FUSION_THEME."images/bullet.gif'> <a href='".FUSION_BASE."lostpassword.php' class='side'>".LAN_66."</a>
</div>\n";
}
closeside();
?>