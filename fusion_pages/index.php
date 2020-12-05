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
include FUSION_LANGUAGES.FUSION_LAN."custom_pages.php";
include FUSION_BASE."side_left.php";

$result = dbquery("SELECT * FROM ".$fusion_prefix."custom_pages WHERE page_id='$page_id'");
if (dbrows($result) != 0) {
	$data = dbarray($result);
	opentable($data[page_title]);
	if (iUSER >= $data[page_access]) {
		include FUSION_BASE."fusion_pages/".$data[page_id].".php";
	} else {
	echo "<center><br>
".LAN_400."
<br><br></center>\n";
	}
} else {
	opentable(LAN_401);
	echo "<center><br>
".LAN_402."
<br><br></center>\n";
}
closetable();

include FUSION_BASE."side_right.php";
include FUSION_BASE."footer.php";
?>