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
require fusion_langdir."admin/admin_settings.php";

require "navigation.php";
require "userinfo.php";
echo "</td>
<td valign=\"top\" class=\"bodybg\">\n";

if ($userdata[user_mod] > "2") {
	if (isset($_POST['prune'])) {
		require_once "forums_prune.php";
	}
	$handle=opendir(fusion_basedir."themes");
	while ($file = readdir($handle)){
		if ($file != "." && $file != ".." && $file != "/" && $file != "index.php") {
			if ($file == $settings[theme]) { $sel = " selected"; } else { $sel = ""; }
			$themelist .= "<option$sel>$file</option>\n";
		}
	}
	closedir($handle);
	$handle=opendir(fusion_basedir."language");
	while ($file = readdir($handle)){
		if ($file != "." && $file != ".." && $file != "/" && $file != "index.php") {
				if ($file == $settings[language]) { $sel = " selected"; } else { $sel = ""; }
				$langlist .= "<option$sel>$file</option>\n";
		}
	}
	closedir($handle);
	for ($i=-12;$i<14;$i++) {
		if ($i > 0) { $offset="+".$i; } else { $offset=$i; }
		if ($offset == $settings[timeoffset]) { $sel = " selected"; } else { $sel = ""; }
		$offsetlist .= "<option$sel>$offset</option>\n";
	}
	opentable(LAN_200);
	echo "<table width=\"500\" align=\"center\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<form name=\"settingsform\" method=\"post\" action=\"$PHP_SELF\">
<tr>
<td>".LAN_201."</td>
<td><input type=\"textbox\" name=\"sitename\" value=\"$settings[sitename]\" maxlength=\"255\" class=\"textbox\" style=\"width:200px;\"></td>
</tr>
<tr>
<td>".LAN_202."</td>
<td><input type=\"textbox\" name=\"siteurl\" value=\"$settings[siteurl]\" maxlength=\"255\" class=\"textbox\" style=\"width:200px;\"></td>
</tr>
<tr>
<td>".LAN_203."</td>
<td><input type=\"textbox\" name=\"sitebanner\" value=\"$settings[sitebanner]\" maxlength=\"255\" class=\"textbox\" style=\"width:200px;\"></td>
</tr>
<tr>
<td>".LAN_204."</td>
<td><input type=\"textbox\" name=\"siteemail\" value=\"$settings[siteemail]\" maxlength=\"128\" class=\"textbox\" style=\"width:200px;\"></td>
</tr>
<tr>
<td>".LAN_205."</td>
<td><input type=\"textbox\" name=\"username\" value=\"$settings[siteusername]\" maxlength=\"32\" class=\"textbox\" style=\"width:200px;\"></td>
</tr>
<tr>
<td valign=\"top\">".LAN_206."<br>
<span class=\"small2\">".LAN_207."</span></td>
<td><textarea name=\"intro\" rows=\"5\" class=\"textbox\" style=\"width:250px;\">$settings[siteintro]</textarea></td>
</tr>
<tr>
<td valign=\"top\">".LAN_208."</td>
<td><textarea name=\"description\" rows=\"5\" class=\"textbox\" style=\"width:250px;\">$settings[description]</textarea></td>
</tr>
<tr>
<td valign=\"top\">".LAN_209."<br>
<span class=\"small2\">".LAN_210."</span></td>
<td><textarea name=\"keywords\" rows=\"5\" class=\"textbox\" style=\"width:250px;\">$settings[keywords]</textarea></td>
</tr>
<tr><td valign=\"top\">".LAN_211."</td>
<td><textarea name=\"footer\" rows=\"5\" class=\"textbox\" style=\"width:250px;\">$settings[footer]</textarea></td>
</tr>
<tr>
<td>".LAN_212."</td>
<td><select name=\"language\" class=\"textbox\" style=\"width:100px;\">
$langlist</select></td>
</tr>
<tr>
<td>".LAN_213."</td>
<td><select name=\"theme\" class=\"textbox\" style=\"width:100px;\">
$themelist</select></td>
</tr>
<tr>
<td colspan=\"2\" class=\"altbg\">".LAN_250."</td>
</tr>
<tr>
<td>".LAN_251."</td>
<td><input type=\"textbox\" name=\"shortdate\" value=\"$settings[shortdate]\" maxlength=\"50\" class=\"textbox\" style=\"width:150px;\"></td>
</tr>
<tr>
<td>".LAN_252."</td>
<td><input type=\"textbox\" name=\"longdate\" value=\"$settings[longdate]\" maxlength=\"50\" class=\"textbox\" style=\"width:150px;\"></td>
</tr>
<tr>
<td>".LAN_253."</td>
<td><input type=\"textbox\" name=\"forumdate\" value=\"$settings[forumdate]\" maxlength=\"50\" class=\"textbox\" style=\"width:150px;\"></td>
</tr>
<tr>
<td>".LAN_254."</td>
<td><select name=\"timeoffset\" class=\"textbox\" style=\"width:50px;\">
$offsetlist</select></td>
</tr>
<tr>
<td colspan=\"2\" class=\"altbg\">".LAN_300."</td>
</tr>
<tr>
<td>".LAN_301."</td>
<td>
<select name=\"forumpanels\" class=\"textbox\" style=\"width:100px;\">\n";
if ($settings[forumpanels] == "H") { $sel = " selected"; } else { $sel = ""; }
echo "<option value=\"H\"".$sel.">".LAN_302."</option>\n";
if ($settings[forumpanels] == "V") { $sel = " selected"; } else { $sel = ""; }
echo "<option value=\"V\"".$sel.">".LAN_303."</option>\n";
if ($settings[forumpanels] == "B") { $sel = " selected"; } else { $sel = ""; }
echo "<option value=\"B\"".$sel.">".LAN_304."</option>
</select>
</td>
</tr>
<tr>
<td>".LAN_305."<br>
<span class=\"small2\">".LAN_306."</span>
</td>
<td>
<select name=\"numofthreads\" class=\"textbox\" style=\"width:50px;\">\n";
if ($settings[numofthreads] == 5) { $sel = " selected"; } else { $sel = ""; }
echo "<option".$sel.">5</option>\n";
if ($settings[numofthreads] == 10) { $sel = " selected"; } else { $sel = ""; }
echo "<option".$sel.">10</option>\n";
if ($settings[numofthreads] == 15) { $sel = " selected"; } else { $sel = ""; }
echo "<option".$sel.">15</option>\n";
if ($settings[numofthreads] == 20) { $sel = " selected"; } else { $sel = ""; }
echo "<option".$sel.">20</option>
</select>
</td>
</tr>
<tr>
<td>".LAN_307."</td>
<td><select name=\"attachments\" class=\"textbox\" style=\"width:50px;\">
<option value=\"1\""; if ($settings[attachments] == "1") { echo "selected"; } echo ">".LAN_308."</option>
<option value=\"0\""; if ($settings[attachments] == "0") { echo "selected"; } echo ">".LAN_309."</option>
</select></td>
</tr>
<tr>
<td>".LAN_310."<br>
<span class=\"small2\">".LAN_311."</span></td>
<td><input type=\"textbox\" name=\"attachmax\" value=\"$settings[attachmax]\" maxlength=\"150\" class=\"textbox\" style=\"width:100px;\"></td>
</tr>
<tr>
<tr>
<td>".LAN_312."<br>
<span class=\"small2\">".LAN_313."</span></td>
<td><input type=\"textbox\" name=\"attachtypes\" value=\"$settings[attachtypes]\" maxlength=\"150\" class=\"textbox\" style=\"width:200px;\"></td>
</tr>
<tr>
<td>".LAN_314."<br>
<span class=\"small2\"><font color=\"red\">".LAN_315."</font> ".LAN_316."</span></td>
<td valign=\"top\">
<input type=\"submit\" name=\"prune\" value=\"".LAN_317."\" class=\"button\" style=\"width:50px;\">
<select name=\"prunedays\" class=\"textbox\" style=\"width:50px;\">
<option>10</option>
<option>20</option>
<option>30</option>
<option>60</option>
<option>90</option>
<option selected>120</option>
</select>
".LAN_318." 
</td>
</tr>
<tr>
<td colspan=\"2\" class=\"altbg\">".LAN_350."</td>
</tr>
<tr>
<td>".LAN_351."</td>
<td><select name=\"guestposts\" class=\"textbox\" style=\"width:50px;\">
<option value=\"1\""; if ($settings[guestposts] == "1") { echo "selected"; } echo ">".LAN_308."</option>
<option value=\"0\""; if ($settings[guestposts] == "0") { echo "selected"; } echo ">".LAN_309."</option>
</select></td>
</tr>
<tr>
<td>".LAN_352."</td>
<td><select name=\"displaypoll\" class=\"textbox\" style=\"width:50px;\">
<option value=\"1\""; if ($settings[displaypoll] == "1") { echo "selected"; } echo ">".LAN_308."</option>
<option value=\"0\""; if ($settings[displaypoll] == "0") { echo "selected"; } echo ">".LAN_309."</option>
</select></td>
</tr>
<tr>
<td>".LAN_353."</td>
<td>
<select name=\"numofshouts\" class=\"textbox\" style=\"width:50px;\">\n";
if ($settings[numofshouts] == 5) { $sel = " selected"; } else { $sel = ""; }
echo "<option".$sel.">5</option>\n";
if ($settings[numofshouts] == 10) { $sel = " selected"; } else { $sel = ""; }
echo "<option".$sel.">10</option>\n";
if ($settings[numofshouts] == 15) { $sel = " selected"; } else { $sel = ""; }
echo "<option".$sel.">15</option>\n";
if ($settings[numofshouts] == 20) { $sel = " selected"; } else { $sel = ""; }
echo "<option".$sel.">20</option>
</select>
</td>
</tr>
<tr><td align=\"center\" colspan=\"2\"><br>
<input type=\"submit\" name=\"savesettings\" value=\"".LAN_354."\" class=\"button\" style=\"width:100px;\"></td>
</tr>
</form>
</table>\n";
	closetable();
}

echo "</td>
</tr>
</table>\n";

require "../footer.php";
?>