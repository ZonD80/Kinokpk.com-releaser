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

gzip();

// 0 - No debug; 1 - Show and run SQL query; 2 - Show SQL query only
$DEBUG_MODE = 0;
/*
 function get_user_icons($arr, $big = false)
 {
 if ($big)
 {
 $donorpic = "starbig.gif";
 $warnedpic = "warnedbig.gif";
 $disabledpic = "disabledbig.gif";
 }
 else
 {
 $donorpic = "star.gif";
 $warnedpic = "warned.gif";
 $disabledpic = "disabled.gif";
 }
 $pics = $arr["donor"] == "yes" ? "<img src=pic/$donorpic alt='Donor' border=0 style=\"margin-left: 2pt\">" : "";
 if ($arr["enabled"] == "yes")
 $pics .= $arr["warned"] == "yes" ? "<img src=pic/$warnedpic alt=\"Warned\" border=0>" : "";
 else
 $pics .= "<img src=pic/$disabledpic alt=\"Disabled\" border=0 style=\"margin-left: 2pt\">\n";
 return $pics;
 }
 */

dbconn();
loggedinorreturn();

if (get_user_class() < UC_MODERATOR)
stderr($tracker_lang['error'], "Отказано в доступе.");

stdhead("Административный поиск");
echo "<h1>Административный поиск</h1>\n";

if ($_GET['h'])
{
	begin_frame("Инструкция<font color=#009900> - Читать обязательно</font>");
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
	end_frame();
}
else
{
	echo "<p align=center>(<a href='usersearch.php?h=1'>Инструкиця</a>)";
	echo "&nbsp;-&nbsp;(<a href='usersearch.php'>Сброс</a>)</p>\n";
}

$highlight = " bgcolor=#BBAF9B";

?>

	<form method="get" action="usersearch.php">
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
				echo "<option value=$i ".(((int)$_GET['rt']=="$i")?"selected":"").">".$options[$i]."</option>\n";
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
				echo "<option value=$i ".(((int)$_GET['st']=="$i")?"selected":"").">".$options[$i]."</option>\n";
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
				echo "<option value=$i ".(((int)$_GET['as']=="$i")?"selected":"").">".$options[$i]."</option>\n";
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
			<td <?=((int)$_GET['c'] && (int)$_GET['c'] != 1)?$highlight:""?>><select
				name="c">
				<option value='1'>(Любой)</option>
				<?

				if (!is_valid_id($_GET['c']))
				$class = '';
				$class = (int) $_GET['c'];
				for ($i = 2;;++$i) {
					if ($c = get_user_class_name($i-2))
					print("<option value=" . $i . ($class && $class == $i? " selected" : "") . ">$c</option>\n");
					else
	   	break;
				}
				?>
			</select></td>
		</tr>
		<tr>

			<td valign="middle" class=rowhead>Регистрация:</td>

			<td <?=$_GET['d']?$highlight:""?>><select name="dt">
			<?
			$options = array("в","раньше","после","между");
			for ($i = 0; $i < count($options); $i++){
				echo "<option value=$i ".(((int)$_GET['dt']=="$i")?"selected":"").">".$options[$i]."</option>\n";
			}
			?>
			</select> <input name="d" type="text"
				value="<?=htmlspecialchars($_GET['d'])?>" size="12" maxlength="10">

			<input name="d2" type="text"
				value="<?=htmlspecialchars($_GET['d2'])?>" size="12" maxlength="10"></td>


			<td valign="middle" class=rowhead>Раздал:</td>

			<td <?=$_GET['ul']?$highlight:""?>><select name="ult" id="ult">
			<?
			$options = array("ровно","больше","меньше","между");
			for ($i = 0; $i < count($options); $i++){
				echo "<option value=$i ".(((int)$_GET['ult']=="$i")?"selected":"").">".$options[$i]."</option>\n";
			}
			?>
			</select> <input name="ul" type="text" id="ul" size="8" maxlength="7"
				value="<?=htmlspecialchars($_GET['ul'])?>"> <input name="ul2"
				type="text" id="ul2" size="8" maxlength="7"
				value="<?=htmlspecialchars($_GET['ul2'])?>"></td>
			<td valign="middle" class="rowhead">Донор:</td>

			<td <?=$_GET['do']?$highlight:""?>><select name="do">
			<?
			$options = array("(Любой)","Да","Нет");
			for ($i = 0; $i < count($options); $i++){
				echo "<option value=$i ".(((int)$_GET['do']=="$i")?"selected":"").">".$options[$i]."</option>\n";
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
				echo "<option value=$i ".(((int)$_GET['lst']=="$i")?"selected":"").">".$options[$i]."</option>\n";
			}
			?>
			</select> <input name="ls" type="text"
				value="<?=htmlspecialchars($_GET['ls'])?>" size="12" maxlength="10">

			<input name="ls2" type="text"
				value="<?=htmlspecialchars($_GET['ls2'])?>" size="12" maxlength="10"></td>
			<td valign="middle" class=rowhead>Скачал:</td>

			<td <?=$_GET['dl']?$highlight:""?>><select name="dlt" id="dlt">
			<?
			$options = array("ровно","больше","меньше","между");
			for ($i = 0; $i < count($options); $i++){
				echo "<option value=$i ".(((int)$_GET['dlt']=="$i")?"selected":"").">".$options[$i]."</option>\n";
			}
			?>
			</select> <input name="dl" type="text" id="dl" size="8" maxlength="7"
				value="<?=htmlspecialchars($_GET['dl'])?>"> <input name="dl2"
				type="text" id="dl2" size="8" maxlength="7"
				value="<?=htmlspecialchars($_GET['dl2'])?>"></td>

			<td valign="middle" class=rowhead>Предупрежден:</td>

			<td <?=$_GET['w']?$highlight:""?>><select name="w">
			<?
			$options = array("(Любой)","Да","Нет");
			for ($i = 0; $i < count($options); $i++){
				echo "<option value=$i ".(((int)$_GET['w']=="$i")?"selected":"").">".$options[$i]."</option>\n";
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

	// ratio as a string
	function ratios($up,$down, $color = True)
	{
		if ($down > 0)
		{
			$r = number_format($up / $down, 2);
			if ($color)
			$r = "<font color=".get_ratio_color($r).">$r</font>";
		}
		else
		if ($up > 0)
		$r = "Inf.";
		else
		$r = "---";
		return $r;
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
	  $q .= ($q ? "&amp;" : "") . "n=".urlencode(trim(htmlspecialchars($_GET['n'])));
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
	  			stdmsg($tracker_lang['error'], "Неправильный E-mail.");
	  			stdfoot();
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
			$q .= ($q ? "&amp;" : "") . "em=".urlencode(trim(htmlspecialchars($_GET['em'])));
		}

		//class
		// NB: the c parameter is passed as two units above the real one
		$class = (int)$_GET['c'] - 2;
		if (is_valid_id($class + 1))
		{
			$where_is .= (isset($where_is)?" AND ":"")."u.class=$class";
			$q .= ($q ? "&amp;" : "") . "c=".($class+2);
		}

		// IP
		$ip = trim(htmlspecialchars($_GET['ip']));
		if ($ip)
		{
			$regex = "/^(((1?\d{1,2})|(2[0-4]\d)|(25[0-5]))(\.\b|$)){4}$/";
			if (!preg_match($regex, $ip))
			{
				stdmsg($tracker_lang['error'], "Неверный IP.");
				stdfoot();
				die();
			}

			$mask = trim(htmlspecialchars($_GET['ma']));
			if ($mask == "" || $mask == "255.255.255.255")
			$where_is .= (isset($where_is)?" AND ":"")."u.ip = '$ip'";
			else
			{
				if (substr($mask,0,1) == "/")
				{
					$n = substr($mask, 1, strlen($mask) - 1);
					if (!is_numeric($n) or $n < 0 or $n > 32)
					{
						stdmsg($tracker_lang['error'], "Неверная макса подсети.");
						stdfoot();
						die();
					}
					else
					$mask = long2ip(pow(2,32) - pow(2,32-$n));
				}
				elseif (!preg_match($regex, $mask))
				{
					stdmsg($tracker_lang['error'], "Неверная макса подсети.");
					stdfoot();
					die();
				}
				$where_is .= (isset($where_is)?" AND ":"")."INET_ATON(u.ip) & INET_ATON('$mask') = INET_ATON('$ip') & INET_ATON('$mask')";
				$q .= ($q ? "&amp;" : "") . "ma=$mask";
			}
			$q .= ($q ? "&amp;" : "") . "ip=$ip";
		}

		// ratio
		$ratio = trim(htmlspecialchars($_GET['r']));
		if ($ratio)
		{
			if ($ratio == '---')
			{
				$ratio2 = "";
				$where_is .= isset($where_is)?" AND ":"";
				$where_is .= " u.uploaded = 0 and u.downloaded = 0";
			}
			elseif (strtolower(substr($ratio,0,3)) == 'inf')
			{
				$ratio2 = "";
				$where_is .= isset($where_is)?" AND ":"";
				$where_is .= " u.uploaded > 0 and u.downloaded = 0";
			}
			else
			{
				if (!is_numeric($ratio) || $ratio < 0)
				{
					stdmsg($tracker_lang['error'], "Неверный рейтинг.");
					stdfoot();
					die();
				}
				$where_is .= isset($where_is)?" AND ":"";
				$where_is .= " (u.uploaded/u.downloaded)";
				$ratiotype = (int) $_GET['rt'];
				$q .= ($q ? "&amp;" : "") . "rt=$ratiotype";
				if ($ratiotype == "3")
				{
					$ratio2 = trim(htmlspecialchars($_GET['r2']));
					if(!$ratio2)
					{
						stdmsg($tracker_lang['error'], "Нужны два рейтинга для этого типа поиска.");
						stdfoot();
						die();
					}
					if (!is_numeric($ratio2) or $ratio2 < $ratio)
					{
						stdmsg($tracker_lang['error'], "Плохой второй рейтинг.");
						stdfoot();
						die();
					}
					$where_is .= " BETWEEN ".sqlesc($ratio)." and ".sqlesc($ratio2);
					$q .= ($q ? "&amp;" : "") . "r2=$ratio2";
				}
				elseif ($ratiotype == "2")
				$where_is .= " < $ratio";
				elseif ($ratiotype == "1")
				$where_is .= " > $ratio";
				else
				$where_is .= " BETWEEN (".sqlesc($ratio)." - 0.004) and (".sqlesc($ratio)." + 0.004)";
			}
			$q .= ($q ? "&amp;" : "") . "r=$ratio";
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
	  $q .= ($q ? "&amp;" : "") . "co=".urlencode(trim($_GET['co']));
		}

		$unit = 1073741824;		// 1GB

		// uploaded
		$ul = trim((int)$_GET['ul']);
		if ($ul)
		{
			if (!is_numeric($ul) || $ul < 0)
			{
				stdmsg($tracker_lang['error'], "Неправильное количество залитой информации.");
				stdfoot();
				die();
			}
			$where_is .= isset($where_is)?" AND ":"";
			$where_is .= " u.uploaded ";
			$ultype = (int)$_GET['ult'];
			$q .= ($q ? "&amp;" : "") . "ult=$ultype";
			if ($ultype == "3")
			{
				$ul2 = trim((int)$_GET['ul2']);
				if(!$ul2)
				{
					stdmsg($tracker_lang['error'], "Нужны два количества залитой информации для этого типа поиска.");
					stdfoot();
					die();
				}
				if (!is_numeric($ul2) or $ul2 < $ul)
				{
					stdmsg($tracker_lang['error'], "Неправильный второй параметр залитой информации.");
					stdfoot();
					die();
				}
				$where_is .= " BETWEEN ".$ul*$unit." and ".$ul2*$unit;
				$q .= ($q ? "&amp;" : "") . "ul2=$ul2";
			}
			elseif ($ultype == "2")
			$where_is .= " < ".$ul*$unit;
			elseif ($ultype == "1")
			$where_is .= " >". $ul*$unit;
			else
			$where_is .= " BETWEEN ".($ul - 0.004)*$unit." and ".($ul + 0.004)*$unit;
			$q .= ($q ? "&amp;" : "") . "ul=$ul";
		}

		// downloaded
		$dl = trim((int)$_GET['dl']);
		if ($dl)
		{
			if (!is_numeric($dl) || $dl < 0)
			{
				stdmsg($tracker_lang['error'], "Bad downloaded amount.");
				stdfoot();
				die();
			}
			$where_is .= isset($where_is)?" AND ":"";
			$where_is .= " u.downloaded ";
			$dltype = (int)$_GET['dlt'];
			$q .= ($q ? "&amp;" : "") . "dlt=$dltype";
			if ($dltype == "3")
			{
				$dl2 = trim((int)$_GET['dl2']);
				if(!$dl2)
				{
					stdmsg($tracker_lang['error'], "Two downloaded amounts needed for this type of search.");
					stdfoot();
					die();
				}
				if (!is_numeric($dl2) or $dl2 < $dl)
				{
					stdmsg($tracker_lang['error'], "Bad second downloaded amount.");
					stdfoot();
					die();
				}
				$where_is .= " BETWEEN ".$dl*$unit." and ".$dl2*$unit;
				$q .= ($q ? "&amp;" : "") . "dl2=$dl2";
			}
			elseif ($dltype == "2")
			$where_is .= " < ".$dl*$unit;
			elseif ($dltype == "1")
			$where_is .= " > ".$dl*$unit;
			else
			$where_is .= " BETWEEN ".($dl - 0.004)*$unit." and ".($dl + 0.004)*$unit;
			$q .= ($q ? "&amp;" : "") . "dl=$dl";
		}

		// date joined
		$date = trim($_GET['d']);
		if ($date)
		{
			if (!$date = mkdate($date))
			{
				stdmsg($tracker_lang['error'], "Неправильная дата.");
				stdfoot();
				die();
			}
			$q .= ($q ? "&amp;" : "") . "d=$date";
			$datetype = (int)$_GET['dt'];
			$q .= ($q ? "&amp;" : "") . "dt=$datetype";
			if ($datetype == "0")
			// For mySQL 4.1.1 or above use instead
			// $where_is .= (isset($where_is)?" AND ":"")."DATE(added) = DATE('$date')";
			$where_is .= (isset($where_is)?" AND ":"").
    		"(UNIX_TIMESTAMP(added) - UNIX_TIMESTAMP('$date')) BETWEEN 0 and 86400";
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
							stdmsg($tracker_lang['error'], "Неправильная дата.");
							stdfoot();
							die();
						}
						$q .= ($q ? "&amp;" : "") . "d2=$date2";
						$where_is .= " BETWEEN '$date' and '$date2'";
					}
					else
					{
						stdmsg($tracker_lang['error'], "Нужны две даты для этого типа поиска.");
						stdfoot();
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
				stdmsg($tracker_lang['error'], "Неправильная дата.");
				stdfoot();
				die();
			}
			$q .= ($q ? "&amp;" : "") . "ls=$last";
			$lasttype = (int)$_GET['lst'];
			$q .= ($q ? "&amp;" : "") . "lst=$lasttype";
			if ($lasttype == "0")
			// For mySQL 4.1.1 or above use instead
			// $where_is .= (isset($where_is)?" AND ":"")."DATE(added) = DATE('$date')";
			$where_is .= (isset($where_is)?" AND ":"").
      		"(UNIX_TIMESTAMP(last_access) - UNIX_TIMESTAMP('$last')) BETWEEN 0 and 86400";
			else
			{
				$where_is .= (isset($where_is)?" AND ":"")."u.last_access ";
				if ($lasttype == "3")
				{
					$last2 = mkdate(trim($_GET['ls2']));
					if ($last2)
					{
						$where_is .= " BETWEEN '$last' and '$last2'";
						$q .= ($q ? "&amp;" : "") . "ls2=$last2";
					}
					else
					{
						stdmsg($tracker_lang['error'], "Вторая дата неверна.");
						stdfoot();
						die();
					}
				}
				elseif ($lasttype == "1")
				$where_is .= "< '$last'";
				elseif ($lasttype == "2")
				$where_is .= "> '$last'";
			}
		}

		// status
		$status = (int)$_GET['st'];
		if ($status)
		{
			$where_is .= ((isset($where_is))?" AND ":"");
			if ($status == "1")
			$where_is .= "u.status = 'confirmed'";
			else
			$where_is .= "u.status = 'pending'";
			$q .= ($q ? "&amp;" : "") . "st=$status";
		}

		// account status
		$accountstatus = (int)$_GET['as'];
		if ($accountstatus)
		{
			$where_is .= (isset($where_is))?" AND ":"";
			if ($accountstatus == "1")
			$where_is .= " u.enabled = 'yes'";
			else
			$where_is .= " u.enabled = 'no'";
			$q .= ($q ? "&amp;" : "") . "as=$accountstatus";
		}

		//donor
		$donor = (int)$_GET['do'];
		if ($donor)
		{
			$where_is .= (isset($where_is))?" AND ":"";
			if ($donor == 1)
			$where_is .= " u.donor = 'yes'";
			else
			$where_is .= " u.donor = 'no'";
			$q .= ($q ? "&amp;" : "") . "do=$donor";
		}

		//warned
		$warned = (int)$_GET['w'];
		if ($warned)
		{
			$where_is .= (isset($where_is))?" AND ":"";
			if ($warned == 1)
			$where_is .= " u.warned = 'yes'";
			else
			$where_is .= " u.warned = 'no'";
			$q .= ($q ? "&amp;" : "") . "w=$warned";
		}

		// disabled IP
		$disabled = htmlspecialchars($_GET['dip']);
		if ($disabled)
		{
			$distinct = "DISTINCT ";
			$join_is .= " LEFT JOIN users AS u2 ON u.ip = u2.ip";
			$where_is .= ((isset($where_is))?" AND ":"")."u2.enabled = 'no'";
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

		$select_is = "u.id, u.username, u.email, u.status, u.added, u.last_access, u.ip,
  	u.class, u.uploaded, u.downloaded, u.donor, u.modcomment, u.enabled, u.warned";

		$query = "SELECT ".$distinct." ".$select_is." ".$querypm;

		//    <temporary>    /////////////////////////////////////////////////////
		if ($DEBUG_MODE > 0)
		{
			stdmsg("Запрос подсчета",$queryc);
			echo "<BR><BR>";
			stdmsg("Поисковый запрос",$query);
			echo "<BR><BR>";
			stdmsg("URL ",$q);
			if ($DEBUG_MODE == 2)
			die();
			echo "<BR><BR>";
		}
		//    </temporary>   /////////////////////////////////////////////////////

		$res = sql_query($queryc) or sqlerr(__FILE__, __LINE__);
		$arr = mysql_fetch_row($res);
		$count = $arr[0];

		$q = isset($q)?($q."&amp;"):"";

		$perpage = 30;

		list($pagertop, $pagerbottom, $limit) = pager($perpage, $count, $_SERVER["PHP_SELF"]."?".$q);

		$query .= $limit;

		$res = sql_query($query) or sqlerr(__FILE__, __LINE__);

		if (mysql_num_rows($res) == 0)
		stdmsg("Внимание","Пользователь не был найден.");
		else
		{
			if ($count > $perpage)
			echo $pagertop;
			echo "<table border=1 cellspacing=0 cellpadding=5>\n";
			echo "<tr><td class=colhead align=left>Пользователь</td>
    		<td class=colhead align=left>Рейтинг</td>
        <td class=colhead align=left>IP</td>
        <td class=colhead align=left>Email</td>".
        "<td class=colhead align=left>Регистрация:</td>".
        "<td class=colhead align=left>Последняя активность:</td>".
        "<td class=colhead align=left>Статус</td>".
        "<td class=colhead align=left>Включен</td>".
        "<td class=colhead>pR</td>".
        "<td class=colhead>pUL</td>".
        "<td class=colhead>pDL</td>".
        "<td class=colhead>История</td></tr>";
			while ($user = mysql_fetch_array($res))
			{
				if ($user['added'] == '0000-00-00 00:00:00')
				$user['added'] = '---';
				if ($user['last_access'] == '0000-00-00 00:00:00')
				$user['last_access'] = '---';


				$ipstr = $user['ip'];

				$auxres = sql_query("SELECT SUM(uploaded) AS pul, SUM(downloaded) AS pdl FROM peers WHERE userid = " . $user['id']) or sqlerr(__FILE__, __LINE__);
				$array = mysql_fetch_array($auxres);

				$pul = $array['pul'];
				$pdl = $array['pdl'];

				$n_posts = $n[0];

				$auxres = sql_query("SELECT COUNT(id) FROM comments WHERE user = ".$user['id']) or sqlerr(__FILE__, __LINE__);
				// Use LEFT JOIN to exclude orphan comments
				// $auxres = sql_query("SELECT COUNT(c.id) FROM comments AS c LEFT JOIN torrents as t ON c.torrent = t.id WHERE c.user = '".$user['id']."'") or sqlerr(__FILE__, __LINE__);
				$n = mysql_fetch_row($auxres);
				$n_comments = $n[0];

				echo "<tr><td><b><a href='userdetails.php?id=" . $user['id'] . "'>" .
				$user['username']."</a></b>" . get_user_icons($user) . "</td>" .
				//      		($user["donor"] == "yes" ? "<img src=pic/star.gif alt=\"Donor\">" : "") .
				//					($user["warned"] == "yes" ? "<img src=\"pic/warned.gif\" alt=\"Warned\">" : "") . "</td>
          "<td>" . ratios($user['uploaded'], $user['downloaded']) . "</td>
          <td>" . $ipstr . "</td><td>" . $user['email'] . "</td>
          <td><div align=center>" . $user['added'] . "</div></td>
          <td><div align=center>" . $user['last_access'] . "</div></td>
          <td><div align=center>" . $user['status'] . "</div></td>
          <td><div align=center>" . $user['enabled']."</div></td>
          <td><div align=center>" . ratios($pul,$pdl) . "</div></td>" .
          "<td><div align=right>" . mksize($pul) . "</div></td>
          <td><div align=right>" . mksize($pdl) . "</div></td>".
          "|".($n_comments?"<a href=userhistory.php?action=viewcomments&id=".$user['id'].">$n_comments</a>":$n_comments).
          "</div></td></tr>\n";
			}
			echo "</table>";
			if ($count > $perpage)
			echo "$pagerbottom";

			?> <br />
	<br />
	<form method=post action=message.php>
	<table border="1" cellpadding="5" cellspacing="0">
		<tr>
			<td>
			<div align="center">Рассылка сообщений найденным юзерам<br />
			<input name="pmees" type="hidden" value="<?echo $querypm?>" size=10>
			<input name="PM" type="submit" value="PM" class=btn> <input
				name="n_pms" type="hidden" value="<?echo $count?>" size=10> <input
				name="action" type="hidden" value="mass_pm" size=10></div>
			</td>
		</tr>
	</table>
	</form>
	<?

		}
	}

	print("<p>$pagemenu<br />$browsemenu</p>");
	stdfoot();
	die;

	?>