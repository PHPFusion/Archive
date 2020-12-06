<?php
// Member Management Options
$locale['400'] = "會員管理";
$locale['401'] = "帳號";
$locale['402'] = "新增";
$locale['403'] = "會員類型";
$locale['404'] = "選項";
$locale['405'] = "觀看";
$locale['406'] = "編輯";
$locale['407'] = "取消禁止";
$locale['408'] = "禁止";
$locale['409'] = "刪除";
$locale['410'] = "沒有會員帳號名稱開始為 ";
$locale['411'] = "顯示全部";
$locale['412'] = "啟用會員資格";
// Ban/Unban/Delete Member
$locale['420'] = "會員已被禁止";
$locale['421'] = "禁止已被取消";
$locale['422'] = "會員已被刪除";
$locale['423'] = "你確定要刪除這位會員嗎?";
$locale['424'] = "會員資格已被啟用";
$locale['425'] = "會員資格啟用: ";
$locale['426'] = "哈囉 [USER_NAME]，\n你在 ".$settings['sitename']." 的帳號已經被啟用。\n你已經可以用註冊的帳號和密碼登入本站。\n祝安康，".$settings['siteusername'];
// Edit Member Details
$locale['430'] = "編輯會員資料";
$locale['431'] = "會員資料已更新";
$locale['432'] = "回到會員管理";
$locale['433'] = "回到控制台首頁";
$locale['434'] = "無法更新會員資料:";
// Extra Edit Member Details form options
$locale['440'] = "儲存更新";
// Update Profile Errors
$locale['450'] = "不能編輯超級管理員資料。";
$locale['451'] = "你必須提供一組帳號名稱以及電子信箱。";
$locale['452'] = "帳號內含有無效字元。";
$locale['453'] = "帳號 ".(isset($_POST['user_name']) ? $_POST['user_name'] : "")." 已被註冊使用。";
$locale['454'] = "無效的電子信箱。";
$locale['455'] = "電子信箱 ".(isset($_POST['user_email']) ? $_POST['user_email'] : "")." 已被註冊使用。";
$locale['456'] = "兩組密碼不同。";
$locale['457'] = "無效的密碼，你只能使用英數字元。<br>密碼必須至少六位元長。";
$locale['458'] = "<b>警告:</b> 未預期的程式執行。";
// View Member Profile
$locale['470'] = "會員資料: ";
$locale['471'] = "基本資料";
$locale['472'] = "統計";
$locale['473'] = "群組";
// Add Member Errors
$locale['480'] = "新增會員";
$locale['481'] = "會員帳號已成功新增。";
$locale['482'] = "無法新增此會員帳號。";
?>
