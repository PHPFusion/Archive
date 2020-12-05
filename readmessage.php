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
@require "fusion_config.php";
require "header.php";
require "subheader.php";
require fusion_langdir."messages.php";
require "side_left.php";

if (Member()) {
	$result = dbquery("SELECT * FROM ".$fusion_prefix."messages INNER JOIN ".$fusion_prefix."users ON ".$fusion_prefix."messages.message_from=".$fusion_prefix."users.user_id WHERE message_to='$userdata[user_id]' AND message_id='$message_id'");
	if (dbrows($result) != 0) {
		$data = dbarray($result);
		$subject = $data[message_subject];
		$message = nl2br($data[message_message]);
		if ($data[message_smileys] == "y") $message = parsesmileys($message);
		$message = parseubb($message);
		$recieved = strftime($settings[shortdate], $data[message_datestamp]+($settings[timeoffset]*3600));
		if ($data[message_read] == 0) {
			$result = dbquery("UPDATE ".$fusion_prefix."messages SET message_read='1' WHERE message_id='$message_id'");
		}
		opentable(LAN_510);
		echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" class=\"body\">
<tr>
<td><span class=\"alt\">".LAN_490."</span> $userdata[user_name]<br>
<span class=\"alt\">".LAN_491."</span> $data[user_name]<br>
<span class=\"alt\">".LAN_492."</span> $recieved<br>
<span class=\"alt\">".LAN_493."</span> $subject<br><br>
<span class=\"alt\">".LAN_494."</span><br>
$message</td>
</tr>
<tr>
<td align=\"center\" colspan=\"2\"><br>
<a href=\"sendmessage.php?user_id=$data[message_from]&reply_id=$data[message_id]\">".LAN_520."</a><br><br>
<a href=\"messages.php\">".LAN_521."</a></td>
</tr>
</table>\n";
		closetable();
	}
} else {
	opentable(LAN_510);
	echo LAN_413."\n";
	closetable();
}

require "side_right.php";
require "footer.php";
?>