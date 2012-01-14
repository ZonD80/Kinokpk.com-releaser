<?php
/**
 * Class changer for admin testing purposes
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once "include/bittorrent.php";

INIT();

loggedinorreturn();
httpauth();


// HTML Code to allow changes to current class
$REL_TPL->stdhead($REL_LANG->say_by_key('change_class'));

function e5cjs($str=''){//эскейпинг уровня JavaScript
	$result=$str;
	$result=str_replace ('\\', '\\\\', $result);
	$result=str_replace ("'", "\'", $result);
	$result=str_replace ('"', '\"', $result);
	$result=str_replace ("\r", "", $result);
	$result=str_replace ("\n", "\\n", $result);
	$result=str_replace ('</', '<\\/', $result);
	return $result;
}

function defvar($default_value, $waited){//если отсутствуют данные - отдает значение по умолчанию
	$result=($waited!='')?$waited:$default_value;
	return $result;
}
?>
<style type="text/css" charset="utf-8" media="screen">
#searchBox form {
	position: relative;
}

#searchBox form input.txt {
	display: block;
	padding: 4px 6px;
	width: 300px;
	border: 1px solid #ececec;
}

#searchBox form input.sbmt {
	position: absolute;
	left: 320px;
	top: 3px;
}

#searchBox div.results ol li {
	margin-bottom: 1em;
}

#searchBox div.results ol li p {
	margin: 0;
}

#searchBox div.results ol li p b {
	color: #72B11F;
}

#searchBox div.pages {
	margin: 2em 0;
	font-size: 13px;
}

#searchBox div.pages * {
	padding-left: 5px;
}

#searchBox div.pages a {
	text-decoration: none;
}

#searchBox div.pages b {
	color: #72B11F;
}
</style>
<script
	type="text/javascript" src="/js/search.js" charset="utf-8"></script>
<div id="searchBox">
<p>Чтобы воспользоваться поиском, необходимо включить поддержку
JavaScript.</p>
</div>

<script type="text/javascript"><!--
	C_search.initial_query='<? echo e5cjs(strip_tags($_GET['q'])) ?>';
	C_search.initial_page='<? echo defvar(1,intval($_GET['p'])) ?>';
//--></script>





<?
$REL_TPL->stdfoot();
/*
 <div id="cse" style="width: 100%;">Loading</div>
 <script src="http://www.google.com/jsapi" type="text/javascript"></script>
 <script type="text/javascript">
 google.load('search', '1', {language : 'ru'});
 google.setOnLoadCallback(function() {
 var customSearchControl = new google.search.CustomSearchControl('009791040494294785573:arqdadcdep0');
 customSearchControl.setResultSetSize(google.search.Search.FILTERED_CSE_RESULTSET);
 customSearchControl.draw('cse');
 }, true);
 </script>

 <div id="cse" style="width: 100%;">Loading</div>
 <script src="http://www.google.com/jsapi" type="text/javascript"></script>
 <script type="text/javascript">
 google.load('search', '1', {language : 'ru'});
 google.setOnLoadCallback(function() {
 var customSearchControl = new google.search.CustomSearchControl('009791040494294785573:c0dpnnjm6w8');
 customSearchControl.setResultSetSize(google.search.Search.FILTERED_CSE_RESULTSET);
 customSearchControl.draw('cse');
 }, true);
</script>
 
      
*/
?>

