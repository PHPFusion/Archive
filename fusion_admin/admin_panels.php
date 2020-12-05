<?php

/* Admin panel array key:
field 1 = link
field 2 = title
field 3 = rights
*/

$admin_panels = array(
	0 => array(LAN_201, "articles.php", "1"),
	1 => array(LAN_202, "article_cats.php", "2"),
	2 => array(LAN_224, "blacklist.php", "3"),
	3 => array(LAN_203, "comments.php", "4"),
	4 => array(LAN_204, "custom_pages.php", "5"),
	5 => array(LAN_205, "db_backup.php", "6"),
	6 => array(LAN_206, "downloads.php", "7"),
	7 => array(LAN_207, "download_cats.php", "8"),
	8 => array(LAN_222, "faq.php", "9"),
	9 => array(LAN_208, "forums.php", "A"),
	10 => array(LAN_209, "images.php", "B"),
	11 => array(LAN_227, "infusions.php", "C"),
	12 => array(LAN_210, "members.php", "D"),
	13 => array(LAN_211, "news.php", "E"),
	14 => array(LAN_212, "panels.php", "F"),
	15 => array(LAN_223, "photoalbums.php", "G"),
	16 => array(LAN_213, "phpinfo.php", "H"),
	17 => array(LAN_214, "polls.php", "I"),
	18 => array(LAN_215, "shoutbox.php", "J"),
	19 => array(LAN_216, "site_links.php", "K"),
	20 => array(LAN_217, "settings.php", "L"),
	21 => array(LAN_218, "submissions.php", "M"),
	22 => array(LAN_219, "weblinks.php", "N"),
	23 => array(LAN_220, "weblink_cats.php", "O"),
	24 => array(LAN_221, "upgrade.php", "P"),
	25 => array(LAN_225, "user_groups.php", "Q"),
	26 => array(LAN_226, "administrators.php", "R"),
	27 => array(LAN_228, "empty", "S")
);

sort($admin_panels);
?>