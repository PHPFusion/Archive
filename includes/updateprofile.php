<?
$username = stripinput($username);
$password1 = stripinput($password1);
$password2 = stripinput($password2);
$email = stripinput($email);
$icq = stripinput($icq);
$msn = stripinput($msn);
$yahoo = stripinput($yahoo);
$signature = stripinput($signature);
if ($username != "") {
	if ($username != $userdata[username]) {
		$result = dbquery("SELECT * FROM users WHERE username='$username'");
		if (dbrows($result) != 0) {
			$error = "username in use.<br>";
		}
	}
} else {
	$error .= "you did not specify a username.<br>";
}
if ($email == "") {
	$error .= "you did not specify your email address.<br>";
}
if ($error == "") {
	if ($username != $userdata[username]) {
		$result = dbquery("UPDATE forums SET lastuser=md5('$username') WHERE lastuser='$userdata[userid]'");
		$result = dbquery("UPDATE threads SET author=md5('$username') WHERE author='$userdata[userid]'");
		$result = dbquery("UPDATE threads SET lastuser=md5('$username') WHERE lastuser='$userdata[userid]'");
		$result = dbquery("UPDATE posts SET author=md5('$username') WHERE author='$userdata[userid]'");
		$result = dbquery("UPDATE posts SET edituser=md5('$username') WHERE edituser='$userdata[userid]'");
		$result = dbquery("UPDATE votes SET voteid=md5('$username') WHERE voteid='$userdata[userid]'");
		$result = dbquery("UPDATE messages SET touser=md5('$username') WHERE touser='$userdata[userid]'");
		$result = dbquery("UPDATE messages SET fromuser=md5('$username') WHERE fromuser='$userdata[userid]'");
		$result = dbquery("UPDATE users SET userid=md5('$username'), username='$username', email='$email', location='$location', icq='$icq', msn='$msn', yahoo='$yahoo', web='$web', sig='$signature' WHERE userid='$userdata[userid]'");
		$result = dbquery("SELECT * FROM users WHERE userid=md5('$username')");
		if (dbrows($result) != 0) {
			$userdata = dbarray($result);
		}
		$cookievar = $userdata[userid];
		setcookie($cookiename, $cookievar, time() + 31536000, "/", "", "0");
	} else {
		$result = dbquery("UPDATE users SET email='$email', location='$location', icq='$icq', msn='$msn', yahoo='$yahoo', web='$web', sig='$signature' WHERE userid='$userdata[userid]'");
		$result = dbquery("SELECT * FROM users WHERE userid='$userdata[userid]'");
		if (dbrows($result) != 0) {
			$userdata = dbarray($result);
		}
	}
}
?>