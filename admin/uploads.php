<?
/*
-------------------------------------------------------
	PHP Fusion X3
	-----------------------------------------------
	© Nick Jones 2002-2004
	http://www.digitaldominion.co.uk
	nick@digitaldominion.co.uk
	-----------------------------------------------
	Released under the terms and conditions of the
	GNU General Public License (http://gnu.org).
-------------------------------------------------------
*/
require "header.php";
require fusion_basedir."subheader.php";
require fusion_langdir."admin/admin_image_uploads.php";

require "navigation.php";
require "userinfo.php";
echo "</td>
<td valign=\"top\" class=\"bodybg\">\n";

if ($userdata[user_mod] > "2") {
	if (isset($del)) {
		unlink(fusion_basedir."images/$del");
		opentable(LAN_200);
		echo "<center><br>
".LAN_201."<br><br>
<a href=\"$PHP_SELF\">".LAN_202."</a><br><br>
<a href=\"index.php\">".LAN_203."</a><br><br>
</center>\n";
		closetable();
	} else if (isset($_POST['uploadimage'])) {
		$imgext = $_FILES['myfile']['type'];
		$imgname = $_FILES['myfile']['name'];
		$imgsize = $_FILES['myfile']['size'];
		$imgtemp = $_FILES['myfile']['tmp_name'];
		if ($imgext == "image/gif") {
			$error = "";
		} else if ($imgext == "image/pjpeg") {
			$error = "";
		} else if ($imgext == "image/png") {
			$error = "";
		} else {
			$error = "<center><br>
".LAN_204."<br><br>
<a href=\"$PHP_SELF\">".LAN_202."</a><br><br>
<a href=\"index.php\">".LAN_203."</a><br><br>
</center>\n";
		}
		opentable(LAN_205);
		if ($error == "") {		
			if (is_uploaded_file($imgtemp)){
				move_uploaded_file($imgtemp, fusion_basedir."images/".$imgname);
				echo "<center><br>
<img src=\"".fusion_basedir."images/$imgname\"><br><br>
".LAN_206."<br><br>
<a href=\"$PHP_SELF\">".LAN_202."</a><br><br>
<a href=\"index.php\">".LAN_203."</a><br><br>
</center>\n";
			}
		} else {
			echo "$error\n";
		}
		closetable();
	} else {
		opentable(LAN_205);
		echo "<table align=\"center\" width=\"350\" cellpadding=\"0\" cellspacing=\"0\" class=\"body\">
<form name=\"uploadform\" method=\"post\" action=\"$PHP_SELF\" enctype=\"multipart/form-data\">
<tr>
<td width=\"80\">".LAN_207."</td>
<td><input type=\"file\" name=\"myfile\" class=\"textbox\" style=\"width:250px;\"></td>
</tr>
<tr>
<td align=\"center\" colspan=\"2\">
<input type=\"submit\" name=\"uploadimage\" value=\"".LAN_205."\" class=\"button\" style=\"width:100px;\"></td>
</tr>
</form>
</table>\n";
		closetable();
		tablebreak();
		opentable(LAN_208);
		echo "<table align=\"center\" width=\"300\" cellpadding=\"0\" cellspacing=\"0\" class=\"body\">\n";
		$handle = opendir(fusion_basedir."images");
		while ($file = readdir($handle)){
			if ($file != "." && $file != ".." && $file != "/" && $file != "index.php" && $file != "smiley") {
				$imagelist .= "<tr>
<td>$file</td><td width=\"60\"><a href=\"$PHP_SELF?del=$file\">".LAN_209."</a></td>
</tr>\n";
			}
		}
		closedir($handle);
		echo $imagelist."
</table>\n";
		closetable();
	}
}

echo "</td>
</tr>
</table>\n";

require "../footer.php";
?>