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
+----------------------------------------------------*/
require_once "../maincore.php";
require_once BASEDIR."subheader.php";
require_once ADMIN."navigation.php";
include LOCALE.LOCALESET."admin/forums.php";

if (!checkrights("F")) fallback("../index.php");
if (isset($forum_id) && !isNum($forum_id)) fallback(FUSION_SELF);
if (!isset($action)) $action = "";
if (!isset($t)) $t = "";

if (isset($_POST['save_cat'])) {
	$cat_name = stripinput($_POST['cat_name']);
	$cat_order = isNum($_POST['cat_order']) ? $_POST['cat_order'] : "";
	if ($action == "edit" && $t == "cat") {
		$data = dbarray(dbquery("SELECT forum_order FROM ".$db_prefix."forums WHERE forum_id='$forum_id'"));
		if($cat_order < $data['forum_order']){
			$result = dbquery("UPDATE ".$db_prefix."forums SET forum_order=forum_order+1 WHERE forum_cat='0' AND forum_order>='$cat_order' AND forum_order<'".$data['forum_order']."'");
		} else if ($cat_order > $data['forum_order']){
			$result = dbquery("UPDATE ".$db_prefix."forums SET forum_order=forum_order-1 WHERE forum_cat='0' AND forum_order>'".$data['forum_order']."' AND forum_order<='$cat_order'");
		}				
		$result = dbquery("UPDATE ".$db_prefix."forums SET forum_name='$cat_name', forum_order='$cat_order' WHERE forum_id='$forum_id'");
		opentable($locale['400']);
		echo "<center><br>
".$locale['401']."<br><br>
<a href='forums.php'>".$locale['418']."</a><br><br>
<a href='index.php'>".$locale['419']."</a><br><br>
</center>\n";
		closetable();
	} else {
		if ($cat_name != "") {
			if(!$cat_order) $cat_order=dbresult(dbquery("SELECT MAX(forum_order) FROM ".$db_prefix."forums WHERE forum_cat='0'"),0)+1;
			$result = dbquery("UPDATE ".$db_prefix."forums SET forum_order=forum_order+1 WHERE forum_cat='0' AND forum_order>='$cat_order'");	
			$result = dbquery("INSERT INTO ".$db_prefix."forums VALUES('', '0', '$cat_name', '$cat_order', '', '', '0', '0', '0', '0')");
		}
		opentable($locale['402']);
		echo "<center><br>
".$locale['403']."<br><br>
<a href='forums.php'>".$locale['418']."</a><br><br>
<a href='index.php'>".$locale['419']."</a><br><br>
</center>\n";
		closetable();
	}
} elseif (isset($_POST['save_forum'])) {
	$forum_name = stripinput($_POST['forum_name']);
	$forum_description = stripinput($_POST['forum_description']);
	$forum_cat = isNum($_POST['forum_cat']) ? $_POST['forum_cat'] : "";
	$forum_access = $_POST['forum_access'];
	$forum_posting = $_POST['forum_posting'];
	if ($action == "edit" && $t == "forum") {
		$data = dbarray(dbquery("SELECT forum_order FROM ".$db_prefix."forums WHERE forum_id='$forum_id'"));
		if($forum_order < $data['forum_order']){
			$result = dbquery("UPDATE ".$db_prefix."forums SET forum_order=forum_order+1 WHERE forum_cat='$forum_cat' AND forum_order>='$forum_order' AND forum_order<'".$data['forum_order']."'");
		} else if ($forum_order > $data['forum_order']){
			$result = dbquery("UPDATE ".$db_prefix."forums SET forum_order=forum_order-1 WHERE forum_cat='$forum_cat' AND forum_order>'".$data['forum_order']."' AND forum_order<='$forum_order'");
		}				
		$result = dbquery("UPDATE ".$db_prefix."forums SET forum_name='$forum_name', forum_cat='$forum_cat', forum_order='$forum_order', forum_description='$forum_description', forum_access='$forum_access', forum_posting='$forum_posting' WHERE forum_id='$forum_id'");
		opentable($locale['404']);
		echo "<center><br>
".$locale['405']."<br><br>
<a href='forums.php'>".$locale['418']."</a><br><br>
<a href='index.php'>".$locale['419']."</a><br><br>
</center>\n";
		closetable();
	} else {
		if ($forum_name != "") {
			$forum_mods = "";
			$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_level='102'");
			while ($data = dbarray($result)) {
				$forum_mods .= $data['user_id'];
				if ($i < dbrows($result)) $forum_mods .= ".";
				$i++;
			}
			if(!$forum_order) $forum_order=dbresult(dbquery("SELECT MAX(forum_order) FROM ".$db_prefix."forums WHERE forum_cat='$forum_cat'"),0)+1;
			$result = dbquery("UPDATE ".$db_prefix."forums SET forum_order=forum_order+1 WHERE forum_cat='$forum_cat' AND forum_order>='$forum_order'");	
			$result = dbquery("INSERT INTO ".$db_prefix."forums VALUES('', '$forum_cat', '$forum_name', '$forum_order', '$forum_description', '$forum_mods', '$forum_access', '$forum_posting', '0', '0')");
		}
		opentable($locale['406']);
		echo "<center><br>
".$locale['407']."<br><br>
<a href='forums.php'>".$locale['418']."</a><br><br>
<a href='index.php'>".$locale['419']."</a><br><br>
</center>\n";
		closetable();
	}
} elseif (isset($_POST['save_forum_mods'])) {
	$forum_mods = $_POST['forum_mods'];
	$result = dbquery("UPDATE ".$db_prefix."forums SET forum_moderators='$forum_mods' WHERE forum_id='".$_POST['forum_id']."'");
	opentable($locale['408']);
	echo "<center><br>
".$locale['409']."<br><br>
<a href='forums.php'>".$locale['418']."</a><br><br>
<a href='index.php'>".$locale['419']."</a><br><br>
</center>\n";
	closetable();
} elseif ($action == "delete" && $t == "cat") {
	opentable($locale['410']);
	if (dbcount("(*)", "forums", "forum_cat='$forum_id'") == 0) {
		$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_id='$forum_id'"));
		$result = dbquery("UPDATE ".$db_prefix."forums SET forum_order=forum_order-1 WHERE forum_order>'".$data['forum_order']."'");
		$result = dbquery("DELETE FROM ".$db_prefix."forums WHERE forum_id='$forum_id'");
		echo "<center><br>
".$locale['411']."<br><br>
<a href='forums.php'>".$locale['418']."</a><br><br>
<a href='index.php'>".$locale['419']."</a><br><br>
</center>\n";
	} else {
		echo "<center><br>
".$locale['412']."<br><br>
".$locale['413']."<br><br>
<a href='forums.php'>".$locale['418']."</a><br><br>
<a href='index.php'>".$locale['419']."</a><br><br>
</center>\n";
	}
	closetable();
} else if ($action == "delete" && $t == "forum") {
	opentable($locale['414']);
	if (dbcount("(*)", "posts", "forum_id='$forum_id'") == 0) {
		$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_id='$forum_id'"));
		$result = dbquery("UPDATE ".$db_prefix."forums SET forum_order=forum_order-1 WHERE forum_order>'".$data['forum_order']."'");
		$result = dbquery("DELETE FROM ".$db_prefix."forums WHERE forum_id='$forum_id'");
		echo "<center><br>
".$locale['415']."<br><br>
<a href='forums.php'>".$locale['418']."</a><br><br>
<a href='index.php'>".$locale['419']."</a><br><br>
</center>\n";
	} else {
		echo "<center><br>
".$locale['416']."<br><br>
".$locale['417']."<br><br>
<a href='forums.php'>".$locale['418']."</a><br><br>
<a href='index.php'>".$locale['419']."</a><br><br>
</center>\n";
	}
	closetable();
} else {
	if ($action == "edit") {
		if ($t == "cat") {
			$result = dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_id='$forum_id'");
			$data = dbarray($result);
			$cat_name = $data['forum_name'];
			$cat_order = $data['forum_order'];
			$cat_title = $locale['420'];
			$cat_action = FUSION_SELF."?action=edit&forum_id=".$data['forum_id']."&t=cat";
			$forum_title = $locale['421'];
			$forum_action = FUSION_SELF;
		} else if ($t == "forum") {
			$result = dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_id='$forum_id'");
			$data = dbarray($result);
			$forum_name = $data['forum_name'];
			$forum_description = $data['forum_description'];
			$forum_cat = $data['forum_cat'];
			$forum_order = $data['forum_order'];
			$forum_access = $data['forum_access'];
			$forum_posting = $data['forum_posting'];
			$forum_title = $locale['422'];
			$forum_action = FUSION_SELF."?action=edit&forum_id=".$data['forum_id']."&t=forum";
			$cat_title = $locale['423'];
			$cat_action = FUSION_SELF;
		}
	} else {
		$cat_name = "";
		$cat_order = "";
		$cat_title = $locale['423'];
		$cat_action = FUSION_SELF;
		$forum_name = "";
		$forum_description = "";
		$forum_cat = "";
		$forum_order = "";
		$forum_access = "";
		$forum_posting = "";
		$forum_title = $locale['421'];
		$forum_action = FUSION_SELF;
	}
	if ($t != "forum") {
		opentable($cat_title);
		echo "<form name='addcat' method='post' action='$cat_action'>
<table align='center' width='300' cellspacing='0' cellpadding='0'>
<tr>
<td class='tbl'>".$locale['440']."<br>
<input type='text' name='cat_name' value='$cat_name' class='textbox' style='width:230px;'></td>
<td width='50' class='tbl'>".$locale['441']."<br>
<input type='text' name='cat_order' value='$cat_order' class='textbox' style='width:45px;'></td>
</tr>
<tr>
<td align='center' colspan='2' class='tbl'>
<input type='submit' name='save_cat' value='".$locale['442']."' class='button'></td>
</tr>
</table>
</form>\n";
		closetable();
	}
	if ($t == "") tablebreak();
	if ($t != "cat") {
		$cat_opts = ""; $sel = "";
		$result2 = dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_cat='0' ORDER BY forum_order");
		if (dbrows($result2) != 0) {
			while ($data2 = dbarray($result2)) {
				if ($action == "edit" && $t == "forum") $sel = ($data2['forum_id'] == $forum_cat ? " selected" : "");
				$cat_opts .= "<option value='".$data2['forum_id']."'$sel>".$data2['forum_name']."</option>\n";
			}
		}
		$user_groups = getusergroups(); $access_opts = "";
		while(list($key, $user_group) = each($user_groups)){
			$sel = ($forum_access == $user_group['0'] ? " selected" : "");
			$access_opts .= "<option value='".$user_group['0']."'$sel>".$user_group['1']."</option>\n";
		}
		$post_groups = getusergroups(); $posting_opts = "";
		while(list($key, $user_group) = each($post_groups)){
			if ($user_group['0'] != "0") {
				$sel = ($forum_posting == $user_group['0'] ? " selected" : "");
				$posting_opts .= "<option value='".$user_group['0']."'$sel>".$user_group['1']."</option>\n";
			}
		}
		opentable($forum_title);
		echo "<form name='addforum' method='post' action='$forum_action'>
<table align='center' width='300' cellspacing='0' cellpadding='0'>
<tr>
<td colspan='2' class='tbl'>".$locale['460']."<br>
<input type='text' name='forum_name' value='$forum_name' class='textbox' style='width:285px;'></td>
</tr>
<tr>
<td colspan='2' class='tbl'>".$locale['461']."<br>
<textarea name='forum_description' rows='2' class='textbox' style='width:285px;'>$forum_description</textarea></td>
</tr>
<tr>
<td class='tbl'>".$locale['462']."<br>
<select name='forum_cat' class='textbox' style='width:225px;'>
$cat_opts</select></td>
<td width='55' class='tbl'>".$locale['463']."<br>
<input type='text' name='forum_order' value='$forum_order' class='textbox' style='width:45px;'></td>
</tr>
<tr>
<td colspan='2' class='tbl'>".$locale['464']."<br>
<select name='forum_access' class='textbox' style='width:225px;'>
$access_opts</select></td>
</tr>
<tr>
<tr>
<td colspan='2' class='tbl'>".$locale['465']."<br>
<select name='forum_posting' class='textbox' style='width:225px;'>
$posting_opts</select></td>
</tr>
<tr>
<td align='center' colspan='2' class='tbl'>
<input type='submit' name='save_forum' value='".$locale['466']."' class='button'></td>
</tr>
</table>
</form>\n";
		closetable();
		if ($action == "edit" && $t == "forum") {
			tablebreak();
			opentable($locale['408']);
			$result = dbquery("SELECT user_id,user_name FROM ".$db_prefix."users ORDER BY user_level DESC, user_name");
			while ($data2 = dbarray($result)) {
				$user_id = $data2['user_id'];
 				if (!preg_match("(^$user_id$|^$user_id\.|\.$user_id\.|\.$user_id$)", $data['forum_moderators'])) {
					$mods1_user_id[] = $data2['user_id'];
					$mods1_user_name[] = $data2['user_name'];
				} else {
					$mods2_user_id[] = $data2['user_id'];
					$mods2_user_name[] = $data2['user_name'];
				}
				unset($user_id);
			}
			echo "<form name='modsform' method='post' action='".FUSION_SELF."'>
<table align='center' cellpadding='0' cellspacing='0' class='tbl'>
<tr>
<td>
<select multiple size='15' name='modlist1' id='modlist1' class='textbox' style='width:150' onChange=\"addUser('modlist2','modlist1');\">\n";
		for ($i=0;$i < count($mods1_user_id);$i++) echo "<option value='".$mods1_user_id[$i]."'>".$mods1_user_name[$i]."</option>\n";
		echo "</select>
</td>
<td align='center' valign='middle'>
</td>
<td>
<select multiple size='15' name='modlist2' id='modlist2' class='textbox' style='width:150' onChange=\"addUser('modlist1','modlist2');\">\n";
		for ($i=0;$i < count($mods2_user_id);$i++) echo "<option value='".$mods2_user_id[$i]."'>".$mods2_user_name[$i]."</option>\n";
		echo "</select>
</td>
</tr>
<tr>
<td align='center' colspan='3'><br>
<input type='hidden' name='forum_mods'>
<input type='hidden' name='forum_id' value='".$data['forum_id']."'>
<input type='hidden' name='save_forum_mods'>
<input type='button' name='update' value='".$locale['467']."' class='button' onclick='saveMods();'></td>
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

function saveMods() {
	var strValues = \"\";
	var boxLength = document.getElementById('modlist2').length;
	var count = 0;
	if (boxLength != 0) {
		for (i = 0; i < boxLength; i++) {
			if (count == 0) {
				strValues = document.getElementById('modlist2').options[i].value;
			} else {
				strValues = strValues + \".\" + document.getElementById('modlist2').options[i].value;
			}
			count++;
		}
	}
	if (strValues.length == 0) {
		document.forms['modsform'].submit();
	} else {
		document.forms['modsform'].forum_mods.value = strValues;
		document.forms['modsform'].submit();
	}
}
</script>\n";
		}
	}
	tablebreak();
	opentable($locale['480']);
	$forum = "<table width='100%' cellspacing='0' cellpadding='0'>\n";
	$result = dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_cat='0' ORDER BY forum_order");
	if (dbrows($result) != 0) {
		while ($data = dbarray($result)) {
			$forum .= "<tr>
<td class='tbl2' colspan='2'>".$data['forum_name']."</td>
<td width='25' class='tbl2'>".$data['forum_order']."</td>
<td width='100' align='right' class='tbl2'><a href='".FUSION_SELF."?action=edit&forum_id=".$data['forum_id']."&t=cat'>".$locale['481']."</a> -
<a href='".FUSION_SELF."?action=delete&forum_id=".$data['forum_id']."&t=cat'>".$locale['482']."</a></td>
</tr>\n";
			$result2 = dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_cat='".$data['forum_id']."' ORDER BY forum_order");
			if (dbrows($result) != 0) {
				while ($data2 = dbarray($result2)) {
					$forum .= "<tr>
<td class='tbl1'><span class='alt'>".$data2['forum_name']."</span><br>
<span class='small'>".$data2['forum_description']."</span></td>
<td align='center' class='tbl1'>".getgroupname($data2['forum_access'])."<br>
<span class='small2'>".getgroupname($data2['forum_posting'])."</span></td>
<td width='25' class='tbl1'>".$data2['forum_order']."</td>
<td width='100' align='right' class='tbl1'><a href='".FUSION_SELF."?action=edit&forum_id=".$data2['forum_id']."&t=forum'>".$locale['481']."</a> -
<a href='".FUSION_SELF."?action=delete&forum_id=".$data2['forum_id']."&t=forum'>".$locale['482']."</a></td>
</tr>\n";
				}
			} else {
				$forum .= "<tr>\n<td colspan='3'>".$locale['483']."</td>\n</tr>\n";
			}
		}
	} else {
		$forum .= "<tr>\n<td colspan='3'>".$locale['484']."</td>\n</tr>\n";
	}
	$forum .= "</table>\n";
	echo $forum;
	closetable();
}

echo "</td>\n";
require_once BASEDIR."footer.php";
?>