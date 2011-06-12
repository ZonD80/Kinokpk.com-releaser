<?php
/**
 * Moderators and administrators user search by various parametres
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require "include/bittorrent.php";

INIT();
loggedinorreturn();

get_privilege('is_moderator');

$REL_TPL->stdhead("Административный поиск");
print "<h1>Административный поиск</h1>\n";

$q[] = 'usersearch';
if ($_GET['h'])
{
	$REL_TPL->begin_frame("Инструкция<font color=#009900> - Читать обязательно</font>");
	?>
<ul>
	<li>Пустые поля будут проигнорированы</li>
	<li>Шаблоны * и ? могут быть использованы в Имени, Email и
	Комментариях, так-же и в нескольких значениях разделенными пробелами
	(т.е. 'wyz Max*' в Имени выведет обоих пользователей 'wyz' и тех у
	которых имена начинаються на 'Max'. Похожим образом может быть
	использована '~' для отрицания, т.е. '~alfiest' в комментариях
	ограничит поиск пользователей к тем у которых нету выражения 'alfiest'
	в ихних комментариях).</li>
	<li>Поле Рейтинг принимает 'Inf' и '---' наравне с числовыми
	значениями.</li>
	<li>Маска подсети может быть введена или в десятично точечной или CIDR
	записи (т.е. 255.255.255.0 то-же самое что и /24).</li>
	<li>Раздал и Скачал вводиться в GB.</li>
	<li>For search parameters with multiple text fields the second will be
	ignored unless relevant for the type of search chosen.</li>
	<li>'Только активных' ограничивает поиск к тем пользователям которые
	сейчас что-то качают или раздают, 'Отключенные IP' к тем чьи IP
	отключены.</li>
	<li>The 'p' columns in the results show partial stats, that is, those
	of the torrents in progress.</li>
	<li>Колонка история отображает количество постов в форуме и
	комментариев к торрентам, соотвественно, как и ведет на страницу
	истории. <?
	$REL_TPL->end_frame();
}
else
{
	print "<p align=center>(<a href='".$REL_SEO->make_link('usersearch','h',1)."'>Инструкиця</a>)";
	print "&nbsp;-&nbsp;(<a href='".$REL_SEO->make_link('usersearch')."'>Сброс</a>)</p>\n";
}

$highlight = " bgcolor=#BBAF9B";

?>

	<form method="get" action="<?=$REL_SEO->make_link('usersearch');?>">
	<table border="1" cellspacing="0" cellpadding="5">
		<tr>

			<td valign="middle" class=rowhead>Имя:</td>
			<td <?=$_GET['n']?$highlight:""?>><input name="n" type="text"
				value="<?=htmlspecialchars($_GET['n'])?>" size=35></td>

			<td valign="middle" class=rowhead>Рейтинг:</td>
			<td <?=$_GET['r']?$highlight:""?>><select name="rt">
			<?
			$options = array("равен","выше","ниже","между");
			for ($i = 0; $i < count($options); $i++){
				print "<option value=$i ".(((int)$_GET['rt']=="$i")?"selected":"").">".$options[$i]."</option>\n";
			}
			?>
			</select> <input name="r" type="text"
				value="<?=htmlspecialchars($_GET['r'])?>" size="5" maxlength="4"> <input
				name="r2" type="text" value="<?=htmlspecialchars($_GET['r2'])?>"
				size="5" maxlength="4"></td>

			<td valign="middle" class=rowhead>Статус:</td>
			<td <?=$_GET['st']?$highlight:""?>><select name="st">
			<?
			$options = array("(Любой)","Подтвержден","Не подтвержден");
			for ($i = 0; $i < count($options); $i++){
				print "<option value=$i ".(((int)$_GET['st']=="$i")?"selected":"").">".$options[$i]."</option>\n";
			}
			?>
			</select></td>
		</tr>
		<tr>
			<td valign="middle" class=rowhead>Email:</td>
			<td <?=$_GET['em']?$highlight:""?>><input name="em" type="text"
				value="<?=htmlspecialchars($_GET['em'])?>" size="35"></td>
			<td valign="middle" class=rowhead>IP:</td>
			<td <?=$_GET['ip']?$highlight:""?>><input name="ip" type="text"
				value="<?=htmlspecialchars($_GET['ip'])?>" maxlength="17"></td>

			<td valign="middle" class=rowhead>Отключен:</td>
			<td <?=$_GET['as']?$highlight:""?>><select name="as">
			<?
			$options = array("(Любой)","Нет","Да");
			for ($i = 0; $i < count($options); $i++){
				print "<option value=$i ".(((int)$_GET['as']=="$i")?"selected":"").">".$options[$i]."</option>\n";
			}
			?>
			</select></td>
		</tr>
		<tr>
			<td valign="middle" class=rowhead>Комментарий:</td>
			<td <?=$_GET['co']?$highlight:""?>><input name="co" type="text"
				value="<?=htmlspecialchars($_GET['co'])?>" size="35"></td>
			<td valign="middle" class=rowhead>Маска:</td>
			<td <?=$_GET['ma']?$highlight:""?>><input name="ma" type="text"
				value="<?=htmlspecialchars($_GET['ma'])?>" maxlength="17"></td>
			<td valign="middle" class=rowhead>Класс:</td>
			<td <?=((int)$_GET['c'] && (int)$_GET['c'] != 1)?$highlight:""?>><?php print make_classes_select('c',(int)$_GET['c']);?></td>
		</tr>
		<tr>

			<td valign="middle" class=rowhead>Регистрация:</td>

			<td <?=$_GET['d']?$highlight:""?>><select name="dt">
			<?
			$options = array("в","раньше","после","между");
			for ($i = 0; $i < count($options); $i++){
				print "<option value=$i ".(((int)$_GET['dt']=="$i")?"selected":"").">".$options[$i]."</option>\n";
			}
			?>
			</select> <input name="d" type="text"
				value="<?=htmlspecialchars($_GET['d'])?>" size="12" maxlength="10">

			<input name="d2" type="text"
				value="<?=htmlspecialchars($_GET['d2'])?>" size="12" maxlength="10"></td>

			<td valign="middle" class="rowhead" colspan="3">Донор:</td>

			<td <?=$_GET['do']?$highlight:""?>><select name="do">
			<?
			$options = array("(Любой)","Да","Нет");
			for ($i = 0; $i < count($options); $i++){
				print "<option value=$i ".(((int)$_GET['do']=="$i")?"selected":"").">".$options[$i]."</option>\n";
			}
			?>
			</select></td>
		</tr>
		<tr>

			<td valign="middle" class=rowhead>Последняя активность:</td>

			<td <?=$_GET['ls']?$highlight:""?>><select name="lst">
			<?
			$options = array("в","раньше","после","между");
			for ($i = 0; $i < count($options); $i++){
				print "<option value=$i ".(((int)$_GET['lst']=="$i")?"selected":"").">".$options[$i]."</option>\n";
			}
			?>
			</select> <input name="ls" type="text"
				value="<?=htmlspecialchars($_GET['ls'])?>" size="12" maxlength="10">

			<input name="ls2" type="text"
				value="<?=htmlspecialchars($_GET['ls2'])?>" size="12" maxlength="10"></td>

			<td valign="middle" class=rowhead colspan="3">Предупрежден:</td>

			<td <?=$_GET['w']?$highlight:""?>><select name="w">
			<?
			$options = array("(Любой)","Да","Нет");
			for ($i = 0; $i < count($options); $i++){
				print "<option value=$i ".(((int)$_GET['w']=="$i")?"selected":"").">".$options[$i]."</option>\n";
			}
			?>
			</select></td>
		</tr>

		<tr>
			<td class="rowhead"></td>
			<td></td>
			<td valign="middle" class=rowhead>Только&nbsp;активные:</td>
			<td <?=$_GET['ac']?$highlight:""?>><input name="ac" type="checkbox"
				value="1" <?=($_GET['ac'])?"checked":"" ?>></td>
			<td valign="middle" class=rowhead>Забаненые&nbsp;IP:</td>
			<td <?=$_GET['dip']?$highlight:""?>><input name="dip" type="checkbox"
				value="1" <?=($_GET['dip'])?"checked":"" ?>></td>
		</tr>
		<tr>
			<td colspan="6" align=center><input name="submit" type=submit
				class=btn value=Искать></td>
		</tr>
	</table>
	<br />
	<br />
	</form>

	<?

	// Validates date in the form [yy]yy-mm-dd;
	// Returns date if valid, 0 otherwise.
	function mkdate($date){
		if (strpos($date,'-'))
		$a = explode('-', $date);
		elseif (strpos($date,'/'))
		$a = explode('/', $date);
		else
		return 0;
		for ($i=0;$i<3;$i++)
		if (!is_numeric($a[$i]))
		return 0;
		if (checkdate($a[1], $a[2], $a[0]))
		return  date ("Y-m-d", mktime (0,0,0,$a[1],$a[2],$a[0]));
		else
		return 0;
	}

	// checks for the usual wildcards *, ? plus mySQL ones
	function haswildcard($text){
		if (strpos($text,'*') === False && strpos($text,'?') === False
		&& strpos($text,'%') === False && strpos($text,'_') === False)
		return False;
		else
		return True;
	}

	///////////////////////////////////////////////////////////////////////////////

	if (count($_GET) > 0 && !$_GET['h'])
	{
		// name
		$names = explode(' ',trim(htmlspecialchars($_GET['n'])));
		if ($names[0] !== "")
		{
			foreach($names as $name)
			{
	  	if (substr($name,0,1) == '~')
	  	{
	  		if ($name == '~') continue;
	  		$names_exc[] = substr($name,1);
	  	}
	  	else
	  	$names_inc[] = $name;
	  }

	  if (is_array($names_inc))
	  {
	  	$where_is .= isset($where_is)?" AND (":"(";
	  	foreach($names_inc as $name)
	  	{
	  		if (!haswildcard($name))
	  		$name_is .= (isset($name_is)?" OR ":"")."u.username = ".sqlesc($name);
	  		else
	  		{
	  			$name = str_replace(array('?','*'), array('_','%'), $name);
	  			$name_is .= (isset($name_is)?" OR ":"")."u.username LIKE ".sqlesc($name);
	  		}
	  	}
	  	$where_is .= $name_is.")";
	  	unset($name_is);
	  }

	  if (is_array($names_exc))
	  {
	  	$where_is .= isset($where_is)?" AND NOT (":" NOT (";
	  	foreach($names_exc as $name)
	  	{
	  		if (!haswildcard($name))
	  		$name_is .= (isset($name_is)?" OR ":"")."u.username = ".sqlesc($name);
	  		else
	  		{
	  			$name = str_replace(array('?','*'), array('_','%'), $name);
	  			$name_is .= (isset($name_is)?" OR ":"")."u.username LIKE ".sqlesc($name);
	  		}
	  	}
	  	$where_is .= $name_is.")";
	  }
	  $q[] ="n";
	  $q[] = urlencode(trim(htmlspecialchars((string)$_GET['n'])));
		}

		// ratio

		$ratio = (int)$_GET['r'];

		if ($ratio)

		{

			if ($ratio == 0)

			{

				$ratio2 = "";

				$where_is .= isset($where_is)?" AND ":"";

				$where_is .= " u.ratingsum = 0";

			}

			else

			{

				$where_is .= isset($where_is)?" AND ":"";

				$where_is .= " u.ratingsum";

				$ratiotype = (int) $_GET['rt'];

				$q[] ="rt";
				$q[] = $ratiotype;

				if ($ratiotype == "3")

				{

					$ratio2 = (int)$_GET['r2'];

					if($ratio2<=$ratio)

					{

						stdmsg($REL_LANG->say_by_key('error'), "Второй рейтинг должен быть больше первого.");

						$REL_TPL->stdfoot();

						die();

					}

					$where_is .= " BETWEEN $ratio AND $ratio2";

					$q[] ="r2";
					$q[] = $ratio2;

				}

				elseif ($ratiotype == "2")

				$where_is .= " < $ratio";

				elseif ($ratiotype == "1")

				$where_is .= " > $ratio";

				else

				$where_is .= " = $ratio";

			}

			$q[] ="r";
			$q[] = $ratio;

		}

		// email
		$emaila = explode(' ', trim(htmlspecialchars($_GET['em'])));
		if ($emaila[0] !== "")
		{
			$where_is .= isset($where_is)?" AND (":"(";
			foreach($emaila as $email)
			{
	  	if (strpos($email,'*') === False && strpos($email,'?') === False
	  	&& strpos($email,'%') === False)
	  	{
	  		if (!validemail($email))
	  		{
	  			stdmsg($REL_LANG->say_by_key('error'), "Неправильный E-mail.");
	  			$REL_TPL->stdfoot();
	  			die();
	  		}
	  		$email_is .= (isset($email_is)?" OR ":"")."u.email =".sqlesc($email);
	  	}
	  	else
	  	{
	  		$sql_email = str_replace(array('?','*'), array('_','%'), $email);
	  		$email_is .= (isset($email_is)?" OR ":"")."u.email LIKE ".sqlesc($sql_email);
	  	}
			}
			$where_is .= $email_is.")";
			$q[] ="em";
			$q[] =urlencode(trim(htmlspecialchars((string)$_GET['em'])));
		}

		//class
		// NB: the c parameter is passed as two units above the real one
		$class = (int)$_GET['c'] - 2;
		if (is_valid_id($class + 1))
		{
			$where_is .= (isset($where_is)?" AND ":"")."u.class=$class";
			$q[] ="c";
			$q[] = ($class+2);
		}

		// IP
		$ip = trim(htmlspecialchars($_GET['ip']));
		if ($ip)
		{
			$regex = "/^(((1?\d{1,2})|(2[0-4]\d)|(25[0-5]))(\.\b|$)){4}$/";
			if (!preg_match($regex, $ip))
			{
				stdmsg($REL_LANG->say_by_key('error'), "Неверный IP.");
				$REL_TPL->stdfoot();
				die();
			}

			$mask = trim(htmlspecialchars($_GET['ma']));
			if ($mask == "" || $mask == "255.255.255.255")
			$where_is .= (isset($where_is)?" AND ":"")."u.ip = '$ip'";
			else
			{
				if (substr($mask,0,1) == "/")
				{
					$n = substr($mask, 1, mb_strlen($mask) - 1);
					if (!is_numeric($n) or $n < 0 or $n > 32)
					{
						stdmsg($REL_LANG->say_by_key('error'), "Неверная макса подсети.");
						$REL_TPL->stdfoot();
						die();
					}
					else
					$mask = long2ip(pow(2,32) - pow(2,32-$n));
				}
				elseif (!preg_match($regex, $mask))
				{
					stdmsg($REL_LANG->say_by_key('error'), "Неверная макса подсети.");
					$REL_TPL->stdfoot();
					die();
				}
				$where_is .= (isset($where_is)?" AND ":"")."INET_ATON(u.ip) & INET_ATON('$mask') = INET_ATON('$ip') & INET_ATON('$mask')";
				$q[] ="ma";
				$q[] =$mask;
			}
			$q[] ="ip";
			$q[] = $ip;
		}


		// comment
		$comments = explode(' ',trim(htmlspecialchars($_GET['co'])));
		if ($comments[0] !== "")
		{
			foreach($comments as $comment)
			{
				if (substr($comment,0,1) == '~')
				{
					if ($comment == '~') continue;
					$comments_exc[] = substr($comment,1);
				}
				else
				$comments_inc[] = $comment;
	  }

	  if (is_array($comments_inc))
	  {
	  	$where_is .= isset($where_is)?" AND (":"(";
	  	foreach($comments_inc as $comment)
	  	{
	  		if (!haswildcard($comment))
	  		$comment_is .= (isset($comment_is)?" OR ":"")."u.modcomment LIKE ".sqlesc("%".$comment."%");
	  		else
	  		{
	  			$comment = str_replace(array('?','*'), array('_','%'), $comment);
	  			$comment_is .= (isset($comment_is)?" OR ":"")."u.modcomment LIKE ".sqlesc($comment);
	  		}
	  	}
	  	$where_is .= $comment_is.")";
	  	unset($comment_is);
	  }

	  if (is_array($comments_exc))
	  {
	  	$where_is .= isset($where_is)?" AND NOT (":" NOT (";
	  	foreach($comments_exc as $comment)
	  	{
	  		if (!haswildcard($comment))
	  		$comment_is .= (isset($comment_is)?" OR ":"")."u.modcomment LIKE ".sqlesc("%".$comment."%");
	  		else
	  		{
	  			$comment = str_replace(array('?','*'), array('_','%'), $comment);
	  			$comment_is .= (isset($comment_is)?" OR ":"")."u.modcomment LIKE ".sqlesc($comment);
	  		}
	  	}
	  	$where_is .= $comment_is.")";
	  }
	  $q[] ="co";
	  $q[] =urlencode(trim((string)$_GET['co']));
		}

		$unit = 1073741824;		// 1GB

		// date joined
		$date = trim($_GET['d']);
		if ($date)
		{
			if (!$date = mkdate($date))
			{
				stdmsg($REL_LANG->say_by_key('error'), "Неправильная дата.");
				$REL_TPL->stdfoot();
				die();
			}
			$q[] ="d";
			$q[] = $date;
			$datetype = (int)$_GET['dt'];
			$q[] ="dt";
			$q[] =$datetype;
			if ($datetype == "0")
			// For mySQL 4.1.1 or above use instead
			// $where_is .= (isset($where_is)?" AND ":"")."DATE(added) = DATE('$date')";
			$where_is .= (isset($where_is)?" AND ":"").
    		"(added - UNIX_TIMESTAMP('$date')) BETWEEN 0 and 86400";
			else
			{
				$where_is .= (isset($where_is)?" AND ":"")."u.added ";
				if ($datetype == "3")
				{
					$date2 = mkdate(trim($_GET['d2']));
					if ($date2)
					{
						if (!$date = mkdate($date))
						{
							stdmsg($REL_LANG->say_by_key('error'), "Неправильная дата.");
							$REL_TPL->stdfoot();
							die();
						}
						$q[] ="d2";
						$q[] = $date2;
						$where_is .= " BETWEEN '$date' and '$date2'";
					}
					else
					{
						stdmsg($REL_LANG->say_by_key('error'), "Нужны две даты для этого типа поиска.");
						$REL_TPL->stdfoot();
						die();
					}
				}
				elseif ($datetype == "1")
				$where_is .= "< '$date'";
				elseif ($datetype == "2")
				$where_is .= "> '$date'";
			}
		}

		// date last seen
		$last = trim($_GET['ls']);
		if ($last)
		{
			if (!$last = mkdate($last))
			{
				stdmsg($REL_LANG->say_by_key('error'), "Неправильная дата.");
				$REL_TPL->stdfoot();
				die();
			}
			$q .= ($q ? "&amp;" : "") . "ls=$last";
			$lasttype = (int)$_GET['lst'];
			$q .= ($q ? "&amp;" : "") . "lst=$lasttype";
			if ($lasttype == "0")
			// For mySQL 4.1.1 or above use instead
			// $where_is .= (isset($where_is)?" AND ":"")."DATE(added) = DATE('$date')";
			$where_is .= (isset($where_is)?" AND ":"").
      		"(last_access - $last) BETWEEN 0 and 86400";
			else
			{
				$where_is .= (isset($where_is)?" AND ":"")."last_access ";
				if ($lasttype == "3")
				{
					$last2 = mkdate(trim($_GET['ls2']));
					if ($last2)
					{
						$where_is .= " BETWEEN $last and $last2";
						$q .= ($q ? "&amp;" : "") . "ls2=$last2";
					}
					else
					{
						stdmsg($REL_LANG->say_by_key('error'), "Вторая дата неверна.");
						$REL_TPL->stdfoot();
						die();
					}
				}
				elseif ($lasttype == "1")
				$where_is .= "< $last";
				elseif ($lasttype == "2")
				$where_is .= "> $last";
			}
		}

		// status
		$status = (int)$_GET['st'];
		if ($status)
		{
			$where_is .= ((isset($where_is))?" AND ":"");
			if ($status == "1")
			$where_is .= "u.confirmed=1";
			else
			$where_is .= "u.confirmed=0";
			$q .= ($q ? "&amp;" : "") . "st=$status";
		}

		// account status
		$accountstatus = (int)$_GET['as'];
		if ($accountstatus)
		{
			$where_is .= (isset($where_is))?" AND ":"";
			if ($accountstatus == "1")
			$where_is .= " u.enabled = 1";
			else
			$where_is .= " u.enabled = 0";
			$q .= ($q ? "&amp;" : "") . "as=$accountstatus";
		}

		//donor
		$donor = (int)$_GET['do'];
		if ($donor)
		{
			$where_is .= (isset($where_is))?" AND ":"";
			if ($donor == 1)
			$where_is .= " u.donor = 1";
			else
			$where_is .= " u.donor = 0";
			$q .= ($q ? "&amp;" : "") . "do=$donor";
		}

		//warned
		$warned = (int)$_GET['w'];
		if ($warned)
		{
			$where_is .= (isset($where_is))?" AND ":"";
			if ($warned == 1)
			$where_is .= " u.warned = 1";
			else
			$where_is .= " u.warned = 0";
			$q .= ($q ? "&amp;" : "") . "w=$warned";
		}

		// disabled IP
		$disabled = htmlspecialchars($_GET['dip']);
		if ($disabled)
		{
			$distinct = "DISTINCT ";
			$join_is .= " LEFT JOIN users AS u2 ON u.ip = u2.ip";
			$where_is .= ((isset($where_is))?" AND ":"")."u2.enabled = 0";
			$q .= ($q ? "&amp;" : "") . "dip=$disabled";
		}

		// active
		$active = (int)$_GET['ac'];
		if ($active == "1")
		{
			$distinct = "DISTINCT ";
			$join_is .= " LEFT JOIN peers AS p ON u.id = p.userid";
			$q .= ($q ? "&amp;" : "") . "ac=$active";
		}


		$from_is = "users AS u".$join_is;
		$distinct = isset($distinct)?$distinct:"";

		$queryc = "SELECT COUNT(".$distinct."u.id) FROM ".$from_is.
		(($where_is == "")?"":" WHERE $where_is ");

		$querypm = "FROM ".$from_is.(($where_is == "")?" ":" WHERE $where_is ");

		$select_is = "u.id, u.username, u.email, u.ratingsum, u.confirmed, u.added, last_access, u.ip,
  	u.class, u.donor, u.modcomment, u.enabled, u.warned";

		$query = "SELECT ".$distinct." ".$select_is." ".$querypm.' GROUP BY u.id ';

		//    <temporary>    /////////////////////////////////////////////////////
		if ($DEBUG_MODE > 0)
		{
			stdmsg("Запрос подсчета",$queryc);
			print "<br /><br />";
			stdmsg("Поисковый запрос",$query);
			print "<br /><br />";
			stdmsg("URL ",$q);
			if ($DEBUG_MODE == 2)
			die();
			print "<br /><br />";
		}
		//    </temporary>   /////////////////////////////////////////////////////

		$res = sql_query($queryc) or sqlerr(__FILE__, __LINE__);
		$arr = mysql_fetch_row($res);
		$count = $arr[0];


		$perpage = 30;

		$limit = "LIMIT 50";

		$query .= $limit;

		$res = sql_query($query) or sqlerr(__FILE__, __LINE__);

		if (mysql_num_rows($res) == 0)
		stdmsg("Внимание","Пользователь не был найден.");
		else
		{
			print "<table border=1 cellspacing=0 cellpadding=5>\n";
			print "<tr><td class=colhead align=left>Пользователь</td>
    		<td class=colhead align=left>Рейтинг</td>
        <td class=colhead align=left>IP</td>
        <td class=colhead align=left>Email</td>".
        "<td class=colhead align=left>Регистрация:</td>".
        "<td class=colhead align=left>Последняя активность:</td>".
        "<td class=colhead align=left>Подтвержден</td>".
        "<td class=colhead align=left>Включен</td>";
			while ($user = mysql_fetch_array($res))
			{

				print "<tr><td>".make_user_link($user)."</td>" .
          "<td nowrap>" . ratearea($user['ratingsum'],$user['id'],'users',$CURUSER['id']) . "</td>
          <td>" . $user['ip'] . "</td><td>" . $user['email'] . "</td>
          <td><div align=center>" . mkprettytime($user['added']) . "</div></td>
          <td><div align=center>" . mkprettytime($user['last_access']) . " (".get_elapsed_time($user['last_access'])." {$REL_LANG->say_by_key('ago')})</div></td>
          <td><div align=center>" . ($user['confirmed']?$REL_LANG->say_by_key('yes'):$REL_LANG->say_by_key('no')) . "</div></td>
          <td><div align=center>" . ($user['enabled']?$REL_LANG->say_by_key('yes'):$REL_LANG->say_by_key('no'))."</div></td>
			</tr>\n";
			}
			print "</table>";

			?> <br />
	<br />
	<form method=post action=message.php>
	<table border="1" cellpadding="5" cellspacing="0">
		<tr>
			<td>
			<div align="center">Рассылка сообщений найденным юзерам<br />
			<input name="pmees" type="hidden" value="<?print $querypm?>" size=10>
			<input name="PM" type="submit" value="PM" class=btn> <input
				name="n_pms" type="hidden" value="<?print $count?>" size=10> <input
				name="action" type="hidden" value="mass_pm" size=10></div>
			</td>
		</tr>
	</table>
	</form>
	<?

		}
	}

	print("<p>$pagemenu<br />$browsemenu</p>");
	$REL_TPL->stdfoot();
	die;

	?>