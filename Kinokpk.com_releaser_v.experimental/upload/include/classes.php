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

/**
 * Initializes class array
 * @return array Class array
 */
function init_class_array() {
	global $REL_CACHE,$REL_DB;
	$classes = $REL_CACHE->get('system', 'classes');
	if ($classes===false) {
		$classes = $REL_DB->query_assoc("SELECT * FROM classes ORDER BY prior DESC");
		foreach ($classes AS $class) {
			$to_cache[$class['id']] = array('name'=>$class['name'],'priority'=>$class['prior'],'style'=>$class['style']);
			if ($class['remark']) {
				$remark = explode(',', $class['remark']);
				foreach ($remark as $r)
				$to_cache[$r] = $class['id'];
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
 * @param boolean $max Maximize select by current user class
 * @return string HTML for form
 */
function make_classes_checkbox($name,$selected='',$max=false) {
	global $REL_LANG;
	if ($max) $max = get_class_priority($max);
	$selected = explode(',',$selected);
	$classes = init_class_array();
	foreach ($classes AS $id=>$class) {
		if ($max&&$class['priority']>=$max) continue;
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
function make_classes_select($name='class',$selected=NULL,$max=false) {
	global $REL_LANG;
	if ($max)  $max = get_class_priority($max);
	$return .=("<select name=\"class\">\n");
	$return .=("<option value=\"-\">({$REL_LANG->_('All levels')})</option>\n");
	$classes = init_class_array();
	foreach ($classes AS $id=>$class) {
		if ($max&&$class['priority']>=$max) continue;
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
	return in_array($class, init_class_array());
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
