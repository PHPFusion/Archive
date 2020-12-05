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

include FUSION_LANGUAGES.FUSION_LAN."admin/admin_panels.php";

if (!checkrights("F")) { header("Location:../index.php"); exit; }

$handle = opendir(FUSION_INFUSIONS);
while ($folder = readdir($handle)) {
	if (!in_array($folder, array(".", "..", "index.php")) && strstr($folder, "_panel")) {
		if (file_exists(FUSION_INFUSIONS.$folder."/infusion.php")) {
			@include FUSION_INFUSIONS.$folder."/infusion.php";
			if (dbrows(dbquery("SELECT inf_name FROM ".$fusion_prefix."infusions WHERE inf_name='$inf_name' AND inf_installed='1'"))!=0) {
				$panel_list[] = $folder;
			}
		} else {
			$panel_list[] = $folder;
		}
	}
}
closedir($handle);
sort($panel_list);
array_unshift($panel_list, "none");

if (isset($_POST['save'])) {
	$error = "";
	$panel_name = stripinput($_POST['panel_name']);
	if ($panel_name == "") $error .= LAN_470."<br>";
	if ($_POST['panel_filename'] == "none") {
		$panel_filename = "";
		$panel_content = addslash($_POST['panel_content']);
		$panel_type = "php";
	} else {
		$panel_type = "file";
		$panel_content = "";
	}
	$panel_access = $_POST['panel_access'];
	if (isset($panel_id)) {
		if ($panel_name != "") {
			$data = dbarray(dbquery("SELECT * FROM ".$fusion_prefix."panels WHERE panel_id='$panel_id'"));
			if ($panel_name != $data['panel_name']) {
				$result = dbquery("SELECT * FROM ".$fusion_prefix."panels WHERE panel_name='$panel_name'");
				if (dbrows($result) != 0) $error .= LAN_471."<br>";
			}
		}
		if ($panel_type == "php" && $panel_content == "") $error .= LAN_472."<br>";
		if ($error == "") {
			$result = dbquery("UPDATE ".$fusion_prefix."panels SET panel_name='$panel_name', panel_filename='$panel_filename', panel_content='$panel_content', panel_access='$panel_access' WHERE panel_id='$panel_id'");
		}
		opentable(LAN_480);
		echo "<center><br>\n";
		if ($error != "") {
			echo LAN_481."<br><br>\n".$error."<br>\n";
		} else {
			echo LAN_482."<br><br>\n";
		}
		echo "<a href='panels.php'>".LAN_486."</a><br><br>
<a href='index.php'>".LAN_487."</a><br><br>
</center>\n";
		closetable();
	} else {
		if ($panel_name != "") {
			$result = dbquery("SELECT * FROM ".$fusion_prefix."panels WHERE panel_name='$panel_name'");
			if (dbrows($result) != 0) $error .= LAN_471."<br>";
		}
		if ($panel_type == "php" && $panel_content == "") $error .= LAN_472."<br>";
		if ($panel_type == "file" && $panel_filename == "none") $error .= LAN_473."<br>";
		if ($error == "") {
			$result = dbquery("SELECT * FROM ".$fusion_prefix."panels WHERE panel_side='$panel_side' ORDER BY panel_order DESC LIMIT 1");
			if (dbrows($result) != 0) { $data = dbarray($result); $neworder = $data['panel_order'] + 1; } else { $neworder = 1; }
			$result = dbquery("INSERT INTO ".$fusion_prefix."panels VALUES('', '$panel_name', '$panel_filename', '$panel_content', '$panel_side', '$neworder', '$panel_type', '$panel_access', '0')");
		}
		opentable(LAN_483);
		echo "<center><br>\n";
		if ($error != "") {
			echo LAN_484."<br><br>
".$error."<br>\n";
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
		$panel_name = stripinput($_POST['panel_name']);
		$panel_filename = $_POST['panel_filename'];
		$panel_content = $_POST['panel_content'];
		$panel_access = $_POST['panel_access'];
		$panel_side = $_POST['panel_side'];
		$panel_content = stripslash($panel_content);
		opentable($panel_name);
		if ($panel_filename != "none") {
			@include FUSION_INFUSIONS.$panel_filename."/".$panel_filename.".php";
			$panel_type = "file";
		} else {
			eval($panel_content);
			$panel_type = "php";
		}
		$panel_content = stripinput((QUOTES_GPC ? addslashes($panel_content) : $panel_content));
		closetable();
		tablebreak();
	}
	if (isset($step) && $step == "edit") {
		$result = dbquery("SELECT * FROM ".$fusion_prefix."panels WHERE panel_id='$panel_id'");
		if (dbrows($result) != 0) {
			$data = dbarray($result);
			$panel_name = $data['panel_name'];
			$panel_filename = $data['panel_filename'];
			$panel_content = stripinput((QUOTES_GPC ? $data['panel_content'] : stripslashes($data['panel_content'])));
			$panel_type = $data['panel_type'];
			$panel_access = $data['panel_access'];
			$panel_side = $data['panel_side'];
		}
	}
	if (isset($panel_id)) {
		$action = FUSION_SELF."?panel_id=$panel_id";
		opentable(LAN_450);
	} else {
		if (!isset($_POST['preview'])) {
			$panel_name = "";
			$panel_filename = "";
			$panel_content = "openside(\"name\");\n"."  echo \"content\";\n"."closeside();";
			$panel_type = "";
			$panel_access = "";
			$panel_side = "";
		}
		$action = FUSION_SELF;
		opentable(LAN_451);
	}
	$user_groups = getusergroups(); $access_opts = "";
	while(list($key, $user_group) = each($user_groups)){
		$sel = ($panel_access == $user_group['0'] ? " selected" : "");
		$access_opts .= "<option value='".$user_group['0']."'$sel>".$user_group['1']."</option>\n";
	}
	echo "<form name='editform' method='post' action='$action'>
<table align='center' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td>".LAN_452."</td>
<td><input type='text' name='panel_name' value='$panel_name' class='textbox' style='width:200px;'></td>
</tr>\n";
	if (isset($panel_id)) {
		if ($panel_type == "file") {
			echo "<tr>
<td>".LAN_453."</td>
<td><select name='panel_filename' class='textbox' style='width:200px;'>\n";
			for ($i=0;$panel_list[$i]!="";$i++) {
				echo "<option".($panel_filename == $panel_list[$i] ? " selected" : "").">$panel_list[$i]</option>\n";
			}
			echo "</select>&nbsp;&nbsp;<span class='small2'>".LAN_454."</span></td>\n</tr>\n";
		}
	} else {
		echo "<tr>
<td>".LAN_453."</td>
<td><select name='panel_filename' class='textbox' style='width:200px;'>\n";
		for ($i=0;$panel_list[$i]!="";$i++) {
			echo "<option".($panel_filename == $panel_list[$i] ? " selected" : "").">$panel_list[$i]</option>\n";
		}
		echo "</select>&nbsp;&nbsp;<span class='small2'>".LAN_454."</span></td>\n</tr>\n";
	}
	if (isset($panel_id)) {
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
	if (!isset($panel_id)) {
		echo "<tr>
<td>".LAN_456."</td>
<td><select name='panel_side' class='textbox' style='width:150px;'>
<option value='1'".($panel_side == "1" ? " selected" : "").">".LAN_420."</option>
<option value='2'".($panel_side == "2" ? " selected" : "").">".LAN_421."</option>
<option value='3'".($panel_side == "3" ? " selected" : "").">".LAN_422."</option>
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
	if (isset($panel_id)) {
		if ($panel_type == "php") echo "<input type='hidden' name='panel_filename' value='none'>\n";
		echo "<input type='hidden' name='panel_side' value='$panel_side'>\n";
	}
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

echo "</td>\n";
require_once FUSION_BASE."footer.php";
?>