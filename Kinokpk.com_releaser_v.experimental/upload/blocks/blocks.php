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
die ( 'Hacking attempt!' );
/**
 * renders block content
 * @param string $side Position of block
 * @param boolean $blockfile is file block?
 * @param string $blocktitle title of block
 * @param string $content contents of block
 * @param int $bid block id
 * @param string $bposition position of block
 * @return NULL|string block content or null on error
 */
function render_blocks($side, $blockfile, $blocktitle, $content, $bid, $bposition) {
	global $showbanners, $ss_uri, $foot, $blockid;
	if (empty ( $blockid )) {
		$blockid = explode ( ".", $_COOKIE ["hidebid"] );
	}
	$bidpos = in_array ( "b" . $bid, $blockid );
	if ($bidpos) {
		$contenta = "<div id=\"bb" . $bid . "\" style=\"display: none;\">";
	} else {
		$contenta = "<div id=\"bb" . $bid . "\" style=\"display: block;\">";
	}

	if ($blockfile != "") {
		if (file_exists ( ROOT_PATH."blocks/" . $blockfile . "" )) {
			define ( 'BLOCK_FILE', true );
			require (ROOT_PATH."blocks/" . $blockfile . "");
			$blocktitlea .= $blocktitle;
			$contenta .= $content;
		} else {
			$contenta = "<center>Существует проблема с этим блоком!</center>";
		}
	} else	$contenta .= render_block($content);
	if (!$blocktitlea) $blocktitlea .= $blocktitle;

	if ($bidpos) {
		$blocktitlea .= "&nbsp;<span style=\"cursor: pointer;\" onclick=\"javascript: showshides('b" . $bid . "');\"><img border=\"0\" src=\"pic/plus.gif\" id=\"picb" . $bid . "\" title=\"Показать\" alt=\"Показать\"/></span>";
	} else {
		$blocktitlea .= "&nbsp;<span style=\"cursor: pointer;\" onclick=\"javascript: showshides('b" . $bid . "');\"><img border=\"0\" src=\"pic/minus.gif\" id=\"picb" . $bid . "\" title=\"Скрыть\" alt=\"Скрыть\"/></span>";
	}

	if (!((isset ($content) AND !empty ($content)))) {
		$contenta = "<center>Существует проблема с этим блоком!</center>";
	}


	switch ($side) {
		case 'b' :
			$showbanners = $contenta;
			return null;

		case 'f' :
			$foot = $contenta;
			return null;

		case 'n' :
			echo $contenta;
			return null;

		case 'p' :
			return $contenta;

		case 'o' :
			return "$blocktitlea - $contenta";
	}
	$contenta .= "</div>";
	//        BeginBlock($blocktitle, $bposition);
	themesidebox ( $blocktitlea, $contenta, $bposition );
	//        EndBlock($bposition);
	return null;
}
/**
 * renders spoilers
 * @param string $text block text(content) to be processed
 * @return void
 */
function render_block($text) {
	while (preg_match("#\[spoiler\](.*?)\[/spoiler\]#si", $text)) $text = encode_spoiler($text);
	while (preg_match("#\[spoiler=(.+?)\](.*?)\[/spoiler\]#si", $text)) $text = encode_spoiler_from($text);
	return $text;
}

/**
 * Prints block using template
 * @param string $title title of block
 * @param string $content content of block
 * @param string $pos block position
 * @return void
 */
function themesidebox($title, $content, $pos) {
	global $blockfile, $b_id, $ss_uri;
	static $bl_mass;
	//$content = str_replace("'", "&#039;", $content);
	$func = 'echo';
	$func2 = '';
	if ($pos == "s" || $pos == "o") {
		if (empty ( $blockfile )) {
			$bl_name = "fly-block-" . $b_id;
		} else {
			$bl_name = "fly-" . str_replace ( ".php", "", $blockfile );
		}
	} else {
		if (empty ( $blockfile )) {
			$bl_name = "block-" . $b_id;
		} else {
			$bl_name = str_replace ( ".php", "", $blockfile );
		}
	}
	if (! isset ( $bl_mass [$bl_name] )) {
		if (file_exists (ROOT_PATH."themes/" . $ss_uri . "/html/" . $bl_name . ".html" )) {
			$bl_mass [$bl_name] ['m'] = true;
		} else {
			$bl_mass [$bl_name] ['m'] = false;
		}
	}
	if ($bl_mass [$bl_name] ['m']) {
		$thefile = addslashes ( file_get_contents (ROOT_PATH."themes/" . $ss_uri . "/html/" . $bl_name . ".html" ) );
		$thefile = "\$r_file=\"" . $thefile . "\";";
		eval ( $thefile );
		if ($pos == "o") {
			return $r_file;
		} else {
			echo $r_file;
		}
	} else {
		switch ($pos) {
			case 'l' :
				$bl_name = "block-left";
				break;
			case 'r' :
				$bl_name = "block-right";
				break;
			case 'c' :
				$bl_name = "block-center";
				break;
			case 'd' :
				$bl_name = "block-down";
				break;
			case 's' :
				$bl_name = "block-fly";
				break;
			case 'o' :
				$func = 'return(';
				$func2 = ')';
				$bl_name = "block-fly";
				break;
			default :
				$bl_name = "block-all";
				break;
		}
		if (! isset ( $bl_mass [$bl_name] )) {
			if (file_exists (ROOT_PATH."themes/" . $ss_uri . "/html/" . $bl_name . ".html" )) {
				$bl_mass [$bl_name] ['m'] = true;
				$f_str = file_get_contents (ROOT_PATH."themes/" . $ss_uri . "/html/" . $bl_name . ".html" );
				$f_str = 'global $ss_uri, $REL_LANG; ' . $func . ' "' . addslashes ( $f_str ) . " \"" . $func2 . ";";
				$bl_mass [$bl_name] ['f'] = create_function ( '$title, $content', $f_str );
			} else {
				$bl_mass [$bl_name] ['m'] = false;
			}
		}
		if ($bl_mass [$bl_name] ['m']) {
			if ($pos == "o") {
				return $bl_mass [$bl_name] ['f'] ( $title, $content );
			} else {
				$bl_mass [$bl_name] ['f'] ( $title, $content );
			}
		} else {
			$bl_name = 'block-all';
			if (! isset ( $bl_mass [$bl_name] )) {
				if (file_exists (ROOT_PATH."themes/" . $ss_uri . "/html/" . $bl_name . ".html" )) {
					$bl_mass [$bl_name] ['m'] = true;
					$f_str = file_get_contents (ROOT_PATH."themes/" . $ss_uri . "/html/" . $bl_name . ".html" );
					$f_str = 'global $ss_uri, $REL_LANG; ' . $func . ' "' . addslashes ( $f_str ) . " \"" . $func2 . ";";
					$bl_mass [$bl_name] ['f'] = create_function ( '$title, $content', $f_str );
				} else {
					$bl_mass [$bl_name] ['m'] = false;
				}
			}
			if ($bl_mass [$bl_name] ['m']) {
				if ($pos == "o") {
					return $bl_mass [$bl_name] ['f'] ( $title, $content );
				} else {
					$bl_mass [$bl_name] ['f'] ( $title, $content );
				}
			} else {
				echo( "<fieldset><legend>" . $title . "</legend>" . $content . "</fieldset>");
			}
		}
	}
	return;
}

$orbital_blocks = array ();

/**
 * Shows blocks on selected position
 * @param string $position blocks position
 * @return void
 */
function show_blocks($position) {
	global $CURUSER, $REL_CONFIG, $already_used, $orbital_blocks, $REL_CACHE;


	if ($REL_CONFIG['use_blocks']) {

		if (!is_array($orbital_blocks)) $orbital_blocks = array();

		if (!$already_used) {

			$orbital_blocks = $REL_CACHE->get('blocks', 'query');
			if($orbital_blocks===false){

				$blocks_res = sql_query("SELECT * FROM orbital_blocks WHERE active = 1 ORDER BY weight ASC") or sqlerr(__FILE__,__LINE__);
				while ($blocks_row = mysql_fetch_array($blocks_res))
				$orbital_blocks[] = $blocks_row;
				$REL_CACHE->set('blocks', 'query', $orbital_blocks);
			}
			$already_used = true;
		}

		//$blocks = sql_query("SELECT * FROM orbital_blocks WHERE bposition = ".sqlesc($position)." AND active = 1 ORDER BY weight ASC") or sqlerr(__FILE__,__LINE__);
		foreach ( $orbital_blocks as $block ) {
			$bid = $block ["bid"];
			$title = $block ["title"];
			$content = $block ["content"];
			$blockfile = $block ["blockfile"];
			$bposition = $block ["bposition"];
			if ($position != $bposition)
			continue;
			$view = $block ["view"];
			$which = explode ( ",", $block ["which"] );
			$module_name = str_replace ( ".php", "", basename ( $_SERVER ["PHP_SELF"] ) );
			
			//if (get_user_class() >= UC_SYSOP) print ('<h1>'.basename ( $_SERVER ["PHP_SELF"] ).'</h1>');
			 print ('<h1>'.basename ( $_SERVER ["PHP_SELF"] ).'</h1>');
			if (! (in_array ( $module_name, $which ) || in_array ( "all", $which ) || (in_array ( "ihome", $which ) && $module_name == "index"))) {
				continue;
			}
			if ($view == 0) {
				render_blocks($side, $blockfile, $title, $content, $bid, $bposition);
			} elseif ($view == 1 && $CURUSER) {
				render_blocks($side, $blockfile, $title, $content, $bid, $bposition);
			} elseif ($view == 2 && (get_user_class() >= UC_MODERATOR)) {
				render_blocks($side, $blockfile, $title, $content, $bid, $bposition);
			} elseif ($view == 3 && (!$CURUSER || get_user_class() >= UC_MODERATOR)) {
				render_blocks($side, $blockfile, $title, $content, $bid, $bposition);
			}
		}
	}
	return;
}
?>