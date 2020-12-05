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
require "../includes/config.php";
require "../includes/classes.php";

// get the user's up address and store it in a variable.
$user_ip = getenv(REMOTE_ADDR);

// Open a connection to the database, settings for which are retrieved from includes/config.php file
dbconnect($dbhost, $dbusername, $dbpassword, $dbname);

// Get the settings from the database and store them in an array
$settings = dbarray(dbquery("SELECT * FROM ".$fusion_prefix."settings"));

// Define the language and theme paths
define("fusion_langdir", fusion_basedir."language/".$settings[language]."/");
define("fusion_themedir", fusion_basedir."themes/".$settings[theme]."/");

// Set a cookie to prevent multiple counts on the unique visit counter
if (!isset($_COOKIE['fusion_visited'])) {
	$result=dbquery("UPDATE ".$fusion_prefix."settings SET counter=counter+1");
	setcookie("fusion_visited", "yes", time() + 31536000, "/", "", "0");
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
require "../includes/classes2.php";
?>