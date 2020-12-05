<?
/*
---------------------------------------------------
	PHP-Fusion - Content Management System
	-------------------------------------------
	© Nick Jones (Digitanium) 2002-2004
	http://www.php-fusion.co.uk
	nick@php-fusion.co.uk
	-------------------------------------------
	Released under the terms and conditions of
	the GNU General Public License (Version 2)
---------------------------------------------------
*/
render_footer(false);

echo "</body>
</html>\n";

$result = dbquery("DELETE FROM ".$fusion_prefix."temp WHERE temp_time < '".(time()-300)."'");

ob_end_flush();
?>