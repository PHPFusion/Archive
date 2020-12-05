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
include FUSION_LANGUAGES.FUSION_LAN."admin/admin_main.php";
include FUSION_LANGUAGES.FUSION_LAN."admin/admin_image_uploads.php";
include FUSION_ADMIN."navigation.php";

if (!iADMIN) { header("Location:../index.php"); exit; }

if (isset($del)) {
	unlink(FUSION_IMAGES."$del");
	opentable(LAN_400);
	echo "<center><br>
".LAN_401."<br><br>
<a href='$PHP_SELF'>".LAN_402."</a><br><br>
<a href='index.php'>".LAN_403."</a><br><br>
</center>\n";
	closetable();
} else if (isset($_POST['uploadimage'])) {
	$imgext = $_FILES['myfile']['type'];
	$imgname = $_FILES['myfile']['name'];
	$imgsize = $_FILES['myfile']['size'];
	$imgtemp = $_FILES['myfile']['tmp_name'];
	if ($imgext == "image/gif") {
		$error = "";
	} else if ($imgext == "image/jpeg") {
		$error = "";
	} else if ($imgext == "image/pjpeg") {
		$error = "";
	} else if ($imgext == "image/png") {
		$error = "";
	} else if ($imgext == "image/x-png") {
		$error = "";
	} else {
		$error = "<center><br>
".LAN_422."<br><br>
<a href='$PHP_SELF'>".LAN_402."</a><br><br>
<a href='index.php'>".LAN_403."</a><br><br>
</center>\n";
	}
	opentable(LAN_420);
	if ($error == "") {		
		if (is_uploaded_file($imgtemp)){
			move_uploaded_file($imgtemp, FUSION_IMAGES.$imgname);
			chmod(FUSION_IMAGES.$imgname,0644);
			echo "<center><br>
<img src='".FUSION_IMAGES.$imgname."'><br><br>
".LAN_423."<br><br>
<a href='$PHP_SELF'>".LAN_402."</a><br><br>
<a href='index.php'>".LAN_403."</a><br><br>
</center>\n";
		}
	} else {
		echo "$error\n";
	}
	closetable();
} else {
	opentable(LAN_420);
	echo "<form name='uploadform' method='post' action='$PHP_SELF' enctype='multipart/form-data'>
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
		$image_ext = strrchr(FUSION_IMAGES.$view,".");
		if (in_array($image_ext, array(".gif",".jpg",".jpeg",".png"))) {
			echo "<img src='".FUSION_IMAGES.$view."'><br><br>\n";
		} else {
			echo LAN_441."<br><br>\n";
		}
		echo "<a href='$PHP_SELF?del=$view'>".LAN_442."</a><br><br>\n<a href='$PHP_SELF'>".LAN_402."</a>\n</center><br><br>\n";
		closetable();
	} else {
		$handle = opendir(FUSION_IMAGES);
		while ($file = readdir($handle)) {
			if (!in_array($file, array(".", "..", "/", "index.php", "smiley", "photoalbum"))) {
				$image_list[] = $file;
			}
		}
		closedir($handle);
		sort($image_list);
		opentable(LAN_460);
		echo "<table width='400' align='center' cellpadding='0' cellspacing='0'>\n";
		for ($i=0;$image_list[$i]!="";$i++) {
			if ($i % 2 == 0) { $row_color = "tbl1"; } else { $row_color = "tbl2"; }
			echo "<tr class='$row_color'>
<td>$image_list[$i]</td>
<td align='right'><a href='$PHP_SELF?view=$image_list[$i]'>".LAN_461."</a> - <a href='$PHP_SELF?del=$image_list[$i]'>".LAN_462."</a></td>
</tr>\n";
		}
		echo "</table>\n";
		closetable();
	}
}

echo "</td>\n";
include "../footer.php";
?>