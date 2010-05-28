<?

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

# IMPORTANT: Do not edit below unless you know what you are doing!
if(!defined('IN_TRACKER'))
  die('Hacking attempt!');

$video_codec = array(
	1 => "H.263",
	2 => "H.264",
	3 => "VPx",
	4 => "DivX 3.x",
	5 => "DivX 4.x",
	6 => "DivX 5.x",
	7 => "DivX 6.x",
	8 => "XviD",
	9 => "MPEG 1",
	10 => "MPEG 2 SVCD",
	11 => "MPEG 2 DVD",
	12 => "ASF",
	13 => "WMV"

);

$audio_codec = array(
	1 => "MP3",
	2 => "MP3 Pro",
	3 => "AC3",
	4 => "AC3 2.0",
	5 => "AC3 5.1",
	6 => "WMA",
	7 => "AAC",
	8 => "OGG",
	9 => "MP2"
);

$audio_lang = array(
	1 => "Русский",
	2 => "Английский",
	3 => "Украинский",
	4 => "Немецкий",
	5 => "Польский",
	6 => "Китайский",
	7 => "Японский"
);

$audio_trans = array(
	1 => "Без перевода",
	2 => "Дублированный",
	3 => "Профессиональный",
	4 => "Многоголосый закадровый",
	5 => "Двухголосый закадровый",
	6 => "Одноголосый закадровый"
);

$release_quality = array(
	1 => "HD DVD",
	2 => "HDTV",
	3 => "HDTVRip",
	4 => "DVD-9",
	5 => "DVD-5",
	6 => "DVDRip",
	7 => "DVDScr",
	8 => "Scr",
	9 => "SatRip",
	10 => "TVRip",
	11 => "TC",
	12 => "Super-TS",
	13 => "TS",
	14 => "CAM"
);

?>