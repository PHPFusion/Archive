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

if ($rowstart < 1) {
	if ($settings['siteintro'] != "") {
		opentable(LAN_30);
		echo stripslashes($settings['siteintro'])."\n";
		closetable();
		tablebreak();
	}
	if ($settings['forumpanel'] == "1") {
		require "forumlist.php";
	}
}
require "news.php";

require "side_right.php";
require "footer.php";
?>