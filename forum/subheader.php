<?
echo "<html>
<head>
<title>$settings[sitename]</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
<meta name=\"description\" content=\"$settings[description]\">
<meta name=\"keywords\" content=\"$settings[keywords]\">
<link rel=\"stylesheet\" href=\"../themes/$settings[theme]/styles.css\" type=\"text/css\">
</head>
<body bgcolor=\"#888888\" text=\"#dddddd\">\n";
$today = strtoupper(gmdate("l F d Y", $servertime));

echo "<table width=\"100%\" align=\"center\" cellspacing=\"0\" cellpadding=\"0\" class=\"bodyborder\">
<tr><td>

<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" class=\"header\">
<tr><td><img src=\"../$settings[sitebanner]\"></td></tr>
</table>\n";

echo "<table width=\"100%\" cellspacing=\"10\" cellpadding=\"0\">\n";
?>