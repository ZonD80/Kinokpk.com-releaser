<?php
/**
 * Classes administration panel
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require "include/bittorrent.php";
INIT();
loggedinorreturn();


get_privilege('access_to_classadmin');

httpauth();

$a = (string)$_GET['a'];

function rolesdesc() {
	global $REL_TPL;
	return $REL_TPL->fetch('modules/classadmin/rolesdesc.tpl');
}

function make_role_checkbox($currole,$name) {
	
	$roles = array('sysop','uploader','staffbegin','vip','rating','reg','guest');
	$currole = explode(',',$currole);
	foreach ($roles as $r) {
		$return.='<input class="role_'.$r.'" type="checkbox" name="'.$name.'"'.(in_array($r, $currole)?' checked="checked"':'').'> '.$r.'&nbsp;';
	}
	return $return;
}

/*if ($_SERVER['REQUEST_METHOD']=='POST') {
	$name = trim((string)($_POST['name']?$_POST['name']:$_GET['name']));
	if ($a=='add') {
		if (!$_POST['classes']) $REL_TPL->stderr($REL_LANG->_('Error'),$REL_LANG->_('Error. Please select at least one class'));
		$t = register_privilege($name, (array)$_POST['classes'], trim((string)$_POST['descr']));
		$REL_CACHE->clearCache('system','privileges');
		if (!REL_AJAX) safe_redirect($REL_SEO->make_link('privadmin'),1);
		$REL_TPL->stderr(($t?$REL_LANG->_('Successful'):$REL_LANG->_('Error')),($t?$REL_LANG->_('Privilege "%s" successfully registered',htmlspecialchars($name)):$REL_LANG->_('Privilege does not registered (it already exists)')),($t?'success':'error'));
	}
	elseif ($a=='del') {
		if (in_array($name,$reserved_privileges)) $REL_TPL->stderr($REL_LANG->_('Error'),$REL_LANG->_('Privilege "%s" can not be deleted. It is system privilege.',htmlspecialchars($name)));
		unregister_privilege($name);
		$REL_CACHE->clearCache('system','privileges');
		if (!REL_AJAX) safe_redirect($REL_SEO->make_link('privadmin'),1);
		$REL_TPL->stderr($REL_LANG->_('Successful'),$REL_LANG->_('Privilege "%s" unregistered',htmlspecialchars($name)),'success');
		
	}
	elseif ($a=='edit') {
		$classes = implode(',',array_map('intval',(array)$_POST['classes']));
		$descr = htmlspecialchars(trim((string)$_POST['descr']));
		if (!$classes) die($REL_LANG->_('Error. Please select at least one class'));
		$REL_DB->query('UPDATE privileges SET classes_allowed='.sqlesc($classes).($descr?', description='.sqlesc($descr):'').' WHERE name='.sqlesc($name));
		$REL_CACHE->clearCache('system','privileges');
		if (REL_AJAX) {
		die($REL_LANG->_('Privilege updated'));
		}
	}
}*/

$REL_TPL->stdhead($REL_LANG->_('Classes control panel'));

if ($a=='add'||$a=='edit') {
		$id = (int)($_POST['id']?$_POST['id']:$_GET['id']);
		if ($a=='edit') {
			$c = $REL_DB->query_row("SELECT * FROM classes WHERE id=$id");
			if (!$c) {
				$REL_TPL->stdmsg($REL_LANG->_('Error'),$REL_LANG->_('Invalid id'));
				$REL_TPL->stdfoot();
				die();
			}
			$REL_TPL->assign('c',$c);
		}
		$REL_TPL->assign('a',$a);
	$REL_TPL->output('edit');
} 
else {

		$classes = $REL_DB->query_return("SELECT * FROM classes ORDER BY prior DESC");
		foreach ($classes AS $class) {
			$c[$class['id']] = $class;
		}
	$REL_TPL->assign('c',$c);
	$REL_TPL->output();	
}
$REL_TPL->stdfoot();

?>