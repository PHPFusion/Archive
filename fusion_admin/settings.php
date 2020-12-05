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
		$result = dbquery("UPDATE ".$fusion_prefix."settings SET
			sitename='".$_POST['sitename']."',
			siteurl='".stripinput($_POST['siteurl'])."',
			sitebanner='".$_POST['sitebanner']."',
			siteemail='".stripinput($_POST['siteemail'])."',
			siteusername='".stripinput($_POST['username'])."',
			siteintro='".addslashes($_POST['intro'])."',
			description='".$_POST['description']."',
			keywords='".$_POST['keywords']."',
			footer='".addslashes($_POST['footer'])."',
			language='".$_POST['language']."',
			theme='".$_POST['theme']."',
			shortdate='".$_POST['shortdate']."',
			longdate='".$_POST['longdate']."',
			forumdate='".$_POST['forumdate']."',
			timeoffset='".$_POST['timeoffset']."',
			forumpanel='".$_POST['forumpanel']."',
			numofthreads='".$_POST['numofthreads']."',
			attachments='".$_POST['attachments']."',
			attachmax='".$_POST['attachmax']."',
			attachtypes='".$_POST['attachtypes']."',
			album_image_w='".$_POST['album_image_w']."',
			album_image_h='".$_POST['album_image_h']."',
			thumb_image_w='".$_POST['thumb_image_w']."',
			thumb_image_h='".$_POST['thumb_image_h']."',
			album_comments='".$_POST['album_comments']."',
			albums_per_row='".$_POST['albums_per_row']."',
			albums_per_page='".$_POST['albums_per_page']."',
			thumbs_per_row='".$_POST['thumbs_per_row']."',
			thumbs_per_page='".$_POST['thumbs_per_page']."',
			album_max_w='".$_POST['album_max_w']."',
			album_max_h='".$_POST['album_max_h']."',
			album_max_b='".$_POST['album_max_b']."',
			guestposts='".$_POST['guestposts']."',
			numofshouts='".$_POST['numofshouts']."',
			maintenance='".$_POST['maintenance']."',
			maintenance_message='".$_POST['maintenance_message']."'
		");
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
		if ($offset == $settings2['timeoffset']) { $sel = " selected"; } else { $sel = ""; }
		$offsetlist .= "<option$sel>$offset</option>\n";
	}
	opentable(LAN_400);
	echo "<form name='settingsform' method='post' action='$PHP_SELF'>
<table width='500' align='center' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td class='altbg'><img align='left' onclick=\"javascript:flipBox('main')\" name='b_main' border='0' src='".fusion_themedir."images/panel_off.gif'>
".LAN_401."</td>
</tr>
<tr>
<td>

<div id='box_main'>
<table width='100%' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td>".LAN_402."</td>
<td align='right'><input type='text' name='sitename' value='".$settings2['sitename']."' maxlength='255' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td>".LAN_403."</td>
<td align='right'><input type='text' name='siteurl' value='".$settings2['siteurl']."' maxlength='255' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td>".LAN_404."</td>
<td align='right'><input type='text' name='sitebanner' value='".$settings2['sitebanner']."' maxlength='255' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td>".LAN_405."</td>
<td align='right'><input type='text' name='siteemail' value='".$settings2['siteemail']."' maxlength='128' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td>".LAN_406."</td>
<td align='right'><input type='text' name='username' value='".$settings2['siteusername']."' maxlength='32' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td valign='top'>".LAN_407."<br>
<span class='small2'>".LAN_408."</span></td>
<td align='right'><textarea name='intro' rows='5' class='textbox' style='width:250px;'>".stripslashes($settings2['siteintro'])."</textarea></td>
</tr>
<tr>
<td valign='top'>".LAN_409."</td>
<td align='right'><textarea name='description' rows='5' class='textbox' style='width:250px;'>".$settings2['description']."</textarea></td>
</tr>
<tr>
<td valign='top'>".LAN_410."<br>
<span class='small2'>".LAN_411."</span></td>
<td align='right'><textarea name='keywords' rows='5' class='textbox' style='width:250px;'>".$settings2['keywords']."</textarea></td>
</tr>
<tr><td valign='top'>".LAN_412."</td>
<td align='right'><textarea name='footer' rows='5' class='textbox' style='width:250px;'>".stripslashes($settings2['footer'])."</textarea></td>
</tr>
<tr>
<td>".LAN_413."</td>
<td align='right'><select name='language' class='textbox' style='width:100px;'>\n";
	for ($count=0;$lang_list[$count]!="";$count++) {
		if ($lang_list[$count] == $settings2['language']) { $sel = " selected"; } else { $sel = ""; }
		echo "<option$sel>$lang_list[$count]</option>\n";
	}
echo "</select></td>
</tr>
<tr>
<td>".LAN_414."</td>
<td align='right'><select name='theme' class='textbox' style='width:100px;'>\n";
	for ($count=0;$theme_list[$count]!="";$count++) {
		if ($theme_list[$count] == $settings2['theme']) { $sel = " selected"; } else { $sel = ""; }
		echo "<option$sel>$theme_list[$count]</option>\n";
	}
echo "</select></td>
</tr>
</table>
</div>

</td>
</tr>
<tr>
<td class='altbg'><img align='left' onclick=\"javascript:flipBox('timedate')\" name='b_timedate' border='0' src='".fusion_themedir."images/panel_on.gif'>
".LAN_450."</td>
</tr>
<tr>
<td>

<div id='box_timedate' style='display:none'>
<table width='100%' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td>".LAN_451."</td>
<td align='right'><input type='text' name='shortdate' value='".$settings2['shortdate']."' maxlength='50' class='textbox' style='width:150px;'></td>
</tr>
<tr>
<td>".LAN_452."</td>
<td align='right'><input type='text' name='longdate' value='".$settings2['longdate']."' maxlength='50' class='textbox' style='width:150px;'></td>
</tr>
<tr>
<td>".LAN_453."</td>
<td align='right'><input type='text' name='forumdate' value='".$settings2['forumdate']."' maxlength='50' class='textbox' style='width:150px;'></td>
</tr>
<tr>
<td>".LAN_454."</td>
<td align='right'><select name='timeoffset' class='textbox' style='width:75px;'>
$offsetlist</select></td>
</tr>
</table>
</div>

</td>
</tr>
<tr>
<td class='altbg'><img align='left' onclick=\"javascript:flipBox('forum')\" name='b_forum' border='0' src='".fusion_themedir."images/panel_on.gif'>
".LAN_500."</td>
</tr>
<tr>
<td>

<div id='box_forum' style='display:none'>
<table width='100%' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td>".LAN_501."</td>
<td align='right'><select name='forumpanel' class='textbox' style='width:50px;'>
<option value='1'"; if($settings2['forumpanel']=="1"){echo"selected";} echo ">".LAN_502."</option>
<option value='0'"; if($settings2['forumpanel']=="0"){echo"selected";} echo ">".LAN_503."</option>
</select></td>
</td>
</tr>
<tr>
<td>".LAN_505."<br>
<span class='small2'>".LAN_506."</span>
</td>
<td align='right'>
<select name='numofthreads' class='textbox' style='width:50px;'>\n";
if ($settings2['numofthreads'] == 5) { $sel = " selected"; } else { $sel = ""; }
echo "<option".$sel.">5</option>\n";
if ($settings2['numofthreads'] == 10) { $sel = " selected"; } else { $sel = ""; }
echo "<option".$sel.">10</option>\n";
if ($settings2['numofthreads'] == 15) { $sel = " selected"; } else { $sel = ""; }
echo "<option".$sel.">15</option>\n";
if ($settings2['numofthreads'] == 20) { $sel = " selected"; } else { $sel = ""; }
echo "<option".$sel.">20</option>
</select>
</td>
</tr>
<tr>
<td>".LAN_507."</td>
<td align='right'><select name='attachments' class='textbox' style='width:50px;'>
<option value='1'"; if ($settings2['attachments'] == "1") { echo "selected"; } echo ">".LAN_508."</option>
<option value='0'"; if ($settings2['attachments'] == "0") { echo "selected"; } echo ">".LAN_509."</option>
</select></td>
</tr>
<tr>
<td>".LAN_510."<br>
<span class='small2'>".LAN_511."</span></td>
<td align='right'><input type='text' name='attachmax' value='".$settings2['attachmax']."' maxlength='150' class='textbox' style='width:100px;'></td>
</tr>
<tr>
<tr>
<td>".LAN_512."<br>
<span class='small2'>".LAN_513."</span></td>
<td align='right'><input type='text' name='attachtypes' value='".$settings2['attachtypes']."' maxlength='150' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td>".LAN_514."<br>
<span class='small2'><font color='red'>".LAN_515."</font> ".LAN_516."</span></td>
<td align='right' valign='top'>
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
<td class='altbg'><img align='left' onclick=\"javascript:flipBox('photos')\" name='b_photos' border='0' src='".fusion_themedir."images/panel_on.gif'>
".LAN_530."</td>
</tr>
<tr>
<td>

<div id='box_photos' style='display:none'>
<table width='100%' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
</tr>
<tr>
<td>".LAN_531."<br>
<span class='small2'>".LAN_540."</span></td>
<td align='right'><input type='text' name='album_image_w' value='".$settings2['album_image_w']."' maxlength='3' class='textbox' style='width:40px;'> x
<input type='text' name='album_image_h' value='".$settings2['album_image_h']."' maxlength='3' class='textbox' style='width:40px;'></td>
</tr>
<tr>
<td>".LAN_532."<br>
<span class='small2'>".LAN_540."</span></td>
<td align='right'><input type='text' name='thumb_image_w' value='".$settings2['thumb_image_w']."' maxlength='3' class='textbox' style='width:40px;'> x
<input type='text' name='thumb_image_h' value='".$settings2['thumb_image_h']."' maxlength='3' class='textbox' style='width:40px;'></td>
</tr>
<tr>
<td>".LAN_533."</td>
<td align='right'><select name='album_comments' class='textbox' style='width:50px;'>
<option value='1'"; if ($settings2['album_comments'] == "1") { echo "selected"; } echo ">".LAN_508."</option>
<option value='0'"; if ($settings2['album_comments'] == "0") { echo "selected"; } echo ">".LAN_509."</option>
</select></td>
</tr>
<tr>
<td>".LAN_534."</td>
<td align='right'><input type='text' name='albums_per_row' value='".$settings2['albums_per_row']."' maxlength='2' class='textbox' style='width:40px;'></td>
</tr>
<tr>
<td>".LAN_535."</td>
<td align='right'><input type='text' name='albums_per_page' value='".$settings2['albums_per_page']."' maxlength='2' class='textbox' style='width:40px;'></td>
</tr>
<tr>
<td>".LAN_536."</td>
<td align='right'><input type='text' name='thumbs_per_row' value='".$settings2['thumbs_per_row']."' maxlength='2' class='textbox' style='width:40px;'></td>
</tr>
<tr>
<td>".LAN_537."</td>
<td align='right'><input type='text' name='thumbs_per_page' value='".$settings2['thumbs_per_page']."' maxlength='2' class='textbox' style='width:40px;'></td>
</tr>
<tr>
<td>".LAN_538."<br>
<span class='small2'>".LAN_540."</span></td>
<td align='right'><input type='text' name='album_max_w' value='".$settings2['album_max_w']."' maxlength='3' class='textbox' style='width:40px;'> x
<input type='text' name='album_max_h' value='".$settings2['album_max_h']."' maxlength='3' class='textbox' style='width:40px;'></td>
</tr>
<tr>
<td>".LAN_539."</td>
<td align='right'><input type='text' name='album_max_b' value='".$settings2['album_max_b']."' maxlength='10' class='textbox' style='width:100px;'></td>
</tr>
</table>
</div>

</td>
</tr>
<tr>
<td class='altbg'><img align='left' onclick=\"javascript:flipBox('other')\" name='b_other' border='0' src='".fusion_themedir."images/panel_on.gif'>
".LAN_550."</td>
</tr>
<tr>
<td>

<div id='box_other' style='display:none'>
<table width='100%' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
</tr>
<tr>
<td>".LAN_551."</td>
<td align='right'><select name='guestposts' class='textbox' style='width:50px;'>
<option value='1'"; if ($settings2['guestposts'] == "1") { echo "selected"; } echo ">".LAN_508."</option>
<option value='0'"; if ($settings2['guestposts'] == "0") { echo "selected"; } echo ">".LAN_509."</option>
</select></td>
</tr>
<tr>
<td>".LAN_553."</td>
<td align='right'>
<select name='numofshouts' class='textbox' style='width:50px;'>\n";
if ($settings2['numofshouts'] == 5) { $sel = " selected"; } else { $sel = ""; }
echo "<option".$sel.">5</option>\n";
if ($settings2['numofshouts'] == 10) { $sel = " selected"; } else { $sel = ""; }
echo "<option".$sel.">10</option>\n";
if ($settings2['numofshouts'] == 15) { $sel = " selected"; } else { $sel = ""; }
echo "<option".$sel.">15</option>\n";
if ($settings2['numofshouts'] == 20) { $sel = " selected"; } else { $sel = ""; }
echo "<option".$sel.">20</option>
</select>
</td>
</tr>
<tr>
<td>".LAN_554."</td>
<td align='right'><select name='maintenance' class='textbox' style='width:50px;'>
<option value='1'"; if ($settings2['maintenance'] == "1") { echo "selected"; } echo ">".LAN_502."</option>
<option value='0'"; if ($settings2['maintenance'] == "0") { echo "selected"; } echo ">".LAN_503."</option>
</select></td>
</tr>
<tr><td valign='top'>".LAN_555."</td>
<td align='right'><textarea name='maintenance_message' rows='5' class='textbox' style='width:250px;'>".stripslashes($settings2['maintenance_message'])."</textarea></td>
</tr>
</table>
</div>

</td>
</tr>
<tr><td align='center'><br>
<input type='submit' name='savesettings' value='".LAN_590."' class='button'></td>
</tr>
</table>
</form>\n";
	closetable();
}

echo "</td>\n";
require "../footer.php";
?>