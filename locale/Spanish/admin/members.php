<?php
// Member Management Options
$locale['400'] = "Miembros";
$locale['401'] = "Usuario";
$locale['402'] = "A�adir";
$locale['403'] = "Tipo de Usuario";
$locale['404'] = "Opciones";
$locale['405'] = "Ver";
$locale['406'] = "Editar";
$locale['407'] = "Desbloquear";
$locale['408'] = "Bloquear";
$locale['409'] = "Borrar";
$locale['410'] = "No hay usuarios que empiecen por ";
$locale['411'] = "Mostrar todo";
$locale['412'] = "Activar";
// Ban/Unban/Delete Member
$locale['420'] = "Bloqueo Activo";
$locale['421'] = "Bloqueo Borrado";
$locale['422'] = "Miembro Borrado";
$locale['423'] = "�Est� Seguro de eliminar este miembro?";
$locale['424'] = "Miembro Activado";
$locale['425'] = "Cuenta activada en ";
$locale['426'] = "Hola [USER_NAME],\n
Su cuenta en ".$settings['sitename']." ha sido activada.\n
Ahora puede conectarse usando su nombre de usuario y contrase�a elegidos.\n
Saludos,
".$settings['siteusername'];
// Edit Member Details
$locale['430'] = "Editar Miembro";
$locale['431'] = "Detalles de Miembro Actualizados";
$locale['432'] = "Volver a la Administraci�n de Miembros";
$locale['433'] = "Volver al �ndice de Administraci�n";
$locale['434'] = "No se pueden actualizar los detalles del miembro:";
// Extra Edit Member Details form options
$locale['440'] = "Guardar Cambios";
// Update Profile Errors
$locale['450'] = "No se Puede Editar el Adminstrador primario.";
$locale['451'] = "Debe especificar un nombre de usuario y un correo electr�nico.";
$locale['452'] = "El Nombre de ususario contiene caracteres no validos.";
$locale['453'] = "El Nombre de usuario ".(isset($_POST['user_name']) ? $_POST['user_name'] : "")." ya est� en uso.";
$locale['454'] = "Correo Electr�nico no v�lido";
$locale['455'] = "El Correo Electr�nico ".(isset($_POST['user_email']) ? $_POST['user_email'] : "")." ya est� en uso.";
$locale['456'] = "Las contrase�as no coinciden.";
$locale['457'] = "Contrase�a No v�lida, utilice s�lo caraceres alfanum�ricos.<br>
La longitud m�nima de la contrase�a debe ser de 6 caracteres.";
$locale['458'] = "<b>Alerta:</b> ejecuci�n inesperada de script.";
// View Member Profile
$locale['470'] = "Perfil de Miembro: ";
$locale['471'] = "Informacion General";
$locale['472'] = "Estad�sticas";
$locale['473'] = "Grupos de Usuarios";
// Add Member Errors
$locale['480'] = "A�adir Miembro";
$locale['481'] = "La cuenta de miembro ha sido creada.";
$locale['482'] = "No puede crearse la cuenta de miembro.";
?>