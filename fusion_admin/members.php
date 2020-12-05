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

include FUSION_LANGUAGES.FUSION_LAN."admin/admin_members.php";
include FUSION_LANGUAGES.FUSION_LAN."user_fields.php";

if (!checkrights("D")) { header("Location:../index.php"); exit; }

if (!isset($step)) $step = "";

if ($step == "add") {
	if (isset($_POST['add_user'])) {
		$error = "";
		
		$username = trim(chop(eregi_replace(" +", " ", $_POST['username'])));
		
		if ($username == "" || $_POST['password1'] == "" || $_POST['email'] == "") $error .= LAN_451."<br>\n";
		
		if (!preg_match("/^[-0-9A-Z_@\s]+$/i", $username)) $error .= LAN_452."<br>\n";
		
		if (preg_match("/^[0-9A-Z@]{6,20}$/i", $_POST['password1'])) {
			if ($_POST['password1'] != $_POST['password2']) $error .= LAN_456."<br>\n";
		} else {
			$error .= LAN_457."<br>\n";
		}
	 
		if (!preg_match("/^[-0-9A-Z_\.]{1,50}@([-0-9A-Z_\.]+\.){1,50}([0-9A-Z]){2,4}$/i", $_POST['email'])) {
			$error .= LAN_454."<br>\n";
		}
		
		$result = dbquery("SELECT * FROM ".$fusion_prefix."users WHERE user_name='$username'");
		if (dbrows($result) != 0) $error = LAN_453."<br>\n";
		
		$result = dbquery("SELECT * FROM ".$fusion_prefix."users WHERE user_email='".$_POST['email']."'");
		if (dbrows($result) != 0) $error = LAN_455."<br>\n";
		
		if ($error == "") {
			$result = dbquery("INSERT INTO ".$fusion_prefix."users VALUES('', '$username', md5('$password1'), '$email', '$hide_email', '', '0000-00-00', '', '', '', '', 'Default', '0', '', '', '0', '".time()."', '0', '".FUSION_IP."', '', '', '250', '0')");
			opentable(LAN_480);
			echo "<center><br>
".LAN_481."<br><br>
<a href='members.php'>".LAN_432."</a><br><br>
<a href='index.php'>".LAN_433."</a><br><br>
</center>\n";
			closetable();
		} else {
			opentable(LAN_480);
			echo "<center><br>
".LAN_482."<br><br>
$error<br>
<a href='members.php'>".LAN_432."</a><br><br>
<a href='index.php'>".LAN_433."</a><br><br>
</center>\n";
			closetable();
		}
	} else {
		opentable(LAN_480);
		echo "<form name='addform' method='post' action='".FUSION_SELF."?step=add' onSubmit='return ValidateForm(this)'>
<table align='center' cellspacing='0' cellpadding='0' class='tbl'>
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
</tr>
</tr>
<tr>
<td align='center' colspan='2'><br>
<input type='submit' name='add_user' value='".LAN_480."' class='button'>
</td>
</tr>
</table>
</form>\n";
		closetable();
	}
} else if ($step == "view") {
	$data = dbarray(dbquery("SELECT * FROM ".$fusion_prefix."users WHERE user_id='$user_id'"));
	opentable(LAN_470.$data['user_name']);
	echo "<table align='center' cellpadding='0' cellspacing='0'>
<tr>\n<td class='tbl2' colspan='3'><b>".LAN_471."</b></td>\n\n</tr>
<tr>\n<td align='center' width='150' rowspan='7' class='tbl'>\n";
	
	echo ($data['user_avatar'] ? "<img src='".FUSION_PUBLIC."avatars/".$data['user_avatar']."'>" : LNU_046)."\n</td>\n";
	
	echo "<td width='125' class='tbl'>".LNU_005."</td>\n<td class='tbl'>\n";
	echo ($data['user_hide_email'] != "1" || iADMIN ? "<a href='mailto:".$data['user_email']."'>".$data['user_email']."</a>" : LNU_047)."</td>\n</tr>\n";
	
	echo "<tr>\n<td class='tbl'>".LNU_009."</td>\n<td class='tbl'>\n";
	echo ($data['user_location'] ? $data['user_location'] : LNU_048)."</td>\n</tr>\n";
	
	echo "<tr>\n<td class='tbl'>".LNU_010."</td>\n<td class='tbl'>";
	if ($data['user_birthdate'] != "0000-00-00") {
		$months = explode("|", LAN_MONTHS);
		$user_birthdate = explode("-", $data['user_birthdate']);
		echo $months[number_format($user_birthdate['1'])]." ".number_format($user_birthdate['2'])." ".$user_birthdate['0']."</td>\n</tr>\n";
	} else {
		echo LNU_048."</td>\n</tr>\n";
	}
	
	echo "<tr>\n<td class='tbl'>".LNU_011."</td>\n<td class='tbl'>\n";
	echo ($data['user_icq'] ? $data['user_icq'] : LNU_048)."</td>\n</tr>\n";
	
	echo "<tr>\n<td class='tbl'>".LNU_012."</td>\n<td class='tbl'>\n";
	echo ($data['user_msn'] ? $data['user_msn'] : LNU_048)."</td>\n</tr>\n";
	
	echo "<tr>\n<td class='tbl'>".LNU_013."</td>\n<td class='tbl'>\n";
	echo ($data['user_yahoo'] ? $data['user_yahoo'] : LNU_048)."</td>\n</tr>\n";
	
	echo "<tr>\n<td class='tbl'>".LNU_014."</td>\n<td class='tbl'>";
	if ($data['user_web']) {
		$urlprefix = !strstr($data['user_web'], "http://") ? "http://" : "";
		echo "<a href='".$urlprefix.$data['user_web']."' target='_blank'>".$data['user_web']."</a></td>\n</tr>\n";
	} else {
		echo LNU_048."</td>\n</tr>\n";
	}
	
	echo "<tr>\n<td class='tbl2' colspan='3'><b>".LAN_472."</b></td>\n\n</tr>\n";
	
	echo "<tr>\n<td class='tbl'>".LNU_040."</td>\n<td class='tbl' colspan='2'>\n";
	echo showdate("longdate", $data['user_joined'])."</td>\n</tr>\n";
	
	echo "<tr>\n<td class='tbl'>".LNU_041."</td>\n<td class='tbl' colspan='2'>\n";
	echo dbcount("(shout_id)", "shoutbox", "shout_name='".$data['user_id']."'")."</td>\n</tr>\n";
	
	echo "<tr>\n<td class='tbl'>".LNU_042."</td>\n<td class='tbl' colspan='2'>\n";
	echo dbcount("(comment_id)", "comments", "comment_name='".$data['user_id']."'")."</td>\n</tr>\n";
	
	echo "<tr>\n<td class='tbl'>".LNU_043."</td>\n<td class='tbl' colspan='2'>\n";
	echo dbcount("(post_id)", "posts", "post_author='".$data['user_id']."'")."</td>\n</tr>\n";
	
	echo "<tr>\n<td class='tbl'>".LNU_044."</td>\n<td class='tbl' colspan='2'>\n";
	echo ($data['user_lastvisit'] != 0 ? showdate("longdate", $data['user_lastvisit']) : LNU_049)."</td>\n</tr>\n";

	echo "<tr>\n<td class='tbl'>".LNU_050."</td>\n<td class='tbl' colspan='2'>\n";
	echo $data['user_ip']."</td>\n</tr>\n";
	
	echo "<tr>\n<td class='tbl'>".LNU_045."</td>\n<td class='tbl' colspan='2'>\n";
	echo getuserlevel($data['user_level'])."</td>\n</tr>\n";
	
	if ($data['user_groups']) {
		echo "<tr>\n<td class='tbl2' colspan='3'><b>".LAN_473."</b></td>\n\n</tr>\n<tr>\n<td class='tbl' colspan='3'>\n";
		$user_groups = (strpos($data['user_groups'], ".") == 0 ? explode(".", substr($data['user_groups'], 1)) : explode(".", $data['user_groups']));
		for ($i = 0;$i < count($user_groups);$i++) {
			echo getgroupname($user_groups[$i]);
			if ($i != (count($user_groups)-1)) echo ", ";
		}
		echo "</td>\n</tr>\n";
	}
	
	if ($data['user_id'] != $userdata['user_id']) {
		echo "<tr><td align='center' colspan='3' class='tbl'><br>\n<a href='".FUSION_BASE."messages.php?step=send&user_id=".$data['user_id']."'>".LNU_060."</a>\n";
		echo "</td>\n</tr>\n";
	}

	echo "</table>\n";
	closetable();
} else if ($step == "edit") {
	if (isset($_POST['savechanges'])) {
		require_once "updateuser.php";
		if ($error == "") {
			opentable(LAN_430);
			echo "<center><br>
".LAN_431."<br><br>
<a href='members.php'>".LAN_432."</a><br><br>
<a href='index.php'>".LAN_433."</a><br><br>
</center>\n";
			closetable();
		} else {
			opentable(LAN_430);
			echo "<center><br>
".LAN_434."<br><br>
$error<br>
<a href='members.php'>".LAN_432."</a><br><br>
<a href='index.php'>".LAN_433."</a><br><br>
</center>\n";
			closetable();
		}
	} else {
		$result = dbquery("SELECT * FROM ".$fusion_prefix."users WHERE user_id='$user_id'");
		$data = dbarray($result);
		if ($data['user_birthdate']!="0000-00-00") {
			$user_birthdate = explode("-", $data['user_birthdate']);
			$user_month = number_format($user_birthdate['1']);
			$user_day = number_format($user_birthdate['2']);
			$user_year = $user_birthdate['0'];
		} else {
			$user_month = 0; $user_day = 0; $user_year = 0;
		}
		$handle = opendir(FUSION_THEMES);
		while ($folder = readdir($handle)) if (!in_array($folder, array(".", "..", "/", "index.php"))) $theme_list[] = $folder;
		closedir($handle); sort($theme_list); array_unshift($theme_list, "Default"); $offset_list = "";
		for ($i=-13;$i<17;$i++) {
			if ($i > 0) { $offset = "+".$i; } else { $offset = $i; }
			$offset_list .= "<option".($offset == $data['user_offset'] ? " selected" : "").">$offset</option>\n";
		}
		opentable(LAN_430);
		echo "<form name='inputform' method='post' action='".FUSION_SELF."?step=edit&user_id=$user_id' enctype='multipart/form-data'>
<table align='center' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td>".LNU_001."<font color='red'>*&nbsp</font></td>
<td><input type='text' name='user_name' value='".$data['user_name']."' maxlength='30' class='textbox' style='width:200px;'></td>
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
<td>".LNU_005."<font color='red'>*&nbsp</font></td>
<td><input type='text' name='user_email' value='".$data['user_email']."' maxlength='100' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td>".LNU_006."</td>
<td><input type='radio' name='user_hide_email' value='1'".($data['user_hide_email'] == "1" ? " checked" : "").">".LNU_007."
<input type='radio' name='user_hide_email' value='0'".($data['user_hide_email'] == "0" ? " checked" : "").">".LNU_008."</td>
</tr>
<tr>
<td>".LNU_009."</td>
<td><input type='text' name='user_location' value='".$data['user_location']."' maxlength='50' class='textbox' style='width:200px;'></td>
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
<td><input type='text' name='user_icq' value='".$data['user_icq']."' maxlength='15' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td>".LNU_012."</td>
<td><input type='text' name='user_msn' value='".$data['user_msn']."' maxlength='100' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td>".LNU_013."</td>
<td><input type='text' name='user_yahoo' value='".$data['user_yahoo']."' maxlength='50' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td>".LNU_014."</td>
<td><input type='text' name='user_web' value='".$data['user_web']."' maxlength='200' class='textbox' style='width:200px;'></td>
</tr>
</tr>
<tr>
<td>".LNU_015."</td>
<td><select name='user_theme' class='textbox' style='width:100px;'>\n";
for ($count=0;$theme_list[$count]!="";$count++) {
echo "<option".($theme_list[$count] == $data['user_theme'] ? " selected" : "").">$theme_list[$count]</option>\n";
}
echo "</select></td>
</tr>
</tr>
<tr>
<td>".LNU_016."</td>
<td><select name='user_offset' class='textbox' style='width:100px;'>
$offset_list</select></td>
</tr>\n";
if (!$data['user_avatar']) {
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
<td><textarea name='user_sig' rows='5' class='textbox' style='width:295px'>".$data['user_sig']."</textarea><br>
<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onClick=\"addText('user_sig', '[b]', '[/b]');\">
<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onClick=\"addText('user_sig', '[i]', '[/i]');\">
<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onClick=\"addText('user_sig', '[u]', '[/u]');\">
<input type='button' value='url' class='button' style='width:30px;' onClick=\"addText('user_sig', '[url]', '[/url]');\">
<input type='button' value='mail' class='button' style='width:35px;' onClick=\"addText('user_sig', '[mail]', '[/mail]');\">
<input type='button' value='img' class='button' style='width:30px;' onClick=\"addText('user_sig', '[img]', '[/img]');\">
<input type='button' value='center' class='button' style='width:45px;' onClick=\"addText('user_sig', '[center]', '[/center]');\">
<input type='button' value='small' class='button' style='width:40px;' onClick=\"addText('user_sig', '[small]', '[/small]');\">
</tr>
<tr>
<td colspan='2'><br><div align='center'>\n";
if ($data['user_avatar']) {
echo LNU_017."<br>\n<img src='".FUSION_PUBLIC."avatars/".$data['user_avatar']."'><br>
<input type='checkbox' name='del_avatar' value='y'> ".LNU_019."
<input type='hidden' name='user_avatar' value='".$data['user_avatar']."'><br><br>\n";
}
echo "<input type='hidden' name='user_hash' value='".$data['user_password']."'>
<input type='submit' name='savechanges' value='".LAN_440."' class='button'></div>
</td>
</tr>
</table>
</form>\n";
		closetable();
	}
} else {
	opentable(LAN_400);
	if ($step == "ban") {
		if ($act == "on") {
			if ($user_id != 1) {
				$result = dbquery("UPDATE ".$fusion_prefix."users SET user_ban='1' WHERE user_id='$user_id'");
				echo "<center>".LAN_420."<br><br></center>\n";
			}
		} else if ($act == "off") {
			$result = dbquery("UPDATE ".$fusion_prefix."users SET user_ban='0' WHERE user_id='$user_id'");
			echo "<center>".LAN_421."<br><br></center>\n";
		}
	}	
	if ($step == "delete") {
		if ($user_id != 1) {
			$result = dbquery("DELETE FROM ".$fusion_prefix."users WHERE user_id='$user_id'");
			$result = dbquery("DELETE FROM ".$fusion_prefix."articles WHERE article_name='$user_id'");
			$result = dbquery("DELETE FROM ".$fusion_prefix."comments WHERE comment_name='$user_id'");
			$result = dbquery("DELETE FROM ".$fusion_prefix."messages WHERE message_to='$user_id'");
			$result = dbquery("DELETE FROM ".$fusion_prefix."messages WHERE message_from='$user_id'");
			$result = dbquery("DELETE FROM ".$fusion_prefix."news WHERE news_name='$user_id'");
			$result = dbquery("DELETE FROM ".$fusion_prefix."poll_votes WHERE vote_user='$user_id'");
			$result = dbquery("DELETE FROM ".$fusion_prefix."ratings WHERE rating_user='$user_id'");
			$result = dbquery("DELETE FROM ".$fusion_prefix."shoutbox WHERE shout_name='$user_id'");
			$result = dbquery("SELECT * FROM ".$fusion_prefix."threads WHERE thread_author='$user_id'");
			while ($data = dbarray($result)) {
				$result2 = dbquery("DELETE FROM ".$fusion_prefix."posts WHERE thread_id='".$data['thread_id']."'");
			}
			$result = dbquery("DELETE FROM ".$fusion_prefix."threads WHERE thread_author='$user_id'");
			$result = dbquery("DELETE FROM ".$fusion_prefix."posts WHERE post_author='$user_id'");
			echo "<center>".LAN_422."<br><br></center>\n";
		}
	}
	if (!isset($sortby)) $sortby = "all";
	$orderby = ($sortby == "all" ? "" : " WHERE user_name LIKE '$sortby%'");
	$result = dbquery("SELECT * FROM ".$fusion_prefix."users".$orderby."");
	$rows = dbrows($result);
	if (!isset($rowstart)) $rowstart = 0; 
	$result = dbquery("SELECT * FROM ".$fusion_prefix."users".$orderby." ORDER BY user_level DESC, user_name LIMIT $rowstart,20");
	if ($rows != 0) {
		echo "<table width='430' align='center' cellpadding='0' cellspacing='0' class='tbl'>
<tr>
<td class='tbl2'>".LAN_401." [<a href='".FUSION_SELF."?step=add'>".LAN_402."</a>]</td>
<td align='center' class='tbl2'>".LAN_403."</td>
<td align='right' class='tbl2'>".LAN_404."</td>
</tr>\n";
		while ($data = dbarray($result)) {
			echo "<tr>\n<td><a href='".FUSION_SELF."?step=view&user_id=".$data['user_id']."'>".$data['user_name']."</a></td>
<td align='center' style='text-indent:2px'>".getuserlevel($data['user_level'])."</td>\n<td align='right'>";
			if (iUSER >= $data['user_level'] && $data['user_id'] != 1) {
				echo "<a href='".FUSION_SELF."?step=edit&user_id=".$data['user_id']."'>".LAN_406."</a>\n";
				if ($data['user_ban'] == "1") {
					echo "- <a href='".FUSION_SELF."?step=ban&act=off&sortby=$sortby&rowstart=$rowstart&user_id=".$data['user_id']."'>".LAN_407."</a>\n";
				} else {
					echo "- <a href='".FUSION_SELF."?step=ban&act=on&sortby=$sortby&rowstart=$rowstart&user_id=".$data['user_id']."'>".LAN_408."</a>\n";
				}
				echo "- <a href='".FUSION_SELF."?step=delete&sortby=$sortby&rowstart=$rowstart&user_id=".$data['user_id']."' onclick='return DeleteMember()'>".LAN_409."</a>";
			}
			echo "</td>\n</tr>\n";
		}
		echo "</table>\n";
	} else {
		echo "<center><br>\n".LAN_410."$sortby<br><br>\n</center>\n";
	}
	$search = array(
		"A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R",
		"S","T","U","V","W","X","Y","Z","0","1","2","3","4","5","6","7","8","9"
	);
	echo "<hr><table align='center' cellpadding='0' cellspacing='1' class='tbl-border'>\n<tr>\n";
	echo "<td rowspan='2' class='tbl2'><a href='".FUSION_SELF."?sortby=all'>".LAN_411."</a></td>";
	for ($i=0;$i < 36;$i++) {
		echo "<td align='center' class='tbl1'><div class='small'><a href='".FUSION_SELF."?sortby=".$search[$i]."'>".$search[$i]."</a></div></td>";
		echo ($i==17 ? "<td rowspan='2' class='tbl2'><a href='".FUSION_SELF."?sortby=all'>".LAN_411."</a></td>\n</tr>\n<tr>\n" : "\n");
	}
	echo "</tr>\n</table>\n";
	closetable();
	echo "<div align='center' style='margin-top:5px;'>\n".makePageNav($rowstart,20,$rows,3,FUSION_SELF."?sortby=$sortby&")."\n</div>\n";
	echo "<script language='JavaScript'>
function DeleteMember() {
	return confirm('".LAN_423."');
}
</script>\n";
}

echo "</td>\n";
require_once FUSION_BASE."footer.php";
?>