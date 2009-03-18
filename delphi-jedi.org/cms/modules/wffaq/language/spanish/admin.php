<?php
/* 
* $Id: admin.php v 1.0 12 July 2003 Catwolf Exp $
* Module: WF-FAQ
* Version: v1.00
* Release Date: 12 July 2003
* Author: Catzwolf
* Licence: GNU
*/

//Main ADmin Section

define('_AM_FAQMANINTRO','Bienvenido a la mesa de control de WF-FAQ');


/*
* Uni Lang defines
*/
define('_AM_SUBMIT','Enviar');
define('_AM_CREATE','Crear');
define('_AM_YES','Sí');
define('_AM_NO','No');
define('_AM_DELETE','Borrar');
define('_AM_MODIFY','Modificar');
define('_AM_UPDATED','¡La base de datos se actualizó correctamente!');
define('_AM_NOTUPDATED','¡Hubo un error al actualizar la base de datos!');
define('_AM_CATCREATED','¡La nueva categoría fue creada y salvada!');
define('_AM_CATMODIFY','¡La categoría fue modificada y salvada!');
/*
* Lang defines for functions.php
*/
define('_AM_FADMINHEAD','Manejo de WF-FAQ');
define('_AM_FADMINCATH','Manejo de categorías de WF-FAQ');
define('_AM_FNEWCAT','Índice de categorías de las preguntas');
define('_AM_FNEWCATTXT','Crear, editar o borrar una categoría de preguntas. O volver al Índice de categorías de preguntas.');
define('_AM_FNEWFAQ','Índice de preguntas');
define('_AM_FNEWFAQTXT','Crear, editar o borrar una pregunta. O volver al Índice de preguntas.');
define('_AM_FVAL','Autorizar nuevos envíos');
define('_AM_FVALTXT',"Te permite borrar o autorizar las nuevas preguntas enviadas.");
/*
* Lang defines for Category.php
*/
define('_AM_FRECOUNT','Contar otra vez las preguntas');
define('_AM_FRECOUNTTXT','Te permite recontar el número de preguntas en cada categoría.');
define('_AM_CREATIN','Crear en');
define('_AM_CATNAME','Nombre de categoría');
define('_AM_CATDESCRIPT','Descripción de categoría');
define('_AM_NOCATTOEDIT','No hay categoría qué editar.');
define('_AM_MODIFYCAT','Modificar categoría');
define('_AM_DELCAT','Borrar categoría');
define('_AM_ADDCAT','Agregar categoría');
define('_AM_MODIFYTHISCAT','¿Modificar esta categoría?');
define('_AM_DELETETHISCAT','¿Borrar esta categoría?');
define('_AM_CATISDELETED','La categoría %s ha sido borrada');

/*
* Lang defines for topics.php
*/
define('_AM_TOPICSADMIN','Manejo de preguntas');
define('_AM_NOTCTREATEDACAT','¡No puedes agregar una pregunta si no creas antes una categoría de preguntas!');
define('_AM_ADDFAQ','Crear nueva pregunta');
define('_AM_CREATEIN','Crear en');
define('_AM_TOPICQ','Pregunta');
define('_AM_TOPICA','Respuesta');
define('_AM_SUMMARY','Sumario');
define('_AM_MODIFYFAQ','Editar pregunta');
define('_AM_MODIFYEXSITFAQ','Editar pregunta');
define('_AM_MODIFYTHISFAQ','Editar esta pregunta');
define('_AM_DELFAQ','Borrar pregunta');
define('_AM_DELTHISFAQ','Borrar esta pregunta');
define('_AM_NOTOPICTOEDIT','No hay preguntas qué editar en la base de datos');
define('_AM_DELETETHISTOPIC','¿Borrar esta pregunta?');
define('_AM_TOPICISDELETED','La pregunta %s ha sido borrada');
define('_AM_FAQCREATED','La pregunta fue creada y salvada');
define('_AM_FAQNOTCREATED','ERROR: La pregunta NO se creó ni salvó');
define('_AM_FAQMODIFY','La pregunta fue modificada y salvada');
define('_AM_FAQNOTMODIFY','ERROR: La pregunta NO se editó ni salvó');

define('_AM_SUBALLOW','Permitir');
define('_AM_SUBDELETE','Borrar');
define('_AM_SUBRETURN','Volver a mesa de control');
define('_AM_SUBRETURNTO','Volver a nuevos envíos');
define('_AM_AUTHOR','Autor');
define('_AM_PUBLISHED','Publicación');
define('_AM_SUBPREVIEW','La vista de control de WF-FAQ');
define('_AM_SUBADMINPREV','Esta es la vista previa de control de esta pregunta.');
define('_AM_DBUPDATED','La base de datos que contiene las preguntas ha sido actualizada');
/*
*  Copyright and Support.  Please do not remove this line as this is part of the only copyright agreement for using WF-FAQ 
*/
define('_AM_VISITSUPPORT', 'Visita el sitio web de WF-Section para información, actualización y ayuda sobre su uso.<br /> WF-FAQ v1 Catzwolf &copy; 2003 <a href="http://wfsections.xoops2.com/" target="_blank">WF-FAQ</a>');

?>