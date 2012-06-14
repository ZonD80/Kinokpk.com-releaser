<?php
/**
 * @desc            This language-file provides systemMessages from IPBWI in your foreign language.
 * @copyright        2007-2010 IPBWI development team
 * @package            Languages
 * @author            Matthias Reuter ($LastChangedBy: matthias $)
 * @license            http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 * @version            $LastChangedDate: 2009-08-26 19:19:41 +0200 (Mi, 26 Aug 2009) $
 * @since            2.0
 * @web                http://ipbwi.com
 */

// Define Encoding and localisation
$liblang['encoding'] = 'ISO-8859-1';
$liblang['local'] = 'de_DE';

// attachment
$libLang['attachMimeNotFound'] = 'Der angeforderte mimetype ist nicht definiert.';
$libLang['attachNotFoundFS'] = 'Der Dateianhang konnte auf dem Dateisystem nicht gefunden werden.';
$libLang['attachNotFoundDB'] = 'Der Dateianhang konnte in der Datenbank nicht gefunden werden.';
$libLang['attachCreated'] = 'Erstellung des Dateianhangs erfolgreich.';
$libLang['attachCreationFailed'] = 'Die Erstellung des Dateianhangs ist fehlgeschlagen.';
$libLang['attachFileNotInUploadDir'] = 'Der ausgewählte Anhang konnte im Upload-Verzeichnis nicht gefunden werden.';
$libLang['attachFileExtNotExists'] = 'Die Dateiendung des Anhang ist nicht in der Datenbank vorhanden.';
$libLang['attachFileExtNotAllowed'] = 'Die Dateiendung des Anhangs ist nicht erlaubt.';
$libLang['attachFileTooBig'] = 'Der Dateianhang ist zu groß.';
$libLang['attachFileExceedsUserSpace'] = 'Der Dateianhang übersteigt deinen maximalen Dateiplatz.';

// captcha
$libLang['badKey'] = 'Der Captcha Key existiert nicht.';
$libLang['captchaWrongCode'] = 'Der eingegebene Captcha-Code war falsch.';

// forum
$libLang['catNotExist'] = 'Die Kategorie existiert nicht.';
$libLang['forumNotExist'] = 'Dieses Forum existiert nicht.';

// member
$libLang['badMemID'] = 'Ungültige Benutzer ID';
$libLang['badMemPW'] = 'Falsches oder ungültiges Benutzerpasswort';
$libLang['cfMissing'] = 'Eins oder mehrer der erforderliche Benutzerprofilfelder wurde nicht angegeben.';
$libLang['cfLength'] = 'Eins oder mehrer der erforderliche Benutzerprofilfelder sind zu lang.';
$libLang['cfInvalidValue'] = 'Ungültiger Wert';
$libLang['cfMustFillIn'] = 'Das Benutzerprofilfeld "%s" muss ausgefüllt werden.';
$libLang['cfCantEdit'] = 'Das Benutzerprofilfeld "%s" kann nicht geändert werden.';
$libLang['cfNotExist'] = 'Das Benutzerprofilfeld "%s" existiert nicht.';
$libLang['accBanned'] = 'Das Mitglied wurde gebanned';
$libLang['accUser'] = 'Der angegebene Benutzername ist ungültig.';
$libLang['accPass'] = 'Der angegebene Passwort ist ungültig.';
$libLang['accEmail'] = 'Die angegebene E-Mail-Adresse ist ungültig.';
$libLang['accTaken'] = 'Der angegebene Benutzername oder die E-Mail Adresse sind vergeben und werden bereits verwendet.';
$libLang['loginNoFields'] = 'Bitte gib den Benutzernamen und das Passwort an.';
$libLang['loginLength'] = 'Benutzername oder Passwort sind zu lang.';
$libLang['loginMemberID'] = 'Keine Mitglieder ID';
$libLang['loginWrongPass'] = 'Das Passwort ist ungültig.';
$libLang['loginNoMember'] = 'Das Mitglied existiert nicht.';
$libLang['noAdmin'] = 'Adminrechte für diese Aktion benötigt.';
$libLang['membersOnly'] = 'Die Funktion ist nur für registrierte Mitglieder verfügbar.';
$libLang['sigTooLong'] = 'Die Signatur ist zu lang.';
$libLang['groupIcon'] = 'Gruppen-Symbol';
$libLang['avatarSuccess'] = 'Avatar erfolgreich aktualisiert.';
$libLang['avatarError'] = 'Avatar konnte nicht aktualisiert werden.';
$libLang['reg_username'] = 'Benutzername: ';
$libLang['reg_dname'] = 'Anzeigename: ';

// permissions
$libLang['badPermID'] = 'Ungültige Permission ID';
$libLang['noPerms'] = 'Du hast nicht die erforderliche Berechtigung um diese Aktion auszuführen.';

// pm
$libLang['pmFolderNotExist'] = 'Der Ordner existiert nicht.';
$libLang['pmMsgNoMove'] = 'Die Nachricht konnte nicht verschoben werden.';
$libLang['pmFolderNoRem'] = 'Der Ordner kann nicht gelöscht werden.';
$libLang['pmNoRecipient'] = 'Kein Empfänger angegeben.';
$libLang['pmTitle'] = 'Ungültiger Nachrichtentitel.';
$libLang['pmMessage'] = 'Ungültiger Nachrichtentext.';
$libLang['pmMemNotExist'] = 'Das Mitglied existiert nicht.';
$libLang['pmMemDisAllowed'] = 'Das angegebene Mitglied kann das PM-System nicht verwenden.';
$libLang['pmMemFull'] = 'Der Posteingang des Mitglieds ist voll.';
$libLang['pmMemBlocked'] = 'Das Mitglied hat dich geblockt.';
$libLang['pmCClimit'] = 'Du kannst keine Kopien an so viele Mitglieder senden.';
$libLang['pmRecDisallowed'] = 'Einer der angegebene Empfänger kann das PM-System nicht verwenden.';
$libLang['pmRecFull'] = 'Der Posteingang des Empfängers ist voll.';
$libLang['pmRecBlocked'] = 'Der Empfänger hat dich geblockt.';
$libLang['pmCantSendToSelf'] = 'Du kannst keine Nachricht an dich selbst senden.';

// poll
$libLang['pollAlreadyVoted'] = 'Du hast in dieser Umfrage bereits abgestimmt.';
$libLang['pollInvalidVote'] = 'Ungültige Stimme.';
$libLang['pollNotExist'] = 'Diese Umfrage existiert nicht.';
$libLang['pollInvalidOpts'] = 'Du must zwischen 2 und %s Optionen angeben.';
$libLang['pollInvalidQuestions'] = 'Du must zwischen 1 und %s Optionen Fragen.';

// topic
$libLang['topicNotExist'] = 'Dieses Thema existiert nicht.';
$libLang['topicNoTitle'] = 'Du hast keinen Titel für das Thema angegeben.';

// post
$libLang['floodControl'] = 'Schutz vor Überflutung - Bitte warte weitere "%s" Sekunden bevor du ein weiteres Posting erstellst.';
$libLang['postNotExist'] = 'Der Post existiert nicht.';

// search
$libLang['searchIDnotExist'] = 'Die Search ID existiert nicht.';
$libLang['searchNoResults'] = 'Es wurden keine Ergebnisse gefunden.';

// skin
$libLang['skinNotExist'] = 'Der Skin existiert nicht.';

// tag cloud
$libLang['badTag'] = 'Es muss ein Tag Name angegeben werden.';
$libLang['badDestination'] = 'Es muss eine Destination angegeben werden.';
$libLang['badTagID'] = 'Es muss eine valide Tag ID angegeben werden.';

// wordpress
$libLang['wpRegisterNameExists'] = 'Dieser Benutzername wird bereits von einem Board-Account verwendet. Bitte wähle einen anderen.';
$libLang['wpRegisterEmailExists'] = 'Diese Emailadresse wird bereits von einem Board-Account verwendet. Bitte wähle eine andere.';

// months
$libLang['month_1'] = 'Januar';
$libLang['month_2'] = 'Februar';
$libLang['month_3'] = 'März';
$libLang['month_4'] = 'April';
$libLang['month_5'] = 'Mai';
$libLang['month_6'] = 'Juni';
$libLang['month_7'] = 'Juli';
$libLang['month_8'] = 'August';
$libLang['month_9'] = 'September';
$libLang['month_10'] = 'Oktober';
$libLang['month_11'] = 'November';
$libLang['month_12'] = 'Dezember';

// system messages
$libLang['sysMsg_Success'] = 'Erfolg: ';
$libLang['sysMsg_Error'] = 'Fehler: ';
$libLang['sysMsg_Hidden'] = 'Ausgeblendeter Hinweis: ';

?>