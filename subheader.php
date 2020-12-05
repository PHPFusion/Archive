<?
/*
-------------------------------------------------------
	PHP-Fusion
	-----------------------------------------------
	© Nick Jones 2002-2004
	http://www.digitaldominion.co.uk
	nick@digitaldominion.co.uk
	-----------------------------------------------
	Released under the terms and conditions of the
	GNU General Public License (http://gnu.org).
-------------------------------------------------------
*/
require fusion_langdir."global.php";
require fusion_themedir."theme.php";

if ($userdata[user_name] != "") {
	$result = dbquery("UPDATE ".$fusion_prefix."users SET user_lastvisit='".time()."' WHERE user_id='$userdata[user_id]'");
}

echo "<html>
<head>
<title>$settings[sitename]</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=".fusion_charset."\">
<meta name=\"description\" content=\"$settings[description]\">
<meta name=\"keywords\" content=\"$settings[keywords]\">
<link rel=\"stylesheet\" href=\"".fusion_themedir."styles.css\" type=\"text/css\">
</head>
<body bgcolor=\"$body_bg\" leftmargin=\"$body_margin\" topmargin=\"$body_margin\" text=\"$body_text\">\n";

echo "<table align=\"center\" width=\"$table_width\" cellspacing=\"0\" cellpadding=\"0\" style=\"$table_border\">
<tr>
<td>
<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
<tr>
<td class=\"full-header\"><center><img src=\"".fusion_basedir."$settings[sitebanner]\"></center></td>
</tr>
</table>\n";

if ($header_bar) {
	echo "$header_bar";
}

echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
<tr>
<td width=\"170\" valign=\"top\" class=\"slborder\">\n";
?>