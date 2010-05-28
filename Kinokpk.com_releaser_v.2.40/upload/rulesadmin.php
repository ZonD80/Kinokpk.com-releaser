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

//recoded by Steel

require_once("include/bittorrent.php");
dbconn();
loggedinorreturn();
gzip();
httpauth();

if (get_user_class() < UC_SYSOP) die('Access denied. You\'re not SYSOP');


if (!isset($_GET['action'])) {
	stdhead("Rules Administration / Админка Правил");
	// make the array that has all the rules in a nice structured
	$res = sql_query("SELECT `id`, `rule`, `flag`, `order` FROM `rules` WHERE `type`='categ' ORDER BY `order` ASC") or sqlerr(__FILE__,__LINE__);
	while ($arr = mysql_fetch_array($res, MYSQL_BOTH)) {
		$rules_categ[$arr["id"]]["title"] = $arr["rule"];
		$rules_categ[$arr["id"]]["flag"] = $arr["flag"];
		$rules_categ[$arr["id"]]["order"] = $arr["order"];
	}

	$res = sql_query("SELECT `id`, `rule`, `flag`, `categ`, `order` FROM `rules` WHERE `type`='item' ORDER BY `order` ASC") or sqlerr(__FILE__,__LINE__);
	while ($arr = mysql_fetch_array($res, MYSQL_BOTH)) {
		$rules_categ[$arr["categ"]]["items"][$arr["id"]]["rule"] = $arr["rule"];
		$rules_categ[$arr["categ"]]["items"][$arr["id"]]["flag"] = $arr["flag"];
		$rules_categ[$arr["categ"]]["items"][$arr["id"]]["order"] = $arr["order"];
	}

	if (isset($rules_categ)) {
		// gather orphaned items
		foreach ($rules_categ as $id => $temp) {
			if (!array_key_exists("title", $rules_categ[$id])) {
				foreach ($rules_categ[$id]["items"] as $id2 => $temp) {
					$rules_orphaned[$id2]["rule"] = $rules_categ[$id]["items"][$id2]["rule"];
					$rules_orphaned[$id2]["flag"] = $rules_categ[$id]["items"][$id2]["flag"];
					unset($rules_categ[$id]);
				}
			}
		}

		// print the rules table
		print("<form method=\"post\" action=\"rulesadmin.php?action=reorder\">");

		foreach ($rules_categ as $id => $temp) {
			print("<br />\n<table border=\"1\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\" width=\"95%\">\n");
			print("<tr><td class=\"colhead\" align=\"center\" colspan=\"2\">Позиция</td><td class=\"colhead\" align=\"left\">Секция/Название</td><td class=\"colhead\" align=\"center\">Статус</td><td class=\"colhead\" align=\"center\">Действие</td></tr>\n");

			print("<tr><td align=\"center\" width=\"40px\"><select name=\"order[". $id ."]\">");
			for ($n=1; $n <= count($rules_categ); $n++) {
				$sel = ($n == $rules_categ[$id]["order"]) ? " selected=\"selected\"" : "";
				print("<option value=\"$n\"". $sel .">". $n ."</option>");
			}
			$status = ($rules_categ[$id]["flag"] == "0") ? "<font color=\"red\">Скрыто</font>" : "Обычный";
			print("</select></td><td align=\"center\" width=\"40px\">&nbsp;</td><td><b>". $rules_categ[$id]["title"] ."</b></td><td align=\"center\" width=\"60px\">". $status ."</td><td align=\"center\" width=\"60px\"><a href=\"rulesadmin.php?action=edit&id=". $id ."\">E</a> / <a onClick=\"return confirm('Вы уверены?')\" href=\"rulesadmin.php?action=delete&id=". $id ."\">D</a></td></tr>\n");

			if (array_key_exists("items", $rules_categ[$id])) {
				foreach ($rules_categ[$id]["items"] as $id2 => $temp) {
					print("<tr><td align=\"center\" width=\"40px\">&nbsp;</td><td align=\"center\" width=\"40px\"><select name=\"order[". $id2 ."]\">");
					for ($n=1; $n <= count($rules_categ[$id]["items"]); $n++) {
						$sel = ($n == $rules_categ[$id]["items"][$id2][order]) ? " selected=\"selected\"" : "";
						print("<option value=\"$n\"". $sel .">". $n ."</option>");
					}
					if ($rules_categ[$id]["items"][$id2][flag] == "0") $status = "<font color=\"#FF0000\">Скрыто</font>";
					elseif ($rules_categ[$id]["items"][$id2][flag] == "2") $status = "<font color=\"#0000FF\"><img src=\"".ROOT_PATH."pic/updated.png\" alt=\"Updated\" align=\"absbottom\"></font>";
					elseif ($rules_categ[$id]["items"][$id2][flag] == "3") $status = "<font color=\"#008000\"><img src=\"".ROOT_PATH."pic/new.png\" alt=\"Новое\" align=\"absbottom\"></font>";
					else $status = "Обычный";
					print("</select></td><td>". $rules_categ[$id]["items"][$id2]["rule"] ."</td><td align=\"center\" width=\"60px\">". $status ."</td><td align=\"center\" width=\"60px\"><a href=\"rulesadmin.php?action=edit&id=". $id2 ."\">E</a> / <a onClick=\"return confirm('Вы уверены?')\" href=\"rulesadmin.php?action=delete&id=". $id2 ."\">D</a></td></tr>\n");
				}
			}

			print("<tr><td colspan=\"5\" align=\"center\"><a href=\"rulesadmin.php?action=additem&inid=". $id ."\">Добавить новый элемент</a></td></tr>\n");
			print("</table>\n");
		}
	}

	// print the orphaned items table
	if (isset($rules_orphaned)) {
		print("<br />\n<table border=\"1\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\" width=\"95%\">\n");
		print("<tr><td align=\"center\" colspan=\"3\"><b style=\"color: #FF0000\">Удаленные элементы</b></td>\n");
		print("<tr><td class=\"colhead\" align=\"left\">Item Title</td><td class=\"colhead\" align=\"center\">Status</td><td class=\"colhead\" align=\"center\">Actions</td></tr>\n");
		foreach ($rules_orphaned as $id => $temp) {
			if ($rules_orphaned[$id]["flag"] == "0") $status = "<font color=\"#FF0000\">Скрыто</font>";
			elseif ($rules_orphaned[$id]["flag"] == "2") $status = "<font color=\"#0000FF\">Обновлено</font>";
			elseif ($rules_orphaned[$id]["flag"] == "3") $status = "<font color=\"#008000\">Новое</font>";
			else $status = "Обычный";
			print("<tr><td>". $rules_orphaned[$id]["rule"] ."</td><td align=\"center\" width=\"60px\">". $status ."</td><td align=\"center\" width=\"60px\"><a href=\"rulesadmin.php?action=edit&id=". $id ."\">edit</a> <a onClick=\"return confirm('Вы уверены?')\" href=\"rulesadmin.php?action=delete&id=". $id ."\">delete</a></td></tr>\n");
		}
		print("</table>\n");
	}

	print("<br />\n<table border=\"1\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\" width=\"95%\">\n<tr><td align=\"center\"><a href=\"rulesadmin.php?action=addsection\">Добавить новую секцию</a></td></tr>\n</table>\n");
	print("<p align=\"center\"><input type=\"submit\" name=\"reorder\" value=\"Сортировать\" class=\"btn\"></p>\n");
	print("</form>\n");

}
elseif ($_GET["action"] == "reorder") {
	foreach($_POST["order"] as $id => $position)
	sql_query("UPDATE `rules` SET `order`='$position' WHERE id=".sqlesc((int)$id)) or sqlerr(__FILE__,__LINE__);
	header("Location: rulesadmin.php");
}

// ACTION: edit - edit a section or item
elseif ($_GET["action"] == "edit" && isset($_GET[id])) {
	stdhead("Rules Administration / Админка Правила");
	print("<h2>Редактировать Правила</h2>");

	$res = sql_query("SELECT * FROM `rules` WHERE `id`=".sqlesc((int)$_GET[id])." LIMIT 1");
	while ($arr = mysql_fetch_array($res, MYSQL_BOTH)) {
		$arr["rule"] = htmlspecialchars($arr["rule"]);
		/* $arr["answer"] = htmlspecialchars($arr["answer"]); */
		if ($arr[type] == "item") {
			print("<form method=\"post\" action=\"rulesadmin.php?action=edititem\">");
			print("<table border=\"1\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\" width=100%>\n");
			print("<tr><td>ID:</td><td>$arr[id] <input type=\"hidden\" name=\"id\" value=\"$arr[id]\" /></td></tr>\n");
			/*   print("<tr><td>Вопрос:</td><td><input id=specialboxg type=\"text\" name=\"rule\" value=\"$arr[rule]\" size=50 /></td></tr>\n");   */
			print("<tr><td style=\"vertical-align: top;\">Вопрос:</td><td><textarea id=specialboxg rows=15 cols=80 name=\"rule\">$arr[rule]</textarea></td></tr>\n");
			print("<tr><td>Статус:</td><td><select name=\"flag\" style=\"width: 110px;\"><option value=\"0\" style=\"color: #FF0000;\"".($arr['flag'] == 0 ? " selected" : "").">Скрыто</option><option value=\"1\" style=\"color: #000000;\"".($arr['flag'] == 1 ? " selected" : "").">Обычный</option><option value=\"2\" style=\"color: #0000FF;\" ".($arr['flag'] == 2 ? "selected" : "").">Обновлено</option><option value=\"3\" style=\"color: #008000;\" ".($arr['flag'] == 3 ? "selected" : "").">Новое</option></select></td></tr>");

			print("<tr><td>Категория:</td><td><select style=\"width: 300px;\" name=\"categ\" />");
			$res2 = sql_query("SELECT `id`, `rule` FROM `rules` WHERE `type`='categ' ORDER BY `order` ASC");
			while ($arr2 = mysql_fetch_array($res2, MYSQL_BOTH)) {
				$selected = ($arr2[id] == $arr[categ]) ? " selected=\"selected\"" : "";
				print("<option value=\"$arr2[id]\"". $selected .">$arr2[rule]</option>");
			}
			print("</td></tr>\n");
			print("<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"edit\" value=\"Отредактировать\" class=\"btn\"></td></tr>\n");
			print("</table>");
		}
		elseif ($arr[type] == "categ") {
			print("<form method=\"post\" action=\"rulesadmin.php?action=editsect\">");
			print("<table border=\"1\" cellspacing=\"0\" cellpadding=\"5\" width=100% align=\"center\">\n");
			print("<tr><td>ID:</td><td>$arr[id] <input type=\"hidden\" name=\"id\" value=\"$arr[id]\" /></td></tr>\n");
			print("<tr><td>Название:</td><td><input style=\"width: 300px;\" type=\"text\" name=\"title\" value=\"$arr[rule]\" id=specialboxn /></td></tr>\n");
			if ($arr[flag] == "0")
			print("<tr><td>Статус:</td><td><select name=\"flag\" style=\"width: 110px;\"><option value=\"0\" style=\"color: #FF0000;\">Скрыто</option><option value=\"1\" style=\"color: #000000;\">Обычный</option></select></td></tr>");
			else
			print("<tr><td>Статус:</td><td><select name=\"flag\" style=\"width: 110px;\"><option value=\"0\" style=\"color: #FF0000;\"".($arr['flag'] == 0 ? " selected" : "").">Скрыто</option><option value=\"1\" style=\"color: #000000;\"".($arr['flag'] == 1 ? " selected" : "").">Обычный</option></select></td></tr>");
			print("<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" class=btn name=\"edit\" value=\"Отредактировать\"></td></tr>\n");
			print("</table>");
		}
	}

}

// subACTION: edititem - edit an item
elseif ($_GET["action"] == "edititem" && $_POST["id"] != NULL && $_POST["rule"] != NULL /* && $_POST["answer"] != NULL */ && $_POST["flag"] != NULL && $_POST["categ"] != NULL) {
	$rule = sqlesc($_POST["rule"]);
	/*   $answer = sqlesc($_POST["answer"]);    */
	sql_query("UPDATE `rules` SET `rule`=$rule, `flag`=".sqlesc($_POST["flag"]).", `categ`=".sqlesc($_POST["categ"])." WHERE id=".sqlesc($_POST["id"])) or sqlerr(__FILE__,__LINE__);
	header("Location: rulesadmin.php");
}

// subACTION: editsect - edit a section
elseif ($_GET[action] == "editsect" && $_POST["id"] != NULL && $_POST["title"] != NULL && $_POST["flag"] != NULL) {
	$title = sqlesc($_POST[title]);
	sql_query("UPDATE `rules` SET `rule`=$title, `flag`=".sqlesc($_POST["flag"]).", `categ`='0' WHERE id=".sqlesc($_POST["id"])) or sqlerr(__FILE__,__LINE__);
	header("Location: rulesadmin.php");
}

// ACTION: delete - delete a section or item
elseif ($_GET["action"] == "delete" && isset($_GET["id"])) {
	sql_query("DELETE FROM `rules` WHERE `id`=".sqlesc($_GET["id"])." LIMIT 1") or sqlerr(__FILE__,__LINE__);
	header("Location: rulesadmin.php");   }

	// ACTION: additem - add a new item
	elseif ($_GET[action] == "additem" && $_GET["inid"]) {
		stdhead("Rules Administration / Админка Правил");
		print("<h2>Add Item</h2>");
		print("<form method=\"post\" action=\"rulesadmin.php?action=addnewitem\">");
		print("<table border=\"1\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\" width=\"100%\">\n");
		/*   print("<tr><td>Вопрос:</td><td><input id=specialboxg type=\"text\" name=\"rule\" value=\"\" /></td></tr>\n"); */
		print("<tr><td style=\"vertical-align: top;\">Вопрос:</td><td><textarea id=specialboxg rows=15 cols=80 name=\"rule\"></textarea></td></tr>\n");
		print("<tr><td>Статус:</td><td><select name=\"flag\" style=\"width: 110px;\"><option value=\"0\" style=\"color: #FF0000;\">Скрыто</option><option value=\"1\" style=\"color: #000000;\">Обычный</option><option value=\"2\" style=\"color: #0000FF;\">Обновлено</option><option value=\"3\" style=\"color: #008000;\">Новое</option></select></td></tr>");
		print("<tr><td>Категория:</td><td><select style=\"width: 300px;\" name=\"categ\" />");
		$res = sql_query("SELECT `id`, `rule` FROM `rules` WHERE `type`='categ' ORDER BY `order` ASC");
		while ($arr = mysql_fetch_array($res, MYSQL_BOTH)) {
			$selected = ($arr["id"] == $_GET["inid"]) ? " selected=\"selected\"" : "";
			print("<option value=\"{$arr["id"]}\"". $selected .">{$arr["rule"]}</option>");
		}
		print("</td></tr>\n");
		print("<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" class=\"btn\" name=\"edit\" value=\"Добавить\"></td></tr>\n");
		print("</table>");
	}

	// ACTION: addsection - add a new section
	elseif ($_GET[action] == "addsection") {
		stdhead("Rules Administration / Админка Правил");
		print("<h2>Add Section</h2>");
		print("<form method=\"post\" action=\"rulesadmin.php?action=addnewsect\">");
		print("<table border=\"1\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\" width=\"100%\">\n");
		print("<tr><td>Title:</td><td><input style=\"width: 600px;\" type=\"text\" name=\"title\" value=\"\" id=specialboxn /></td></tr>\n");
		print("<tr><td>Status:</td><td><select name=\"flag\" style=\"width: 110px;\"><option value=\"0\" style=\"color: #FF0000;\">Скрыто</option><option value=\"1\" style=\"color: #000000;\">Обычный</option></select></td></tr>");
		print("<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"edit\" value=\"Add\" class=\"btn\" style=\"width: 60px;\"></td></tr>\n");
		print("</table>");
	}

	// subACTION: addnewitem - add a new item to the db
	elseif ($_GET[action] == "addnewitem" && $_POST[rule] != NULL /* && $_POST[answer] != NULL */ && $_POST[flag] != NULL && $_POST[categ] != NULL) {
		$rule = sqlesc($_POST[rule]);
		/*   $answer = sqlesc($_POST[answer]); */
		$res = sql_query("SELECT MAX(`order`) FROM `rules` WHERE `type`='item' AND `categ`=".sqlesc($_POST[categ]));
		while ($arr = mysql_fetch_array($res, MYSQL_BOTH)) $order = $arr[0] + 1;
		sql_query("INSERT INTO `rules` (`type`, `rule`, `flag`, `categ`, `order`) VALUES ('item', $rule, ".sqlesc($_POST[flag]).", ".sqlesc($_POST[categ]).", ".sqlesc($order).")") or sqlerr(__FILE__,__LINE__);
		header("Location: rulesadmin.php");
	}

	// subACTION: addnewsect - add a new section to the db
	elseif ($_GET[action] == "addnewsect" && $_POST[title] != NULL && $_POST[flag] != NULL) {
		$title = sqlesc($_POST[title]);
		$res = sql_query("SELECT MAX(`order`) FROM `rules` WHERE `type`='categ'");
		while ($arr = mysql_fetch_array($res, MYSQL_BOTH)) $order = $arr[0] + 1;
		sql_query("INSERT INTO `rules` (`type`, `rule`, `flag`, `categ`, `order`) VALUES ('categ', $title, ".sqlesc($_POST[flag]).", '0', ".sqlesc($order).")") or sqlerr(__FILE__,__LINE__);
		header("Location: rulesadmin.php");
	}

	else header("Location: rulesadmin.php");

	stdfoot();

	?>