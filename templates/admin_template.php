<?
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
@include "../fusion_config.php";
include "../fusion_core.php";
include FUSION_BASE."subheader.php";
require FUSION_LANGUAGES.FUSION_LAN."admin/admin_main.php";
require "navigation.php";

if (!iADMIN) { header("Location: ../index.php"); exit; }

//if (iSUPERADMIN) {
	opentable("Page Title")
	// content
	closetable() 
//}

echo "</td>\n";
require FUSION_BASE."footer.php";
?>