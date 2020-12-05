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
$itemsperpage = 10;
$result = dbquery("SELECT * FROM ".$fusion_prefix."news");
$rows = dbrows($result);
if (!$rowstart) $rowstart = 0;
if ($rows != 0) {
	$i = 1;
	$result = dbquery(
		"SELECT tn.*, user_id, user_name FROM ".$fusion_prefix."news tn
		LEFT JOIN ".$fusion_prefix."users tu ON tn.news_name=tu.user_id
		ORDER BY news_datestamp DESC LIMIT $rowstart,$itemsperpage"
	);
	$numrows = dbrows($result);
	while ($data = dbarray($result)) {
		$news_subject = stripslashes($data['news_subject']);
		$news_news = stripslashes($data['news_news']);
		if ($data['news_breaks'] == "y") $news_news = nl2br($news_news);
		$news_date = strftime($settings['longdate'], $data['news_datestamp']+($settings['timeoffset']*3600));
		if ($data['news_extended']) { $extnews = "y"; } else { $extnews = "n"; }
		$news_info = array($data['news_id'], $data['user_id'], $data['user_name'], $news_date,
		$extnews, $data['news_comments'], $data['news_reads']);
		render_news($news_subject, $news_news, $news_info);
		if ($i != $numrows) {
			tablebreak();
		}
		$i++;
	}
	echo "<div align='center' style='margin-top:5px;'>
".makePageNav($rowstart,$itemsperpage,$rows,3,"$PHP_SELF?")."
</div>\n";
} else {
	opentable(LAN_46);
	echo "<center>".LAN_47."</center>\n";
	closetable();
}
?>