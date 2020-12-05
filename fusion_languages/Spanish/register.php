<?php
define("LAN_400", "Registrar");
define("LAN_401", "Activar Cuenta");
// Registration Errors
define("LAN_402", "Tienes que indicar un Nombre de Usuario, una Contrase�a y una Direcci�n de Email");
define("LAN_403", "El Nombre de Usuario contiene caracteres inv�lidos");
define("LAN_404", "Las contrase�as no coinciden");
define("LAN_405", "Contrase�a inv�lida, use solo caracteres alfa num�ricos");
define("LAN_406", "T� Direcci�n de Email no parece v�lida");
define("LAN_407", "Lo siento, el Nombre de Usuario ".$_POST['username']." est� en uso.");
define("LAN_408", "Lo siento, la Direcci�n de Email ".$_POST['email']." est� en uso.");
define("LAN_409", "Ya existe un usuario registrado con ese email.");
define("LAN_410", "C�digo de validaci�n incorrecto");
// Email Message
define("LAN_450", "Hola ".$_POST['username'].",\n
Bienvenid@ a ".$settings['sitename'].". Aqu� tiene los detalles de su cuenta:\n
Usuario: ".$_POST['username']."
Contrase�a: ".$_POST['password1']."\n
Por favor, active su cuenta entrando en el link:\n");
// Registration Success/Fail
define("LAN_451", "Registro completo, ya puede conectarse.");
define("LAN_452", "Su registro est� casi completo, recibir� un email con un enlace para activar su cuenta.");
define("LAN_453", "Su ceunta se ha activado, ahora puede conectarse.");
define("LAN_454", "Fallo de registro");
define("LAN_455", "Fallo al enviar el correo, conacto con <a href='mailto:".$settings['siteemail']."'>Site Administrator</a>.");
define("LAN_456", "El registro fallo debido a:");
define("LAN_457", "Por favor, int�ntelo de nuevo");
// Register Form
define("LAN_500", "Por favor, entre la siguiente informaci�n. ");
define("LAN_501", "Un email de comprobaci�n le ser� enviado. ");
define("LAN_502", "Campos marcados con<span style='color:#ff0000;'>*</span> son obligatorios.
el nombre de usuario y la contrase�a distingues may�sculas de min�sculas.");
define("LAN_503", " Puede entrar informaci�n adicional entrando en la configuraci�n de su perfil.");
define("LAN_504", "C�digo de Validaci�n:");
define("LAN_505", "Entre c�digo de Validaci�n:");
define("LAN_506", "Registrar");
define("LAN_507", "El sistema de resitro est� actualmente desabilitado.");
// Validation Errors
define("LAN_550", "Por favor, especifique Nombre de Usuario.");
define("LAN_551", "Por favor, especifique contrase�a.");
define("LAN_552", "Por favor, espacifique email.");
?>