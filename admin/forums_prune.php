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
opentable(LAN_400);
if ($userdata[user_mod] > "2") {
	
	$expired = time()-(86400*$prunedays);
	
	// Check number of posts & threads older than expired date and delete them
	$result = dbquery("SELECT * FROM ".$fusion_prefix."posts WHERE post_datestamp < $expired");
	$delposts = dbrows($result);
	$result = dbquery("DELETE FROM ".$fusion_prefix."posts WHERE post_datestamp < $expired");
	$result = dbquery("SELECT * FROM ".$fusion_prefix."threads WHERE thread_lastpost < $expired");
	$delthreads = dbrows($result);
	$result = dbquery("DELETE FROM ".$fusion_prefix."threads WHERE thread_lastpost < $expired");
	echo LAN_401.$delposts."<br>
".LAN_402.$delthreads."<br><br>\n";

	// Refresh Forum Thread & Posts counts
	echo "<b>".LAN_403."</b><br>\n";
	$result = dbquery("SELECT * FROM ".$fusion_prefix."forums WHERE forum_cat!='0'");
	while ($data = dbarray($result)) {
		$threads = dbquery("SELECT count(forum_id) FROM ".$fusion_prefix."threads WHERE forum_id='$data[forum_id]'");
		$numthreads = dbresult($threads, 0);
		$posts = dbquery("SELECT count(forum_id) FROM ".$fusion_prefix."posts WHERE forum_id='$data[forum_id]'");
		$numposts = dbresult($posts, 0)-$numthreads;
		if ($numposts == -1) {
			$numposts = 0;
		}
		if ($numthreads == 0) {
			$lastpost = ", forum_lastpost='0', forum_lastuser='0'";
		} else {
			$lastpost = "";
		}
		$result2 = dbquery("UPDATE ".$fusion_prefix."forums SET forum_threads='$numthreads', forum_posts='$numposts'".$lastpost." WHERE forum_id='$data[forum_id]'");
		echo "$data[forum_name] - $numthreads".LAN_404."& $numposts".LAN_405."<br>\n";
	}
	
	// Refresh Thread Replies counts
	echo "<br><b>".LAN_406."</b><br>\n";
	$result = dbquery("SELECT * FROM ".$fusion_prefix."threads");
	while ($data = dbarray($result)) {
		$replies = dbquery("SELECT count(thread_id) FROM ".$fusion_prefix."posts WHERE thread_id='$data[thread_id]'");
		$numreplies = dbresult($replies, 0)-1;
		if ($numreplies == -1) {
			$numreplies = 0;
		}
		$result2 = dbquery("UPDATE ".$fusion_prefix."threads SET thread_replies='$numreplies' WHERE thread_id='$data[thread_id]'");
		echo "$data[thread_subject] - $numreplies".LAN_407."<br>";
	}
}
closetable();
tablebreak();
?>
