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
if (!defined("IN_FUSION") || !iADMIN) { header("Location:../index.php"); exit; }

include FUSION_LANGUAGES.FUSION_LAN."admin/admin_main.php";
include FUSION_ADMIN."admin_panels.php";

function showpanels($admin_title, $admin_link, $admin_right) {
	$tmp = "";
	if (checkrights($admin_right)) {
		$tmp .= "<img src='".FUSION_THEME."images/bullet.gif'> <a href='".FUSION_ADMIN.$admin_link."' class='side'>$admin_title</a><br>";
	}
	return $tmp;
}

echo "<td width='$theme_width_l' valign='top' class='side-border-left'>\n";
include FUSION_INFUSIONS."user_info_panel/user_info_panel.php";
openside(LAN_01);
if (iMEMBER) {
	echo "<img src='".FUSION_THEME."images/bullet.gif'> <a href='".FUSION_ADMIN."index.php' class='side'>".LAN_150."</a><br>
<hr class='side-hr'>
<img src='".FUSION_THEME."images/bullet.gif'> <a href='".FUSION_BASE."index.php' class='side'>".LAN_151."</a>\n";
}
closeside();
opensidex(LAN_152,"off");
while(list($key, $admin_info) = each($admin_panels)){
        if ($admin_info[1] != "empty") echo showpanels($admin_info[0], $admin_info[1], $admin_info[2]);
}
closesidex();
echo "</td>\n<td valign='top' class='main-bg'>\n";
?>
