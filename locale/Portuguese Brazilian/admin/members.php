<?php
/*
Portuguese Language Fileset
Traduzido por:
Guilherme Ara�jo (Chancer)
E-mail: guilhermeama@gmail.com
Web: http://www.php-fusion.co.uk
*/

// Member Management Options
$locale['400'] = "Membros";
$locale['401'] = "Usu�rio";
$locale['402'] = "Adicionar";
$locale['403'] = "Tipo de Usu�rio";
$locale['404'] = "Op��es";
$locale['405'] = "Ver";
$locale['406'] = "Editar";
$locale['407'] = "Remover Ban";
$locale['408'] = "Banir";
$locale['409'] = "Deletar";
$locale['410'] = "N�o h� membros cujo nick comece por ";
$locale['411'] = "Mostrar todos";
$locale['412'] = "Ativar";
// Ban/Unban/Delete Member
$locale['420'] = "Ban Imposto";
$locale['421'] = "Ban Removido";
$locale['422'] = "Membro Deletado";
$locale['423'] = "Tem certeza de que quer deletar este membro?";
$locale['424'] = "Membro Ativado";
$locale['425'] = "Conta ativada �s ";
$locale['426'] = "Ol� [USER_NAME],\n
Sua conta no(a) ".$settings['sitename']." foi ativada.\n
Agora voc� pode logar usando seu usu�rio e sua senha.\n
Atenciosamente,
".$settings['siteusername'];
// Edit Member Details
$locale['430'] = "Editar Membro";
$locale['431'] = "Detalhar do Membro Atualizados";
$locale['432'] = "Retornar � Administra��o dos Membros";
$locale['433'] = "Retornar ao Admin Home";
$locale['434'] = "Incapaz de atualizar os Detalhes do Membro:";
// Extra Edit Member Details form options
$locale['440'] = "Salvar Mudan�as";
// Update Profile Errors
$locale['450'] = "N�o pode editar o administrador prim�rio.";
$locale['451'] = "Voc� deve especificar um usu�rio e um e-mail.";
$locale['452'] = "O nome de usu�rio cont�m caracteres inv�lidos.";
$locale['453'] = "O nome de usu�rio ".(isset($_POST['user_name']) ? $_POST['user_name'] : "")." j� est� em uso.";
$locale['454'] = "E-mail inv�lido.";
$locale['455'] = "O email ".(isset($_POST['user_email']) ? $_POST['user_email'] : "")." j� est� em uso.";
$locale['456'] = "As novas senhas n�o coincidem.";
$locale['457'] = "Senha inv�lida. Use somente caracteres alfa-num�ricos.<br>
A senha tem que ter pelo menos 6 caracteres.";
$locale['458'] = "<b>Aviso:</b> execu��o inesperada do script.";
// View Member Profile
$locale['470'] = "Perfil do Membro";
$locale['472'] = "Estat�sticas";
$locale['473'] = "Grupos de Usu�rios";
// Add Member Errors
$locale['480'] = "Adicionar Membro";
$locale['481'] = "A conta do membro foi criada.";
$locale['482'] = "A conta do membro n�o p�de ser criada.";
?>
