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
include FUSION_LANGUAGES.FUSION_LAN."forum/forum_options.php";
require_once FUSION_BASE."side_left.php";

if (iMEMBER) {
	$data = dbarray(dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_id='".$forum_id."'"));
	if (!checkgroup($data['forum_access'])) { header("Location: index.php"); exit; }
	$forum_mods = explode(".", $data['forum_moderators']);
	if (in_array($userdata['user_id'], $forum_mods)) { define("iMOD", true); } else { define("iMOD", false); }
} else {
	define("iMOD", false);
}

if (!iMOD || !checkgroup($data['forum_posting'])) { header("Location: index.php"); exit; }

if ($step == "renew") {
	$result = dbquery("UPDATE ".$fusion_prefix."threads SET thread_lastpost='".time()."' WHERE thread_id='$thread_id'");
	opentable(LAN_458);
	echo "<center><br>
".LAN_459."<br><br>
<a href='viewforum.php?forum_id=$forum_id'>".LAN_402."</a><br><br>
<a href='index.php'>".LAN_403."</a><br><br>
</center>\n";
	closetable();
}
if ($step == "delete") {
	$threads_count = dbcount("(forum_id)", "threads", "forum_id='$forum_id'") - 1;
       	if ($threads_count == 0) {
       		$result = dbquery("UPDATE ".$fusion_prefix."forums SET forum_lastuser='0', forum_lastpost='0' WHERE forum_id='$forum_id'");
       	}
	$result = dbquery("DELETE FROM ".$fusion_prefix."posts WHERE thread_id='$thread_id'");
	$result = dbquery("DELETE FROM ".$fusion_prefix."threads WHERE thread_id='$thread_id'");
	$result = dbquery("SELECT * FROM ".$fusion_prefix."forum_attachments WHERE thread_id='$thread_id'");
	if (dbrows($result) != 0) {
		while ($attach = dbarray($result)) {
			unlink(FUSION_PUBLIC."attachments/".$attach['attach_name']."[".$attach['attach_id']."]".$attach['attach_ext']);
		}
	}
	$result = dbquery("DELETE FROM ".$fusion_prefix."forum_attachments WHERE thread_id='$thread_id'");
	opentable(LAN_400);
	echo "<center><br>
".LAN_401."<br><br>
<a href='viewforum.php?forum_id=$forum_id'>".LAN_402."</a><br><br>
<a href='index.php'>".LAN_403."</a><br><br>
</center>\n";
	closetable();
}
if ($step == "lock") {
	$sql = dbquery("UPDATE ".$fusion_prefix."threads SET thread_locked='1' WHERE thread_id='$thread_id'");
	opentable(LAN_410);
	echo "<center><br>
".LAN_411."<br><br>
<a href='viewforum.php?forum_id=$forum_id'>".LAN_402."</a><br><br>
<a href='index.php'>".LAN_403."</a><br><br>
</center>\n";
	closetable();
}
if ($step == "unlock") {
	$sql = dbquery("UPDATE ".$fusion_prefix."threads SET thread_locked='0' WHERE thread_id='$thread_id'");
	opentable(LAN_420);
	echo "<center><br>
".LAN_421."<br><br>
<a href='viewforum.php?forum_id=$forum_id'>".LAN_402."</a><br><br>
<a href='index.php'>".LAN_403."</a><br><br>
</center>\n";
	closetable();
}
if ($step == "sticky") {
	$sql = dbquery("UPDATE ".$fusion_prefix."threads SET thread_sticky='1' WHERE thread_id='$thread_id'");
	opentable(LAN_430);
	echo "<center><br>
".LAN_431."<br><br>
<a href='viewforum.php?forum_id=$forum_id'>".LAN_402."</a><br><br>
<a href='index.php'>".LAN_403."</a><br><br>
</center>\n";
	closetable();
}
if ($step == "nonsticky") {
	$sql = dbquery("UPDATE ".$fusion_prefix."threads SET thread_sticky='0' WHERE thread_id='$thread_id'");
	opentable(LAN_440);
	echo "<center><br>
".LAN_441."<br><br>
<a href='viewforum.php?forum_id=$forum_id'>".LAN_402."</a><br><br>
<a href='index.php'>".LAN_403."</a><br><br>
</center>\n";
	closetable();
}
if ($step == "move") {
	opentable(LAN_450);
	if (isset($_POST['move_thread'])) {
		$sql = dbquery("UPDATE ".$fusion_prefix."threads SET forum_id='$new_forum_id' WHERE thread_id='$thread_id'");
		$sql = dbquery("UPDATE ".$fusion_prefix."posts SET forum_id='$new_forum_id' WHERE thread_id='$thread_id'");
		echo "<center><br>
".LAN_452."<br><br>
<a href='index.php'>".LAN_403."</a>
<br><br></center>\n";
	} else {
		$move_list = ""; $sel = "";
		$sql = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_cat='0' ORDER BY forum_order");
		if (dbrows($sql) != 0) {
			while ($data = dbarray($sql)) {
				$sql2 = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_cat='".$data['forum_id']."' ORDER BY forum_order");
				if (dbrows($sql2) != 0) {
					$move_list .= "<optgroup label='".$data['forum_name']."'>\n";
					while ($data2 = dbarray($sql2)) {
						if ($forum_id == $data2['forum_id']) { $sel = " selected"; } else { $sel = ""; }
						$move_list .= "<option value='".$data2['forum_id']."'$sel>".$data2['forum_name']."</option>\n";
					}
					$move_list .= "</optgroup>\n";
				}
			}
		}
		echo "<form name='moveform' method='post' action='".FUSION_SELF."?step=move&forum_id=$forum_id&thread_id=$thread_id'>
<table cellpadding='0' cellspacing='0' class='tbl-border' width='100%'>
<tr>
<td>
<table cellpadding='0' cellspacing='1' width='100%'>
<tr>
<td class='tbl2' width='150'>".LAN_451."</td>
<td class='tbl1'><select name='new_forum_id' class='textbox' style='width:250px;'>
$move_list</select></td>
</tr>
<tr>
<td colspan='2' class='tbl2' style='text-align:center;'><input type='submit' name='move_thread' value='".LAN_450."' class='button'></td>
</tr>
</table>
</td>
</tr>
</table>
</form>\n";
	}
	closetable();
}

require_once FUSION_BASE."side_right.php";
require_once FUSION_BASE."footer.php";
?>