<?php
/*
Spanish Language Fileset
Produced by Daniel Rindorf (colgate)
and Pablo Salas Aragon (Salas)
Email: colgate@amforaen.dk
and pablo.salas.aragon@gmail.com
*/

// Locale Settings
setlocale(LC_TIME, "en","GB"); // Linux Server (Windows may differ)
$locale['charset'] = "iso-8859-1";

// Full & Short Months
$locale['months'] = "&nbsp|Enero|Febrero|Marzo|Abril|Mayo|Junio|Julio|Agosto|Septiembre|Octubre|Noviembre|Diciembre";
$locale['shortmonths'] = "&nbsp|Enr|Feb|Mar|Abr|May|Jun|Jul|Ago|Sept|Oct|Nov|Dic";

// Standard User Levels
$locale['user0'] = "Público";
$locale['user1'] = "Miembro";
$locale['user2'] = "Administrador";
$locale['user3'] = "Super Administrador";
// Forum Moderator Level(s)
$locale['userf1'] = "Moderador";
// Navigation
$locale['001'] = "Navegación";
$locale['002'] = "No se definieron enlaces\n";
$locale['003'] = "Solo Miembros";
$locale['004'] = "No hay contenido en este panel";
// Users Online
$locale['010'] = "Usuarios En Línea";
$locale['011'] = "Invitados En Línea: ";
$locale['012'] = "Miembros En Línea: ";
$locale['013'] = "Sin Miembros En Línea";
$locale['014'] = "Miembros Registrados: ";
$locale['015'] = "Miembros no activados: ";
$locale['016'] = "Nuevos Miembros: ";
// Sidebar
$locale['020'] = "Temas del Foro";
$locale['021'] = "Nuevos Temas";
$locale['022'] = "Temas más interesantes";
$locale['023'] = "Ultimos Articulos";
// Welcome Title & Forum List
$locale['030'] = "Bienvenid@";
$locale['031'] = "Último Tema Activo del Foro";
$locale['032'] = "Foro";
$locale['033'] = "Tema";
$locale['034'] = "Visto";
$locale['035'] = "Respuestas";
$locale['036'] = "Último mensaje";
// News & Articles
$locale['040'] = "Enviado por ";
$locale['041'] = "el ";
$locale['042'] = "Leer más";
$locale['043'] = " Comentarios";
$locale['044'] = " Lecturas";
$locale['045'] = "Imprimir";
$locale['046'] = "Sin Noticias";
$locale['047'] = "No se ha enviado ninguna Noticia";
// Prev-Next Bar
$locale['050'] = "Anterior";
$locale['051'] = "Siguiente";
$locale['052'] = "Página ";
$locale['053'] = " de ";
// User Menu
$locale['060'] = "Conectarse";
$locale['061'] = "Nombre de Ususario";
$locale['062'] = "Contraseña";
$locale['063'] = "Recordarme";
$locale['064'] = "Conectarse";
$locale['065'] = "¿Todavía no es miembro?<br><a href='".BASEDIR."register.php' class='side'>Haga click aquí</a> para registrarse.";
$locale['066'] = "¿Olvidó su contraseña?<br>Solicite una nueva <a href='".BASEDIR."lostpassword.php' class='side'>aquí</a>.";
//
$locale['080'] = "Editar Perfil";
$locale['081'] = "Mensajes Privados";
$locale['082'] = "Lista de Miembros";
$locale['083'] = "Panel de Administración";
$locale['084'] = "Desconectarse";
$locale['085'] = "Tiene %u nuevo ";
$locale['086'] = "mensaje";
$locale['087'] = "mensajes";
// Poll
$locale['100'] = "Encuesta";
$locale['101'] = "Enviar Voto";
$locale['102'] = "Necesita conectarse para votar.";
$locale['103'] = "Voto";
$locale['104'] = "Votos";
$locale['105'] = "Votos: ";
$locale['106'] = "Iniciado: ";
$locale['107'] = "Finalizado: ";
$locale['108'] = "Archivo de Encuestas";
$locale['109'] = "Selecciona una Encuesta de la lista para ver los resultados:";
$locale['110'] = "Ver";
$locale['111'] = "Ver Encuesta";
// Shoutbox
$locale['120'] = "Panel de Notas";
$locale['121'] = "Nombre:";
$locale['122'] = "Mensaje:";
$locale['123'] = "Enviar";
$locale['124'] = "Ayuda";
$locale['125'] = "Necesita conectarse para enviar mensajes.";
$locale['126'] = "Archivo del Panel de Notas";
$locale['127'] = "No se enviaron mensajes.";
// Footer Counter
$locale['140'] = "Visita";
$locale['141'] = "Visitas";
// Admin Navigation
$locale['150'] = "Página de Administración";
$locale['151'] = "Volver al Sitio";
$locale['152'] = "Paneles de Administración";
$locale['180'] = "Seleccionar";
$locale['181'] = "Expandir";
$locale['182'] = "Contraer";
// Miscellaneous
$locale['190'] = "Modo Mantenimiento Activado";
$locale['191'] = "Su dirección IP está actualmente en la lista negra.";
$locale['192'] = "Desconectándose como ";
$locale['193'] = "Conectándose como ";
$locale['194'] = "Esta cuenta está actualmente suspendida.";
$locale['195'] = "Esta cuenta no ha sido activada.";
$locale['196'] = "Nombre de Usuario o contraseña no válido.";
$locale['197'] = "Por favor, espere mientras le transferimos...<br><br>
[ <a href='index.php'>O haga click aquí si no desea esperar</a> ]";
$locale['198'] = "<b>Alerta:</b> setup.php ha sido detectado, por favor, bórrelo inmediatamente";
?>