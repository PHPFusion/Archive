<?php
/*---------------------------------------------------+
| PHP-Fusion 6 Content Management System
+----------------------------------------------------+
| Copyright (c) 2005 Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the
| GNU General Public License. For details refer to
| the included gpl.txt file or visit http://gnu.org
+----------------------------------------------------*/
require_once "../maincore.php";
require_once BASEDIR."subheader.php";
require_once ADMIN."navigation.php";
include LOCALE.LOCALESET."admin/image_uploads.php";

if (!checkrights("IM")) fallback("../index.php");
if (!isset($ifolder)) $ifolder = "images";

if ($ifolder == "images") { $afolder = IMAGES; }
elseif ($ifolder == "imagesa") { $afolder = IMAGES_A; }
elseif ($ifolder == "imagesn") { $afolder = IMAGES_N; }

if (isset($del)) {
	unlink($afolder."$del");
	opentable($locale['400']);
	echo "<center><br>
".$locale['401']."<br><br>
<a href='".FUSION_SELF."?ifolder=$ifolder'>".$locale['402']."</a><br><br>
<a href='index.php'>".$locale['403']."</a><br><br>
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
".$locale['425']."<br><br>
<a href='".FUSION_SELF."?ifolder=$ifolder'>".$locale['402']."</a><br><br>
<a href='index.php'>".$locale['403']."</a><br><br>
</center>\n";
	}
	opentable($locale['420']);
	if ($error == "") {		
		if (is_uploaded_file($imgtemp)){
			move_uploaded_file($imgtemp, $afolder.$imgname);
			chmod($afolder.$imgname,0644);
			echo "<center><br>
<img src='".$afolder.$imgname."'><br><br>
".$locale['426']."<br><br>
<a href='".FUSION_SELF."?ifolder=$ifolder'>".$locale['402']."</a><br><br>
<a href='index.php'>".$locale['403']."</a><br><br>
</center>\n";
		}
	} else {
		echo "$error\n";
	}
	closetable();
} else {
	opentable($locale['420']);
	echo "<form name='uploadform' method='post' action='".FUSION_SELF."?ifolder=$ifolder' enctype='multipart/form-data'>
<table align='center' width='350' cellspacing='0' cellpadding='0'>
<tr>
<td width='80' class='tbl'>".$locale['421']."</td>
<td class='tbl'><input type='file' name='myfile' class='textbox' style='width:250px;'></td>
</tr>
<tr>
<td align='center' colspan='2' class='tbl'>
<input type='submit' name='uploadimage' value='".$locale['420']."' class='button' style='width:100px;'></td>
</tr>
</table>
</form>\n";
	closetable();
	tablebreak();
	if (isset($view)) {
		opentable($locale['440']);
		echo "<center><br>\n";
		$image_ext = strrchr($afolder.$view,".");
		if (in_array($image_ext, array(".gif",".GIF",".jpg",".JPG",".jpeg",".JPEG",".png",".PNG"))) {
			echo "<img src='".$afolder.$view."'><br><br>\n";
		} else {
			echo $locale['441']."<br><br>\n";
		}
		echo "<a href='".FUSION_SELF."?del=$view'>".$locale['442']."</a><br><br>\n<a href='".FUSION_SELF."'>".$locale['402']."</a><br><br>\n</center>\n";
		closetable();
	} else {
		$image_list = makefilelist($afolder, ".|..|index.php", true);
		if ($image_list) { $image_count = count($image_list); }
		opentable($locale['460']);
		echo "<table width='350' align='center' cellpadding='0' cellspacing='0'>
<tr>
<td width='33%'><a href='".FUSION_SELF."?ifolder=images'>".$locale['422']."</a></td>
<td width='33%' align='center'><a href='".FUSION_SELF."?ifolder=imagesa'>".$locale['423']."</a></td>
<td width='33%' align='right'><a href='".FUSION_SELF."?ifolder=imagesn'>".$locale['424']."</a></td>
</tr>
<tr>
<td colspan='3'><hr></td>
</tr>
</table>\n";
		echo "<table width='400' align='center' cellpadding='0' cellspacing='0'>\n";
		if ($image_list) {
			for ($i=0;$i < $image_count;$i++) {
				if ($i % 2 == 0) { $row_color = "tbl1"; } else { $row_color = "tbl2"; }
				echo "<tr>\n<td class='$row_color'>$image_list[$i]</td>
<td align='right' class='$row_color'><a href='".FUSION_SELF."?ifolder=$ifolder&view=$image_list[$i]'>".$locale['461']."</a> -
<a href='".FUSION_SELF."?ifolder=$ifolder&del=$image_list[$i]'>".$locale['462']."</a></td>
</tr>\n";
			}
		} else {
			echo "<tr>\n<td align='center' class='tbl1'>".$locale['463']."</td>\n</tr>\n";
		}
		echo "</table>\n";
		closetable();
	}
}

echo "</td>\n";
require_once BASEDIR."footer.php";
?>