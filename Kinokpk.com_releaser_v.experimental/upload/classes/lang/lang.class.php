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
	 * Debug mode
	 * @var boolean
	 */
	private $DEBUG;

	/**
	 * Already parsed languages for file mode. Used to prevent double parse of languages
	 * @var array
	 */
	private $parsed_langs;


	/**
	 * Class constructor, loads main language file
	 * @param string $REL_CONFIG Configuration to use
	 * @return boolean True
	 */
	function __construct($REL_CONFIG) {
		if ($REL_CONFIG['debug_language']) $this->DEBUG=true; else $this->DEBUG=false;
		$this->lang[$REL_CONFIG['lang']]=array();
		$this->language = $REL_CONFIG['lang'];
		if ($REL_CONFIG['static_language']) {
			$langfiles = explode(',',$REL_CONFIG['static_language']);
			foreach ($langfiles as $langs) {
				$langdata = explode('=',$langs);
				$this->load($langdata[0],$langdata[1]);
			}
		} else {
			$this->load('en');
			if ($this->language<>'en') $this->load($this->language);
		}
		return true;
	}

	/**
	 * Loads selected language file
	 * @param string $option What file to load
	 * @param string $file Load language from this file, if empty, loads from database
	 */
	public function load($language='en',$file='') {
		if (!$file)
		$this->parse_db($language);
		else $this->parse_langfile(ROOT_PATH.$file,$language);
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
	 * Say something by key to specific user with him language-specific settings
	 * @param int $id User id to say to
	 * @param string $value Key to use
	 * @param string $language Language to use
	 * @return string String of a language file
	 */
	public function say_by_key_to($id, $value,$language = '') {
		global $REL_DB;
		$return = '';
		if (!$language) {
			$language = @mysql_result($REL_DB->query("SELECT language FROM users WHERE id={$id}"),0);
			if (!$language) $language=$this->language;
		}
		if (!array_key_exists($value,$this->lang[$language])) $return .= ($this->DEBUG?"*NO_KEY:{$language}* ":'');
		if (!$this->lang[$language][$value]) { $return .= ($this->DEBUG?"*NO_VALUE:$value* ":'');
		return $return.$this->lang['en'][$value];
		}
		return $return.$this->lang[$language][$value];

	}

	/**
	 * Parse language from database (cache) into associative array
	 * @param string $language Language to parse
	 */
	private function parse_db($language='en') {
		global $REL_CACHE,$REL_SEO, $CURUSER;
		if ($this->lang[$language]) return;
		$this->lang[$language] = $REL_CACHE->get('languages',$language);
		if ($this->lang[$language]===false) {
			$res = sql_query("SELECT lkey,lvalue FROM languages WHERE ltranslate='$language'");
			while ($row = mysql_fetch_assoc($res))
			$this->lang[$language][$row['lkey']] = $row['lvalue'];
			if (!$this->lang[$language]) {
				print ("ERROR: no language ($language). <a href=\"".$REL_SEO->make_link('setlang','l','en')."\">Switch to English (default)</a>.".(get_privilege('is_owner',false)?" Or you can <a href=\"".$REL_SEO->make_link('langadmin','import')."\">Import a language file</a>":''));
				$this->lang[$language] = array();
			}
			$REL_CACHE->set('languages',$language,$this->lang[$language]);
		}

	}

	/**
	 * Parses language file into associative array (used only in installer)
	 * @param string $file File to be used (full path to file)
	 * @param string $language Language to be used
	 * @return boolean False on error & prints error message
	 */
	private function parse_langfile($file,$language='en') {
		global $REL_SEO;
		if (@in_array($file,$this->parsed_langs[$language])) return;
		$parse = @file($file,FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
		if (!$parse) {
			print ("FATAL ERROR: no language ($language) for file $file.");
			return false;
		}

		foreach ($parse as $string) {
			$cut = strpos($string,'=');
			if (!$cut) continue;
			$key = substr($string,0,$cut);
			$value = substr($string,$cut+1,mb_strlen($string));
			if ($this->lang[$language][$key]) $value = "*REDECLARATED_KEY:$key* $value";
			$this->lang[$language][$key] = $value;
		}
		$this->parsed_langs[$language][] = $file;
	}


	public function import_langfile($file,$language='en',$override=false) {

		$parse = @file($file,FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
		if (!$parse) return false;
		$res = sql_query("SELECT lkey,lvalue FROM languages WHERE ltranslate='$language'");
		while ($row = mysql_fetch_assoc($res))
		$check[$row['lkey']] = $row['lvalue'];
		foreach ($parse as $string) {
			//$string = iconv('cp1251','utf-8',$string);
			$cut = strpos($string,'=');
			if (!$cut) continue;
			$key = strtolower(trim(substr($string,0,$cut)));
			$value = trim(substr($string,$cut+1,strlen($string)));
			if ($to_database[$key]||$check[$key]&&!$override) { $return['errors'][] = 'REDECLARATED KEY:"'.$key.'"';}
			$to_database[$key] = $value;
		}
		//if ($return['errors']) return $return;

		foreach ($to_database as $key=>$value) {
			sql_query("INSERT INTO languages (lkey,ltranslate,lvalue) VALUES (".sqlesc(makesafe($key)).",'$language',".sqlesc(makesafe($value)).")".($override?" ON DUPLICATE KEY UPDATE lvalue=".sqlesc(makesafe($value)):''));
			if (!mysql_errno()) $return['words'][] = "$key : $value";
		}
		return $return;
	}

	public function export_langfile($lang) {
		global $REL_CONFIG;
		header("Content-type: text/plain");
		header("Content-Disposition: attachment;filename=$lang.lang");
		header("Content-Transfer-Encoding: binary");
		header('Pragma: no-cache');
		header('Expires: 0');
		print "// Kinokpk.com releaser ".RELVERSION." language tools\n";
		if (!$this->lang[$lang]) {
			$this->load($lang);
			if (!$this->lang[$lang])
			die ("ERROR: No lang to export ($lang)");
		}
		foreach ($this->lang[$lang] as $key => $value) {
			print "$key=$value\n";
		}
		die("// langfile ($lang) from {$REL_CONFIG['defaultbaseurl']} created at ".date('d/m/Y H:i:s'));
	}

	/**
	 * Formates string to output, like print or spritnf. Many argumets allowed
	 * @return string|string Formatted string
	 */
	public function _() {
		$args = func_get_args();
		$text = $args[0];
		$return = '';
		if ($this->lang['en'])
		$key = array_search($text,$this->lang['en']);
		else return ("*NO_ENGLISH_LANGUAGE*");
		if (!$key) $return .= ($this->DEBUG?"*NO_KEY_OR_NO_TRANSLATION* ":'');
		else
		$text = $this->say_by_key($key);

		if (count($args)>1) { $args[0] = $text;
		return $return.call_user_func_array("sprintf",$args);
		}
		else return $return.$text;

	}

	/**
	 * Formates string to output, like print or spritnf. Many argumets allowed. FIRST is a language, next is as $this->_
	 * @return string|string Formatted string
	 */
	public function _lang() {
		$args = func_get_args();
		$lang = $args[0];
		$text = $args[1];
		$return = '';
		if ($this->lang['en'])
		$key = array_search($text,$this->lang['en']);
		else return ("*NO_ENGLISH_LANGUAGE*");
		if (!$key) $return .= ($this->DEBUG?"*NO_KEY_OR_NO_TRANSLATION* ":'');
		else
		$text = $this->say_by_key($key,$lang);

		if (count($args)>2) { $args[1] = $text; unset($args[0]);
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
		global $REL_DB;
		$args = func_get_args();
		if ($args[0]==0) $langauge = $this->language; else
		$langauge = @mysql_result($REL_DB->query("SELECT language FROM users WHERE id={$args[0]}"),0);
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