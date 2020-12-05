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
if ($settings[forumpanels] == "V" || $settings[forumpanels] == "B") {
	$result = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_access>'0' ORDER BY forum_id");
	if (dbrows($result) != 0) {
		$exc_list = "WHERE ";
		for ($i=1;$data=dbarray($result);$i++) {
			$exc_list .= "forum_id!='$data[forum_id]' ";
			if ($i != dbrows($result)) { $exc_list .= "AND "; }
		}
	}			
	$result = dbquery("SELECT * FROM ".$fusion_prefix."threads ".$exc_list."ORDER BY thread_lastpost DESC LIMIT 5");
	if (dbrows($result) != 0) {
		openside(LAN_20);
		while($data = dbarray($result)) {
			$itemsubject = trimlink($data[thread_subject], 23);
			echo "· <a href=\"fusion_forum/viewthread.php?forum_id=$data[forum_id]&thread_id=$data[thread_id]\" class=\"slink\">$itemsubject</a><br>\n";
		}
		closeside();
	}
	$result = dbquery("SELECT * FROM ".$fusion_prefix."threads  ".$exc_list."ORDER BY thread_replies DESC,thread_lastpost DESC LIMIT 5");
	if (dbrows($result) != 0) {
		openside(LAN_21);
		while($data = dbarray($result)) {
			$itemsubject = trimlink($data[thread_subject], 20);
			echo "· <a href=\"fusion_forum/viewthread.php?forum_id=$data[forum_id]&thread_id=$data[thread_id]\" class=\"slink\">$itemsubject</a> [".$data[thread_replies]."]<br>\n";
		}
		closeside();
	}
}
$result = dbquery("SELECT * FROM ".$fusion_prefix."articles ORDER BY article_datestamp DESC LIMIT 0,5");
if (dbrows($result) != 0) {
	openside(LAN_22);
	while($data = dbarray($result)) {
		$itemsubject = trimlink($data[article_subject], 23);
		echo "· <a href=\"readarticle.php?article_id=$data[article_id]\" class=\"slink\">$itemsubject</a><br>\n";
	}
	closeside();
}
?>