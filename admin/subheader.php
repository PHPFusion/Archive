<?
echo "<html>
<head>
<script language=\"JavaScript\">
function AddTags(left, right) {
	strSelection = document.selection.createRange().text
	if (strSelection == \"\") {
		code = left + right;
		AddText(code);
		return false;
	} else {
		document.selection.createRange().text = left + strSelection + right;
		return;
	}
}
function AddText(NewCode) {
        if(document.all) {
        	insertAtCaret(document.forms[\"editform\"].article,NewCode);
        	setfocus();
        } else {
        	document.forms[\"editform\"].article.value+=NewCode;
        	setfocus();
        }
}
function storeCaret (textEl){
        if(textEl.createTextRange) {
                textEl.caretPos = document.selection.createRange().duplicate();
        }
}

function insertAtCaret (textEl, text) {
        if (textEl.createTextRange && textEl.caretPos) {
                var caretPos = textEl.caretPos;
                caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? text + ' ' : text;
        } else {
                textEl.value  = text;
        }
}

function setfocus() {
        document.forms[\"editform\"].article.focus();
}
</script>
<title>$settings[sitename]</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
<meta name=\"description\" content=\"$settings[description]\">
<meta name=\"keywords\" content=\"$settings[keywords]\">
<link rel=\"stylesheet\" href=\"../themes/$settings[theme]/styles.css\" type=\"text/css\">
</head>
<body bgcolor=\"#888888\" text=\"#dddddd\">\n";
$today = strtoupper(gmdate("l F d Y", $servertime));

echo "<table width=\"100%\" align=\"center\" cellspacing=\"0\" cellpadding=\"0\" class=\"bodyborder\">
<tr><td>

<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" class=\"header\">
<tr><td><img src=\"../$settings[sitebanner]\"></td></tr>
</table>\n";

echo "<table width=\"100%\" cellspacing=\"10\" cellpadding=\"0\">\n";
?>