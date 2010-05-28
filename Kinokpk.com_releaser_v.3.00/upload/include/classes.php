<?php
/*
 Project: Kinokpk.com releaser
 This file is part of Kinokpk.com releaser.
 Kinokpk.com releaser is based on TBDev,
 originally by RedBeard of TorrentBits, extensively modified by
 Gartenzwerg and Yuna Scatari.
 Kinokpk.com releaser is free software;
 you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.
 Kinokpk.com is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.
 You should have received a copy of the GNU General Public License
 along with Kinokpk.com releaser; if not, write to the
 Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,
 MA  02111-1307  USA
 Do not remove above lines!
 */

if (!defined("IN_TRACKER") && !defined("IN_ANNOUNCE")) die('Direct access to this file not allowed.');
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
	global $tracker_lang;
	switch ($class)
	{
		case UC_SYSOP:
			return "<span style=\"color:#0F6CEE\" title=\"".$tracker_lang['class_sysop']."\">" . $username . "</span>";
			break;
		case UC_ADMINISTRATOR:
			return "<span style=\"color:green\" title=\"".$tracker_lang['class_administrator']."\">" . $username . "</span>";
			break;
		case UC_MODERATOR:
			return "<span style=\"color:#00cccc\" title=\"".$tracker_lang['class_moderator']."\">" . $username . "</span>";
			break;
		case UC_UPLOADER:
			return "<span style=\"color:orange\" title=\"".$tracker_lang['class_uploader']."\">" . $username . "</span>";
			break;
		case UC_VIP:
			return "<span style=\"color:#9C2FE0\" title=\"".$tracker_lang['class_vip']."\">" . $username . "</span>";
			break;
		case UC_POWER_USER:
			return "<span style=\"color:#D21E36\" title=\"".$tracker_lang['class_power_user']."\">" . $username . "</span>";
			break;
		case UC_USER:
			return "<span title=\"".$tracker_lang['class_user']."\">" . $username . "</span>";
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
	global $tracker_lang;
	switch ($class) {
		case UC_USER: return $tracker_lang['class_user'];

		case UC_POWER_USER: return $tracker_lang['class_power_user'];

		case UC_VIP: return $tracker_lang['class_vip'];

		case UC_UPLOADER: return $tracker_lang['class_uploader'];

		case UC_MODERATOR: return $tracker_lang['class_moderator'];

		case UC_ADMINISTRATOR: return $tracker_lang['class_administrator'];

		case UC_SYSOP: return $tracker_lang['class_sysop'];
	}
	return "";
}

/**
 * Checks that id of user class is valid
 * @param int $class id of class
 * @return boolean
 */
function is_valid_user_class($class) {
	return is_numeric($class) && floor($class) == $class && $class >= UC_USER && $class <= UC_SYSOP;
}
?>