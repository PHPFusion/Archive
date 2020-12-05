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
if ($settings['forumpanel'] == "1") {
	$result = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_access>'".UserLevel()."' ORDER BY forum_id");
	if (dbrows($result) != 0) {
		$exc_list = "WHERE ";
		for ($i=1;$data=dbarray($result);$i++) {
			$exc_list .= "forum_id!='".$data['forum_id']."' ";
			if ($i != dbrows($result)) { $exc_list .= "AND "; }
		}
	}		
	$result = dbquery("SELECT * FROM ".$fusion_prefix."threads INNER JOIN ".$fusion_prefix."users ON ".$fusion_prefix."threads.thread_lastuser=".$fusion_prefix."users.user_id ".$exc_list."ORDER BY thread_lastpost DESC LIMIT 0,".$settings['numofthreads']);
	if (dbrows($result) != 0) {
		opentable(LAN_31);
		echo "<table width='100%' cellpadding='0' cellspacing='0'";
		if ($theme_width == "100%") { echo " style='text-indent:2px'"; }
		echo ">\n<tr>\n";
		if ($theme_width == "100%") {
			echo "<td><b>".LAN_32."</b></td>\n";
		}
		echo "<td><b>".LAN_33."</b></td>
<td align='center'><b>".LAN_34."</b></td>
<td align='center'><b>".LAN_35."</b></td>
<td align='right'><b>".LAN_36."</b></td>
</tr>\n";
		while ($data = dbarray($result)) {
			$result2 = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_id='$data[forum_id]'");
			$data2 = dbarray($result2);
			echo "<tr>\n";
			if ($theme_width == "100%") {
				echo "<td>".$data2['forum_name']."</td>
<td><a href='fusion_forum/viewthread.php?forum_id=".$data2['forum_id']."&thread_id=".$data['thread_id']."'>".trimlink($data['thread_subject'], 30)."</a></td>\n";
			} else {
				echo "<td><a href='fusion_forum/viewthread.php?forum_id=".$data2['forum_id']."&thread_id=".$data['thread_id']."'>".trimlink($data['thread_subject'], 30)."</a><br>
<span class='small'>[".stripslashes($data2[forum_name])."]</span></td>\n";
			}
			echo "<td align='center'>".$data['thread_views']."</td>
<td align='center'>".$data['thread_replies']."</td>
<td align='right'><a href='profile.php?lookup=".$data['thread_lastuser']."'>".$data['user_name']."</a><br>
".strftime($settings['forumdate'], $data['thread_lastpost']+($settings['timeoffset']*3600))."</td>
</tr>\n";
		}
		echo "</table>\n";
		closetable();
		tablebreak();
	}
}
?>