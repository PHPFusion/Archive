<?php
/*
Portuguese Language Fileset
Traduzido por:
Guilherme Ara�jo (Chancer)
E-mail: guilhermeama@gmail.com
Web: http://www.php-fusion.co.uk
*/

$locale['400'] = "Registrar";
$locale['401'] = "Ativar Conta de Usu�rio";
// Registration Errors
$locale['402'] = "Voc� precisa especificar um Usu�rio, Senha e e-mail.";
$locale['403'] = "O Usu�rio cont�m caracteres inv�lidos.";
$locale['404'] = "As senhas n�o coincidem.";
$locale['405'] = "Senha inv�lida. Use apenas letras e n�meros.<br>
Sua senha precisa ter no m�nimo 6 caracteres.";
$locale['406'] = "Seu endere�o de e-mail parece n�o ser v�lido.";
$locale['407'] = "Desculpe, o usu�rio ".(isset($_POST['username']) ? $_POST['username'] : "")." j� est� sendo usado.";
$locale['408'] = "Desculpe, o e-mail ".(isset($_POST['email']) ? $_POST['email'] : "")." j� est� sendo usado.";
$locale['409'] = "Uma conta inativa foi registrada com este e-mail.";
$locale['410'] = "C�digo de valida��o incorreto.";
$locale['411'] = "Seu endere�o de e-mail ou dom�nio de e-mail est� bloqueado para registro no site.";
// Email Message
$locale['450'] = "Ol� ".(isset($_POST['username']) ? $_POST['username'] : "").",\n
Bem vindo a ".$settings['sitename'].". Estes s�o seus dados de Login:\n
Usu�rio: ".(isset($_POST['username']) ? $_POST['username'] : "")."
Senha: ".(isset($_POST['password1']) ? $_POST['password1'] : "")."\n
Por favor, use o link a seguir para ativar sua conta:\n";
// Registration Success/Fail
$locale['451'] = "Registro conclu�do";
$locale['452'] = "Voc� pode fazer Login agora.";
$locale['453'] = "Um administrador ativar� sua conta brevemente.";
$locale['454'] = "Seu registro est� quase conclu�do! Voc� receber� um e-mail que cont�m os dados de Login junto com um Link para ativar sua conta.";
$locale['455'] = "Sua conta foi verificada.";
$locale['456'] = "Registro falhou";
$locale['457'] = "Envio de e-mail falhou. Por favor contate o <a href='mailto:".$settings['siteemail']."'>Administrador do Site</a>.";
$locale['458'] = "O registro falhou pelo(s) seguinte(s) motivo(s):";
$locale['459'] = "Por favor, tente novamente";
// Register Form
$locale['500'] = "Informe seus dados abaixo. ";
$locale['501'] = "Um e-mail de verifica��o ser� enviado para o endere�o de e-mail aqui especificado. ";
$locale['502'] = "Campos marcados com <span style='color:#ff0000;'>*</span> devem ser preenchidos.
Seu usu�rio e senha usam o caso sensitivo. Letras mai�sculas e min�sculas s�o diferenciadas.";
$locale['503'] = " Voc� pode adicionar suas informa��es adicionais editando o seu <b>PERFIL</b> apenas quando fizer Login.";
$locale['504'] = "C�digo de Valida��o:";
$locale['505'] = "Digite o C�digo de Valida��o:";
$locale['506'] = "Registrar";
$locale['507'] = "O Sistema de registro est� atualmente desabilitado.";
// Validation Errors
$locale['550'] = "Por favor, digite um Usu�rio.";
$locale['551'] = "Por favor, digite uma Senha.";
$locale['552'] = "Por favor, digite um e-mail.";
?>
