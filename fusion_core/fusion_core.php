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
if (eregi("fusion_core.php", $_SERVER['PHP_SELF'])) die();

if (phpversion() >= "4.2.0") {
	if (ini_get('register_globals') != 1) {
		$supers = array('_REQUEST', '_ENV', '_SERVER', '_POST', '_GET', '_COOKIE', '_SESSION', '_FILES', '_GLOBALS' );
		foreach ($supers as $__s) { if ((isset($$__s) == true) && (is_array($$__s) == true)) extract($$__s, EXTR_OVERWRITE); }
		unset($supers);
	}
} else {
	if (ini_get('register_globals') != 1) {
		$supers = array('HTTP_POST_VARS', 'HTTP_GET_VARS', 'HTTP_COOKIE_VARS', 'GLOBALS', 'HTTP_SESSION_VARS', 'HTTP_SERVER_VARS', 'HTTP_ENV_VARS');
		foreach ($supers as $__s) { if ((isset($$__s) == true) && (is_array($$__s) == true)) extract ($$__s, EXTR_OVERWRITE); }
		unset($supers);
	}
}

foreach ($_GET as $secv) {
	if ((eregi("<[^>]*script*\"?[^>]*>", $secv)) || (eregi("<[^>]*object*\"?[^>]*>", $secv)) || (eregi("<[^>]*iframe*\"?[^>]*>", $secv)) ||
		(eregi("<[^>]*applet*\"?[^>]*>", $secv)) || (eregi("<[^>]*meta*\"?[^>]*>", $secv)) || (eregi("<[^>]*style*\"?[^>]*>", $secv)) ||
		(eregi("<[^>]*form*\"?[^>]*>", $secv)) || (eregi("\([^>]*\"?[^)]*\)", $secv)) || (eregi("\"", $secv))) {
	die ();
	}
}

// database functions
function dbquery($query) {
	if (!$query = mysql_query($query)) { echo mysql_error(); }
	return $query;
}

function dbresult($query, $row) {
	if (!$query = mysql_result($query, $row)) { echo mysql_error(); }
	return $query;
}

function dbrows($query) {
	if (!$query = mysql_num_rows($query)) { echo mysql_error(); }
	return $query;
}

function dbarray($query) {
	if (!$query = mysql_fetch_assoc($query)) { echo mysql_error(); }
	return $query;
}

function dbarraynum($query) {
	if (!$query = mysql_fetch_row($query)) { echo mysql_error(); }
	return $query;
}

function dbconnect($dbhost, $dbusername, $dbpassword, $dbname) {
	mysql_connect("$dbhost", "$dbusername", "$dbpassword") or die ("Unable to connect to SQL Server");
	mysql_select_db("$dbname") or die ("Unable to select database");
}

// Strip Input Function, prevents HTML in unwanted places
function stripinput($text) {
	if (ini_get('magic_quotes_gpc')) $text = stripslashes($text);
	$search = array("\"", "'", "\\", '\"', "\'", "<", ">", "&nbsp;");
	$replace = array("&quot;", "&#39;", "&#92;", "&quot;", "&#39;", "&lt;", "&gt;", " ");
	$text = str_replace($search, $replace, $text);
	$text = trim(chop($text));
	return $text;
}
?>