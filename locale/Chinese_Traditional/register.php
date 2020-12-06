<?php
$locale['400'] = "註冊";
$locale['401'] = "啟用帳號";
// Registration Errors
$locale['402'] = "你必須提供一組帳號、密碼和電子信箱。";
$locale['403'] = "帳號內含有無效字元。";
$locale['404'] = "兩組密碼不同。";
$locale['405'] = "無效的密碼，你只能使用英數字元。<br>密碼必須至少六位元長。";
$locale['406'] = "你給的電子信箱似乎是無效的。";
$locale['407'] = "很抱歉，你選擇的帳號 ".(isset($_POST['username']) ? $_POST['username'] : "")." 已被註冊使用。";
$locale['408'] = "很抱歉，你選擇的電子信箱 ".(isset($_POST['email']) ? $_POST['email'] : "")." 已被註冊使用。";
$locale['409'] = "已經有一個尚未開通的帳號使用了這個電子信箱。";
$locale['410'] = "你輸入的確認代碼錯誤。";
$locale['411'] = "你輸入的電子信箱或信箱的網域在黑名單中。";
// Email Message
$locale['450'] = "哈囉 ".(isset($_POST['username']) ? $_POST['username'] : "").",\n
歡迎來到 ".$settings['sitename'].". 以下是你的登入資料:\n
帳號: ".(isset($_POST['username']) ? $_POST['username'] : "")."
密碼: ".(isset($_POST['password1']) ? $_POST['password1'] : "")."\n
請使用以下連結來啟用你的帳號:\n";
// Registration Success/Fail
$locale['451'] = "註冊程序已完成";
$locale['452'] = "你現在可以登入本站了。";
$locale['453'] = "你的帳號將在稍後由管理員來啟動。";
$locale['454'] = "註冊程序即將完成，你將會收到一封包含你的註冊資料的電子郵件，裡面將有啟用這個帳號的連結。";
$locale['455'] = "你的帳號已經被啟用。";
$locale['456'] = "註冊失敗";
$locale['457'] = "寄信失敗，請聯絡 <a href='mailto:".$settings['siteemail']."'>站長</a>.";
$locale['458'] = "註冊失敗的原因如下:";
$locale['459'] = "請再試一次";
// Register Form
$locale['500'] = "請在以下表格填入你的個人資料。 ";
$locale['501'] = "有一封驗證電子郵件已經寄往你的信箱。 ";
$locale['502'] = "必須填寫標有 <span style='color:#ff0000;'>*</span> 的欄位。你的帳號和密碼將有大小寫之分。";
$locale['503'] = " 在成功登入後，你可以前往編輯個人資料填寫其它的額外訊息。";
$locale['504'] = "確認代碼:";
$locale['505'] = "鍵入確認代碼:";
$locale['506'] = "確定註冊";
$locale['507'] = "註冊系統目前關閉中。";
// Validation Errors
$locale['550'] = "你必須填入一組帳號名稱。";
$locale['551'] = "你必須填入一組密碼。";
$locale['552'] = "你必須填入一組電子信箱。";
?>
