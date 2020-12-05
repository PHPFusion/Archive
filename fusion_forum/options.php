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
require fusion_langdir."forum/forum_options.php";
require "navigation.php";

if (Moderator()) {
	if ($step == "delete") {
		$result = dbquery("SELECT count(thread_id) FROM ".$fusion_prefix."posts WHERE thread_id='$thread_id'");
	        $postcount = dbresult($result, 0);
		$result = dbquery("SELECT count(forum_id) FROM ".$fusion_prefix."threads WHERE forum_id='$forum_id'");
	        $threadcount = dbresult($result, 0);
	        $threadcount--;
        	if ($threadcount == 0) {
        		$lastuser = ", forum_lastuser='0', forum_lastpost='0'";
        	}
		$result = dbquery("UPDATE ".$fusion_prefix."forums SET forum_threads=forum_threads-1, forum_posts=forum_posts-$postcount".$lastuser." WHERE forum_id='$forum_id'");
		$result = dbquery("DELETE FROM ".$fusion_prefix."posts WHERE thread_id='$thread_id'");
		$result = dbquery("DELETE FROM ".$fusion_prefix."threads WHERE thread_id='$thread_id'");
		$result = dbquery("SELECT * FROM ".$fusion_prefix."forum_attachments WHERE thread_id='$thread_id'");
		if (dbrows($result) != 0) {
			while ($attach = dbarray($result)) {
				unlink(fusion_basedir."fusion_public/attachments/".$attach[attach_name]."[".$attach[attach_id]."]".$attach[attach_ext]);
			}
		}
		$result = dbquery("DELETE FROM ".$fusion_prefix."forum_attachments WHERE thread_id='$thread_id'");
		opentable(LAN_400);
		echo "<center><br />
".LAN_401."<br /><br />
<a href=\"viewforum.php?forum_id=$forum_id&forum_cat=$forum_cat\">".LAN_402."</a><br /><br />
<a href=\"index.php\">".LAN_403."</a><br /><br />
</center>\n";
		closetable();
	}
	if ($step == "lock") {
		$sql = dbquery("UPDATE ".$fusion_prefix."threads SET thread_locked='y' WHERE thread_id='$thread_id'");
		opentable(LAN_410);
		echo "<center><br />
".LAN_411."<br /><br />
<a href=\"viewforum.php?forum_id=$forum_id&forum_cat=$forum_cat\">".LAN_402."</a><br /><br />
<a href=\"index.php\">".LAN_403."</a><br /><br />
</center>\n";
		closetable();
	}
	if ($step == "unlock") {
		$sql = dbquery("UPDATE ".$fusion_prefix."threads SET thread_locked='n' WHERE thread_id='$thread_id'");
		opentable(LAN_420);
		echo "<center><br />
".LAN_421."<br /><br />
<a href=\"viewforum.php?forum_id=$forum_id&forum_cat=$forum_cat\">".LAN_402."</a><br /><br />
<a href=\"index.php\">".LAN_403."</a><br /><br />
</center>\n";
		closetable();
	}
	if ($step == "sticky") {
		$sql = dbquery("UPDATE ".$fusion_prefix."threads SET thread_sticky='y' WHERE thread_id='$thread_id'");
		opentable(LAN_430);
		echo "<center><br />
".LAN_431."<br /><br />
<a href=\"viewforum.php?forum_id=$forum_id&forum_cat=$forum_cat\">".LAN_402."</a><br /><br />
<a href=\"index.php\">".LAN_403."</a><br /><br />
</center>\n";
		closetable();
	}
	if ($step == "nonsticky") {
		$sql = dbquery("UPDATE ".$fusion_prefix."threads SET thread_sticky='n' WHERE thread_id='$thread_id'");
		opentable(LAN_440);
		echo "<center><br />
".LAN_441."<br /><br />
<a href=\"viewforum.php?forum_id=$forum_id&forum_cat=$forum_cat\">".LAN_402."</a><br /><br />
<a href=\"index.php\">".LAN_403."</a><br /><br />
</center>\n";
		closetable();
	}
	if ($step == "move") {
		opentable(LAN_450);
		if (isset($_POST['move_thread'])) {
			$sql = dbquery("SELECT count(post_id) FROM ".$fusion_prefix."posts WHERE thread_id='$thread_id'");
			$post_count = dbresult($sql, 0);
			$sql = dbquery("UPDATE ".$fusion_prefix."threads SET forum_id='$new_forum_id' WHERE thread_id='$thread_id'");
			$sql = dbquery("UPDATE ".$fusion_prefix."posts SET forum_id='$new_forum_id' WHERE thread_id='$thread_id'");
			$sql = dbquery("UPDATE ".$fusion_prefix."forums SET forum_threads=forum_threads-1, forum_posts=forum_posts-$post_count WHERE forum_id='$forum_id'");
			$sql = dbquery("UPDATE ".$fusion_prefix."forums SET forum_threads=forum_threads+1, forum_posts=forum_posts+$post_count WHERE forum_id='$new_forum_id'");
			echo "<center><br />
".LAN_452."<br /><br />
<a href=\"index.php\">".LAN_403."</a>
<br /><br /></center>\n";
		} else {
			$sql = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_cat>'0' ORDER BY forum_order");
			if (dbrows($sql) != 0) {
				while ($data = dbarray($sql)) {
					if ($forum_id == $data[forum_id]) { $sel = " selected"; } else { $sel = ""; }
					$move_list .= "<option value='$data[forum_id]'$sel>".$data[forum_name]."</option>\n";
				}
			}
			echo "<form name='moveform' method='post' action='$PHP_SELF?step=move&forum_id=$forum_id&thread_id=$thread_id'>
<table cellpadding='0' cellspacing='0' class='forum-border' width='100%'>
<tr>
<td>
<table cellpadding='0' cellspacing='1' width='100%'>
<tr>
<td class='forum2' width='150'>".LAN_451."</td>
<td class='forum1'><select name='new_forum_id' class='textbox' style='width:250px;'>
$move_list</select></td>
</tr>
<tr>
<td colspan='2' class='forum2' style='text-align:center;'><input type='submit' name='move_thread' value='".LAN_450."' class='button' /></td>
</tr>
</table>
</td>
</tr>
</table>
</form>\n";
		}
		closetable();
	}
}

echo "</td>\n";
require "../footer.php";
?>