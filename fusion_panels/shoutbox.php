<?
@openside(LAN_100);
if (isset($_POST['post_shout'])) {
	if (Member()) {
		$shout_name = $userdata['user_id'];
	} elseif ($settings['guestposts'] == "1") {
		$shout_name = stripinput($_POST['shout_name']);
		if (is_numeric($shout_name)) $shout_name="";
	}
	$shout_message = str_replace("\n", " ", $_POST['shout_message']);
	$shout_message = preg_replace("/^(.{255}).*$/", "$1", $shout_message);
	$shout_message = preg_replace("/([^\s]{25})/", "$1\n", $shout_message);
	$shout_message = stripinput($shout_message);
	$shout_message = str_replace("\n", "<br>", $shout_message);
	if ($shout_name != "" && $shout_message != "") {
		$result = dbquery("INSERT INTO ".$fusion_prefix."shoutbox VALUES('', '$shout_name', '$shout_message', '".time()."', '$user_ip')");
	}
	header("Location: ".$PHP_SELF);
}
if (Member() || $settings['guestposts'] == "1") {
	echo "<form name='chatform' method='post' action='$PHP_SELF' OnSubmit=\"document.forms['chatform'].post_shout.enabled = false;\">\n";
	if (Guest()) {
		echo LAN_101."<br>
<input type='text' name='shout_name' value='".$userdata['user_name']."' class='textbox' maxlength='32' style='width:100%;'><br>
".LAN_102."<br>\n";
	}
	echo "<textarea name='shout_message' rows='3' class='textbox' style='width:100%;'></textarea><br>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr><td>
<input type='submit' name='post_shout' value='".LAN_103."' class='button'></td>
<td align='right'><a href='shoutboxhelp.php' class='slink'>".LAN_104."</a></td></tr>
</table>
</form><br>\n";
} else {
	echo "<center>".LAN_105."</center><br>\n";
}
$result = dbquery("SELECT count(shout_id) FROM ".$fusion_prefix."shoutbox");
$numrows = dbresult($result, 0);
$result = dbquery(
	"SELECT * FROM ".$fusion_prefix."shoutbox LEFT JOIN ".$fusion_prefix."users
	ON ".$fusion_prefix."shoutbox.shout_name=".$fusion_prefix."users.user_id
	ORDER BY shout_datestamp DESC LIMIT 0,".$settings['numofshouts']
);
if (dbrows($result) != 0) {
	while ($data = dbarray($result)) {
		echo "<div class='shoutboxname'>";
		if ($data[user_name]) {
			echo "<a href='profile.php?lookup=".$data['shout_name']."' class='slink'>".$data['user_name']."</a><br>\n";
		} else {
			echo "<span class='shoutboxname'>".$data['shout_name']."</span><br>\n";
		}
		echo "</div>
<div align='left' class='shoutbox'>".parsesmileys($data['shout_message'])."</div>
<div class='shoutboxdate'>".strftime($settings['shortdate'], $data['shout_datestamp']+($settings['timeoffset']*3600))."</div>\n";
	}
	if ($numrows > $settings['numofshouts']) {
		echo "<center><br>
[<a href='shoutbox_archive.php' class='slink'>".LAN_106."</a>]</center>\n";
	}
} else {
	echo "<div align='left'>".LAN_107."</div>\n";
}
@closeside();
?>