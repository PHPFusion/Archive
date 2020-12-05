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
if ($settings[guestposts] == "1") {
	if ($userdata[user_name]!="") {
		$shoutname = $userdata[user_id].".".$userdata[user_name];
	} else {
		$shoutname = stripinput($shoutname);
		if ($shoutname!="") { $shoutname = "0.".$shoutname; }
	}
} else {
	$shoutname = $userdata[user_id].".".$userdata[user_name];
}
$shout_message = stripinput($shout_message);
$shout_message = preg_replace("/^(.{255}).*$/", "$1", $shout_message);
$shout_message = preg_replace("/([^\s]{20})/", "$1<br>", $shout_message);
$shout_message = trim($shout_message);
if ($shoutname != "" && $shout_message != "") {
	$result = dbquery("INSERT INTO ".$fusion_prefix."shoutbox VALUES ('', '$shoutname', '$shout_message', '".time()."', '$user_ip')");
}
?>