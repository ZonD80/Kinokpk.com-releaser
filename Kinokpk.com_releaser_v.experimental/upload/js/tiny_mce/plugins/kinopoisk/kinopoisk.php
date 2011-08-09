<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
$path = str_replace("js/tiny_mce/plugins/kinopoisk",'',dirname(__FILE__));
require_once ($path."include/bittorrent.php");
INIT();
?>
<base href="<?=$REL_CONFIG['defaultbaseurl'];?>" />
<title>{#kinopoisk_dlg.title}</title>
<script type="text/javascript" src="js/tiny_mce/tiny_mce_popup.js"></script>
<script type="text/javascript"
	src="js/tiny_mce/plugins/kinopoisk/js/kinopoisk.js"></script>
</head>
<body style="display: none">
<div align="center">
<div class="title">{#kinopoisk_dlg.title}:<br />
<br />
</div>

<?php
//die('Парсер временно отключен');
function search($source,$text)
{
	$result = false;
	$source = strip_tags($source,'<a>');
	$source = str_replace("\n",'',$source);
	//die($source);
	//<a href="/level/1/film/507/sr/1/">Терминатор</a>, 1984     The Terminator,
	$searchfilms = "#<a href=\"\/level\/1\/film\/([0-9]+)\/sr\/1\/\">(.*?)<\/a>#si";

	preg_match_all ($searchfilms, $source, $matches);
	$matches = $matches[0];
	//var_dump($matches);

	if ($matches) {
		foreach ($matches as $filmlink) {
			if ($filmlink)
			$return[] = preg_replace("#\/level\/1\/film\/(.*?)\/sr\/1\/\">(.*?)<\/a>#is", "js/tiny_mce/plugins/kinopoisk/kinopoisk.php?id=$1\">$2</a>", $filmlink);
		}
		return $return;
	}
}


function get_content($text, $option)

{
	global $id;
	if ($option == 'rusname') {
		$search = "#class=\"moviename-big\"\>(.*?)\</h1\>#si";
	}
	elseif ($option == 'origname') {
		$search = "#font-size: 13px\"\>(.*?)\</span\>#si";
	}
	elseif ($option == 'country') {
		$search = "#страна</td><td class=\"\">(.*?)</td></tr>#si";
		$parse =1;
	}
	elseif ($option == 'year') {
		$search = "#год</td><td class=\"\"><a href=\"(.*?)\">(.*?)</a></td></tr>#si";
		$parse = 1;
	}
	elseif ($option == 'director') {
		$search = "#режиссер</td><td>(.*?)</td></tr>#si";
		$parse = 1;
	}
	elseif ($option == 'scenario') {
		$search = "#сценарий</td><td class=\"type\">(.*?)</td></tr>#si";
		$parse = 1;
	}
	elseif ($option == 'producer') {
		$search = "#продюсер</td><td class=\"type\">(.*?)</td></tr>#si";
		$parse = 1;
	}
	elseif ($option == 'operator') {
		$search = "#оператор</td><td class=\"type\">(.*?)</td></tr>#si";
		$parse = 1;
	}
	elseif ($option == 'time') {
		$search = "#время</td><td class=\"time\" id=\"runtime\">(.*?)</td></tr>#si";
		$parse = 1;
	}
	elseif ($option == 'mpaa') {
		$search = "#<img src='/images/mpaa/(.*?).gif' height=11 alt#si";
	}
	elseif ($option == 'imdb') {
		$search = "#IMDB: (.*?)</div#si";
	}
	elseif ($option == 'descr') {
		$search = "#<span class=\"_reachbanner_\">(.*?)</span>#si";
	}
	elseif ($option == 'kinopoisk') {
		$search = "#<a href=\"/level/83/film/$id/\" class=\"continue\">(.*?)</a>#si";
	}
	elseif ($option == 'kinopoisktotal')
	{
		$search = "#<span style=\"color:\#999;font:800 14px tahoma, verdana\"\>(.*?)</div>#si";
	}

	elseif ($option == 'actors') {
		$search = '#В главных ролях:</span>(.*?)>...</a></span>#si';
	}

	elseif ($option == 'genre') {
		$search = "#жанр</td><td>(.*?)</td></tr>#si";
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
		$result = str_replace('&nbsp;',' ', $result);
		return $result;
	}

}
if (!$CURUSER) die('Only users enabled');

if (!isset($_GET['id']) && !isset($_GET['filmname'])) print('<table><tr><td>Введите название фильма:</td><td><form method="get"><input type="text" name="filmname">
<input type="submit" value="Продолжить" />
</form></td></tr></table>');

require_once(ROOT_PATH."classes/parser/Snoopy.class.php");
$page = new Snoopy;

if (isset($_GET['filmname'])) {
	$film = RawUrlEncode($_GET['filmname']);
	$filmsafe = htmlspecialchars($_GET['filmname']);
	$page->fetch("http://www.kinopoisk.ru/index.php?kp_query={$film}");
	$source = $page->results;
	if (!$source) die('Nothing found!');

	print("<table><tr><td align=\"center\">Найденные по запросу \"$filmsafe\" фильмы</td></tr>");

	$searched = search($source,$film);
	if (!$searched) die('Nothing found!');
	foreach ($searched as $searchedrow) {
		print("<tr><td>".$searchedrow."</td></tr>");
	}
	print ('</table>');
}
elseif (isset($_GET['id']) && $_GET['id'] != '') {
	if (!is_valid_id($_GET['id'])) die('Wrong ID');
	$id = (int)$_GET['id'];

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
		$text = preg_replace("#\n#si"," ",$text);
		$text = preg_replace("/<\/a>/",", ",$text);
		$text = preg_replace("#\&\#133;|\&\#151;#si","",strip_tags(trim(html_entity_decode($text,ENT_QUOTES))));
		if (strpos($text,',')<=2) $text = substr($text,20,iconv_mb_strlen($text));

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
	$genre = clear(get_content($source,'genre'));
	$actors = substr(format_actors(get_content($source, 'actors')), 0, -2);
	$kinopoiskrating = "<a href=\"http://www.kinopoisk.ru/level/1/film/$id/\"><img style=\"border: none;\" src=\"http://www.kinopoisk.ru/rating/$id.gif\" title=\"Рейтинг кинопоиска\"/></a>";

	switch ($mpaarating){
		case "G": $mpaarating = "<img src=\"pic/mpaa/G.gif\" title=\"G - Нет возрастных ограничений\"/> G - Нет возрастных ограничений"; break;
		case "PG": $mpaarating ="<img src=\"pic/mpaa/PG.gif\" title=\"PG - Рекомендуется присутствие родителей\"/> PG - Рекомендуется присутствие родителей"; break;
		case "PG-13": $mpaarating = "<img src=\"pic/mpaa/PG-13.gif\" title=\"PG-13 - Детям до 13 лет просмотр не желателен\"/> PG-13 - Детям до 13 лет просмотр не желателен"; break;
		case "R": $mpaarating = "<img src=\"pic/mpaa/R.gif\" title=\"R - Лицам до 17 лет обязательно присутствие взрослого\"/> R - Лицам до 17 лет обязательно присутствие взрослого"; break;
		case "NC-17": $mpaarating = "<img src=\"pic/mpaa/NC-17.gif\" title=\"NC-17 - Лицам до 17 лет просмотр запрещен\"/> NC-17 - Лицам до 17 лет просмотр запрещен"; break;
	}

	print ('<script type="text/javascript" language="javascript">
function fillform(){
  //window.opener.document.forms["upload"].elements["name"].value = "'.$rusname.'/ '.$origname.'";

  var content = \'<i>Информация о фильме:</i><br/><b>Жанр:</b> '.$genre.'<br/><br/><b>Оригинальное название:</b> '.$origname.'<br/><b>Год выхода:</b> '.$year.'<br/><b>Режиссер:</b> '.$director.'<br/><b>В ролях:</b> '.$actors.'<br/><b>Выпущено:</b> '.$country.'<br/><b>Продолжительность:</b> '.$time.'<br/><b>Рейтинг IMDB:</b> '.$imdbrating.'<br/><b>Рейтинг кинопоиска:</b> '.$kinopoiskrating.'<br/><b>Рейтинг MPAA:</b> '.$mpaarating.'<br/><b>О фильме:</b><br/>'.$descr.'\';

  KinopoiskDialog.insert(content);
  }
</script>');
	print ("<table width=\"100%\" border=\"1\"><tr>
<td>Жанр:</td><td>$genre</td></tr>
<td>Русское название:</td><td>$rusname</td></tr>
<tr><td>Оригинальное название:</td><td>$origname</td></tr>
<tr><td>В ролях:</td><td>$actors</td></tr>
<tr><td>Страна производства:</td><td>$country</td></tr>
<tr><td>Год выхода:</td><td>$year</td></tr>
<tr><td>Режиссер:</td><td>$director</td></tr>
<tr><td>Рейтинг MPAA:</td><td>".(($mpaapic)?"<img src=\"pic/mpaa/$mpaapic.gif\"/>":"")."</td></tr>
<tr><td>Рейтинг IMDB:</td><td>$imdbrating</td></tr>
<tr><td>Рейтинг Кинопоиска</td><td>$kinopoiskrating</td></tr>
<tr><td>Продолжительность:</td><td>$time</td></tr>
<tr><td>Описание:</td><td>$descr</td></tr>");
	print ('<tr><td align="center">Эта информация верна?</td>
<td align="center">[<a href="javascript:fillform();">Да, заполнить форму</a>]<br/>[<a href="js/tiny_mce/plugins/kinopoisk/kinopoisk.php">Повторить поиск</a>]<br/>[<a href="javascript:window.close()">Закрыть окно</a>]</td></tr>
</table>');
}
?></div>
</body>
</html>
