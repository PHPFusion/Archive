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
@require_once "fusion_config.php";
require_once "fusion_core.php";
require_once "subheader.php";
require_once "side_left.php";
include FUSION_LANGUAGES.FUSION_LAN."register.php";
include FUSION_LANGUAGES.FUSION_LAN."user_fields.php";
include FUSION_INCLUDES."sendmail_include.php";

if (iMEMBER) { header("Location: index.php"); exit; }

if ($settings['enable_registration']) {

if (isset($activate)) {
	$result = dbquery("SELECT * FROM ".$fusion_prefix."new_users WHERE user_code='$activate'");
	if (dbrows($result) != 0) {
		$data = dbarray($result);
		$user_info = unserialize($data['user_info']);
		$result = dbquery("INSERT INTO ".$fusion_prefix."users VALUES('', '".$user_info['user_name']."', '".md5($user_info['user_password'])."', '".$user_info['user_email']."', '".$user_info['user_hide_email']."', '', '0000-00-00', '', '', '', '', 'Default', '0', '', '', '0', '".time()."', '0', '".FUSION_IP."', '', '', '250', '0')");
		$result = dbquery("DELETE FROM ".$fusion_prefix."new_users WHERE user_code='$activate'");	
		opentable(LAN_401);
		echo "<center><br>
".LAN_453."<br><br>
</center>\n";
		closetable();
	} else {
		header("Location: index.php");
	}
} else if (isset($_POST['register'])) {
	$username = trim(chop(eregi_replace(" +", " ", $_POST['username'])));
	
	if ($username == "" || $_POST['password1'] == "" || $_POST['email'] == "") $error .= LAN_402."<br>\n";
	
	if (!preg_match("/^[-0-9A-Z_@\s]+$/i", $username)) $error .= LAN_403."<br>\n";
	
	if (preg_match("/^[0-9A-Z@]{6,20}$/i", $_POST['password1'])) {
		if ($_POST['password1'] != $_POST['password2']) $error .= LAN_404."<br>\n";
	} else {
		$error .= LAN_405."<br>\n";
	}
 
	if (!preg_match("/^[-0-9A-Z_\.]{1,50}@([-0-9A-Z_\.]+\.){1,50}([0-9A-Z]){2,4}$/i", $_POST['email'])) {
		$error .= LAN_406."<br>\n";
	}
	
	$email_domain = substr(strrchr($_POST['email'], "@"), 1);
	$result = dbquery("SELECT * FROM ".$fusion_prefix."blacklist WHERE blacklist_email='".$_POST['email']."' OR blacklist_email='$email_domain'");
	if (dbrows($result) != 0) $error = LAN_411."<br>\n";
	
	$result = dbquery("SELECT * FROM ".$fusion_prefix."users WHERE user_name='$username'");
	if (dbrows($result) != 0) $error = LAN_407."<br>\n";
	
	$result = dbquery("SELECT * FROM ".$fusion_prefix."users WHERE user_email='".$_POST['email']."'");
	if (dbrows($result) != 0) $error = LAN_408."<br>\n";
	
	if ($settings['email_verification'] == "1") {
		$result = dbquery("SELECT * FROM ".$fusion_prefix."new_users WHERE user_email='".$_POST['email']."'");
		if (dbrows($result) != 0) $error = LAN_409."<br>\n";
	}
	
	if ($settings['display_validation'] == "1") {
		$result = dbquery("SELECT * FROM ".$fusion_prefix."temp WHERE temp_dec='".$_POST['user_code']."'");
		if (dbrows($result) == 0) $error .= LAN_410."<br>\n";
	} else {
		$result = dbquery("DELETE FROM ".$fusion_prefix."temp WHERE temp_dec='".$_POST['user_code']."'");
	}
	
	if ($settings['email_verification'] == "0") {
		if ($_POST['user_icq']) $user_icq = stripinput(trim(chop($_POST['user_icq'])));
		if ($_POST['user_msn']) $user_msn = stripinput(trim(chop($_POST['user_msn'])));
		if ($_POST['user_yahoo']) $user_yahoo = stripinput(trim(chop($_POST['user_yahoo'])));
		if ($_POST['user_location']) $user_location = stripinput(trim(chop($_POST['user_location'])));
		if ($_POST['user_web']) $user_web = stripinput(trim(chop($_POST['user_web'])));
		if ($_POST['user_month'] != 0 && $_POST['user_day'] != 0 && $_POST['user_year'] != 0) {
			$user_birthdate = (isNum($_POST['user_year']) ? $_POST['user_year'] : "0000")
			."-".(isNum($_POST['user_month']) ? $_POST['user_month'] : "00")
			."-".(isNum($_POST['user_day']) ? $_POST['user_day'] : "00");
		} else {
			$user_birthdate = "0000-00-00";
		}
		if ($_POST['user_sig']) $user_sig = stripinput(trim(chop($_POST['user_sig'])));
	}
	if ($error == "") {
		if ($settings['email_verification'] == "1") {
			mt_srand((double)microtime()*1000000);
			for ($i=0;$i<=7;$i++) { $salt .= chr(rand(97, 122)); }
			$user_code = md5($_POST['email'].$salt);
			$activation_url = $settings['siteurl']."register.php?activate=".$user_code;
			if (sendemail($username,$_POST['email'],$settings['siteusername'],$settings['siteemail'],"Welcome to ".$settings['sitename'], LAN_450.$activation_url)) {
				$user_info = serialize(array(
					"user_name" => $username,
					"user_password" => $_POST['password1'],
					"user_email" => $_POST['email'],
					"user_hide_email" => $_POST['hide_email']
				));
				$result = dbquery("INSERT INTO ".$fusion_prefix."new_users VALUES('$user_code', '".$_POST['email']."', '".time()."', '$user_info')");
				opentable(LAN_400);
				echo "<center><br>\n".LAN_452."<br><br>\n</center>\n";
				closetable();
			} else {
				opentable(LAN_454);
				echo "<center><br>\n".LAN_455."<br><br>\n</center>\n";
				closetable();
			}
		} else {
			$result = dbquery("INSERT INTO ".$fusion_prefix."users VALUES('', '$username', md5('$password1'), '$email', '$hide_email', '$user_location', '$user_birthdate', '$user_icq', '$user_msn', '$user_yahoo', '$user_web', '".$_POST['user_theme']."', '".$_POST['user_offset']."', '', '$user_sig', '0', '".time()."', '0', '".FUSION_IP."', '', '', '250', '0')");
			opentable(LAN_400);
			echo "<center><br>\n".LAN_451."<br><br>\n</center>\n";
			closetable();
		}
	} else {
		opentable(LAN_454);
		echo "<center><br>\n".LAN_456."<br><br>\n$error<br>\n<a href='".FUSION_SELF."'>".LAN_457."</a></div></br>\n";
		closetable();
	}
} else {
	if ($settings['email_verification'] == "0") {
		$handle = opendir(FUSION_THEMES);
		while ($folder = readdir($handle)) if (!in_array($folder, array(".", "..", "/", "index.php"))) $theme_list[] = $folder;
		closedir($handle); sort($theme_list); array_unshift($theme_list, "Default");
		for ($i=-13;$i<17;$i++) {
			if ($i > 0) { $offset="+".$i; } else { $offset=$i; }
			$offset_list .= "<option".($offset == "0" ? " selected" : "").">$offset</option>\n";
		}
	}
	if ($settings['display_validation'] == "1") {
		mt_srand((double)microtime()*1000000);
		$maxran = 1000000;
		$rand_num = mt_rand(0, $maxran);
		$result = dbquery("INSERT INTO ".$fusion_prefix."temp VALUES('', ".time().", md5('$rand_num'), '$rand_num')");
	}
	opentable(LAN_400);
	echo "<center>".LAN_500."\n";
	if ($settings['email_verification'] == "1") echo LAN_501."\n";
	echo LAN_502;
	if ($settings['email_verification'] == "1") echo "\n".LAN_503;
	echo "</center><br>
<table align='center' cellspacing='0' cellpadding='0' class='tbl'>
<form name='inputform' method='post' action='".FUSION_SELF."' onSubmit='return ValidateForm(this)'>
<tr>
<td>".LNU_001."<span style='color:#ff0000'>*</span></td>
<td><input type='text' name='username' maxlength='30' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td>".LNU_002."<span style='color:#ff0000'>*</span></td>
<td><input type='password' name='password1' maxlength='20' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td>".LNU_004."<span style='color:#ff0000'>*</span></td>
<td><input type='password' name='password2' maxlength='20' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td>".LNU_005."<span style='color:#ff0000'>*</span></td>
<td><input type='text' name='email' maxlength='100' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td>".LNU_006."</td>
<td><input type='radio' name='hide_email' value='1'>".LNU_007."<input type='radio' name='hide_email' value='0' checked>".LNU_008."</td>
</tr>\n";
	if ($settings['display_validation'] == "1") {
		echo "<tr>\n<td>".LAN_504."</td>\n<td>";
		if ($settings['validation_method'] == "image") {
			echo "<img src='".FUSION_INCLUDES."create_img_include.php?img_code=".md5($rand_num)."'>";
		} else {
			echo "<b>$rand_num</b>";
		}
		echo "</td>\n</tr>\n";
		unset($rand_num);
		echo "<tr>
<td>".LAN_505."<span style='color:#ff0000'>*</span></td>
<td><input type='text' name='user_code' class='textbox' style='width:100px'></td>
</tr>\n";
	}
	if ($settings['email_verification'] == "0") {
		echo "<tr>
<td>".LNU_009."</td>
<td><input type='text' name='user_location' maxlength='50' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td>".LNU_010." <span class='small2'>(mm/dd/yyyy)</span></td>
<td><select name='user_month' class='textbox'>\n<option> </option>\n";
		for ($i=1;$i<=12;$i++) echo "<option".($user_month == $i ? " selected" : "").">$i</option>\n";
		echo "</select>\n<select name='user_day' class='textbox'>\n<option> </option>\n";
		for ($i=1;$i<=31;$i++) echo "<option".($user_day == $i ? " selected" : "").">$i</option>\n";
		echo "</select>\n<select name='user_year' class='textbox'>\n<option> </option>\n";
		for ($i=1900;$i<=2004;$i++) echo "<option".($user_year == $i ? " selected" : "").">$i</option>\n";
		echo "</select>
</td>
</tr>
<tr>
<td>".LNU_011."</td>
<td><input type='text' name='user_icq' maxlength='15' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td>".LNU_012."</td>
<td><input type='text' name='user_msn' maxlength='100' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td>".LNU_013."</td>
<td>
<input type='text' name='user_yahoo' maxlength='100' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td>".LNU_014."</td>
<td><input type='text' name='user_web' maxlength='100' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td>".LNU_015."</td>
<td><select name='user_theme' class='textbox' style='width:200px;'>\n";
		for ($count=0;$theme_list[$count]!="";$count++) echo "<option>".$theme_list[$count]."</option>\n";
		echo "</select></td>
</tr>
<tr>
<td>".LNU_016."</td>
<td><select name='user_offset' class='textbox'>
$offset_list</select></td>
</tr>
<tr>
<td valign='top'>".LNU_020."</td>
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
</tr>\n";
	}
	echo "<tr>
<td align='center' colspan='2'><br>
<input type='submit' name='register' value='".LAN_506."' class='button'>
</td>
</tr>
</form>
</table>";
	closetable();
	echo "<script language='JavaScript'>
function ValidateForm(frm) {
	if (frm.username.value==\"\") {
		alert(\"".LAN_550."\");
		return false;
	}
	if (frm.password1.value==\"\") {
		alert(\"".LAN_551."\");
		return false;
	}
	if (frm.email.value==\"\") {
		alert(\"".LAN_552."\");
		return false;
	}
}
</script>\n";
}

} else {
	opentable(LAN_400);
	echo "<center><br>
".LAN_507."<br><br>
</center>\n";
	closetable();
}

require_once "side_right.php";
require_once "footer.php";
?>