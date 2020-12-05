<?
$itemsperpage = 10;
$result = dbquery("SELECT * FROM news");
$rows = dbrows($result);
if (!$rowstart) {
	$rowstart = 0;
}
if ($rows != 0) {
	$totalpages = ceil($rows / $itemsperpage);	
	$currentpage = $rowstart / $itemsperpage + 1;
	// check how many news items are in the database
	$result = dbquery("SELECT * FROM news ORDER BY posted DESC LIMIT $rowstart,$itemsperpage");
	$numrows = dbrows($result);
	$i = 1;
	while ($data = dbarray($result)) {
		$postdate = gmdate("F d Y", $data[posted]);
		$posttime = gmdate("H:i", $data[posted]);
		$subject = stripslashes($data[subject]);
		$news = stripslashes($data[news]);
		$news = nl2br($news);
		opentablex();
		echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr><td class=\"newstitle\">$subject<br>
<span class=\"newssubtitle\">Posted by <a href=\"mailto:$data[postemail]\" class=\"x\">".$data[postname]."</a> on $postdate at $posttime</span></td></tr>
<tr><td class=\"newsbody\">$news</td></tr>
<tr><td class=\"newsfoot\">";
	if ($data[extendednews] != "") {
		echo "<a href=\"readmore.php?nid=$data[nid]\" class=\"x\">Read More</a> | ";
	}
	echo "<a href=\"readmore.php?nid=$data[nid]\" class=\"x\">$data[comments] Comments</a> | $data[reads] Reads</td></tr>
</table>\n";
		closetablex();
		if ($i != $numrows) {
			tablebreak();
		}
		$i++;
	}
	if ($rowstart >= $itemsperpage) {
		$start = $rowstart - $itemsperpage;
		$prev = "<a href=\"$PHP_SELF?rowstart=$start\" class=\"x\">Prev</a>";
	}
	if ($rowstart + $itemsperpage < $rows) {
		$start = $rowstart + $itemsperpage;
		$next = "<a href=\"$PHP_SELF?rowstart=$start\" class=\"x\">Next</a>";
	}
	if ($prev != "" || $next != "") {
		tablebreak();
		opentablex();
		echo "<table width=\"100%\" class=\"nextprev\">
<tr><td width=\"50\" class=\"small\">$prev</td>
<td align=\"center\" class=\"small\">Page $currentpage of $totalpages</td>
<td width=\"50\" align=\"right\" class=\"small\">$next</td></tr>
</table>\n";
		closetablex();
	}
} else {
	opentable("No News");
	echo "No News has been posted yet.\n";
	closetable();
}
?>