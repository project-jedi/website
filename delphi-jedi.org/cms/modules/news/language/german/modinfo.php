<?php

// Module Info

// The name of this module
define('_MI_NEWS_NAME','Artikel');

// A brief description of this module
define('_MI_NEWS_DESC','Erzeugt einen Artikel-Bereich in dem Mitglieder Artikel und Kommentare schreiben können.');

// Names of blocks for this module (Not all module has blocks)
define('_MI_NEWS_BNAME1','Artikelthemen-Block');
define('_MI_NEWS_BNAME3','Leitartikel-Block');
define('_MI_NEWS_BNAME4','Top Artikel-Block');
define('_MI_NEWS_BNAME5','Meistgelesene Artikel-Block');

// Sub menus in main menu block
define('_MI_NEWS_SMNAME1','Artikel schreiben');
define('_MI_NEWS_SMNAME2','Archiv');

// Names of admin menu items
define('_MI_NEWS_ADMENU2','Themenmanager');
define('_MI_NEWS_ADMENU3','Artikel veröffentlichen/bearbeiten');

// Title of config items
define('_MI_STORYHOME', 'Wieviel Artikel auf der Homepage?');
define('_MI_NOTIFYSUBMIT', 'Bei Artikeleingang benachrichtigen?');
define('_MI_DISPLAYNAV', 'Navigations-Box anzeigen?');
define('_MI_ANONPOST','Darf ein Gast Artikel veröffentlichen?');
define('_MI_AUTOAPPROVE','Artikel automatisch veröffentlichen?');

// Description of each config items
define('_MI_STORYHOMEDSC', 'Artikelanzahl auf der Startseite');
define('_MI_NOTIFYSUBMITDSC', 'Bei Artikeleingang eine Nachricht an den Webmaster schicken');
define('_MI_DISPLAYNAVDSC', 'Navigations-Box auf der Startseite anzeigen ?');
define('_MI_AUTOAPPROVEDSC', '');

// Text for notifications

define('_MI_NEWS_GLOBAL_NOTIFY', 'Allgemein');
define('_MI_NEWS_GLOBAL_NOTIFYDSC', 'Allgemeine News-Benachrichtigungsoptionen.');

define('_MI_NEWS_STORY_NOTIFY', 'Artikel');
define('_MI_NEWS_STORY_NOTIFYDSC', 'Benachrichtigungsoptionen die den aktuellen Artikel betreffen.');

define('_MI_NEWS_GLOBAL_NEWCATEGORY_NOTIFY', 'Neues Thema');
define('_MI_NEWS_GLOBAL_NEWCATEGORY_NOTIFYCAP', 'Benachrichtigen wenn ein neues Thema angelegt worden ist.');
define('_MI_NEWS_GLOBAL_NEWCATEGORY_NOTIFYDSC', 'Benachrichtigung wenn ein neues Thema angelegt worden ist.');
define('_MI_NEWS_GLOBAL_NEWCATEGORY_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} Automatische Benachrichtigung: Neues Thema');

define('_MI_NEWS_GLOBAL_STORYSUBMIT_NOTIFY', 'Neuer Artikel eingeschickt');       
define('_MI_NEWS_GLOBAL_STORYSUBMIT_NOTIFYCAP', 'Benachrichtigen wenn ein neuer Artikel eingeschickt worden ist (noch freizugeben).');                           
define('_MI_NEWS_GLOBAL_STORYSUBMIT_NOTIFYDSC', 'Benachrichtigung wenn ein neuer Artikel eingeschickt worden ist (noch freizugeben).');                
define('_MI_NEWS_GLOBAL_STORYSUBMIT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} Automatische Benachrichtigung: Neuer Artikel eingeschickt');                              

define('_MI_NEWS_GLOBAL_NEWSTORY_NOTIFY', 'Neuer Artikel');       
define('_MI_NEWS_GLOBAL_NEWSTORY_NOTIFYCAP', 'Benachrichtigen wenn ein neuer Artikel veröffentlicht worden ist.');
define('_MI_NEWS_GLOBAL_NEWSTORY_NOTIFYDSC', 'Benachrichtigung wenn ein neuer Artikel veröffentlicht worden ist.');
define('_MI_NEWS_GLOBAL_NEWSTORY_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} Automatische Benachrichtigung: Neuer Artikel veröffentlicht');                              

define('_MI_NEWS_STORY_APPROVE_NOTIFY', 'Artikel freigegeben');
define('_MI_NEWS_STORY_APPROVE_NOTIFYCAP', 'Benachrichtigen wenn dieser Artikel freigegeben worden ist.');
define('_MI_NEWS_STORY_APPROVE_NOTIFYDSC', 'Benachrichtigung wenn dieser Artikel freigegeben worden ist.');
define('_MI_NEWS_STORY_APPROVE_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} Automatische Benachrichtigung: Artikel freigegeben');

// WYSIWYG Defines
define('_MI_WYSIWYG',' Wysiwyg-Editor benutzen?');
define('_MI_WYSIWYG_DESC','');
define('_MI_USER_WYSIWYG',' Wysiwyg-Editor für Mitglieder?');
define('_MI_USER_WYSIWYG_DESC','');
define('_MI_LBRCONVERT','Keine Linebreak-Konvertierung auf der Startseite?');
define('_MI_LBRCONVERT_DESC','Sollte aktiviert sein wenn der Wysiwyg-Editor benutzt wird');
?>
