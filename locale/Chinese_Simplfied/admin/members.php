<?php
// Member Management Options
$locale['400'] = "会员管理";
$locale['401'] = "帐号";
$locale['402'] = "新增";
$locale['403'] = "会员类型";
$locale['404'] = "选项";
$locale['405'] = "观看";
$locale['406'] = "编辑";
$locale['407'] = "取消禁止";
$locale['408'] = "禁止";
$locale['409'] = "删除";
$locale['410'] = "没有会员帐号名称开始为 ";
$locale['411'] = "显示全部";
$locale['412'] = "启用会员资格";
// Ban/Unban/Delete Member
$locale['420'] = "会员已被禁止";
$locale['421'] = "禁止已被取消";
$locale['422'] = "会员已被删除";
$locale['423'] = "你确定要删除这位会员吗?";
$locale['424'] = "会员资格已被启用";
$locale['425'] = "会员资格启用: ";
$locale['426'] = "哈啰 [USER_NAME]，\n你在 ".$settings['sitename']." 的帐号已经被启用。\n你已经可以用注册的帐号和密码登入本站。\n祝安康，".$settings['siteusername'];
// Edit Member Details
$locale['430'] = "编辑会员资料";
$locale['431'] = "会员资料已更新";
$locale['432'] = "回到会员管理";
$locale['433'] = "回到控制台首页";
$locale['434'] = "无法更新会员资料:";
// Extra Edit Member Details form options
$locale['440'] = "储存更新";
// Update Profile Errors
$locale['450'] = "不能编辑超级管理员资料。";
$locale['451'] = "你必须提供一组帐号名称以及电子信箱。";
$locale['452'] = "帐号内含有无效字元。";
$locale['453'] = "帐号 ".(isset($_POST['user_name']) ? $_POST['user_name'] : "")." 已被注册使用。";
$locale['454'] = "无效的电子信箱。";
$locale['455'] = "电子信箱 ".(isset($_POST['user_email']) ? $_POST['user_email'] : "")." 已被注册使用。";
$locale['456'] = "两组密码不同。";
$locale['457'] = "无效的密码，你只能使用英数字元。<br>密码必须至少六位元长。";
$locale['458'] = "<b>警告:</b> 未预期的程式执行。";
// View Member Profile
$locale['470'] = "会员资料: ";
$locale['471'] = "基本资料";
$locale['472'] = "统计";
$locale['473'] = "群组";
// Add Member Errors
$locale['480'] = "新增会员";
$locale['481'] = "会员帐号已成功新增。";
$locale['482'] = "无法新增此会员帐号。";
?>
