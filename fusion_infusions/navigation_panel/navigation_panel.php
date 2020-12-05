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

openside(LAN_01);
$result = dbquery("SELECT * FROM ".$fusion_prefix."site_links WHERE link_position<='2' ORDER BY link_order");
if (dbrows($result) != 0) {
	while($data = dbarray($result)) {
		if (checkgroup($data['link_visibility'])) {
			if ($data['link_name'] != "---" && $data['link_url'] == "---") {
				echo "<div class='side-label'><b>".$data['link_name']."</b></div>\n";
			} else if ($data['link_name'] == "---" && $data['link_url'] == "---") {
				echo "<hr class='side-hr'>\n";
			} else {
				$link_target = ($data['link_window'] == "1" ? " target='_blank'" : "");
				if (strstr($data['link_url'], "http://") || strstr($data['link_url'], "https://")) {
					echo "<img src='".FUSION_THEME."images/bullet.gif'> <a href='".$data['link_url']."'".$link_target." class='side'>".$data['link_name']."</a><br>\n";
				} else {
					echo "<img src='".FUSION_THEME."images/bullet.gif'> <a href='".FUSION_BASE.$data['link_url']."'".$link_target." class='side'>".$data['link_name']."</a><br>\n";
				}
			}
		}
	}
} else {
	echo LAN_02;
}
closeside();
?>