<?
$result = dbquery("SELECT * FROM threads ORDER BY lastpost DESC LIMIT 0,5");
if (dbrows($result) != 0) {
	opentables("Newest Threads");
	while($data = dbarray($result)) {
		$itemsubject = stripslashes($data[subject]);
		if (strlen($itemsubject) > 25) {
			$itemsubject = substr($itemsubject, 0, 22)."...";
		}
		echo "<a href=\"forum/viewthread.php?fid=$data[fid]&tid=$data[tid]\">$itemsubject</a><br>\n";
	}
	closetable();
}
$result = dbquery("SELECT * FROM threads ORDER BY replies DESC LIMIT 0,5");
if (dbrows($result) != 0) {
	opentables("Hottest Threads");
	while($data = dbarray($result)) {
		$itemsubject = stripslashes($data[subject]);
		if (strlen($itemsubject) > 25) {
			$itemsubject = substr($itemsubject, 0, 22)."...";
		}
		echo "<a href=\"forum/viewthread.php?fid=$data[fid]&tid=$data[tid]\">$itemsubject</a> (".$data[replies].")<br>\n";
	}
	closetable();
}
$result = dbquery("SELECT * FROM articles ORDER BY posted DESC LIMIT 0,5");
if (dbrows($result) != 0) {
	opentables("Latest Articles");
	while($data = dbarray($result)) {
		$itemsubject = stripslashes($data[subject]);
		if (strlen($itemsubject) > 25) {
			$itemsubject = substr($itemsubject, 0, 22)."...";
		}
		echo "<a href=\"readarticle.php?aid=$data[aid]\">$itemsubject</a><br>\n";
	}
	closetable();
}
tablebar();
?>