<?
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
@include "fusion_config.php";
include "fusion_core.php";
include FUSION_LANGUAGES.FUSION_LAN."global.php";
include FUSION_THEME."theme.php";

echo "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'>
<html>
<head>
<title>".$settings['sitename']."</title>
<meta http-equiv='Content-Type' content='text/html; charset=".FUSION_CHARSET."'>
<meta name='description' content='".$settings['description']."'>
<meta name='keywords' content='".$settings['keywords']."'>
<link rel='stylesheet' href='".FUSION_THEME."styles.css' type='text/css'>
</head>
<body bgcolor='$body_bg' text='$body_text'>

<table width='100%' height='100%'>
<tr>
<td>

<table align='center' width='80%' cellspacing='0' cellpadding='0' style='border:1px #000 solid;'>
<tr>
<td class='full-header' style='padding:5px;'><img src='".FUSION_BASE.$settings['sitebanner']."' alt='".$settings['sitename']."'></td>
</tr>
<tr>
<td>
<center><br>
".LAN_00."<br><br>
".stripslashes(nl2br($settings['maintenance_message']))."
<br><br></center>
</td>
</tr>
<tr>
<td align='center' class='full-header'><br>
Powered by <a href='http://www.php-fusion.co.uk' class='foot'>PHP-Fusion</a> v".
sprintf("%.2f", $settings['version']/100)."© 2003-2004<br><br>
</td>
</tr>
</table>\n";

if (!iMEMBER) {
	echo "<div align='center'><br>
<form name='loginform' method='post' action='$PHP_SELF'>
".LAN_61.": <input type='text' name='user_name' class='textbox' style='width:100px'>
".LAN_62.": <input type='password' name='user_pass' class='textbox' style='width:100px'>
<input type='checkbox' name='remember_me' value='y'>".LAN_63."
<input type='submit' name='login' value='".LAN_64."' class='button'>
</form>
</div>\n";
}

echo "</td>
</tr>
</table>

</body>
</html>\n";

ob_end_flush();
?>