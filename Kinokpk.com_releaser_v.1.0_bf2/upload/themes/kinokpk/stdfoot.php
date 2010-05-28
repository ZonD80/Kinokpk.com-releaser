<?
	show_blocks('d');
?>
<br /></td></tr><tr><td width="3"><img src="./themes/<?=$ss_uri;?>/images/r_4.jpg" width="3" height="3"></td><td background="./themes/<?=$ss_uri;?>/images/r45.jpg"><img src="./themes/<?=$ss_uri;?>/images/r45.jpg" /></td><td width="3"><img src="./themes/<?=$ss_uri;?>/images/r_5.jpg" width="3" height="3"></td></tr></table>
<?
	show_blocks('r');


// Variables for End Time
$seconds = (timer() - $tstart);

$phptime = 		$seconds - $querytime;
$query_time = 	$querytime;
$percentphp = 	number_format(($phptime/$seconds) * 100, 2);
$percentsql = 	number_format(($query_time/$seconds) * 100, 2);
$seconds = 		substr($seconds, 0, 8);

	print("</td></tr></table>\n");
	print("<table id=\"footer\" width=\"100%\"  border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#EBEBEB\">
      <tr align=\"left\" valign=\"top\">
        <td width=\"9\"><img src=\"./themes/$ss_uri/images/q_1.jpg\" width=\"9\" height=\"47\"></td>
        <td width=\"100%\" align=\"center\" valign=\"middle\" style=\"background-image:url(./themes/$ss_uri/images/rep_2.jpg); background-position:bottom; background-repeat:repeat-x \"><div align=\"center\">
                <p align=\"center\"><font class=\"small\"><span class=\"footer\">".TBVERSION.(BETA?BETA_NOTICE:"")."<br />".sprintf($tracker_lang["page_generated"], $seconds, $queries, $percentphp, $percentsql)."</span></font></p>
        </div></td>
        <td width=\"9\"><img src=\"./themes/$ss_uri/images/q_2.jpg\" width=\"9\" height=\"47\"></td>
      </tr>
    </table>\n");
	print("</body></html>\n");
?>