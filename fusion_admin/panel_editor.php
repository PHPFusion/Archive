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
require fusion_langdir."admin/admin_panels.php";
require "navigation.php";

if (!Admin()) header("Location: ../index.php");

if (Admin()) {
	if (isset($_POST['save'])) {
		$panel_name = stripinput($panel_name);
		if ($panel_name == "") $error['0'] = LAN_470."<br>";
		if ($panel_filename == "none") {
			$panel_filename = "";
			$panel_content = addslashes($panel_content);
			$panel_type = "php";
		} else {
			$panel_type = "file";
			$panel_content = "";
		}
		if (isset($panel_id)) {
			if ($panel_name != "") {
				$data = dbarray(dbquery("SELECT * FROM ".$fusion_prefix."panels WHERE panel_id='$panel_id'"));
				if ($panel_name != $data['panel_name']) {
					$result = dbquery("SELECT * FROM ".$fusion_prefix."panels WHERE panel_name='$panel_name'");
					if (dbrows($result) != 0) $error['1'] = LAN_471."<br>";
				}
			}
			if ($panel_type == "php" && $panel_content == "") $error['2'] = LAN_472."<br>";
			if ($error == "") {
				$result = dbquery("UPDATE ".$fusion_prefix."panels SET panel_name='$panel_name', panel_filename='$panel_filename', panel_content='$panel_content', panel_access='$panel_access' WHERE panel_id='$panel_id'");
			}
			opentable(LAN_480);
			echo "<center><br>\n";
			if ($error != "") {
				echo LAN_481."<br><br>
".$error['0'].$error['1'].$error['2']."<br>\n";
			} else {
				echo LAN_482."<br><br>\n";
			}
			echo "<a href='panels.php'>".LAN_486."</a><br><br>
<a href='index.php'>".LAN_487."</a><br><br>
</center>\n";
			closetable();
		} else {
			if ($panel_name != "") {
				$data = dbarray(dbquery("SELECT * FROM ".$fusion_prefix."panels WHERE panel_id='$panel_id'"));
				if ($panel_name != $data['panel_name']) {
					$result = dbquery("SELECT * FROM ".$fusion_prefix."panels WHERE panel_name='$panel_name'");
					if (dbrows($result) != 0) $error['1'] = LAN_471."<br>";
				}
			}
			if ($panel_type == "php" && $panel_content == "") $error['2'] = LAN_472."<br>";
			if ($panel_type == "file" && $panel_filename == "none") $error['3'] = LAN_473."<br>";
			if ($error == "") {
				$result = dbquery("SELECT * FROM ".$fusion_prefix."panels WHERE panel_side='$panel_side' ORDER BY panel_order DESC LIMIT 1");
				if (dbrows($result) != 0) { $data = dbarray($result); $neworder = $data['panel_order'] + 1; } else { $neworder = 1; }
				$result = dbquery("INSERT INTO ".$fusion_prefix."panels VALUES('', '$panel_name', '$panel_filename', '$panel_content', '$panel_side', '$neworder', '$panel_type', '$panel_access', '0')");
			}
			opentable(LAN_483);
			echo "<center><br>\n";
			if ($error != "") {
				echo LAN_484."<br><br>
".$error['0'].$error['1'].$error['2']."<br>\n";
			} else {
				echo LAN_485."<br><br>\n";
			}
			echo "<a href='panels.php'>".LAN_486."</a><br><br>
<a href='index.php'>".LAN_487."</a><br><br>
</center>\n";
			closetable();
		}
	} else {
		if (isset($_POST['preview'])) {
			$panel_name = stripinput($panel_name);
			if (ini_get('magic_quotes_gpc')) $panel_content = stripslashes($panel_content);
			opentable($panel_name);
			if ($panel_filename != "none") {
				require fusion_basedir."fusion_panels/".$panel_filename;
				$panel_type = "file";
			} else {
				eval($panel_content);
				$panel_type = "php";
			}
			$panel_content = stripinput(addslashes($panel_content));
			closetable();
			tablebreak();
		}
		if ($step == "edit") {
			$result = dbquery("SELECT * FROM ".$fusion_prefix."panels WHERE panel_id='$panel_id'");
			if (dbrows($result) != 0) {
				$data = dbarray($result);
				$panel_name = $data['panel_name'];
				$panel_filename = $data['panel_filename'];
				$panel_content = stripinput($data['panel_content']);
				$panel_type = $data['panel_type'];
				$panel_access = $data['panel_access'];
			}
		}
		$handle = opendir(fusion_basedir."fusion_panels");
		while ($folder = readdir($handle)){
			if ($folder != "." && $folder != ".." && $folder != "/" && $folder != "index.php") $panel_list[] = $folder;
		}
		closedir($handle);
		sort($panel_list);
		$access = array(USER0,USER1,USER2,USER3,USER4);
		for ($i=0;$access[$i]!="";$i++) {
			if ($panel_access) {
				if ($panel_access == $i) { $sel = " selected"; } else { $sel = ""; }
			}
			$access_opts .= "<option value='$i'$sel>".$access[$i]."</option>\n";
		}
		if (isset($panel_id)) {
			$action = $PHP_SELF."?panel_id=$panel_id";
			opentable(LAN_450);
		} else {
			if (!$_POST['preview']) {
				$panel_content = "@openside(\"name\");\n"."  echo \"content\";\n"."@closeside();";
			}
			$action = $PHP_SELF;
			opentable(LAN_451);
		}
		echo "<form name='editform' method='post' action='$action'>
<table align='center' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td>".LAN_452."</td>
<td><input type='text' name='panel_name' value='$panel_name' class='textbox' style='width:200px;'></td>
</tr>\n";
		if ($panel_id) {
			if ($panel_type == "file") {
				echo "<tr>
<td>".LAN_453."</td>
<td><select name='panel_filename' class='textbox' style='width:200px;'>\n";
				for ($count=0;$panel_list[$count]!="";$count++) {
					if ($panel_filename == $panel_list[$count]) { $sel = " selected"; } else { $sel = ""; }
					echo "<option$sel>$panel_list[$count]</option>\n";
				}
				echo "</select></td>\n</tr>\n";
			}
		} else {
			echo "<tr>
<td>".LAN_453."</td>
<td><select name='panel_filename' class='textbox' style='width:200px;'>\n";
			if ($panel_filename == "none") { $sel = " selected"; } else { $sel = ""; }
			echo "<option$sel>none</option>\n";
			for ($count=0;$panel_list[$count]!="";$count++) {
				if ($panel_filename == $panel_list[$count]) { $sel = " selected"; } else { $sel = ""; }
				echo "<option$sel>$panel_list[$count]</option>\n";
			}
			echo "</select>&nbsp;&nbsp;<span class='small2'>".LAN_454."</span></td>\n</tr>\n";
		}
		if ($panel_id) {
			if ($panel_type == "php") {
				echo "<tr>
<td valign='top'>".LAN_455."</td>
<td><textarea name='panel_content' cols='95' rows='15' class='textbox' onselect='updatePos(this);' onkeyup='updatePos(this);' onclick='updatePos(this);' ondblclick='updatePos(this);'>$panel_content</textarea></td>
</tr>\n";
			}
		} else {
			echo "<tr>
<td valign='top'>".LAN_455."</td>
<td><textarea name='panel_content' cols='95' rows='15' class='textbox' onselect='updatePos(this);' onkeyup='updatePos(this);' onclick='updatePos(this);' ondblclick='updatePos(this);'>$panel_content</textarea></td>
</tr>\n";
		}
		if (!$panel_id) {
			if ($panel_side == "l") { $lsel = " selected"; } else { $lsel = ""; }
			if ($panel_side == "r") { $rsel = " selected"; } else { $rsel = ""; }
			echo "<tr>
<td>".LAN_456."</td>
<td><select name='panel_side' class='textbox' style='width:150px;'>
<option value='l'$lsel>".LAN_420."</option>
<option value='r'$rsel>".LAN_421."</option>
</select></td>
</tr>\n";
		}
echo "<tr>
<td>".LAN_457."</td>
<td><select name='panel_access' class='textbox' style='width:150px;'>
$access_opts</select></td>
</tr>
<tr>
<td align='center' colspan='2'><br>\n";
		if ($panel_id && $panel_type == "php") { echo "<input type='hidden' name='panel_filename' value='none'>\n"; }
		echo "<input type='submit' name='preview' value='".LAN_458."' class='button'>
<input type='submit' name='save' value='".LAN_459."' class='button'></td>
</tr>
</table>
</form>\n";
		closetable();
		echo "<script language='JavaScript'>
var editBody = document.editform.panel_content;
function insertText(theText) {
	if (editBody.createTextRange && editBody.curPos) {
		editBody.curPos.text = theText;
	} else {
		editBody.value += theText;
	}
	editBody.focus();
}
function AddText(wrap,unwrap) {
	if (editBody.curPos) {
		insertText(wrap + editBody.curPos.text + unwrap);
	} else {
		insertText(wrap + unwrap);
	}
}
function updatePos(obj) {
	if (obj.createTextRange) {
		obj.curPos = document.selection.createRange().duplicate();
	}
}
</script>\n";
	}
}

echo "</td>\n";
require "../footer.php";
?>