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
$itemsperpage = 10;
$result = dbquery("SELECT * FROM ".$fusion_prefix."news");
$rows = dbrows($result);
if (!$rowstart) {
	$rowstart = 0;
}
if ($rows != 0) {
	$i = 1;
	$totalpages = ceil($rows / $itemsperpage);	
	$currentpage = $rowstart / $itemsperpage + 1;
	// check how many news items are in the database
	$result = dbquery("SELECT * FROM ".$fusion_prefix."news ORDER BY news_datestamp DESC LIMIT $rowstart,$itemsperpage");
	$numrows = dbrows($result);
	while ($data = dbarray($result)) {
		$news_subject = stripslashes($data[news_subject]);
		$news_news = stripslashes($data[news_news]);
		$news_date = strftime($settings[longdate], $data[news_datestamp]+($settings[timeoffset]*3600));
		if ($data[news_extended]) { $extnews = "y"; } else { $extnews = "n"; }
		$news_info = array($data[news_id], $data[news_email], $data[news_name], $news_date,
		$extnews, $data[news_comments], $data[news_reads]);
		render_news($news_subject, $news_news, $news_info);
		if ($i != $numrows) {
			tablebreak();
		}
		$i++;
	}
	if ($rowstart >= $itemsperpage) {
		$start = $rowstart - $itemsperpage;
		$prev = "<a href=\"$PHP_SELF?rowstart=$start\" class=\"white\">".LAN_50."</a>";
	}
	if ($rowstart + $itemsperpage < $rows) {
		$start = $rowstart + $itemsperpage;
		$next = "<a href=\"$PHP_SELF?rowstart=$start\" class=\"white\">".LAN_51."</a>";
	}
	if ($prev != "" || $next != "") {
		$current = LAN_52.$currentpage.LAN_53.$totalpages;
		tablebreak();
		prevnextbar($prev,$current,$next);
	}
} else {
	opentable(LAN_45);
	echo "<center>".LAN_46."</center>\n";
	closetable();
}
?>