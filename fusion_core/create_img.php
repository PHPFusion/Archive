<?php
@require "../fusion_config.php";
require "../header.php";
$result = dbquery("SELECT * FROM ".$fusion_prefix."temp WHERE temp_enc='$img_code'");
if (dbrows($result) != 0) {
	$data = dbarray($result);
	$image = ImageCreateFromJPEG("../fusion_images/vcode.jpg");
	$text_color = ImageColorAllocate($image, 180, 180, 180);
	Header("Content-type: image/jpeg");
	ImageString ($image, 5, 12, 2, $data[temp_dec], $text_color);
	ImageJPEG($image, '', 75);
	ImageDestroy($image);
}
?>