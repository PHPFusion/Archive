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
require fusion_langdir."custom_pages.php";
require fusion_basedir."side_left.php";

$result = dbquery("SELECT * FROM ".$fusion_prefix."custom_pages WHERE page_id='$page_id'");
if (dbrows($result) != 0) {
	$data = dbarray($result);
	opentable($data[page_title]);
	if (UserLevel() >= $data[page_access]) {
		require fusion_basedir."fusion_pages/".$data[page_id].".php";
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

require fusion_basedir."side_right.php";
require fusion_basedir."footer.php";
?>