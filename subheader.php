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

if ($settings['maintenance'] == "1" && !SuperAdmin()) header("Location: ".fusion_basedir."maintenance.php");
if (Member()) $result = dbquery("UPDATE ".$fusion_prefix."users SET user_lastvisit='".time()."' WHERE user_id='".$userdata['user_id']."'");

echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
<html>
<head>
<title>".$settings['sitename']."</title>
<meta http-equiv='Content-Type' content='text/html; charset=".fusion_charset."'>
<meta name='description' content='".$settings['description']."'>
<meta name='keywords' content='".$settings['keywords']."'>
<link rel='stylesheet' href='".fusion_themedir."styles.css' type='text/css'>
<style type='text/css'>
<!--
.gallery { padding: 16px 0px 8px 0px; }
.gallery img { border: 1px solid #ccc; filter: gray; }
.gallery:hover img { border: 1px solid red; filter: none; }
img.activegallery { border: 1px solid green; filter: none; }
//-->
</style>
<script type='text/javascript' src='".fusion_basedir."fusion_core/fusion.js'></script>
</head>
<body bgcolor='$body_bg' text='$body_text'>\n";

render_header("<img src='".fusion_basedir.$settings['sitebanner']."' alt='".$settings['sitename']."'>");
?>