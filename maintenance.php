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
@require "fusion_config.php";
require "header.php";

require fusion_langdir."global.php";
require fusion_themedir."theme.php";

echo "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'>
<html>
<head>
<title>$settings[sitename]</title>
<meta http-equiv='Content-Type' content='text/html; charset=".fusion_charset."'>
<meta name='description' content='$settings[description]'>
<meta name='keywords' content='$settings[keywords]'>
<link rel='stylesheet' href='".fusion_themedir."styles.css' type='text/css'>
</head>
<body bgcolor='$body_bg' leftmargin='$body_margin' topmargin='$body_margin' text='$body_text'>
<table align='center' width='$table_width' cellspacing='0' cellpadding='0'";
if ($table_border) { echo " style='$table_border'>\n"; } else { echo ">\n"; }
echo "<tr>
<td>
<center><br />
Maintenance Mode Activated<br /><br />
<a href='$settings[siteurl]'>$settings[sitename]</a> will be back shortly.
<br /><br /></center>
</td>
</tr>
</table>
</body>
</html>\n";

ob_end_flush();
?>