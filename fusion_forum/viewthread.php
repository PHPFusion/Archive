<?
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
@include "../fusion_config.php";
include "../fusion_core.php";
include FUSION_BASE."subheader.php";
include FUSION_LANGUAGES.FUSION_LAN."forum/forum_main.php";
include FUSION_BASE."side_left.php";

if (!FUSION_QUERY || !$forum_id || !isNum($forum_id) || !$thread_id || !isNum($thread_id)) { header("Location:index.php"); exit; }

$result = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_id='".$forum_id."'");
if (dbrows($result)) {
	$fdata = dbarray($result);
	if ($fdata['forum_access'] && iUSER < $fdata['forum_access'] || !$fdata['forum_cat']) { header("Location:index.php"); exit; }
} else {
	header("Location:index.php"); exit;
}

$result = dbquery("SELECT * FROM ".$fusion_prefix."threads WHERE thread_id='".$thread_id."' AND forum_id='".$fdata['forum_id']."'");
if (dbrows($result)) { $tdata = dbarray($result); } else { header("Location:index.php"); exit; }

$fcdata = dbarray(dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_id='".$fdata['forum_cat']."'"));

$caption = $fcdata['forum_name']." | <a href='viewforum.php?forum_id=".$fdata['forum_id']."'>".$fdata['forum_name']."</a>";;
$result = dbquery("UPDATE ".$fusion_prefix."threads SET thread_views=thread_views+1 WHERE thread_id='$thread_id'");

opentable(LAN_500);
if (iMEMBER) {
	echo "<table width='100%' cellspacing='0' cellpadding='0'>
<tr>
<td class='smallalt'>
<a href='index.php'>".$settings['sitename']."</a> |
$caption</td>
<td align='right'>\n";
	if (!$tdata['thread_locked']) {
		echo "<a href='post.php?action=reply&forum_id=$forum_id&thread_id=$thread_id'><img src='".FUSION_THEME."forum/reply.gif' border='0'></a>\n";
	}
	echo "<a href='post.php?action=newthread&forum_id=$forum_id'><img src='".FUSION_THEME."forum/newthread.gif' border='0'></a>
</td>
</tr>
</table>\n";
}

$rows = dbrows(dbquery("SELECT * FROM ".$fusion_prefix."posts WHERE thread_id='$thread_id'"));
if (!$rowstart) $rowstart = 0;
echo "<div align='center' style='margin-top:5px;margin-bottom:5px;'>
".makePageNav($rowstart,20,$rows,3,"$PHP_SELF?forum_id=$forum_id&thread_id=$thread_id&")."
</div>\n";

echo "<table cellspacing='0' cellpadding='0' border='0' width='100%' class='tbl-border'>
<tr>
<td>
<table border='0' cellspacing='1' cellpadding='0' width='100%'>\n";

if ($rows != 0) {
	$result = dbquery(
		"SELECT * FROM ".$fusion_prefix."posts
		LEFT JOIN ".$fusion_prefix."users ON ".$fusion_prefix."posts.post_author=".$fusion_prefix."users.user_id
		WHERE thread_id='$thread_id' ORDER BY post_datestamp LIMIT $rowstart,20"
	);
	$numrows = dbrows($result);
	$i = 0;
	while ($data = dbarray($result)) {
		$i++;
		$message = $data['post_message'];
		if ($data['post_showsig']) { $message = $message."\n\n<hr>".$data['user_sig']; }
		if ($data['post_smileys']) { $message = parsesmileys($message); }
		$message = parseubb($message);
		$message = nl2br($message);
		if ($data['post_edittime'] != "0") {
			if ($data['post_author'] != $data['post_edituser']) {
				$data2 = dbarray(dbquery("SELECT user_id,user_name FROM ".$fusion_prefix."users WHERE user_id='".$data['post_edituser']."'"));
				$edituser = "<a href='../profile.php?lookup=".$data2['user_id']."'>".$data2['user_name']."</a>";
			} else {
				$edituser = "<a href='../profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a>";
			}
			$edittime = showdate("forumdate", $data['post_edittime']);
		}
		echo "<tr>
<td width='145' class='tbl2'>".LAN_501."</td>
<td class='tbl2'><a name='".$data['post_id']."'></a>".$data['post_subject']."</td>
</tr>
<tr>
<td valign='top' rowspan='3' width='145' class='tbl1'>
<a href='../profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a><br>
<span class='alt'>".getmodlevel($data['user_mod'])."</span><br><br>\n";
		if ($data['user_avatar'] != "") {
			echo "<img src='".FUSION_BASE."fusion_public/avatars/".$data['user_avatar']."'><br><br>\n";
			$height = "170";
		} else {
			$height = "55";
		}
		echo "<span class='alt'>".LAN_502."</span> ".$data['user_posts']."<br>
<span class='alt'>".LAN_503."</span> ".$data['user_location']."<br>
<span class='alt'>".LAN_504."</span> ".showdate("%d.%m.%y", $data['user_joined'])."</td>
<td>
<table width='100%' cellspacing='0' cellpadding='0'>
<td class='tbl1'>".LAN_505.showdate("forumdate", $data['post_datestamp'])."</td>
<td align='right' class='tbl1'>\n";
		if (iMEMBER) {
			if (!$tdata['thread_locked']) {
				if ($userdata['user_id'] == $data['post_author'] || iMOD) {
					echo "<a href='post.php?action=edit&forum_id=$forum_id&thread_id=".$data['thread_id']."&post_id=".$data['post_id']."'><img src='".FUSION_THEME."forum/edit.gif' border='0'></a>\n";
				}
				echo "<a href='post.php?action=reply&forum_id=$forum_id&thread_id=".$data['thread_id']."&post_id=".$data['post_id']."&quote=".$data['post_id']."'><img src='".FUSION_THEME."forum/quote.gif' alt='quote' border='0'></a>\n";
			} else {
				if (iMOD) {
					echo "<a href='post.php?action=edit&forum_id=$forum_id&thread_id=".$data['thread_id']."&post_id=".$data['post_id']."'><img src='".FUSION_THEME."forum/edit.gif' border='0'></a>";
				}
			}
		}
		echo "</td>
</tr>
</table>
</td>
</tr>
<tr>
<td height='$height' valign='top' class='tbl1'>
$message";
		$result2 = dbquery("SELECT * FROM ".$fusion_prefix."forum_attachments WHERE post_id='".$data['post_id']."'");
		if (dbrows($result2) != 0) {
			$attach = dbarray($result2);
			if ($attach['attach_ext'] == ".gif" || $attach['attach_ext'] == ".jpg" || $attach['attach_ext'] == ".png") {
				echo "<br><br>
<span class='small'>".$data['user_name'].LAN_506."</span><br><br>
<img src='".FUSION_BASE."fusion_public/attachments/".$attach['attach_name']."[".$attach['attach_id']."]".$attach['attach_ext']."'>";
			} else {
				echo "<br><br>
<span class='small'>".$data['user_name'].LAN_507."</span><br>
<a href='".FUSION_BASE."fusion_public/attachments/".$attach['attach_name']."[".$attach['attach_id']."]".$attach['attach_ext']."'>".$attach['attach_name']."[".$attach['attach_id']."]".$attach['attach_ext']."</a>";
			}
		}
		if ($data['post_edittime'] != "0") {
			echo "<br>
<br>
<span class='small'>".LAN_508."$edituser".LAN_509."$edittime</span>";
		}
echo "</td>
</tr>
<td class='tbl1'>\n";
		if ($data['user_icq']) {
			echo "<a href='http://web.icq.com/wwp?Uin=".$data['user_icq']."' target='_blank'><img src='".FUSION_THEME."forum/icq.gif' alt='".$data['user_icq']."' border='0'></a> ";
		}
		if ($data['user_msn']) {
			echo "<a href='mailto:$data[user_msn]'><img src='".FUSION_THEME."forum/msn.gif' alt='".$data['user_msn']."' border='0'></a> ";
		}
		if ($data['user_yahoo']) {
			echo "<a href='http://uk.profiles.yahoo.com/$data[user_yahoo]' target='_blank'><img src='".FUSION_THEME."forum/yahoo.gif' alt='".$data['user_yahoo']."' border='0'></a> ";
		}
		if ($data['user_web']) {
			if (!strstr($data['user_web'], "http://")) { $urlprefix = "http://"; } else { $urlprefix = ""; }
			echo "<a href='".$urlprefix."".$data['user_web']."' target='_blank'><img src='".FUSION_THEME."forum/web.gif' alt='".$data['user_web']."' border='0'></a> ";
		}
		echo "<a href='../messages.php?step=send&user_id=".$data['user_id']."'><img src='".FUSION_THEME."forum/pm.gif' border='0'></a>
</td>
</tr>\n";
	}
}

echo "</table>
</td>
</tr>
</table>\n";

echo "<div align='center' style='margin-top:5px;'>
".makePageNav($rowstart,20,$rows,3,"$PHP_SELF?forum_id=$forum_id&thread_id=$thread_id&")."
</div>\n";

if (iMEMBER) {
	echo "<div style='margin-top:5px;'>
<form name='modopts' method='post' action='options.php?forum_id=$forum_id&thread_id=$thread_id'>
<table cellpadding='0' cellspacing='0' width='100%'>
<tr>\n";
	if (iMOD) {
		echo "<td style='text-align:left;'>
".LAN_520."<br>
<select name='step' class='textbox'>
<option value='none'></option>
<option value='renew'>".LAN_527."</option>
<option value='delete'>".LAN_521."</option>\n";
		if (!$tdata['thread_locked']) { 
			echo "<option value='lock'>".LAN_522."</option>\n";
		} else {
			echo "<option value='unlock'>".LAN_523."</option>\n";
		}
		if (!$tdata['thread_sticky']) {
			echo "<option value='sticky'>".LAN_524."</option>\n";
		} else {
			echo "<option value='nonsticky'>".LAN_525."</option>\n";
		}
		echo "<option value='move'>".LAN_526."</option>\n";
		echo "</select>
<input type='submit' name='go' value='".LAN_528."' class='button'>
</td>\n";
	}
	echo "<td style='text-align:right;vertical-align:bottom;'>\n";
	if (!$tdata['thread_locked']) {
		echo "<a href='post.php?action=reply&forum_id=$forum_id&thread_id=$thread_id'><img src='".FUSION_THEME."forum/reply.gif' border='0'></a>";
	}
	echo "&nbsp;<a href='post.php?action=newthread&forum_id=$forum_id'><img src='".FUSION_THEME."forum/newthread.gif' border='0'></a>
</td>
</tr>
</table>
</form>
</div>\n";
}
closetable();

include FUSION_BASE."side_right.php";
include FUSION_BASE."footer.php";
?>