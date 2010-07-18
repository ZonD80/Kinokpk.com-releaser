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
	public function __constuct($conf=NULL) {
		//	if (!$conf) return;
	}
	public function make_link() {
		global $REL_CONFIG;
		$linkar = func_get_args();
		$dest = $linkar[0];
		$script = $dest;
		unset($linkar[0]);
		if ($linkar) {
		/*	if ($script=='browse') { unset($dest); }
			elseif ($script=='details') {
				$dest='';
			}
						elseif ($script=='torrent_info') $dest = 'trackers/';
			elseif ($script=='exportrelease') $dest = 'export/';
			elseif (($script<>'download')&&($script<>'details'))*/
			$dest .= '.php?';
			//else $dest.='/';
			foreach ($linkar as $place => $param) {
				if ($place % 2 == 0) continue;
				//if (isset($linkar[$place+1])) {
			/*	if ($script=='browse') {
						if ($param=='cat') {
							$cats = $this->assoc_cats();
							$dest.="{$cats[$linkar[$place+1]]}/";
						}
						elseif ($param=='relgroup') {
							$dest.="by_relgroup/{$linkar[$place+1]}/";
						}
						elseif ($param=='nofile') {
							$dest.='releases/nofile';
						}
						elseif ($param=='dead') {
							$dest.="releases/dead";
						}
						elseif ($param=='unchecked') {
							$dest.='releases/unchecked';
						}
					}
					elseif (($script<>'download')&&($script<>'details')&&($script<>'torrent_info')&&($script<>'exportrelease'))
					*/$destar[] = "$param={$linkar[$place+1]}";
					/*else {
						if ($param=='catname') $dest.="{$linkar[$place+1]}/";
						elseif ($param=='id') $dest.="{$linkar[$place+1]}/";
						elseif ($param=='name') $dest.="{$linkar[$place+1]}/";
						elseif ($param=='dllist') $dest.="statistics-local/";
						elseif ($param=='info') $dest.="information/";
					}*/
				//}
			}
			//if (($script<>'download')&&($script<>'details')&&($script<>'browse')&&($script<>'torrent_info')&&($script<>'exportrelease') && $destar)
			$dest .= implode("&",$destar);
		}
		/*elseif ($script=='login') $dest = 'login';
		elseif ($script=='recover') $dest = 'lostpassword';
		elseif ($script=='logout') $dest = 'byebye';
		elseif ($script=='signup') $dest = 'welcome';
		elseif ($script=='browse') $dest = 'releases';
		elseif ($script=='rules')  $dest = 'pagedetails.php?id=32';
		elseif ($script=='providers') $dest = 'pagedetails.php?id=33';
		elseif ($script=='press') $dest = 'pagedetails.php?id=47';
		elseif ($script=='privacy') $dest = 'pagedetails.php?id=49';
		elseif ($script=='faq') $dest = 'pagedetails.php?id=48';*/
		else $dest .='.php?';
		return $REL_CONFIG['defaultbaseurl'].'/'.addslashes($dest);
	}

	/**
	 * Associates categories with its ids
	 * @param string $type Table to take categories
	 * @return array Array of categories, keys are ids, values are categories' names
	 */
	public function assoc_cats($type='categories') {
		global $REL_CACHE;
		$cats = $REL_CACHE->get('trees','cat_seoassoc_'.$type);
		if ($cats===false) {
			$cats=array();
			$catsrow = sql_query("SELECT id,name,seo_name FROM $type ORDER BY sort ASC");
			while ($catres= mysql_fetch_assoc($catsrow)) $cats[$catres['id']]=($catres['seo_name']?$catres['seo_name']:translit($catres['name']));
			$REL_CACHE->set('trees','cat_seoassoc_'.$type,$cats);
		}
		return $cats;
	}

	public function get_cat_id_by_seoname($search,$type='categories') {
		global $REL_CACHE;
		$cats = $this->assoc_cats();
		return array_search(trim((string)$search),$cats);
	}
}
?>