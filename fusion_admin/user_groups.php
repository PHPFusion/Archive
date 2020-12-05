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
require_once "navigation.php";

include FUSION_LANGUAGES.FUSION_LAN."admin/admin_user_groups.php";

if (!checkrights("Q")) { header("Location:../index.php"); exit; }

if (isset($_POST['save_group'])) {
	$group_name = stripinput($_POST['group_name']);
	$group_description = stripinput($_POST['group_description']);
	if (isset($group_id)) {
		$result = dbquery("UPDATE ".$fusion_prefix."user_groups SET group_name='$group_name', group_description='$group_description' WHERE group_id='$group_id'");
		opentable(LAN_400);
		echo "<center><br>
".LAN_401."<br><br>
<a href='user_groups.php'>".LAN_412."</a><br><br>
<a href='index.php'>".LAN_413."</a><br><br>
</center>\n";
		closetable();
	} else {
		$result = dbquery("INSERT INTO ".$fusion_prefix."user_groups VALUES('', '$group_name', '$group_description')");
		opentable(LAN_402);
		echo "<center><br>
".LAN_403."<br><br>
<a href='user_groups.php'>".LAN_412."</a><br><br>
<a href='index.php'>".LAN_413."</a><br><br>
</center>\n";
		closetable();
	}
} elseif (isset($_POST['add_all'])) {
	$sql = dbquery("SELECT user_id,user_name,user_groups FROM ".$fusion_prefix."users");
	while ($data = dbarray($sql)) {
		$user_groups = explode(".", $data['user_groups']);
		if (!in_array($_POST['group_id'], $user_groups)) {
			array_push($user_groups, $_POST['group_id']);
			$user_groups = implode(".", $user_groups);
			$sql2 = dbquery("UPDATE ".$fusion_prefix."users SET user_groups='$user_groups' WHERE user_id='".$data['user_id']."'");
		}
	}
	opentable(LAN_404);
	echo "<center><br>
".LAN_405."<br><br>
<a href='user_groups.php'>".LAN_412."</a><br><br>
<a href='index.php'>".LAN_413."</a><br><br>
</center>\n";
	closetable();
} elseif (isset($_POST['remove_all'])) {
	$sql = dbquery("SELECT user_id,user_name,user_groups FROM ".$fusion_prefix."users");
	while ($data = dbarray($sql)) {
		$user_groups = explode(".", $data['user_groups']);
		if (in_array($_POST['group_id'], $user_groups)) {
			$i = array_search($_POST['group_id'], $user_groups);
			unset($user_groups[$i]);
			$user_groups = implode(".", $user_groups);
			$sql2 = dbquery("UPDATE ".$fusion_prefix."users SET user_groups='$user_groups' WHERE user_id='".$data['user_id']."'");
		}
	}
	opentable(LAN_404);
	echo "<center><br>
".LAN_406."<br><br>
<a href='user_groups.php'>".LAN_412."</a><br><br>
<a href='index.php'>".LAN_413."</a><br><br>
</center>\n";
	closetable();
} elseif (isset($_POST['save_selected'])) {
	$group_users = explode(".", $_POST['group_users']);
	$sql = dbquery("SELECT user_id,user_name,user_groups FROM ".$fusion_prefix."users");
	while ($data = dbarray($sql)) {
		$user_groups = explode(".", $data['user_groups']);
		if (in_array($data['user_id'], $group_users)) {
			if (!in_array($_POST['group_id'], $user_groups)) {
				array_push($user_groups, $_POST['group_id']);
				$user_groups = implode(".", $user_groups);
				$sql2 = dbquery("UPDATE ".$fusion_prefix."users SET user_groups='$user_groups' WHERE user_id='".$data['user_id']."'");
			}
		} else if (in_array($_POST['group_id'], $user_groups)) {
			$i = array_search($_POST['group_id'], $user_groups);
			unset($user_groups[$i]);
			$user_groups = implode(".", $user_groups);
			$sql2 = dbquery("UPDATE ".$fusion_prefix."users SET user_groups='$user_groups' WHERE user_id='".$data['user_id']."'");
		}
	}
	opentable(LAN_404);
	echo "<center><br>
".LAN_407."<br><br>
<a href='user_groups.php'>".LAN_412."</a><br><br>
<a href='index.php'>".LAN_413."</a><br><br>
</center>\n";
	closetable();
} elseif (isset($_POST['delete'])) {
	$count = dbcount("(*)", "users", "user_groups REGEXP('^{$group_id}\.') OR user_groups REGEXP('\.{$group_id}\.') OR user_groups REGEXP('\.{$group_id}$')");
	if ($count != 0) {
		$error = LAN_409."<br><br>\n".LAN_410."<br><br>\n";
	} else {
		$result = dbquery("DELETE FROM ".$fusion_prefix."user_groups WHERE group_id='$group_id'");
	}
	opentable(LAN_408);
	echo "<center><br>
".(!isset($error) ? LAN_411."<br><br>\n" : $error)."
<a href='user_groups.php'>".LAN_412."</a><br><br>
<a href='index.php'>".LAN_413."</a><br><br>
</center>\n";
	closetable();
} else {
	$sql = dbquery("SELECT * FROM ".$fusion_prefix."user_groups ORDER BY group_name DESC");
	if (dbrows($sql) != 0) {
		opentable(LAN_420);
		echo "<form name='selectform' method='post' action='".FUSION_SELF."'>
<center>
<select name='group_id' class='textbox'>\n";
		$sel = "";
		while ($data = dbarray($sql)) {
			if (isset($group_id)) $sel = ($group_id == $data['group_id'] ? " selected" : "");
			echo "<option value='".$data['group_id']."'$sel>".$data['group_name']."</option>\n";
		}
		echo "</select>
<input type='submit' name='edit' value='".LAN_421."' class='button'>
<input type='submit' name='delete' value='".LAN_422."' onclick='return DeleteGroup();' class='button'>
</center>
</form>\n";
		closetable();
		tablebreak();
	}
	if (isset($_POST['edit'])) {
		$sql = dbquery("SELECT * FROM ".$fusion_prefix."user_groups WHERE group_id='$group_id'");
		if (dbrows($sql) == 0) { header("Location: ".FUSION_SELF); exit; }
		$data = dbarray($sql);
		$group_name = $data['group_name'];
		$group_description = $data['group_description'];
		$form_action = FUSION_SELF."?group_id=$group_id";
		opentable(LAN_430);
	} else {
		$group_name = "";
		$group_description = "";
		$form_action = FUSION_SELF;
		opentable(LAN_431);
	}
	echo "<form name='editform' method='post' action='$form_action'>
<table align='center' cellpadding='0' cellspacing='0' class='tbl'>
<tr>
<td>".LAN_432."</td>
<td><input type='text' name='group_name' value='$group_name' class='textbox' style='width:200px'></td>
</tr>
<tr>
<td>".LAN_433."</td>
<td><input type='text' name='group_description' value='$group_description' class='textbox' style='width:200px'></td>
</tr>
<tr>
<td align='center' colspan='2'><br>
<input type='submit' name='save_group' value='".LAN_434."' class='button'></td>
</tr>
</table>
</form>";
	closetable();
	tablebreak();
	if (isset($group_id)) {
		opentable(LAN_404);
		$sql = dbquery("SELECT user_id,user_name,user_groups FROM ".$fusion_prefix."users ORDER BY user_level DESC, user_name");
		$o = 0; $i = 0;
		while ($data = dbarray($sql)) {
			$user_groups = explode(".", $data['user_groups']);
			if (!in_array($group_id, $user_groups)) {
				$group1_user_id[$o] = $data['user_id'];
				$group1_user_name[$o] = $data['user_name'];
				$o++;
			} else {
				$group2_user_id[$i] = $data['user_id'];
				$group2_user_name[$i] = $data['user_name'];
				$i++;
			}
		}
		echo "<form name='groupsform' method='post' action='".FUSION_SELF."'>
<table align='center' cellpadding='0' cellspacing='0' class='tbl'>
<tr>
<td>
<select multiple size='15' name='grouplist1' id='grouplist1' class='textbox' style='width:150' onChange=\"addUser('grouplist2','grouplist1');\">\n";
		for ($c=0;$c<$o;$c++) echo "<option value='".$group1_user_id[$c]."'>".$group1_user_name[$c]."</option>\n";
		echo "</select>
</td>
<td align='center' valign='middle'>
</td>
<td>
<select multiple size='15' name='grouplist2' id='grouplist2' class='textbox' style='width:150' onChange=\"addUser('grouplist1','grouplist2');\">\n";
		for ($c=0;$c<$i;$c++) echo "<option value='".$group2_user_id[$c]."'>".$group2_user_name[$c]."</option>\n";
		echo "</select>
</td>
</tr>
<tr>
<td align='center' colspan='3'>
<input type='hidden' name='group_users'>
<input type='hidden' name='group_id' value='$group_id'>
<input type='submit' name='add_all' value='".LAN_435."' class='button'>
<input type='submit' name='remove_all' value='".LAN_436."' class='button'><br><br>
<input type='hidden' name='save_selected'>
<input type='button' name='update' value='".LAN_437."' class='button' onclick='saveGroup();'></td>
</tr>
</table>
</form>\n";
		closetable();
		echo "<script type='text/javascript'>
function addUser(toGroup,fromGroup) {
	var listLength = document.getElementById(toGroup).length;
	var selItem = document.getElementById(fromGroup).selectedIndex;
	var selText = document.getElementById(fromGroup).options[selItem].text;
	var selValue = document.getElementById(fromGroup).options[selItem].value;
	var i; var newItem = true;
	for (i = 0; i < listLength; i++) {
		if (document.getElementById(toGroup).options[i].text == selText) {
			newItem = false; break;
		}
	}
	if (newItem) {
		document.getElementById(toGroup).options[listLength] = new Option(selText, selValue);
		document.getElementById(fromGroup).options[selItem] = null;
	}
}

function saveGroup() {
	var strValues = \"\";
	var boxLength = document.getElementById('grouplist2').length;
	var elcount = 0;
	if (boxLength != 0) {
		for (i = 0; i < boxLength; i++) {
			if (elcount == 0) {
				strValues = document.getElementById('grouplist2').options[i].value;
			} else {
				strValues = strValues + \".\" + document.getElementById('grouplist2').options[i].value;
			}
			elcount++;
		}
	}
	if (strValues.length == 0) {
		document.forms['groupsform'].submit();
	} else {
		document.forms['groupsform'].group_users.value = strValues;
		document.forms['groupsform'].submit();
	}
}
</script>\n";
	}
}
echo "<script type='text/javascript'>
function DeleteGroup() {
	return confirm('".LAN_423."');
}
</script>\n";

echo "</td>\n";
require_once FUSION_BASE."footer.php";
?>