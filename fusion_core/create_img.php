<?php
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
$result = dbquery("SELECT * FROM ".$fusion_prefix."temp WHERE temp_enc='$img_code'");
if (dbrows($result) != 0) {
	$data = dbarray($result);
	$image = ImageCreateFromJPEG("../fusion_images/vcode.jpg");
	$text_color = ImageColorAllocate($image, 180, 180, 180);
	Header("Content-type: image/jpeg");
	ImageString ($image, 5, 12, 2, $data['temp_dec'], $text_color);
	ImageJPEG($image, '', 75);
	ImageDestroy($image);
}
?>