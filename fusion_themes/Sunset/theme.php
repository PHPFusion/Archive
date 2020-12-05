<?
// theme settings
$body_text = "#bbbbbb";
$body_bg = "#000000";
$table_width = "90%";
$table_border = "border:1px #555 solid;";

// horizontal bar
$header_bar = "<table width='100%' cellspacing='0' cellpadding='0'>
<tr>
<td class='white-header'>
<a href='".fusion_basedir."index.php' class='white'>Home</a> |
<a href='".fusion_basedir."articles.php' class='white'>Articles</a> |
<a href='".fusion_basedir."downloads.php' class='white'>Downloads</a> |
<a href='".fusion_basedir."fusion_forum/' class='white'>Forum</a> |
<a href='".fusion_basedir."weblinks.php' class='white'>Web Links</a> |
<a href='".fusion_basedir."submit_news.php' class='white'>Submit News</a> |
<a href='".fusion_basedir."submit_link.php' class='white'>Submit Link</a>
</td>
<td align='right' class='white-header'>
".ucwords(strftime("%A, %B %d, %Y", time()+($settings[timeoffset]*3600)))."</td>
</tr>
</table>\n";

function render_news($subject, $news, $info) {

// $info is an array, the array order is:
// 0 = news_id, 1 = email, 2 = author, 3 = date, 4 = extended?, 5 = comments, 6 = reads

echo "<table width='100%' cellpadding='0' cellspacing='0' class='border'>
<tr>
<td>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='capmain'>$subject</td>
</tr>
</table>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='body'>
$news
</td>
</tr>
</table>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td align='left' class='news-footer'>".LAN_40."<a href='mailto:$info[1]' class='white'>$info[2]</a>".LAN_41."$info[3]</td>
<td align='right' class='news-footer'>";
if ($info[4] == "y") {
	echo "<a href='readmore.php?news_id=$info[0]'>".LAN_42."</a> | ";
}
echo "<a href='readmore.php?news_id=$info[0]'>$info[5]".LAN_43."</a> | $info[6]".LAN_44."</td>
</tr>
</table>
</td>
</tr>
</table>\n";

}

function render_morenews($subject, $morenews, $info) {
	
echo "<table width='100%' cellpadding='0' cellspacing='0' class='border'>
<tr>
<td>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='capmain'>$subject<br>
<span class='small'>".LAN_40."<a href='mailto:$info[0]' class='white'>$info[1]</a>".LAN_41."$info[2]</span></td>
</tr>
</table>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='body'>
$morenews
</td>
</tr>
</table>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td align='right' class='news-footer'>$info[3]".LAN_43." | $info[4]".LAN_44."</td>
</tr>
</table>
</td>
</tr>
</table>\n";

}

function render_article($subject, $article, $info) {
	
echo "<table width='100%' cellpadding='0' cellspacing='0' class='border'>
<tr>
<td>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='capmain'>$subject<br>
<span class='small'>".LAN_40."<a href='mailto:$info[1]' class='white'>$info[2]</a>".LAN_41."$info[3]</span></td>
</tr>
</table>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='body'>\n";
if ($info[4] == "y") { echo nl2br($article); } else { echo $article; }
echo "</td>
</tr>
</table>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td align='right' class='news-footer'>\n";
if ($info[0] != "0") {
	echo "<a href='articlecomments.php?article_id=$info[0]'>$info[5]".LAN_43."</a> | ";
} else {
	echo "$info[5]".LAN_43." | ";
}
echo "$info[6]".LAN_44."</td>
</tr>
</table>
</td>
</tr>
</table>\n";

}

function opentable($title) {

echo "<table width='100%' cellpadding='0' cellspacing='0' class='border'>
<tr>
<td>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='capmain'>$title</td>
</tr>
</table>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='body'>\n";

}

function closetable() {

echo "</td>
</tr>
</table>
</td>
</tr>
</table>\n";

}

function openside($title) {
	
echo "<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='scapmain'>$title</td>
</tr>
</table>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='sbody'>\n";

}

function closeside() {

echo "</td>
</tr>
</table>\n";

}

function opensidex($title,$open="on") {

if($open=="on"){$box_img="off";}else{$box_img="on";}
echo "<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='scapmain'>$title</td>
<td class='scapmain' align='right'>
<img onclick=\"javascript:flipBox('$title')\" name='b_$title' border='0' src='".fusion_themedir."images/panel_$box_img.gif'>
</td>
</tr>
</table>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='sbody'>
<div id='box_$title'"; if($open=="off"){ echo "style='display:none'"; } echo ">\n";

}

function closesidex() {

echo "</div>
</td>
</tr>
</table>\n";

}

function tablebreak() {

echo "<table width='100%' cellspacing='0' cellpadding='0'>
<tr><td height='8'></td></tr>
</table>\n";

}
?>