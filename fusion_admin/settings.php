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
require fusion_langdir."admin/admin_settings.php";
require "navigation.php";

if (!Admin()) header("Location: ../index.php");

if (SuperAdmin()) {
	if (isset($_POST['savesettings'])) {
		$result = dbquery("UPDATE ".$fusion_prefix."settings SET sitename='$sitename', siteurl='$siteurl', sitebanner='$sitebanner',
		siteemail='$siteemail',	siteusername='$username', siteintro='$intro', description='$description', keywords='$keywords',
		footer='$footer', language='$language', theme='$theme', shortdate='$shortdate', longdate='$longdate', forumdate='$forumdate',
		timeoffset='$timeoffset', forumpanel='$forumpanel', numofthreads='$numofthreads', attachments='$attachments',
		attachmax='$attachmax', attachtypes='$attachtypes', guestposts='$guestposts', numofshouts='$numofshouts'");
		header("Location: settings.php");
	}
	if (isset($_POST['prune'])) {
		require_once "forums_prune.php";
	}
	$settings2 = dbarray(dbquery("SELECT * FROM ".$fusion_prefix."settings"));
	$handle = opendir(fusion_basedir."fusion_themes");
	while ($folder = readdir($handle)){
		if ($folder != "." && $folder != ".." && $folder != "/" && $folder != "index.php") {
			$theme_list[] = $folder;
		}
	}
	closedir($handle);
	$handle=opendir(fusion_basedir."fusion_languages");
	while ($folder = readdir($handle)){
		if ($folder != "." && $folder != ".." && $folder != "/" && $folder != "index.php") {
			$lang_list[] = $folder;
		}
	}
	closedir($handle);
	sort($theme_list);
	sort($lang_list);
	for ($i=-13;$i<17;$i++) {
		if ($i > 0) { $offset="+".$i; } else { $offset=$i; }
		if ($offset == $settings2[timeoffset]) { $sel = " selected"; } else { $sel = ""; }
		$offsetlist .= "<option$sel>$offset</option>\n";
	}
	opentable(LAN_400);
	echo "<form name=\"settingsform\" method=\"post\" action=\"$PHP_SELF\">
<table width=\"500\" align=\"center\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<tr>
<td>".LAN_401."</td>
<td><input type=\"text\" name=\"sitename\" value=\"$settings2[sitename]\" maxlength=\"255\" class=\"textbox\" style=\"width:200px;\"></td>
</tr>
<tr>
<td>".LAN_402."</td>
<td><input type=\"text\" name=\"siteurl\" value=\"$settings2[siteurl]\" maxlength=\"255\" class=\"textbox\" style=\"width:200px;\"></td>
</tr>
<tr>
<td>".LAN_403."</td>
<td><input type=\"text\" name=\"sitebanner\" value=\"$settings2[sitebanner]\" maxlength=\"255\" class=\"textbox\" style=\"width:200px;\"></td>
</tr>
<tr>
<td>".LAN_404."</td>
<td><input type=\"text\" name=\"siteemail\" value=\"$settings2[siteemail]\" maxlength=\"128\" class=\"textbox\" style=\"width:200px;\"></td>
</tr>
<tr>
<td>".LAN_405."</td>
<td><input type=\"text\" name=\"username\" value=\"$settings2[siteusername]\" maxlength=\"32\" class=\"textbox\" style=\"width:200px;\"></td>
</tr>
<tr>
<td valign=\"top\">".LAN_406."<br>
<span class=\"small2\">".LAN_407."</span></td>
<td><textarea name=\"intro\" rows=\"5\" class=\"textbox\" style=\"width:250px;\">$settings2[siteintro]</textarea></td>
</tr>
<tr>
<td valign=\"top\">".LAN_408."</td>
<td><textarea name=\"description\" rows=\"5\" class=\"textbox\" style=\"width:250px;\">$settings2[description]</textarea></td>
</tr>
<tr>
<td valign=\"top\">".LAN_409."<br>
<span class=\"small2\">".LAN_410."</span></td>
<td><textarea name=\"keywords\" rows=\"5\" class=\"textbox\" style=\"width:250px;\">$settings2[keywords]</textarea></td>
</tr>
<tr><td valign=\"top\">".LAN_411."</td>
<td><textarea name=\"footer\" rows=\"5\" class=\"textbox\" style=\"width:250px;\">$settings2[footer]</textarea></td>
</tr>
<tr>
<td>".LAN_412."</td>
<td><select name=\"language\" class=\"textbox\" style=\"width:100px;\">\n";
	for ($count=0;$lang_list[$count]!="";$count++) {
		if ($lang_list[$count] == $settings2[language]) { $sel = " selected"; } else { $sel = ""; }
		echo "<option$sel>$lang_list[$count]</option>\n";
	}
echo "</select></td>
</tr>
<tr>
<td>".LAN_413."</td>
<td><select name=\"theme\" class=\"textbox\" style=\"width:100px;\">\n";
	for ($count=0;$theme_list[$count]!="";$count++) {
		if ($theme_list[$count] == $settings2[theme]) { $sel = " selected"; } else { $sel = ""; }
		echo "<option$sel>$theme_list[$count]</option>\n";
	}
echo "</select></td>
</tr>
<tr>
<td colspan=\"2\" class=\"altbg\">".LAN_450."</td>
</tr>
<tr>
<td>".LAN_451."</td>
<td><input type=\"text\" name=\"shortdate\" value=\"$settings2[shortdate]\" maxlength=\"50\" class=\"textbox\" style=\"width:150px;\"></td>
</tr>
<tr>
<td>".LAN_452."</td>
<td><input type=\"text\" name=\"longdate\" value=\"$settings2[longdate]\" maxlength=\"50\" class=\"textbox\" style=\"width:150px;\"></td>
</tr>
<tr>
<td>".LAN_453."</td>
<td><input type=\"text\" name=\"forumdate\" value=\"$settings2[forumdate]\" maxlength=\"50\" class=\"textbox\" style=\"width:150px;\"></td>
</tr>
<tr>
<td>".LAN_454."</td>
<td><select name=\"timeoffset\" class=\"textbox\" style=\"width:75px;\">
$offsetlist</select></td>
</tr>
<tr>
<td colspan=\"2\" class=\"altbg\">".LAN_500."</td>
</tr>
<tr>
<td>".LAN_501."</td>
<td><select name=\"forumpanel\" class=\"textbox\" style=\"width:50px;\">
<option value=\"1\""; if($settings2[forumpanel]=="1"){echo"selected";} echo ">".LAN_502."</option>
<option value=\"0\""; if($settings2[forumpanel]=="0"){echo"selected";} echo ">".LAN_503."</option>
</select></td>
</td>
</tr>
<tr>
<td>".LAN_505."<br>
<span class=\"small2\">".LAN_506."</span>
</td>
<td>
<select name=\"numofthreads\" class=\"textbox\" style=\"width:50px;\">\n";
if ($settings2[numofthreads] == 5) { $sel = " selected"; } else { $sel = ""; }
echo "<option".$sel.">5</option>\n";
if ($settings2[numofthreads] == 10) { $sel = " selected"; } else { $sel = ""; }
echo "<option".$sel.">10</option>\n";
if ($settings2[numofthreads] == 15) { $sel = " selected"; } else { $sel = ""; }
echo "<option".$sel.">15</option>\n";
if ($settings2[numofthreads] == 20) { $sel = " selected"; } else { $sel = ""; }
echo "<option".$sel.">20</option>
</select>
</td>
</tr>
<tr>
<td>".LAN_507."</td>
<td><select name=\"attachments\" class=\"textbox\" style=\"width:50px;\">
<option value=\"1\""; if ($settings2[attachments] == "1") { echo "selected"; } echo ">".LAN_508."</option>
<option value=\"0\""; if ($settings2[attachments] == "0") { echo "selected"; } echo ">".LAN_509."</option>
</select></td>
</tr>
<tr>
<td>".LAN_510."<br>
<span class=\"small2\">".LAN_511."</span></td>
<td><input type=\"text\" name=\"attachmax\" value=\"$settings2[attachmax]\" maxlength=\"150\" class=\"textbox\" style=\"width:100px;\"></td>
</tr>
<tr>
<tr>
<td>".LAN_512."<br>
<span class=\"small2\">".LAN_513."</span></td>
<td><input type=\"text\" name=\"attachtypes\" value=\"$settings2[attachtypes]\" maxlength=\"150\" class=\"textbox\" style=\"width:200px;\"></td>
</tr>
<tr>
<td>".LAN_514."<br>
<span class=\"small2\"><font color=\"red\">".LAN_515."</font> ".LAN_516."</span></td>
<td valign=\"top\">
<input type=\"submit\" name=\"prune\" value=\"".LAN_517."\" class=\"button\">
<select name=\"prune_days\" class=\"textbox\" style=\"width:50px;\">
<option>10</option>
<option>20</option>
<option>30</option>
<option>60</option>
<option>90</option>
<option selected>120</option>
</select>
".LAN_518." 
</td>
</tr>
<tr>
<td colspan=\"2\" class=\"altbg\">".LAN_550."</td>
</tr>
<tr>
<td>".LAN_551."</td>
<td><select name=\"guestposts\" class=\"textbox\" style=\"width:50px;\">
<option value=\"1\""; if ($settings2[guestposts] == "1") { echo "selected"; } echo ">".LAN_508."</option>
<option value=\"0\""; if ($settings2[guestposts] == "0") { echo "selected"; } echo ">".LAN_509."</option>
</select></td>
</tr>
<tr>
<td>".LAN_553."</td>
<td>
<select name=\"numofshouts\" class=\"textbox\" style=\"width:50px;\">\n";
if ($settings2[numofshouts] == 5) { $sel = " selected"; } else { $sel = ""; }
echo "<option".$sel.">5</option>\n";
if ($settings2[numofshouts] == 10) { $sel = " selected"; } else { $sel = ""; }
echo "<option".$sel.">10</option>\n";
if ($settings2[numofshouts] == 15) { $sel = " selected"; } else { $sel = ""; }
echo "<option".$sel.">15</option>\n";
if ($settings2[numofshouts] == 20) { $sel = " selected"; } else { $sel = ""; }
echo "<option".$sel.">20</option>
</select>
</td>
</tr>
<tr><td align=\"center\" colspan=\"2\"><br>
<input type=\"submit\" name=\"savesettings\" value=\"".LAN_554."\" class=\"button\"></td>
</tr>
</table>
</form>\n";
	closetable();
}

echo "</td>\n";
require "../footer.php";
?>