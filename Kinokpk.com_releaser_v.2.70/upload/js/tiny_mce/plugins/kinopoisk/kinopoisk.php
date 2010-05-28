<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<?php
$path = str_replace("js/tiny_mce/plugins/kinopoisk",'',dirname(__FILE__));
require_once ($path."include/bittorrent.php");
dbconn();
?>
<base href="<?=$CACHEARRAY['defaultbaseurl'];?>" />
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
function search($source,$text)
{

	$result = false;
	/* регулярное выражение для рус названия (Терминатор) */
	$searchfilms = "#<a class=\"all\" href=\"/level/1/film/(.*?)a>#si";

	/*регулярное выражение для англ названия (...Terminator)*/
	$searchfilms2 = "#<font color=\"\#999999\">(.*?)</font>#si";

	/*регулярное выражение для выборки года*/
	$searchyear = "#\[year\]/(\d{4})/#si";

	/*регулярное выражение для выборки айдишника фильма на кинопоиске
	 если после поиска фильма результат только один, и в параметре $source
	 передайтся страница фильма вместо страницы поиска (Антидурь, Аустерлиц)*/
	$search_one_id = "#img src=\"/images/film/([0-9]+)\.jpg#si";
	preg_match_all ($searchfilms, $source, $matches);
	preg_match_all($searchfilms2, $source, $matches2);
	preg_match_all($searchyear, $source, $matches_y);


	if (!$matches[1]){
		preg_match_all($search_one_id, $source, $matches_one);
		$parsID = $matches_one[1][0];
		//Сразу перенаправляем на страницу результат по id (Антидурь, Аустерлиц)
		//header ("Location: parser.php?id=$parsID"); - перенаправление подходит только если вызывать скрипт напрямую
		if (!is_numeric($parsID)) die('Фильм с таким именем не найден, попробуйте искать по оригинальному названию.');

		print('
    <SCRIPT type="text/javascript">
		<!--
		window.location="js/tiny_mce/plugins/kinopoisk/kinopoisk.php?id='.$parsID.'";
		//-->
	</SCRIPT>'); //перенаправление, используя javascript
	}

	else{
		//Есть несколько вариантов поиска, даём выбрать подходящий фильм
		$temparray = $matches[1];

		foreach ($temparray as $key2 => $tempresult){

			$result[$key2] = $tempresult;

			$result[$key2] = preg_replace("#(.*?)/.*?\">(.*?)</#is", "<a href=\"js/tiny_mce/plugins/kinopoisk/kinopoisk.php?id=$1\">$2</a>", $result[$key2])."   (".$matches_y[1][$key2].")   ".$matches2[1][$key2];
			$result[$key2] .= ($matches2[1][$key2]) ? "   (".$matches_y[1][$key2].")" : "" ;

		}
	}

	return $result;

}


function get_content($text, $option)

{
	global $id;
	if ($option == 'rusname') {
		$search = "#\<h1 style=\"margin: 0; padding: 0\" class=\"moviename-big\"\>(.*?)\</h1\>#si";
	}
	elseif ($option == 'origname') {

		$search = "#\<span style=\"color: \#666; font-size: 13px\"\>(.*?)\</span\>#si";
	}
	elseif ($option == 'country') {
		$search = "#страна</td>(.*?)</td>#si";
		$parse = 1;
	}
	elseif ($option == 'year') {
		$search = "#год</td>(.*?)</td>#si";
		$parse = 1;
	}
	elseif ($option == 'director') {
		$search = "#режиссер</td>(.*?)</td>#si";
		$parse = 1;
	}
	elseif ($option == 'scenario') {
		$search = "#сценарий</td>(.*?)</td>#si";
		$parse = 1;
	}
	elseif ($option == 'producer') {
		$search = "#продюсер</td>(.*?)</td>#si";
		$parse = 1;
	}
	elseif ($option == 'operator') {
		$search = "#оператор</td>(.*?)</td>#si";
		$parse = 1;
	}
	elseif ($option == 'time') {
		$search = "#время</td>(.*?)</td>#si";

	}
	elseif ($option == 'mpaa') {
		$search = "#src=\"/images/mpaa/(.*?)\.gif\"#si";
	}
	elseif ($option == 'imdb') {
		$search = "#IMDB: (.*?)</div>#si";
	}
	elseif ($option == 'descr') {
		$search = "#<tr><td colspan=3 style=\"padding:10px;padding-left:20px;\" class=\"news\">(.*?)</td></tr>#si";
	}
	elseif ($option == 'kinopoisk') {
		$search = "#<a href=\"/level/83/film/".$id."/\" class=\"continue\" style=\"background: url\(/images/dot_or.gif\) 0 93% repeat-x; font-weight: normal !important; text-decoration: none\">(.*?)<span#si";
	}
	elseif ($option == 'kinopoisktotal')
	{
		$search = "#<span style=\"font:100 14px tahoma, verdana\">&nbsp;&nbsp;(.*?)</span>#si";
	}

	elseif ($option == 'actors') {
		$search = '#В главных ролях:</span></td>(.*?)(Роли дублировали:</td>|<!-- /актеры фильма -->)#si';
		$parse = 1;
	}

	elseif ($option == 'genre') {
		$search = "#жанр</td><td>(.*?)</td>#si";
		$parse = 1;
	}

	elseif ($option == 'budget') {
		$search = "#бюджет</td>(.*?)</td>#si";
		$parse = 1;
	}

	elseif ($option == 'cashusa') {
		$search = "#сборы в США</td>(.*?)</td>#si";
		$parse = 1;
	}

	elseif ($option == 'cashworld') {
		$search = "#сборы в мире</td>(.*?)</td>#si";
		$parse = 1;
	}

	elseif ($option == 'cashrus') {
		$search = "#сборы в России</td>(.*?)</td>#si";
		$parse = 1;
	}

	elseif ($option == 'dvdusa') {
		$search = "#DVD в США</td>(.*?)</td>#si";
		$parse = 1;
	}

	elseif ($option == 'dvdru') {
		$search = "#релиз на DVD</td>(.*?)</td>#si";
		$parse = 1;
	}

	elseif ($option == 'premierworld') {
		$search = "#премьера \(мир\)</td>(.*?)</td>#si";

		$parse = 1;
	}

	elseif ($option == 'premierrus') {
		$search = "#премьера \(РФ\)</td>(.*?)</td>#si";
		$parse = 1;
	}

	elseif ($option == 'bluray') {
		$search = "#релиз на Blu-Ray</td>(.*?)</td>#si";
		$parse = 1;
	}

	$result = false;

	preg_match_all ($search, $text, $matches);
	$result = $matches[1][0];

	if ($parse) {
		$result = ParseUrls($result);
	}

	return $result;
}

if (!$CURUSER) die('Only users enabled');

if (!isset($_GET['id']) && !isset($_GET['filmname'])) print('<table><tr><td>Введите название фильма:</td><td><form method="get"><input type="text" name="filmname"><br />Год фильма (опционально):<input type="text" name="filmyear" size="5">
<input type="submit" value="Продолжить" />
</form></td></tr></table>');

require_once(ROOT_PATH."classes/parser/Snoopy.class.php");
$page = new Snoopy;

if (isset($_GET['filmname'])) {
	$film = RawUrlEncode($_GET['filmname']);
	$filmsafe = htmlspecialchars($_GET['filmname']);
	$filmyear = (int)$_GET['filmyear'];
	if (!$filmyear)
	$page->fetch("http://www.kinopoisk.ru/index.php?kp_query={$film}&x=0&y=0");
	else
	$page->fetch("http://www.kinopoisk.ru/index.php?level=7&m_act%5Bwhat%5D=content&m_act%5Bfind%5D={$film}&m_act%5Byear%5D={$filmyear}");
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
		$text = preg_replace("#\&\#133;|\&\#151;#si","",strip_tags(trim(html_entity_decode($text,ENT_QUOTES,"windows-1251"))));
		return $text;
	}
	function clearDescr($text){
		$text = preg_replace("#\t|\n|\r|\x0B#si","",$text);
		$text = strip_tags(trim(html_entity_decode($text,ENT_QUOTES,"windows-1251")), "<br></br>");

		return $text;
	}

	function ParseUrls($text){
		$text = preg_replace("#\t|\r|\x0B#si","",$text);
		$text = strip_tags(trim($text), "<a></a>");
		//$text = preg_replace("#.*?(<a.*</a>).*#is", "$1", $text);
		$text = preg_replace("#<a href=\"(.*?)>(.*?)</a>#is", "\\2", $text);
		$text = trim(str_replace('...', '', $text));

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
	$genre = clear(get_content($source, 'genre'));

	$mpaarating = clear(get_content($source, 'mpaa'));
	$mpaapic = $mpaarating;
	$imdbrating = clear(get_content($source, 'imdb'));
	$time = clear(get_content($source, 'time'));
	$descr = clearDescr(get_content($source, 'descr'));
	$actors = format_actors(get_content($source, 'actors'));

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

//$kinopoiskarray = sql_query("SELECT image FROM kinopoisk WHERE class <= ".get_user_class()." ORDER BY sort ASC");
//while ((list($img) = mysql_fetch_array($kinopoiskarray))) print('<tr><td><a href="javascript:KinopoiskDialog.insert(\''.$img.'\',\'\');"><img src="'.$CACHEARRAY['defaultbaseurl'].'/pic/kinopoisk/'.$img.'" border="0" alt="" title="" /></a></td></tr>');

?></div>
</body>
</html>
