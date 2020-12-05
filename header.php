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
require "includes/config.php";
require "includes/classes.php";

// get the user's up address and store it in a variable.
$user_ip = getenv(REMOTE_ADDR);

// Open a connection to the database, settings for which are retrieved from includes/config.php file
dbconnect($dbhost, $dbusername, $dbpassword, $dbname);

// Get the settings from the database and store them in an array
$settings = dbarray(dbquery("SELECT * FROM ".$fusion_prefix."settings"));

// Define the language and theme paths
define("fusion_langdir", fusion_basedir."language/".$settings[language]."/");
define("fusion_themedir", fusion_basedir."themes/".$settings[theme]."/");

// Check if $download_id is set.
if (isset($download_id)) {
	$result = dbquery("UPDATE ".$fusion_prefix."downloads SET download_count=download_count+1 WHERE download_id='$download_id'");
	$result = dbquery("SELECT download_url FROM ".$fusion_prefix."downloads WHERE download_id='$download_id'");
	$data = dbarray($result);
	header ("Location: $data[download_url]");
}

// Check if $weblink_id is set.
if (isset($weblink_id)) {
	$result = dbquery("UPDATE ".$fusion_prefix."weblinks SET weblink_count=weblink_count+1 WHERE weblink_id='$weblink_id'");
	$result = dbquery("SELECT weblink_url FROM ".$fusion_prefix."weblinks WHERE weblink_id='$weblink_id'");
	$data = dbarray($result);
	header ("Location: $data[weblink_url]");
}

// Set a cookie to prevent multiple counts on the unique visit counter
if (!isset($_COOKIE['fusion_visited'])) {
	$result=dbquery("UPDATE ".$fusion_prefix."settings SET counter=counter+1");
	setcookie("fusion_visited", "yes", time() + 31536000, "/", "", "0");
}

// Check if the login button has been pressed
if (isset($_POST['login'])) {
	$result = dbquery("SELECT * FROM ".$fusion_prefix."users WHERE user_name='".$_POST['username']."' and user_password=md5('".$_POST['password']."')");
	if (dbrows($result) != 0) {
		$data = dbarray($result);
		if ($data[user_ban] != 1) {			
			setcookie("fusion_user", "$data[user_id].$data[user_password]", time() + 31536000, "/", "", "0");
			header("Location: index.php");
		} else {
			$loginerror = "This account is suspended<br><br>\n";
		}
	}
}

// Check if $logout is set
if ($_REQUEST['logout'] == "yes") {
	setcookie("fusion_user", "Null", time() - 7200, "/", "", "0");
	setcookie("fusion_lastvisit", "", time() - 7200, "/", "", "0");
	header ("Location: index.php");
}

// If value of cookie fusion_user is set, check the database and automatically log the user in
if (isset($_COOKIE['fusion_user'])) {
	$logincheck = explode(".", $_COOKIE['fusion_user']);
	$result = dbquery("SELECT * FROM ".$fusion_prefix."users WHERE user_id='$logincheck[0]' AND user_password='$logincheck[1]'");
	if (dbrows($result) != 0) {
		$userdata = dbarray($result);
		if ($userdata[user_ban] != 1) {
			if (empty($_COOKIE['fusion_lastvisit'])) {
				setcookie("fusion_lastvisit", "$userdata[user_lastvisit]", time() + 3600, "/", "", "0");
				$lastvisited = $userdata[user_lastvisit];
			} else {
				$lastvisited = $_COOKIE['fusion_lastvisit'];
			}
		} else {
			setcookie("fusion_user", "Null", time() - 7200, "/", "", "0");
			setcookie("fusion_lastvisit", "", time() - 7200, "/", "", "0");
			header ("Location: index.php");
		}
	} else {
		setcookie("fusion_user", "Null", time() - 7200, "/", "", "0");
		setcookie("fusion_lastvisit", "", time() - 7200, "/", "", "0");
		header ("Location: index.php");
	}
} else {
	$userdata = "";
}

// Initialize the secondary core functions
require "includes/classes2.php";

// Check if update_profile button in edit_profile.php is pressed
if (isset($_POST['update_profile'])) {
	require "includes/update_profile.php";
}

// Check if post_shout button in shoutbox.php is pressed
if (isset($_POST['post_shout'])) {
	require "includes/post_shout.php";
	header ("Location: index.php");
}
?>