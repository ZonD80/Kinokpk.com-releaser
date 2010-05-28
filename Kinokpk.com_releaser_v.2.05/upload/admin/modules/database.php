<?php
if (!defined("ADMIN_FILE")) die("Illegal File Access");

$dbname = $mysql_db;

function StatusDB() {
	global $prefix, $admin_file, $dbname;
	$result = sql_query("SHOW TABLES FROM ".$dbname."");
	$content = "";
	while (list($name) = mysql_fetch_array($result)) $content .= "<option value=\"".$name."\" selected>".$name."</option>";
	echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"3\" align=\"center\">"
	."<form method=\"post\" action=\"".$admin_file.".php\">"
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
}

switch ($op) {
	case "StatusDB":
	StatusDB();
	break;
}