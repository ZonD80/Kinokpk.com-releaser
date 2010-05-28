<?php
if (!defined('BLOCK_FILE')) {
 Header("Location: ../index.php");
 exit;
}

global $CURUSER, $tracker_lang, $ss_uri;

  // Get current poll
  $res = sql_query("SELECT polls.* , COUNT(pollcomments.id) as numcomm FROM polls LEFT JOIN pollcomments ON pollcomments.poll = polls.id GROUP BY polls.id ORDER BY polls.added DESC LIMIT 1") or sqlerr(__FILE__, __LINE__);
  if($pollok=(mysql_num_rows($res))) {
          $arr = mysql_fetch_assoc($res);
          $pollid = $arr["id"];
          $userid = $CURUSER["id"];
          $question = $arr["question"];
          $comments = $arr['numcomm'];
          $o = array($arr["option0"], $arr["option1"], $arr["option2"], $arr["option3"], $arr["option4"],
            $arr["option5"], $arr["option6"], $arr["option7"], $arr["option8"], $arr["option9"],
            $arr["option10"], $arr["option11"], $arr["option12"], $arr["option13"], $arr["option14"],
            $arr["option15"], $arr["option16"], $arr["option17"], $arr["option18"], $arr["option19"]);

  // Check if user has already voted
          $res = sql_query("SELECT * FROM pollanswers WHERE pollid=$pollid AND userid=$userid") or sqlerr(__FILE__, __LINE__);
          $arr2 = mysql_fetch_assoc($res);
  }

  $blocktitle = $tracker_lang['poll'].(get_user_class() >= UC_MODERATOR ? "<font class=\"small\"> - [<a class=\"altlink\" href=\"makepoll.php?returnto=main\"><b>".$tracker_lang['create']."</b></a>]".($pollok ? " - [<a class=\"altlink\" href=\"makepoll.php?action=edit&pollid=$arr[id]&returnto=main\"><b>Редактировать</b></a>] - [<a class=\"altlink\" href=\"polls.php?action=delete&pollid=$arr[id]&returnto=main\"><b>Удалить</b></a>]" : "")."</font>" : "");

        if($pollok) {
          $content .= ("<p align=\"center\"><b>$question</b></p>\n");
          $voted = $arr2;
          if ($voted) {
            // display results
            if ($arr["selection"])
              $uservote = $arr["selection"];
            else
              $uservote = -1;
                        // we reserve 255 for blank vote.
            $res = sql_query("SELECT selection FROM pollanswers WHERE pollid=$pollid AND selection < 20") or sqlerr(__FILE__, __LINE__);

            $tvotes = mysql_num_rows($res);

            $vs = array(); // array of
            $os = array();

            // Count votes
            while ($arr2 = mysql_fetch_row($res))
              $vs[$arr2[0]] += 1;

            reset($o);
            for ($i = 0; $i < count($o); ++$i)
              if ($o[$i])
                $os[$i] = array($vs[$i], $o[$i]);

            function srt($a,$b) {
              if ($a[0] > $b[0]) return -1;
              if ($a[0] < $b[0]) return 1;
              return 0;
            }

            // now os is an array like this: array(array(123, "Option 1"), array(45, "Option 2"))
            if ($arr["sort"] == "yes")
                usort($os, srt);

            $content .= ("<table class=\"main\" align=\"center\" width=\"250\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n");
            $i = 0;
            while ($a = $os[$i]) {
              if ($i == $uservote)
                $a[1] .= "&nbsp;*";
              if ($tvotes == 0)
                      $p = 0;
              else
                      $p = round($a[0] / $tvotes * 100);
              if ($i % 2)
                $c = "";
              else
                $c = " bgcolor=\"#eeeeee\"";
              $content .= ("<tr><td width=\"1%\" class=\"embedded\"$c><nobr>" . $a[1] . "&nbsp;&nbsp;</nobr></td><td width=\"99%\" class=\"embedded\"$c><nobr>" .
                "<img src=\"./themes/$ss_uri/images/bar_left.gif\"><img src=\"./themes/$ss_uri/images/bar.gif\" height=\"12\" width=\"" . ($p * 3) .
                "\"><img src=\"./themes/$ss_uri/images/bar_right.gif\"> $p%</nobr></td></tr>\n");
              ++$i;
            }
            $content .= ("</table>\n");
            $tvotes = number_format($tvotes);
            $content .= ("<p align=\"center\">Голосов: $tvotes Комментариев: $comments</p>\n");
          } else {
            $content .= ("<form method=\"post\" action=\"index.php\">\n");
            $i = 0;
            while ($a = $o[$i]) {
              $content .= ("<input type=\"radio\" name=\"choice\" value=\"$i\">$a<br />\n");
              ++$i;
            }
            $content .= ("<br />");
            $content .= ("<input type=\"radio\" name=\"choice\" value=\"255\">".$tracker_lang['blank_vote']."<br />\n");
            $content .= ("<p align=\"center\"><input type=\"submit\" value=\"".$tracker_lang['vote']."!\" class=\"btn\"></p>");
            $content .= ("</form><td><div align=\"center\">[<a href=\"polls.php\">".$tracker_lang['old_polls']."</a>]</div></td><td><div align=\"center\">[<a href=\"polloverview.php?id=".$pollid."\">Комментировать / подробнее</a>]</div></td>\n");
          }
		if ($voted)
		  $content .= ("<td><div align=\"center\">[<a href=\"polls.php\">".$tracker_lang['old_polls']."</a>]</div></td><td><div align=\"center\">[<a href=\"polloverview.php?id=".$pollid."\">Комментировать / подробнее</a>]</div></td>\n");
        } else {
			$content .= "<table class=\"main\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"10\"><tr><td class=\"text\">";
			$content .= "<div align=\"center\"><h3>".$tracker_lang['no_polls']."</h3></div>\n";
			$content .= "</td></tr></table>";
        }
?>