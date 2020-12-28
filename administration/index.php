<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) PHP-Fusion Inc
| https://www.phpfusion.com/
+--------------------------------------------------------+
| Filename: index.php
| Author: Core Development Team (coredevs@phpfusion.com)
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
require_once __DIR__.'/../maincore.php';
if (!iADMIN || $userdata['user_rights'] == "" || !defined("iAUTH") || !isset($_GET['aid']) || $_GET['aid'] != iAUTH) {
    redirect("../index.php");
}

require_once THEMES."templates/admin_header.php";
include LOCALE.LOCALESET."admin/main.php";

if (!isset($_GET['pagenum']) || !isnum($_GET['pagenum'])) {
    $_GET['pagenum'] = 0;
}

$admin_images = TRUE;

// Members stats
$members_registered = dbcount("(user_id)", DB_USERS, "user_status<='1' OR user_status='3' OR user_status='5'");
$members_unactivated = dbcount("(user_id)", DB_USERS, "user_status='2'");
$members_security_ban = dbcount("(user_id)", DB_USERS, "user_status='4'");
$members_canceled = dbcount("(user_id)", DB_USERS, "user_status='5'");
$members['registered'] = dbcount("(user_id)", DB_USERS, "user_status<='1' OR user_status='3' OR user_status='5'");
$members['unactivated'] = dbcount("(user_id)", DB_USERS, "user_status='2'");
$members['security_ban'] = dbcount("(user_id)", DB_USERS, "user_status='4'");
$members['cancelled'] = dbcount("(user_id)", DB_USERS, "user_status='5'");
if (fusion_get_settings("enable_deactivation") == "1") {
    $time_overdue = time() - (86400 * fusion_get_settings("deactivation_period"));
    $members['inactive'] = dbcount("(user_id)", DB_USERS, "user_lastvisit<'$time_overdue' AND user_actiontime='0' AND user_joined<'$time_overdue' AND user_status='0'");
}
// Get Core Infusion´s stats
$forum = [];
if (db_exists(DB_FORUMS)) {
    $forum['count'] = dbcount("('forum_id')", DB_FORUMS);
    $forum['thread'] = dbcount("('post_id')", DB_THREADS);
    $forum['post'] = dbcount("('post_id')", DB_POSTS);
    $forum['users'] = dbcount("('user_id')", DB_USERS, "user_posts > '0'");
}
$download = [];
if (db_exists(DB_DOWNLOADS)) {
    $download['download'] = dbcount("('download_id')", DB_DOWNLOADS);
    $download['comment'] = dbcount("('comment_id')", DB_COMMENTS, "comment_type='d'");
    $download['submit'] = dbcount("(submit_id)", DB_SUBMISSIONS, "submit_type='d'");
}
$articles = [];
if (db_exists(DB_ARTICLES)) {
    $articles['article'] = dbcount("('article_id')", DB_ARTICLES);
    $articles['comment'] = dbcount("('comment_id')", DB_COMMENTS, "comment_type='A'");
    $articles['submit'] = dbcount("(submit_id)", DB_SUBMISSIONS, "submit_type='a'");
}
$weblinks = [];
if (db_exists(DB_WEBLINKS)) {
    $weblinks['weblink'] = dbcount("('weblink_id')", DB_WEBLINKS);
    $weblinks['comment'] = dbcount("('comment_id')", DB_COMMENTS, "comment_type='L'");
    $weblinks['submit'] = dbcount("(submit_id)", DB_SUBMISSIONS, "submit_type='l'");
}
$news = [];
if (db_exists(DB_NEWS)) {
    $news['news'] = dbcount("('news_id')", DB_NEWS);
    $news['comment'] = dbcount("('comment_id')", DB_COMMENTS, "comment_type='n'");
    $news['submit'] = dbcount("(submit_id)", DB_SUBMISSIONS, "submit_type='n'");
}
$blog = [];
if (db_exists(DB_BLOG)) {
    $blog['blog'] = dbcount("('blog_id')", DB_BLOG);
    $blog['comment'] = dbcount("('comment_id')", DB_COMMENTS, "comment_type='b'");
    $blog['submit'] = dbcount("(submit_id)", DB_SUBMISSIONS, "submit_type='b'");
}
$photos = [];
if (db_exists(DB_PHOTOS)) {
    $photos['photo'] = dbcount("('photo_id')", DB_PHOTOS);
    $photos['comment'] = dbcount("('comment_id')", DB_COMMENTS, "comment_type='P'");
    $photos['submit'] = dbcount("(submit_id)", DB_SUBMISSIONS, "submit_type='p'");
}
$comments_type = [
    'N'  => $locale['269'],
    'D'  => $locale['268'],
    'P'  => $locale['272'],
    'A'  => $locale['270'],
    'B'  => $locale['269b'],
    'C'  => $locale['272a'],
    'PH' => $locale['261'],
];
$submit_type = [
    'n' => $locale['269'],
    'd' => $locale['268'],
    'p' => $locale['272'],
    'a' => $locale['270'],
    'l' => $locale['271'],
    'b' => $locale['269b'],
];
$link_type = [
    'N'  => fusion_get_settings("siteurl")."news.php?readmore=%s",
    'D'  => fusion_get_settings("siteurl")."downloads.php?download_id=%s",
    'P'  => fusion_get_settings("siteurl")."photogallery.php?photo_id=%s",
    'A'  => fusion_get_settings("siteurl")."articles.php?article_id=%s",
    'B'  => fusion_get_settings("siteurl")."blog.php?readmore=%s",
    'C'  => fusion_get_settings("siteurl")."viewpage.php?page_id=%s",
    'PH' => fusion_get_settings("siteurl")."photogallery.php?photo_id=%s",
];
$submit_data = [];

// Infusions count
$infusions_count = dbcount("(inf_id)", DB_INFUSIONS);
$global_infusions = [];
if ($infusions_count > 0) {
    $inf_result = dbquery("SELECT * FROM ".DB_INFUSIONS." ORDER BY inf_id ASC");
    while ($_inf = dbarray($inf_result)) {
        $global_infusions[$_inf['inf_id']] = $_inf;
    }
}
// Latest Comments
$global_comments['rows'] = dbcount("('comment_id')", DB_COMMENTS);
$_GET['c_rowstart'] = isset($_GET['c_rowstart']) && $_GET['c_rowstart'] <= $global_comments['rows'] ? $_GET['c_rowstart'] : 0;
$comments_result = dbquery("SELECT c.*, u.user_id, u.user_name, u.user_status, u.user_avatar
							FROM ".DB_COMMENTS." c LEFT JOIN ".DB_USERS." u on u.user_id=c.comment_name
							ORDER BY comment_datestamp DESC LIMIT ".$_GET['c_rowstart'].", ".$settings['comments_per_page']."
							");
if ($global_comments['rows'] > $settings['comments_per_page']) {
    $global_comments['nav'] = makepagenav($_GET['c_rowstart'], $settings['comments_per_page'], $global_comments['rows'], 2);
}
$global_comments['data'] = [];
if (dbrows($comments_result)) {
    while ($_comdata = dbarray($comments_result)) {
        $global_comments['data'][] = $_comdata;
    }
} else {
    $global_comments['nodata'] = $locale['254c'];
}
// Latest Ratings
$global_ratings['rows'] = dbcount("('rating_id')", DB_RATINGS);
$_GET['r_rowstart'] = isset($_GET['r_rowstart']) && $_GET['r_rowstart'] <= $global_ratings['rows'] ? $_GET['r_rowstart'] : 0;
$result = dbquery("SELECT r.*, u.user_id, u.user_name, u.user_status, u.user_avatar
					FROM ".DB_RATINGS." r LEFT JOIN ".DB_USERS." u on u.user_id=r.rating_user
					ORDER BY rating_datestamp DESC LIMIT ".$_GET['r_rowstart'].", ".$settings['comments_per_page']."
					");
$global_ratings['data'] = [];
if (dbrows($result) > 0) {
    while ($_ratdata = dbarray($result)) {
        $global_ratings['data'][] = $_ratdata;
    }
} else {
    $global_ratings['nodata'] = $locale['254b'];
}
if ($global_ratings['rows'] > $settings['comments_per_page']) {
    $global_ratings['ratings_nav'] = makepagenav($_GET['r_rowstart'], $settings['comments_per_page'], $global_ratings['rows'], 2);
}
// Latest Submissions
$global_submissions['rows'] = dbcount("('submit_id')", DB_SUBMISSIONS);
$_GET['s_rowstart'] = isset($_GET['s_rowstart']) && $_GET['s_rowstart'] <= $global_submissions['rows'] ? $_GET['s_rowstart'] : 0;
$result = dbquery("SELECT s.*, u.user_id, u.user_name, u.user_status, u.user_avatar
				FROM ".DB_SUBMISSIONS." s LEFT JOIN ".DB_USERS." u on u.user_id=s.submit_user
				ORDER BY submit_datestamp DESC LIMIT ".$_GET['s_rowstart'].", ".$settings['comments_per_page']."
				");
$global_submissions['data'] = [];
if (dbrows($result) > 0 && checkrights('SU')) {
    while ($_subdata = dbarray($result)) {
        $global_submissions['data'][] = $_subdata;
    }
} else {
    $global_submissions['nodata'] = $locale['254a'];
}
if ($global_submissions['rows'] > $settings['comments_per_page']) {
    $global_submissions['submissions_nav'] = "<span class='pull-right text-smaller'>".makepagenav($_GET['s_rowstart'], $settings['comments_per_page'], $global_submissions['rows'], 2)."</span>\n";
}
// Icon Grid
if (isset($_GET['pagenum']) && isnum($_GET['pagenum'])) {
    $result = dbquery("SELECT * FROM ".DB_ADMIN." WHERE admin_page='".$_GET['pagenum']."' AND admin_link !='reserved' ORDER BY admin_page DESC, admin_id ASC, admin_title ASC");
    $admin_icons['rows'] = dbrows($result);
    $admin_icons['data'] = [];
    if (dbrows($result)) {
        while ($_idata = dbarray($result)) {
            if (checkrights($_idata['admin_rights']) && $_idata['admin_link'] != "reserved") {
                // Current locale file have the admin title definitions paired by admin_rights.
                if ($_idata['admin_page'] !== 5) {
                    $_idata['admin_title'] = isset($locale[$_idata['admin_rights']]) ? $locale[$_idata['admin_rights']] : $_idata['admin_title'];
                }
                $admin_icons['data'][] = $_idata;
            }
        }
    }
}

// Update checker
$new_update = '';
if ($settings['update_checker'] == 1) {
    function get_http_response_code($url) {
        $headers = get_headers($url);
        return substr($headers[0], 9, 3);
    }

    $url = 'https://www.php-fusion.co.uk/updates/8.txt';
    if (get_http_response_code($url) == 200) {
        $file = @file_get_contents($url);
        $array = explode("\n", $file);
        $version = $array[0];

        if (version_compare($version, $settings['version'], '>')) {
            $new_update = str_replace(['[LINK]', '[/LINK]', '[VERSION]'], ['<a href="'.$array[1].'" target="_blank">', '</a>', $version], $locale['new_update_avalaible']);
        }
    }
}

render_admin_dashboard();
require_once THEMES."templates/footer.php";
