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
@require "../fusion_config.php";
require "../header.php";
require fusion_basedir."subheader.php";
require fusion_langdir."admin/admin_main.php";
require "navigation.php";

if (!Admin()) header("Location: ../index.php");

//if (SuperAdmin()) {
	opentable("Page Title")
	  // content
	closetable() 
//}

echo "</td>\n";
require "../footer.php";
?>