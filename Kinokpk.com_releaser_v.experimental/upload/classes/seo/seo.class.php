<?php
/**
 * Class to generate seo links and other stuff
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

class REL_SEO {
	private $SR, $SO, $SU;
	function __construct() {
		global  $REL_CACHE, $REL_DB;
		$cache = $REL_CACHE->get('system','seorules');

		if ($cache===false) {
			$cache = array();
			$res = $REL_DB->query("SELECT script,parameter,repl,sort,unset_params FROM seorules WHERE enabled=1 ORDER BY script,parameter DESC, sort ASC");
			while ($row = mysql_fetch_assoc($res)) {
				$cache[] = $row;
			}
			$REL_CACHE->set('system','seorules',$cache);
		}
		if ($cache) {
			foreach ($cache as $row) {
				$this->SR[$row['script']][$row['parameter']] = $row['repl'];
				$this->SO[$row['script']][$row['parameter']] = $row['sort'];
				$this->SU[$row['script']][$row['parameter']] = explode(',', $row['unset_params']);
			}
		}
	}

	/**
	 * Links constructor based on seo rules from seoadmin.php. Recevies multiple params, first - is script, next coming pairs is parameter and value. E.g. make_link('browse','cat',4);, or an array of params, e.g. make_link(array('browse','cat',4)), like pager();
	 * @see pager();
	 * @return string
	 */
	public function make_link() {
		global  $REL_CONFIG, $REL_DB;

		$linkar = func_get_args();
		if (is_array($linkar[0]))
		$linkar = $linkar[0];

		$script = $linkar[0];

		if (isset($this->SR[$script]['{base}'])) $dest = $this->SR[$script]['{base}'];
		else $dest = "$script.php";
		unset($linkar[0]);
		if ($linkar) {

			foreach ($linkar as $place => $param) {
				if ($place % 2 == 0) continue;
				if ($this->SR[$script][$param]) {
					$destar[$this->SO[$script][$param]][] = sprintf($this->SR[$script][$param],$linkar[$place+1]);
					if ($this->SU[$script][$param]) {
						foreach ($this->SU[$script][$param] as $to_unset) unset($destar[$this->SO[$script][$to_unset]]);
					}
				} else {
					$destar2[$param][] = "$param={$linkar[$place+1]}";
				}
			}
		}

		if ($destar) {
			ksort($destar);
			foreach ($destar as $dkey=>$dval) {
				if ($dval)
				$destar[$dkey] = implode('&',$dval);
			}
			$dest .= addslashes(implode('',$destar));
		}
		if ($destar2) {
			foreach ($destar2 as $dkey=>$dval) {
				if ($dval)
				$destar2[$dkey] = implode('&',$dval);
			}
			$dest .= '?'.addslashes(implode('&',$destar2));
		}
		return $REL_CONFIG['defaultbaseurl'].'/'.addslashes($dest);
	}

}
?>