<?php
// Members List
$locale['400'] = "Lista de Miembros";
$locale['401'] = "Nombre de Usuario";
$locale['402'] = "Tipo de Usuario";
$locale['403'] = "No existen usuarios que empiecen por ";
$locale['404'] = "Ver Todo";
// User Profile
$locale['420'] = "Perfil de Usuario: ";
$locale['421'] = "Información General";
$locale['422'] = "Estadísticas";
$locale['423'] = "Grupos de Usuarios";
// Edit Profile
$locale['440'] = "Editar Perfil";
$locale['441'] = "Perfil guardado con éxito";
$locale['442'] = "No se puede abrir perfil:";
// Edit Profile Form
$locale['460'] = "Actualizar Perfil";
// Update Profile Errors
$locale['480'] = "Debe especificar un nombre de usuario y un Correo Electrónico.";
$locale['481'] = "El nombre de usuario contiene caracteres no válidos.";
$locale['482'] = "El nombre de usuario ".(isset($_POST['user_name']) ? $_POST['user_name'] : "")." ya está en uso.";
$locale['483'] = "Correo Electrónico no válido.";
$locale['484'] = "El Correo Electrónico ".(isset($_POST['user_email']) ? $_POST['user_email'] : "")." ya está en uso.";
$locale['485'] = "Las nuevas contraseñas no coinciden.";
$locale['486'] = "Contraseña no válida, utilice sólo caracteres alfanuméricos.<br>
La contraseña debe tener un mínimo de 6 caracteres.";
$locale['487'] = "<b>Alerta:</b> ejecución inesperada de script.";
?>