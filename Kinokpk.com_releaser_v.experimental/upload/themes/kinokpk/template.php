<?
// ---------------------------------------------------------------------------------------------------------

//-------- Begins a main frame

function begin_main_frame()
{

}

//-------- Ends a main frame

function end_main_frame()
{

}

// ---------------------------------------------------------------------------------------------------------

function begin_table($fullwidth = false, $padding = 5)
{
	if ($fullwidth)

	print("<table class=\"main\" border=\"1\" cellspacing=\"0\" cellpadding=\"$padding\">\n");
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

?>
