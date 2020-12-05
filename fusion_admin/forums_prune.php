<?
/*--------------------------------------------+
| PHP-Fusion 5 - Content Management System    |
|---------------------------------------------|
| author: Nick Jones (Digitanium) © 2002-2005 |
| web: http://www.php-fusion.co.uk            |
| email: nick@php-fusion.co.uk                |
|---------------------------------------------|
| Released under the terms and conditions of  |
| the GNU General Public License (Version 2)  |
+--------------------------------------------*/
if (!defined("IN_FUSION")) { header("Location:../index.php"); exit; }

if (!iSUPERADMIN) { header("Location:../index.php"); exit; }

opentable(LAN_750);
$expired = time()-(86400 * $_POST['prune_days']);
// Check number of posts & threads older than expired date and delete them
$sql = dbquery("SELECT post_id,post_datestamp FROM ".$fusion_prefix."posts WHERE post_datestamp < $expired");
$delposts = dbrows($sql);
if ($delposts != 0) {
	$delattach = 0;
	while ($data = dbarray($sql)) {
		$sql2 = dbquery("SELECT * FROM ".$fusion_prefix."forum_attachments WHERE post_id='".$data['post_id']."'");
		if (dbrows($sql2) != 0) {
			$delattach++;
			$attach = dbarray($sql2);
			unlink(FUSION_PUBLIC."attachments/".$attach['attach_name']."[".$attach['attach_id']."]".$attach['attach_ext']);
			$sql3 = dbquery("DELETE FROM ".$fusion_prefix."forum_attachments WHERE post_id='".$data['post_id']."'");
		}
	}
}
$sql = dbquery("DELETE FROM ".$fusion_prefix."posts WHERE post_datestamp < $expired");
$delthreads = dbcount("(thread_id)", "threads", "thread_lastpost < $expired");
$sql = dbquery("DELETE FROM ".$fusion_prefix."threads WHERE thread_lastpost < $expired");
echo LAN_751.$delposts."<br>\n".LAN_752.$delthreads."<br>\nAttachments Deleted: $delattach<br>\n";
closetable();
tablebreak();
?>
