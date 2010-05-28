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
    global $smilies, $DEFAULTBASEURL;

    begin_frame("Смайлы", true);

    begin_table(false, 5);

    print("<tr><td class=\"colhead\">Написание</td><td class=\"colhead\">Смайл</td></tr>\n");

    while (list($code, $url) = each($smilies))
      print("<tr><td>$code</td><td><img src=\"$DEFAULTBASEURL/pic/smilies/$url\"></td>\n");

    end_table();

    end_frame();
  }

  // Block menu function
  // Print out menu block!

function blok_menu($title, $content , $width="155") {
	global $ss_uri;
	print('<!-- Menu Left -->
<table class="bl" width="165" border="0" cellspacing="0" cellpadding="0"><tr><td><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="3"><img src="themes/'.$ss_uri.'/images/u_3.jpg" width="4" height="38"></td><td class="btitle"><font class="block-title">'.$title.'</font></td><td width="3"><img src="themes/'.$ss_uri.'/images/u_4.jpg" width="4" height="38"></td></tr></table></td></tr><tr><td><table class="bborder" width="165" align="center" cellspacing="0" cellpadding="0"><tr><td>'.$content.'</td></tr></table></td></tr><tr><td><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td><td width="4"><img src="themes/'.$ss_uri.'/images/u_1.jpg" width="4" height="3"></td><td style="background-image:url(themes/'.$ss_uri.'/images/rep_u.jpg) "><img src="themes/'.$ss_uri.'/images/rep_u.jpg" width="1" height="3"></td><td width="3"><img src="themes/'.$ss_uri.'/images/u_2.jpg" width="3" height="3"></td></tr></table></td></tr></table><br />
<!-- \ Menu Left -->');
}

?>