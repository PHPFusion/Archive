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
@require_once "fusion_config.php";
require_once "fusion_core.php";
require_once FUSION_BASE."subheader.php";
require_once FUSION_BASE."side_left.php";
include FUSION_LANGUAGES.FUSION_LAN."custom_pages.php";

$result = dbquery("SELECT * FROM ".$fusion_prefix."custom_pages WHERE page_id='$page_id'");
if (dbrows($result) != 0) {
	$data = dbarray($result);
	opentable($data['page_title']);
	if (checkgroup($data['page_access'])) {
		eval("?>".stripslashes($data['page_content'])."<?php ");
	} else {
		echo "<center><br>\n".LAN_400."\n<br><br></center>\n";
	}
} else {
	opentable(LAN_401);
	echo "<center><br>\n".LAN_402."\n<br><br></center>\n";
}
closetable();

require_once FUSION_BASE."side_right.php";
require_once FUSION_BASE."footer.php";
?>