<?php
/**
 * Privileges administration panel
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require "include/bittorrent.php";
INIT();
loggedinorreturn();


get_privilege('access_to_privadmincp');

httpauth();

$a = (string)$_GET['a'];

$reserved_privileges = array('is_guest');

if ($_SERVER['REQUEST_METHOD']=='POST') {
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
}

$REL_TPL->stdhead($REL_LANG->_('Privileges control panel'));

if ($a=='add'||$a=='edit') {
		$name = trim((string)($_POST['name']?$_POST['name']:$_GET['name']));
		if ($a=='edit') {
			$p = $REL_DB->query_row("SELECT * FROM privileges WHERE name=".sqlesc($name));
			if (!$p) {
				$REL_TPL->stdmsg($REL_LANG->_('Error'),$REL_LANG->_('No privilege named "%s"',htmlspecialchars($name)),'error');
				$REL_TPL->stdfoot();
				die();
			}
			$REL_TPL->assign('p',$p);
		}
		$REL_TPL->assign('a',$a);
	$REL_TPL->output('edit');
} 
else {

		$privs = $REL_DB->query_return("SELECT * FROM privileges ORDER BY id DESC");
		foreach ($privs AS $priv) {
			$p[$priv['id']] = array('name'=>$priv['name'],'classes'=>$priv['classes_allowed'],'descr'=>$priv['description']);
		}
	$REL_TPL->assign('p',$p);
	$REL_TPL->output();	
}
$REL_TPL->stdfoot();