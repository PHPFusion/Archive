<?php
/*---------------------------------------------------+
| PHP-Fusion 6 Content Management System
+----------------------------------------------------+
| Copyright (c) 2005 Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the
| GNU General Public License. For details refer to
| the included gpl.txt file or visit http://gnu.org
+---------------------------------------------------*/
require_once "../maincore.php";
require_once BASEDIR."subheader.php";
require_once "navigation.php";

include LOCALE.LOCALESET."admin/user_groups.php";

if (!checkrights("UG")) fallback("../index.php");
if (isset($group_id) && !isNum($group_id)) fallback(FUSION_SELF);

if (isset($_POST['save_group'])) {
	$group_name = stripinput($_POST['group_name']);
	$group_description = stripinput($_POST['group_description']);
	if (isset($group_id)) {
		$result = dbquery("UPDATE ".$db_prefix."user_groups SET group_name='$group_name', group_description='$group_description' WHERE group_id='$group_id'");
		opentable($locale['400']);
		echo "<center><br>
".$locale['401']."<br><br>
<a href='user_groups.php'>".$locale['412']."</a><br><br>
<a href='index.php'>".$locale['413']."</a><br><br>
</center>\n";
		closetable();
	} else {
		$result = dbquery("INSERT INTO ".$db_prefix."user_groups VALUES('', '$group_name', '$group_description')");
		opentable($locale['402']);
		echo "<center><br>
".$locale['403']."<br><br>
<a href='user_groups.php'>".$locale['412']."</a><br><br>
<a href='index.php'>".$locale['413']."</a><br><br>
</center>\n";
		closetable();
	}
} elseif (isset($_POST['add_all'])) {
	$group_id = $_POST['group_id'];
	$result = dbquery("SELECT user_id,user_name,user_groups FROM ".$db_prefix."users");
	while ($data = dbarray($result)) {
  		if (!preg_match("(^\.$group_id|\.$group_id\.|\.$group_id$)", $data['user_groups'])) {
			$user_groups = $data['user_groups'].".".$group_id;
			$result2 = dbquery("UPDATE ".$db_prefix."users SET user_groups='$user_groups' WHERE user_id='".$data['user_id']."'");
		}
	}
	opentable($locale['404']);
	echo "<center><br>
".$locale['405']."<br><br>
<a href='user_groups.php'>".$locale['412']."</a><br><br>
<a href='index.php'>".$locale['413']."</a><br><br>
</center>\n";
	closetable();
} elseif (isset($_POST['remove_all'])) {
	$group_id = $_POST['group_id'];
	$result = dbquery("SELECT user_id,user_name,user_groups FROM ".$db_prefix."users WHERE user_groups REGEXP('^\\\.$group_id$|\\\.$group_id\\\.|\\\.$group_id$')");
	while ($data = dbarray($result)) {
		$user_groups = $data['user_groups'];
		$user_groups = preg_replace(array("(^\.$group_id$)","(\.$group_id\.)","(\.$group_id$)"), array("",".",""), $user_groups);
		$result2 = dbquery("UPDATE ".$db_prefix."users SET user_groups='$user_groups' WHERE user_id='".$data['user_id']."'");
	}
	opentable($locale['404']);
	echo "<center><br>
".$locale['406']."<br><br>
<a href='user_groups.php'>".$locale['412']."</a><br><br>
<a href='index.php'>".$locale['413']."</a><br><br>
</center>\n";
	closetable();
} elseif (isset($_POST['save_selected'])) {
	$group_id = $_POST['group_id'];	$group_users = $_POST['group_users'];
	$result = dbquery("SELECT user_id,user_name,user_groups FROM ".$db_prefix."users");
	while ($data = dbarray($result)) {
		$user_id = $data['user_id'];
 		if (preg_match("(^$user_id$|^$user_id\.|\.$user_id\.|\.$user_id$)", $group_users)) {
			if (!preg_match("(^\.$group_id$|\.$group_id\.|\.$group_id$)", $data['user_groups'])) {
				$user_groups = $data['user_groups'].".".$group_id;
				$result2 = dbquery("UPDATE ".$db_prefix."users SET user_groups='$user_groups' WHERE user_id='".$data['user_id']."'");
			}
		} elseif (preg_match("(^\.$group_id$|\.$group_id\.|\.$group_id$)", $data['user_groups'])) {
			$user_groups = $data['user_groups'];
			$user_groups = preg_replace(array("(^$group_id\.)","(\.$group_id\.)","(\.$group_id$)"), array("",".",""), $user_groups);
			$result2 = dbquery("UPDATE ".$db_prefix."users SET user_groups='$user_groups' WHERE user_id='".$data['user_id']."'");
		}
		unset($user_id);
	}
	opentable($locale['404']);
	echo "<center><br>
".$locale['407']."<br><br>
<a href='user_groups.php'>".$locale['412']."</a><br><br>
<a href='index.php'>".$locale['413']."</a><br><br>
</center>\n";
	closetable();
} elseif (isset($_POST['delete'])) {
	if (dbcount("(*)", "users", "user_groups REGEXP('^\\\.$group_id$|\\\.$group_id\\\.|\\\.$group_id$')") != 0) {
		$error = $locale['409']."<br><br>\n".$locale['410']."<br><br>\n";
	} else {
		$result = dbquery("DELETE FROM ".$db_prefix."user_groups WHERE group_id='$group_id'");
	}
	opentable($locale['408']);
	echo "<center><br>
".(!isset($error) ? $locale['411']."<br><br>\n" : $error)."
<a href='user_groups.php'>".$locale['412']."</a><br><br>
<a href='index.php'>".$locale['413']."</a><br><br>
</center>\n";
	closetable();
} else {
	$result = dbquery("SELECT * FROM ".$db_prefix."user_groups ORDER BY group_name");
	if (dbrows($result) != 0) {
		opentable($locale['420']);
		echo "<form name='selectform' method='post' action='".FUSION_SELF."'>
<center>
<select name='group_id' class='textbox'>\n";
		$sel = "";
		while ($data = dbarray($result)) {
			if (isset($group_id)) $sel = ($group_id == $data['group_id'] ? " selected" : "");
			echo "<option value='".$data['group_id']."'$sel>[".$data['group_id']."] ".$data['group_name']."</option>\n";
		}
		echo "</select>
<input type='submit' name='edit' value='".$locale['421']."' class='button'>
<input type='submit' name='delete' value='".$locale['422']."' onclick='return DeleteGroup();' class='button'>
</center>
</form>\n";
		closetable();
		tablebreak();
	}
	if (isset($_POST['edit'])) {
		$result = dbquery("SELECT * FROM ".$db_prefix."user_groups WHERE group_id='$group_id'");
		if (dbrows($result) == 0) fallback(FUSION_SELF);
		$data = dbarray($result);
		$group_name = $data['group_name'];
		$group_description = $data['group_description'];
		$form_action = FUSION_SELF."?group_id=$group_id";
		opentable($locale['430']);
	} else {
		$group_name = "";
		$group_description = "";
		$form_action = FUSION_SELF;
		opentable($locale['431']);
	}
	echo "<form name='editform' method='post' action='$form_action'>
<table align='center' cellpadding='0' cellspacing='0'>
<tr>
<td class='tbl'>".$locale['432']."</td>
<td class='tbl'><input type='text' name='group_name' value='$group_name' class='textbox' style='width:200px'></td>
</tr>
<tr>
<td class='tbl'>".$locale['433']."</td>
<td class='tbl'><input type='text' name='group_description' value='$group_description' class='textbox' style='width:200px'></td>
</tr>
<tr>
<td align='center' colspan='2' class='tbl'><br>
<input type='submit' name='save_group' value='".$locale['434']."' class='button'></td>
</tr>
</table>
</form>";
	closetable();
	tablebreak();
	if (isset($group_id)) {
		opentable($locale['404']);
		$result = dbquery("SELECT user_id,user_name,user_groups FROM ".$db_prefix."users ORDER BY user_level DESC, user_name");
		while ($data = dbarray($result)) {
  			if (!preg_match("(^\.$group_id$|\.$group_id\.|\.$group_id$)", $data['user_groups'])) {
				$group1_user_id[] = $data['user_id'];
				$group1_user_name[] = $data['user_name'];
			} else {
				$group2_user_id[] = $data['user_id'];
				$group2_user_name[] = $data['user_name'];
			}
		}
		echo "<form name='groupsform' method='post' action='".FUSION_SELF."'>
<table align='center' cellpadding='0' cellspacing='0'>
<tr>
<td class='tbl'>
<select multiple size='15' name='grouplist1' id='grouplist1' class='textbox' style='width:150' onChange=\"addUser('grouplist2','grouplist1');\">\n";
		for ($i=0;$i < count($group1_user_id);$i++) echo "<option value='".$group1_user_id[$i]."'>".$group1_user_name[$i]."</option>\n";
		echo "</select>
</td>
<td align='center' valign='middle' class='tbl'>
</td>
<td class='tbl'>
<select multiple size='15' name='grouplist2' id='grouplist2' class='textbox' style='width:150' onChange=\"addUser('grouplist1','grouplist2');\">\n";
		for ($i=0;$i < count($group2_user_id);$i++) echo "<option value='".$group2_user_id[$i]."'>".$group2_user_name[$i]."</option>\n";
		echo "</select>
</td>
</tr>
<tr>
<td align='center' colspan='3' class='tbl'>
<input type='hidden' name='group_users'>
<input type='hidden' name='group_id' value='$group_id'>
<input type='submit' name='add_all' value='".$locale['435']."' class='button'>
<input type='submit' name='remove_all' value='".$locale['436']."' class='button'><br><br>
<input type='hidden' name='save_selected'>
<input type='button' name='update' value='".$locale['437']."' class='button' onclick='saveGroup();'></td>
</tr>
</table>
</form>\n";
		closetable();
		// Script Original Author: Kathi O'Shea (Kathi.O'Shea@internet.com)
		// http://www.webdesignhelper.co.uk/sample_code/sample_code/sample_code10/sample_code10.shtml		
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
	return confirm('".$locale['423']."');
}
</script>\n";

echo "</td>\n";
require_once BASEDIR."footer.php";
?>