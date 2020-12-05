<?
/*--------------------------------------------+
| PHP-Fusion 5 - Content Management System    |
|---------------------------------------------|
| author: Nick Jones (Digitanium) � 2002-2005 |
| web: http://www.php-fusion.co.uk            |
| email: nick@php-fusion.co.uk                |
|---------------------------------------------|
| Released under the terms and conditions of  |
| the GNU General Public License (Version 2)  |
+--------------------------------------------*/
@include "../fusion_config.php";
include "../fusion_core.php";
include FUSION_BASE."subheader.php";
include FUSION_LANGUAGES.FUSION_LAN."admin/admin_main.php";
include FUSION_LANGUAGES.FUSION_LAN."admin/admin_forums.php";
include FUSION_ADMIN."navigation.php";

if (!iADMIN) { header("Location:../index.php"); exit; }

if ($action == "delete" && $t == "cat") {
	opentable(LAN_400);
	$result = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_cat='$forum_id'");
	if (dbrows($result) == 0) {
		$data = dbarray(dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_id='$forum_id'"));
		$result = dbquery("UPDATE ".$fusion_prefix."forums SET forum_order=forum_order-1 WHERE forum_order>'".$data['forum_order']."'");
		$result = dbquery("DELETE FROM ".$fusion_prefix."forums WHERE forum_id='$forum_id'");
		echo "<center><br>
".LAN_401."<br><br>
<a href='forums.php'>".LAN_402."</a><br><br>
<a href='index.php'>".LAN_403."</a><br><br>
</center>\n";
	} else {
		echo "<center><br>
".LAN_404."<br>
<span class='small'>".LAN_405."</span><br><br>
<a href='forums.php'>".LAN_402."</a><br><br>
<a href='index.php'>".LAN_403."</a><br><br>
</center>\n";
	}
	closetable();
} else if ($action == "delete" && $t == "forum") {
	opentable(LAN_406);
	$result = dbquery("SELECT * FROM ".$fusion_prefix."posts WHERE forum_id='$forum_id'");
	if (dbrows($result) == 0) {
		$data = dbarray(dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_id='$forum_id'"));
		$result = dbquery("UPDATE ".$fusion_prefix."forums SET forum_order=forum_order-1 WHERE forum_order>'".$data['forum_order']."'");
		$result = dbquery("DELETE FROM ".$fusion_prefix."forums WHERE forum_id='$forum_id'");
		echo "<center><br>
".LAN_407."<br><br>
<a href='forums.php'>".LAN_402."</a><br><br>
<a href='index.php'>".LAN_403."</a><br><br>
</center>\n";
	} else {
		echo "<center><br>
".LAN_408."<br>
<span class='small'>".LAN_409."</span><br><br>
<a href='forums.php'>".LAN_402."</a><br><br>
<a href='index.php'>".LAN_403."</a><br><br>
</center>\n";
	}
	closetable();
} else {
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
		} else {
			if ($cat_name != "") {
				if(!$cat_order) $cat_order=dbresult(dbquery("SELECT MAX(forum_order) FROM ".$fusion_prefix."forums WHERE forum_cat='0'"),0)+1;
				$result = dbquery("UPDATE ".$fusion_prefix."forums SET forum_order=forum_order+1 WHERE forum_cat='0' AND forum_order>='$cat_order'");	
				$result = dbquery("INSERT INTO ".$fusion_prefix."forums VALUES('', '0', '$cat_name', '$cat_order', '', '0', '0', '0')");
			}
		}
		header("Location:".$PHP_SELF);
	}
	if (isset($_POST[save_forum])) {
		$forum_name = stripinput($_POST['forum_name']);
		$forum_description = stripinput($_POST['forum_description']);
		$forum_cat = isNum($_POST['forum_cat']) ? $_POST['forum_cat'] : "";
		if ($action == "edit" && $t == "forum") {
			$data = dbarray(dbquery("SELECT forum_order FROM ".$fusion_prefix."forums WHERE forum_id='$forum_id'"));
			if($forum_order < $data['forum_order']){
				$result = dbquery("UPDATE ".$fusion_prefix."forums SET forum_order=forum_order+1 WHERE forum_cat='$forum_cat' AND forum_order>='$forum_order' AND forum_order<'".$data['forum_order']."'");
			} else if ($forum_order > $data['forum_order']){
				$result = dbquery("UPDATE ".$fusion_prefix."forums SET forum_order=forum_order-1 WHERE forum_cat='$forum_cat' AND forum_order>'".$data['forum_order']."' AND forum_order<='$forum_order'");
			}				
			$result = dbquery("UPDATE ".$fusion_prefix."forums SET forum_name='$forum_name', forum_cat='$forum_cat', forum_order='$forum_order', forum_description='$forum_description', forum_access='$forum_access' WHERE forum_id='$forum_id'");
		} else {
			if ($forum_name != "") {
				if(!$forum_order) $forum_order=dbresult(dbquery("SELECT MAX(forum_order) FROM ".$fusion_prefix."forums WHERE forum_cat='$forum_cat'"),0)+1;
				$result = dbquery("UPDATE ".$fusion_prefix."forums SET forum_order=forum_order+1 WHERE forum_cat='$forum_cat' AND forum_order>='$forum_order'");	
				$result = dbquery("INSERT INTO ".$fusion_prefix."forums VALUES('', '$forum_cat', '$forum_name', '$forum_order', '$forum_description', '$forum_access', '0', '0')");
			}
		}
		header("Location:".$PHP_SELF);
	}
	if ($action == "edit") {
		if ($t == "cat") {
			$result = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_id='$forum_id'");
			$data = dbarray($result);
			$cat_name = $data['forum_name'];
			$cat_order = $data['forum_order'];
			$cat_title = LAN_420;
			$cat_action = "$PHP_SELF?action=edit&forum_id=".$data['forum_id']."&t=cat";
			$forum_title = LAN_421;
			$forum_action = "$PHP_SELF";
		} else if ($t == "forum") {
			$result = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_id='$forum_id'");
			$data = dbarray($result);
			$forum_name = $data['forum_name'];
			$forum_description = $data['forum_description'];
			$forum_cat = $data[forum_cat];
			$forum_order = $data[forum_order];
			$forum_access = $data[forum_access];
			$forum_title = LAN_422;
			$forum_action = "$PHP_SELF?action=edit&forum_id=".$data['forum_id']."&t=forum";
			$cat_title = LAN_423;
			$cat_action = "$PHP_SELF";
		}
	} else {
		$cat_title = LAN_423;
		$cat_action = "$PHP_SELF";
		$forum_title = LAN_421;
		$forum_action = "$PHP_SELF";
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
	if (!$t) tablebreak();
	if ($t != "cat") {
		$result2 = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_cat='0' ORDER BY forum_order");
		if (dbrows($result2) != 0) {
			while ($data2 = dbarray($result2)) {
				if ($action == "edit" && $t == "forum") {
					if ($data2['forum_id'] == $forum_cat) { $sel = " selected"; } else { $sel = ""; }
				}
				$cat_opts .= "<option value='".$data2['forum_id']."'$sel>".$data2['forum_name']."</option>\n";
			}
		}
		$i = 0;
		$access = array(USER0,USER1,USER2,USER3,USER4);
		while ($access[$i]) {
			if ($action == "edit" && $t == "forum") {
				if ($forum_access == $i) { $sel = " selected"; } else { $sel = ""; }
			}
			$access_opts .= "<option value='$i'$sel>".$access[$i]."</option>\n";
			$i++;
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
<td align='center' colspan='2'>
<input type='submit' name='save_forum' value='".LAN_465."' class='button'></td>
</tr>
</table>
</form>\n";
		closetable();
	}
	tablebreak();
	opentable(LAN_480);
	$forum .= "<table width='100%' cellspacing='0' cellpadding='0' class='tbl'>\n";
	$result = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_cat='0' ORDER BY forum_order");
	if (dbrows($result) != 0) {
		while ($data = dbarray($result)) {
			$forum .= "<tr>
<td class='tbl2'>".$data['forum_name']."</td>
<td class='tbl2'>".$data['forum_order']."</td>
<td class='tbl2'><a href='$PHP_SELF?action=edit&forum_id=".$data['forum_id']."&t=cat'>".LAN_481."</a> -
<a href='$PHP_SELF?action=delete&forum_id=".$data['forum_id']."&t=cat'>".LAN_482."</a></td>
</tr>\n";
			$result2 = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_cat='".$data['forum_id']."' ORDER BY forum_order");
			if (dbrows($result) != 0) {
				while ($data2 = dbarray($result2)) {
					$forum .= "<tr>
<td><span class='alt'>".$data2['forum_name']."</span><br>
<span class='small'>".$data2['forum_description']."</span></td>
<td>$data2[forum_order]</td>
<td><a href='$PHP_SELF?action=edit&forum_id=".$data2['forum_id']."&t=forum'>".LAN_481."</a> -
<a href='$PHP_SELF?action=delete&forum_id=".$data2['forum_id']."&t=forum'>".LAN_482."</a></td>
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
include FUSION_BASE."footer.php";
?>