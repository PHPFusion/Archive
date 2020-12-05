<?php
define("LAN_400", "Registrar");
define("LAN_401", "Activar Cuenta");
// Registration Errors
define("LAN_402", "Tienes que indicar un Nombre de Usuario, una Contraseña y una Dirección de Email");
define("LAN_403", "El Nombre de Usuario contiene caracteres inválidos");
define("LAN_404", "Las contraseñas no coinciden");
define("LAN_405", "Contraseña inválida, use solo caracteres alfa numéricos");
define("LAN_406", "Tú Dirección de Email no parece válida");
define("LAN_407", "Lo siento, el Nombre de Usuario ".$_POST['username']." está en uso.");
define("LAN_408", "Lo siento, la Dirección de Email ".$_POST['email']." está en uso.");
define("LAN_409", "Ya existe un usuario registrado con ese email.");
define("LAN_410", "Código de validación incorrecto");
// Email Message
define("LAN_450", "Hola ".$_POST['username'].",\n
Bienvenid@ a ".$settings['sitename'].". Aquí tiene los detalles de su cuenta:\n
Usuario: ".$_POST['username']."
Contraseña: ".$_POST['password1']."\n
Por favor, active su cuenta entrando en el link:\n");
// Registration Success/Fail
define("LAN_451", "Registro completo, ya puede conectarse.");
define("LAN_452", "Su registro está casi completo, recibirá un email con un enlace para activar su cuenta.");
define("LAN_453", "Su ceunta se ha activado, ahora puede conectarse.");
define("LAN_454", "Fallo de registro");
define("LAN_455", "Fallo al enviar el correo, conacto con <a href='mailto:".$settings['siteemail']."'>Site Administrator</a>.");
define("LAN_456", "El registro fallo debido a:");
define("LAN_457", "Por favor, inténtelo de nuevo");
// Register Form
define("LAN_500", "Por favor, entre la siguiente información. ");
define("LAN_501", "Un email de comprobación le será enviado. ");
define("LAN_502", "Campos marcados con<span style='color:#ff0000;'>*</span> son obligatorios.
el nombre de usuario y la contraseña distingues mayúsculas de minúsculas.");
define("LAN_503", " Puede entrar información adicional entrando en la configuración de su perfil.");
define("LAN_504", "Código de Validación:");
define("LAN_505", "Entre código de Validación:");
define("LAN_506", "Registrar");
define("LAN_507", "El sistema de resitro está actualmente desabilitado.");
// Validation Errors
define("LAN_550", "Por favor, especifique Nombre de Usuario.");
define("LAN_551", "Por favor, especifique contraseña.");
define("LAN_552", "Por favor, espacifique email.");
?>