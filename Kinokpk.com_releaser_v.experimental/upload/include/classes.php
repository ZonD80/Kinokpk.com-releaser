<?php
/**
 * All staff for class manipulation
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

if (!defined("IN_TRACKER") && !defined("IN_ANNOUNCE")) die('Direct access to this file not allowed.');
define ("UC_GUEST", -1);
define ("UC_USER", 0);
define ("UC_POWER_USER", 1);
define ("UC_VIP", 2);
define ("UC_UPLOADER", 3);
define ("UC_MODERATOR", 4);
define ("UC_ADMINISTRATOR", 5);
define ("UC_SYSOP", 6);

/**
 * Returns username with a color by user class
 * @param int $class id of user class
 * @param string $username username to be colored
 * @return string Colored username
 */
function get_user_class_color($class, $username)
{
	global $REL_LANG;
	switch ($class)
	{
		case UC_SYSOP:
			return "<span  title=\"".$REL_LANG->_('Owner')."\">" . $username . "</span>";/*style=\"color:#0F6CEE\"*/
			break;
		case UC_ADMINISTRATOR:
			return "<span  title=\"".$REL_LANG->_('Administrator')."\">" . $username . "</span>";/*style=\"color:green\"*/
			break;
		case UC_MODERATOR:
			return "<span  title=\"".$REL_LANG->_('Moderator')."\">" . $username . "</span>";/*style=\"color:#00cccc\"*/
			break;
		case UC_UPLOADER:
			return "<span  title=\"".$REL_LANG->_('Releaser')."\">" . $username . "</span>"; /*style=\"color:orange\"*/
			break;
		case UC_VIP:
			return "<span style=\"color:#9C2FE0\" title=\"".$REL_LANG->_('VIP')."\">" . $username . "</span>";
			break;
		case UC_POWER_USER:
			return "<span  title=\"".$REL_LANG->_('Power user')."\">" . $username . "</span>"; /*style=\"color:#D21E36\"*/
			break;
		case UC_USER:
			return "<span title=\"".$REL_LANG->_('User')."\">" . $username . "</span>";
			break;
		case UC_GUEST:
			return "<i>{$REL_LANG->_('Guest')}</i>";
			break;

	}
	return "$username";
}

/**
 * Returns user class name
 * @param int $class class id
 * @return string class name
 */
function get_user_class_name($class) {
	global $REL_LANG;
	switch ($class) {
		case UC_USER: return $REL_LANG->_('User');

		case UC_POWER_USER: return $REL_LANG->_('Power user');

		case UC_VIP: return $REL_LANG->_('VIP');

		case UC_UPLOADER: return $REL_LANG->_('Releaser');

		case UC_MODERATOR: return $REL_LANG->_('Moderator');

		case UC_ADMINISTRATOR: return $REL_LANG->_('Administrator');

		case UC_SYSOP: return $REL_LANG->_('Owner');

		case UC_GUEST: return $REL_LANG->_('Guest');
	}
	return "";
}

/**
 * Checks that id of user class is valid
 * @param int $class id of class
 * @return boolean
 */
function is_valid_user_class($class) {
	return (is_numeric($class) && floor($class) == $class && $class >= UC_USER && $class <= UC_SYSOP) || $class==-1;
}
?>