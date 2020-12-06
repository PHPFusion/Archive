<?php
$locale['400'] = "Registrar";
$locale['401'] = "Activar Cuenta";
// Registration Errors
$locale['402'] = "Tiene que indicar un Nombre de Usuario, una Contraseña y una Dirección de Correo Electrónico";
$locale['403'] = "El Nombre de Usuario contiene caracteres no válidos";
$locale['404'] = "Las contraseñas no coinciden";
$locale['405'] = "Contraseña no válida, use sólo caracteres alfanuméricos<br>

Password must be a minimum of 6 characters long.";

$locale['406'] = "Su Dirección Correo Electrónico no parece válida";
$locale['407'] = "Lo siento, el Nombre de Usuario ".(isset($_POST['username']) ? $_POST['username'] : "")." está en uso.";
$locale['408'] = "Lo siento, la Dirección de Correo Electrónico ".(isset($_POST['email']) ? $_POST['email'] : "")." está en uso.";
$locale['409'] = "Una cuenta inactiva se ha registrado con la dirección de correo electrónico.";
$locale['410'] = "Código de validación incorrecto";
$locale['411'] = "Su dirección de correo electrónico o el dominio del correo electrónico está bloqueado.";
// Email Message
$locale['450'] = "Hola ".(isset($_POST['username']) ? $_POST['username'] : "").",\n
Bienvenid@ a ".$settings['sitename'].". Aquí tiene los detalles de su cuenta:\n
Nombre de Usuario: ".(isset($_POST['username']) ? $_POST['username'] : "")."
Contraseña: ".(isset($_POST['password1']) ? $_POST['password1'] : "")."\n
Por favor, active su cuenta entrando en el link:\n";
// Registration Success/Fail
$locale['451'] = "Registro completo";
$locale['452'] = "Ya puede conectarse.";
$locale['453'] = "Un administrador activará su cuenta en breve.";
$locale['454'] = "Su registro está casi completo, recibirá un correo electrónico con los detalles de su
conexión y un enlace para activar su cuenta.";
$locale['455'] = "Su cuenta ha sido verificada.";
$locale['456'] = "Fallo de registro";
$locale['457'] = "Fallo al enviar el correo, contacte con
<a href='mailto:".$settings['siteemail']."'>Administrador del Sitio</a>.";
$locale['458'] = "El registro falló debido a:";
$locale['459'] = "Por favor, inténtelo de nuevo";
// Register Form
$locale['500'] = "Por favor, introduzca la siguiente información. ";
$locale['501'] = "Un correo electrónico de comprobación le será enviado a su dirección de correo electrónico. ";
$locale['502'] = "Campos marcados con<span style='color:#ff0000;'>*</span> son obligatorios.
El nombre de usuario y la contraseña distingue mayúsculas de minúsculas.";
$locale['503'] = " Puede introducir información adicional entrando en la configuración de su perfil una vez conectado.";
$locale['504'] = "Código de Validación:";
$locale['505'] = "Introduzca código de Validación:";
$locale['506'] = "Registrar";
$locale['507'] = "El sistema de registro está actualmente deshabilitado.";
// Validation Errors
$locale['550'] = "Por favor, especifique Nombre de Usuario.";
$locale['551'] = "Por favor, especifique contraseña.";
$locale['552'] = "Por favor, espacifique correo electrónico.";
?>