<?
/*
---------------------------------------------------
	PHP-Fusion - Content Management System
	-------------------------------------------
	� Nick Jones (Digitanium) 2002-2004
	http://www.php-fusion.co.uk
	nick@php-fusion.co.uk
	-------------------------------------------
	Released under the terms and conditions of
	the GNU General Public License (Version 2)
---------------------------------------------------
*/
echo "</td>\n";
$p_res = dbquery("SELECT * FROM ".$fusion_prefix."panels WHERE panel_side='r' AND panel_access<='".UserLevel()."' AND panel_status='1' ORDER BY panel_order");
if (dbrows($p_res) != 0) {
	echo "<td width='$theme_width_r' valign='top' class='srborder'>\n";
	while ($p_data = dbarray($p_res)) {
		if ($p_data['panel_type'] == "file") {
			require fusion_basedir."fusion_panels/".$p_data['panel_filename'];
		} else {
			eval(stripslashes($p_data['panel_content']));
		}
	}
	echo "</td>\n";
}
?>