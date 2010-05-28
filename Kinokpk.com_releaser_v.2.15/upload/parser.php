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

//if (@strpos($_SERVER['HTTP_REFERER'],"upload.php?type=1") === false) die ("Direct access to this script not allowed.");

require_once "include/bittorrent.php";
dbconn(false);
loggedinorreturn();

if ($CURUSER) {
$ss_a = @mysql_fetch_array(@sql_query("SELECT uri FROM stylesheets WHERE id=" . $CURUSER["stylesheet"]));
if ($ss_a) $ss_uri = $ss_a["uri"];
}
if (!$ss_uri) {
$ss_uri = $CACHEARRAY['default_theme'];
}

function search($source,$text)
{

$result = false;

$searchfilms = "#<a class=\"all\" href=\"/level/1/film/(.*?)a>#si";
$searchfilms2 = "#<font color=\"\#999999\">(.*?)</font>#si";

  while (preg_match_all ($searchfilms, $source, $matches))
  {
    preg_match_all($searchfilms2, $source, $matches2);
    foreach ($matches as $key => $temparray)
    foreach ($temparray as $key2 => $tempresult){
    $result[$key2] = $tempresult;
    $result[$key2] = preg_replace("#(.*?)/sr/1/\">(.*?)</#is", "<a href=\"?id=\\1\">\\2</a>", $result[$key2])."   ".$matches2[$key][$key2];
    }
 return $result;
  }
}


function get_content($text, $option)

{
  global $id;
if ($option == 'rusname') {
  $search = "#\<H1 style=\"padding:0px;margin:0px\" class=\"moviename-big\"\>(.*?)\</H1\>#si";
}
elseif ($option == 'origname') {
  $search = "#\<span style=\"font-size:13px;color:\#666\"\>(.*?)\</span\>#si";
}
elseif ($option == 'country') {
  $search = "#style=\"border:1px solid \#fff\" alt=\"(.*?)\" width=16 height=11\>#si";
}
elseif ($option == 'year') {
  $search = "#год</td><td class=\"desc-data\"><a href=\"(.*?)\" class=\"all\">(.*?)</a></td></tr>#si";
  $parse = 1;
}
elseif ($option == 'director') {
  $search = "#режиссер</td><td class=\"desc-data\">(.*?)</td></tr>#si";
  $parse = 1;
}
elseif ($option == 'scenario') {
  $search = "#сценарий</td><td class=\"desc-data\">(.*?)</td></tr>#si";
  $parse = 1;
}
elseif ($option == 'producer') {
  $search = "#продюсер</td><td class=\"desc-data\">(.*?)</td></tr>#si";
  $parse = 1;
}
elseif ($option == 'operator') {
  $search = "#оператор</td><td class=\"desc-data\">(.*?)</td></tr>#si";
  $parse = 1;
}
elseif ($option == 'time') {
  $search = "#время</td><td class=\"desc-data\">(.*?)</td></tr>#si";
  $parse = 1;
}
elseif ($option == 'mpaa') {
  $search = "#<img src='/images/mpaa/(.*?).gif' height=11 alt#si";
}
elseif ($option == 'imdb') {
  $search = "#IMDB: (.*?)</div></td>#si";
}
elseif ($option == 'descr') {
  $search = "#<tr><td colspan=3 style=\"padding:10px;padding-left:20px;\" class=\"news\">(.*?)</td></tr>#si";
}
elseif ($option == 'kinopoisk') {
  $search = "#<a href=\"/level/83/film/$id/\" class=\"continue\">(.*?)</a>#si";
}
elseif ($option == 'kinopoisktotal')
{
  $search = "#<span style=\"color:\#999;font:800 14px tahoma, verdana\"\>(.*?)</div>#si";
}

elseif ($option == 'actors') {
  $search = '#В главных ролях:</td></tr>(.*?)class="all">...</a></td></tr>#si';
}

elseif ($option == 'genre') {
  $search = "#жанр</td><td class=\"desc-data\">(.*?)</td></tr>#si";
  $parse = 1;
}

$result = false;
$parse = false;

if (!$parse) {$parse = 0;}
  while (preg_match_all ($search, $text, $matches))
  {
    foreach ($matches as $tempresult)
      $result = $tempresult[$parse];
	  if ($parse == 1) {
      $result = preg_replace("#<a href=\"(.*?)>(.*?)</a>#is", "\\2", $result);
    $result = str_replace(', ...', '', $result);
    }

     return $result;
  }

}
?>
<html>
<head>
<title><?=$CACHEARRAY['sitename']?> :: Заполнить форму для фильма</title>
<link rel="stylesheet" href="./themes/<?=$ss_uri."/".$ss_uri?>.css" type="text/css"/>
</head>
<table width="100%" border="1" cellspacing="2" cellpadding="2">
<h2>Поиск фильма на Kinopoisk.ru</h2>
<?

if (!isset($_GET['id']) && !isset($_GET['filmname'])) print('<tr><td>Введите название фильма:</td><td><form method="get"><input type="text" name="filmname">
<input type="submit" value="Продолжить" />
</form></td></tr>');

	include "classes/parser/Snoopy.class.php";
	$page = new Snoopy;

if (isset($_GET['filmname'])) {
  $film = RawUrlEncode($_GET['filmname']);
  $filmsafe = htmlspecialchars($_GET['filmname']);
$page->fetch("http://www.kinopoisk.ru/index.php?kp_query={$film}&x=0&y=0");
$source = $page->results;
if (!$source) die('Nothing found!');

   print("<tr><td align=\"center\">Найденные по запросу \"$filmsafe\" фильмы</td></tr>");

  $searched = search($source,$film);
  if (!$searched) die('Nothing found!');
  foreach ($searched as $searchedrow) {
    print("<tr><td>".$searchedrow."</td></tr>");
  }
}
elseif (isset($_GET['id']) && $_GET['id'] != '') {
  if (!is_numeric($_GET['id'])) die('Wrong ID');
  $id = $_GET['id'];

  $page->fetch("http://www.kinopoisk.ru/level/1/film/$id/");
$source = $page->results;

if (!$source) die('Nothing found!');

function clear($text){
  $text = preg_replace("#\t|\r|\x0B|\n#si","",$text);
 // $text = preg_replace("#\n(.*?)\n#si","\n",$text);
  $text = preg_replace("#\&\#133;|\&\#151;#si","",strip_tags(trim(html_entity_decode($text,ENT_QUOTES))));
  return $text;
}

function format_actors($text){
  $text = preg_replace("#\t|\r|\x0B#si","",$text);
  $text = preg_replace("#\n#si",", ",$text);
  $text = preg_replace("#\&\#133;|\&\#151;#si","",strip_tags(trim(html_entity_decode($text,ENT_QUOTES))));
  $text = str_replace(", , , , ", "", $text);
  return $text;
  }


$rusname = clear(get_content($source, 'rusname'));
$origname = clear(get_content($source, 'origname'));
$country = clear(get_content($source, 'country'));
$year = clear(get_content($source, 'year'));
$director = clear(get_content($source, 'director'));
/*
$genre = mb_convert_case(clear(get_content($source, 'genre')), MB_CASE_TITLE, $mysql_charset);
$scenario = clear(get_content($source, 'scenario'));
$producer = clear(get_content($source, 'producer'));
$operator = clear(get_content($source, 'operator'));
*/
$mpaarating = clear(get_content($source, 'mpaa'));
$mpaapic = $mpaarating;
$imdbrating = clear(get_content($source, 'imdb'));
$time = clear(get_content($source, 'time'));
$descr = clear(get_content($source, 'descr'));
$actors = substr(format_actors(get_content($source, 'actors')), 0, -2);
$kinopoiskrating = clear(get_content($source, 'kinopoisk').get_content($source,'kinopoisktotal'))."http://www.kinopoisk.ru/level/1/film/$id/";

switch ($mpaarating){
  case "G": $mpaarating = "[img][siteurl]/pic/mpaa/G.gif[/img] G - Нет возрастных ограничений"; break;
  case "PG": $mpaarating ="[img][siteurl]/pic/mpaa/PG.gif[/img] PG - Рекомендуется присутствие родителей"; break;
  case "PG-13": $mpaarating = "[img][siteurl]/pic/mpaa/PG-13.gif[/img] PG-13 - Детям до 13 лет просмотр не желателен"; break;
  case "R": $mpaarating = "[img][siteurl]/pic/mpaa/R.gif[/img] R - Лицам до 17 лет обязательно присутствие взрослого"; break;
  case "NC-17": $mpaarating = "[img][siteurl]/pic/mpaa/NC-17.gif[/img] NC-17 - Лицам до 17 лет просмотр запрещен"; break;
}

print ('<script type="text/javascript" language="javascript">
function fillform(){
  window.opener.document.forms["upload"].elements["name"].value = "'.$rusname.'/ '.$origname.'";

  window.opener.document.forms["upload"].elements["val[1]"].value = "'.$origname.'";
  window.opener.document.forms["upload"].elements["val[2]"].value = "'.$year.'";
  window.opener.document.forms["upload"].elements["val[3]"].value = "'.$director.'";
  window.opener.document.forms["upload"].elements["val[4]"].value = "'.$actors.'";
  window.opener.document.forms["upload"].elements["val[5]"].value = "'.$country.'";
  window.opener.document.forms["upload"].elements["val[6]"].value = "'.$time.'";
  window.opener.document.forms["upload"].elements["val[9]"].value = "'.$imdbrating.'";
  window.opener.document.forms["upload"].elements["val[10]"].value = "'.$kinopoiskrating.'";
  window.opener.document.forms["upload"].elements["val[11]"].value = "'.$mpaarating.'";

  window.opener.document.forms["upload"].elements["val[18]"].value = "'.$descr.'";
  
    window.opener.document.forms["upload"].elements["val[19]"].value = "'.$origname.'";
  window.opener.document.forms["upload"].elements["val[20]"].value = "'.$year.'";
  window.opener.document.forms["upload"].elements["val[23]"].value = "'.$director.'";
  window.opener.document.forms["upload"].elements["val[24]"].value = "'.$actors.'";
  window.opener.document.forms["upload"].elements["val[25]"].value = "'.$country.'";
  window.opener.document.forms["upload"].elements["val[50]"].value = "'.$time.'";
  window.opener.document.forms["upload"].elements["val[29]"].value = "'.$imdbrating.'";
  window.opener.document.forms["upload"].elements["val[30]"].value = "'.$kinopoiskrating.'";
  window.opener.document.forms["upload"].elements["val[31]"].value = "'.$mpaarating.'";

  window.opener.document.forms["upload"].elements["val[32]"].value = "'.$descr.'";

  }
</script>');
print ("<table width=\"100%\" border=\"1\"><tr><td>Русское название:</td><td>$rusname</td></tr>
<tr><td>Оригинальное название:</td><td>$origname</td></tr>
<tr><td>В ролях:</td><td>$actors</td></tr>
<tr><td>Страна производства:</td><td>$country</td></tr>
<tr><td>Год выхода:</td><td>$year</td></tr>
<tr><td>Режиссер:</td><td>$director</td></tr>
<tr><td>Рейтинг MPAA:</td><td>".(($mpaapic)?"<img src=\"pic/mpaa/$mpaapic.gif\">":"")."</td></tr>
<tr><td>Рейтинг IMDB:</td><td>$imdbrating</td></tr>
<tr><td>Рейтинг Кинопоиска</td><td>$kinopoiskrating</td></tr>
<tr><td>Продолжительность:</td><td>$time</td></tr>
<tr><td>Описание:</td><td>$descr</td></tr>");
print ('<tr><td align="center">Эта информация верна?</td>
<td align="center">[<a href="javascript:fillform();">Да, заполнить форму</a>]<br/>[<a href="parser.php">Повторить поиск</a>]<br/>[<a href="javascript:window.close()">Закрыть окно</a>]</td></tr>
');
}

?>
</table>
</html>