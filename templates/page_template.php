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
@require "fusion_config.php";
require "header.php";
require "subheader.php";
require "side_left.php";

echo "<td valign=\"top\" class=\"bodybg\">\n";

opentable("Page Template");
echo "\n";
closetable();

echo "</td>\n";

require "side_right.php";
require "footer.php";
?>