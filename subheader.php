<?php
/*--------------------------------------------+
| PHP-Fusion 5 - Content Management System    |
|---------------------------------------------|
| author: Nick Jones (Digitanium) © 2002-2005 |
| web: http://www.php-fusion.co.uk            |
| email: nick@php-fusion.co.uk                |
|---------------------------------------------|
| Released under the terms and conditions of  |
| the GNU General Public License (Version 2)  |
+--------------------------------------------*/
require_once FUSION_THEME."theme.php";

if ($settings['maintenance'] == "1" && !iADMIN) { header("Location: ".FUSION_BASE."maintenance.php"); exit; }
if (iMEMBER) $result = dbquery("UPDATE ".$fusion_prefix."users SET user_lastvisit='".time()."', user_ip='".FUSION_IP."' WHERE user_id='".$userdata['user_id']."'");

echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
<html>
<head>
<title>".$settings['sitename']."</title>
<meta http-equiv='Content-Type' content='text/html; charset=".FUSION_CHARSET."'>
<meta name='description' content='".$settings['description']."'>
<meta name='keywords' content='".$settings['keywords']."'>
<link rel='stylesheet' href='".FUSION_THEME."styles.css' type='text/css'>
<script type='text/javascript' src='".FUSION_INCLUDES."fusion_script.js'></script>
</head>
<body bgcolor='$body_bg' text='$body_text'>\n";

render_header("<img src='".FUSION_BASE.$settings['sitebanner']."' alt='".$settings['sitename']."'>");
?>