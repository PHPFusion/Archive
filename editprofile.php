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
@require "fusion_config.php";
require "header.php";
require "subheader.php";
require fusion_langdir."editprofile.php";
require "side_left.php";

opentable(LAN_400);
if (Member()) {
	$handle = opendir(fusion_basedir."fusion_themes");
	while ($folder = readdir($handle)){
		if ($folder != "." && $folder != ".." && $folder != "/" && $folder != "index.php") {
			$theme_list[] = $folder;
		}
	}
	closedir($handle);
	sort($theme_list);
	for ($i=-13;$i<17;$i++) {
		if ($i > 0) { $offset="+".$i; } else { $offset=$i; }
		if ($offset == $userdata[user_offset]) { $sel = " selected"; } else { $sel = ""; }
		$offset_list .= "<option$sel>$offset</option>\n";
	}
	echo "<form name='editform' method='post' action='$PHP_SELF' enctype='multipart/form-data'>
<table cellpadding='0' cellspacing='0' width='100%' class='body'>\n";
	if (isset($update_profile)) {
		if ($error == "") {
			echo "<tr>
<td colspan='2'>".LAN_401."<br><br>
</td>
</tr>\n";
		} else {
			echo "<tr>
<td colspan='2'>".LAN_402."<br><br>
$error<br></td>
</tr>\n";
			unset($error);
		}
	}
	echo "<tr>
<td>".LAN_410."<span style='color:#ff0000'>*</span></td>
<td><input type='text' name='user_name' value='$userdata[user_name]' maxlength='30' class='textbox' style='width:200px;'></td>
</tr>
<td>".LAN_411."</td>
<td><input type='password' name='user_newpassword' maxlength='20' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td>".LAN_412."</td>
<td><input type='password' name='user_newpassword2' maxlength='20' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td>".LAN_413."<span style='color:#ff0000'>*</span></td>
<td><input type='text' name='user_email' value='$userdata[user_email]' maxlength='100' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td>".LAN_414."</td>\n";
	if ($userdata[user_hide_email] == "1") { $yes = " checked"; $no = ""; } else { $yes = ""; $no = " checked"; } 
	echo "<td><input type='radio' name='user_hide_email' value='1'$yes>".LAN_415."<input type='radio' name='user_hide_email' value='2'$no>".LAN_416."</td>
</tr>
<tr>
<td>".LAN_417."</td>
<td><input type='text' name='user_location' value='$userdata[user_location]' maxlength='50' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td>".LAN_418."</td>
<td><input type='text' name='user_icq' value='$userdata[user_icq]' maxlength='15' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td>".LAN_419."</td>
<td><input type='text' name='user_msn' value='$userdata[user_msn]' maxlength='100' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td>".LAN_420."</td>
<td><input type='text' name='user_yahoo' value='$userdata[user_yahoo]' maxlength='100' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td>".LAN_421."</td>
<td><input type='text' name='user_web' value='$userdata[user_web]' maxlength='100' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td>".LAN_422."</td>
<td><select name='user_theme' class='textbox' style='width:100px;'>\n";
	if ($userdata[user_theme] == "Default") { $sel = " selected"; } else { $sel = ""; }
	echo "<option$sel>Default</option>\n";
	for ($count=0;$theme_list[$count]!="";$count++) {
		if ($theme_list[$count] == $userdata[user_theme]) { $sel = " selected"; } else { $sel = ""; }
		echo "<option$sel>$theme_list[$count]</option>\n";
	}
echo "</select></td>
</tr>
<tr>
<td>".LAN_423."</td>
<td><select name='user_offset' class='textbox' style='width:100px;'>
$offset_list</select></td>
</tr>
<tr>
<td>".LAN_424."</td>
<td>";
	if ($userdata[user_avatar] == "") {
		echo "<input type='file' name='user_avatar' enctype='multipart/form-data' class='textbox' style='width:200px;'><br>
<span class='small2'>".LAN_425."</span>";
	} else {
		echo "<input type='text' name='user_avatar' value='$userdata[user_avatar]' maxlength='100' class='textbox' style='width:200px;'><br>
<span class='small2'>".LAN_426."</span>";
	}
	echo "</td>
</tr>
<tr>
<td valign='top' class='content'>".LAN_427."</td>
<td><textarea name='user_signature' rows='5' class='textbox' style='width:295px' onselect='updatePos(this);' onkeyup='updatePos(this);' onclick='updatePos(this);' ondblclick='updatePos(this);'>$userdata[user_sig]</textarea><br>
<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onClick=\"AddText('b');\">
<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onClick=\"AddText('i');\">
<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onClick=\"AddText('u');\">
<input type='button' value='url' class='button' style='width:30px;' onClick=\"AddText('url');\">
<input type='button' value='mail' class='button' style='width:35px;' onClick=\"AddText('mail');\">
<input type='button' value='img' class='button' style='width:40px;' onClick=\"AddText('img');\">
<input type='button' value='center' class='button' style='width:45px;' onClick=\"AddText('center');\">
<input type='button' value='small' class='button' style='width:40px;' onClick=\"AddText('small');\"></td>
</tr>
<tr>
<td align='center' colspan='2'><br>
<input type='submit' name='update_profile' value='".LAN_428."' class='button'></td>
</tr>
</table>
</form>\n";
closetable();
	echo "<script language='JavaScript'>
var editBody = document.editform.user_signature;
function insertText(theText) {
	if (editBody.createTextRange && editBody.curPos) {
		editBody.curPos.text = theText;
	} else {
		editBody.value += theText;
	}
	editBody.focus();
}
function AddText(wrap) {
	if (editBody.curPos) {
		insertText(\"[\" + wrap + \"]\" + editBody.curPos.text + \"[/\" + wrap + \"]\");
	} else {
		insertText(\"[\" + wrap + \"][/\" + wrap + \"]\");
	}
}
function updatePos(obj) {
	if (obj.createTextRange) {
		obj.curPos = document.selection.createRange().duplicate();
	}
}
</script>\n";
} else {
	echo "<center><br>
".LAN_03."<br>
<br></center>\n";
	closetable();
}

require "side_right.php";
require "footer.php";
?>