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
if (!defined("IN_FUSION")) header("Location:index.php");

echo "</td>\n";
$p_res = dbquery("SELECT * FROM ".$fusion_prefix."panels WHERE panel_side='3' AND panel_access<='".iUSER."' AND panel_status='1' ORDER BY panel_order");
if (dbrows($p_res) != 0) {
	$pc = 0;
	while ($p_data = dbarray($p_res)) {
		if (checkgroup($p_data['panel_access'])) {
			if ($pc == 0) echo "<td width='$theme_width_r' valign='top' class='side-border-right'>\n";
			if ($p_data['panel_type'] == "file") {
				$panel_name = $p_data['panel_filename'];
				include FUSION_INFUSIONS.$panel_name."/".$panel_name.".php";
			} else {
				eval(stripslashes($p_data['panel_content']));
			}
			$pc++;
		}
	}
	if ($pc > 0) echo "</td>\n";
}
?>