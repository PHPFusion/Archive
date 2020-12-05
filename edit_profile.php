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
@include "fusion_config.php";
include "fusion_core.php";
include "subheader.php";
include "side_left.php";
include FUSION_LANGUAGES.FUSION_LAN."members-profile.php";
include FUSION_LANGUAGES.FUSION_LAN."user_fields.php";

if (isset($_POST['update_profile'])) include FUSION_INCLUDES."update_profile_include.php";

opentable(LAN_440);
if (iMEMBER) {
	if ($userdata['user_birthdate']!="0000-00-00") {
		$user_birthdate = explode("-", $userdata['user_birthdate']);
		$user_month = number_format($user_birthdate['1']);
		$user_day = number_format($user_birthdate['2']);
		$user_year = $user_birthdate['0'];
	} else {
		$user_month = 0; $user_day = 0; $user_year = 0;
	}
	$handle = opendir(FUSION_THEMES);
	while ($folder = readdir($handle)) if (!in_array($folder, array(".", "..", "/", "index.php"))) $theme_list[] = $folder;
	closedir($handle); sort($theme_list); array_unshift($theme_list, "Default");
	for ($i=-13;$i<17;$i++) {
		if ($i > 0) { $offset="+".$i; } else { $offset=$i; }
		$offset_list .= "<option".($offset == $userdata['user_offset'] ? " selected" : "").">$offset</option>\n";
	}
	echo "<form name='inputform' method='post' action='$PHP_SELF' enctype='multipart/form-data'>\n";
	echo "<table width='100%' cellspacing='0' cellpadding='4' class='tbl'>\n";
	if ($update_profile) {
		if ($error == "") {
			echo "<tr>\n<td colspan='2'>".LAN_441."<br><br>\n</td>\n</tr>\n";
		} else {
			echo "<tr>\n<td colspan='2'>".LAN_442."<br><br>\n$error<br></td>\n</tr>\n";
			unset($error);
		}
	}
	echo "<tr>
<td>".LNU_001."<span style='color:#ff0000'>*</span></td>
<td><input type='text' name='user_name' value='".$userdata['user_name']."' maxlength='30' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td>".LNU_003."</td>
<td><input type='password' name='user_newpassword' maxlength='20' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td>".LNU_004."</td>
<td><input type='password' name='user_newpassword2' maxlength='20' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td>".LNU_005."<span style='color:#ff0000'>*</span></td>
<td><input type='text' name='user_email' value='".$userdata['user_email']."' maxlength='100' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td>".LNU_006."</td>
<td><input type='radio' name='user_hide_email' value='1'".($userdata['user_hide_email'] == "1" ? " checked" : "").">".LNU_007."
<input type='radio' name='user_hide_email' value='0'".($userdata['user_hide_email'] == "0" ? " checked" : "").">".LNU_008."</td>
</tr>
<tr>
<td>".LNU_009."</td>
<td><input type='text' name='user_location' value='".$userdata['user_location']."' maxlength='50' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td>".LNU_010." <span class='small2'>(mm/dd/yyyy)</span></td>
<td><select name='user_month' class='textbox'>\n<option> </option>\n";
	for ($i=1;$i<=12;$i++) echo "<option".($user_month == $i ? " selected" : "").">$i</option>\n";
echo "</select>
<select name='user_day' class='textbox'>\n<option> </option>\n";
	for ($i=1;$i<=31;$i++) echo "<option".($user_day == $i ? " selected" : "").">$i</option>\n";
echo "</select>
<select name='user_year' class='textbox'>\n<option> </option>\n";
	for ($i=1900;$i<=2004;$i++) echo "<option".($user_year == $i ? " selected" : "").">$i</option>\n";
echo "</select>
</td>
</tr>
<tr>
<td>".LNU_011."</td>
<td><input type='text' name='user_icq' value='".$userdata['user_icq']."' maxlength='15' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td>".LNU_012."</td>
<td><input type='text' name='user_msn' value='".$userdata['user_msn']."' maxlength='100' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td>".LNU_013."</td>
<td><input type='text' name='user_yahoo' value='".$userdata['user_yahoo']."' maxlength='100' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td>".LNU_014."</td>
<td><input type='text' name='user_web' value='".$userdata['user_web']."' maxlength='100' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td>".LNU_015."</td>
<td><select name='user_theme' class='textbox' style='width:100px;'>\n";
	for ($count=0;$theme_list[$count]!="";$count++) {
		echo "<option".($theme_list[$count] == $userdata['user_theme'] ? " selected" : "").">$theme_list[$count]</option>\n";
	}
	echo "</select></td>
</tr>
<tr>
<td>".LNU_016."</td>
<td><select name='user_offset' class='textbox' style='width:100px;'>
$offset_list</select></td>
</tr>\n";
	if (!$userdata['user_avatar']) {
		echo "<tr>
<td>".LNU_017."</td>
<td>
<input type='file' name='user_avatar' enctype='multipart/form-data' class='textbox' style='width:200px;'><br>
<span class='small2'>".LNU_018."</span>
</td>
</tr>\n";
	}
echo "<tr>
<td valign='top' class='content'>".LNU_020."</td>
<td>
<textarea name='user_sig' rows='5' class='textbox' style='width:295px'>".$userdata['user_sig']."</textarea><br>
<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onClick=\"addText('user_sig', '[b]', '[/b]');\">
<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onClick=\"addText('user_sig', '[i]', '[/i]');\">
<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onClick=\"addText('user_sig', '[u]', '[/u]');\">
<input type='button' value='url' class='button' style='width:30px;' onClick=\"addText('user_sig', '[url]', '[/url]');\">
<input type='button' value='mail' class='button' style='width:35px;' onClick=\"addText('user_sig', '[mail]', '[/mail]');\">
<input type='button' value='img' class='button' style='width:30px;' onClick=\"addText('user_sig', '[img]', '[/img]');\">
<input type='button' value='center' class='button' style='width:45px;' onClick=\"addText('user_sig', '[center]', '[/center]');\">
<input type='button' value='small' class='button' style='width:40px;' onClick=\"addText('user_sig', '[small]', '[/small]');\">
</td>
</tr>
<tr>
<td align='center' colspan='2'><br>\n";
	if ($userdata['user_avatar']) {
		echo LNU_017."<br>\n<img src='".FUSION_PUBLIC."avatars/".$userdata['user_avatar']."'><br>
<input type='checkbox' name='del_avatar' value='y'> ".LNU_019."
<input type='hidden' name='user_avatar' value='".$userdata['user_avatar']."'><br><br>\n";
	}
	echo "<input type='hidden' name='user_hash' value='".$userdata['user_password']."'>
<input type='submit' name='update_profile' value='".LAN_460."' class='button'></td>
</tr>
</table>
</form>\n";
	closetable();
} else {
	echo "<center><br>
".LAN_03."<br>
<br></center>\n";
	closetable();
}

include "side_right.php";
include "footer.php";
?>