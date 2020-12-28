<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) PHP-Fusion Inc
| https://www.phpfusion.com/
+--------------------------------------------------------+
| Filename: site_links_updater.php
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
if (!checkrights("SL") || !defined("iAUTH") || !isset($_GET['aid']) || $_GET['aid'] != iAUTH) {redirect("../index.php");}

include LOCALE.LOCALESET."admin/sitelinks.php";

if (isset($_GET['listItem']) && is_array($_GET['listItem'])) {
    foreach ($_GET['listItem'] as $position => $item) {
        if (isnum($position) && isnum($item)) {
            dbquery("UPDATE ".DB_SITE_LINKS." SET link_order='".($position + 1)."' WHERE link_id='".$item."'");
        }
    }
    header("Content-Type: text/html; charset=".$locale['charset']."\n");
    echo "<div id='close-message'><div class='admin-message'>".$locale['455']."</div></div>";
}
