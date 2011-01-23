<?php
/*********************/
/*                   */
/*  Version : 5.1.0  */
/*  Author  : RM     */
/*  Comment : 071223 */
/*                   */
/*********************/

require( "include/bittorrent.php" );
dbconn( );
loggedinorreturn( );
if (get_user_class()<UC_SYSOP) stderr($REL_LANG->_("Error"),$REL_LANG->_("Access denied"));
headers(true);
?>
<p>Begin to parse from rutor.org</p>
<form action="takeparser.php" method="get">
<input type="text" name="cat"/> Category: 1(kino), 2(audio), 3(other), 4(seriali), 6(tv), 7(multiki), 8(games), 9(soft), 10(anime), 11(knigi)<br/>
<input type="text" name="page"/> Page (100 tpp)<br/>
<input type="submit"/>
</form>