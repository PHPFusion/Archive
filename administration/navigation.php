<?php
/*---------------------------------------------------+
| PHP-Fusion 6 Content Management System
+----------------------------------------------------+
| Copyright (c) 2005 Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the
| GNU General Public License. For details refer to
| the included gpl.txt file or visit http://gnu.org
+----------------------------------------------------*/
if (!defined("IN_FUSION") || !iADMIN) fallback("../index.php");

echo "<td width='$theme_width_l' valign='top' class='side-border-left'>\n";
include INFUSIONS."user_info_panel/user_info_panel.php";
openside($locale['001']);
if (iMEMBER) {
	echo "<img src='".THEME."images/bullet.gif'> <a href='".ADMIN."index.php' class='side'>".$locale['150']."</a><br>
<hr class='side-hr'>
<img src='".THEME."images/bullet.gif'> <a href='".BASEDIR."index.php' class='side'>".$locale['151']."</a>\n";
}
closeside();

echo "</td>\n<td valign='top' class='main-bg'>\n";
?>
