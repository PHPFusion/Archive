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
include FUSION_ADMIN."navigation.php";

if (!iSUPERADMIN) { header("Location:../index.php"); exit; }

opentable("Upgrade");
echo "<center><br>\nThere is no database upgrade available.<br><br>\n</center>\n";
closetable();

echo "</td>\n";
include FUSION_BASE."footer.php";
?>