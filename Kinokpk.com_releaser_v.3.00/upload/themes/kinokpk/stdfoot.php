<?
if(!defined("IN_TRACKER")) die("Direct access to this page not allowed");
show_blocks('d');
?>
</td>
<td class="tt" valign="top" width="155"><?
show_blocks('r');
?></td>
</tr>
</table>
<?



// Variables for End Time
$seconds = microtime(true) - $tstart;

$phptime =              $seconds - $querytime;
$query_time =   $querytime;
$percentphp =   number_format(($phptime/$seconds) * 100, 2);
$percentsql =   number_format(($query_time/$seconds) * 100, 2);
$seconds =              substr($seconds, 0, 8);

print("<table class=\"bottom\" width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr valign=\"top\">\n");
print("<td width=\"49%\" class=\"bottom\">
				<div align=\"center\"><br />
					<b>".TBVERSION.(BETA?BETA_NOTICE:"")."<br />
					".sprintf($tracker_lang["page_generated"], $seconds, $queries, $percentphp, $percentsql)."</b>
						<br />
						<form action=\"setlang.php\" method=\"get\">
						<span class=\"nobr\">
								<select title=\"Выберите язык/Choose a language:\" name=\"l\">
									<option value=\"ru\">Русский (RU)</option>
									<!--<option value=\"en\">English (EN-US)</option>-->
								</select>
						<input type=\"submit\" value=\"OK\" />
						</span>
						</form>	
				
				</div>
			</td>\n");
print("</tr></table>\n");
?>
