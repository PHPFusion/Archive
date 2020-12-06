<?php
/*
Portuguese Language Fileset
Traduzido por:
Guilherme Araújo (Chancer)
E-mail: guilhermeama@gmail.com
Web: http://www.php-fusion.co.uk
*/

// Member Management Options
$locale['400'] = "Membros";
$locale['401'] = "Usuário";
$locale['402'] = "Adicionar";
$locale['403'] = "Tipo de Usuário";
$locale['404'] = "Opções";
$locale['405'] = "Ver";
$locale['406'] = "Editar";
$locale['407'] = "Remover Ban";
$locale['408'] = "Banir";
$locale['409'] = "Deletar";
$locale['410'] = "Não há membros cujo nick comece por ";
$locale['411'] = "Mostrar todos";
$locale['412'] = "Ativar";
// Ban/Unban/Delete Member
$locale['420'] = "Ban Imposto";
$locale['421'] = "Ban Removido";
$locale['422'] = "Membro Deletado";
$locale['423'] = "Tem certeza de que quer deletar este membro?";
$locale['424'] = "Membro Ativado";
$locale['425'] = "Conta ativada às ";
$locale['426'] = "Olá [USER_NAME],\n
Sua conta no(a) ".$settings['sitename']." foi ativada.\n
Agora você pode logar usando seu usuário e sua senha.\n
Atenciosamente,
".$settings['siteusername'];
// Edit Member Details
$locale['430'] = "Editar Membro";
$locale['431'] = "Detalhar do Membro Atualizados";
$locale['432'] = "Retornar à Administração dos Membros";
$locale['433'] = "Retornar ao Admin Home";
$locale['434'] = "Incapaz de atualizar os Detalhes do Membro:";
// Extra Edit Member Details form options
$locale['440'] = "Salvar Mudanças";
// Update Profile Errors
$locale['450'] = "Não pode editar o administrador primário.";
$locale['451'] = "Você deve especificar um usuário e um e-mail.";
$locale['452'] = "O nome de usuário contém caracteres inválidos.";
$locale['453'] = "O nome de usuário ".(isset($_POST['user_name']) ? $_POST['user_name'] : "")." já está em uso.";
$locale['454'] = "E-mail inválido.";
$locale['455'] = "O email ".(isset($_POST['user_email']) ? $_POST['user_email'] : "")." já está em uso.";
$locale['456'] = "As novas senhas não coincidem.";
$locale['457'] = "Senha inválida. Use somente caracteres alfa-numéricos.<br>
A senha tem que ter pelo menos 6 caracteres.";
$locale['458'] = "<b>Aviso:</b> execução inesperada do script.";
// View Member Profile
$locale['470'] = "Perfil do Membro";
$locale['472'] = "Estatísticas";
$locale['473'] = "Grupos de Usuários";
// Add Member Errors
$locale['480'] = "Adicionar Membro";
$locale['481'] = "A conta do membro foi criada.";
$locale['482'] = "A conta do membro não pôde ser criada.";
?>
