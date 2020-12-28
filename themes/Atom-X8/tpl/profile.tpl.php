<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) PHP-Fusion Inc
| https://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: profile.tpl.php
| Author: Hien (Frederick MC Chan)
| Author: Falk (Joakim Falk)
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/

// Atom Rrouter.
function atom_profile() {
    global $userdata, $settings, $locale;
    $html = '';
    if (isset($_GET['lookup']) && isnum($_GET['lookup'])) {
        return user_profile_page();
    } else if (isset($_GET['group_id']) && isnum($_GET['group_id'])) {
        return user_groups_page();
    } else {
        if (iMEMBER) {
            ob_start();
            echo "<div class='row m-t-15'><div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>";
            include BASEDIR."edit_profile.php";
            $profile_page = ob_get_contents();
            echo "</div></div>";
            ob_end_clean();

            $html .= "<section id='greybody p-15'>\n";
            $html .= $profile_page;
            $html .= "</section>\n";
            return $html;
        } else {
            redirect(BASEDIR."home.php");
        }
    }
}

function user_profile_page() {
    global $locale, $settings, $userdata, $aidlink;
    $user_data = $userdata;
    add_to_head("<link href='".THEME."tpl/tpl_css/profile.css' rel='stylesheet' media='screen'>");
    $html = '';

    // member profile page.
    if (iMEMBER) {
        ($_GET['lookup'] == $userdata['user_id']) ? define('PAGE_OWNER', TRUE) : define('PAGE_OWNER', FALSE);
    } else {
        define('PAGE_OWNER', FALSE);
    }
    if (isset($_GET['lookup']) && isnum($_GET['lookup'])) {
        $user_status = " AND (user_status='0' OR user_status='3' OR user_status='7')";
        if (iADMIN) {
            $user_status = "";
        }
        $result = dbquery(
            "SELECT u.*, s.suspend_reason
            FROM ".DB_USERS." u
            LEFT JOIN ".DB_SUSPENDS." s ON u.user_id=s.suspended_user
            WHERE user_id='".$_GET['lookup']."'".$user_status."
            ORDER BY suspend_date DESC
            LIMIT 1"
        );

        if (dbrows($result) > 0) {
            $user_data = dbarray($result);
        } else {
            redirect("index.php");
        }

        // add to group.
        if (iADMIN && checkrights("UG") && $_GET['lookup'] != $userdata['user_id']) {
            if ((isset($_POST['add_to_group'])) && (isset($_POST['user_group']) && isnum($_POST['user_group']))) {
                if (!preg_match("(^\.{$_POST['user_group']}$|\.{$_POST['user_group']}\.|\.{$_POST['user_group']}$)", $user_data['user_groups'])) {
                    $result = dbquery("UPDATE ".DB_USERS." SET user_groups='".$user_data['user_groups'].".".$_POST['user_group']."' WHERE user_id='".$_GET['lookup']."'");
                }
                redirect(FUSION_SELF."?lookup=".$user_data['user_id']);
            }
        }

        // header
        $html .= "<section id='maincontent' class='profile-header' style='padding-bottom:80px;'>\n";
        $html .= "<div class='row'>\n";
        $html .= "<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>\n";
        $html .= "<h3 style='margin-bottom:0px;'>".$user_data['user_name'].$locale['ax8_29']."</h3>\n";
        $lastVisit = ($user_data['user_lastvisit']) ? timer($user_data['user_lastvisit']) : $locale['u042'];
        $html .= "<div class='row'><div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>\n";
        $html .= "<div><span>".$locale['u066']." : ".showdate("longdate", $user_data['user_joined'])."</span><br>
        <span>".$locale['u067']." : $lastVisit</span>\n</div>\n";
        $html .= "</div>\n<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>\n";
        $html .= "".($user_data['user_hide_email'] == 1 ? "<span>".$locale['ax8_03']."</span>" : "<a href='mailto:".$user_data['user_email']."'>".$user_data['user_email']."</a>")."<br>\n";
        $html .= "</div>\n</div>\n";
        $html .= "</div>\n<div class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>\n";
        if (iMEMBER && $userdata['user_id'] != $user_data['user_id']) {
            $html .= "<div class='btn-group m-t-20'>\n";
            $html .= "<a class='btn btn-sm btn-primary' href='".BASEDIR."messages.php?msg_send=".$user_data['user_id']."' title='".$locale['u043']."'>".$locale['u043']."</a>\n";
            if (iADMIN && checkrights("M") && $user_data['user_level'] != "103" && $user_data['user_id'] != "1") {
                $html .= "<a class='btn btn-sm btn-primary' href='".ADMIN."members.php".$aidlink."&amp;step=log&amp;user_id=".$user_data['user_id']."'>".$locale['ax8_04']."</a>";
            }
            $html .= "</div>\n";
        }
        $html .= "</div>\n";
        $html .= "</div>\n</section>\n";

        // end header
        $html .= "<section id='mainbody m-t-20'>\n";
        $html .= '<script type="text/javascript">
            $(function () {
                if(window.location.hash){
                $("a[href="+window.location.hash+"]").click();
            }
        });
        </script>';


        $html .= "<div class='row'>\n";
        $html .= "<div class='col-xs-12 col-sm-9 col-md-9 col-lg-9'>\n";
        $tab_title['title'][] = $locale['ax8_05'];
        $tab_title['id'][] = "1-";
        $tab_title['icon'][] = "";

        $tab_active = tab_active($tab_title, 0);
        $html .= "<div class='profile-main'>\n";
        $html .= opentab($tab_title, $tab_active, 'main-pages');
        // Overview.
        $html .= opentabbody($tab_title['title']['0'], '1-', $tab_active);
        // Forum Dashboard Management.
        $html .= "<div class='row'>\n";
        $html .= "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>\n";
        $html .= "<div class='panel panel-default'>\n";
        $html .= "<div class='panel-heading' style='background:#222; color:#fff'>".$locale['ax8_06']."</div>\n";
        $html .= "<div class='panel-body'>\n";
        $html .= "<div class='row'>\n";
        $html .= "<div class='col-xs-1 col-sm-1 col-md-1 col-lg-1'>\n";
        $html .= display_avatar($user_data, '50px');
        $html .= "</div><div class='col-xs-11 col-sm-11 col-md-11 col-lg-11'>\n";

        $html .= "<div class='row'>\n";
        $html .= "<div class='col-xs-12 col-sm-4 col-md-4 col-lg-4'><strong>".$locale['u127']."</strong></div>\n";
        $html .= "<div class='col-xs-12 col-sm-8 col-md-8 col-lg-8'>".$user_data['user_name']."</div>\n";
        $html .= "</div>\n";

        $html .= "<div class='row'>\n";
        $html .= "<div class='col-xs-12 col-sm-4 col-md-4 col-lg-4'><strong>".$locale['u128']."</strong></div>\n";
        $html .= "<div class='col-xs-12 col-sm-8 col-md-8 col-lg-8'>".($user_data['user_hide_email'] == 1 ? "<span>".$locale['ax8_03']."</span>" : "<a href='mailto:".$user_data['user_email']."'>".$user_data['user_email']."</a>")."</div>\n";
        $html .= "</div>\n";

        if (PAGE_OWNER) {
            $html .= "<div class='row'>\n";
            $html .= "<div class='col-xs-12 col-sm-4 col-md-4 col-lg-4'><strong>".$locale['u051']."</strong></div>\n";
            $html .= "<div class='col-xs-12 col-sm-8 col-md-8 col-lg-8'>".($userdata['user_hide_email'] == '1' ? $locale['ax8_07'] : $locale['ax8_08'])."</div>\n";
            $html .= "</div>\n";
        }

        $html .= "<div class='row'>\n";
        $html .= "<div class='col-xs-12 col-sm-4 col-md-4 col-lg-4'><strong>".$locale['u063']."</strong></div>\n";
        $html .= "<div class='col-xs-12 col-sm-8 col-md-8 col-lg-8'>".getuserlevel($user_data['user_level'])."</div>\n";
        $html .= "</div>\n";

        $html .= "<div class='row'>\n";
        $html .= "<div class='col-xs-12 col-sm-4 col-md-4 col-lg-4'><strong>".$locale['u057']."</strong></div>\n";
        $html .= "<div class='col-xs-12 col-sm-8 col-md-8 col-lg-8'>\n";
        $user_groups = strpos($user_data['user_groups'], ".") == 0 ? substr($user_data['user_groups'], 1) : $user_data['user_groups'];
        $user_groups = explode(".", $user_groups);
        if (!empty($user_groups['0'])) {
            for ($i = 0; $i < count($user_groups); $i++) {
                $html .= "<p><span><a href='".FUSION_SELF."?group_id=".$user_groups[$i]."'>".getgroupname($user_groups[$i])."</a></span> : ".getgroupname($user_groups[$i], TRUE)."</p>\n";
            }
        } else {
            $html .= "<p><span>".$locale['ax8_09']."</p>\n";
        }
        $html .= "</div>\n";
        $html .= "</div>\n";

        $html .= "<div class='btn-group pull-left m-r-10'>\n";
        $html .= (PAGE_OWNER) ? "<a href='".BASEDIR."edit_profile.php' class='btn  m-t-10 btn-primary btn-sm'>".$locale['ax8_10']."</a>\n" : '';
        $html .= "<button class='btn btn-sm btn-primary m-t-10' id='show-uf' type='button'>".$locale['ax8_11']."</button>\n";
        $html .= "</div>\n";

        $html .= "</div></div>\n";
        $html .= "</div>\n</div>\n";
        $html .= "</div>\n</div>\n";

        // Go for user fields.

        $html .= "<div id='extra-info' style='display:none;'>\n";
        $html .= "<div class='row'>\n";
        $uf_query = dbquery(
            "SELECT * FROM ".DB_USER_FIELDS." tuf
            INNER JOIN ".DB_USER_FIELD_CATS." tufc ON tuf.field_cat = tufc.field_cat_id
            ORDER BY field_cat_order, field_order"
        );
        $i = 0;

        $cats = [];
        $fields = [];
        if (dbrows($uf_query)) {
            while ($data = dbarray($uf_query)) {
                if ($i != $data['field_cat']) {
                    $i = $data['field_cat'];
                    $cats[$i] = [
                        "field_cat_name" => $data['field_cat_name'],
                        "field_cat"      => $data['field_cat']
                    ];
                }
                $fields[$i][] = (array_key_exists($data['field_name'], $user_data)) ? ['field_name' => $data['field_name'], 'value' => $user_data[$data['field_name']]] : ['field_name' => $data['field_name'], 'value' => ''];
            }
        }
        $i = 0;
        foreach ($cats as $user_field_cats) {
            $field_cat = $user_field_cats['field_cat'];
            if (isset($fields[$field_cat])) {
                $html .= ($i == 2) ? "</div><div class='row'>\n" : "";
                $html .= "<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>\n";
                $html .= "<!---start aside panel-->\n";
                $html .= "<div class='panel panel-default'>\n";
                $html .= "<div class='panel-heading' style='background:#222; color:#fff; text-transform:uppercase'>".$user_field_cats['field_cat_name']."</div>\n";
                $html .= "<div class='panel-body'>\n";

                foreach ($fields[$field_cat] as $field_value) {
                    $value = $locale['ax8_12'];

                    //print_p($field_value);

                    $field_replacement = str_replace('user_', 'uf_', $field_value['field_name']);
                    $title = $locale[$field_replacement];
                    if ($field_value['field_name'] == 'user_forum-stat') {
                        $value = number_format($user_data['user_posts']);
                    } else if ($field_value['field_name'] == 'user_comments-stat') {
                        $value = number_format(dbcount("('comment_id')", DB_COMMENTS, "comment_name='".$user_data['user_id']."'"));
                    } else if ($field_value['field_name'] == 'user_shouts-stat') {
                        $check_shoutbox = dbquery("SELECT * FROM ".DB_INFUSIONS." WHERE inf_folder='shoutbox_panel'");
                        if (dbrows($check_shoutbox) > 0) {
                            $value = number_format(dbcount("('shout_id')", DB_SHOUTBOX, "shout_name='".$user_data['user_id']."'"));
                        }
                    } else if ($field_value['field_name'] == 'user_web') {
                        if ($field_value['value'] && iMEMBER) {
                            $value = "<a href='".$field_value['value']."' target='_blank' >".$field_value['value']."</a>\n";
                        }
                    } else if ($field_value['field_name'] == 'user_birthdate') {
                        if ($field_value['value'] !== '1900-01-01') {
                            $bday = explode("-", $field_value['value']);
                            $value = "".$bday['2']."-".$bday['1']."-".$bday['0']."";
                        }
                    } else if ($field_value['field_name'] == 'user_sig') {

                        $value = ($field_value['value'] && iMEMBER) ? parseubb(parsesmileys($field_value['value'])) : '';
                    } else {
                        $value = $field_value['value'];
                    }

                    $html .= "<div class='row'>\n";
                    $html .= "<div class='col-xs-12 col-sm-4 col-md-4 col-lg-4'><strong>$title</strong>:</div> ";
                    $html .= "<div class='col-xs-12 col-sm-8 col-md-8 col-lg-8'>".($value ? $value : $locale['ax8_12'])."</div>\n";
                    $html .= "</div>\n";
                }
                $html .= "</div></div>\n";
                $html .= "<!---end aside panel-->\n";
                $html .= "</div>\n";
                $i++;
            }

        }
        $html .= "</div>\n</div>\n";

        add_to_footer("
            <script type='text/javascript'>
            $('#show-uf').click(function () {

                var text = $(this).text();
                $('#extra-info').slideDown(400);
                if (text == '".$locale['ax8_11']."') {
                    $(this).text('".$locale['ax8_16']."');
                } else {
                    $(this).text('".$locale['ax8_11']."');
                }
            });
            </script>
        ");

        // Everyone's Activites.
        $timeline = [];
        if (iMEMBER) {
            function setTimezoneByOffset($offset) {
                $testTimestamp = time();
                date_default_timezone_set('UTC');
                $testLocaltime = localtime($testTimestamp, TRUE);
                $testHour = $testLocaltime['tm_hour'];
                $abbrarray = timezone_abbreviations_list();
                foreach ($abbrarray as $abbr) {

                    foreach ($abbr as $city) {
                        date_default_timezone_set($city['timezone_id']);
                        $testLocaltime = localtime($testTimestamp, TRUE);
                        $hour = $testLocaltime['tm_hour'];
                        $testOffset = $hour - $testHour;
                        if ($testOffset == $offset) {
                            return TRUE;
                        }
                    }
                }
                return FALSE;
            }

            $forum_activities = dbquery("SELECT a.*, b.thread_subject FROM ".DB_POSTS." a
             LEFT JOIN ".DB_THREADS." b on (b.thread_id=a.thread_id)
             LEFT JOIN ".DB_FORUMS." c on (c.forum_id=a.forum_id)
             WHERE a.post_author='".$user_data['user_id']."' AND ".groupaccess('forum_access')." ORDER BY a.post_id DESC, a.post_edittime DESC LIMIT 20
             ");
            while ($fdata = dbarray($forum_activities)) {
                $forum_item = "<label class='label label-primary'>".$locale['ax8_17']."</label> ".$locale['ax8_18']." <a href='".FORUM."viewthread.php?thread_id=".$fdata['thread_id']."&pid=".$fdata['post_id']."#post_".$fdata['post_id']."'><strong>".$fdata['thread_subject']."</strong></a>\n";
                if ($fdata['post_edittime'] > 0 && $fdata['post_edituser'] == $userdata['user_id']) {
                    $time = $fdata['post_edittime'];
                    $message = "<img src='".THEME_IMG."icons/uf2.png' style='max-width:24px;'> ".display_avatar($user_data, '25px')." <label class='label label-default'><a style='color:#fff;' href='".BASEDIR."profile.php?lookup=".$user_data['user_id']."'>".$user_data['user_name']."</a></label> ".$locale['ax8_19']." $forum_item";
                } else {
                    $time = $fdata['post_datestamp'];
                    $message = "<img src='".THEME_IMG."icons/uf2.png' style='max-width:24px;'> ".display_avatar($user_data, '25px')." <label class='label label-default'><a style='color:#fff;' href='".BASEDIR."profile.php?lookup=".$user_data['user_id']."'>".$user_data['user_name']."</a></label> ".$locale['ax8_20']." $forum_item";
                }
                $timeline[$time][] = $message;
            }
            setTimezoneByOffset($userdata['user_offset']);
        }

        krsort($timeline); // need this to merge by days.
        function timeline_format($time) {
            global $userdata, $locale;
            // offsets
            if ($time >= strtotime("today 00:00")) {
                return date("g:i A", $time);
            } else if ($time >= strtotime("yesterday 00:00")) {
                return $locale['ax8_21'];
            } else if ($time >= strtotime("-6 day 00:00")) {
                return $locale['ax8_22'];
            } else if ($time >= strtotime("-13 day 00:00")) {
                return $locale['ax8_23'];
            } else if ($time >= strtotime("-20 day 00:00")) {
                return $locale['ax8_24'];
            } else if ($time >= strtotime("-29 day 00:00")) {
                return $locale['ax8_25'];
            } else {
                return date("M j, Y", $time);
            }
        }

        $_timeline = [];
        if (iMEMBER) {
            foreach ($timeline as $timestamp => $item) {
                $format = timeline_format($timestamp);
                if (strpos($format, 'AM') || strpos($format, 'PM')) {
                    $_timeline['0'][$format][] = $item;
                } else if ($format == $locale['ax8_21']) {
                    $_timeline['1'][$format][] = $item;
                } else if ($format == $locale['ax8_22']) {
                    $_timeline['3'][$format][] = $item;
                } else if ($format == $locale['ax8_23']) {
                    $_timeline['4'][$format][] = $item;
                } else if ($format == $locale['ax8_24']) {
                    $_timeline['5'][$format][] = $item;
                } else if ($format == $locale['ax8_25']) {
                    $_timeline['6'][$format][] = $item;
                } else {
                    $_timeline['2'][$format][] = $item;
                }
            }
        }

        $html .= "<div class='row'><div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>";
        $html .= "<h4 style='font-weight:bold !important;'>".ucfirst($user_data['user_name']).$locale['ax8_26']."</h4>";
        if (!empty($_timeline)) {
            foreach ($_timeline as $timeline_cats) {
                foreach ($timeline_cats as $time => $t_item) {
                    $html .= "<h6 style='font-weight:bold !important;'>$time</h6>";
                    $i = 0;
                    foreach ($t_item as $ts) {
                        foreach ($ts as $news_item) {
                            $html .= "<div class='well' style='".($i > 0 ? 'border-top:1px solid #eee;' : '')." padding:8px 0px;'>$news_item</div>\n";
                            $i++;
                        }
                    }
                }
            }
        } else {
            if (iMEMBER) {
                $html .= "<div class='well text-center'>\n ".$user_data['user_name']." ".$locale['ax8_27']."</div>\n";
            } else {
                $html .= "<div class='well text-center'>\n ".$locale['ax8_28']."</div>\n";
            }

        }
        $html .= "</div>\n</div>\n";

        $html .= closetabbody();
        $html .= closetab();
        $html .= "</div>\n";

        //------ START RIGHT SIDEBAR
        $html .= "</div><div class='col-xs-12 col-sm-3 col-md-3 col-lg-3'>\n";

        // Quick Main Links
        $html .= profile_point_of_interest();
        //$html .= user_last_seen();

        // End sidebar.
        $html .= "</div>\n</div>\n";
        $html .= "</div>\n</section>\n";

        return $html;

    }
}

function profile_point_of_interest() {
    global $user_data, $aidlink, $settings, $locale;
    $html = '';

    $html .= "<div class='panel panel-default'>\n<div class='panel-body'>\n";
    $html .= "<p class='m-b-10'><span class='m-r-10'><img src='".THEME_IMG."icons/acc2.png'></span><strong>".$locale['ax8_30']."</strong></p>\n";

    $html .= "<ul class='profile-side-ul'>\n";
    $html .= "<li><span class='m-r-10'><img src='".THEME_IMG."icons/mail2.png'></span><a href='".BASEDIR."messages.php'>".$locale['ax8_31']."</a></li>\n";
    $html .= "<li><span class='m-r-10'><img src='".THEME_IMG."icons/uf2.png'></span><a href='".FORUM."'>".$locale['ax8_32']."</a></li>\n";
    $html .= "<li><span class='m-r-10'><img src='".THEME_IMG."icons/acc2.png'></span><a href='".BASEDIR."edit_profile.php'>".$locale['ax8_33']."</a></li>\n";
    $html .= "<li><span class='m-r-10'><img src='".THEME_IMG."icons/news.png'></span><a href='".BASEDIR."news.php'>".$settings['sitename']." ".$locale['ax8_34']."</a></li>\n";
    $html .= "<li><span class='m-r-10'><img src='".THEME_IMG."icons/news.png'></span><a href='".BASEDIR."blog.php'>".$settings['sitename']." ".$locale['ax8_35']."</a></li>\n";
    $html .= "<li><span class='m-r-10'><img src='".THEME_IMG."icons/groups.png'></span><a href='".BASEDIR."members.php'>".$locale['ax8_36']."</a></li>\n";
    $html .= "<li><span class='m-r-10'><img src='".THEME_IMG."icons/mark.png'></span><a href='".BASEDIR."search.php'>".$locale['ax8_37']."</a></li>\n";
    $html .= "</ul>\n";

    $html .= "</div>\n</div>\n";
    return $html;
}

// User Groups - Start modding your group page here.
function user_groups_page() {
    $html = '';
    if (isset($_GET['group_id']) && isnum($_GET['group_id'])) {
        $html .= "<section id='mainbody'>\n";
        $html .= CONTENT;
        $html .= "</section>\n";
    } else {
        redirect("index.php");
    }
    return $html;
}
