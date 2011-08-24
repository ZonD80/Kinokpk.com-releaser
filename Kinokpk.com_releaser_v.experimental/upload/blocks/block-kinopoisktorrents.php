<?php

global $REL_LANG, $REL_CACHE, $REL_SEO;

if (!defined('BLOCK_FILE')) {
	safe_redirect($REL_SEO->make_link('index'));
	exit;
}

$content = $REL_CACHE->get('block-indextorrents','kinopoisk');

if ($content===false) {
	$content = '<script language="javascript" type="text/javascript" src="js/slider.js"></script>';

	$content .= '<table width="100%" border="0" cellspacing="1" cellpadding="3"><tr>
	<td align="left"><div id="coloredPicturesPanel"><div id="leftNav"><div><h4 class="active">'.$REL_LANG->say_by_key('browse').'</h4><ul>';

	function clear($text){
		$text = preg_replace("#\t|\r|\x0B|\n#si","",$text);
		// $text = preg_replace("#\n(.*?)\n#si","\n",$text);
		$text = preg_replace("#\&\#133;|\&\#151;#si","",strip_tags(trim(html_entity_decode($text,ENT_QUOTES))));
		return $text;
	}

	function get_vars($text, $option)

	{
		if ($option == 'flashcode') {
			$search = "#<span id=\"flashContent_(.*?)\"></span>#si";
		}
		elseif ($option == 'descr') {
			$search = "#<tr><td colspan=3 style=\"padding:10px;padding-left:20px;\" class=\"news\">(.*?)</td></tr>#si";
		}

		preg_match($search,$text,$result);
		return $result[1];

	}


	$res = sql_query("SELECT id,name,descr,images,free FROM torrents WHERE visible=1 AND banned=0 AND moderatedby<>0 ORDER BY added DESC LIMIT 8") or sqlerr(__FILE__, __LINE__);
	require_once(ROOT_PATH.'classes/parser/Snoopy.class.php');
	$page = new Snoopy;

	while ($row = mysql_fetch_assoc($res)) {
		preg_match("#http://www.kinopoisk.ru/level/1/film/(.*?)/#si",$row['descr'],$matches);

		$filmid = $matches[1];
		if ($filmid) {
			$page->fetch("http://www.kinopoisk.ru/level/1/film/$filmid/");
			$source = $page->results;
			$flashcode = get_vars($source,'flashcode');
			$row['descr'] = clear(get_vars($source, 'descr'));

		}
		if ($row['images']) $image = array_shift(explode(',',$row['images']));

		if (!$image) $imgcode = 'pic/noimage.gif'; else $imgcode = $image;

		$row['descr'] = strip_tags($row['descr']);

		if (mb_strlen($row['descr'])>300) $row['descr']=substr($row['descr'],0,300).'...';
		$content .= '<li><a href="'.$REL_SEO->make_link('details','id',$row['id'],'name',translit($row['name'])).'">'.(($row['free'])?'<img border="0" src="pic/freedownload.gif" alt="'.$REL_LANG->_('Golden release').'" title="'.$REL_LANG->_('Golden release').'"/>&nbsp;':'').$row['name'].'</a><div>
  <h5>'.$row['name'].'</h5><p>'.$row['descr'].'</p>'.($filmid?'<a href="http://www.kinopoisk.ru/level/1/film/'.$filmid.'/" class="info icon">info</a> '.($flashcode?'<a href="#__ClipID='.$flashcode.'" class="trailer icon">trailer</a> ':'').' ':'').'<a href="'.$REL_SEO->make_link('details','id',$row['id'],'name',translit($row['name'])).'" class="details icon">details</a></div><img class="changeImage" src="'.$imgcode.'" alt="'.$row['name'].'" title="'.$row['name'].'" /></li>';
		//break;
	}
	$content .='</ul></div></div><div id="subPanel"><div class="content"></div><div id="arrow"><span></span></div></div><div class="image"><img src="" alt="" title="" class="bg" /><img src="" alt="" title="" class="fg" /><div id="flashTrailer"></div></div></div></td>

	</tr></table>';

	$REL_CACHE->set('block-indextorrents','kinopoisk',$content);

}

?