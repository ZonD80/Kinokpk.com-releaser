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
		$return.='<input class="role_'.$r.'" type="checkbox" name="'.$name.'"'.(in_array($r, $currole)?' checked="checked"':'').' value="'.$r.'"> '.$r.'&nbsp;';
	}
	return $return;
}

if ($_SERVER['REQUEST_METHOD']=='POST') {
	$id = (int)($_POST['id']?$_POST['id']:$_GET['id']);
	if ($a=='add') {
		$to_set = (array)$_POST['c'];

		$keys = array('name','style','prior','remark');
		foreach ($keys as $key) {
		$val = $to_set[$key];
		//var_Dump($val);
				if ($key=='remark') {
					$check = (array)$val;
					if ($check) {
					foreach ($check as $chk=>$chv) $check[$chk] = "FIND_IN_SET(".sqlesc($chv).",remark)";
					$count = get_row_count("classes","WHERE ".implode(' OR ',$check));
					if ($count) $REL_TPL->stderr($REL_LANG->_('Error'),$REL_LANG->_('Error. Selected roles already associated with another classes. Please <a href="javascript:history.go(-1);">try again</a> and select another roles'));
					}
					$val = implode(',',(array)$val);
				}
			$query[] = sqlesc($val);
		}
		if ($query) {
		$REL_DB->query('INSERT INTO classes ('.implode(',',$keys).') VALUES ('.implode(', ', $query).")");
		if (mysql_errno()==1062) $REL_TPL->stderr($REL_LANG->_('Error'),$REL_LANG->_('Class with this priority already exists. Please <a href="javascript:history.go(-1);">try again</a>'));
		$REL_CACHE->clearCache('system','classes');
		safe_redirect($REL_SEO->make_link('classadmin'),1);
		$REL_TPL->stderr($REL_LANG->_('Successfully'),$REL_LANG->_('Class added'),'success');
		} else $REL_TPL->stderr($REL_LANG->_('Error'),$REL_LANG->_('Missing form data'));
	}
	elseif ($a=='del') {
		$cl = (int)$_POST['cl'];
		$count = get_row_count("classes");
		if ($count==1) $REL_TPL->stderr($REL_LANG->_('Error'),$REL_LANG->_('You can not delete last class'));
		$REL_DB->query("DELETE FROM classes WHERE id=$id");
		$REL_DB->query("UPDATE users SET class = $cl WHERE class = $id");
		$REL_CACHE->clearCache('system','classes');
		safe_redirect($REL_SEO->make_link('classadmin'),1);
		$REL_TPL->stderr($REL_LANG->_('Successfully'),$REL_LANG->_('Class deleted'),'success');
	}
	elseif ($a=='edit') {
		
		$to_set = (array)$_POST['c'];

		$keys = array('name','style','prior','remark');
		foreach ($keys as $key) {
		$val = $to_set[$key];
		//var_Dump($val);
				if ($key=='remark') {
					$check = (array)$val;
					if ($check) {
					foreach ($check as $chk=>$chv) $check[$chk] = "FIND_IN_SET(".sqlesc($chv).",remark)";
					$count = get_row_count("classes","WHERE (".implode(' OR ',$check).") AND id<>$id");
					if ($count) $REL_TPL->stderr($REL_LANG->_('Error'),$REL_LANG->_('Error. Selected roles already associated with another classes. Please <a href="javascript:history.go(-1);">try again</a> and select another roles'));
					}
					$val = implode(',',(array)$val);
				}
			$query[] = "$key = ".sqlesc($val);
		}
		if ($query) {
		$REL_DB->query('UPDATE classes SET '.implode(', ', $query)." WHERE id=$id");
		if (mysql_errno()==1062) $REL_TPL->stderr($REL_LANG->_('Error'),$REL_LANG->_('Class with this priority already exists. Please <a href="javascript:history.go(-1);">try again</a>'));
		$REL_CACHE->clearCache('system','classes');
		safe_redirect($REL_SEO->make_link('classadmin'),1);
		$REL_TPL->stderr($REL_LANG->_('Successfully'),$REL_LANG->_('Class updated'),'success');
		} else $REL_TPL->stderr($REL_LANG->_('Error'),$REL_LANG->_('Missing form data'));
	}
	elseif ($a=='reorder') {
		$classes = init_class_array();
		$pr_set = (array)$_POST['prior'];
		$ro_set = (array)$_POST['role'];
		$pr_set = array_map('intval',$pr_set);
		$vals = array();
		foreach ($pr_set as $key=>$val) {
			$key = (int)$key;
			$check = (array)$ro_set[$key];
			$roq = implode(',',$check);
			
							if ($check) {
					foreach ($check as $chk=>$chv) $check[$chk] = "FIND_IN_SET(".sqlesc($chv).",remark)";
					$count = get_row_count("classes","WHERE (".implode(' OR ',$check).") AND id<>$key");
					if ($count) $REL_TPL->stderr($REL_LANG->_('Error'),$REL_LANG->_('Error. Selected roles for class "%s" are alredy associated with another classes. Please <a href="javascript:history.go(-1);">try again</a> and select another roles',$REL_LANG->_($classes[$key]['name'])));
					}
			if (in_array($val,$vals)){ $prerror=$key; break; }
			$vals[] = $val;
			$query[] = "UPDATE classes SET prior=$val, remark=".sqlesc($roq)." WHERE id=$key";
		}
		if ($prerror) $REL_TPL->stderr($REL_LANG->_('Error'),$REL_LANG->_('Prioity for class "%s" is not unique',$REL_LANG->_($classes[$prerror]['name'])));
		
		if ($query) foreach ($query as $q) $REL_DB->query($q);
		$REL_CACHE->clearCache('system','classes');
		safe_redirect($REL_SEO->make_link('classadmin'),1);
		$REL_TPL->stderr($REL_LANG->_('Successfully'),$REL_LANG->_('Priority and role changes saved'),'success');
	}
}

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
elseif ($a=='del') {
	$id = (int)$_GET['id'];
	$classes = $REL_DB->query_return("SELECT id,name FROM classes WHERE id<>$id ORDER BY prior DESC");
	$REL_TPL->assign('c',$classes);
	$REL_TPL->assign('id',$id);
	$REL_TPL->output($a);
	$REL_TPL->stdfoot();
	die();
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