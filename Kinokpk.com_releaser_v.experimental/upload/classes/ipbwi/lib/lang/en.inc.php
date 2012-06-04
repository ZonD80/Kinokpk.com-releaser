<?php
	/**
	 * @desc			This language-file provides systemMessages from IPBWI in your foreign language.
	 * @copyright		2007-2010 IPBWI development team
	 * @package			Languages
	 * @author			Matthias Reuter ($LastChangedBy: matthias $)
	 * @license			http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
	 * @version			$LastChangedDate: 2009-08-26 19:19:41 +0200 (Mi, 26 Aug 2009) $
	 * @since			2.0
	 * @web				http://ipbwi.com
	 */

	// Define Encoding and localisation
	$liblang['encoding']	= 'ISO-8859-1';
	$liblang['local']		= 'en_US';

	// attachment
	$libLang['attachMimeNotFound']					= 'The requested mimetype is not defined.';
	$libLang['attachNotFoundFS']					= 'Attachment not found on filesystem.';
	$libLang['attachNotFoundDB']					= 'Attachment not found in database.';
	$libLang['attachCreated']						= 'Attachment successful created.';
	$libLang['attachCreationFailed']				= 'Attachment creation failed.';
	$libLang['attachFileNotInUploadDir']			= 'Selected attachment not found in upload dir.';
	$libLang['attachFileExtNotExists']				= 'Attachment file-extension does not exist in Database.';
	$libLang['attachFileExtNotAllowed']				= 'Attachment file-extension is not allowed.';
	$libLang['attachFileTooBig']					= 'Attachment is too big.';
	$libLang['attachFileExceedsUserSpace']			= 'Attachment exceeds max user file space.';

	// captcha
	$libLang['badKey']								= 'Key does not exist.';
	$libLang['captchaWrongCode']					= 'The typed in captcha code was wrong.';

	// forum
	$libLang['catNotExist']							= 'Category does not exist';
	$libLang['forumNotExist']						= 'Forum does not exist.';

	// member
	$libLang['badMemID']							= 'Invalid User ID';
	$libLang['badMemPW']							= 'False or Invalid User Password';
	$libLang['cfMissing']							= 'One or more required custom profile fields were not specified.';
	$libLang['cfLength']							= 'One or more required custom profile fields were too long.';
	$libLang['cfInvalidValue']						= 'Invalid Value';
	$libLang['cfMustFillIn']						= 'Custom Profile Field "%s" must be filled in.';
	$libLang['cfCantEdit']							= 'Cannot edit Custom Profile Field "%s".';
	$libLang['cfNotExist']							= 'Custom Profile Field "%s" does not exist.';
	$libLang['accBanned']							= 'This member is banned';
	$libLang['accUser']								= 'The username specified is not valid.';
	$libLang['accPass']								= 'The password specified is not valid.';
	$libLang['accEmail']							= 'The e-mail address specified is not valid.';
	$libLang['accTaken']							= 'The username or email specified is taken and is currently being used.';
	$libLang['loginNoFields']						= 'Please specify the username and password.';
	$libLang['loginLength']							= 'The username or password were too long.';
	$libLang['loginMemberID']						= 'No Member ID.';
	$libLang['loginWrongPass']						= 'The password was incorrect.';
	$libLang['loginNoMember']						= 'The member does not exist.';
	$libLang['noAdmin']								= 'Adminrights required for this action.';
	$libLang['membersOnly']							= 'This feature is only avaliable to registered members.';
	$libLang['sigTooLong']							= 'Signature was too long.';
	$libLang['groupIcon']							= 'Group Icon';
	$libLang['avatarSuccess']						= 'Avatar-Update successed.';
	$libLang['avatarError']							= 'Avatar-Update failed.';
	$libLang['reg_username']						= 'Username: ';
	$libLang['reg_dname']							= 'Display Name: ';

	// permissions
	$libLang['badPermID']							= 'Invalid Permission ID';
	$libLang['noPerms']								= 'You do not have the required permissions for this action.';

	// pm
	$libLang['pmFolderNotExist']					= 'Folder does not exist.';
	$libLang['pmMsgNoMove']							= 'Could not move message.';
	$libLang['pmFolderNoRem']						= 'Folder cannot be removed.';
	$libLang['pmNoRecipient']						= 'No Recipient Specified.';
	$libLang['pmTitle']								= 'Invalid Message Title.';
	$libLang['pmMessage']							= 'Invalid Message.';
	$libLang['pmMemNotExist']						= 'The member does not exist.';
	$libLang['pmMemDisAllowed']						= 'The member specified cannot use the PM system.';
	$libLang['pmMemFull']							= 'The member\'s inbox is full.';
	$libLang['pmMemBlocked']						= 'The member has blocked you.';
	$libLang['pmCClimit']							= 'You cannot CC messages to so many users.';
	$libLang['pmRecDisallowed']						= 'A recepient specified cannot use the PM system.';
	$libLang['pmRecFull']							= 'A recepient\'s inbox is full.';
	$libLang['pmRecBlocked']						= 'A recepient has blocked you.';
	$libLang['pmCantSendToSelf']					= 'You cannot send a conversation to yourself';

	// poll
	$libLang['pollAlreadyVoted']					= 'You have already voted in this poll.';
	$libLang['pollInvalidVote']						= 'Invalid Vote.';
	$libLang['pollNotExist']						= 'Poll does not exist.';
	$libLang['pollInvalidOpts']						= 'You must specify between 2 and %s  options.';
	$libLang['pollInvalidQuestions']				= 'You must specify between 1 and %s  questions.';

	// topic
	$libLang['topicNotExist']						= 'Topic does not exist.';
	$libLang['topicNoTitle']						= 'You did not enter a topic title.';

	// post
	$libLang['floodControl']						= 'Flood Prevention - Please wait another "%s" seconds before attempting to post.';
	$libLang['postNotExist']						= 'Post does not exist.';

	// search
	$libLang['searchIDnotExist']					= 'Search ID does not exist.';
	$libLang['searchNoResults']						= 'No results were found.';

	// skin
	$libLang['skinNotExist']						= 'Skin does not exist.';

	// tag cloud
	$libLang['badTag']								= 'You have to deliver a Tag Name';
	$libLang['badDestination']						= 'You have to deliver a Destination';
	$libLang['badTagID']							= 'You have to deliver a valid Tag ID';

	// wordpress
	$libLang['wpRegisterNameExists']				= 'This username is still in use by an existing board-account. Please choose another one.';
	$libLang['wpRegisterEmailExists']				= 'This emailaddress is still in use by an existing board-account. Please choose another one.';

	// months
	$libLang['month_1']								= 'January';
	$libLang['month_2']								= 'February';
	$libLang['month_3']								= 'March';
	$libLang['month_4']								= 'April';
	$libLang['month_5']								= 'May';
	$libLang['month_6']								= 'June';
	$libLang['month_7']								= 'July';
	$libLang['month_8']								= 'August';
	$libLang['month_9']								= 'September';
	$libLang['month_10']							= 'October';
	$libLang['month_11']							= 'November';
	$libLang['month_12']							= 'December';


	// system messages
	$libLang['sysMsg_Success']						= 'Success: ';
	$libLang['sysMsg_Error']						= 'Error: ';
	$libLang['sysMsg_Hidden']						= 'Hidden Notice: ';

?>