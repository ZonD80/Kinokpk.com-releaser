<?
// ---------------------------------------------------------------------------------------------------------

//-------- Begins a main frame

function begin_main_frame()
{
	print("<table class=\"main\" width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">" .
      "<tr><td class=\"embedded\">\n");
}

//-------- Ends a main frame

function end_main_frame()
{
	print("</td></tr></table>\n");
}

// ---------------------------------------------------------------------------------------------------------

function begin_table($fullwidth = false, $padding = 5)
{
	$width = "";

	if ($fullwidth)
	$width .= " width=\"100%\"";
	print("<table class=\"main\"$width border=\"1\" cellspacing=\"0\" cellpadding=\"$padding\">\n");
}

function end_table()
{
	print("</td></tr></table>\n");
}

// ---------------------------------------------------------------------------------------------------------

function begin_frame($caption = "", $center = false, $padding = 10)
{
	$tdextra = "";

	if ($caption)
	print("<h2>$caption</h2>\n");

	if ($center)
	$tdextra .= " align=\"center\"";

	print("<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"$padding\"><tr><td$tdextra>\n");

}

function attach_frame($padding = 10)
{
	print("</td></tr><tr><td style=\"border-top: 0px\">\n");
}

function end_frame()
{
	print("</td></tr></table>\n");
}

// ---------------------------------------------------------------------------------------------------------

//-------- Inserts a smilies frame
//         (move to globals)

function insert_smilies_frame()
{
	global $smilies, $CACHEARRAY;

	begin_frame("Смайлы", true);

	begin_table(false, 5);

	print("<tr><td class=\"colhead\">Написание</td><td class=\"colhead\">Смайл</td></tr>\n");

	while (list($code, $url) = each($smilies))
	print("<tr><td>$code</td><td><img src=\"pic/smilies/$url\"></td>\n");

	end_table();

	end_frame();
}

// Block menu function
// Print out menu block!

function blok_menu($title, $content , $width="155") {
	global $ss_uri;
	print('<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td class="block">
        <table width="100%" border="0" cellpadding="0" cellspacing="0"><tr>
        <td class="block" width="14" align="left"><img src="themes/'.$ss_uri.'/images/cellpic_left.gif" width="14" height="24"></td>
        <td class="block" width="100%" align="center" valign="middle" background="themes/'.$ss_uri.'/images/cellpic.gif"><nobr><font class="block-title" valign="bottom"><strong>'.$title.'</strong></font></nobr></td>
        <td class="block" width="14" align="right"><img src="themes/'.$ss_uri.'/images/cellpic_right.gif" width="14" height="24"></td>
        </tr></table>
        <table width="100%" border="0" cellspacing="1" cellpadding="3"><tr>
        <td align="left">'.$content.'</td>
        </tr></table>
</td></tr></table><br>');
}

?>
