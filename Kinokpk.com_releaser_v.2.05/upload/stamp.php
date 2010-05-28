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
//Tarix22 and ZonD80 edition

require_once "include/bittorrent.php";
dbconn(false);
loggedinorreturn();

if ($CURUSER) {
$ss_a = @mysql_fetch_array(@sql_query("SELECT uri FROM stylesheets WHERE id=" . $CURUSER["stylesheet"]));
if ($ss_a) $ss_uri = $ss_a["uri"];
}
if (!$ss_uri) {
$ss_uri = $default_theme;
}
?>

<html>
<head>
<script language="javascript">

function SmileIT(smile,form,text){
window.opener.document.forms[form].elements[text].value = window.opener.document.forms[form].elements[text].value+" [img]<?=$DEFAULTBASEURL?>/pic/stamp/"+smile+"[/img] ";
window.opener.document.forms[form].elements[text].focus();
}
</script>
<title><?=$SITENAME?> :: Штампы</title>
<link rel="stylesheet" href="./themes/<?=$ss_uri."/".$ss_uri?>.css" type="text/css"/>
</head>

<table width="100%" border="1" cellspacing="2" cellpadding="2">
<h2>Печати и штампы <?=$DEFAULTBASEURL?></h2>
<tr align="center">
<?
$ctr=0;
$stamparray = sql_query("SELECT image FROM stamps WHERE class <= ".get_user_class()." ORDER BY sort ASC");
while ((list($img) = mysql_fetch_array($stamparray))) {
if ($count % 3==0)
print("\n<tr>");
print("<td align=\"center\"><a href=\"javascript:SmileIT('".$img."','".htmlentities($_GET["form"])."','".htmlentities($_GET["text"])."')\"><img border=\"0\" src=\"pic/stamp/".$img."\"></a></td>");
$count++;

if ($count % 3==0)
print("\n</tr>");
}
?>
</tr>
</table>
<div align="center">
[<a href="javascript:window.close()">Закрыть окно</a>]
</div>