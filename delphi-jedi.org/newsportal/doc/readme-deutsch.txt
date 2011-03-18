
                                News Portal
                                      
   Version 0.24pre6
   
Einleitung

   Newsportal ist ein PHP-Basierter Newsreader. Es steht unter der GNU
   Public License (siehe beiliegende LICENSE).
   
Überblick

   Diese Skriptsammlung ermöglicht von einer Webseite aus den Zugriff auf
   einen Newsserver (per NNTP). Man kann damit Webforen und Newsgruppen
   verbinden, so daß auf ein "Webforum" auch per NNTP zugegriffen werden
   kann. Dieses Skript eignet sich auch für die Präsentation von
   Announce-Newsgruppen auf Webseiten, ohne daß der Benutzer merkt, daß
   er in Wirklichkeit auf einen Newsserver zugreift.
   
   Die eigentliche Funktionalitäts des Skripts liegt in der Datei
   newsportal.php, in der die meisten php-Funktionen untergebracht sind.
   Zusätzlich gibt es vier weitere php-Dateien, auf die direkt mit dem
   Browser zugegriffen wird:
     * index.php zeigt die auf dem Newsserver verfügbaren Newsgruppen an
       (sofern sie auch in die Datei groups.txt eingetragen sind).
     * thread.php zeigt die Artikel in einer Newsgruppe an.
     * article.php zeigt einen einzelnen Artikel an.
     * post.php schreibt eine Nachricht in die passende Newsgruppe.
     * attachment.php zeit mögliche Attachments an.
       
   Neben dan Dateien, auf die direkt zugegriffen wird, gibt es eine Zahl
   weiterer Dateien, die das Verhalten von Newsportal steuern oder
   wichtige Daten enthalten:
     * config.inc enthält die Einstellungen
     * head.inc enthält den Kopf jeder von Newsportal ausgegebenen Seite,
       wie den HTML-title und das Body-Tag mit den Farbeinstellungen für
       die Webseiten.
     * tail.inc enthält das Ende jeder generierten Seite.
     * deutsch.lang: Die deutschen Sprachdefinitionen
     * english.lang: Die englischen Sprachdefinitionen
       
   Da das Abfragen der Artikelübersicht vom Newsserver viel Zeit
   beansprucht, werden diese Dateien im Verzeichnis spool/
   zwischengespeichert. Die Dateien dort drin können nach belieben
   gelöscht werden, sie werden bei Bedarf neu angelegt.
   
Installation:

    1. Das Archiv in ein Verzeichnis entpacken.
    2. Die Datei config.inc muß angepasst werden (für einen Schnellstart
       müssen $server, $port, $title und gegebenenfalls $readonly
       verändert werden).
    3. In die Datei groups.txt werden alle Newsgruppen eingetragen, die
       Newsportal anzeigen soll. Optional kann man hinter den
       Gruppennamen von einem Leerzeichen getrennt eine Beschreibung
       eintragen, die dann von der index.php3 angezeigt wird. Fehlt
       diese, wird die Beschreibung vom Newsserver angefordert.
    4. Das Verzeichnis spool muß mit "chmod 777 spool" für jeden les- und
       schreibbar gemacht werden. Nach einem Update kann es passieren,
       daß das Skript ohne das Löschen aller Dateien in diesem
       Verzeichnis nicht funktioniert. Das kommt aber darauf an, ob ich
       das Format dieser Dateien geändert habe. NewsPortal erkennt
       normalerweise fehlerhafte Spooldateien und löscht sie
       gegebenenfalls.
    5. In der Datei head.inc den Zeichensatz anpassen, falls man sich
       nicht in Westeuropa oder den USA befindet, wo die Voreinstellung
       von iso-8859-1 richtig ist.
       
Konfiguration

   Folgende Einstellungen können in der config.inc vorgenommen werden:
   
   Verzeichnisse und Dateien:
     * $file_newsportal="newsportal.php": Name des newsportal-Skripts
     * $file_groups="index.php": Die Gruppenübersicht
     * $file_thread="thread.php": Die Artikelübersicht
     * $file_article="article.php": Zeigt den Artikel an
     * $file_post="post.php": einen Artikel schreiben. Die Datei kann
       entfernt werden, wenn das System auf readonly gesetzt ist (siehe
       unten)
     * $file_language="deutsch.lang": Verweis auf die
       Sprachdefinitionsdatei.
     * $file_footer: Hier kann Optional der Name einer Datei angegeben
       werden, die an jede über Newsportal verschickte Nachricht
       angehängt wird.
       
   Servereinstellungen
     * $server: Adresse des Newsservers
     * $port: Port des Newsservers, normalerweise 119
     * $post_server: Optional kann hier für das Schreiben von Artikeln
       ein eigener Server angegeben werden. Es ist dann natürlich so, daß
       ein Artikel eine Zeit braucht, bis er vom $post_server zum $server
       gelangt ist.
     * $post_port: Port des Post-Newsservers, normalerweise 119
     * $maxfetch: Hier wird die maximal bei einem Aufruf von thread.php3
       vom Newsserver anzufordernden Artikelübersichten beschränkt. Auf
       "0" gesetzt werden so viele Artikel angefordert, wie neu zur
       Verfügung stehen, jede andere Nummer legt die Maximalanzahl fest.
       Diese Option sollte nur dann auf etwas anderes als 0 gesetzt
       werden, wenn es Probleme mit der Geschwindigkeit gibt.
     * $initialfetch: Bei einem Neuaufbau der Overview-Spooldatei wird
       statt $maxfech viele Artikel maximal $initialfetch viele Artikel
       abgeholt. Bei "0" ist es auch ungeschränkt.
     * $server_auth_user: Falls der Newsserver durch Name und Passwort
       gschützt wird, kann hier der Username angegeben werden. Ansonsten
       einfach die Variable auf "" setzen.
     * $server_auth_pass: Hier wird das zum Usernamen passende Passwort
       angegeben.
       
   Threaddarstellung
     * $treestyle: Setzt das Aussehen des Nachrichtenbaums.
          + 0: Einfache Auflistung der Artikel
          + 1: Einfache Auflistung der Artikel, jedoch als
            HTML-Auflistung
          + 2: Einfache Auflistung in Tabellenform
          + 3: Thread mit HTML-Auflistungen
          + 4: Thread aus Textzeichen
          + 5: Thread auf Graphikelementen
          + 6: Thread aus Textzeichen mit Tabelle
          + 7: Thread aus Grafikzeichen mit Tabelle
     * $thread_fontPre: Der Inhalt dieser Variable wird vor Texten in der
       Threadansicht ausgegeben. Diese Variable ist gedacht um z.B. die
       Textgröße der Texte zu ändern. Standardmäß wird dort der Font
       klein gestellt. Das ist bei alle Threadstyles sinnvoll, wo mit
       Tabellen gearbeitet wird, bei allen anderen ist es schöner die
       Variable auf einen leeren String zu setzen.
     * $thread_fontPost: Das gleiche wie $thread_fontPre, nur daß dieser
       String nach Textausgaben ausgegeben wird.
     * $thread_showDate,
       $thread_showSubject,
       $thread_showAuthor:
          + true: das Datum / das Subject / der Autor wird im Artikelbaum
            angezeigt
          + false: Darstellung wird unterdrückt.
     * $thread_maxSubject: Anzahl der Zeichen, die vom Subject in der
       Artikelübersicht angezeigt werden.
     * $maxarticles: Gibt die Anzahl der Artikel an, die maximal im
       Artikelbam angezeigt werden. "0" bedeutet keine Beschränkung. Es
       werden immer die letzten x Artikel angezeigt, wo wie sie auf dem
       Newsserver liegen. Das muß nicht unbedingt mit dem Erstelldatum
       des Artikels übereinstimmen.
     * $maxarticles_extra: Das Problem beim Betrieb mit $maxarticles ist,
       daß alle Artikeldaten vom Newsserver komplett neu angefordert
       werden müssen, wenn der angegebene Wert überschritten worden ist.
       Damit dies nicht ganz so oft vorkommt, kann $maxarticles_extra
       gesetzt werden. Dann wird die Artikeldatenbank erst neu aufgebaut,
       wenn $maxarticles + $maxarticles_extra Artikel vorliegen, wobei
       dann $maxarticles viele Artikeldaten angefordert werden. Wenn man
       mit $maxarticles arbeitet, weil die Newsgruppen zu groß sind,
       sollte man unbedingt immer auch mit $maxarticles_extra arbeiten.
       Der Wert sollte etwa 20% von $maxarticles betragen. Nur dann, wenn
       man wirklich eine ganz genau vorgegebene Anzahl von Artikeln auf
       einer Webseite anzeigen will, sollte man hier den Wert auf Null
       setzen.
     * $age_count: Anzahl der verschiedenen Altersstufen für die
       farbliche Markierung von Artikeln
     * $age_time[n]: maximales Alter eines Artikels in Sekunden, so daß
       der Artikel mit der Farbe $age_color[n] markiert wird. n ist eine
       natürliche Zahl >= 1, wobei alle Zahlen von 1 bis n vergeben sein
       müssen, Lücken sind also nicht erlaubt.
     * $age_color[n]: Die Farbe, mit dem der Artikel markiert wird
     * $thread_sorting: Die Sortierreihenfolge für die Artikel:
          + 0: Keine Sortierung, die Artikel werden in der Reihenfolge
            angezeigt, in der sie vom Newsserver kommen.
          + 1: aufsteigende Sortierung, die ältesten Artikel zu oberst
          + -1: absteigende Sortierung, die neusten Artikel zu oberst
       Es ist zu beachten, daß die Artikel in einer Baumstruktur
       angezeigt werden, so daß der oberste Artikel eines Teilbaums immer
       den Ausschlag gibt.
     * $articles_per_page: Ist dieser Wert ungleich 0, so gibt er die
       Anzahl der Artikel an, die auf einer Seite gleichzeitig angezeigt
       werden sollen. Es gibt dann Links um die einzelnen Seiten zu
       wechseln. Benutzt man diese Option, so sollte man $maxarticles
       beachten: Diese Variable gibt nämlich auch an, wieviele Artikel in
       den Spooldateien gespeichert werden, sa daß ein zu hoher Wert
       trotz Seiteneinteilung die Geschwindigkeit herabsetzen kann.
     * $startpage: In Verbindung mit $articles_per_page wird hier
       angegeben, welche Seite bei Aufruf des Threads angezeigt werden
       soll:
          + "first": zeigt zuerst die erste Seite an.
          + "last": zeigt die letzte Seite an.
       Die Angabe sollte mit $thread_sorting abgestimmt werden. "first"
       für 0 und 1, und "last" für -1.
       
   Artikeldarstellung
     * $article_show["Subject"],
       $article_show["From"],
       $article_show["Newsgroups"],
       $article_show["Organization"],
       $article_show["Date"],
       $article_show["Message-ID"],
       $article_show["User-Agent"],
       $article_show["References"]: Bei "true" wird die jeweilige
       Headerzeile in der Artikelansicht angezeigt, bei "false" wird sie
       unterdrückt. Momentan ist die Ansicht weiterer Headerzeilen nicht
       möglich.
       
   Attachments
     * $attachment_show: true oder false, je nochdem ob die Dekodierung
       von Attachments unterstützt werden soll. Ist sie deaktiviert,
       werden die möglichen Attachments im Rohformat angezeigt.
     * $attachment_delete_alternative: true oder false. Wenn ein Artikel
       mehr als einen Body in verschiedenen Formaten hat (multipart
       alternative), dann werden alle überflüssigen Alternativen
       verworfen.
     * $attachment_uudecode: true oder false. Aktiviert die Dekodierung
       von uuencoded Attachments. Momentan sehr langsam und fehlerhaft.
       
   Frameunterstützung
   Beispieldateien für die Frameunterstützung liegen in extras/frames/.
   In dieser Sektion werden die targets für diverse Links definiert, also
   in welchem Frame welche Webseite dargestellt werden soll. In der
   config.inc muß statt "thread.php3" "thread_frameset.php3" eingetragen
   werden.
     * $frame_article: Name des Artikel-Frames. Muß mit dem Namen in
       thread_frameset.php3 übereinstimmen
     * $frame_thread: Name des Thread-Frames;
     * $frame_groups: Name des Frames für die Gruppenübersicht,
       normalerweise "_top".
     * $frame_post: Name des Schreiben-Frames
     * $frame_threadframeset: Frame, in dem der Frameset erscheinen soll,
       der den Artikel- und Thread-Frame aufnimmt. Normalerweise "_top".
     * $frame_externallink: Zielframe für extrerne Links innerhalb von
       Artikeln
       
   Sicherheitseinstellungen
     * $send_poster_host: bei "true" wird bei jeder geschriebenen
       Nachricht noch die Zeile "X-HTTP-Posting-Host: " in den Header
       geschrieben, und der Name des Rechners eingetragen, der die
       Nachricht abgeschickt ist. Das kann als Ersatz von
       "NNTP-Posting-Host" angesehen werden, dessen Wert beim Einsatz von
       Newsportal immer nur den Namen des Webservers anzeigt.
     * $readonly: wenn auf "true" gesetzt, kann man keine Artikel in
       Gruppen schreiben.
     * $testgroup: auf "true" gesetzt wird beim Anzeigen des Threads
       überprüft, ob betreffende Gruppe in die "groups.txt" eingetragen
       ist. Andernfalls könnte man über das direkte Eintragen der
       richtigen URL eine Gruppe einsehen, obwohl diese nicht in der
       Gruppenübersicht angezeigt wird.
     * $validate_email: Hier kann eingestellt werden, wie Newsportal beim
       Posten eine angegebene eMail-Adresse auf richtigkeit prüft:
          + 0: keine Überprüfung. Ist nicht zu empfehlen, da
            normalerweise der Newsserver eine Fehlermeldung liefert, wenn
            die Adresse syntaktisch Falsch ist.
          + 1: Ü:berprüft die Adresse auf syntaktische Richtigkeit.
          + 2: hier wird zusätzlich überprüft, ob zu der angegebenen
            Domain ein MX oder A Record existiert.
       
   Allgemeines
     * $title: Name des Systems, wird als Überschrift verwendet
     * $organization: Die Organisation für den NNTP-Header beim Schreiben
       von Nachrichten
     * $setcookies: Erlaubt dem Benutzer, seinen Namen und seine
       eMail-Adresse beim schreiben eines Artikels als Cookie
       abzuspeichern, so daß die Daten beim erneuten Schreiben eines
       Artikels automatisch eingesetzt werden.
     * $compress_spoolfiles: Hier kann eingestellt werden, ob die
       Spooldateien komprimiert werden sollen. Dies ist im Normalfall
       empfohlen, da auf etwa 10 bis 15% der Originalgröße komprimiert
       wird. Bei aelteren PHP-Versionen muß man diese Variable jedoch auf
       false setzen, falls diese Kompression noch nicht unterstützen.
       
Sicherheitshinweise

   Ein paar Kleinigkeiten müßen beachtet werden, damit NewsPortal nicht
   zu einem Sicherheitsloch werden soll:
     * Zu Debugzwecken wird immer der User-Agent in der Artikelansicht
       mitübermittelt, wenn die Anzeige ($article_show["User-Agent"])
       abgeschaltet ist, ist der Eintrag lediglich unsichtbar.
     * Die config.inc kann solange von jedem Websurfer (der den
       Dateinamen kennt) abgerufen werden, wie die Datei nicht in einen
       geschützten Bereich des Webservers verschoben worden ist.
       
   Dieses Skript ist nur für den Zugriff auf lokale Newsgruppen gedacht.
   Wenn Gruppen des UseNet im Web verfügbar sind, ergeben sich folgende
   Probleme:
     * Spammer können anonym ($send_poster_host beachten!) Artikel
       abschicken
     * Es gibt im Internet Listen mit sogenannten "offenen" Newsservern.
       Offen heißt hier meist nicht, daß die jeder benutzen darf, sondern
       daß diese einfach nur nicht ordentlich gesichert worden sind.
       Bevor Du also einen solchen Newsserver benutzt, solltest Du Dich
       vergewissern, daß der Betreiber nichts gegen Dein Vorhaben
       einzuwenden hat.
     * Es wird im UseNet oft nicht gerne gesehen, wenn Personen anonym in
       Newsgruppen schreiben können. Bevor Du also schreibenden Zugriff
       auf eine Newsgruppe erlaubst, solltest Du in der betreffenden
       Gruppe nachfragen, ob es dort keine Einwände gibt. Etwas anderes
       ist es natürlich, wenn Du NewsPortal in einem geschützten Bereich
       Deines Webservers betreibst, auf den nur eine Dir bekannte
       Benutzergruppe zugreifen kann. Gib keinen öffentlichen
       Schreibzugriff auf UseNet Newsgruppen, wenn Du nicht ganz genau
       weißt, was Du tust!
       
   Die Benutzung des Skripts erfolgt auf eigene Gefahr!
   
Kompatiblität

   Sollte auf jedem PHP4-Fähigen Webserver zusammen mit jedem
   NNRP-fähigen Newsserver laufen. Webserver und Newsserver müßen nicht
   auf der selben Maschine laufen.
   
Kontakt:

   Florian Amrhein
   eMail: florian.amrhein@web.de
   WWW: http://florian-amrhein.de
