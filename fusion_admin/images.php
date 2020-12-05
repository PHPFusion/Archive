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
@require_once "../fusion_config.php";
require_once "../fusion_core.php";
require_once FUSION_BASE."subheader.php";
require_once FUSION_ADMIN."navigation.php";

include FUSION_LANGUAGES.FUSION_LAN."admin/admin_image_uploads.php";

if (!checkrights("B")) { header("Location:../index.php"); exit; }

if (!isset($ifolder)) $ifolder = "images";

if ($ifolder == "images") { $afolder = FUSION_IMAGES; }
elseif ($ifolder == "imagesa") { $afolder = FUSION_IMAGES_A; }
elseif ($ifolder == "imagesn") { $afolder = FUSION_IMAGES_N; }

if (isset($del)) {
	unlink($afolder."$del");
	opentable(LAN_400);
	echo "<center><br>
".LAN_401."<br><br>
<a href='".FUSION_SELF."?ifolder=$ifolder'>".LAN_402."</a><br><br>
<a href='index.php'>".LAN_403."</a><br><br>
</center>\n";
	closetable();
} else if (isset($_POST['uploadimage'])) {
	$error = "";
	$image_types = array(
		".gif",
		".GIF",
		".jpeg",
		".JPEG",
		".jpg",
		".JPG",
		".png",
		".PNG"
	);
	$imgext = strrchr($_FILES['myfile']['name'], ".");
	$imgname = $_FILES['myfile']['name'];
	$imgsize = $_FILES['myfile']['size'];
	$imgtemp = $_FILES['myfile']['tmp_name'];
	if (!in_array($imgext, $image_types)) {
		$error = "<center><br>
".LAN_425."<br><br>
<a href='".FUSION_SELF."?ifolder=$ifolder'>".LAN_402."</a><br><br>
<a href='index.php'>".LAN_403."</a><br><br>
</center>\n";
	}
	opentable(LAN_420);
	if ($error == "") {		
		if (is_uploaded_file($imgtemp)){
			move_uploaded_file($imgtemp, $afolder.$imgname);
			chmod($afolder.$imgname,0644);
			echo "<center><br>
<img src='".$afolder.$imgname."'><br><br>
".LAN_426."<br><br>
<a href='".FUSION_SELF."?ifolder=$ifolder'>".LAN_402."</a><br><br>
<a href='index.php'>".LAN_403."</a><br><br>
</center>\n";
		}
	} else {
		echo "$error\n";
	}
	closetable();
} else {
	opentable(LAN_420);
	echo "<form name='uploadform' method='post' action='".FUSION_SELF."?ifolder=$ifolder' enctype='multipart/form-data'>
<table align='center' width='350' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td width='80'>".LAN_421."</td>
<td><input type='file' name='myfile' class='textbox' style='width:250px;'></td>
</tr>
<tr>
<td align='center' colspan='2'>
<input type='submit' name='uploadimage' value='".LAN_420."' class='button' style='width:100px;'></td>
</tr>
</table>
</form>\n";
	closetable();
	tablebreak();
	if (isset($view)) {
		opentable(LAN_440);
		echo "<center><br>\n";
		$image_ext = strrchr($afolder.$view,".");
		if (in_array($image_ext, array(".gif",".jpg",".jpeg",".png"))) {
			echo "<img src='".$afolder.$view."'><br><br>\n";
		} else {
			echo LAN_441."<br><br>\n";
		}
		echo "<a href='".FUSION_SELF."?del=$view'>".LAN_442."</a><br><br>\n<a href='".FUSION_SELF."'>".LAN_402."</a><br><br>\n</center>\n";
		closetable();
	} else {
		$handle = opendir($afolder);
		while ($file = readdir($handle)) {
			if (!in_array($file, array(".", "..", "/", "index.php"))) {
				if (!is_dir($afolder.$file)) $image_list[] = $file;
			}
		}
		closedir($handle);
		if (isset($image_list)) { sort($image_list); $image_count = count($image_list); }
		opentable(LAN_460);
		echo "<table width='350' align='center' cellpadding='0' cellspacing='0'>
<tr>
<td width='33%'><a href='".FUSION_SELF."?ifolder=images'>".LAN_422."</a></td>
<td width='33%' align='center'><a href='".FUSION_SELF."?ifolder=imagesa'>".LAN_423."</a></td>
<td width='33%' align='right'><a href='".FUSION_SELF."?ifolder=imagesn'>".LAN_424."</a></td>
</tr>
<tr>
<td colspan='3'><hr></td>
</tr>
</table>\n";
		echo "<table width='400' align='center' cellpadding='0' cellspacing='0'>\n";
		if (isset($image_list)) {
			for ($i=0;$i < $image_count;$i++) {
				if ($i % 2 == 0) { $row_color = "tbl1"; } else { $row_color = "tbl2"; }
				echo "<tr>\n<td class='$row_color'>$image_list[$i]</td>
<td align='right' class='$row_color'><a href='".FUSION_SELF."?ifolder=$ifolder&view=$image_list[$i]'>".LAN_461."</a> -
<a href='".FUSION_SELF."?ifolder=$ifolder&del=$image_list[$i]'>".LAN_462."</a></td>
</tr>\n";
			}
		} else {
			echo "<tr>\n<td align='center' class='tbl1'>".LAN_463."</td>\n</tr>\n";
		}
		echo "</table>\n";
		closetable();
	}
}

echo "</td>\n";
require_once FUSION_BASE."footer.php";
?>