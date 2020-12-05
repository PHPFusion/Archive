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
require fusion_themedir."theme.php";

if ($settings[maintenance] == "on" && !SuperAdmin()) header("Location: ".fusion_basedir."maintenance.php");
if (Member()) $result = dbquery("UPDATE ".$fusion_prefix."users SET user_lastvisit='".time()."' WHERE user_id='$userdata[user_id]'");

echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"DTD/xhtml1-strict.dtd\">
<html>
<head>
<title>$settings[sitename]</title>
<meta http-equiv='Content-Type' content='text/html; charset=".fusion_charset."'>
<meta name='description' content='$settings[description]'>
<meta name='keywords' content='$settings[keywords]'>
<link rel='stylesheet' href='".fusion_themedir."styles.css' type='text/css'>
<script type='text/javascript' src='".fusion_basedir."fusion_core/fusion.js'></script>
</head>
<body bgcolor='$body_bg' text='$body_text'>
<table align='center' width='$table_width' cellspacing='0' cellpadding='0'";
if ($table_border) { echo " style='$table_border'>\n"; } else { echo ">\n"; }
echo "<tr>
<td>
<table width='100%' cellspacing='0' cellpadding='0'>
<tr>
<td class='full-header'><img src='".fusion_basedir."$settings[sitebanner]' alt='$settings[sitename]'></td>
</tr>
</table>\n";

if ($header_bar) {
	echo $header_bar;
}

echo "<table width='100%' cellspacing='0' cellpadding='0'>
<tr>\n";
?>