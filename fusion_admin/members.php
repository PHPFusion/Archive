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
require fusion_langdir."admin/admin_members.php";
require "navigation.php";

if (!Admin()) header("Location: ../index.php");

if (Admin()) {
	if ($step == "view") {
		$data = dbarray(dbquery("SELECT * FROM ".$fusion_prefix."users WHERE user_id='$user_id'"));
		opentable(LAN_400.$data['user_name']);
		echo "<table align='center' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td align='center' width='110'>";
if ($data['user_avatar']) {
	echo "<img src='".fusion_basedir."fusion_public/avatars/".$data['user_avatar']."'>";
} else {
	echo LAN_415;
}
echo "</td>
<td width='100'>
".LAN_401."<br>
".LAN_402."<br>
".LAN_403."<br>
".LAN_404."<br>
".LAN_405."<br>
".LAN_406."<br>
".LAN_407."<br>
".LAN_408."<br>
".LAN_409."<br>
</td>
<td>\n";
		if ($data['user_hide_email'] != "1" || Admin()) {
			echo "<a href='mailto:".$data['user_email']."'>".$data['user_email']."</a><br>\n";
		} else {
			echo LAN_416."<br>\n";
		}
		if ($data['location']) {
			echo $data['user_location']."<br>\n";
		} else {
			echo LAN_417."<br>\n";
		}
		if ($data['user_birthdate'] != "0000-00-00") {
			$months = explode("|", LAN_MONTHS);
			$user_birthdate = explode("-", $data['user_birthdate']);
			echo $months[number_format($user_birthdate['1'])]." ".number_format($user_birthdate['2'])." ".$user_birthdate['0']."<br>\n";
		} else {
			echo LAN_417."<br>\n";
		}
		if ($data['user_icq']) {
			echo "<a href='http://web.icq.com/wwp?Uin=".$data['user_icq']."'>".$data['user_icq']."</a><br>\n";
		} else {
			echo LAN_417."<br>\n";
		}
		if ($data['user_msn']) {
			echo "<a href='mailto:".$data['user_msn']."'>".$data['user_msn']."</a><br>\n";
		} else {
			echo LAN_417."<br>\n";
		}
		if ($data['user_yahoo']) {
			echo "<a href='http://uk.profiles.yahoo.com/".$data['user_yahoo']."'>".$data['user_yahoo']."</a><br>\n";
		} else {
			echo LAN_417."<br>\n";
		}
		if ($data['user_web']) {
			echo "<a href='".$data['user_web']."'>".$data['user_web']."</a><br>\n";
		} else {
			echo LAN_417."<br>\n";
		}
		echo strftime("%d.%m.%y", $data['user_joined']+($settings['timeoffset']*3600))."<br>\n";
		if ($data['user_lastvisit'] != 0) {
			echo strftime("%d.%m.%y", $data['user_lastvisit']+($settings['timeoffset']*3600))."<br>\n";
		} else {
			echo LAN_410."<br>\n";
		}
		echo "</td>
</tr>
<tr>
<td align='center' colspan='3'>\n";
		if ($data['user_id'] != $userdata['user_id']) {
			echo "<br><a href='sendmessage.php?user_id=".$data['user_id']."'>".LAN_411."</a>\n";
		}
		echo "</td>
</tr>
</table>\n";
		closetable();
	} else if ($step == "edit") {
		if (isset($_POST['savechanges'])) {
			require_once "updateuser.php";
			if ($error == "") {
				opentable(LAN_420);
				echo "<center><br>
".LAN_421."<br><br>
<a href='members.php'>".LAN_422."</a><br><br>
<a href='index.php'>".LAN_423."</a><br><br>
</center>\n";
				closetable();
			} else {
				opentable(LAN_420);
				echo "<center><br>
".LAN_424."<br><br>
$error<br>
<a href='members.php'>".LAN_422."</a><br><br>
<a href='index.php'>".LAN_423."</a><br><br>
</center>\n";
				closetable();
			}
		} else {
			$i = 1;
			$levels = array(1=> USER1, USER2, USER3, USER4);
			$result = dbquery("SELECT * FROM ".$fusion_prefix."users WHERE user_id='$user_id'");
			$data = dbarray($result);
			while ($levels[$i]) {
				if (UserLevel() == "3" && UserLevel() != "4") {
					if ($i != "4") {
						if ($data[user_mod] == $i) {
							$sel = " selected";
						} else {
							$sel = "";
						}
						$modopts .= "<option value='$i'$sel>$levels[$i]</option>\n";
					}
				} else if (UserLevel() == "4") {
					if ($data[user_mod] == $i) {
						$sel = " selected";
					} else {
						$sel = "";
					}
					$modopts .= "<option value='$i'$sel>$levels[$i]</option>\n";
				}
				$i++;
			}
			if ($data['user_birthdate']!="0000-00-00") {
				$user_birthdate = explode("-", $data['user_birthdate']);
				$user_month = number_format($user_birthdate['1']);
				$user_day = number_format($user_birthdate['2']);
				$user_year = $user_birthdate['0'];
			} else {
				$user_month = 0; $user_day = 0; $user_year = 0;
			}
			$months = explode("|", LAN_MONTHS);
			for ($i=0;$months[$i];$i++) {
				if ($user_month == $i) { $sel = " selected"; } else { $sel = ""; }
				$user_months .= "<option value='$i'$sel>$months[$i]</option>\n";
			}
			for ($i=0;$i<32;$i++) {
				if ($user_day == $i) { $sel = " selected"; } else { $sel = ""; }
				$user_days .= "<option value='$i'$sel>";if($i!=0){$user_days.=$i;}else{$user_days.="";}$user_days.="</option>\n";
			}
			for ($i=1940;$i<2005;$i++) {
				if ($user_year == $i) { $sel = " selected"; } else { $sel = ""; }
				$user_years .= "<option value='$i'$sel>$i</option>\n";
			}
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
				if ($offset == $data['user_offset']) { $sel = " selected"; } else { $sel = ""; }
				$offset_list .= "<option$sel>$offset</option>\n";
			}
			opentable(LAN_420);
			echo "<form name='editform' method='post' action='$PHP_SELF?step=edit&user_id=$user_id'>
<table align='center' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td>".LAN_430."<font color='red'>*&nbsp</font></td>
<td><input type='text' name='user_name' value='".$data['user_name']."' maxlength='30' class='textbox' style='width:200px;'></td></tr>
<tr>
<td>".LAN_431."<font color='red'>*&nbsp</font></td>
<td><input type='text' name='user_email' value='".$data['user_email']."' maxlength='100' class='textbox' style='width:200px;'></td></tr>
<tr>
<td>".LAN_432."</td>\n";
if ($data['user_hide_email'] == "1") { $yes = " checked"; $no = ""; } else { $yes = ""; $no = " checked"; } 
echo "<td><input type='radio' name='user_hide_email' value='1'$yes> ".LAN_433." <input type='radio' name='user_hide_email' value='0'$no> ".LAN_434."</td>
</tr>
<tr>
<td>".LAN_435."&nbsp</td>\n";
if (UserLevel() == "3") {
	if (UserLevel() != "4") {
		echo "<td><select name='user_mod' class='textbox' style='width:200px;'>
$modopts</select></td>\n";
	} else {
		echo "<td><input type='hidden' name='user_mod' value='1'>
<div class='textbox' style='width:200px;'>Super Administrator</div></td>\n";
	}
} else {
	echo "<td><select name='user_mod' class='textbox' style='width:200px;'>
$modopts</select></td>\n";
}
echo "</tr>
<tr>
<td>".LAN_436."&nbsp</td>
<td><input type='text' name='user_location' value='".$data['user_location']."' maxlength='50' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td>".LAN_437." <span class='small2'>(mm/dd/yyyy)</span></td>
<td><select name='user_month' class='textbox' style='width:100px;'>
$user_months</select>
<select name='user_day' class='textbox'>
$user_days</select>
<select name='user_year' class='textbox'>
<option value='0'></option>
$user_years</select>
</td>
</tr>
<tr>
<td>".LAN_438."&nbsp</td>
<td><input type='text' name='user_icq' value='".$data['user_icq']."' maxlength='15' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td>".LAN_439."&nbsp</td>
<td><input type='text' name='user_msn' value='".$data['user_msn']."' maxlength='100' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td>".LAN_440."&nbsp</td>
<td><input type='text' name='user_yahoo' value='".$data['user_yahoo']."' maxlength='50' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td>".LAN_441."&nbsp</td>
<td><input type='text' name='user_web' value='".$data['user_web']."' maxlength='200' class='textbox' style='width:200px;'></td>
</tr>
</tr>
<tr>
<td>".LAN_442."</td>
<td><select name='user_theme' class='textbox' style='width:100px;'>\n";
	if ($data['user_theme'] == "Default") { $sel = " selected"; } else { $sel = ""; }
	echo "<option$sel>Default</option>\n";
	for ($count=0;$theme_list[$count]!="";$count++) {
		if ($theme_list[$count] == $data['user_theme']) { $sel = " selected"; } else { $sel = ""; }
		echo "<option$sel>$theme_list[$count]</option>\n";
	}
echo "</select></td>
</tr>
<tr>
<td>".LAN_443."</td>
<td><select name='user_offset' class='textbox' style='width:100px;'>
$offset_list</select></td>
</tr>
<tr>
<td>".LAN_444."</td>
<td>";
	if ($data['user_avatar'] == "") {
		echo "<input type='hidden' name='user_avatar' value='undefined'><span class='textbox'>&nbsp;".LAN_445."&nbsp;</div>";
	} else {
		echo "<input type='text' name='user_avatar' value='".$data['user_avatar']."' maxlength='100' class='textbox' style='width:200px;'><br>
<span class='small2'>".LAN_446."</span>";
	}
	echo "</td>
</tr><tr>
<td valign='top' class='content'>".LAN_447."&nbsp;</td>
<td><textarea name='user_signature' rows='5' class='textbox' style='width:295px' onselect='updatePos(this);' onkeyup='updatePos(this);' onclick='updatePos(this);' ondblclick='updatePos(this);'>".$data['user_sig']."</textarea><br>
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
<td colspan='2'><br><div align='center'>
<input type='submit' name='savechanges' value='".LAN_448."' class='button'></div>
</td>
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
		}
	} else {
		opentable(LAN_490);
		if ($step == "ban") {
			if ($act == on) {
				if ($user_id != 1) {
					$result = dbquery("UPDATE ".$fusion_prefix."users SET user_ban='1' WHERE user_id='$user_id'");
					echo "<center>".LAN_470."<br><br></center>\n";
				} else {
					echo "<center>".LAN_471."<br><br></center>\n";
				}
			} else {
				$result = dbquery("UPDATE ".$fusion_prefix."users SET user_ban='0' WHERE user_id='$user_id'");
				echo "<center>".LAN_472."<br><br></center>\n";
			}
		}	
		if ($step == "delete") {
			if ($user_id != 1) {
				$result = dbquery("DELETE FROM ".$fusion_prefix."users WHERE user_id='$user_id'");
				echo "<center>".LAN_473."<br><br></center>\n";
			} else {
				echo "<center>".LAN_474."<br><br></center>\n";
			}
		}
		echo "<script>
function DeleteMember()
{
	return confirm('".LAN_475."');
}
</script>
<table align='center' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td width='150' class='altbg'>".LAN_491."</td>
<td align='center' class='altbg'>".LAN_492."</td>
<td class='altbg'>".LAN_493."</td>
</tr>\n";
		$itemsperpage = 20;
		$result = dbquery("SELECT * FROM ".$fusion_prefix."users");
		$rows = dbrows($result);
		if (!$rowstart) $rowstart = 0; 
		$totalpages = ceil($rows / $itemsperpage);	
		$currentpage = $rowstart / $itemsperpage + 1;
		$result = dbquery("SELECT * FROM ".$fusion_prefix."users ORDER BY user_mod DESC, user_name LIMIT $rowstart,$itemsperpage");
		while ($data = dbarray($result)) {
			echo "<tr>
<td><a href='$PHP_SELF?step=view&user_id=".$data['user_id']."'>".$data['user_name']."</a></td>
<td align='center' style='text-indent:2px'>".getmodlevel($data['user_mod'])."</td>
<td><a href='$PHP_SELF?step=edit&user_id=".$data['user_id']."'>".LAN_495."</a> - ";
			if ($data['user_ban'] == "1") {
				echo "<a href='$PHP_SELF?step=ban&act=off&user_id=".$data['user_id']."'>".LAN_496."</a> - ";
			} else {
				echo "<a href='$PHP_SELF?step=ban&act=on&user_id=".$data['user_id']."'>".LAN_497."</a> - ";
			}
			echo "<a href='$PHP_SELF?step=delete&rowstart=$rowstart&user_id=".$data['user_id']."' onClick='return DeleteMember()'>".LAN_498."</a></td>
</tr>\n";
		}
		echo "</table>\n";	
		closetable();
		echo "<div align='center' style='margin-top:5px;'>
".makePageNav($rowstart,$itemsperpage,$rows,3,"$PHP_SELF?")."
</div>\n";
	}
}

echo "</td>\n";
require "../footer.php";
?>