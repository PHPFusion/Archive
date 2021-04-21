<?php
/*-------------------------------------------------------+
| PHPFusion Content Management System
| Copyright (C) PHP Fusion Inc
| https://phpfusion.com/
+--------------------------------------------------------+
| Filename: user_field_cats.php
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
if (!checkrights("UFC") || !defined("iAUTH") || !isset($_GET['aid']) || $_GET['aid'] != iAUTH) {redirect("../index.php");}

require_once THEMES."templates/admin_header.php";
include LOCALE.LOCALESET."admin/user_fields-cats.php";

if (isset($_GET['status']) && !isset($message)) {
    if ($_GET['status'] == "sn") {
        $message = $locale['410'];
    } else if ($_GET['status'] == "su") {
        $message = $locale['411'];
    } else if ($_GET['status'] == "del") {
        $message = $locale['412'];
    } else if ($_GET['status'] == "deln") {
        $message = $locale['413']."<br />\n<span class='small'>".$locale['414']."</span>";
    }
    if ($message) {
        echo "<div id='close-message'><div class='admin-message alert alert-info'>".$message."</div></div>\n";
    }
}

if (isset($_GET['action']) && $_GET['action'] == "refresh") {
    $i = 1;
    $result = dbquery("SELECT field_cat_id FROM ".DB_USER_FIELD_CATS." ORDER BY field_cat_order");
    while ($data = dbarray($result)) {
        $result2 = dbquery("UPDATE ".DB_USER_FIELD_CATS." SET field_cat_order='$i' WHERE field_cat_id='".$data['field_cat_id']."'");
        $i++;
    }
    redirect(FUSION_SELF.$aidlink);
} else if ((isset($_GET['action']) && $_GET['action'] == "moveup") && (isset($_GET['cat_id']) && isnum($_GET['cat_id']))) {
    $data = dbarray(dbquery("SELECT field_cat_id FROM ".DB_USER_FIELD_CATS." WHERE field_cat_order='".intval($_GET['order'])."'"));
    $result = dbquery("UPDATE ".DB_USER_FIELD_CATS." SET field_cat_order=field_cat_order+1 WHERE field_cat_id='".$data['field_cat_id']."'");
    $result = dbquery("UPDATE ".DB_USER_FIELD_CATS." SET field_cat_order=field_cat_order-1 WHERE field_cat_id='".$_GET['cat_id']."'");
    redirect(FUSION_SELF.$aidlink);
} else if ((isset($_GET['action']) && $_GET['action'] == "movedown") && (isset($_GET['cat_id']) && isnum($_GET['cat_id']))) {
    $data = dbarray(dbquery("SELECT field_cat_id FROM ".DB_USER_FIELD_CATS." WHERE field_cat_order='".intval($_GET['order'])."'"));
    $result = dbquery("UPDATE ".DB_USER_FIELD_CATS." SET field_cat_order=field_cat_order-1 WHERE field_cat_id='".$data['field_cat_id']."'");
    $result = dbquery("UPDATE ".DB_USER_FIELD_CATS." SET field_cat_order=field_cat_order+1 WHERE field_cat_id='".$_GET['cat_id']."'");
    redirect(FUSION_SELF.$aidlink);
} else if ((isset($_GET['action']) && $_GET['action'] == "delete") && (isset($_GET['cat_id']) && isnum($_GET['cat_id']))) {
    if (!dbcount("(field_id)", DB_USER_FIELDS, "field_cat='".$_GET['cat_id']."'")) {
        $data = dbarray(dbquery("SELECT field_cat_order FROM ".DB_USER_FIELD_CATS." WHERE field_cat_id='".$_GET['cat_id']."'"));
        $result = dbquery("UPDATE ".DB_USER_FIELD_CATS." SET field_cat_order=field_cat_order-1 WHERE field_cat_order>'".$data['field_cat_order']."'");
        $result = dbquery("DELETE FROM ".DB_USER_FIELD_CATS." WHERE field_cat_id='".$_GET['cat_id']."'");
        redirect(FUSION_SELF.$aidlink."&status=del");
    } else {
        redirect(FUSION_SELF.$aidlink."&status=deln");
    }
} else {
    if (isset($_POST['savecat'])) {
        $cat_name = stripinput($_POST['cat_name'], '', 'cat_name');
        $cat_order = isnum($_POST['cat_order']) ? $_POST['cat_order'] : 0;
        $cat_class = stripinput($_POST['cat_class'], '');
        $cat_db = stripinput($_POST['cat_db'], '');
        $cat_index = stripinput($_POST['cat_index'], '');
        $cat_page = (isset($_POST['cat_page']) && $_POST['cat_page']) ? 1 : 0;
        if ((!$cat_db && !$cat_index) || ($cat_db && $cat_index)) {
            if ((isset($_GET['action']) && $_GET['action'] == "edit") && (isset($_GET['cat_id']) && isnum($_GET['cat_id']))) {
                $old_cat_order = dbresult(dbquery("SELECT field_cat_order FROM ".DB_USER_FIELD_CATS." WHERE field_cat_id='".$_GET['cat_id']."'"), 0);
                if ($cat_order > $old_cat_order) {
                    $result = dbquery("UPDATE ".DB_USER_FIELD_CATS." SET field_cat_order=field_cat_order-1 WHERE field_cat_order>'".$old_cat_order."' AND field_cat_order<='".$cat_order."'");
                } else if ($cat_order < $old_cat_order) {
                    $result = dbquery("UPDATE ".DB_USER_FIELD_CATS." SET field_cat_order=field_cat_order+1 WHERE field_cat_order<'".$old_cat_order."' AND field_cat_order>='".$cat_order."'");
                }
                $result = dbquery("UPDATE ".DB_USER_FIELD_CATS." SET field_cat_name='".$cat_name."', field_cat_db='$cat_db', field_cat_index='$cat_index', field_cat_class='$cat_class', field_cat_page='$cat_page', field_cat_order='$cat_order' WHERE field_cat_id='".$_GET['cat_id']."'");
                redirect(FUSION_SELF.$aidlink."&status=su");
            } else {
                if ($cat_order == 0) {
                    $cat_order = dbresult(dbquery("SELECT MAX(field_cat_order) FROM ".DB_USER_FIELD_CATS.""), 0) + 1;
                }
                $result = dbquery("UPDATE ".DB_USER_FIELD_CATS." SET field_cat_order=field_cat_order+1 WHERE field_cat_order>='".$cat_order."'");
                $result = dbquery("INSERT INTO ".DB_USER_FIELD_CATS." (field_cat_name, field_cat_db, field_cat_index, field_cat_class, field_cat_page, field_cat_order) VALUES ('".$cat_name."', '".$cat_db."', '".$cat_index."', '".$cat_class."', '".$cat_page."', '".$cat_order."')");
                redirect(FUSION_SELF.$aidlink."&status=sn");
            }
        } else {
            redirect(FUSION_SELF.$aidlink);
        }
    }
    if ((isset($_GET['action']) && $_GET['action'] == "edit") && (isset($_GET['cat_id']) && isnum($_GET['cat_id']))) {
        $result = dbquery("SELECT * FROM ".DB_USER_FIELD_CATS." WHERE field_cat_id='".$_GET['cat_id']."'");
        if (dbrows($result)) {
            $data = dbarray($result);
            $cat_name = $data['field_cat_name'];
            $cat_db = $data['field_cat_db'];
            $cat_index = $data['field_cat_index'];
            $cat_class = $data['field_cat_class'];
            $cat_order = $data['field_cat_order'];
            $cat_page = $data['field_cat_page'];
            $formaction = FUSION_SELF.$aidlink."&amp;action=edit&amp;cat_id=".$data['field_cat_id'];
            opentable($locale['401']);
        } else {
            redirect(FUSION_SELF.$aidlink);
        }
    } else {
        $cat_name = "";
        $cat_order = "";
        $cat_db = "";
        $cat_index = "";
        $cat_class = "";
        $cat_page = "";
        $formaction = FUSION_SELF.$aidlink;
        opentable($locale['400']);
    }

    echo "<form name='layoutform' method='post' action='".$formaction."'>\n";
    echo "<table cellpadding='0' cellspacing='0' class='table table-responsive center'><tbody>\n<tr>\n";
    echo "<td class='tbl' width='1%' style='white-space:nowrap'><label for='cat_name'>".$locale['420'].":</label> <span class='required'>*</span></td>\n";
    echo "<td class='tbl'>\n";
    echo "<input type='text' name='cat_name' value='".$cat_name."' maxlength='100' class='textbox' style='width:240px;' />\n";
    echo "</td>\n</tr>\n<tr>\n";
    echo "<td class='tbl' width='1%' style='white-space:nowrap'><label for='cat_db'>".$locale['423'].":</label></td>\n";
    echo "<td class='tbl'>\n";
    echo "<input type='text' name='cat_db'  value='".$cat_db."' maxlength='100' class='textbox' style='width:240px;' />\n";
    echo "</td>\n</tr>\n<tr>\n";
    echo "<td class='tbl' width='1%' style='white-space:nowrap'><label for='cat_index'>".$locale['428'].":</label></td>\n";
    echo "<td class='tbl'>\n";
    echo "<input type='text' name='cat_index'  value='".$cat_index."' maxlength='100' class='textbox' style='width:240px;' />\n";
    echo "</tr>\n<tr>\n";
    echo "<td class='tbl' width='1%' style='white-space:nowrap'><label for='cat_class'>".$locale['425'].":</label></td>\n";
    echo "<td class='tbl'>\n";
    echo "<input type='text' name='cat_class'  value='".$cat_class."' maxlength='100' class='textbox' style='width:240px;' />\n";
    echo "</td>\n</tr>\n<tr>\n";
    echo "<td class='tbl' width='1%' style='white-space:nowrap'><label for='cat_order'>".$locale['421'].":</label></td>\n";
    echo "<td class='tbl'>\n";
    echo "<input type='text' name='cat_order'  value='".$cat_order."' maxlength='3' class='textbox' style='width:240px;' />\n";
    echo "</tr>\n<tr>\n";
    echo "<td class='tbl' width='1%' style='white-space:nowrap'><label for='cat_page'>".$locale['426'].":</label></td>\n";
    echo "<td class='tbl'><input type='checkbox' name='cat_page'  value='1' class='input-xs' ".($cat_page ? 'checked' : '')." /> ".$locale['427']."</td>\n";
    echo "</tr>\n<tr>\n";
    echo "<td align='center' colspan='2' class='tbl'>\n";
    echo "<input type='submit' name='savecat' value='".$locale['422']."' class='button' /></td>\n";
    echo "</td>\n</tr>\n</tbody>\n</table>\n</form>\n";
    closetable();
    opentable($locale['402']);
    echo "<table cellpadding='0' cellspacing='1' class='table table-responsive center'>\n<thead>\n<tr>\n";
    echo "<th class='tbl2'><strong>".$locale['420']."</strong></th>\n";
    echo "<th align='center' width='1%' class='tbl2' style='white-space:nowrap'><strong>".$locale['435']."</strong></th>\n";
    echo "<th align='center' colspan='2' width='1%' class='tbl2' style='white-space:nowrap'><strong>".$locale['430']."</strong></th>\n";
    echo "<th align='center' width='1%' class='tbl2' style='white-space:nowrap'><strong>".$locale['431']."</strong></th>\n";
    echo "</tr>\n</thead>\n<tbody>";
    $result = dbquery("SELECT * FROM ".DB_USER_FIELD_CATS." ORDER BY field_cat_order");
    if (dbrows($result)) {
        $i = 0;
        $k = 1;
        while ($data = dbarray($result)) {
            $row_color = ($i % 2 == 0 ? "tbl1" : "tbl2");
            echo "<tr>\n<td class='".$row_color."'>".$data['field_cat_name']."</td>\n";
            echo "<td width='1%' class='".$row_color."' style='white-space:nowrap'>".($data['field_cat_page'] ? $data['field_cat_name'] : $locale['436'])."</td>\n";
            echo "<td align='center' width='1%' class='".$row_color."' style='white-space:nowrap'>".$data['field_cat_order']."</td>\n";
            echo "<td align='center' width='1%' class='".$row_color."' style='white-space:nowrap'>\n";
            if (dbrows($result) != 1) {
                $up = $data['field_cat_order'] - 1;
                $down = $data['field_cat_order'] + 1;
                if ($k == 1) {
                    echo "<a href='".FUSION_SELF.$aidlink."&amp;action=movedown&amp;order=$down&amp;cat_id=".$data['field_cat_id']."'><img src='".get_image("down")."' alt='".$locale['441']."' title='".$locale['443']."' style='border:0px;' /></a>\n";
                } else if ($k < dbrows($result)) {
                    echo "<a href='".FUSION_SELF.$aidlink."&amp;action=moveup&amp;order=$up&amp;cat_id=".$data['field_cat_id']."'><img src='".get_image("up")."' alt='".$locale['440']."' title='".$locale['442']."' style='border:0px;' /></a>\n";
                    echo "<a href='".FUSION_SELF.$aidlink."&amp;action=movedown&amp;order=$down&amp;cat_id=".$data['field_cat_id']."'><img src='".get_image("down")."' alt='".$locale['441']."' title='".$locale['443']."' style='border:0px;' /></a>\n";
                } else {
                    echo "<a href='".FUSION_SELF.$aidlink."&amp;action=moveup&amp;order=$up&amp;cat_id=".$data['field_cat_id']."'><img src='".get_image("up")."' alt='".$locale['440']."' title='".$locale['442']."' style='border:0px;' /></a>\n";
                }
            }
            $k++;
            echo "</td>\n";
            echo "<td align='center' width='1%' class='".$row_color."' style='white-space:nowrap'><a href='".FUSION_SELF.$aidlink."&amp;action=edit&amp;cat_id=".$data['field_cat_id']."'>".$locale['432']."</a> -\n";
            echo "<a href='".FUSION_SELF.$aidlink."&amp;action=delete&amp;cat_id=".$data['field_cat_id']."' onclick=\"return confirm('".$locale['450']."');\">".$locale['433']."</a></td>\n";
            echo "</tr>\n";
            $i++;
        }
    } else {
        echo "<tr>\n<td align='center' colspan='3' class='tbl1'>".$locale['434']."</td>\n</tr>\n";
    }
    echo "</tbody>\n</table>\n";
    if (dbrows($result)) {
        echo "<div style='text-align:center;margin-top:5px'><a class='btn btn-primary' href='".FUSION_SELF.$aidlink."&amp;action=refresh'>".$locale['444']."</a></div>\n";
    }
    closetable();
}

require_once THEMES."templates/footer.php";
