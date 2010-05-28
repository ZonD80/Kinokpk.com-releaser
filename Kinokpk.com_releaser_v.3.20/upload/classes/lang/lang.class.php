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
	 * Class constructor, loads main language file
	 * @param string $language Language to use
	 * @return boolean True
	 */
	function __construct($language='en') {
		$this->lang[$language]=array();
		$this->parsed_langs[$language]=array();
		$this->language = $language;
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
	 * @return string String of a language file
	 */
	public function say_by_key($value) {
		$return = '';
		if (!array_key_exists($value,$this->lang[$this->language])) $return .="*NO_KEY:{$this->language}* "; 
		if (!$this->lang[$this->language][$value]) { $return .= "*NO_VALUE:$value* ";
		return $return.$this->lang['en'][$value];
		}
		return $return.$this->lang[$this->language][$value];
		
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
			$key = substr($string,0,$cut);
			$value = substr($string,$cut+1,strlen($string));
			if ($this->lang[$language][$key]) $value = "*REDECLARATED_KEY:$key* $value";
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
		if (!$key) $return .= "*NO_KEY_OR_NO_TRANSLATION* "; 
		else
		$text = $this->say_by_key($key);
		
		if (count($args>1)) { $args[0] = $text;
		return $return.call_user_func_array("sprintf",$args);
		}
		else return $return.$text;

	}	
	
}