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

// IPB SETCOOKIE SCRIPT (it is not part of IPB, but it is part of Kinokpk.com releaser) //

$host = ".".$_SERVER['SERVER_NAME'];

if (!isset($_GET['unset'])){
	setcookie($_GET['c']."member_id", $_GET['m'], 0x7fffffff, "/", $host);
	setcookie($_GET['c']."pass_hash", $_GET['p'], 0x7fffffff, "/", $host);
	setcookie($_GET['c']."session_id", $_GET['s'], 0x7fffffff, "/", $host);
} else
{
	setcookie($_GET['c']."member_id", 0, 0x7fffffff, "/", $host);
	setcookie($_GET['c']."pass_hash", 0, 0x7fffffff, "/", $host);
	setcookie($_GET['c']."session_id", 0, 0x7fffffff, "/", $host);
}

?>