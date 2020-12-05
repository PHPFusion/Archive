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
require fusion_langdir."admin/admin_main.php";
require fusion_langdir."admin/admin_image_uploads.php";
require "navigation.php";

if (!Admin()) header("Location: ../index.php");

if (SuperAdmin()) {
	if (isset($del)) {
		unlink(fusion_basedir."fusion_images/$del");
		opentable(LAN_400);
		echo "<center><br>
".LAN_401."<br><br>
<a href=\"$PHP_SELF\">".LAN_402."</a><br><br>
<a href=\"index.php\">".LAN_403."</a><br><br>
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
".LAN_404."<br><br>
<a href=\"$PHP_SELF\">".LAN_402."</a><br><br>
<a href=\"index.php\">".LAN_403."</a><br><br>
</center>\n";
		}
		opentable(LAN_405);
		if ($error == "") {		
			if (is_uploaded_file($imgtemp)){
				move_uploaded_file($imgtemp, fusion_basedir."fusion_images/".$imgname);
				chmod(fusion_basedir."fusion_images/".$imgname,0644);
				echo "<center><br>
<img src=\"".fusion_basedir."fusion_images/$imgname\"><br><br>
".LAN_406."<br><br>
<a href=\"$PHP_SELF\">".LAN_402."</a><br><br>
<a href=\"index.php\">".LAN_403."</a><br><br>
</center>\n";
			}
		} else {
			echo "$error\n";
		}
		closetable();
	} else {
		opentable(LAN_405);
		echo "<form name=\"uploadform\" method=\"post\" action=\"$PHP_SELF\" enctype=\"multipart/form-data\">
<table align=\"center\" width=\"350\" cellpadding=\"0\" cellspacing=\"0\" class=\"body\">
<tr>
<td width=\"80\">".LAN_407."</td>
<td><input type=\"file\" name=\"myfile\" class=\"textbox\" style=\"width:250px;\"></td>
</tr>
<tr>
<td align=\"center\" colspan=\"2\">
<input type=\"submit\" name=\"uploadimage\" value=\"".LAN_405."\" class=\"button\" style=\"width:100px;\"></td>
</tr>
</table>
</form>\n";
		closetable();
		tablebreak();
		$handle = opendir(fusion_basedir."fusion_images");
		while ($file = readdir($handle)){
			if ($file != "." && $file != ".." && $file != "/" && $file != "index.php" && $file != "smiley") {
				$image_list[] = $file;
			}
		}
		closedir($handle);
		sort($image_list);
		opentable(LAN_408);
		echo "<table align=\"center\" width=\"300\" cellpadding=\"0\" cellspacing=\"0\" class=\"body\">\n";
		for ($count=0;$image_list[$count]!="";$count++) {
			echo "<tr>
<td>$image_list[$count]</td><td width=\"60\"><a href=\"$PHP_SELF?del=$image_list[$count]\">".LAN_409."</a></td>
</tr>\n";
		}
		echo "</table>\n";
		closetable();
	}
}

echo "</td>\n";
require "../footer.php";
?>