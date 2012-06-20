<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<?php
define('DS',DIRECTORY_SEPARATOR);
$path = str_replace("js".DS."tiny_mce".DS."plugins".DS."kinopoisk",'',dirname(__FILE__));
require_once ($path."include/bittorrent.php");
dbconn();
?>
<base href="<?php print $REL_CONFIG['defaultbaseurl']; ?>" />
<title>{#kinopoisk_dlg.title}</title>
<script type="text/javascript" src="js/tiny_mce/tiny_mce_popup.js"></script>
<script type="text/javascript"
	src="js/tiny_mce/plugins/kinopoisk/js/kinopoisk.js"></script>
</head>
<body style="display: none">
	<div align="center">
		<div class="title">
			{#kinopoisk_dlg.title}:<br /> <br />
		</div>

		<?php
		//die('������ �������� ��������');
		function search($source,$text)
		{
			$result = false;
			$source = strip_tags($source,'<a>');
			$source = str_replace("\n",'',$source);
			//die($source);
			//<a href="/level/1/film/507/sr/1/">����������</a>, 1984     The Terminator,
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


		function get_content($html, $option)

		{
			$html = iconv('windows-1251','utf-8',$html);
			//var_dump(htmlspecialchars($html));
			//����� ������ �� ���.
			if ($option=='rusname') {
				preg_match('#<h1 style="margin: 0; padding: 0" class="moviename-big" itemprop="name">(.*)</h1>#',$html, $name);
				//var_dump($name);
				return $name[1];

			}

			elseif ($option=='origname') {
				//����� ������ �� ����.
				preg_match('#<span style="color: \#666; font-size: 13px" itemprop="alternativeHeadline">(.*)</span>#',$html, $name_orig);
				return $name_orig[1];
			}

			elseif ($option=='year') {
				//���
				preg_match('#<a href="/level/10/m_act%5Byear%5D/([\d]+)/".*?>([\d]+)</a>#si',$html, $year);
				return $year[1];
			}

			elseif ($option=='genre') {
				//����
				preg_match('#����</td><td itemprop="genre">(.*)</td>#iU',$html, $genre);
				$genre = preg_replace('/<a.*>/iU','', $genre[1]);
				$genre = preg_replace('/<\/a>/iU','', $genre);
				$genre = str_replace(", ...", "", $genre);
				return $genre;
			}

			elseif ($option=='director') {
				//��������
				preg_match('#��������</td><td itemprop="director">(.*)</td></tr>#iU',$html, $director);
				$director = preg_replace('/<a.*>/iU','', $director[1]);
				$director = preg_replace('/<\/a>/iU','', $director);
				return $director;
			}

			elseif ($option=='actors') {
				//� �����
				preg_match('#class="actor_list" style="background: none">(.*?)</td>#s',$html,$actors);
				$actors = trim($actors[1]);
				$actors = str_replace("\n",', ',$actors);
				//$actors = trim($actors);
				$actors = strip_tags($actors);
				$actors = str_replace('� ������� �����:','',$actors);
				$actors = preg_replace('#,    \.\.\.(.*)#s','',$actors);
				//$actors = str_replace('  ','',$actors);
				//$actors = substr($actors, 0, );
				return $actors;
			}

			elseif ($option=='descr') {
				//� ������
				preg_match('#<div class="brand_words" itemprop="description">(.*)</div>#',$html, $description);
				$description = str_replace("&nbsp;"," ",$description[1]);
				$description = str_replace("&#133;","",$description);
				$description = str_replace("&#151;","",$description);
				return $description;
			}

			elseif ($option=='imdb') {
				//������� IMDb
				preg_match('#IMDb: (.*)</div>#',$html, $imdb);
				return $imdb[1];
			}

			elseif ($option=='country') {
				//������
				preg_match_all('/<a href="\/level\/10\/m_act%5Bcountry%5D\/[\d]+\/" >(.*?)<\/a>/',$html, $country);
				$country = implode(', ',$country[1]);
				return $country;
			}

			elseif ($option=='time') {
				//�����������������
				preg_match('#<td class="time" id="runtime">(.*)</td>#',$html, $time);
				return $time[1];
			}
			elseif ($option == 'mpaa') {
				preg_match("#<img src=\"http://st\.kinopoisk\.ru/images/mpaa/(.*?)\.gif\" height=#si",$html,$mpaa);
				//var_dump($mpaa);
				return $mpaa[1];
			}

		}
		if (!$CURUSER) die('Only users enabled');

		if (!isset($_GET['id']) && !isset($_GET['filmname'])) print('<table><tr><td>������� �������� ������:</td><td><form method="get"><input type="text" name="filmname">
				<input type="submit" value="����������" />
				</form></td></tr></table>');

		require_once(ROOT_PATH."classes/parser/Snoopy.class.php");
		$page = new Snoopy;

		if (isset($_GET['filmname'])) {
			$film = RawUrlEncode((string)$_GET['filmname']);
			$filmsafe = htmlspecialchars((string)$_GET['filmname']);
			$page->fetch("http://www.kinopoisk.ru/index.php?kp_query={$film}");
			$source = $page->results;
			if (!$source) die('Nothing found!');

			print("<table><tr><td align=\"center\">��������� �� ������� \"$filmsafe\" ������</td></tr>");

			$searched = search($source,$film);
			if (!$searched) die('Nothing found!');
			foreach ($searched as $searchedrow) {
				print("<tr><td>".iconv('windows-1251','utf-8',$searchedrow)."</td></tr>");
			}
			print ('</table>');
		}
		elseif (isset($_GET['id']) && $_GET['id'] != '') {
			if (!is_valid_id($_GET['id'])) die('Wrong ID');
			$id = (int)$_GET['id'];

			$page->fetch("http://www.kinopoisk.ru/level/1/film/$id/");
			$source = $page->results;

			if (!$source) die('Nothing found!');


			$rusname = (get_content($source, 'rusname'));
			$origname = (get_content($source, 'origname'));
			$country = (get_content($source, 'country'));
			$year = (get_content($source, 'year'));
			$director = (get_content($source, 'director'));

			$mpaarating = (get_content($source, 'mpaa'));
			$mpaapic = $mpaarating;
			$imdbrating = (get_content($source, 'imdb'));
			$time = (get_content($source, 'time'));
			$descr = (get_content($source, 'descr'));
			$genre = (get_content($source,'genre'));
			$actors = get_content($source, 'actors');
			$kinopoiskrating = "<a href=\"http://www.kinopoisk.ru/level/1/film/$id/\"><img style=\"border: none;\" src=\"http://www.kinopoisk.ru/rating/$id.gif\" title=\"������� ����������\"/></a>";

			switch ($mpaarating){
				case "G": $mpaarating = "<img src=\"pic/mpaa/G.gif\" title=\"G - ��� ���������� �����������\"/> G - ��� ���������� �����������"; break;
				case "PG": $mpaarating ="<img src=\"pic/mpaa/PG.gif\" title=\"PG - ������������� ����������� ���������\"/> PG - ������������� ����������� ���������"; break;
				case "PG-13": $mpaarating = "<img src=\"pic/mpaa/PG-13.gif\" title=\"PG-13 - ����� �� 13 ��� �������� �� ���������\"/> PG-13 - ����� �� 13 ��� �������� �� ���������"; break;
				case "R": $mpaarating = "<img src=\"pic/mpaa/R.gif\" title=\"R - ����� �� 17 ��� ����������� ����������� ���������\"/> R - ����� �� 17 ��� ����������� ����������� ���������"; break;
				case "NC-17": $mpaarating = "<img src=\"pic/mpaa/NC-17.gif\" title=\"NC-17 - ����� �� 17 ��� �������� ��������\"/> NC-17 - ����� �� 17 ��� �������� ��������"; break;
			}

			print ('<script type="text/javascript" language="javascript">
					function fillform(){
					//window.opener.document.forms["upload"].elements["name"].value = "'.$rusname.'/ '.$origname.'";

					var content = \'<i>���������� � ������:</i><br/><b>����:</b> '.$genre.'<br/><br/><b>������������ ��������:</b> '.$origname.'<br/><b>��� ������:</b> '.$year.'<br/><b>��������:</b> '.$director.'<br/><b>� �����:</b> '.$actors.'<br/><b>��������:</b> '.$country.'<br/><b>�����������������:</b> '.$time.'<br/><b>������� IMDB:</b> '.$imdbrating.'<br/><b>������� ����������:</b> '.$kinopoiskrating.'<br/><b>������� MPAA:</b> '.$mpaarating.'<br/><b>� ������:</b><br/>'.$descr.'\';

					KinopoiskDialog.insert(content);
		}
					</script>');
			print ("<table width=\"100%\" border=\"1\"><tr>
					<td>����:</td><td>$genre</td></tr>
					<td>������� ��������:</td><td>$rusname</td></tr>
					<tr><td>������������ ��������:</td><td>$origname</td></tr>
					<tr><td>� �����:</td><td>$actors</td></tr>
					<tr><td>������ ������������:</td><td>$country</td></tr>
					<tr><td>��� ������:</td><td>$year</td></tr>
					<tr><td>��������:</td><td>$director</td></tr>
					<tr><td>������� MPAA:</td><td>".(($mpaapic)?"<img src=\"pic/mpaa/$mpaapic.gif\"/>":"")."</td></tr>
					<tr><td>������� IMDB:</td><td>$imdbrating</td></tr>
					<tr><td>������� ����������</td><td>$kinopoiskrating</td></tr>
					<tr><td>�����������������:</td><td>$time</td></tr>
					<tr><td>��������:</td><td>$descr</td></tr>");
			print ('<tr><td align="center">��� ���������� �����?</td>
					<td align="center">[<a href="javascript:fillform();">��, ��������� �����</a>]<br/>[<a href="js/tiny_mce/plugins/kinopoisk/kinopoisk.php">��������� �����</a>]<br/>[<a href="javascript:window.close()">������� ����</a>]</td></tr>
					</table>');
		}
		?>
	</div>
</body>
</html>