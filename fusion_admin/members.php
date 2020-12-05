<?
/*
-------------------------------------------------------
	PHP-Fusion
	-----------------------------------------------
	© Nick Jones 2002-2004
	http://www.digitaldominion.co.uk
	nick@digitaldominion.co.uk
	-----------------------------------------------
	Released under the terms and conditions of the
	GNU General Public License (http://gnu.org).
-------------------------------------------------------
*/
@require "../fusion_config.php";
require "../header.php";
require fusion_basedir."subheader.php";
require fusion_langdir."admin/admin_members.php";

require "navigation.php";
require "userinfo.php";
echo "</td>
<td valign=\"top\" class=\"bodybg\">\n";

if ($userdata[user_mod] > "1" && $userdata != "") {
	if ($step == "view") {
		$result = dbquery("SELECT * FROM ".$fusion_prefix."users WHERE user_id='$user_id'");
		$data = dbarray($result);
		opentable(LAN_200);
		echo "<b>$data[user_name]</b><br><br>\n";
		if ($data[user_hide_email] != "1") {
			echo "<span class=\"alt\">".LAN_201."</span> <a href=\"mailto:$data[user_email]\">$data[user_email]</a><br>\n";
		} else {
			echo "<span class=\"alt\">".LAN_201."</span> Hidden by request<br>\n";
		}
		if ($data[location]) {
			echo "<span class=\"alt\">".LAN_202."</span> $data[user_location]<br>\n";
		} else {
			echo "<span class=\"alt\">".LAN_202."</span> Not Specified<br>\n";
		}
		if ($data[user_icq]) {
			echo "<span class=\"alt\">".LAN_203."</span> <a href=\"http://web.icq.com/wwp?Uin=$data[user_icq]\">$data[user_icq]</a><br>\n";
		} else {
			echo "<span class=\"alt\">".LAN_203."</span> Not Specified<br>\n";
		}
		if ($data[user_msn]) {
			echo "<span class=\"alt\">".LAN_204."</span> <a href=\"mailto:$data[user_msn]\">$data[user_msn]</a><br>\n";
		} else {
			echo "<span class=\"alt\">".LAN_204."</span> Not Specified<br>\n";
		}
		if ($data[user_yahoo]) {
			echo "<span class=\"alt\">".LAN_205."</span> <a href=\"http://uk.profiles.yahoo.com/$data[user_yahoo]\">$data[user_yahoo]</a><br>\n";
		} else {
			echo "<span class=\"alt\">".LAN_205."</span> Not Specified<br>\n";
		}
		if ($data[user_web]) {
			echo "<span class=\"alt\">".LAN_206."</span> <a href=\"$data[user_web]\">$data[user_web]</a><br>\n";
		} else {
			echo "<span class=\"alt\">".LAN_206."</span> Not Specified<br>\n";
		}
		echo "<span class=\"alt\">".LAN_207."</span> ".strftime("%d.%m.%y", $data[user_joined]+($settings[timeoffset]*3600))."<br>\n";
		if ($data[user_lastvisit] != 0) {
			echo "<span class=\"alt\">".LAN_208."</span> ".strftime("%d.%m.%y", $data[user_lastvisit]+($settings[timeoffset]*3600))."<br>\n";
		} else {
			echo "<span class=\"alt\">".LAN_208."</span>".LAN_209."<br>\n";
		}
		if ($data[user_id] != $userdata[user_id]) {
			echo "<br><a href=\"../sendmessage.php?user_id=$data[user_id]\">".LAN_210."</a>\n";
		}
		closetable();
	} else if ($step == "edit") {
		if (isset($_POST['savechanges'])) {
			require_once "updateuser.php";
			if ($error == "") {
				opentable(LAN_220);
				echo "<center><br>
".LAN_221."<br><br>
<a href=\"members.php\">".LAN_222."</a><br><br>
<a href=\"index.php\">".LAN_223."</a><br><br>
</center>\n";
				closetable();
			} else {
				opentable(LAN_220);
				echo "<center><br>
".LAN_224."<br><br>
$error<br>
<a href=\"members.php\">".LAN_222."</a><br><br>
<a href=\"index.php\">".LAN_223."</a><br><br>
</center>\n";
				closetable();
			}
		} else {
			$i = 0;
			$levels = array(MOD0, MOD1, MOD2, MOD3);
			$result = dbquery("SELECT * FROM ".$fusion_prefix."users WHERE user_id='$user_id'");
			$data = dbarray($result);
			while ($levels[$i]) {
				if ($userdata[user_mod] == "2" && $data[user_mod] != "3") {
					if ($i != "3") {
						if ($data[user_mod] == $i) {
							$sel = " selected";
						} else {
							$sel = "";
						}
						$modopts .= "<option value=\"$i\"$sel>$levels[$i]</option>\n";
					}
				} else if ($userdata[user_mod] == "3") {
					if ($data[user_mod] == $i) {
						$sel = " selected";
					} else {
						$sel = "";
					}
					$modopts .= "<option value=\"$i\"$sel>$levels[$i]</option>\n";
				}
				$i++;
			}
			opentable(LAN_220);
			echo "<table align=\"center\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<form name=\"editform\" method=\"post\" action=\"$PHP_SELF?step=edit&user_id=$user_id\">
<tr>
<td>".LAN_230."<font color=\"red\">*&nbsp</font></td>
<td><input type=\"textbox\" name=\"username\" value=\"$data[user_name]\" maxlength=\"30\" class=\"textbox\" style=\"width:200px;\"></td></tr>
<tr>
<td>".LAN_231."<font color=\"red\">*&nbsp</font></td>
<td><input type=\"textbox\" name=\"email\" value=\"$data[user_email]\" maxlength=\"100\" class=\"textbox\" style=\"width:200px;\"></td></tr>
<tr>
<td>".LAN_232."</td>\n";
if ($data[user_hide_email] == "1") { $yes = " checked"; $no = ""; } else { $yes = ""; $no = " checked"; } 
echo "<td><input type=\"radio\" name=\"hide_email\" value=\"1\"$yes> ".LAN_233." <input type=\"radio\" name=\"hide_email\" value=\"0\"$no> ".LAN_234."</td>
</tr>
<tr>
<td>".LAN_235."&nbsp</td>\n";
if ($userdata[user_mod] == "2") {
	if ($data[user_mod] != "3") {
		echo "<td><select name=\"modlevel\" class=\"textbox\" style=\"width:200px;\">
$modopts</select></td>\n";
	} else {
		echo "<td><input type=\"hidden\" name=\"modlevel\" value=\"1\">
<div class=\"textbox\" style=\"width:200px;\">Super Administrator</div></td>\n";
	}
} else {
	echo "<td><select name=\"modlevel\" class=\"textbox\" style=\"width:200px;\">
$modopts</select></td>\n";
}
echo "</tr>
<tr>
<td>".LAN_236."&nbsp</td>
<td><input type=\"textbox\" name=\"location\" value=\"$data[user_location]\" maxlength=\"50\" class=\"textbox\" style=\"width:200px;\"></td>
</tr>
<tr>
<td>".LAN_237."&nbsp</td>
<td><input type=\"textbox\" name=\"icq\" value=\"$data[user_icq]\" maxlength=\"15\" class=\"textbox\" style=\"width:200px;\"></td>
</tr>
<tr>
<td>".LAN_238."&nbsp</td>
<td><input type=\"textbox\" name=\"msn\" value=\"$data[user_msn]\" maxlength=\"100\" class=\"textbox\" style=\"width:200px;\"></td>
</tr>
<tr>
<td>".LAN_239."&nbsp</td>
<td><input type=\"textbox\" name=\"yahoo\" value=\"$data[user_yahoo]\" maxlength=\"50\" class=\"textbox\" style=\"width:200px;\"></td>
</tr>
<tr>
<td>".LAN_240."&nbsp</td>
<td><input type=\"textbox\" name=\"web\" value=\"$data[user_web]\" maxlength=\"200\" class=\"textbox\" style=\"width:200px;\"></td>
</tr>
<tr>
<td valign=\"top\" class=\"content\">".LAN_241."&nbsp;</td>
<td><textarea name=\"signature\" rows=\"5\" class=\"textbox\" onselect=\"updatePos(this);\" onkeyup=\"updatePos(this);\" onclick=\"updatePos(this);\" ondblclick=\"updatePos(this);\">$data[user_sig]</textarea><br>
<input type=\"button\" value=\"b\" class=\"button\" style=\"font-weight:bold;width:25px;\" onClick=\"AddText('b');\">
<input type=\"button\" value=\"i\" class=\"button\" style=\"font-style:italic;width:25px;\" onClick=\"AddText('i');\">
<input type=\"button\" value=\"u\" class=\"button\" style=\"text-decoration:underline;width:25px;\" onClick=\"AddText('u');\">
<input type=\"button\" value=\"url\" class=\"button\" style=\"width:30px;\" onClick=\"AddText('url');\">
<input type=\"button\" value=\"mail\" class=\"button\" style=\"width:35px;\" onClick=\"AddText('mail');\">
<input type=\"button\" value=\"img\" class=\"button\" style=\"width:40px;\" onClick=\"AddText('img');\">
<input type=\"button\" value=\"center\" class=\"button\" style=\"width:45px;\" onClick=\"AddText('center');\">
<input type=\"button\" value=\"small\" class=\"button\" style=\"width:40px;\" onClick=\"AddText('small');\"></td>
</tr>
<tr>
<td colspan=\"2\"><br><div align=\"center\">
<input type=\"submit\" name=\"savechanges\" value=\"".LAN_242."\" class=\"button\"></div>
</td>
</tr>
</form>
</table>\n";
			closetable();
			echo "<script language=\"JavaScript\">
var editBody = document.editform.signature;
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
	} else if ($step == "ban") {
		if ($act == on) {
			if ($user_id != 1) {
				$result = dbquery("UPDATE ".$fusion_prefix."users SET user_ban='1' WHERE user_id='$user_id'");
				opentable(LAN_270);
				echo "<center><br>
".LAN_271."<br><br>
<a href=\"members.php\">".LAN_272."</a><br><br>
<a href=\"index.php\">".LAN_273."</a><br><br>
</center>\n";
				closetable();
			} else {
				opentable(LAN_270);
				echo "<center><br>
".LAN_274."<br><br>
<a href=\"members.php\">".LAN_272."</a><br><br>
<a href=\"index.php\">".LAN_273."</a><br><br>
</center>\n";
				closetable();
			}
		} else {
			$result = dbquery("UPDATE ".$fusion_prefix."users SET user_ban='0' WHERE user_id='$user_id'");
			opentable(LAN_275);
			echo "<center><br>
".LAN_276."<br><br>
<a href=\"members.php\">".LAN_272."</a><br><br>
<a href=\"index.php\">".LAN_273."</a><br><br>
</center>\n";
			closetable();
		}	
	} else if ($step == "delete") {
		if ($user_id != 1) {
			$result = dbquery("DELETE FROM ".$fusion_prefix."users WHERE user_id='$user_id'");
			opentable(LAN_277);
			echo "<center><br>
".LAN_278."<br><br>
<a href=\"members.php\">".LAN_272."</a><br><br>
<a href=\"index.php\">".LAN_273."</a><br><br>
</center>\n";
			closetable();
		} else {
			opentable(LAN_277);
			echo "<center><br>
".LAN_279."<br><br>
<a href=\"members.php\">".LAN_272."</a><br><br>
<a href=\"index.php\">".LAN_273."</a><br><br>
</center>\n";
			closetable();
		}
	} else {
		opentable(LAN_290);
		echo "<script>
function DeleteMember()
{
	return confirm(\"".LAN_280."\");
}
</script>
<table align=\"center\" cellpadding=\"0\" cellspacing=\"0\" class=\"body\">
<tr>
<td width=\"150\" class=\"altbg\">".LAN_291."</td>
<td align=\"center\" width=\"90\" class=\"altbg\">".LAN_292."</td>
<td class=\"altbg\">".LAN_293."</td>
</tr>\n";
		$result = dbquery("SELECT * FROM ".$fusion_prefix."users ORDER BY user_id");
		$i = 1;
		while ($data = dbarray($result)) {
			echo "<tr>
<td>$i. $data[user_name]</td>
<td align=\"center\">".getmodlevel($data[user_mod])."</td>
<td>[<a href=\"$PHP_SELF?step=view&user_id=$data[user_id]\">".LAN_294."</a>]
[<a href=\"$PHP_SELF?step=edit&user_id=$data[user_id]\">".LAN_295."</a>] ";
		if ($data[user_ban] == "1") {
			echo "[<a href=\"$PHP_SELF?step=ban&act=off&user_id=$data[user_id]\">".LAN_296."</a>] ";
		} else {
			echo "[<a href=\"$PHP_SELF?step=ban&act=on&user_id=$data[user_id]\">".LAN_297."</a>] ";
		}
		echo "[<a href=\"$PHP_SELF?step=delete&user_id=$data[user_id]\" onClick=\"return DeleteMember()\">".LAN_298."</a>]</td>
</tr>\n";
		$i++;
		}
		echo "</table>\n";	
		closetable();
	}
}

echo "</td>
</tr>
</table>\n";

require "../footer.php";
?>