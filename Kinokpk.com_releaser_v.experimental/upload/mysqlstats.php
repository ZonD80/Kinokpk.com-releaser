<?php

/**
 * SQL database statistics viewer
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

// TRANSLATON BY 7Max7

require "include/bittorrent.php";
INIT();
loggedinorreturn();

get_privilege('view_sql_stats');

httpauth();


$GLOBALS["byteUnits"] = array('байт', 'КБ', 'МБ', 'ГБ', 'ТБ', 'ПБ', 'EБ');

$day_of_week = array('Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота');
$month = array('Января', 'Февраля', 'Марта', 'Апреля', 'Мая', 'Июня', 'Июля', 'Августа', 'Сентября', 'Октября', 'Ноября', 'Декабря');

$datefmt = '%d %B, %Y в %I:%M %p';
$timespanfmt = '%s дней, %s часов, %s минут %s секунд';
////////////////// FUNCTION LIST /////////////////////////

function formatByteDown($value, $limes = 6, $comma = 0)
{
	$dh           = pow(10, $comma);
	$li           = pow(10, $limes);
	$return_value = $value;
	$unit         = $GLOBALS['byteUnits'][0];

	for ( $d = 6, $ex = 15; $d >= 1; $d--, $ex-=3 ) {
		if (isset($GLOBALS['byteUnits'][$d]) && $value >= $li * pow(10, $ex)) {
			$value = round($value / ( pow(1024, $d) / $dh) ) /$dh;
			$unit = $GLOBALS['byteUnits'][$d];
			break 1;
		} // end if
	} // end for

	if ($unit != $GLOBALS['byteUnits'][0]) {
		$return_value = number_format($value, $comma, '.', ',');
	} else {
		$return_value = number_format($value, 0, '.', ',');
	}

	return array($return_value, $unit);
} // end of the 'formatByteDown' function


function timespanFormat($seconds)
{
	$return_string = '';
	$days = floor($seconds / 86400);
	if ($days > 0) {
		$seconds -= $days * 86400;
	}
	$hours = floor($seconds / 3600);
	if ($days > 0 || $hours > 0) {
		$seconds -= $hours * 3600;
	}
	$minutes = floor($seconds / 60);
	if ($days > 0 || $hours > 0 || $minutes > 0) {
		$seconds -= $minutes * 60;
	}
	return (string)$days." Дней ". (string)$hours." Часов ". (string)$minutes." Минут ". (string)$seconds." Секунд ";
}


function localisedDate($timestamp = -1, $format = '')
{
	global $datefmt, $month, $day_of_week;

	if ($format == '') {
		$format = $datefmt;
	}

	if ($timestamp == -1) {
		$timestamp = time();
	}

	$date = preg_replace('@%[aA]@', $day_of_week[(int)strftime('%w', $timestamp)], $format);
	$date = preg_replace('@%[bB]@', $month[(int)strftime('%m', $timestamp)-1], $date);

	return strftime($date, $timestamp);
} // end of the 'localisedDate()' function

////////////////////// END FUNCTION LIST /////////////////////////////////////


$REL_TPL->stdhead("Статистка Mysql");
echo '<h2>'."\n".'Статус базы данных (MYSQL)'."\n".'</h2><br />'."\n";

$res = @sql_query('SHOW STATUS') or Die(mysql_error());
while ($row = mysql_fetch_row($res)) {
	$serverStatus[$row[0]] = $row[1];
}
@mysql_free_result($res);
unset($res);
unset($row);

// просчет времени
$res = @sql_query('SELECT UNIX_TIMESTAMP() - ' . $serverStatus['Uptime']);
$row = mysql_fetch_row($res);
//echo sprintf("Server Status Uptime", timespanFormat($serverStatus['Uptime']), localisedDate($row[0])) . "\n";
?>

<table id="torrenttable" border="1">
	<tr>
		<td><?
		print("Mysql работает ". timespanFormat($serverStatus['Uptime']) .". Был запущен ". localisedDate($row[0])) . "\n";

		$dbname = $mysql_db;

		$result = sql_query("SHOW TABLES FROM ".$dbname."");
		$content = "";
		while (list($name) = mysql_fetch_array($result)) $content .= "<option value=\"".$name."\" selected>".$name."</option>";
		echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"3\" align=\"center\">"
		."<form method=\"post\" action=\"".$REL_SEO->make_link('mysqlstats')."\">"
		."<tr><td><select name=\"datatable[]\" size=\"10\" multiple=\"multiple\" style=\"width:400px\">".$content."</select></td><td>"
		."<table border=\"0\" cellspacing=\"0\" cellpadding=\"3\">"
		."<tr><td valign=\"top\"><input type=\"radio\" name=\"type\" value=\"Optimize\" checked></td><td>Оптимизация базы данных<br /><font class=\"small\">Производя оптимизацию базы данных, Вы уменьшаете её размер и соответственно с этим ускоряете её работу. Рекомендуется использовать данную функцию минимум один раз в неделю.</font></td></tr>"
		."<tr><td valign=\"top\"><input type=\"radio\" name=\"type\" value=\"Repair\"></td><td>Ремонт базы данных<br /><font class=\"small\">При неожиданной остановке MySQL сервера, во время выполнения каких-либо действий, может произойти повреждение структуры таблиц базы данных, использование этой функции произведёт ремонт повреждённых таблиц.</font></td></tr></table>"
		."</td></tr>"
		."<input type=\"hidden\" name=\"op\" value=\"StatusDB\">"
		."<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" value=\"Выполнить действие\"></td></tr></form></table>";

		if ($_POST['type'] == "Optimize") {
			$result = sql_query("SHOW TABLE STATUS FROM ".$dbname."");
			$tables = array();
			while ($row = mysql_fetch_array($result)) {
				$total = $row['Data_length'] + $row['Index_length'];
				$totaltotal += $total;
				$free = ($row['Data_free']) ? $row['Data_free'] : 0;
				$totalfree += $free;
				$i++;
				$otitle = (!$free) ? "<font color=\"#FF0000\">Не нуждается</font>" : "<font color=\"#009900\">Оптимизирована</font>";
				//sql_query("OPTIMIZE TABLE ".$row[0]."");
				$tables[] = $row[0];
				$content3 .= "<tr class=\"bgcolor1\"><td align=\"center\">".$i."</td><td>".$row[0]."</td><td>".mksize($total)."</td><td align=\"center\">".$otitle."</td><td align=\"center\">".mksize($free)."</td></tr>";
			}
			sql_query("OPTIMIZE TABLE ".implode(", ", $tables));
			echo "<center><font class=\"option\">Оптимизация базы данных: ".$dbname."<br />Общий размер базы данных: ".mksize($totaltotal)."<br />Общие накладные расходы: ".mksize($totalfree)."<br /><br />"
			."<table border=\"0\" cellpadding=\"3\" cellspacing=\"1\" width=\"100%\"><tr><td class=\"colhead\" align=\"center\">№</td><td class=\"colhead\">Таблица</td><td class=\"colhead\">Размер</td><td class=\"colhead\">Статус</td><td class=\"colhead\">Накладные расходы</td></tr>"
			."".$content3."</table>";
		} elseif ($_POST['type'] == "Repair") {
			$result = sql_query("SHOW TABLE STATUS FROM ".$dbname."");
			while ($row = mysql_fetch_array($result)) {
				$total = $row['Data_length'] + $row['Index_length'];
				$totaltotal += $total;
				$i++;
				$rresult = sql_query("REPAIR TABLE ".$row[0]."");
				$otitle = (!$rresult) ? "<font color=\"#FF0000\">Ошибка</font>" : "<font color=\"#009900\">OK</font>";
				$content4 .= "<tr class=\"bgcolor1\"><td align=\"center\">".$i."</td><td>".$row[0]."</td><td>".mksize($total)."</td><td align=\"center\">".$otitle."</td></tr>";
			}
			echo "<center><font class=\"option\">Ремонт базы данных: ".$dbname."<br />Общий размер базы данных: ".mksize($totaltotal)."<br /><br />"
			."<table border=\"0\" cellpadding=\"3\" cellspacing=\"1\" width=\"100%\"><tr><td class=\"colhead\" align=\"center\">№</td><td class=\"colhead\">Таблица</td><td class=\"colhead\">Размер</td><td class=\"colhead\">Статус</td></tr>"
			."".$content4."</table>";
		}


		?></td>
	</tr>
</table>

		<?
		mysql_free_result($res);
		unset($res);
		unset($row);
		//берем статистику запросов N01heDc=
		$queryStats = array();
		$tmp_array = $serverStatus;
		foreach($tmp_array AS $name => $value) {
			if (substr($name, 0, 4) == 'Com_') {
				$queryStats[str_replace('_', ' ', substr($name, 4))] = $value;
				unset($serverStatus[$name]);
			}
		}
		unset($tmp_array);
		?>

<ul>
	<li><!-- Трафик сервера --> <b>Трафик сервера: </b> Показ таблиц
	сетевого трафика с момента последнего запуска <br />
	<table border="0">
		<tr>
			<td valign="top">
			<table id="torrenttable" border="0">
				<tr>
					<th colspan="2" bgcolor="lightgrey">&nbsp;Трафик&nbsp;</th>
					<th bgcolor="lightgrey">&nbsp;&nbsp;За час&nbsp;</th>
				</tr>
				<tr>
					<td bgcolor="#EFF3FF">&nbsp;Полученно&nbsp;</td>
					<td bgcolor="#EFF3FF" align="right">&nbsp;<? echo join(' ', formatByteDown($serverStatus['Bytes_received'])); ?>&nbsp;</td>
					<td bgcolor="#EFF3FF" align="right">&nbsp;<? echo join(' ', formatByteDown($serverStatus['Bytes_received'] * 3600 / $serverStatus['Uptime'])); ?>&nbsp;</td>
				</tr>
				<tr>
					<td bgcolor="#EFF3FF">&nbsp;Послано&nbsp;</td>
					<td bgcolor="#EFF3FF" align="right">&nbsp;<? echo join(' ', formatByteDown($serverStatus['Bytes_sent'])); ?>&nbsp;</td>
					<td bgcolor="#EFF3FF" align="right">&nbsp;<? echo join(' ', formatByteDown($serverStatus['Bytes_sent'] * 3600 / $serverStatus['Uptime'])); ?>&nbsp;</td>
				</tr>
				<tr>
					<td bgcolor="lightgrey">&nbsp;Всего&nbsp;</td>
					<td bgcolor="lightgrey" align="right">&nbsp;<? echo join(' ', formatByteDown($serverStatus['Bytes_received'] + $serverStatus['Bytes_sent'])); ?>&nbsp;</td>
					<td bgcolor="lightgrey" align="right">&nbsp;<? echo join(' ', formatByteDown(($serverStatus['Bytes_received'] + $serverStatus['Bytes_sent']) * 3600 / $serverStatus['Uptime'])); ?>&nbsp;</td>
				</tr>
			</table>
			</td>
			<td valign="top">
			<table id="torrenttable" border="0">
				<tr>
					<th colspan="2" bgcolor="lightgrey">&nbsp;Соединений&nbsp;</th>
					<th bgcolor="lightgrey">&nbsp;&oslash;&nbsp;За час&nbsp;</th>
					<th bgcolor="lightgrey">&nbsp;%&nbsp;</th>
				</tr>
				<tr>
					<td bgcolor="#EFF3FF">&nbsp;Проваленные попытки&nbsp;</td>
					<td bgcolor="#EFF3FF" align="right">&nbsp;<? echo number_format($serverStatus['Aborted_connects'], 0, '.', ','); ?>&nbsp;</td>
					<td bgcolor="#EFF3FF" align="right">&nbsp;<? echo number_format(($serverStatus['Aborted_connects'] * 3600 / $serverStatus['Uptime']), 2, '.', ','); ?>&nbsp;</td>
					<td bgcolor="#EFF3FF" align="right">&nbsp;<? echo ($serverStatus['Connections'] > 0 ) ? number_format(($serverStatus['Aborted_connects'] * 100 / $serverStatus['Connections']), 2, '.', ',') . '&nbsp;%' : '---'; ?>&nbsp;</td>
				</tr>
				<tr>
					<td bgcolor="#EFF3FF">&nbsp;Отменено клиентами&nbsp;</td>
					<td bgcolor="#EFF3FF" align="right">&nbsp;<? echo number_format($serverStatus['Aborted_clients'], 0, '.', ','); ?>&nbsp;</td>
					<td bgcolor="#EFF3FF" align="right">&nbsp;<? echo number_format(($serverStatus['Aborted_clients'] * 3600 / $serverStatus['Uptime']), 2, '.', ','); ?>&nbsp;</td>
					<td bgcolor="#EFF3FF" align="right">&nbsp;<? echo ($serverStatus['Connections'] > 0 ) ? number_format(($serverStatus['Aborted_clients'] * 100 / $serverStatus['Connections']), 2 , '.', ',') . '&nbsp;%' : '---'; ?>&nbsp;</td>
				</tr>
				<tr>
					<td bgcolor="lightgrey">&nbsp;Всего&nbsp;</td>
					<td bgcolor="lightgrey" align="right">&nbsp;<? echo number_format($serverStatus['Connections'], 0, '.', ','); ?>&nbsp;</td>
					<td bgcolor="lightgrey" align="right">&nbsp;<? echo number_format(($serverStatus['Connections'] * 3600 / $serverStatus['Uptime']), 2, '.', ','); ?>&nbsp;</td>
					<td bgcolor="lightgrey" align="right">&nbsp;<? echo number_format(100, 2, '.', ','); ?>&nbsp;%&nbsp;</td>
				</tr>
			</table>
			</td>
		</tr>
	</table>
	</li>
	<br />
	<li><!-- запросы --> <? print("<b>Статистика Запросов: </b> с момента запуска - ". number_format($serverStatus['Questions'], 0, '.', ',')." запросов было посланно на сервер.\n"); ?>
	<table border="0">
		<tr>
			<td colspan="2"><br />
			<table id="torrenttable" border="0" align="right">
				<tr>
					<th bgcolor="lightgrey">&nbsp;Всего&nbsp;</th>
					<th bgcolor="lightgrey">&nbsp;&oslash;&nbsp;За&nbsp;Час&nbsp;</th>
					<th bgcolor="lightgrey">&nbsp;&oslash;&nbsp;За&nbsp;Минут&nbsp;</th>
					<th bgcolor="lightgrey">&nbsp;&oslash;&nbsp;За&nbsp;Секунд&nbsp;</th>
				</tr>
				<tr>
					<td bgcolor="#EFF3FF" align="right">&nbsp;<? echo number_format($serverStatus['Questions'], 0, '.', ','); ?>&nbsp;</td>
					<td bgcolor="#EFF3FF" align="right">&nbsp;<? echo number_format(($serverStatus['Questions'] * 3600 / $serverStatus['Uptime']), 2, '.', ','); ?>&nbsp;</td>
					<td bgcolor="#EFF3FF" align="right">&nbsp;<? echo number_format(($serverStatus['Questions'] * 60 / $serverStatus['Uptime']), 2, '.', ','); ?>&nbsp;</td>
					<td bgcolor="#EFF3FF" align="right">&nbsp;<? echo number_format(($serverStatus['Questions'] / $serverStatus['Uptime']), 2, '.', ','); ?>&nbsp;</td>
				</tr>
			</table>
			</td>
		</tr>
		<tr>
			<td valign="top">
			<table id="torrenttable" border="0">
				<tr>
					<th colspan="2" bgcolor="lightgrey">&nbsp;Тип&nbsp;Запроса&nbsp;</th>
					<th bgcolor="lightgrey">&nbsp;&oslash;&nbsp;За&nbsp;Час&nbsp;</th>
					<th bgcolor="lightgrey">&nbsp;%&nbsp;</th>
				</tr>
				<?

				$useBgcolorOne = TRUE;
				$countRows = 0;
				foreach ($queryStats as $name => $value) {


					?>
				<tr>
					<td bgcolor="#EFF3FF">&nbsp;<? echo htmlspecialchars($name); ?>&nbsp;</td>
					<td bgcolor="#EFF3FF" align="right">&nbsp;<? echo number_format($value, 0, '.', ','); ?>&nbsp;</td>
					<td bgcolor="#EFF3FF" align="right">&nbsp;<? echo number_format(($value * 3600 / $serverStatus['Uptime']), 2, '.', ','); ?>&nbsp;</td>
					<td bgcolor="#EFF3FF" align="right">&nbsp;<? echo number_format(($value * 100 / ($serverStatus['Questions'] - $serverStatus['Connections'])), 2, '.', ','); ?>&nbsp;%&nbsp;</td>
				</tr>
				<?
				$useBgcolorOne = !$useBgcolorOne;
				if (++$countRows == ceil(count($queryStats) / 2)) {
					$useBgcolorOne = TRUE;
					?>
			</table>
			</td>
			<td valign="top">
			<table id="torrenttable" border="0">
				<tr>
					<th colspan="2" bgcolor="lightgrey">&nbsp;Тип&nbsp;Запроса&nbsp;</th>
					<th bgcolor="lightgrey">&nbsp;&oslash;&nbsp;За&nbsp;Час&nbsp;</th>
					<th bgcolor="lightgrey">&nbsp;%&nbsp;</th>
				</tr>
				<?
				}
				}
				unset($countRows);
				unset($useBgcolorOne);
				?>
			</table>
			</td>
		</tr>
	</table>
	</li>
	<?
	//Unset used variables
	unset($serverStatus['Aborted_clients']);
	unset($serverStatus['Aborted_connects']);
	unset($serverStatus['Bytes_received']);
	unset($serverStatus['Bytes_sent']);
	unset($serverStatus['Connections']);
	unset($serverStatus['Questions']);
	unset($serverStatus['Uptime']);

	if (!empty($serverStatus)) {
		?>
	<br />
	<li><b>Статус значений</b><br />
	<table border="0">
		<tr>
			<td valign="top">
			<table id="torrenttable" border="0">
				<tr>
					<th bgcolor="lightgrey">&nbsp;Функция&nbsp;</th>
					<th bgcolor="lightgrey">&nbsp;Значение&nbsp;</th>
				</tr>
				<?  $useBgcolorOne = TRUE;   $countRows = 0; foreach($serverStatus AS $name => $value) { ?>
				<tr>
					<td bgcolor="#EFF3FF">&nbsp;<? echo htmlspecialchars(str_replace('_', ' ', $name)); ?>&nbsp;</td>
					<td bgcolor="#EFF3FF" align="right">&nbsp;<? echo htmlspecialchars($value); ?>&nbsp;</td>
				</tr>
				<?
				$useBgcolorOne = !$useBgcolorOne;
				if (++$countRows == ceil(count($serverStatus) / 3) || $countRows == ceil(count($serverStatus) * 2 / 3)) {
					$useBgcolorOne = TRUE;
					?>
			</table>
			</td>
			<td valign="top">
			<table id="torrenttable" border="0">
				<tr>
					<th bgcolor="lightgrey">&nbsp;Функция&nbsp;</th>
					<th bgcolor="lightgrey">&nbsp;Значение&nbsp;</th>
				</tr>
				<? } } unset($useBgcolorOne); ?>
			</table>
			</td>
		</tr>
	</table>
	</li>
	<? } ?>
</ul>
	<? $REL_TPL->stdfoot(); ?>