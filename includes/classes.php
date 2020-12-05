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
// Patch code to enable PHP-Fusion to work with register_globals set to Off
// N.B. Only works with PHP 4.2.0 or above
if (phpversion() >= "4.2.0") {
	if (ini_get('register_globals') != 1) {
		$supers = array('_REQUEST', '_ENV', '_SERVER', '_POST', '_GET', '_COOKIE', '_SESSION', '_FILES', '_GLOBALS' );
		foreach ($supers as $__s) {
			if ((isset($$__s) == true) && (is_array($$__s) == true)) extract($$__s, EXTR_OVERWRITE);
		}
		unset($supers);
	}
} else {
	if (ini_get('register_globals') != 1) {
		$supers = array('HTTP_POST_VARS', 'HTTP_GET_VARS', 'HTTP_COOKIE_VARS', 'GLOBALS', 'HTTP_SESSION_VARS', 'HTTP_SERVER_VARS', 'HTTP_ENV_VARS');
		foreach ($supers as $__s) {
			if ((isset($$__s) == true) && (is_array($$__s) == true)) extract ($$__s, EXTR_OVERWRITE);
		}
		unset($supers);
	}
}

// definitions
$numlevels = (substr_count(substr($PHP_SELF, strlen(fusion_root)),"/"));
while ($i < $numlevels) { $prefix .= "../"; $i++; }
define("fusion_basedir", "$prefix");

// database functions
function dbquery($query) {
	$query = mysql_query($query);
	return $query;
}

function dbresult($query, $row) {
	$query = mysql_result($query, $row);
	return $query;
}

function dbrows($query) {
	$query = mysql_num_rows($query);
	return $query;
}

function dbarray($query) {
	$query = mysql_fetch_array($query);
	return $query;
}

function dbid($query) {
	$query = last_insert_id($query);
	return $query;
}

function dbconnect($dbhost, $dbusername, $dbpassword, $dbname) {
	mysql_connect("$dbhost", "$dbusername", "$dbpassword") or die ("Unable to connect to SQL Server");
	mysql_select_db("$dbname") or die ("Unable to select database");
}

// Strip Input Function, prevents HTML in unwanted places
function stripinput($text) {
	$search = array("\"", "'", '\"', "\'", "<", ">", "&nbsp;");
	$replace = array("&quot;", "&#39;", "&quot;", "&#39;", "&lt;", "&gt;", " ");
	$text = str_replace($search, $replace, $text);
	$text = trim(chop($text));
	return $text;
}
?>