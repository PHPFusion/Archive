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
if (!defined("IN_FUSION")) { header("Location:../index.php"); exit; }
include FUSION_LANGUAGES.FUSION_LAN."comments.php";

function showcomments($ctype,$cdb,$ccol,$cid,$clink) {

	global $fusion_prefix,$settings,$userdata;
	
	if (isset($_POST['post_comment'])) {
		if (dbrows(dbquery("SELECT $ccol FROM ".$fusion_prefix."$cdb WHERE $ccol='$cid'"))==0) {
			header("Location:".FUSION_BASE."index.php");
			exit;
		}
		if (iMEMBER) {
			$comment_name = $userdata['user_id'];
		} elseif ($settings['guestposts'] == "1") {
			$comment_name = stripinput($_POST['comment_name']);
			if (is_numeric($comment_name)) $comment_name="";
		}
		$comment_message = stripinput(censorwords($_POST['comment_message']));
		if ($comment_name != "" && $comment_message != "") {
			$result = dbquery("INSERT INTO ".$fusion_prefix."comments VALUES('', '$cid', '$ctype', '$comment_name', '$comment_message', '".time()."', '".FUSION_IP."')");
		}
		header("Location:".$clink);
	}

	tablebreak();
	opentable(COMMENT_100);
	$result = dbquery(
		"SELECT tcm.*,user_name FROM ".$fusion_prefix."comments tcm
		LEFT JOIN ".$fusion_prefix."users tcu ON tcm.comment_name=tcu.user_id
		WHERE comment_item_id='$cid' AND comment_type='$ctype'
		ORDER BY comment_datestamp ASC"
	);
	if (dbrows($result) != 0) {
		$i = 0;
		echo "<table width='100%' cellpadding='0' cellspacing='1' class='tbl-border'>\n";
		while ($data = dbarray($result)) {
			echo "<tr>\n<td class='".($i% 2==0?"tbl1":"tbl2")."'><span class='comment-name'>\n";
			if ($data['user_name']) {
				echo "<a href='".FUSION_BASE."profile.php?lookup=".$data['comment_name']."'>$data[user_name]</a>";
			} else {
				echo $data['comment_name'];
			}
			echo "</span>
<span class='small'>".LAN_41.showdate("longdate", $data['comment_datestamp'])."</span><br>
".parsesmileys(parseubb($data['comment_message']))."</td>\n</tr>\n";
			$i++;
		}
		echo "</table>\n";
	} else {
		echo COMMENT_101."\n";
	}
	closetable();
	tablebreak();
	opentable(COMMENT_102);
	if (iMEMBER || $settings['guestposts'] == "1") {
		echo "<form name='inputform' method='post' action='$clink'>
<table align='center' cellspacing='0' cellpadding='0' class='tbl'>\n";
		if (iGUEST) {
			echo "<tr>
<td>".COMMENT_103."</td>
</tr>
<tr>
<td><input type='text' name='comment_name' maxlength='50' class='textbox' style='width:100%;'></td>
</tr>\n";
		}
		echo "<tr>
<td align='center'><textarea name='comment_message' rows='6' class='textbox' style='width:400px'></textarea><br>
<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onClick=\"addText('comment_message', '[b]', '[/b]');\">
<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onClick=\"addText('comment_message', '[i]', '[/i]');\">
<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onClick=\"addText('comment_message', '[u]', '[/u]');\">
<input type='button' value='url' class='button' style='width:30px;' onClick=\"addText('comment_message', '[url]', '[/url]');\">
<input type='button' value='mail' class='button' style='width:35px;' onClick=\"addText('comment_message', '[mail]', '[/mail]');\">
<input type='button' value='img' class='button' style='width:30px;' onClick=\"addText('comment_message', '[img]', '[/img]');\">
<input type='button' value='center' class='button' style='width:45px;' onClick=\"addText('comment_message', '[center]', '[/center]');\">
<input type='button' value='small' class='button' style='width:40px;' onClick=\"addText('comment_message', '[small]', '[/small]');\">
<br><br>
".displaysmileys("comment_message")."
</tr>
<tr>
<td align='center'><input type='submit' name='post_comment' value='".COMMENT_102."' class='button'></td>
</tr>
</table>
</form>\n";
	} else {
		echo COMMENT_105."\n";
	}
	closetable();
}
?>