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
require fusion_langdir."members-profile.php";
require "side_left.php";

$result = dbquery("SELECT * FROM ".$fusion_prefix."users WHERE user_id='$lookup'");
$data = dbarray($result);
opentable(LAN_410.$data['user_name']);
echo "<table align='center' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td align='center' width='110'>";
if ($data['user_avatar']) {
	echo "<img src='fusion_public/avatars/".$data['user_avatar']."'>";
} else {
	echo LAN_411;
}
echo "</td>
<td width='100'>
".LAN_412."<br>
".LAN_413."<br>
".LAN_414."<br>
".LAN_415."<br>
".LAN_416."<br>
".LAN_417."<br>
".LAN_418."<br>
".LAN_419."<br>
".LAN_420."<br>
</td>
<td>\n";
if ($data['user_hide_email'] != "1" || Admin()) {
	echo "<a href='mailto:".$data['user_email']."'>".$data['user_email']."</a><br>\n";
} else {
	echo LAN_430."<br>\n";
}
if ($data['user_location']) {
	echo $data['user_location']."<br>\n";
} else {
	echo LAN_431."<br>\n";
}
if ($data['user_birthdate'] != "0000-00-00") {
	$months = explode("|", LAN_MONTHS);
	$user_birthdate = explode("-", $data['user_birthdate']);
	echo $months[number_format($user_birthdate['1'])]." ".number_format($user_birthdate['2'])." ".$user_birthdate['0']."<br>\n";
} else {
	echo LAN_431."<br>\n";
}
if ($data['user_icq']) {
	echo "<a href='http://web.icq.com/wwp?Uin=".$data['user_icq']."' target='_blank'>".$data['user_icq']."</a><br>\n";
} else {
	echo LAN_431."<br>\n";
}
if ($data['user_msn']) {
	echo "<a href='mailto:".$data['user_msn']."'>".$data['user_msn']."</a><br>\n";
} else {
	echo LAN_431."<br>\n";
}
if ($data['user_yahoo']) {
	echo "<a href='http://uk.profiles.yahoo.com/".$data['user_yahoo']."' target='_blank'>".$data['user_yahoo']."</a><br>\n";
} else {
	echo LAN_431."<br>\n";
}
if ($data['user_web']) {
	if (!strstr($data['user_web'], "http://")) { $urlprefix = "http://"; }
	echo "<a href='".$urlprefix.$data['user_web']."' target='_blank'>".$data['user_web']."</a><br>\n";
} else {
	echo LAN_431."<br>\n";
}
echo strftime("%d.%m.%y", $data['user_joined']+($settings['timeoffset']*3600))."<br>\n";
if ($data['user_lastvisit'] != 0) {
	echo strftime("%d.%m.%y", $data['user_lastvisit']+($settings['timeoffset']*3600))."\n";
} else {
	echo LAN_432."\n";
}
echo "</td>
</tr>
<tr>
<td align='center' colspan='3'>\n";
if ($data['user_id'] != $userdata['user_id']) {
	echo "<br><a href='sendmessage.php?user_id=".$data['user_id']."'>".LAN_440."</a>\n";
}
echo "</td>
</tr>
</table>\n";
closetable();
echo "</td>\n";

require "side_right.php";
require "footer.php";
?>