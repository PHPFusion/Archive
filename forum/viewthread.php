<?
/*
-------------------------------------------------------
	PHP Fusion X3
	-----------------------------------------------
	© Nick Jones 2002-2004
	http://www.digitaldominion.co.uk
	nick@digitaldominion.co.uk
	-----------------------------------------------
	Released under the terms and conditions of the
	GNU General Public License (http://gnu.org).
-------------------------------------------------------
*/
require "header.php";
require fusion_basedir."subheader.php";
require fusion_langdir."forum/forum_main.php";

if (empty($forum_cat) || ($forum_cat == "")) {
	$result = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_id='$forum_id'");
	$data = dbarray($result);
	$forum_cat = $data[forum_cat];
}

// Get the Forum Category name
$result = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_id='$forum_cat'");
$data = dbarray($result);
$caption = stripslashes($data[forum_name]);

// Get the Forum name
$result = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_id='$forum_id'");
if (dbrows($result) != 0) {
	$data = dbarray($result);
	$caption .= " | <a href=\"viewforum.php?forum_id=$data[forum_id]&forum_cat=$data[forum_cat]\">".stripslashes($data[forum_name])."</a>";
}

// Update the View count in the parent Thread
$result = dbquery("UPDATE ".$fusion_prefix."threads SET thread_views=thread_views+1 WHERE thread_id='$thread_id'");

// Get the Thread Subject
$result = dbquery("SELECT * FROM ".$fusion_prefix."threads WHERE thread_id='$thread_id'");
if (dbrows($result) != 0) {
	$data = dbarray($result);
	$locked = $data[thread_locked];
}

require "navigation.php";
echo "</td>
<td valign=\"top\" class=\"bodybg\">\n";

opentable(LAN_300);
if ($userdata[user_name] != "") {
	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
<tr>
<td class=\"smallalt\">
<a href=\"index.php\">$settings[sitename]</a> |
$caption</td>
<td align=\"right\">\n";
	if ($locked != "y") {
		echo "<a href=\"post.php?action=reply&forum_id=$forum_id&forum_cat=$forum_cat&thread_id=$thread_id\"><img src=\"images/reply.gif\" border=\"0\"></a>";
	}
	echo "&nbsp;<a href=\"post.php?action=newthread&forum_id=$forum_id&forum_cat=$forum_cat\"><img src=\"images/newthread.gif\" border=\"0\"></a></td></tr>
</table>\n";
}

$postsperpage = 20;
$result = dbquery("SELECT * FROM ".$fusion_prefix."posts WHERE thread_id='$thread_id'");
$rows = dbrows($result);
if (!$rowstart) $rowstart = 0;
if ($rows != 0) {
	$totalpages = ceil($rows / $postsperpage);	
	$currentpage = $rowstart / $postsperpage + 1;
	if ($rowstart >= $postsperpage) {
		$start = $rowstart - $postsperpage;
		$prev = "<a href=\"$PHP_SELF?rowstart=$start&forum_id=$forum_id&forum_cat=$forum_cat&thread_id=$thread_id\" class=\"white\">".LAN_50."</a>";
	}
	if ($rowstart + $postsperpage < $rows) {
		$start = $rowstart + $postsperpage;
		$next = "<a href=\"$PHP_SELF?rowstart=$start&forum_id=$forum_id&forum_cat=$forum_cat&thread_id=$thread_id\" class=\"white\">".LAN_51."</a>";
	}
	if ($prev != "" || $next != "") {
		$current = LAN_52.$currentpage.LAN_53.$totalpages;
		prevnextbar($prev,$current,$next);
	}
}

echo "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\" class=\"forum-border\">
<tr>
<td>
<table border=\"0\" cellspacing=\"1\" cellpadding=\"0\" width=\"100%\">\n";

if ($rows != 0) {
	$result = dbquery("SELECT * FROM ".$fusion_prefix."posts WHERE thread_id='$thread_id' ORDER BY post_datestamp LIMIT $rowstart,$postsperpage");
	$numrows = dbrows($result);
	$i = 0;
	while ($data = dbarray($result)) {
		$post_author = explode(".", $data[post_author]);
		$result2 = dbquery("SELECT * FROM ".$fusion_prefix."users WHERE user_id='$post_author[0]' AND user_name='$post_author[1]'");
		$data2 = dbarray($result2);
		$i++;
		$message = stripslashes($data[post_message]);
		if ($data[post_showsig] == "y") $message = $message."\n\n".$data2[user_sig];
		$message = parsesmileys($message);
		$message = parseubb($message);
		$message = nl2br($message);
		if ($data[post_edittime] != "0") {
			if ($data[post_author] != $data[post_edituser]) {
				$post_editor = explode(".", $data[post_edituser]);
				$edituser = "<a href=\"../profile.php?lookup=$post_editor[0]\">$post_editor[1]</a>";
				$edittime = strftime($settings[forumdate], $data[post_edittime]+($settings[timeoffset]*3600));
			} else {
				$edituser = "<a href=\"../profile.php?lookup=$post_author[0]\">$post_author[1]</a>";
				$edittime = strftime($settings[forumdate], $data[post_edittime]+($settings[timeoffset]*3600));
			}
		}
		echo "<tr>
<td width=\"145\" class=\"forum2\">".LAN_301."</td>
<td class=\"forum2\">".stripslashes($data[post_subject])."</td>
</tr>
<tr>
<td valign=\"top\" rowspan=\"3\" width=\"145\" class=\"forum1\">
<a href=\"../profile.php?lookup=$data2[user_id]\">$data2[user_name]</a><br>
<span class=\"alt\">".getmodlevel($data2[user_mod])."</span><br><br>\n";
		if ($data2[user_avatar] != "") {
			echo "<img src=\"".fusion_basedir."avatars/$data2[user_avatar]\"><br><br>\n";
			$height = "170";
		} else {
			$height = "55";
		}
		echo "<span class=\"alt\">".LAN_302."</span> $data2[user_posts]<br>
<span class=\"alt\">".LAN_303."</span> $data2[user_location]<br>
<span class=\"alt\">".LAN_304."</span> ".strftime("%d.%m.%y", $data2[user_joined]+($settings[timeoffset]*3600))."</td>
<td>
<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
<td class=\"forum1\">".LAN_305.strftime($settings[forumdate], $data[post_datestamp]+($settings[timeoffset]*3600))."</td>
<td align=\"right\" class=\"forum1\">\n";
		if ($userdata != "") {
			if ("$userdata[user_id].$userdata[user_name]" == $data[post_author] || $userdata[user_mod] > "0") {
				if ($locked != "y") {
					echo "<a href=\"post.php?action=edit&forum_id=$forum_id&forum_cat=$forum_cat&thread_id=$data[thread_id]&post_id=$data[post_id]\"><img src=\"images/edit.gif\" border=\"0\"></a>
<a href=\"post.php?action=reply&forum_id=$forum_id&forum_cat=$forum_cat&thread_id=$data[thread_id]&post_id=$data[post_id]&quote=$data[post_id]\"><img src=\"images/quote.gif\" alt=\"quote\" border=\"0\"></a>";
				} else {
					if ($userdata[user_mod] > "0") {
						echo "<a href=\"post.php?action=edit&forum_id=$forum_id&forum_cat=$forum_cat&thread_id=$data[thread_id]&post_id=$data[post_id]\"><img src=\"images/edit.gif\" border=\"0\"></a>";
					}
				}
			}
		}
		echo "</td>
</tr>
</table>
</td>
</tr>
<tr>
<td height=\"$height\" valign=\"top\" class=\"forum1\">
$message";
		$result2 = dbquery("SELECT * FROM ".$fusion_prefix."forum_attachments WHERE post_id='$data[post_id]'");
		if (dbrows($result2) != 0) {
			$attach = dbarray($result2);
			if ($attach[attach_ext] == ".gif" || $attach[attach_ext] == ".jpg" || $attach[attach_ext] == ".png") {
				echo "<br><br>
<span class=\"small\">$data2[user_name]".LAN_306."</span><br><br>
<img src=\"".fusion_basedir."attachments/".$attach[attach_name]."[".$attach[attach_id]."]".$attach[attach_ext]."\">";
			} else {
				echo "<br><br>
<span class=\"small\">$data2[user_name]".LAN_307."</span><br>
<a href=\"".fusion_basedir."attachments/".$attach[attach_name]."[".$attach[attach_id]."]".$attach[attach_ext]."\">
".$attach[attach_name]."[".$attach[attach_id]."]".$attach[attach_ext]."</a>";
			}
		}
		if ($data[post_edittime] != "0") {
			echo "<br>
<br>
<span class=\"small\">".LAN_308."$edituser".LAN_309."$edittime</span>";
		}
echo "</td>
</tr>
<td class=\"forum1\">\n";
		if ($data2[user_icq]) {
			echo "<a href=\"http://web.icq.com/wwp?Uin=$data2[user_icq]\" target=\"_blank\"><img src=\"images/icq.gif\" alt=\"$data2[user_icq]\" border=\"0\"></a> ";
		}
		if ($data2[user_msn]) {
			echo "<a href=\"mailto:$data2[user_msn]\"><img src=\"images/msn.gif\" alt=\"$data2[user_msn]\" border=\"0\"></a> ";
		}
		if ($data2[user_yahoo]) {
			echo "<a href=\"http://uk.profiles.yahoo.com/$data2[user_yahoo]\"><img src=\"images/yahoo.gif\" alt=\"$data2[user_yahoo]\" border=\"0\"></a> ";
		}
		if ($data2[user_web]) {
			echo "<a href=\"$data2[user_web]\"><img src=\"images/web.gif\" alt=\"$data2[user_web]\" border=\"0\"></a> ";
		}
		echo "<a href=\"../sendmessage.php?user_id=$data2[user_id]\"><img src=\"images/pm.gif\" border=\"0\"></a>
</td>
</tr>\n";
	}
}

echo "</table>
</td>
</tr>
</table>\n";
if ($rows != 0) {
	if ($prev != "" || $next != "") {
		$current = LAN_52.$currentpage.LAN_53.$totalpages;
		prevnextbar($prev,$current,$next);
	}
}
if ($userdata[user_name] != "") {
	tablebreak();
	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
<tr><td align=\"right\">\n";
	if ($locked != "y") {
		echo "<a href=\"post.php?action=reply&forum_id=$forum_id&forum_cat=$forum_cat&thread_id=$thread_id\"><img src=\"images/reply.gif\" border=\"0\"></a>";
	}
	echo "&nbsp;<a href=\"post.php?action=newthread&forum_id=$forum_id&forum_cat=$forum_cat\"><img src=\"images/newthread.gif\" border=\"0\"></a></td></tr>\n";
echo "</table>";
}
closetable();

echo "</td>
</tr>
</table>\n";

require "../footer.php";
?>