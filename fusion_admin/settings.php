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

include FUSION_LANGUAGES.FUSION_LAN."admin/admin_settings.php";

if (!checkrights("L")) { header("Location:../index.php"); exit; }

if (isset($_POST['prune'])) require_once "forums_prune.php";
if (isset($_POST['savesettings'])) {
	$result = dbquery("UPDATE ".$fusion_prefix."settings SET
		sitename='".$_POST['sitename']."',
		siteurl='".stripinput($_POST['siteurl']).(strrchr($_POST['siteurl'],"/") != "/" ? "/" : "")."',
		sitebanner='".$_POST['sitebanner']."',
		siteemail='".stripinput($_POST['siteemail'])."',
		siteusername='".stripinput($_POST['username'])."',
		siteintro='".addslash($_POST['intro'])."',
		description='".$_POST['description']."',
		keywords='".$_POST['keywords']."',
		footer='".addslash($_POST['footer'])."',
		start_page='".$_POST['start_page']."',
		other_page='".$_POST['other_page']."',
		language='".$_POST['language']."',
		theme='".$_POST['theme']."',
		shortdate='".$_POST['shortdate']."',
		longdate='".$_POST['longdate']."',
		forumdate='".$_POST['forumdate']."',
		timeoffset='".$_POST['timeoffset']."',
		numofthreads='".$_POST['numofthreads']."',
		attachments='".$_POST['attachments']."',
		attachmax='".$_POST['attachmax']."',
		attachtypes='".$_POST['attachtypes']."',
		enable_registration='".$_POST['enable_registration']."',
		email_verification='".$_POST['email_verification']."',
		display_validation='".$_POST['display_validation']."',
		validation_method='".$_POST['validation_method']."',
		album_image_w='".(isNum($_POST['album_image_w']) ? $_POST['album_image_w'] : "80")."',
		album_image_h='".(isNum($_POST['album_image_h']) ? $_POST['album_image_h'] : "60")."',
		thumb_image_w='".(isNum($_POST['thumb_image_w']) ? $_POST['thumb_image_w'] : "120")."',
		thumb_image_h='".(isNum($_POST['thumb_image_h']) ? $_POST['thumb_image_h'] : "100")."',
		thumb_compression='".$_POST['thumb_compression']."',
		album_comments='".$_POST['album_comments']."',
		albums_per_row='".(isNum($_POST['albums_per_row']) ? $_POST['albums_per_row'] : "4")."',
		albums_per_page='".(isNum($_POST['albums_per_page']) ? $_POST['albums_per_page'] : "16")."',
		thumbs_per_row='".(isNum($_POST['thumbs_per_row']) ? $_POST['thumbs_per_row'] : "4")."',
		thumbs_per_page='".(isNum($_POST['thumbs_per_page']) ? $_POST['thumbs_per_page'] : "12")."',
		album_max_w='".(isNum($_POST['album_max_w']) ? $_POST['album_max_w'] : "400")."',
		album_max_h='".(isNum($_POST['album_max_h']) ? $_POST['album_max_h'] : "300")."',
		album_max_b='".(isNum($_POST['album_max_b']) ? $_POST['album_max_b'] : "150000")."',
		bad_words='".$_POST['bad_words']."',
		bad_word_replace='".$_POST['bad_word_replace']."',
		guestposts='".$_POST['guestposts']."',
		numofshouts='".$_POST['numofshouts']."',
		maintenance='".$_POST['maintenance']."',
		maintenance_message='".$_POST['maintenance_message']."'
	");
	header("Location: settings.php");
}
$settings2 = dbarray(dbquery("SELECT * FROM ".$fusion_prefix."settings"));
$handle = opendir(FUSION_THEMES);
while ($folder = readdir($handle)){
	if ($folder != "." && $folder != ".." && $folder != "/" && $folder != "index.php") {
		$theme_list[] = $folder;
	}
}
closedir($handle);
$handle=opendir(FUSION_LANGUAGES);
while ($folder = readdir($handle)){
	if ($folder != "." && $folder != ".." && $folder != "/" && $folder != "index.php") {
		$lang_list[] = $folder;
	}
}
closedir($handle);
sort($theme_list); sort($lang_list); $offsetlist = "";
for ($i=-13;$i<17;$i++) {
	if ($i > 0) { $offset="+".$i; } else { $offset=$i; }
	if ($offset == $settings2['timeoffset']) { $sel = " selected"; } else { $sel = ""; }
	$offsetlist .= "<option$sel>$offset</option>\n";
}
opentable(LAN_400);
echo "<form name='settingsform' method='post' action='".FUSION_SELF."'>
<table width='500' align='center' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td class='tbl2'><img align='left' onclick=\"javascript:flipBox('main')\" name='b_main' border='0' src='".FUSION_THEME."images/panel_off.gif'>
".LAN_401."</td>
</tr>
<tr>
<td>

<div id='box_main'>
<table width='100%' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td width='40%'>".LAN_402."</td>
<td width='60%'><input type='text' name='sitename' value='".$settings2['sitename']."' maxlength='255' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td width='40%'>".LAN_403."</td>
<td width='60%'><input type='text' name='siteurl' value='".$settings2['siteurl']."' maxlength='255' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td width='40%'>".LAN_404."</td>
<td width='60%'><input type='text' name='sitebanner' value='".$settings2['sitebanner']."' maxlength='255' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td width='40%'>".LAN_405."</td>
<td width='60%'><input type='text' name='siteemail' value='".$settings2['siteemail']."' maxlength='128' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td width='40%'>".LAN_406."</td>
<td width='60%'><input type='text' name='username' value='".$settings2['siteusername']."' maxlength='32' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td valign='top'>".LAN_407."<br>
<span class='small2'>".LAN_408."</span></td>
<td width='60%'><textarea name='intro' rows='5' class='textbox' style='width:250px;'>".stripslashes($settings2['siteintro'])."</textarea></td>
</tr>
<tr>
<td valign='top'>".LAN_409."</td>
<td width='60%'><textarea name='description' rows='5' class='textbox' style='width:250px;'>".$settings2['description']."</textarea></td>
</tr>
<tr>
<td valign='top'>".LAN_410."<br>
<span class='small2'>".LAN_411."</span></td>
<td width='60%'><textarea name='keywords' rows='5' class='textbox' style='width:250px;'>".$settings2['keywords']."</textarea></td>
</tr>
<tr><td valign='top'>".LAN_412."</td>
<td width='60%'><textarea name='footer' rows='5' class='textbox' style='width:250px;'>".stripslashes($settings2['footer'])."</textarea></td>
</tr>
<tr>
<td valign='top'>".LAN_413."</td>
<td width='60%'><input type='radio' name='start_page' value='news.php'".($settings2['start_page']=="news.php"?" checked":"")."> ".LAN_414."<br>
<input type='radio' name='start_page' value='downloads.php'".($settings2['start_page']=="downloads.php"?" checked":"")."> ".LAN_415."<br>
<input type='radio' name='start_page' value='weblinks.php'".($settings2['start_page']=="weblinks.php"?" checked":"")."> ".LAN_416."<br>
<input type='radio' name='start_page' value='".str_replace("../","",FUSION_FORUM)."index.php'".($settings2['start_page']==str_replace("../","",FUSION_FORUM)."index.php"?" checked":"")."> ".LAN_417."<br>
<input type='radio' name='start_page' value='other'".($settings2['start_page']=="other"?" checked":"")."> ".LAN_418."
<input type='text' name='other_page' value='".$settings2['other_page']."' maxlength='100' class='textbox' style='width:180px;'><br>
<span class='small2'>".LAN_419."</span>
</td>
</tr>
<tr>
<td width='40%'>".LAN_420."</td>
<td width='60%'><select name='language' class='textbox'>\n";
for ($count=0;$lang_list[$count]!="";$count++) {
	echo "<option".($lang_list[$count] == $settings2['language'] ? " selected" : "").">$lang_list[$count]</option>\n";
}
echo "</select></td>
</tr>
<tr>
<td width='40%'>".LAN_421."</td>
<td width='60%'><select name='theme' class='textbox'>\n";
for ($count=0;$theme_list[$count]!="";$count++) {
	echo "<option".($theme_list[$count] == $settings2['theme'] ? " selected" : "").">$theme_list[$count]</option>\n";
}
echo "</select></td>
</tr>
</table>
</div>

</td>
</tr>
<tr>
<td class='tbl2'><img align='left' onclick=\"javascript:flipBox('timedate')\" name='b_timedate' border='0' src='".FUSION_THEME."images/panel_on.gif'>
".LAN_450."</td>
</tr>
<tr>
<td>

<div id='box_timedate' style='display:none'>
<table width='100%' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td width='40%'>".LAN_451."</td>
<td width='60%'><input type='text' name='shortdate' value='".$settings2['shortdate']."' maxlength='50' class='textbox' style='width:150px;'></td>
</tr>
<tr>
<td width='40%'>".LAN_452."</td>
<td width='60%'><input type='text' name='longdate' value='".$settings2['longdate']."' maxlength='50' class='textbox' style='width:150px;'></td>
</tr>
<tr>
<td width='40%'>".LAN_453."</td>
<td width='60%'><input type='text' name='forumdate' value='".$settings2['forumdate']."' maxlength='50' class='textbox' style='width:150px;'></td>
</tr>
<tr>
<td width='40%'>".LAN_454."</td>
<td width='60%'><select name='timeoffset' class='textbox' style='width:75px;'>
$offsetlist</select></td>
</tr>
</table>
</div>

</td>
</tr>
<tr>
<td class='tbl2'><img align='left' onclick=\"javascript:flipBox('forum')\" name='b_forum' border='0' src='".FUSION_THEME."images/panel_on.gif'>
".LAN_500."</td>
</tr>
<tr>
<td>

<div id='box_forum' style='display:none'>
<table width='100%' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td width='40%'>".LAN_505."<br>
<span class='small2'>".LAN_506."</span>
</td>
<td width='60%'>
<select name='numofthreads' class='textbox'>
<option".($settings2['numofthreads'] == 5 ? " selected" : "").">5</option>
<option".($settings2['numofthreads'] == 10 ? " selected" : "").">10</option>
<option".($settings2['numofthreads'] == 15 ? " selected" : "").">15</option>
<option".($settings2['numofthreads'] == 20 ? " selected" : "").">20</option>
</select>
</td>
</tr>
<tr>
<td width='40%'>".LAN_507."</td>
<td width='60%'><select name='attachments' class='textbox'>
<option value='1'".($settings2['attachments'] == "1" ? " selected" : "").">".LAN_508."</option>
<option value='0'".($settings2['attachments'] == "0" ? " selected" : "").">".LAN_509."</option>
</select></td>
</tr>
<tr>
<td width='40%'>".LAN_510."<br>
<span class='small2'>".LAN_511."</span></td>
<td width='60%'><input type='text' name='attachmax' value='".$settings2['attachmax']."' maxlength='150' class='textbox' style='width:100px;'></td>
</tr>
<tr>
<tr>
<td width='40%'>".LAN_512."<br>
<span class='small2'>".LAN_513."</span></td>
<td width='60%'><input type='text' name='attachtypes' value='".$settings2['attachtypes']."' maxlength='150' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td width='40%'>".LAN_514."<br>
<span class='small2'><font color='red'>".LAN_515."</font> ".LAN_516."</span></td>
<td width='60%'>
<input type='submit' name='prune' value='".LAN_517."' class='button'>
<select name='prune_days' class='textbox' style='width:50px;'>
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
</table>
</div>

</td>
</tr>
<tr>
<td class='tbl2'><img align='left' onclick=\"javascript:flipBox('registration')\" name='b_registration' border='0' src='".FUSION_THEME."images/panel_on.gif'>
".LAN_550."</td>
</tr>
<tr>
<td>

<div id='box_registration' style='display:none'>
<table width='100%' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
</tr>
<tr>
<td width='40%'>".LAN_551."</td>
<td width='60%'><select name='enable_registration' class='textbox'>
<option value='1'".($settings2['enable_registration'] == "1" ? " selected" : "").">".LAN_508."</option>
<option value='0'".($settings2['enable_registration'] == "0" ? " selected" : "").">".LAN_509."</option>
</select></td>
</tr>
<tr>
<td width='40%'>".LAN_552."</td>
<td width='60%'><select name='email_verification' class='textbox'>
<option value='1'".($settings2['email_verification'] == "1" ? " selected" : "").">".LAN_508."</option>
<option value='0'".($settings2['email_verification'] == "0" ? " selected" : "").">".LAN_509."</option>
</select></td>
</tr>
<tr>
<td width='40%'>".LAN_553."</td>
<td width='60%'><select name='display_validation' class='textbox'>
<option value='1'".($settings2['display_validation'] == "1" ? " selected" : "").">".LAN_508."</option>
<option value='0'".($settings2['display_validation'] == "0" ? " selected" : "").">".LAN_509."</option>
</select></td>
</tr>
<tr>
<td width='40%'>".LAN_554."</td>
<td width='60%'><select name='validation_method' class='textbox'>
<option value='image'".($settings2['validation_method'] == "image" ? " selected" : "").">".LAN_555."</option>
<option value='text'".($settings2['validation_method'] == "text" ? " selected" : "").">".LAN_556."</option>
</select></td>
</tr>
</table>
</div>

</td>
</tr>
<tr>
<td class='tbl2'><img align='left' onclick=\"javascript:flipBox('photos')\" name='b_photos' border='0' src='".FUSION_THEME."images/panel_on.gif'>
".LAN_600."</td>
</tr>
<tr>
<td>

<div id='box_photos' style='display:none'>
<table width='100%' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
</tr>
<tr>
<td width='40%'>".LAN_601."<br>
<span class='small2'>".LAN_613."</span></td>
<td width='60%'><input type='text' name='album_image_w' value='".$settings2['album_image_w']."' maxlength='3' class='textbox' style='width:40px;'> x
<input type='text' name='album_image_h' value='".$settings2['album_image_h']."' maxlength='3' class='textbox' style='width:40px;'></td>
</tr>
<tr>
<td width='40%'>".LAN_602."<br>
<span class='small2'>".LAN_613."</span></td>
<td width='60%'><input type='text' name='thumb_image_w' value='".$settings2['thumb_image_w']."' maxlength='3' class='textbox' style='width:40px;'> x
<input type='text' name='thumb_image_h' value='".$settings2['thumb_image_h']."' maxlength='3' class='textbox' style='width:40px;'></td>
</tr>
<tr>
<td width='40%'>".LAN_603."</td>
<td width='60%'><select name='thumb_compression' class='textbox'>
<option value='gd1'".($settings2['thumb_compression'] == "gd1" ? " selected" : "").">".LAN_604."</option>
<option value='gd2'".($settings2['thumb_compression'] == "gd2" ? " selected" : "").">".LAN_605."</option>
</select></td>
</tr>
<tr>
<td width='40%'>".LAN_606."</td>
<td width='60%'><select name='album_comments' class='textbox'>
<option value='1'".($settings2['album_comments'] == "1" ? " selected" : "").">".LAN_508."</option>
<option value='0'".($settings2['album_comments'] == "0" ? " selected" : "").">".LAN_509."</option>
</select></td>
</tr>
<tr>
<td width='40%'>".LAN_607."</td>
<td width='60%'><input type='text' name='albums_per_row' value='".$settings2['albums_per_row']."' maxlength='2' class='textbox' style='width:40px;'></td>
</tr>
<tr>
<td width='40%'>".LAN_608."</td>
<td width='60%'><input type='text' name='albums_per_page' value='".$settings2['albums_per_page']."' maxlength='2' class='textbox' style='width:40px;'></td>
</tr>
<tr>
<td width='40%'>".LAN_609."</td>
<td width='60%'><input type='text' name='thumbs_per_row' value='".$settings2['thumbs_per_row']."' maxlength='2' class='textbox' style='width:40px;'></td>
</tr>
<tr>
<td width='40%'>".LAN_610."</td>
<td width='60%'><input type='text' name='thumbs_per_page' value='".$settings2['thumbs_per_page']."' maxlength='2' class='textbox' style='width:40px;'></td>
</tr>
<tr>
<td width='40%'>".LAN_611."<br>
<span class='small2'>".LAN_613."</span></td>
<td width='60%'><input type='text' name='album_max_w' value='".$settings2['album_max_w']."' maxlength='3' class='textbox' style='width:40px;'> x
<input type='text' name='album_max_h' value='".$settings2['album_max_h']."' maxlength='3' class='textbox' style='width:40px;'></td>
</tr>
<tr>
<td width='40%'>".LAN_612."</td>
<td width='60%'><input type='text' name='album_max_b' value='".$settings2['album_max_b']."' maxlength='10' class='textbox' style='width:100px;'></td>
</tr>
</table>
</div>

</td>
</tr>
<tr>
<td class='tbl2'><img align='left' onclick=\"javascript:flipBox('other')\" name='b_other' border='0' src='".FUSION_THEME."images/panel_on.gif'>
".LAN_650."</td>
</tr>
<tr>
<td>

<div id='box_other' style='display:none'>
<table width='100%' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
</tr>
<tr><td valign='top'>".LAN_651."<br>
<span class='small2'>".LAN_652."<br>
".LAN_653."</small></td>
<td width='60%'><textarea name='bad_words' rows='5' class='textbox' style='width:250px;'>".$settings2['bad_words']."</textarea></td>
</tr>
<tr>
<td width='40%'>".LAN_654."</td>
<td width='60%'><input type='text' name='bad_word_replace' value='".$settings2['bad_word_replace']."' maxlength='128' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td width='40%'>".LAN_655."</td>
<td width='60%'><select name='guestposts' class='textbox'>
<option value='1'".($settings2['guestposts'] == "1" ? " selected" : "").">".LAN_508."</option>
<option value='0'".($settings2['guestposts'] == "0" ? " selected" : "").">".LAN_509."</option>
</select></td>
</tr>
<tr>
<td width='40%'>".LAN_656."</td>
<td width='60%'>
<select name='numofshouts' class='textbox'>
<option".($settings2['numofshouts'] == 5 ? " selected" : "").">5</option>
<option".($settings2['numofshouts'] == 10 ? " selected" : "").">10</option>
<option".($settings2['numofshouts'] == 15 ? " selected" : "").">15</option>
<option".($settings2['numofshouts'] == 20 ? " selected" : "").">20</option>
</select>
</td>
</tr>
<tr>
<td width='40%'>".LAN_657."</td>
<td width='60%'><select name='maintenance' class='textbox' style='width:50px;'>
<option value='1'".($settings2['maintenance'] == "1" ? " selected" : "").">".LAN_502."</option>
<option value='0'".($settings2['maintenance'] == "0" ? " selected" : "").">".LAN_503."</option>
</select></td>
</tr>
<tr><td valign='top'>".LAN_658."</td>
<td width='60%'><textarea name='maintenance_message' rows='5' class='textbox' style='width:250px;'>".stripslashes($settings2['maintenance_message'])."</textarea></td>
</tr>
</table>
</div>

</td>
</tr>
<tr><td align='center'><br>
<input type='submit' name='savesettings' value='".LAN_700."' class='button'></td>
</tr>
</table>
</form>\n";
closetable();

echo "</td>\n";
require_once FUSION_BASE."footer.php";
?>