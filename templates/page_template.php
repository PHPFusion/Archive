<?
/*--------------------------------------------+
| PHP-Fusion 5 - Content Management System    |
|---------------------------------------------|
| author: Nick Jones (Digitanium) � 2002-2005 |
| web: http://www.php-fusion.co.uk            |
| email: nick@php-fusion.co.uk                |
|---------------------------------------------|
| Released under the terms and conditions of  |
| the GNU General Public License (Version 2)  |
+--------------------------------------------*/
@include "fusion_config.php";
include "fusion_core.php";
include "subheader.php";
include "side_left.php";

opentable("Page Template");
echo "\n";
closetable();

require "side_right.php";
require "footer.php";
?>