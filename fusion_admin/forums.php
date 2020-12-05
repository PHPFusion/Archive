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

include FUSION_LANGUAGES.FUSION_LAN."admin/admin_forums.php";

if (!checkrights("A")) { header("Location:../index.php"); exit; }

if (!isset($action)) $action = "";
if (!isset($t)) $t = "";

if (isset($_POST['save_cat'])) {
	$cat_name = stripinput($_POST['cat_name']);
	$cat_order = isNum($_POST['cat_order']) ? $_POST['cat_order'] : "";
	if ($action == "edit" && $t == "cat") {
		$data = dbarray(dbquery("SELECT forum_order FROM ".$fusion_prefix."forums WHERE forum_id='$forum_id'"));
		if($cat_order < $data['forum_order']){
			$result = dbquery("UPDATE ".$fusion_prefix."forums SET forum_order=forum_order+1 WHERE forum_cat='0' AND forum_order>='$cat_order' AND forum_order<'".$data['forum_order']."'");
		} else if ($cat_order > $data['forum_order']){
			$result = dbquery("UPDATE ".$fusion_prefix."forums SET forum_order=forum_order-1 WHERE forum_cat='0' AND forum_order>'".$data['forum_order']."' AND forum_order<='$cat_order'");
		}				
		$result = dbquery("UPDATE ".$fusion_prefix."forums SET forum_name='$cat_name', forum_order='$cat_order' WHERE forum_id='$forum_id'");
		opentable(LAN_400);
		echo "<center><br>
".LAN_401."<br><br>
<a href='forums.php'>".LAN_418."</a><br><br>
<a href='index.php'>".LAN_419."</a><br><br>
</center>\n";
		closetable();
	} else {
		if ($cat_name != "") {
			if(!$cat_order) $cat_order=dbresult(dbquery("SELECT MAX(forum_order) FROM ".$fusion_prefix."forums WHERE forum_cat='0'"),0)+1;
			$result = dbquery("UPDATE ".$fusion_prefix."forums SET forum_order=forum_order+1 WHERE forum_cat='0' AND forum_order>='$cat_order'");	
			$result = dbquery("INSERT INTO ".$fusion_prefix."forums VALUES('', '0', '$cat_name', '$cat_order', '', '', '0', '0', '0', '0')");
		}
		opentable(LAN_402);
		echo "<center><br>
".LAN_403."<br><br>
<a href='forums.php'>".LAN_418."</a><br><br>
<a href='index.php'>".LAN_419."</a><br><br>
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
		$data = dbarray(dbquery("SELECT forum_order FROM ".$fusion_prefix."forums WHERE forum_id='$forum_id'"));
		if($forum_order < $data['forum_order']){
			$result = dbquery("UPDATE ".$fusion_prefix."forums SET forum_order=forum_order+1 WHERE forum_cat='$forum_cat' AND forum_order>='$forum_order' AND forum_order<'".$data['forum_order']."'");
		} else if ($forum_order > $data['forum_order']){
			$result = dbquery("UPDATE ".$fusion_prefix."forums SET forum_order=forum_order-1 WHERE forum_cat='$forum_cat' AND forum_order>'".$data['forum_order']."' AND forum_order<='$forum_order'");
		}				
		$result = dbquery("UPDATE ".$fusion_prefix."forums SET forum_name='$forum_name', forum_cat='$forum_cat', forum_order='$forum_order', forum_description='$forum_description', forum_access='$forum_access', forum_posting='$forum_posting' WHERE forum_id='$forum_id'");
		opentable(LAN_404);
		echo "<center><br>
".LAN_405."<br><br>
<a href='forums.php'>".LAN_418."</a><br><br>
<a href='index.php'>".LAN_419."</a><br><br>
</center>\n";
		closetable();
	} else {
		if ($forum_name != "") {
			$forum_mods = "";
			$result = dbquery("SELECT * FROM ".$fusion_prefix."users WHERE user_level>='251'");
			while ($data = dbarray($result)) {
				$forum_mods .= $data['user_id'];
				if ($i < dbrows($result)) $forum_mods .= ".";
				$i++;
			}
			if(!$forum_order) $forum_order=dbresult(dbquery("SELECT MAX(forum_order) FROM ".$fusion_prefix."forums WHERE forum_cat='$forum_cat'"),0)+1;
			$result = dbquery("UPDATE ".$fusion_prefix."forums SET forum_order=forum_order+1 WHERE forum_cat='$forum_cat' AND forum_order>='$forum_order'");	
			$result = dbquery("INSERT INTO ".$fusion_prefix."forums VALUES('', '$forum_cat', '$forum_name', '$forum_order', '$forum_description', '$forum_mods', '$forum_access', '$forum_posting', '0', '0')");
		}
		opentable(LAN_406);
		echo "<center><br>
".LAN_407."<br><br>
<a href='forums.php'>".LAN_418."</a><br><br>
<a href='index.php'>".LAN_419."</a><br><br>
</center>\n";
		closetable();
	}
} elseif (isset($_POST['save_forum_mods'])) {
	$forum_mods = $_POST['forum_mods'];
	$sql = dbquery("UPDATE ".$fusion_prefix."forums SET forum_moderators='$forum_mods' WHERE forum_id='".$_POST['forum_id']."'");
	opentable(LAN_408);
	echo "<center><br>
".LAN_409."<br><br>
<a href='forums.php'>".LAN_418."</a><br><br>
<a href='index.php'>".LAN_419."</a><br><br>
</center>\n";
	closetable();
} elseif ($action == "delete" && $t == "cat") {
	opentable(LAN_410);
	if (dbcount("(*)", "forums", "forum_cat='$forum_id'") == 0) {
		$data = dbarray(dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_id='$forum_id'"));
		$result = dbquery("UPDATE ".$fusion_prefix."forums SET forum_order=forum_order-1 WHERE forum_order>'".$data['forum_order']."'");
		$result = dbquery("DELETE FROM ".$fusion_prefix."forums WHERE forum_id='$forum_id'");
		echo "<center><br>
".LAN_411."<br><br>
<a href='forums.php'>".LAN_418."</a><br><br>
<a href='index.php'>".LAN_419."</a><br><br>
</center>\n";
	} else {
		echo "<center><br>
".LAN_412."<br><br>
".LAN_413."<br><br>
<a href='forums.php'>".LAN_418."</a><br><br>
<a href='index.php'>".LAN_419."</a><br><br>
</center>\n";
	}
	closetable();
} else if ($action == "delete" && $t == "forum") {
	opentable(LAN_414);
	if (dbcount("(*)", "posts", "forum_id='$forum_id'") == 0) {
		$data = dbarray(dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_id='$forum_id'"));
		$result = dbquery("UPDATE ".$fusion_prefix."forums SET forum_order=forum_order-1 WHERE forum_order>'".$data['forum_order']."'");
		$result = dbquery("DELETE FROM ".$fusion_prefix."forums WHERE forum_id='$forum_id'");
		echo "<center><br>
".LAN_415."<br><br>
<a href='forums.php'>".LAN_418."</a><br><br>
<a href='index.php'>".LAN_419."</a><br><br>
</center>\n";
	} else {
		echo "<center><br>
".LAN_416."<br><br>
".LAN_417."<br><br>
<a href='forums.php'>".LAN_418."</a><br><br>
<a href='index.php'>".LAN_419."</a><br><br>
</center>\n";
	}
	closetable();
} else {
	if ($action == "edit") {
		if ($t == "cat") {
			$result = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_id='$forum_id'");
			$data = dbarray($result);
			$cat_name = $data['forum_name'];
			$cat_order = $data['forum_order'];
			$cat_title = LAN_420;
			$cat_action = FUSION_SELF."?action=edit&forum_id=".$data['forum_id']."&t=cat";
			$forum_title = LAN_421;
			$forum_action = FUSION_SELF;
		} else if ($t == "forum") {
			$result = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_id='$forum_id'");
			$data = dbarray($result);
			$forum_name = $data['forum_name'];
			$forum_description = $data['forum_description'];
			$forum_cat = $data['forum_cat'];
			$forum_order = $data['forum_order'];
			$forum_access = $data['forum_access'];
			$forum_posting = $data['forum_posting'];
			$forum_title = LAN_422;
			$forum_action = FUSION_SELF."?action=edit&forum_id=".$data['forum_id']."&t=forum";
			$cat_title = LAN_423;
			$cat_action = FUSION_SELF;
		}
	} else {
		$cat_name = "";
		$cat_order = "";
		$cat_title = LAN_423;
		$cat_action = FUSION_SELF;
		$forum_name = "";
		$forum_description = "";
		$forum_cat = "";
		$forum_order = "";
		$forum_access = "";
		$forum_posting = "";
		$forum_title = LAN_421;
		$forum_action = FUSION_SELF;
	}
	if ($t != "forum") {
		opentable($cat_title);
		echo "<form name='addcat' method='post' action='$cat_action'>
<table align='center' width='300' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td>".LAN_440."<br>
<input type='text' name='cat_name' value='$cat_name' class='textbox' style='width:230px;'></td>
<td width='50'>".LAN_441."<br>
<input type='text' name='cat_order' value='$cat_order' class='textbox' style='width:45px;'></td>
</tr>
<tr>
<td align='center' colspan='2'>
<input type='submit' name='save_cat' value='".LAN_442."' class='button'></td>
</tr>
</table>
</form>\n";
		closetable();
	}
	if ($t == "") tablebreak();
	if ($t != "cat") {
		$cat_opts = ""; $sel = "";
		$result2 = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_cat='0' ORDER BY forum_order");
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
<table align='center' width='300' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td colspan='2'>".LAN_460."<br>
<input type='text' name='forum_name' value='$forum_name' class='textbox' style='width:285px;'></td>
</tr>
<tr>
<td colspan='2'>".LAN_461."<br>
<textarea name='forum_description' rows='2' class='textbox' style='width:285px;'>$forum_description</textarea></td>
</tr>
<tr>
<td>".LAN_462."<br>
<select name='forum_cat' class='textbox' style='width:225px;'>
$cat_opts</select></td>
<td width='55'>".LAN_463."<br>
<input type='text' name='forum_order' value='$forum_order' class='textbox' style='width:45px;'></td>
</tr>
<tr>
<td colspan='2'>".LAN_464."<br>
<select name='forum_access' class='textbox' style='width:225px;'>
$access_opts</select></td>
</tr>
<tr>
<tr>
<td colspan='2'>".LAN_465."<br>
<select name='forum_posting' class='textbox' style='width:225px;'>
$posting_opts</select></td>
</tr>
<tr>
<td align='center' colspan='2'>
<input type='submit' name='save_forum' value='".LAN_466."' class='button'></td>
</tr>
</table>
</form>\n";
		closetable();
		if ($action == "edit" && $t == "forum") {
			tablebreak();
			opentable(LAN_408);
			$sql = dbquery("SELECT user_id,user_name FROM ".$fusion_prefix."users ORDER BY user_level DESC, user_name");
			$o = 0; $i = 0; $forum_mods = explode(".", $data['forum_moderators']);
			while ($data2 = dbarray($sql)) {
				if (!in_array($data2['user_id'], $forum_mods)) {
					$mods1_user_id[$o] = $data2['user_id'];
					$mods1_user_name[$o] = $data2['user_name'];
					$o++;
				} else {
					$mods2_user_id[$i] = $data2['user_id'];
					$mods2_user_name[$i] = $data2['user_name'];
					$i++;
				}
			}
			echo "<form name='modsform' method='post' action='".FUSION_SELF."'>
<table align='center' cellpadding='0' cellspacing='0' class='tbl'>
<tr>
<td>
<select multiple size='15' name='modlist1' id='modlist1' class='textbox' style='width:150' onChange=\"addUser('modlist2','modlist1');\">\n";
		for ($c=0;$c<$o;$c++) echo "<option value='".$mods1_user_id[$c]."'>".$mods1_user_name[$c]."</option>\n";
		echo "</select>
</td>
<td align='center' valign='middle'>
</td>
<td>
<select multiple size='15' name='modlist2' id='modlist2' class='textbox' style='width:150' onChange=\"addUser('modlist1','modlist2');\">\n";
		for ($c=0;$c<$i;$c++) echo "<option value='".$mods2_user_id[$c]."'>".$mods2_user_name[$c]."</option>\n";
		echo "</select>
</td>
</tr>
<tr>
<td align='center' colspan='3'><br>
<input type='hidden' name='forum_mods'>
<input type='hidden' name='forum_id' value='".$data['forum_id']."'>
<input type='hidden' name='save_forum_mods'>
<input type='button' name='update' value='".LAN_467."' class='button' onclick='saveMods();'></td>
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
	opentable(LAN_480);
	$forum = "<table width='100%' cellspacing='0' cellpadding='0'>\n";
	$result = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_cat='0' ORDER BY forum_order");
	if (dbrows($result) != 0) {
		while ($data = dbarray($result)) {
			$forum .= "<tr>
<td class='tbl2' colspan='2'>".$data['forum_name']."</td>
<td width='25' class='tbl2'>".$data['forum_order']."</td>
<td width='100' align='right' class='tbl2'><a href='".FUSION_SELF."?action=edit&forum_id=".$data['forum_id']."&t=cat'>".LAN_481."</a> -
<a href='".FUSION_SELF."?action=delete&forum_id=".$data['forum_id']."&t=cat'>".LAN_482."</a></td>
</tr>\n";
			$result2 = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_cat='".$data['forum_id']."' ORDER BY forum_order");
			if (dbrows($result) != 0) {
				while ($data2 = dbarray($result2)) {
					$forum .= "<tr>
<td class='tbl1'><span class='alt'>".$data2['forum_name']."</span><br>
<span class='small'>".$data2['forum_description']."</span></td>
<td align='center' class='tbl1'>".getgroupname($data2['forum_access'])."<br>
<span class='small2'>".getgroupname($data2['forum_posting'])."</span></td>
<td width='25' class='tbl1'>".$data2['forum_order']."</td>
<td width='100' align='right' class='tbl1'><a href='".FUSION_SELF."?action=edit&forum_id=".$data2['forum_id']."&t=forum'>".LAN_481."</a> -
<a href='".FUSION_SELF."?action=delete&forum_id=".$data2['forum_id']."&t=forum'>".LAN_482."</a></td>
</tr>\n";
				}
			} else {
				$forum .= "<tr>
<td colspan='3'>".LAN_483."</td>
</tr>\n";
			}
		}
	} else {
		$forum .= "<tr>
<td colspan='3'>".LAN_484."</td>
</tr>\n";
	}
	$forum .= "</table>\n";
	echo $forum;
	closetable();
}

echo "</td>\n";
require_once FUSION_BASE."footer.php";
?>