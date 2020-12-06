<?php
/*
Portuguese Language Fileset
Traduzido por:
Guilherme Araújo (Chancer)
E-mail: guilhermeama@gmail.com
Web: http://www.php-fusion.co.uk
*/

$locale['400'] = "Registrar";
$locale['401'] = "Ativar Conta de Usuário";
// Registration Errors
$locale['402'] = "Você precisa especificar um Usuário, Senha e e-mail.";
$locale['403'] = "O Usuário contém caracteres inválidos.";
$locale['404'] = "As senhas não coincidem.";
$locale['405'] = "Senha inválida. Use apenas letras e números.<br>
Sua senha precisa ter no mínimo 6 caracteres.";
$locale['406'] = "Seu endereço de e-mail parece não ser válido.";
$locale['407'] = "Desculpe, o usuário ".(isset($_POST['username']) ? $_POST['username'] : "")." já está sendo usado.";
$locale['408'] = "Desculpe, o e-mail ".(isset($_POST['email']) ? $_POST['email'] : "")." já está sendo usado.";
$locale['409'] = "Uma conta inativa foi registrada com este e-mail.";
$locale['410'] = "Código de validação incorreto.";
$locale['411'] = "Seu endereço de e-mail ou domínio de e-mail está bloqueado para registro no site.";
// Email Message
$locale['450'] = "Olá ".(isset($_POST['username']) ? $_POST['username'] : "").",\n
Bem vindo a ".$settings['sitename'].". Estes são seus dados de Login:\n
Usuário: ".(isset($_POST['username']) ? $_POST['username'] : "")."
Senha: ".(isset($_POST['password1']) ? $_POST['password1'] : "")."\n
Por favor, use o link a seguir para ativar sua conta:\n";
// Registration Success/Fail
$locale['451'] = "Registro concluído";
$locale['452'] = "Você pode fazer Login agora.";
$locale['453'] = "Um administrador ativará sua conta brevemente.";
$locale['454'] = "Seu registro está quase concluído! Você receberá um e-mail que contém os dados de Login junto com um Link para ativar sua conta.";
$locale['455'] = "Sua conta foi verificada.";
$locale['456'] = "Registro falhou";
$locale['457'] = "Envio de e-mail falhou. Por favor contate o <a href='mailto:".$settings['siteemail']."'>Administrador do Site</a>.";
$locale['458'] = "O registro falhou pelo(s) seguinte(s) motivo(s):";
$locale['459'] = "Por favor, tente novamente";
// Register Form
$locale['500'] = "Informe seus dados abaixo. ";
$locale['501'] = "Um e-mail de verificação será enviado para o endereço de e-mail aqui especificado. ";
$locale['502'] = "Campos marcados com <span style='color:#ff0000;'>*</span> devem ser preenchidos.
Seu usuário e senha usam o caso sensitivo. Letras maiúsculas e minúsculas são diferenciadas.";
$locale['503'] = " Você pode adicionar suas informações adicionais editando o seu <b>PERFIL</b> apenas quando fizer Login.";
$locale['504'] = "Código de Validação:";
$locale['505'] = "Digite o Código de Validação:";
$locale['506'] = "Registrar";
$locale['507'] = "O Sistema de registro está atualmente desabilitado.";
// Validation Errors
$locale['550'] = "Por favor, digite um Usuário.";
$locale['551'] = "Por favor, digite uma Senha.";
$locale['552'] = "Por favor, digite um e-mail.";
?>
