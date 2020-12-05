<?
if ($userdata[mod] == "Administrator") {
	$handle=opendir("../themes");
	while ($file = readdir($handle)){
		if($file != "." && $file != ".." && $file != "/") {
			if ($file == $settings[theme]) { $sel = " selected"; } else { $sel = ""; }
			$themelist .= "<option$sel>$file</option>\n";
		}
	}
	closedir($handle);
	opentable("Site Settings");
	echo "<form name=\"settingsform\" method=\"post\" action=\"$_SELF?sub=settings\">
<table width=\"600\" align=\"center\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<tr><td>Site Name:</td>
<td><input type=\"textbox\" name=\"sitename\" value=\"$settings[sitename]\" maxlength=\"255\" class=\"textbox\" style=\"width: 375px;\"></td></tr>
<tr><td>Site URL:</td>
<td><input type=\"textbox\" name=\"siteurl\" value=\"$settings[siteurl]\" maxlength=\"255\" class=\"textbox\" style=\"width: 375px;\"></td></tr>
<tr><td>Site Banner:</td>
<td><input type=\"textbox\" name=\"sitebanner\" value=\"$settings[sitebanner]\" maxlength=\"255\" class=\"textbox\" style=\"width: 375px;\"></td></tr>
<tr><td>Site Email address:</td>
<td><input type=\"textbox\" name=\"siteemail\" value=\"$settings[siteemail]\" maxlength=\"128\" class=\"textbox\" style=\"width: 375px;\"></td></tr>
<tr><td>Your name or Nickname:</td>
<td><input type=\"textbox\" name=\"username\" value=\"$settings[siteusername]\" maxlength=\"32\" class=\"textbox\" style=\"width: 375px;\"></td></tr>
<tr><td valign=\"top\">Site Introduction:<br>
<span class=\"small2\">Leave empty if not required</span></td>
<td><textarea name=\"intro\" rows=\"3\" class=\"textbox\" style=\"width: 375px;\">$settings[siteintro]</textarea></td></tr>
<tr><td valign=\"top\">Site Description:</td>
<td><textarea name=\"description\" rows=\"3\" class=\"textbox\" style=\"width: 375px;\">$settings[description]</textarea></td></tr>
<tr><td valign=\"top\">Site Keywords:<br>
<span class=\"small2\">Seperate each word with a comma</span></td>
<td><textarea name=\"keywords\" rows=\"3\" class=\"textbox\" style=\"width: 375px;\">$settings[keywords]</textarea></td></tr>
<tr><td valign=\"top\">Site Footer:</td>
<td><textarea name=\"footer\" rows=\"3\" class=\"textbox\" style=\"width: 375px;\">$settings[footer]</textarea></td></tr>
<tr><td>Site Theme:</td><td><select name=\"theme\" class=\"textbox\" style=\"width: 100px;\">
$themelist</select></td></tr>
<tr><td>Allow Visitor Shoutbox Posts:</td><td><select name=\"visitorshoutbox\" class=\"textbox\" style=\"width: 50px;\">
<option value=\"yes\""; if ($settings[visitorshoutbox] == "yes") { echo "selected"; } echo ">Yes</option>
<option value=\"no\""; if ($settings[visitorshoutbox] == "no") { echo "selected"; } echo ">No</option>
</select></td></tr>
<tr><td>Allow Visitor Comments:</td><td><select name=\"visitorcomments\" class=\"textbox\" style=\"width: 50px;\">
<option value=\"yes\""; if ($settings[visitorcomments] == "yes") { echo "selected"; } echo ">Yes</option>
<option value=\"no\""; if ($settings[visitorcomments] == "no") { echo "selected"; } echo ">No</option>
</select></td></tr>
<tr><td colspan=\"2\"><br><div align=\"center\">
<input type=\"submit\" name=\"savesettings\" value=\"Save Settings\" class=\"button\" style=\"width: 100px;\"></div>
</td></tr>
</table>
</form>\n";
	closetable();
}
?>