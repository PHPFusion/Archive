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

$p_res = dbquery("SELECT * FROM ".$fusion_prefix."panels WHERE panel_side='1' AND panel_status='1' ORDER BY panel_order");
if (dbrows($p_res) != 0) {
	$pc = 0;
	while ($p_data = dbarray($p_res)) {
		if (checkgroup($p_data['panel_access'])) {
			if ($pc == 0) echo "<td width='$theme_width_l' valign='top' class='side-border-left'>\n";
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
echo "<td valign='top' class='main-bg'>\n";
if (FUSION_ROOT != "/") {
	$current_url_path = str_replace(FUSION_ROOT, "", $_SERVER['PHP_SELF']).(FUSION_QUERY ? "?".FUSION_QUERY : "");
} else {
	$current_url_path = substr($_SERVER['PHP_SELF'], 1).(FUSION_QUERY ? "?".FUSION_QUERY : "");
}
if ($current_url_path == ($settings['start_page']=="other" ? $settings['other_page'] : $settings['start_page'])) {
	$p_res = dbquery("SELECT * FROM ".$fusion_prefix."panels WHERE panel_side='2' AND panel_status='1' ORDER BY panel_order");
	if (dbrows($p_res) != 0) {
		while ($p_data = dbarray($p_res)) {
			if (checkgroup($p_data['panel_access'])) {
				if ($p_data['panel_type'] == "file") {
					$panel_name = $p_data['panel_filename'];
					include FUSION_INFUSIONS.$panel_name."/".$panel_name.".php";
				} else {
					eval(stripslashes($p_data['panel_content']));
				}
				tablebreak();
			}
		}
	}
}
?>