<?php
/* 
* $Id: admin.php v 1.0 12 July 2003 Catwolf Exp $
* Module: WF-FAQ
* Version: v1.00
* Release Date: 12 July 2003
* Author: Catzwolf
* Translator: frankblack / frankblack@01019freenet.de
* Licence: GNU
*/

//Main ADmin Section

define('_AM_FAQMANINTRO','Willkommen im WF-FAQ Administrationsbereich');


/*
* Uni Lang defines
*/
define('_AM_SUBMIT','Erstellen');
define('_AM_CREATE','Erstellen');
define('_AM_YES','Ja');
define('_AM_NO','Nein');
define('_AM_DELETE','L&ouml;schen');
define('_AM_MODIFY','&Auml;ndern');
define('_AM_UPDATED','Datenbank wurde erfolgreich aktualisiert');
define('_AM_NOTUPDATED','Es gab ein Problem beim Aktualisieren der Datenbank!');
define('_AM_CATCREATED','Die neue Kategorie wurde erstellt und gespeichert!');
define('_AM_CATMODIFY','Die Kategorie wurde ge&auml;ndert und gespeichert!');
/*
* Lang defines for functions.php
*/
define('_AM_FADMINHEAD','WF-FAQ Admininistration');
define('_AM_FADMINCATH','WF-FAQ Kategorieadministration');
define('_AM_FNEWCAT','FAQ Kategorie&uuml;bersicht');
define('_AM_FNEWCATTXT','FAQ Kategorie erstellen, ver&auml;ndern oder L&ouml;schen. Oder zur&uuml;ck zur FAQ Kategorie&uuml;bersicht.');
define('_AM_FNEWFAQ','FAQ Themen&uuml;bersicht');
define('_AM_FNEWFAQTXT','FAQ Thema erstellen, ver&auml;ndern oder l&ouml;schen. Oder zur&uuml;ck zur FAQ Themen&uuml;bersicht.');
define('_AM_FVAL','Neue Einsendungen freigeben');
define('_AM_FVALTXT',"Erlaubt Ihnen die L&ouml;schung oder Freigabe der neu eingeschickten FAQ's.");
/*
* Lang defines for Category.php
*/
define('_AM_FRECOUNT','FAQ Themen neu z&auml;hlen');
define('_AM_FRECOUNTTXT','Erlaubt Ihnen die Anzahl der FAQ in jeder Kategorie neu zu z&auml;hlen.');
define('_AM_CREATIN','Erstellen in');
define('_AM_CATNAME','Kategoriename');
define('_AM_CATDESCRIPT','Kategoriebeschreibung');
define('_AM_NOCATTOEDIT','Es gibt keine Kategorie zum Bearbeiten.');
define('_AM_MODIFYCAT','Kategorie ver&auml;ndern');
define('_AM_DELCAT','Kategorie l&ouml;schen');
define('_AM_ADDCAT','Kategorie hinzuf&uuml;gen');
define('_AM_MODIFYTHISCAT','Diese Kategorie ver&auml;ndern?');
define('_AM_DELETETHISCAT','Diese Kategorie l&ouml;schen?');
define('_AM_CATISDELETED','Kategorie %s wurde gel&ouml;scht');

/*
* Lang defines for topics.php
*/
define('_AM_TOPICSADMIN','FAQ Themenadministration');
define('_AM_NOTCTREATEDACAT','Sie k&ouml;nnen keine FAQ hinzuf&uuml;gen wenn Sie keine FAQ Kategorie erstellt haben!');
define('_AM_ADDFAQ','Neue FAQ hinzuf&uuml;gen');
define('_AM_CREATEIN','Erstellen in');
define('_AM_TOPICQ','Frage');
define('_AM_TOPICA','Antwort');
define('_AM_SUMMARY','Zusammenfassung');
define('_AM_MODIFYFAQ','FAQ ver&auml;ndern');
define('_AM_MODIFYEXSITFAQ','FAQ ver&auml;ndern');
define('_AM_MODIFYTHISFAQ','Diese FAQ Frage ver&auml;ndern');
define('_AM_DELFAQ','FAQ l&ouml;schen');
define('_AM_DELTHISFAQ','Diese FAQ l&ouml;schen');
define('_AM_NOTOPICTOEDIT','Es liegt keine FAQ zum &Auml;ndern in der Datenbank vor');
define('_AM_DELETETHISTOPIC','Dieses FAQ Thema l&ouml;schen?');
define('_AM_TOPICISDELETED','FAQ %s wurde gel&ouml;scht');
define('_AM_FAQCREATED','FAQ wurde erstellt und gespeichert');
define('_AM_FAQNOTCREATED','FEHLER: FAQ wurde NICHT erstellt und gespeichert');
define('_AM_FAQMODIFY','FAQ wurde ver&auml;ndert und gespeichert');
define('_AM_FAQNOTMODIFY','FEHLER: FAQ wurde NICHT ge&auml;ndert und gespeichert');

define('_AM_SUBALLOW','Freigeben');
define('_AM_SUBDELETE','L&ouml;schen');
define('_AM_SUBRETURN','Zur&uuml;ck zur Administration');
define('_AM_SUBRETURNTO','Zur&uuml;ck zu den neuen Einsendungen');
define('_AM_AUTHOR','Autor');
define('_AM_PUBLISHED','Ver&ouml;ffentlicht');
define('_AM_SUBPREVIEW','Die WF-FAQ Admininistrationsansicht');
define('_AM_SUBADMINPREV','Dies ist die Administratorvorschau dieses FAQ Themas.');
define('_AM_DBUPDATED','FAQ Datenbank wurde erfolgreich aktualisiert');
/*
*  Copyright and Support.  Please do not remove this line as this is part of the only copyright agreement for using WF-FAQ 
*/
define('_AM_VISITSUPPORT', 'Besuchen Sie die WF-Section Website f&uuml;r weitere Informationen, Updates und Hinweise f&uuml;r die Benutzung.<br /> WF-FAQ v1 Catzwolf &copy; 2003 <a href="http://wfsections.xoops2.com/" target="_blank">WF-FAQ</a>');

//Added by frankblack
define('_AM_NOVALIDATION','Es liegen keine FAQ zur Freigabe vor');
define('_AM_NEWSUBMISSION','Neue Einsendungen');
define('_AM_SUBMITTEDON','Eingeschickt am');
define('_AM_TITLE','Titel');
define('_AM_ACTION','Aktion');
?>