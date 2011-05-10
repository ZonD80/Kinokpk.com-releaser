<?php
/**
 * All staff for class manipulation
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

if (!defined("IN_TRACKER") && !defined("IN_ANNOUNCE")) die('Direct access to this file not allowed.');

define ("UC_GUEST", -1);
define ("UC_USER", 1);
define ("UC_POWER_USER", 2);
define ("UC_VIP", 3);
define ("UC_UPLOADER", 4);
define ("UC_FREANDS", 5);
define ("UC_SECURITY", 6);
define ("UC_DJ", 7);
define ("UC_MODERATOR", 8);
define ("UC_EVOLUTION", 9);
define ("UC_RADMINISTRATOR", 10);
define ("UC_ADMINISTRATOR", 11);
define ("UC_SYSOP", 12);
define ("UC_TEHNIK", 13);
define ("UC_CREATOR", 14);


/**
 * Initializes class array
 * @return array Class array
 */
function init_class_array() {
	global $REL_CACHE,$REL_DB;
	$classes = $REL_CACHE->get('system', 'classes');
	if ($classes===false) {
		$classes = $REL_DB->query_assoc("SELECT * FROM classes");
		foreach ($classes AS $class) {
			$to_cache[$class['id']] = array('name'=>$class['name'],'priority'=>$class['prior'],'style'=>$class['style']);
			if ($class['remark']) {
				$to_cache[$class['remark']] = $class['id'];
			}
		}
		
		$REL_CACHE->set('system','classes',$to_cache);
		$classes = $to_cache;
	}
	return $classes;
}

/**
 * Returns user class priority
 * @param int $class User class or nothing to return current user class priority
 * @return int User class priority
 */
function get_class_priority($class=false) {
	global $CURUSER;
	if (!$class && $CURUSER) {
		return get_class_priority($CURUSER['class']);
	}
	elseif (!$class && !$CURUSER) return -1;
	else {
			$classes = init_class_array();
		return (int)$classes[$class]['priority'];
	}
}

/**
 * Returns user class
 * @return int User class id
 */
function get_user_class() {
	global $CURUSER;
	$classes = init_class_array();
	if ($CURUSER && $classes[$CURUSER['class']]) return $CURUSER['class'];
	else return $classes['guest'];
}


/**
 * Returns username with a color by user class
 * @param int $class id of user class
 * @param string $username username to be colored
 * @return string Colored username
 */
function get_user_class_color($class, $username)
{
	global $REL_LANG;
	
	if($username=='vem882') return "<span><font color='#00CED1' alt=\"\" title=\"Vem882\">Vem882</font><img src=\"pic/smilies/17.gif\" border=\"0\"></img></span>";
	if($username=='ihtiandris') return "<span><font color='#FFFF00' alt=\"\" title=\"Администратор радио\">ihtiandris</font><img src=\"pic/name/ihtiandris.gif\" border=\"0\"></img></span>";
	if($username=='') return "<span><font color='#FFFFFF' alt=\"\" title=\"Деректор\">liter</font><img src=\"pic/name/hotabovic.gif\" border=\"0\"></img></span>";
	if($username=='DJ_Kиска') return "<span><font color='#0000CD' alt=\"\" title=\"Деректор радио\">DJ_Kиска</font><img src=\"pic/name/dj.gif\" border=\"0\"></img></span>";
	if($username=='СлАдУсИк') return "<span><font color='#FF00FF' alt=\"\" title=\"Ди-джей\">СлАдУсИк</font><img src=\"pic/name/dj.gif\" border=\"0\"></img></span>";
	if($username=='Ruslan') return "<span><font color='#0000CC' alt=\"\" title=\"DJ Best\">RuS</font><font color='black'><marquee behavior='alternate' HEIGHT=30 WIDTH=30  direction='left'>LAN</font></span></marquee>";
	if($username=='') return "<span><font color='#FFD700' alt=\"\" title=\"DJ ihtiandris\">ihti</font><font color='black'><marquee behavior='alternate' HEIGHT=30 WIDTH=30  direction='left'>andris</font></span></marquee>";
	if($username=='СкОрПиОн') return "<span><font color='#1E90FF' alt=\"\" title=\"СкОрПиОн\">СкОрПиОн</font><img src=\"pic/name/zholushka.gif\" width=\"25\" height=\"25\" border=\"0\"></img></span>";		
	if($username=='Prizrak') return "<span><font color='#FFFFFF' alt=\"\" title=\"Деректор\">Prizrak</font><img src=\"pic/name/prizrak.gif\" width=\"20\" height=\"20\" border=\"0\"></img></span>";	
	if($username=='НеЖнаЯ') return "<span><font color='#DC22C6' alt=\"\" title=\"НеЖнаЯ\">НеЖнаЯ</font><img src=\"pic/name/nezhnaja.gif\" border=\"0\"></img></span>";
	if($username=='KMM') return "<span><font color='green' alt=\"\" title=\"KMM\">K</font><font color='blue'>M</font><font color='red'>M</img></font></span>";
	if($username=='Lighman') return "<span><font color='#000066' alt=\"\" title=\"TEHNIK\">Lighman</font><img src=\"pic/name/Lighman.gif\" width=\"20\" height=\"20\" border=\"0\"></img></span>";	
	
		switch ($class)
	{
	
		case UC_TEHNIK:
			return "<span style=\"color:#808000\" title=\"".$REL_LANG->_('TEHNIK')."\">" . $username . "</span>";
			break;
		case UC_SITEF:
			return "<span style=\"color:#808000\" title=\"".$REL_LANG->_('SITEF')."\">" . $username . "</span>";
			break;
		case UC_EVOLUTION:
			return "<span style=\"color:#808000\" title=\"".$REL_LANG->_('Evolution')."\">" . $username . "</span>";
			break;
		case UC_SECURITY:
			return "<span style=\"color:#D2691E\" title=\"".$REL_LANG->_('Security')."\">" . $username . "</span>";
			break;
		case UC_RADMINISTRATOR:
			return "<span style=\"color:#551A8B\" title=\"".$REL_LANG->_('RADMINISTRATOR')."\">" . $username . "</span>";
			break;
		case UC_CREATOR:
			return "<span style=\"color:#1E90FF\" title=\"".$REL_LANG->_('Creator')."\">" . $username . "</span>";
			break;
		case UC_DJ:
			return "<span style=\"color:#00CD00\" title=\"".$REL_LANG->_('Dj')."\">" . $username . "</span><img src=\"pic/name/dj.gif\" border=\"0\"></img>";
			break;
		case UC_SYSOP:
			return "<span style=\"color:#0000FF\" title=\"".$REL_LANG->_('Sysop')."\">" . $username . "</span><img src=\"pic/name/sysop.gif\" border=\"0\"></img>";
			break;
		case UC_ADMINISTRATOR:
			return "<span style=\"color:#191970\" title=\"".$REL_LANG->_('Administrator')."\">" . $username . "</span><img src=\"pic/name/administrator.gif\" border=\"0\"></img>";
			break;
		case UC_MODERATOR:
			return "<span style=\"color:red\" title=\"".$REL_LANG->_('Moderator')."\">" . $username . "</span><img src=\"pic/name/moderator.gif\" border=\"0\"></img>";
			break;
		case UC_UPLOADER:
			return "<span style=\"color:orange\" title=\"".$REL_LANG->_('Uploader')."\">" . $username . "</span><img src=\"pic/name/upplouder.gif\" border=\"0\"></img>";
			break;
		case UC_VIP:
			return "<span style=\"color:#228B22\" title=\"".$REL_LANG->_('Vip')."\">" . $username . "</span><img src=\"pic/name/vip.gif\" border=\"0\"></img>";
			break;
		case UC_FREANDS:
			return "<span style=\"color:#8B008B\" title=\"".$REL_LANG->_('Freands')."\">" . $username . "</span><img src=\"pic/name/power.gif\" border=\"0\"></img>";
			break;
		case UC_POWER_USER:
			return "<span style=\"color:#C71585\" title=\"".$REL_LANG->_('Power_user')."\">" . $username . "</span><img src=\"pic/name/power.gif\" border=\"0\"></img>";
			break;
		case UC_USER:
			return "<span style=\"color:#000000\" title=\"".$REL_LANG->_('User')."\">" . $username . "</span><img src=\"pic/name/user.gif\" border=\"0\"></img>";
		case UC_GUEST:
			return "<i>{$REL_LANG->_('Guest')}</i>";
			break;

	}
	
	 global $REL_LANG;
 $classes = init_class_array();
 $cl = $classes[$class];
 if ($cl['style']) {
  $return = str_replace("{clname}", $REL_LANG->_($cl['name']), $cl['style']);
  $return = str_replace("{uname}", $username, $return);
 } else {
  $return = $username;
 }
 return $return;
}

/**
 * Generates class input HTML checkboxes
 * @param string $name Name of generated input
 * @param string $selected Id of selected classses separated by comma.
 * @return string HTML for form
 */
function make_classes_checkbox($name,$selected='') {
	global $REL_LANG;
	$selected = explode(',',$selected);
	$classes = init_class_array();
	foreach ($classes AS $id=>$class) {
		if (is_int($id))
		$return.="<input type=\"checkbox\" name=\"{$name}[]\" value=\"{$id}\"".(in_array($id, $selected)?' checked':'')."> {$REL_LANG->_($class['name'])}<br/>";
	}
	return $return;
}

/**
 * Generates class select HTML
 * @param string $name Name of select
 * @param id $selected ID of selected class
 * @return string HTML for select
 */
function make_classes_select($name='class',$selected=NULL) {
	global $REL_LANG;
	$return .=("<select name=\"class\">\n");
$return .=("<option value=\"-\">({$REL_LANG->_('All levels')})</option>\n");
	$classes = init_class_array();
	foreach ($classes AS $id=>$class) {
		if (is_int($id))
		$return.="<option value=\"{$id}\"".($selected==$id?' selected':'').">{$REL_LANG->_($class['name'])}</option>";
	}
$return .=("</select>\n");
return $return;
}
/**
 * Returns user class name form lang array
 * @param int $class class id
 * @return string class name
 */
function get_user_class_name($class) {
	global $REL_LANG;
		$classes = init_class_array();
	$cl = $classes[$class];
	if (!$cl) return $REL_LANG->_('ERROR:No class with id %s',$class);
	return $REL_LANG->_($cl['name']);
}

/**
 * Checks that class id is valid
 * @param int $class ID of a class to verify
 * @return boolean True or False
 */
function is_valid_user_class($class) {
$class = (int)$class;
$classes = init_class_array();
if (!$classes[$class]) return false; else return true;
}
/**
 * Returns true or false, or dies. Function used to get privileges on privilege given by name
 * @param string $name privilege name
 * @param boolean $die Die or not on false, default true
 * @return boolean True or false on $die=false or generating template stderr/stdmsg event.
 */
function get_privilege($name,$die=true) {
	global $REL_LANG,$REL_CACHE, $REL_DB, $REL_TPL, $CURUSER;
	
	$privs = $REL_CACHE->get('system', 'privileges');
	if ($privs===false) {
		$privs = $REL_DB->query_assoc("SELECT * FROM privileges");
		foreach ($privs AS $priv) {
			$to_cache[$priv['name']] = array('classes'=>explode(',', $priv['classes_allowed']),'descr'=>$priv['description']);
		}
		$REL_CACHE->set('system','privileges',$to_cache);
		$privs = $to_cache;
	}
	if (!$CURUSER&&$name=='is_guest') {
		if (!$die) return true; else {
			if (ob_get_length()) {
				$REL_TPL->stdmsg($REL_LANG->_('Access denied, you must to have permission to:'),$REL_LANG->_($privs['is_guest']['descr']));
				$REL_TPL->stdfoot();
				die();
			} else $REL_TPL->stderr($REL_LANG->_('Access denied, you must to have permission to:'),$REL_LANG->_($privs['is_guest']['descr']));
		}
	}
	if (!$privs[$name]['classes']) die("No classes defined for privilege $name");
	if (in_array($CURUSER['class'], $privs[$name]['classes'])) {
		return true;
	} else {
			if (!$die) return false; else {
			if (ob_get_length()) {
				$REL_TPL->stdmsg($REL_LANG->_('Access denied, you must to have permission to:'),$REL_LANG->_($privs[$name]['descr']));
				$REL_TPL->stdfoot();
				die();
			} else $REL_TPL->stderr($REL_LANG->_('Access denied, you must to have permission to:'),$REL_LANG->_($privs[$name]['descr']));
		}
	}
}
?>