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

require "include/bittorrent.php";
dbconn();

$passkey = $_GET["passkey"];
if ($passkey) {
$user = mysql_fetch_row(sql_query("SELECT COUNT(*) FROM users WHERE passkey = ".sqlesc($passkey)));
if ($user[0] != 1)
exit();
} else
loggedinorreturn();

$feed = $_GET["feed"];

// name a category
$res = sql_query("SELECT id, name FROM categories");
while($cat = mysql_fetch_assoc($res))
$category[$cat['id']] = $cat['name'];

// RSS Feed description
$DESCR = "RSS - лента новостей $SITENAME";

// by category ?
if ($_GET['cat'])
$cats = explode(",", $_GET["cat"]);
if ($cats)
$where = "category IN (".implode(", ", array_map("sqlesc", $cats)).") AND";

// start the RSS feed output
header("Content-Type: application/xml");
print("<?xml version=\"1.0\" encoding=\"windows-1251\" ?>\n<rss version=\"0.91\">\n<channel>\n" .
"<title>" . $SITENAME . "</title>\n<link>" . $DEFAULTBASEURL . "</link>\n<description>" . $DESCR . "</description>\n" .
"<language>ru-ru</language>\n<copyright>Copyright © " . $SITENAME . "</copyright>\n<webMaster>" . $SITEEMAIL . "</webMaster>\n" .
"<image><title><![CDATA[" . $SITENAME . "]]></title>\n<url>" . $DEFAULTBASEURL . "/favicon.gif</url>\n<link>" . $DEFAULTBASEURL . "</link>\n" .
"<width>16</width>\n<height>16</height>\n<description><![CDATA[" . $DESCR . "]]></description>\n<generator><![CDATA[$SITENAME - $DEFAULTBASEURL]]></generator>\n</image>\n");

// get all vars
$res = sql_query("SELECT image1,id,name,filename,size,category,added FROM torrents WHERE $where visible='yes' ORDER BY added DESC LIMIT 5") or sqlerr(__FILE__, __LINE__);
while ($row = mysql_fetch_row($res)){
list($img,$id,$name,$filename,$size,$cat,$added,$catname) = $row;

// ddl or detail ?
if ($feed == "dl")
$link = "$DEFAULTBASEURL/download.php/$id/". ($passkey ? "$passkey/" : "") ."$filename";
else
$link = "$DEFAULTBASEURL/details.php?id=$id&amp;hit=1";


// output of all data
echo("<item><title><![CDATA[" . $name . "]]></title>\n<link>" . $link . "</link>\n<description><![CDATA[<div align=\"center\"><img alt=\"Постер для ".$name."\" src=\"". $DEFAULTBASEURL ."/thumbnail.php?image=".$img."&for=rss\"></div><br /><br />Тип (жанр): " . $category[$cat] . "<br />Размер: " . mksize($size) . "<br />Добавлен: " . $added . "\n]]></description>\n</item>\n");
}

echo("</channel>\n</rss>\n");
?>