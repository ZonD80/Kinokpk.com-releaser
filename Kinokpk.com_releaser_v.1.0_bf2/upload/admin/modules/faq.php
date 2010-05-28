<?php
if (!defined("ADMIN_FILE")) die("Illegal File Access");

function FaqAdmin() {
	global $rootpath, $pic_base_url;
	global $admin_file;
	// make the array that has all the faq in a nice structured
	$res = sql_query("SELECT `id`, `question`, `flag`, `order` FROM `faq` WHERE `type`='categ' ORDER BY `order` ASC") or sqlerr(__FILE__,__LINE__);
	while ($arr = mysql_fetch_array($res, MYSQL_BOTH)) {
		$faq_categ[$arr["id"]]["title"] = $arr["question"];
		$faq_categ[$arr["id"]]["flag"] = $arr["flag"];
		$faq_categ[$arr["id"]]["order"] = $arr["order"];
	}

	$res = sql_query("SELECT `id`, `question`, `flag`, `categ`, `order` FROM `faq` WHERE `type`='item' ORDER BY `order` ASC") or sqlerr(__FILE__,__LINE__);
	while ($arr = mysql_fetch_array($res, MYSQL_BOTH)) {
		$faq_categ[$arr["categ"]]["items"][$arr["id"]]["question"] = $arr["question"];
		$faq_categ[$arr["categ"]]["items"][$arr["id"]]["flag"] = $arr["flag"];
		$faq_categ[$arr["categ"]]["items"][$arr["id"]]["order"] = $arr["order"];
	}

	if (isset($faq_categ)) {
		// gather orphaned items
		foreach ($faq_categ as $id => $temp) {
		if (!array_key_exists("title", $faq_categ[$id])) {
			foreach ($faq_categ[$id]["items"] as $id2 => $temp) {
				$faq_orphaned[$id2]["question"] = $faq_categ[$id]["items"][$id2]["question"];
				$faq_orphaned[$id2]["flag"] = $faq_categ[$id]["items"][$id2]["flag"];
				unset($faq_categ[$id]);
			}
		}
		}

		// print the faq table
		print("<form method=\"post\" action=\"$admin_file.php?op=FaqAction&action=reorder\">");

		foreach ($faq_categ as $id => $temp) {
		print("<br />\n<table border=\"1\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\" width=\"95%\">\n");
		print("<tr><td class=\"colhead\" align=\"center\" colspan=\"2\">Позиция</td><td class=\"colhead\" align=\"left\">Секция/Название</td><td class=\"colhead\" align=\"center\">Статус</td><td class=\"colhead\" align=\"center\">Действие</td></tr>\n");

		print("<tr><td align=\"center\" width=\"40px\"><select name=\"order[". $id ."]\">");
		for ($n=1; $n <= count($faq_categ); $n++) {
			$sel = ($n == $faq_categ[$id]["order"]) ? " selected=\"selected\"" : "";
			print("<option value=\"$n\"". $sel .">". $n ."</option>");
		}
		$status = ($faq_categ[$id]["flag"] == "0") ? "<font color=\"red\">Скрыто</font>" : "Обычный";
		print("</select></td><td align=\"center\" width=\"40px\">&nbsp;</td><td><b>". $faq_categ[$id]["title"] ."</b></td><td align=\"center\" width=\"60px\">". $status ."</td><td align=\"center\" width=\"60px\"><a href=\"$admin_file.php?op=FaqAction&action=edit&id=". $id ."\">E</a> / <a href=\"$admin_file.php?op=FaqAction&action=delete&id=". $id ."\">D</a></td></tr>\n");

		if (array_key_exists("items", $faq_categ[$id])) {
			foreach ($faq_categ[$id]["items"] as $id2 => $temp) {
				print("<tr><td align=\"center\" width=\"40px\">&nbsp;</td><td align=\"center\" width=\"40px\"><select name=\"order[". $id2 ."]\">");
				for ($n=1; $n <= count($faq_categ[$id]["items"]); $n++) {
					$sel = ($n == $faq_categ[$id]["items"][$id2][order]) ? " selected=\"selected\"" : "";
					print("<option value=\"$n\"". $sel .">". $n ."</option>");
				}
				if ($faq_categ[$id]["items"][$id2][flag] == "0") $status = "<font color=\"#FF0000\">Скрыто</font>";
				elseif ($faq_categ[$id]["items"][$id2][flag] == "2") $status = "<font color=\"#0000FF\"><img src=\"".$rootpath.$pic_base_url."updated.png\" alt=\"Updated\" align=\"absbottom\"></font>";
				elseif ($faq_categ[$id]["items"][$id2][flag] == "3") $status = "<font color=\"#008000\"><img src=\"".$rootpath.$pic_base_url."new.png\" alt=\"Новое\" align=\"absbottom\"></font>";
				else $status = "Обычный";
				print("</select></td><td>". $faq_categ[$id]["items"][$id2]["question"] ."</td><td align=\"center\" width=\"60px\">". $status ."</td><td align=\"center\" width=\"60px\"><a href=\"$admin_file.php?op=FaqAction&action=edit&id=". $id2 ."\">E</a> / <a href=\"$admin_file.php?op=FaqAction&action=delete&id=". $id2 ."\">D</a></td></tr>\n");
			}
		}

		print("<tr><td colspan=\"5\" align=\"center\"><a href=\"$admin_file.php?op=FaqAction&action=additem&inid=". $id ."\">Добавить новый элемент</a></td></tr>\n");
		print("</table>\n");
		}
	}

	// print the orphaned items table
	if (isset($faq_orphaned)) {
		print("<br />\n<table border=\"1\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\" width=\"95%\">\n");
		print("<tr><td align=\"center\" colspan=\"3\"><b style=\"color: #FF0000\">Удаленные элементы</b></td>\n");
		print("<tr><td class=\"colhead\" align=\"left\">Item Title</td><td class=\"colhead\" align=\"center\">Status</td><td class=\"colhead\" align=\"center\">Actions</td></tr>\n");
		foreach ($faq_orphaned as $id => $temp) {
			if ($faq_orphaned[$id]["flag"] == "0") $status = "<font color=\"#FF0000\">Скрыто</font>";
			elseif ($faq_orphaned[$id]["flag"] == "2") $status = "<font color=\"#0000FF\">Обновлено</font>";
			elseif ($faq_orphaned[$id]["flag"] == "3") $status = "<font color=\"#008000\">Новое</font>";
			else $status = "Обычный";
			print("<tr><td>". $faq_orphaned[$id]["question"] ."</td><td align=\"center\" width=\"60px\">". $status ."</td><td align=\"center\" width=\"60px\"><a href=\"$admin_file.php?op=FaqAction&action=edit&id=". $id ."\">edit</a> <a href=\"$admin_file.php?op=FaqAction&action=delete&id=". $id ."\">delete</a></td></tr>\n");
		}
		print("</table>\n");
	}

	print("<br />\n<table border=\"1\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\" width=\"95%\">\n<tr><td align=\"center\"><a href=\"$admin_file.php?op=FaqAction&action=addsection\">Добавить новую секцию</a></td></tr>\n</table>\n");
	print("<p align=\"center\"><input type=\"submit\" name=\"reorder\" value=\"Сортировать\" class=\"btn\"></p>\n");
	print("</form>\n");
}

function FaqAction() {
	global $admin_file;
	if ($_GET["action"] == "reorder") {
		foreach($_POST["order"] as $id => $position)
			sql_query("UPDATE `faq` SET `order`='$position' WHERE id=".sqlesc((int)$id)) or sqlerr(__FILE__,__LINE__);
		header("Location: $admin_file.php?op=FaqAdmin"); 
	}

	// ACTION: edit - edit a section or item
	elseif ($_GET["action"] == "edit" && isset($_GET[id])) {
	print("<h2>Edit Section or Item</h2>");

	$res = sql_query("SELECT * FROM `faq` WHERE `id`=".sqlesc((int)$_GET[id])." LIMIT 1");
	while ($arr = mysql_fetch_array($res, MYSQL_BOTH)) {
	$arr["question"] = htmlspecialchars($arr["question"]);
	$arr["answer"] = htmlspecialchars($arr["answer"]);
	if ($arr[type] == "item") {
		print("<form method=\"post\" action=\"$admin_file.php?op=FaqAction&action=edititem\">");
		print("<table border=\"1\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\" width=100%>\n");
		print("<tr><td>ID:</td><td>$arr[id] <input type=\"hidden\" name=\"id\" value=\"$arr[id]\" /></td></tr>\n");
		print("<tr><td>Вопрос:</td><td><input id=specialboxg type=\"text\" name=\"question\" value=\"$arr[question]\" size=50 /></td></tr>\n");
		print("<tr><td style=\"vertical-align: top;\">Ответ:</td><td><textarea id=specialboxg rows=15 cols=80 name=\"answer\">$arr[answer]</textarea></td></tr>\n");
		print("<tr><td>Статус:</td><td><select name=\"flag\" style=\"width: 110px;\"><option value=\"0\" style=\"color: #FF0000;\"".($arr['flag'] == 0 ? " selected" : "").">Скрыто</option><option value=\"1\" style=\"color: #000000;\"".($arr['flag'] == 1 ? " selected" : "").">Обычный</option><option value=\"2\" style=\"color: #0000FF;\" ".($arr['flag'] == 2 ? "selected" : "").">Обновлено</option><option value=\"3\" style=\"color: #008000;\" ".($arr['flag'] == 3 ? "selected" : "").">Новое</option></select></td></tr>");

		print("<tr><td>Category:</td><td><select style=\"width: 300px;\" name=\"categ\" />");
		$res2 = sql_query("SELECT `id`, `question` FROM `faq` WHERE `type`='categ' ORDER BY `order` ASC");
		while ($arr2 = mysql_fetch_array($res2, MYSQL_BOTH)) {
			$selected = ($arr2[id] == $arr[categ]) ? " selected=\"selected\"" : "";
			print("<option value=\"$arr2[id]\"". $selected .">$arr2[question]</option>");
		}
		print("</td></tr>\n");
		print("<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"edit\" value=\"Отредактировать\" class=\"btn\"></td></tr>\n");
		print("</table>");
	}
	elseif ($arr[type] == "categ") {
		print("<form method=\"post\" action=\"$admin_file.php?op=FaqAction&action=editsect\">");
		print("<table border=\"1\" cellspacing=\"0\" cellpadding=\"5\" width=100% align=\"center\">\n");
		print("<tr><td>ID:</td><td>$arr[id] <input type=\"hidden\" name=\"id\" value=\"$arr[id]\" /></td></tr>\n");
		print("<tr><td>Название:</td><td><input style=\"width: 300px;\" type=\"text\" name=\"title\" value=\"$arr[question]\" id=specialboxn /></td></tr>\n");
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
	elseif ($_GET["action"] == "edititem" && $_POST["id"] != NULL && $_POST["question"] != NULL && $_POST["answer"] != NULL && $_POST["flag"] != NULL && $_POST["categ"] != NULL) {
		$question = sqlesc($_POST["question"]);
		$answer = sqlesc($_POST["answer"]);
		sql_query("UPDATE `faq` SET `question`=$question, `answer`=$answer, `flag`=".sqlesc($_POST["flag"]).", `categ`=".sqlesc($_POST["categ"])." WHERE id=".sqlesc($_POST["id"])) or sqlerr(__FILE__,__LINE__);
		header("Location: $admin_file.php?op=FaqAdmin"); 
	}

	// subACTION: editsect - edit a section
	elseif ($_GET[action] == "editsect" && $_POST["id"] != NULL && $_POST["title"] != NULL && $_POST["flag"] != NULL) {
		$title = sqlesc($_POST[title]);
		sql_query("UPDATE `faq` SET `question`=$title, `answer`='', `flag`=".sqlesc($_POST["flag"]).", `categ`='0' WHERE id=".sqlesc($_POST["id"])) or sqlerr(__FILE__,__LINE__);
		header("Location: $admin_file.php?op=FaqAdmin");
	}

	// ACTION: delete - delete a section or item
	elseif ($_GET["action"] == "delete" && isset($_GET["id"])) {
		if ($_GET["confirm"] == "yes") {
			sql_query("DELETE FROM `faq` WHERE `id`=".sqlesc($_GET["id"])." LIMIT 1") or sqlerr(__FILE__,__LINE__);
			header("Location: $admin_file.php?op=FaqAdmin"); 
		}
		else {
			print("<h1 align=\"center\">Confirmation required</h1>");
			print("<table border=\"1\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\" width=\"95%\">\n<tr><td align=\"center\">Please click <a href=\"$admin_file.php?op=FaqAction&action=delete&id=$_GET[id]&confirm=yes\">here</a> to confirm.</td></tr>\n</table>\n");
		}
	}

	// ACTION: additem - add a new item
	elseif ($_GET[action] == "additem" && $_GET["inid"]) {
		print("<h2>Add Item</h2>");
		print("<form method=\"post\" action=\"$admin_file.php?op=FaqAction&action=addnewitem\">");
		print("<table border=\"1\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\" width=\"100%\">\n");
		print("<tr><td>Вопрос:</td><td><input id=specialboxg type=\"text\" name=\"question\" value=\"\" /></td></tr>\n");
		print("<tr><td style=\"vertical-align: top;\">Ответ:</td><td><textarea id=specialboxg rows=15 cols=80 name=\"answer\"></textarea></td></tr>\n");
		print("<tr><td>Статус:</td><td><select name=\"flag\" style=\"width: 110px;\"><option value=\"0\" style=\"color: #FF0000;\">Скрыто</option><option value=\"1\" style=\"color: #000000;\">Обычный</option><option value=\"2\" style=\"color: #0000FF;\">Обновлено</option><option value=\"3\" style=\"color: #008000;\">Новое</option></select></td></tr>");
		print("<tr><td>Категория:</td><td><select style=\"width: 300px;\" name=\"categ\" />");
		$res = sql_query("SELECT `id`, `question` FROM `faq` WHERE `type`='categ' ORDER BY `order` ASC");
		while ($arr = mysql_fetch_array($res, MYSQL_BOTH)) {
			$selected = ($arr["id"] == $_GET["inid"]) ? " selected=\"selected\"" : "";
			print("<option value=\"{$arr["id"]}\"". $selected .">{$arr["question"]}</option>");
		}
		print("</td></tr>\n");
		print("<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" class=\"btn\" name=\"edit\" value=\"Добавить\"></td></tr>\n");
		print("</table>");
	}

	// ACTION: addsection - add a new section
	elseif ($_GET[action] == "addsection") {
		print("<h2>Add Section</h2>");
		print("<form method=\"post\" action=\"$admin_file.php?op=FaqAction&action=addnewsect\">");
		print("<table border=\"1\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\" width=\"100%\">\n");
		print("<tr><td>Title:</td><td><input style=\"width: 600px;\" type=\"text\" name=\"title\" value=\"\" id=specialboxn /></td></tr>\n");
		print("<tr><td>Status:</td><td><select name=\"flag\" style=\"width: 110px;\"><option value=\"0\" style=\"color: #FF0000;\">Скрыто</option><option value=\"1\" style=\"color: #000000;\">Обычный</option></select></td></tr>");
		print("<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"edit\" value=\"Add\" class=\"btn\" style=\"width: 60px;\"></td></tr>\n");
		print("</table>");
	}

	// subACTION: addnewitem - add a new item to the db
	elseif ($_GET[action] == "addnewitem" && $_POST[question] != NULL && $_POST[answer] != NULL && $_POST[flag] != NULL && $_POST[categ] != NULL) {
		$question = sqlesc($_POST[question]);
		$answer = sqlesc($_POST[answer]);
		$res = sql_query("SELECT MAX(`order`) FROM `faq` WHERE `type`='item' AND `categ`=".sqlesc($_POST[categ]));
		while ($arr = mysql_fetch_array($res, MYSQL_BOTH)) $order = $arr[0] + 1;
		sql_query("INSERT INTO `faq` (`type`, `question`, `answer`, `flag`, `categ`, `order`) VALUES ('item', $question, $answer, ".sqlesc($_POST[flag]).", ".sqlesc($_POST[categ]).", ".sqlesc($order).")") or sqlerr(__FILE__,__LINE__);
		header("Location: $admin_file.php?op=FaqAdmin");
	}

	// subACTION: addnewsect - add a new section to the db
	elseif ($_GET[action] == "addnewsect" && $_POST[title] != NULL && $_POST[flag] != NULL) {
		$title = sqlesc($_POST[title]);
		$res = sql_query("SELECT MAX(`order`) FROM `faq` WHERE `type`='categ'");
		while ($arr = mysql_fetch_array($res, MYSQL_BOTH)) $order = $arr[0] + 1;
		sql_query("INSERT INTO `faq` (`type`, `question`, `answer`, `flag`, `categ`, `order`) VALUES ('categ', $title, '', ".sqlesc($_POST[flag]).", '0', ".sqlesc($order).")") or sqlerr(__FILE__,__LINE__);
		header("Location: $admin_file.php?op=FaqAdmin");
	}

	else header("Location: $admin_file.php?op=FaqAdmin");
}

switch ($op) {
	case "FaqAdmin":
	FaqAdmin();
	break;
	case "FaqAction":
	FaqAction();
	break;
}

?>