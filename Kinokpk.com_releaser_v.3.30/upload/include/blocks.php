<?php
/**
 * Blocks functions
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

if (! defined ( 'IN_TRACKER' ))
die ( 'Direct access to this file not allowed' );

/**
 * renders spoilers
 * @param string $text block text(content) to be processed
 * @return void
 */
function render_content($text) {
	while (preg_match("#\[spoiler\](.*?)\[/spoiler\]#si", $text)) $text = encode_spoiler($text);
	while (preg_match("#\[spoiler=(.+?)\](.*?)\[/spoiler\]#si", $text)) $text = encode_spoiler_from($text);
	return $text;
}

/**
 * Shows blocks on selected position
 * @param string $position blocks position
 * @return void
 */
function show_blocks($position) {
	global $CURUSER, $REL_CONFIG, $REL_CACHE, $REL_TPL, $REL_LANG, $orbital_blocks;

	if (!$REL_CONFIG['use_blocks']) return '';

	if (!$orbital_blocks) {
		$orbital_blocks = $REL_CACHE->get('blocks', 'query');
		if($orbital_blocks===false){
			$orbital_blocks = array();
			$blocks_res = sql_query("SELECT * FROM orbital_blocks WHERE active = 1 ORDER BY weight ASC") or sqlerr(__FILE__,__LINE__);
			while ($blocks_row = mysql_fetch_array($blocks_res))
			$orbital_blocks[] = $blocks_row;
			$REL_CACHE->set('blocks', 'query', $orbital_blocks);
		}
	}

	//$blocks = sql_query("SELECT * FROM orbital_blocks WHERE bposition = ".sqlesc($position)." AND active = 1 ORDER BY weight ASC") or sqlerr(__FILE__,__LINE__);
	foreach ( $orbital_blocks as $block ) {
		$bid = $block ["bid"];
		$title = $block ["title"];
		$content = $block ["content"];
		$blockfile = $block ["blockfile"];
		$bposition = $block ["bposition"];
		$blocktitle = $block['title'];
		if ($position != $bposition)
		continue;
		if ($block['view'] && !in_array(get_user_class(),explode(',',$block['view'])))
		continue;
		if ($block['expire']&&time()>$block['expire']) continue;
		if ($block ["which"]) {
			$which = explode ( ",", $block ["which"] );
			$module_name = str_replace ( ".php", "", basename ( $_SERVER ["PHP_SELF"] ) );
			if (!in_array($module_name,$which)) continue;
		}

		$blockid = explode ( ".", $_COOKIE ["hidebid"] );
		$bidpos = in_array ( "b" . $bid, $blockid );
		if ($bidpos) {
			$contenta = "<div id=\"bb" . $bid . "\" style=\"display: none;\">";
		} else {
			$contenta = "<div id=\"bb" . $bid . "\" style=\"display: block;\">";
		}

		if ($blockfile != "") {
			if (file_exists ( ROOT_PATH."blocks/" . $blockfile . "" )) {
				define ( 'BLOCK_FILE', true );
				@require (ROOT_PATH."blocks/" . $blockfile . "");
				$blocktitlea .= $blocktitle;
				$contenta .= $content;
			} else {
				$contenta = "<center>{$REL_LANG->_("No file/content for this block")}</center>";
			}
		} else	$contenta .= render_content($content);
		if (!$blocktitlea) $blocktitlea .= $blocktitle;

		if ($bidpos) {
			$blocktitlea .= "&nbsp;<span style=\"cursor: pointer;\" onclick=\"javascript: showshides('b" . $bid . "');\"><img border=\"0\" src=\"pic/plus.gif\" id=\"picb" . $bid . "\" title=\"{$REL_LANG->_("Show")}\"/></span>";
		} else {
			$blocktitlea .= "&nbsp;<span style=\"cursor: pointer;\" onclick=\"javascript: showshides('b" . $bid . "');\"><img border=\"0\" src=\"pic/minus.gif\" id=\"picb" . $bid . "\" title=\"{$REL_LANG->_("Hide")}\"/></span>";
		}

		if (!((isset ($content) AND !empty ($content)))) {
			$contenta = "<center>{$REL_LANG->_("No file/content for this block")}</center>";
		}
		$contenta = $contenta.'</div>';

		$REL_TPL->assignByRef('title',$blocktitlea);
		$REL_TPL->assignByRef('content',$contenta);
		if ($block['custom_tpl']) $REL_TPL->display($block['custom_tpl'].'.tpl');
		else $REL_TPL->display('block-'.$bposition.'.tpl');

		unset($blocktitlea); unset($contenta);
	}
	return;
}
?>