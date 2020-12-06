<?php
$locale['400'] = "注册";
$locale['401'] = "启用帐号";
// Registration Errors
$locale['402'] = "你必须提供一组帐号、密码和电子信箱。";
$locale['403'] = "帐号内含有无效字元。";
$locale['404'] = "两组密码不同。";
$locale['405'] = "无效的密码，你只能使用英数字元。<br>密码必须至少六位元长。";
$locale['406'] = "你给的电子信箱似乎是无效的。";
$locale['407'] = "很抱歉，你选择的帐号 ".(isset($_POST['username']) ? $_POST['username'] : "")." 已被注册使用。";
$locale['408'] = "很抱歉，你选择的电子信箱 ".(isset($_POST['email']) ? $_POST['email'] : "")." 已被注册使用。";
$locale['409'] = "已经有一个尚未开通的帐号使用了这个电子信箱。";
$locale['410'] = "你输入的确认代码错误。";
$locale['411'] = "你输入的电子信箱或信箱的网域在黑名单中。";
// Email Message
$locale['450'] = "哈啰 ".(isset($_POST['username']) ? $_POST['username'] : "").",\n
欢迎来到 ".$settings['sitename'].". 以下是你的登入资料:\n
帐号: ".(isset($_POST['username']) ? $_POST['username'] : "")."
密码: ".(isset($_POST['password1']) ? $_POST['password1'] : "")."\n
请使用以下连结来启用你的帐号:\n";
// Registration Success/Fail
$locale['451'] = "注册程序已完成";
$locale['452'] = "你现在可以登入本站了。";
$locale['453'] = "你的帐号将在稍后由管理员来启动。";
$locale['454'] = "注册程序即将完成，你将会收到一封包含你的注册资料的电子邮件，里面将有启用这个帐号的连结。";
$locale['455'] = "你的帐号已经被启用。";
$locale['456'] = "注册失败";
$locale['457'] = "寄信失败，请联络 <a href='mailto:".$settings['siteemail']."'>站长</a>.";
$locale['458'] = "注册失败的原因如下:";
$locale['459'] = "请再试一次";
// Register Form
$locale['500'] = "请在以下表格填入你的个人资料。 ";
$locale['501'] = "有一封验证电子邮件已经寄往你的信箱。 ";
$locale['502'] = "必须填写标有 <span style='color:#ff0000;'>*</span> 的栏位。你的帐号和密码将有大小写之分。";
$locale['503'] = " 在成功登入后，你可以前往编辑个人资料填写其它的额外讯息。";
$locale['504'] = "确认代码:";
$locale['505'] = "键入确认代码:";
$locale['506'] = "确定注册";
$locale['507'] = "注册系统目前关闭中。";
// Validation Errors
$locale['550'] = "你必须填入一组帐号名称。";
$locale['551'] = "你必须填入一组密码。";
$locale['552'] = "你必须填入一组电子信箱。";
?>
