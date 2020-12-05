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
require fusion_langdir."forum/forum_main.php";
require "navigation.php";

if (empty($forum_cat) || ($forum_cat == "")) {
	$result = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_id='$forum_id'");
	$data = dbarray($result);
	$forum_cat = stripslashes($data[forum_cat]);
}

// Get the Forum Category name
$result = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_id='$forum_cat'");
$data = dbarray($result);
$caption = $data[forum_name];

// Get the Forum name
$result = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_id='$forum_id'");
if (dbrows($result) != 0) {
	$data = dbarray($result);
	$access_level = $data[forum_access];
	$caption .= " | <a href='viewforum.php?forum_id=$data[forum_id]&forum_cat=$data[forum_cat]'>".stripslashes($data[forum_name])."</a>";
}

// Update the View count in the parent Thread
$result = dbquery("UPDATE ".$fusion_prefix."threads SET thread_views=thread_views+1 WHERE thread_id='$thread_id'");

// Get the Thread Subject
$result = dbquery("SELECT * FROM ".$fusion_prefix."threads WHERE thread_id='$thread_id'");
if (dbrows($result) != 0) {
	$data = dbarray($result);
	$sticky = $data[thread_sticky];
	$locked = $data[thread_locked];
}

opentable(LAN_500);

if (UserLevel() >= $access_level) {

if (Member()) {
	echo "<table width='100%' cellspacing='0' cellpadding='0'>
<tr>
<td class='smallalt'>
<a href='index.php'>$settings[sitename]</a> |
$caption</td>
<td align='right'>\n";
	if ($locked != "y") {
		echo "<a href='post.php?action=reply&forum_id=$forum_id&forum_cat=$forum_cat&thread_id=$thread_id'><img src='".fusion_themedir."forum/reply.gif' border='0'></a>\n";
	}
	echo "<a href='post.php?action=newthread&forum_id=$forum_id&forum_cat=$forum_cat'><img src='".fusion_themedir."forum/newthread.gif' border='0'></a>
</td>
</tr>
</table>\n";
}

$itemsperpage = 20;
$rows = dbrows(dbquery("SELECT * FROM ".$fusion_prefix."posts WHERE thread_id='$thread_id'"));
if (!$rowstart) $rowstart = 0;
echo "<div align='center' style='margin-top:5px;margin-bottom:5px;'>
".makePageNav($rowstart,$itemsperpage,$rows,3,"small","$PHP_SELF?forum_id=$forum_id&forum_cat=$forum_cat&thread_id=$thread_id&")."
</div>\n";

echo "<table cellspacing='0' cellpadding='0' border='0' width='100%' class='forum-border'>
<tr>
<td>
<table border='0' cellspacing='1' cellpadding='0' width='100%'>\n";

if ($rows != 0) {
	$result = dbquery("SELECT * FROM ".$fusion_prefix."posts INNER JOIN ".$fusion_prefix."users ON ".$fusion_prefix."posts.post_author=".$fusion_prefix."users.user_id WHERE thread_id='$thread_id' ORDER BY post_datestamp LIMIT $rowstart,$itemsperpage");
	$numrows = dbrows($result);
	$i = 0;
	while ($data = dbarray($result)) {
		$i++;
		$message = $data[post_message];
		if ($data[post_showsig] == "y") { $message = $message."\n\n<hr />".$data[user_sig]; }
		if ($data[post_smileys] == "y") { $message = parsesmileys($message); }
		$message = parseubb($message);
		$message = nl2br($message);
		//$message = wordwrap($message, 100, "\n", 1);
		if ($data[post_edittime] != "0") {
			if ($data[post_author] != $data[post_edituser]) {
				$result2 = dbquery("SELECT user_id,user_name FROM ".$fusion_prefix."users WHERE user_id='$data[post_edituser]'");
				$data2 = dbarray($result2);				
				$edituser = "<a href='../profile.php?lookup=$data2[user_id]'>$data2[user_name]</a>";
				$edittime = strftime($settings[forumdate], $data[post_edittime]+($settings[timeoffset]*3600));
			} else {
				$edituser = "<a href='../profile.php?lookup=$data[user_id]'>$data[user_name]</a>";
				$edittime = strftime($settings[forumdate], $data[post_edittime]+($settings[timeoffset]*3600));
			}
		}
		echo "<tr>
<td width='145' class='forum2'>".LAN_501."</td>
<td class='forum2'><a name='$data[post_id]'></a>".stripslashes($data[post_subject])."</td>
</tr>
<tr>
<td valign='top' rowspan='3' width='145' class='forum1'>
<a href='../profile.php?lookup=$data[user_id]'>$data[user_name]</a><br />
<span class='alt'>".getmodlevel($data[user_mod])."</span><br /><br />\n";
		if ($data[user_avatar] != "") {
			echo "<img src='".fusion_basedir."fusion_public/avatars/$data[user_avatar]'><br /><br />\n";
			$height = "170";
		} else {
			$height = "55";
		}
		echo "<span class='alt'>".LAN_502."</span> $data[user_posts]<br />
<span class='alt'>".LAN_503."</span> $data[user_location]<br />
<span class='alt'>".LAN_504."</span> ".strftime("%d.%m.%y", $data[user_joined]+($settings[timeoffset]*3600))."</td>
<td>
<table width='100%' cellspacing='0' cellpadding='0'>
<td class='forum1'>".LAN_505.strftime($settings[forumdate], $data[post_datestamp]+($settings[timeoffset]*3600))."</td>
<td align='right' class='forum1'>\n";
		if ($userdata != "") {
			if ($locked != "y") {
				if ($userdata[user_id] == $data[post_author] || Moderator()) {
					echo "<a href='post.php?action=edit&forum_id=$forum_id&forum_cat=$forum_cat&thread_id=$data[thread_id]&post_id=$data[post_id]'><img src='".fusion_themedir."forum/edit.gif' border='0'></a>\n";
				}
				echo "<a href='post.php?action=reply&forum_id=$forum_id&forum_cat=$forum_cat&thread_id=$data[thread_id]&post_id=$data[post_id]&quote=$data[post_id]'><img src='".fusion_themedir."forum/quote.gif' alt='quote' border='0'></a>\n";
			} else {
				if (Moderator()) {
					echo "<a href='post.php?action=edit&forum_id=$forum_id&forum_cat=$forum_cat&thread_id=$data[thread_id]&post_id=$data[post_id]'><img src='".fusion_themedir."forum/edit.gif' border='0'></a>";
				}
			}
		}
		echo "</td>
</tr>
</table>
</td>
</tr>
<tr>
<td height='$height' valign='top' class='forum1'>
$message";
		$result2 = dbquery("SELECT * FROM ".$fusion_prefix."forum_attachments WHERE post_id='$data[post_id]'");
		if (dbrows($result2) != 0) {
			$attach = dbarray($result2);
			if ($attach[attach_ext] == ".gif" || $attach[attach_ext] == ".jpg" || $attach[attach_ext] == ".png") {
				echo "<br /><br />
<span class='small'>$data[user_name]".LAN_506."</span><br /><br />
<img src='".fusion_basedir."fusion_public/attachments/".$attach[attach_name]."[".$attach[attach_id]."]".$attach[attach_ext]."'>";
			} else {
				echo "<br /><br />
<span class='small'>$data[user_name]".LAN_507."</span><br />
<a href='".fusion_basedir."fusion_public/attachments/".$attach[attach_name]."[".$attach[attach_id]."]".$attach[attach_ext]."'>
".$attach[attach_name]."[".$attach[attach_id]."]".$attach[attach_ext]."</a>";
			}
		}
		if ($data[post_edittime] != "0") {
			echo "<br />
<br />
<span class='small'>".LAN_508."$edituser".LAN_509."$edittime</span>";
		}
echo "</td>
</tr>
<td class='forum1'>\n";
		if ($data[user_icq]) {
			echo "<a href='http://web.icq.com/wwp?Uin=$data[user_icq]' target='_blank'><img src='".fusion_themedir."forum/icq.gif' alt='$data[user_icq]' border='0'></a> ";
		}
		if ($data[user_msn]) {
			echo "<a href='mailto:$data2[user_msn]'><img src='".fusion_themedir."forum/msn.gif' alt='$data[user_msn]' border='0'></a> ";
		}
		if ($data[user_yahoo]) {
			echo "<a href='http://uk.profiles.yahoo.com/$data[user_yahoo]' target='_blank'><img src='".fusion_themedir."forum/yahoo.gif' alt='$data[user_yahoo]' border='0'></a> ";
		}
		if ($data[user_web]) {
			if (!strstr($data[user_web], "http://")) { $urlprefix = "http://"; } else { $urlprefix = ""; }
			echo "<a href='".$urlprefix."$data[user_web]' target='_blank'><img src='".fusion_themedir."forum/web.gif' alt='$data[user_web]' border='0'></a> ";
		}
		echo "<a href='../sendmessage.php?user_id=$data[user_id]'><img src='".fusion_themedir."forum/pm.gif' border='0'></a>
</td>
</tr>\n";
	}
}

echo "</table>
</td>
</tr>
</table>\n";

echo "<div align='center' style='margin-top:5px;'>
".makePageNav($rowstart,$itemsperpage,$rows,3,"small","$PHP_SELF?forum_id=$forum_id&forum_cat=$forum_cat&thread_id=$thread_id&")."
</div>\n";

if (Member()) {
	echo "<div style='margin-top:5px;'>
<form name='modopts' method='post' action='options.php?forum_id=$forum_id&forum_cat=$forum_cat&thread_id=$thread_id'>
<table cellpadding='0' cellspacing='0' width='100%'>
<tr>\n";
	if (Moderator()) {
		echo "<td style='text-align:left;'>
".LAN_520."<br />
<select name='step' class='textbox'>
<option value='none'></option>
<option value='delete'>".LAN_521."</option>\n";
		if ($locked != "y") { 
			echo "<option value='lock'>".LAN_522."</option>\n";
		} else {
			echo "<option value='unlock'>".LAN_523."</option>\n";
		}
		if ($sticky != "y") {
			echo "<option value='sticky'>".LAN_524."</option>\n";
		} else {
			echo "<option value='nonsticky'>".LAN_525."</option>\n";
		}
		echo "<option value='move'>".LAN_526."</option>\n";
		echo "</select>
<input type='submit' name='go' value='".LAN_527."' class='button'>
</td>\n";
	}
	echo "<td style='text-align:right;vertical-align:bottom;'>\n";
	if ($locked != "y") {
		echo "<a href='post.php?action=reply&forum_id=$forum_id&forum_cat=$forum_cat&thread_id=$thread_id'><img src='".fusion_themedir."forum/reply.gif' border='0'></a>";
	}
	echo "&nbsp;<a href='post.php?action=newthread&forum_id=$forum_id&forum_cat=$forum_cat'><img src='".fusion_themedir."forum/newthread.gif' border='0'></a>
</td>
</tr>
</table>
</form>
</div>\n";
}

} else {
	echo "<center><br />\n".LAN_510."<br /><br />
<a href='index.php'>".LAN_511."</a>\n<br /><br /></center>\n";
}

closetable();

echo "</td>\n";
require "../footer.php";
?>