<?php
$locale['400'] = "Registrar";
$locale['401'] = "Activar Cuenta";
// Registration Errors
$locale['402'] = "Tiene que indicar un Nombre de Usuario, una Contrase�a y una Direcci�n de Correo Electr�nico";
$locale['403'] = "El Nombre de Usuario contiene caracteres no v�lidos";
$locale['404'] = "Las contrase�as no coinciden";
$locale['405'] = "Contrase�a no v�lida, use s�lo caracteres alfanum�ricos<br>

Password must be a minimum of 6 characters long.";

$locale['406'] = "Su Direcci�n Correo Electr�nico no parece v�lida";
$locale['407'] = "Lo siento, el Nombre de Usuario ".(isset($_POST['username']) ? $_POST['username'] : "")." est� en uso.";
$locale['408'] = "Lo siento, la Direcci�n de Correo Electr�nico ".(isset($_POST['email']) ? $_POST['email'] : "")." est� en uso.";
$locale['409'] = "Una cuenta inactiva se ha registrado con la direcci�n de correo electr�nico.";
$locale['410'] = "C�digo de validaci�n incorrecto";
$locale['411'] = "Su direcci�n de correo electr�nico o el dominio del correo electr�nico est� bloqueado.";
// Email Message
$locale['450'] = "Hola ".(isset($_POST['username']) ? $_POST['username'] : "").",\n
Bienvenid@ a ".$settings['sitename'].". Aqu� tiene los detalles de su cuenta:\n
Nombre de Usuario: ".(isset($_POST['username']) ? $_POST['username'] : "")."
Contrase�a: ".(isset($_POST['password1']) ? $_POST['password1'] : "")."\n
Por favor, active su cuenta entrando en el link:\n";
// Registration Success/Fail
$locale['451'] = "Registro completo";
$locale['452'] = "Ya puede conectarse.";
$locale['453'] = "Un administrador activar� su cuenta en breve.";
$locale['454'] = "Su registro est� casi completo, recibir� un correo electr�nico con los detalles de su
conexi�n y un enlace para activar su cuenta.";
$locale['455'] = "Su cuenta ha sido verificada.";
$locale['456'] = "Fallo de registro";
$locale['457'] = "Fallo al enviar el correo, contacte con
<a href='mailto:".$settings['siteemail']."'>Administrador del Sitio</a>.";
$locale['458'] = "El registro fall� debido a:";
$locale['459'] = "Por favor, int�ntelo de nuevo";
// Register Form
$locale['500'] = "Por favor, introduzca la siguiente informaci�n. ";
$locale['501'] = "Un correo electr�nico de comprobaci�n le ser� enviado a su direcci�n de correo electr�nico. ";
$locale['502'] = "Campos marcados con<span style='color:#ff0000;'>*</span> son obligatorios.
El nombre de usuario y la contrase�a distingue may�sculas de min�sculas.";
$locale['503'] = " Puede introducir informaci�n adicional entrando en la configuraci�n de su perfil una vez conectado.";
$locale['504'] = "C�digo de Validaci�n:";
$locale['505'] = "Introduzca c�digo de Validaci�n:";
$locale['506'] = "Registrar";
$locale['507'] = "El sistema de registro est� actualmente deshabilitado.";
// Validation Errors
$locale['550'] = "Por favor, especifique Nombre de Usuario.";
$locale['551'] = "Por favor, especifique contrase�a.";
$locale['552'] = "Por favor, espacifique correo electr�nico.";
?>