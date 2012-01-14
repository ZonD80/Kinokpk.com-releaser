{show_blocks('d')}
</td>

<td valign="top" class="blocks_r" width="222px">{show_blocks('r')}</td>
</tr>
</table>
<div class="clear"></div>
</div>
</table>

<div id="pagefooter">
<div id="pagefooters">

<div class="pagefooter_topborder clearfix">
<div class="copyright_and_location clearfix">
<div id="locale_selector_dialog_onclick">
<form action="{$REL_SEO->make_link('setlang')}" method="get"><span
	class="nobr"> <label></label> <select
	title="{$REL_LANG->_('Choose a language')}:" name="l">
	<option value="ru">Русский (RU)</option>
	<option value="en">English (EN-US)</option>
	<option value="ua">Українська (UA) beta</option>
</select> <input type="submit" class="button" value="OK" /> </span></form>
</div>
</div>
<div id="pagefooter_links">
<ul id="pagefooter_left_links">
	<li><a href="{$REL_SEO->make_link('about')}">{$REL_LANG->_('About project')}</a></li>
	<li><a href="{$REL_SEO->make_link('contact')}">{$REL_LANG->_('Contact us')}</a></li>
	<li><a href="{$REL_SEO->make_link('rules')}">{$REL_LANG->_('Rules')}</a></li>
	<li><a href="{$REL_SEO->make_link('aboutrating')}"><font color="red">{$REL_LANG->_('About rating system')}</font></a></li>
	<li><a href="{$REL_SEO->make_link('privacy')}">{$REL_LANG->_('Privacy')}</a></li>

</ul>
<ul id="pagefooter_right_links">
	<li><a href="{$REL_SEO->make_link('faq')}">{$REL_LANG->_('FAQ')}</a></li>
	<li><a href="{$REL_SEO->make_link('press')}">{$REL_LANG->_('For press')}</a></li>
	<li><a href="{$REL_SEO->make_link('providers')}">{$REL_LANG->_('For providers')}</a></li>
</ul>
</div>
</div>
<div id="toTop">^ TOP</div>
<div class="copyright"><span>{$COPYRIGHT}</span></div>

<div class="clear"></div>
<div align="center" id="counters"><!-- begin of Top100 code --> <script
	id="top100Counter" type="text/javascript"
	src="http://counter.rambler.ru/top100.jcn?1788919"></script>
<noscript><img src="http://counter.rambler.ru/top100.cnt?1788919" alt=""
	width="1" height="1" border="0" /></noscript>
<!-- end of Top100 code --> <!-- begin of Top100 logo --> <a
	href="http://top100.rambler.ru/home?id=1788919"><img
	src="http://top100-images.rambler.ru/top100/banner-88x31-rambler-darkblue2.gif"
	alt="Rambler's Top100" width="88" height="31" border="0" /></a> <!-- end of Top100 logo -->
<!--LiveInternet counter--><script type="text/javascript">
//<![CDATA[
jQuery("#counters").append("<a href='http://www.liveinternet.ru/click' "+
"><img src='http://counter.yadro.ru/hit?t12.6;r"+
escape(document.referrer)+((typeof(screen)=="undefined")?"":
";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
";"+Math.random()+
"' alt='' title='LiveInternet: показано число просмотров за 24"+
" часа, посетителей за 24 часа и за сегодня' "+
"border='0' width='88' height='31'><\/a>")
//]]>
</script><!--/LiveInternet--> <a
	href="http://www.toptracker.ru/details.php?id=1179"
	title="Проголосуй за TorrentsBook.com!"><img style="border: none;"
	src="http://www.toptracker.ru/buttons/toptracker.gif"
	title="Проголосуй за TorrentsBook.com!"
	alt="Проголосуй за TorrentsBook.com!" /></a> <a
	href="http://rustrackers.ru/details.php?id=175"><img
	src="http://rustrackers.ru/images/imgtop/006.gif" style="border: none;"
	title="Проголосуй за TorrentsBook.com!"
	alt="Проголосуй за TorrentsBook.com!" /></a></div>
<script type="text/javascript">
//<![CDATA[
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
jQuery("#counters").append(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
//]]>
</script> <!-- Yandex.Metrika --> <script
	src="//mc.yandex.ru/metrika/watch.js" type="text/javascript"></script>
<div style="display: none;"><script type="text/javascript">
try { var yaCounter1170032 = new Ya.Metrika(1170032); } catch(e){}
</script></div>
<noscript>
<div style="position: absolute"><img src="//mc.yandex.ru/watch/1170032"
	alt="" /></div>
</noscript>
<!-- /Yandex.Metrika --> <script type="text/javascript">

//<![CDATA[
try {
var pageTracker = _gat._getTracker("UA-9225099-1");
pageTracker._trackPageview();
} catch(err) {}
//]]>
</script></div>
</div>
</div>
{if $DEBUG} {include file='debug.tpl'} {/if} {$REL_RATING_POPUP}
{$AJAXPAGER}
</body>
</html>