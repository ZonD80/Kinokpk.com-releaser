<?php
	/**
	 * @desc			This language-file provides systemMessages from IPBWI in your foreign language.
	 * @copyright		2007-2009 IPBWI development team
	 * @package			Languages
	 * @author			Matthias Reuter ($LastChangedBy: matthias $)
	 * @license			http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
	 * @version			$LastChangedDate: 2009-08-26 19:19:41 +0200 (Mi, 26 Aug 2009) $
	 * @since			2.0
	 * @web				http://ipbwi.com
	 */

	// Define Encoding and localisation
	$liblang['encoding']	= 'ISO-8859-1';
	$liblang['local']		= 'fr_FR';

	// attachment
	$libLang['attachMimeNotFound']					= 'Le type MIME requis n\'est pas d�fini.';
	$libLang['attachNotFoundFS']					= 'Le fichier joint n\'a pas �t� trouv� dans le syst�me de fichiers.';
	$libLang['attachNotFoundDB']					= 'Le fichier joint n\'a pas �t� trouv� dans la base de donn�es.';
	$libLang['attachCreated']						= 'Le fichier a �t� joint avec succ�s.';
	$libLang['attachCreationFailed']				= 'Echec lors de l\'attachement du fichier.';
	$libLang['attachFileNotInUploadDir']			= 'Le fichier joint n\'a pas �t� trouv� dans le dossier ad�quat.';
	$libLang['attachFileExtNotExists']				= 'Cette extension de fichier n\'existe pas dans la base de donn�es.';
	$libLang['attachFileExtNotAllowed']				= 'Cette extension de fichier n\'est pas autoris�e.';
	$libLang['attachFileTooBig']					= 'La pi�ce jointe est trop grosse.';
	$libLang['attachFileExceedsUserSpace']			= 'Votre quota m�moire ne vous permet pas de joindre ce fichier.';

	// captcha
	$libLang['badKey']								= 'La cl� n\'existe pas.';
	$libLang['captchaWrongCode']					= 'Le texte tap� dans le captcha est incorrect.';

	// forum
	$libLang['catNotExist']							= 'Cette cat�gorie n\'existe pas.';
	$libLang['forumNotExist']						= 'Ce forum n\'existe pas.';

	// member
	$libLang['badMemID']							= 'Idenfiant d\'utilisateur invalide.';
	$libLang['badMemPW']							= 'Mot de passe incorrect ou invalide.';
	$libLang['cfMissing']							= 'Un ou plusieurs champs de profil requis sont manquants.';
	$libLang['cfLength']							= 'Un ou plusieurs champs de profil requis d�passent la taille autoris�e.';
	$libLang['cfInvalidValue']						= 'Valeur invalide.';
	$libLang['cfMustFillIn']						= 'Le champ de profil "%s" doit �tre compl�t�.';
	$libLang['cfCantEdit']							= 'Impossible d\'�diter le champ de profil "%s".';
	$libLang['cfNotExist']							= 'Le champ de profil "%s" n\'existe pas.';
	$libLang['accBanned']							= 'Ce membre a �t� banni.';
	$libLang['accUser']								= 'Le nom d\'utilisateur sp�cifi� n\'existe pas.';
	$libLang['accPass']								= 'Le mot de passe sp�cifi� est invalide.';
	$libLang['accEmail']							= 'L\'adresse email sp�cifi�e est invalide.';
	$libLang['accTaken']							= 'Le nom d\'utilisateur ou l\'adresse email sp�cifi�(e) est d�j� utilis�e.';
	$libLang['loginNoFields']						= 'Merci de sp�cifier votre nom d\'utilisateur et votre mot de passe.';
	$libLang['loginLength']							= 'Le nom d\'utilisateur et/ou le mot de passe sp�cifi�(s) est(sont) trop long(s).';
	$libLang['loginMemberID']						= 'Pas d\'identifiant utilisateur.';
	$libLang['loginWrongPass']						= 'Le mot de passe est incorrect.';
	$libLang['loginNoMember']						= 'Le membre n\'existe pas.';
	$libLang['noAdmin']								= 'Des droits administrateur sont requis pour cette section.';
	$libLang['membersOnly']							= 'Cette fonctionnalit� n\'est disponible qu\'aux membres enregistr�s.';
	$libLang['sigTooLong']							= 'La signature est trop longue.';
	$libLang['groupIcon']							= 'Ic�ne du groupe';
	$libLang['avatarSuccess']						= 'La mise � jour de l\'avatar a �t� effectu�e avec succ�s.';
	$libLang['avatarError']							= 'La mise � jour de l\'avatar a �chou�.';
	$libLang['reg_username']						= 'Username: ';
	$libLang['reg_dname']							= 'Display Name: ';
	
	// permissions
	$libLang['badPermID']							= 'Identifiant de permission invalide.';
	$libLang['noPerms']								= 'Vous n\'avez pas la permission d\'effectuer cette action.';

	// pm
	$libLang['pmFolderNotExist']					= 'Le dossier n\'existe pas.';
	$libLang['pmMsgNoMove']							= 'Impossible de d�placer le message.';
	$libLang['pmFolderNoRem']						= 'Impossible de supprimer le dossier.';
	$libLang['pmNoRecipient']						= 'Le destinataire n\'a pas �t� sp�cifi�.';
	$libLang['pmTitle']								= 'Titre du message invalide.';
	$libLang['pmMessage']							= 'Message invalide.';
	$libLang['pmMemNotExist']						= 'Le membre n\'existe pas.';
	$libLang['pmMemDisAllowed']						= 'Le membre sp�cifi� ne peut pas utiliser sa messagerie priv�e.';
	$libLang['pmMemFull']							= 'La bo�te de r�ception du destinataire est pleine.';
	$libLang['pmMemBlocked']						= 'Ce membre vous a bloqu�(e).';
	$libLang['pmCClimit']							= 'Vous ne pouvez pas envoyer ce message en CC � autant d\'utilisateurs.';
	$libLang['pmRecDisallowed']						= 'Un des destinataires ne peut pas utiliser sa messagerie priv�e.';
	$libLang['pmRecFull']							= 'Un des destinataires a sa bo�te de r�ception pleine.';
	$libLang['pmRecBlocked']						= 'Un des destinataires vous a bloqu�(e).';
	$libLang['pmCantSendToSelf']					= 'You cannot send a conversation to yourself';

	// poll
	$libLang['pollAlreadyVoted']					= 'Vous avez d�j� vot� dans ce sondage.';
	$libLang['pollInvalidVote']						= 'Vote invalide.';
	$libLang['pollNotExist']						= 'Ce sondage n\'existe pas.';
	$libLang['pollInvalidOpts']						= 'Vous devez sp�cifier entre 2 et %s options.';
	$libLang['pollInvalidQuestions']				= 'Vous devez sp�cifier entre 1 et %s questions.';

	// topic
	$libLang['topicNotExist']						= 'Le topic n\'existe pas.';
	$libLang['topicNoTitle']						= 'Vous devez entrer un titre pour le topic.';

	// post
	$libLang['floodControl']						= 'Tentative de flood? Patientez "%s" secondes avant d\'essayer de poster � nouveau.';
	$libLang['postNotExist']						= 'Ce message n\'existe pas.';

	// search
	$libLang['searchIDnotExist']					= 'Cet identifiant de recherche n\'existe pas.';
	$libLang['searchNoResults']						= 'Aucun r�sultat.';

	// skin
	$libLang['skinNotExist']						= 'Ce skin n\'existe pas.';

	// tag cloud
	$libLang['badTag']								= 'Vous devez sp�cifier un nom de tag valide.';
	$libLang['badDestination']						= 'Vous devez sp�cifier une destination valide.';
	$libLang['badTagID']							= 'Vous devez sp�cifier un identifiant de tag valide.';

	// wordpress
	$libLang['wpRegisterNameExists']				= 'Ce nom d\'utilisateur est d�j� utilis� par un compte existant. Merci de faire un autre choix.';
	$libLang['wpRegisterEmailExists']				= 'Cette adresse email est d�j� utilis�e par un compte existant. Merci de faire un autre choix.';

	// months
	$libLang['month_1']								= 'janvier';
	$libLang['month_2']								= 'f�vrier';
	$libLang['month_3']								= 'mars';
	$libLang['month_4']								= 'avril';
	$libLang['month_5']								= 'mai';
	$libLang['month_6']								= 'juin';
	$libLang['month_7']								= 'juillet';
	$libLang['month_8']								= 'ao�t';
	$libLang['month_9']								= 'septembre';
	$libLang['month_10']							= 'octobre';
	$libLang['month_11']							= 'novembre';
	$libLang['month_12']							= 'd�cembre';


	// system messages
	$libLang['sysMsg_Success']						= 'OK: ';
	$libLang['sysMsg_Error']						= 'Erreur: ';
	$libLang['sysMsg_Hidden']						= 'Message cach�: ';

?>