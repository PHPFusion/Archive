<?
require "../includes/config.php";
require "../includes/classes.php";

// Set the servertime, there are 3600 seconds in one hour, adjust the difference
// as required, e.g. +2 hours is time() + 7200, -2 hours is time() - 7200.
$servertime = time() + 3600;

// get the user's up address and store it in a variable.
$userip = getenv(REMOTE_ADDR);

// Open a connection to the database, settings for which are retrieved from includes/config.php file
dbconnect($dbhost, $dbusername, $dbpassword, $dbname);

// Set a cookie to prevent multiple counts on the unique visit counter
if (!isset($_COOKIE['visited'])) {
	$result=dbquery("UPDATE settings SET counter=counter+1");
	setcookie("visited", "yes", time() + 31536000, "/", "", "0");
}

// if the save settings form has been processed, update the settings table
if (isset($_POST['savesettings'])) {
	$result = dbquery("UPDATE settings SET sitename='$sitename', siteurl='$siteurl', sitebanner='$sitebanner',
	siteemail='$siteemail',	siteusername='$username', siteintro='$intro', description='$description',
	keywords='$keywords', footer='$footer', theme='$theme', visitorshoutbox='$visitorshoutbox',
	visitorcomments='$visitorcomments'");
}

// get the settings from the database and store them in an array
$settings = dbarray(dbquery("SELECT * FROM settings"));

// check the Post variable to see if the login button has been pressed
if (isset($_POST['login'])) {
	$result = dbquery("SELECT * FROM users WHERE username='$username' and password=md5('$password')");
	if (dbrows($result) != 0) {
		$data = dbarray($result);
		$cookievar = $data[userid];
		setcookie($cookiename, $cookievar, time() + 31536000, "/", "", "0");
		header("Location: index.php");
	}
}

// check the logout variable to see if the user has logged out
if ($logout == "yes") {
	setcookie("userid", "Null", time() - 7200, "/", "", "0");
	header ("Location: index.php");
}

// if the value of $userid is not Null, the cookie is set and the user is logged in
// set the variables from the matching ID in the database
if (isset($_COOKIE['userid'])) {
	$result = dbquery("SELECT * FROM users WHERE userid='$userid'");
	if (dbrows($result) != 0) {
		$userdata = dbarray($result);
	} else {
		setcookie("userid", "Null", time() - 7200, "/", "", "0");
		header ("Location: index.php");
	}
}

// if the user has saved any changes in their user panel, call the required function
if (isset($_POST['UpdateProfile'])) {
	require "includes/updateuser.php";
}

// check if the person online is a visitor or a member, update or insert
// the matching information in the database
if ($userdata[username] != "") { $name = $userdata[username]; } else { $name = "visitor"; }
if ($userdata[userid] != "") { $onlineid = $userdata[userid]; } else { $onlineid = "0"; }
$result = dbquery("SELECT * FROM online WHERE username='$name' AND userip='$userip'");
if (dbrows($result) != 0) {
	$result = dbquery("UPDATE online SET lastactive='$servertime' WHERE username='$name' AND userip='$userip'");
} else {
	if ($name != "visitor") {
		$result = dbquery("SELECT * FROM online WHERE username='$name'");
		if (dbrows($result) != 0) {
			$result = dbquery("UPDATE online SET userip='$userip', lastactive='$servertime' WHERE username='$name'");
		} else {
			$result = dbquery("INSERT INTO online VALUES('', '$name', '$onlineid', '$userip', '$servertime')");
		}
	} else {
		$result = dbquery("INSERT INTO online VALUES('', '$name', '$onlineid', '$userip', '$servertime')");
	}
}

// if a visitor logs in, or a member logs out, delete the relevant entry from the online table
if (isset($_POST['login'])) {
	$result = dbquery("DELETE FROM online WHERE username='visitor' AND userip='$userip'");
} else if (isset($logout)) {
	$result = dbquery("DELETE FROM online WHERE userip='$userip'");
}

// delete any online entries that have been inactive for 5 or more minutes (300 seconds+)
$deletetime = $servertime - 300;
$result = dbquery("DELETE FROM online WHERE lastactive < $deletetime");
?>