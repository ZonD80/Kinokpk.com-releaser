<?
if(!defined("IN_TRACKER")) die("Direct access to this page not allowed");
show_blocks('d');
?>
<td valign="top" width="155"><?
show_blocks('r');
?></td>
</div>
<?



// Variables for End Time
$seconds = (timer() - $tstart);

$phptime = 		$seconds - $querytime;
$query_time = 	$querytime;
$percentphp = 	number_format(($phptime/$seconds) * 100, 2);
$percentsql = 	number_format(($query_time/$seconds) * 100, 2);
$seconds = 		substr($seconds, 0, 8);
print("</td></tr></table>\n");
print("<table class=\"bottom\" width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr valign=\"top\">\n");
print("<td width=\"49%\" class=\"bottom\"><div align=\"center\"><br /><b>".TBVERSION.(BETA?BETA_NOTICE:"")."<br />".sprintf($tracker_lang["page_generated"], $seconds, $queries, $percentphp, $percentsql)."</b><br/><nobr><form action=\"setlang.php\" method=\"get\"><select name=\"l\"><option value=\"russian\">Русский (RU)</option><option value=\"english\">English (EN-US)</option></select><input type=\"submit\" value=\"OK\"></form></nobr></div></td>\n");
print("</tr></table>\n");
?>