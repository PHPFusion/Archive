	opentable($locale['480']);
	$forums_defined = false;
	$forum = "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n";
	$result = dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_cat='0' ORDER BY forum_order");
	if (dbrows($result) != 0) {
		$forums_defined = true;
		$forum .= "<tr>\n<td class='tbl2'><b>".$locale['485']."</b></td>
<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><b>".$locale['486']."</b></td>
<td align='center' colspan='2' width='1%' class='tbl2' style='white-space:nowrap'><b>".$locale['487']."</b></td>
<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><b>".$locale['488']."</b></td>
</tr>\n";
		$i = 1;
		while ($data = dbarray($result)) {
			$forum .= "<tr>
<td class='tbl2' colspan='2'>".$data['forum_name']."</td>
<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>".$data['forum_order']."</td>
<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>\n";
			if (dbrows($result) != 1) {
				$up = $data['forum_order'] - 1;
				$down = $data['forum_order'] + 1;
				if ($i == 1) {
					$forum .= "<a href='".FUSION_SELF.$aidlink."&amp;action=movedown&amp;order=$down&amp;forum_id=".$data['forum_id']."&amp;t=cat'><img src='".THEME."images/down.gif' alt='".$locale['490']."' title='".$locale['492']."' style='border:0px;'></a>\n";
				} elseif ($i < dbrows($result)) {
					$forum .= "<a href='".FUSION_SELF.$aidlink."&amp;action=moveup&amp;order=$up&amp;forum_id=".$data['forum_id']."&amp;t=cat'><img src='".THEME."images/up.gif' alt='".$locale['489']."' title='".$locale['491']."' style='border:0px;'></a>\n";
					$forum .= "<a href='".FUSION_SELF.$aidlink."&amp;action=movedown&amp;order=$down&amp;forum_id=".$data['forum_id']."&amp;t=cat'><img src='".THEME."images/down.gif' alt='".$locale['490']."' title='".$locale['492']."' style='border:0px;'></a>\n";
				} else {
					$forum .= "<a href='".FUSION_SELF.$aidlink."&amp;action=moveup&amp;order=$up&amp;forum_id=".$data['forum_id']."&amp;t=cat'><img src='".THEME."images/up.gif' alt='".$locale['489']."' title='".$locale['491']."' style='border:0px;'></a>\n";
				}
			}
			$i++;
			$forum .= "</td>
<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><a href='".FUSION_SELF.$aidlink."&amp;action=edit&amp;forum_id=".$data['forum_id']."&amp;t=cat'>".$locale['481']."</a> -
<a href='".FUSION_SELF.$aidlink."&amp;action=delete&amp;forum_id=".$data['forum_id']."&amp;t=cat'>".$locale['482']."</a></td>
</tr>\n";
			$result2 = dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_cat='".$data['forum_id']."' ORDER BY forum_order");
			if (dbrows($result2) != 0) {
				$k = 1;
				while ($data2 = dbarray($result2)) {
					$forum .= "<tr>
<td class='tbl1'><span class='alt'>".$data2['forum_name']."</span><br>
<span class='small'>".$data2['forum_description']."</span></td>
<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>".getgroupname($data2['forum_access'])."<br>
<span class='small2'>".getgroupname($data2['forum_posting'])."</span></td>
<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>".$data2['forum_order']."</td>
<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>\n";
					if (dbrows($result2) != 1) {
						$up = $data2['forum_order'] - 1;
						$down = $data2['forum_order'] + 1;
						if ($k == 1) {
							$forum .= "<a href='".FUSION_SELF.$aidlink."&amp;action=movedown&amp;order=$down&amp;forum_id=".$data2['forum_id']."&amp;t=forum&amp;cat=".$data2['forum_cat']."'><img src='".THEME."images/down.gif' alt='".$locale['490']."' title='".$locale['492']."' style='border:0px;'></a>\n";
						} elseif ($k < dbrows($result2)) {
							$forum .= "<a href='".FUSION_SELF.$aidlink."&amp;action=moveup&amp;order=$up&amp;forum_id=".$data2['forum_id']."&amp;t=forum&amp;cat=".$data2['forum_cat']."'><img src='".THEME."images/up.gif' alt='".$locale['489']."' title='".$locale['491']."' style='border:0px;'></a>\n";
							$forum .= "<a href='".FUSION_SELF.$aidlink."&amp;action=movedown&amp;order=$down&amp;forum_id=".$data2['forum_id']."&amp;t=forum&amp;cat=".$data2['forum_cat']."'><img src='".THEME."images/down.gif' alt='".$locale['490']."' title='".$locale['492']."' style='border:0px;'></a>\n";
						} else {
							$forum .= "<a href='".FUSION_SELF.$aidlink."&amp;action=moveup&amp;order=$up&amp;forum_id=".$data2['forum_id']."&amp;t=forum&amp;cat=".$data2['forum_cat']."'><img src='".THEME."images/up.gif' alt='".$locale['489']."' title='".$locale['491']."' style='border:0px;'></a>\n";
						}
					}
					$k++;
					$forum .= "</td>
<td align='center' width='1%' class='tbl1' style='white-space:nowrap'><a href='".FUSION_SELF.$aidlink."&amp;action=edit&amp;forum_id=".$data2['forum_id']."&amp;t=forum'>".$locale['481']."</a> -
<a href='".FUSION_SELF.$aidlink."&amp;action=delete&amp;forum_id=".$data2['forum_id']."&amp;t=forum'>".$locale['482']."</a></td>
</tr>\n";
				}
			} else {
				$forum .= "<tr>\n<td align='center' colspan='5' class='tbl1'>".$locale['483']."</td>\n</tr>\n";
			}
		}
	} else {
		$forum .= "<tr>\n<td align='center' class='tbl1'>".$locale['484']."</td>\n</tr>\n";
	}
	echo $forum;
	if ($forums_defined) echo "<tr>\n<td align='center' colspan='5' class='tbl1'>[ <a href='".FUSION_SELF.$aidlink."&amp;action=refresh'>".$locale['493']."</a> ]</td>\n</tr>\n";
	echo "</table>\n";
