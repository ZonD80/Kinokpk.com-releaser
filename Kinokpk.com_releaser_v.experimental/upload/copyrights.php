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

require "include/bittorrent.php";

INIT();


//loggedinorreturn();

$REL_TPL->stdhead($REL_LANG->_('Copyrights'));

$REL_TPL->begin_main_frame();
$REL_TPL->begin_frame($REL_LANG->_('Copyrights'));
print(nl2br('Kinokpk.com releaser ' . RELVERSION . '
    Copyright (C) 2008-' . date('Y') . '  ZonD80, Germani

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see http://www.gnu.org/licenses/.

    In this software included:

    * IP Subnet Caltucaltor class by Jeff Silverman, Copyright 2005, The Johns Hopkins University.
    * Cache class by Mateusz "MatheW" Wojcik, GPL.
    * IPBWI forum integration by Matthias Reuter, GNU General Public License v3.
    * PHPMailer class by Andy Prevost, Marcus Bointon, Jim Jagielski, GNU Lesser General Public License.
    * Snoopy PHP net client by Monte Ohrt, GNU Lesser General Public License.
    * FeedGenerator by Mateusz "MatheW" Wojcik, GPL.
    * Smarty PHP compiling template engine by Monte Ohrt, Uwe Tews, GNU Lesser General Public License.
    * ZIP class by A. Grandt, GNU LGPL.
    * Parts of TBDEV tracker engine by TorrentBits, GPLv2.
    * Parts of Yuna Scatari modifications to TBDEV, GPLv2.
    * UDP scraper by Johannes Zinnau, Creative Commons Attribution-ShareAlike 3.0 Unported License.


	 '));
$REL_TPL->end_frame();
$REL_TPL->end_main_frame();
$REL_TPL->stdfoot();
?>