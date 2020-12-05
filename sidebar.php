<?
/*
-------------------------------------------------------
	PHP Fusion X3
	-----------------------------------------------
	© Nick Jones 2002-2004
	http://www.digitaldominion.co.uk
	nick@digitaldominion.co.uk
	-----------------------------------------------
	Released under the terms and conditions of the
	GNU General Public License (http://gnu.org).
-------------------------------------------------------
*/
if ($settings[forumpanels] == "V" || $settings[forumpanels] == "B") {
	$result = dbquery("SELECT * FROM ".$fusion_prefix."threads ORDER BY thread_lastpost DESC LIMIT 0,5");
	if (dbrows($result) != 0) {
		openside(LAN_20);
		while($data = dbarray($result)) {
			$itemsubject = stripslashes($data[thread_subject]);
			if (strlen($itemsubject) > 25) {
				$itemsubject = substr($itemsubject, 0, 22)."...";
			}
			echo "· <a href=\"forum/viewthread.php?forum_id=$data[forum_id]&thread_id=$data[thread_id]\" class=\"slink\">$itemsubject</a><br>\n";
		}
		closeside();
	}
	$result = dbquery("SELECT * FROM ".$fusion_prefix."threads ORDER BY thread_replies DESC,thread_lastpost DESC LIMIT 0,5");
	if (dbrows($result) != 0) {
		openside(LAN_21);
		while($data = dbarray($result)) {
			$itemsubject = stripslashes($data[thread_subject]);
			if (strlen($itemsubject) > 23) {
				$itemsubject = substr($itemsubject, 0, 20)."...";
			}
			echo "· <a href=\"forum/viewthread.php?forum_id=$data[forum_id]&thread_id=$data[thread_id]\" class=\"slink\">$itemsubject</a> [".$data[thread_replies]."]<br>\n";
		}
		closeside();
	}
}
$result = dbquery("SELECT * FROM ".$fusion_prefix."articles ORDER BY article_datestamp DESC LIMIT 0,5");
if (dbrows($result) != 0) {
	openside(LAN_22);
	while($data = dbarray($result)) {
		$itemsubject = stripslashes($data[article_subject]);
		if (strlen($itemsubject) > 25) {
			$itemsubject = substr($itemsubject, 0, 22)."...";
		}
		echo "· <a href=\"readarticle.php?article_id=$data[article_id]\" class=\"slink\">$itemsubject</a><br>\n";
	}
	closeside();
}
?>