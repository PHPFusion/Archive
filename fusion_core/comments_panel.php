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
if (eregi("comments_panel.php", $_SERVER['PHP_SELF'])) die();

tablebreak();
opentable(LAN_900);
$result = dbquery(
	"SELECT * FROM ".$fusion_prefix."comments LEFT JOIN ".$fusion_prefix."users
	ON ".$fusion_prefix."comments.comment_name=".$fusion_prefix."users.user_id
	WHERE comment_item_id='$comment_item_id' AND comment_type='$comment_type'
	ORDER BY comment_datestamp ASC"
);
if (dbrows($result) != 0) {
	$i = 1;
	while ($data = dbarray($result)) {
		if ($data[user_name]) {
			echo "<a href='profile.php?lookup=$data[comment_name]'>$data[user_name]</a><br>\n";
		} else {
			echo "<span class='shoutboxname'>".$data[comment_name]."</span><br>\n";
		}
		echo parsesmileys(parseubb($data[comment_message]))."<br>
<span class='shoutboxdate'>".strftime($settings[longdate], $data[comment_datestamp]+($settings[timeoffset]*3600))."</span>\n";
		if ($i != dbrows($result)) { echo "<br><br>\n"; } else { echo "\n"; }
		$i++;
	}
} else {
	echo LAN_901."\n";
}
closetable();
tablebreak();
opentable(LAN_902);
if (Member() || $settings[guestposts] == "1") {
	echo "<form name='postcomment' method='post' action='$comment_link'>
<table align='center' cellspacing='0' cellpadding='0' class='tbl'>\n";
	if (Guest()) {
		echo "<tr>
<td>".LAN_903."</td>
</tr>
<tr>
<td><input type='text' name='comment_name' maxlength='50' class='textbox' style='width:100%;'></td>
</tr>\n";
	}
	echo "<tr>
<td>".LAN_904."</td>
</tr>
<tr>
<td align='center'><textarea name='comment_message' rows='6' class='textbox' style='width:400px' onselect='updatePos(this);' onclick='updatePos(this);' onkeyup='updatePos(this);' onchange='updatePos(this);' ondblclick='updatePos(this);'></textarea><br>
<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onClick=\"AddText('b');\">
<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onClick=\"AddText('i');\">
<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onClick=\"AddText('u');\">
<input type='button' value='url' class='button' style='width:30px;' onClick=\"AddText('url');\">
<input type='button' value='mail' class='button' style='width:35px;' onClick=\"AddText('mail');\">
<input type='button' value='img' class='button' style='width:30px;' onClick=\"AddText('img');\">
<input type='button' value='center' class='button' style='width:45px;' onClick=\"AddText('center');\">
<input type='button' value='small' class='button' style='width:40px;' onClick=\"AddText('small');\">
<br><br>\n";
	displaysmileys();
	echo "</tr>
<tr>
<td align='center'><input type='submit' name='post_comment' value='".LAN_902."' class='button'></td>
</tr>
</table>
</form>
<script language='JavaScript'>
var editBody = document.postcomment.comment_message;
function insertText(theText) {
	if (editBody.createTextRange && editBody.curPos) {
		editBody.curPos.text = theText;
	} else {
		editBody.value += theText;
	}
	editBody.focus();
}
function AddText(wrap) {
	if (editBody.curPos) {
		insertText(\"[\" + wrap + \"]\" + editBody.curPos.text + \"[/\" + wrap + \"]\");
	} else {
		insertText(\"[\" + wrap + \"][/\" + wrap + \"]\");
	}
}
function updatePos(obj) {
	if (obj.createTextRange) {
		obj.curPos = document.selection.createRange().duplicate();
	}
}
</script>\n";
} else {
	echo LAN_905."\n";
}
closetable();
?>