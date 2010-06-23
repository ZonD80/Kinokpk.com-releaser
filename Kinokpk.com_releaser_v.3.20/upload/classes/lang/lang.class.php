<?php
/**
 * Language class
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */
class REL_LANG {

	/**
	 * Array of languages
	 * @var array
	 */
	private $lang;
	/**
	 * Current language setting
	 * @var string
	 */
	private $language;
	/**
	 * Array of already parsed language files
	 * @var array
	 */
	private $parsed_langs;
	
	/**
	 * Debug mode
	 * @var boolean
	 */
	private $DEBUG;
	
	
	
	/**
	 * Class constructor, loads main language file
	 * @param string $language Language to use
	 * @return boolean True
	 */
	function __construct($REL_CONFIG) {
		if ($REL_CONFIG['debug_language']) $this->DEBUG=true; else $this->DEBUG=false;
		$this->lang[$REL_CONFIG['lang']]=array();
		$this->parsed_langs[$REL_CONFIG['lang']]=array();
		$this->language = $REL_CONFIG['lang'];
		$this->load('main');
		return true;
	}
	
	/**
	 * Loads selected language file
	 * @param string $option What file to load
	 */
	public function load($option='main') {
	$this->parse_langfile($option,$this->language);
	}
	/**
	 * Say something by key
	 * @param string $value Key to use
	 * @param string $language Language to use
	 * @return string String of a language file
	 */
	public function say_by_key($value,$language = '') {
		$return = '';
		if (!$language) $language=$this->language;
		if (!array_key_exists($value,$this->lang[$language])) $return .= ($this->DEBUG?"*NO_KEY:{$language}* ":''); 
		if (!$this->lang[$language][$value]) { $return .= ($this->DEBUG?"*NO_VALUE:$value* ":'');
		return $return.$this->lang['en'][$value];
		}
		return $return.$this->lang[$language][$value];
		
	}
	
	/**
	 * Parses language file into associative array
	 * @param string $file File to be userd
	 * @param string $language Language to be used
	 */
	private function parse_langfile($file,$language='en') {
		global $REL_SEO;
		if (@in_array($file,$this->parsed_langs[$language])) return;
		if ($language<>'en') $this->parse_langfile($file,'en');
		$parse = @file(ROOT_PATH."languages/$language/$file.lang",FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
		if (!$parse) die ("FATAL ERROR: no language ($language) for option $file. <a href=\"".$REL_SEO->make_link('setlang','l','en')."\">Switch to English (default)</a>.");
		foreach ($parse as $string) {
			$cut = strpos($string,'=');
			if (!$cut) continue;
			$key = strtolower(trim(substr($string,0,$cut)));
			$value = trim(substr($string,$cut+1,strlen($string)));
			if ($this->lang[$language][$key]) $value = ($this->DEBUG?"*REDECLARATED_KEY:$key* ":'').$value;
			$this->lang[$language][$key] = $value;
		}
		$this->parsed_langs[$language][] = $file;
	}
	
	/**
	 * Formates string to output, like print or spritnf. Many argumets allowed
	 * @return string|string Formatted string
	 */
	public function _() {
		$args = func_get_args();
		$text = $args[0];
		$return = '';
		$key = array_search($text,$this->lang['en']);
		if (!$key) $return .= ($this->DEBUG?"*NO_KEY_OR_NO_TRANSLATION* ":''); 
		else
		$text = $this->say_by_key($key);
		
		if (count($args)>1) { $args[0] = $text;
		return $return.call_user_func_array("sprintf",$args);
		}
		else return $return.$text;

	}	
	
	/**
	 * Formates string to output, like print or spritnf. Many argumets allowed
	 * This function used to say something to specified userid, that comes first argument
	 * @example _to(11,'this is a test');
	 * @return string|string Formatted string
	 */
	public function _to() {
		/*global $REL_DATABASE;
		if (!$REL_DATABASE) die("FATAL ERROR: No database");*/
		
		$args = func_get_args();
		$langauge = @mysql_result(sql_query("SELECT language FROM users WHERE id={$args[0]}"),0);
		$text = $args[1];
		$return = '';
		$key = array_search($text,$this->lang['en']);
		if (!$key) $return .= ($this->DEBUG?"*NO_KEY_OR_NO_TRANSLATION* ":''); 
		else
		$text = $this->say_by_key($key,$language);
		
		if (count($args)>2) { $args[1] = $text; unset($args[0]);
		return $return.call_user_func_array("sprintf",$args);
		}
		else return $return.$text;

	}	
}
?>