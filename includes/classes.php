<?
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
// table functions
function opentable($title) {
	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" class=\"border\">
<tr><td>
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr><td class=\"title\">$title</td></tr>
<tr><td class=\"body\">\n";
}
function opentables($title) {
	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
<tr><td>
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr><td class=\"sidetitle\">$title</td></tr>
<tr><td class=\"sidebody\">\n";
}
function closetable() {
	echo "</td></tr>
</table>
</td></tr>
</table>\n";
}
function opentablex() {
	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" class=\"border\">
<tr><td>\n";
}
function closetablex() {
	echo "</td></tr>
</table>\n";
}
function tablebreak() {
	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
<tr><td height=\"8\"></td></tr>
</table>\n";
}
function tablebar() {
	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
<tr><td height=\"8\" class=\"tablebar\">&nbsp;</td></tr>
</table>\n";
}
// misc functions
function stripinput($text) {
	$text = htmlentities($text);
	$text = str_replace("&nbsp;", "", $text);
	$text = trim($text);
	return $text;
}
function parseubb($message) {
	$ubbs = array("[b]", "[/b]", "[i]", "[/i]", "[u]", "[/u]", "[center]", "[/center]", "[url]",
	"[/url]", "[img]", "[/img]", "[small]", "[/small]");
	$ubbs2 = array("<b>", "</b>", "<i>", "</i>", "<u>", "</u>", "<center>", "</center>", "<a href=\"",
	"\" target=\"_blank\">Link</a>", "<img src=\"", "\">", "<font class=\"small\">", "</font>");
	$i = 0;
	while ($ubbs[$i] != "") {
		$message = str_replace($ubbs, $ubbs2, $message);
		$i++;
	}
	return $message;
}
function parsesmileys($message, $sub) {
	$smiley = array(":)", ";)", ":(", ":|", ":o", ":p", "(c)", "(l)", "(y)", "(n)");
	$smiley2 = array("<img src=\"$sub/smile.gif\">", "<img src=\"$sub/wink.gif\">", "<img src=\"$sub/sad.gif\">",
	"<img src=\"$sub/none.gif\">", "<img src=\"$sub/eek.gif\">", "<img src=\"$sub/pfft.gif\">",
	"<img src=\"$sub/cool.gif\">", "<img src=\"$sub/laugh.gif\">", "<img src=\"$sub/yes.gif\">",
	"<img src=\"$sub/no.gif\">");
	$i = 0;
	while ($smiley[$i] != "") {
		$message = str_replace($smiley, $smiley2, $message);
		$i++;
	}
	return $message;
}
function trimlink($length, $text) {
	if (strlen($text) > $length) {
		$text = substr($text, 0, ($length - 3))."...";
	}
	return $text;
}
?>