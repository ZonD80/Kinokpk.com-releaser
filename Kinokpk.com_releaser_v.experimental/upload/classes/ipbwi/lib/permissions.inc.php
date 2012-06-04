<?php
	/**
	 * @author			Matthias Reuter ($LastChangedBy: matthias $)
	 * @version			$LastChangedDate: 2008-10-31 15:33:34 +0000 (Fr, 31 Okt 2008) $
	 * @package			permissions
	 * @copyright		2007-2010 IPBWI development team
	 * @link			http://ipbwi.com
	 * @since			2.0
	 * @license			http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
	 */
	class ipbwi_permissions extends ipbwi {
		private $ipbwi			= null;
		/**
		 * @desc			Loads and checks different vars when class is initiating
		 * @author			Matthias Reuter
		 * @since			2.0
		 * @ignore
		 */
		public function __construct($ipbwi){
			// loads common classes
			$this->ipbwi = $ipbwi;
		}
		/**
		 * @desc			Finds out if a user has permission to do something...
		 * @param	string	$perm the permission to be worked out
		 * @param	int		$user the user to have permissions checked. If left blank, currently logged in user used.
		 * @return	bool	true if user has perm, otherwise false
		 * @author			Matthias Reuter
		 * @sample
		 * <code>
		 * $ipbwi->permissions->has('g_access_cp',55);
		 * </code>
		 * @since			2.0
		 */
		public function has($perm,$user=false){
			if(substr($perm,0,2) != "g_"){
				$this->ipbwi->addSystemMessage('Error',$this->ipbwi->getLibLang('badPermID'),'Located in file <strong>'.__FILE__.'</strong> at class <strong>'.__CLASS__.'</strong> in function <strong>'.__FUNCTION__.'</strong> on line #<strong>'.__LINE__.'</strong>');
				return false;
			}
			$perm = preg_replace('#[^a-z_]#','',$perm);
			$info = $this->ipbwi->member->info($user);
			if(!is_array($info)){
				$this->ipbwi->addSystemMessage('Error',$this->ipbwi->getLibLang('badMemID'),'Located in file <strong>'.__FILE__.'</strong> at class <strong>'.__CLASS__.'</strong> in function <strong>'.__FUNCTION__.'</strong> on line #<strong>'.__LINE__.'</strong>');
				return false;
			}
			if($info[$perm]){
				return true;
			// Take a look at secondary groups
			}elseif(isset($info['mgroup_others'])){
				$info['mgroup_others'] = substr($info['mgroup_others'],1,strlen($info['mgroup_others'])-2);
				if($info['mgroup_others'] != ''){
					$this->ipbwi->ips_wrapper->DB->query('SELECT '.$perm.' FROM '.$this->ipbwi->board['sql_tbl_prefix'].'groups WHERE g_id IN('.$info['mgroup_others'].')');
					while($row = $this->ipbwi->ips_wrapper->DB->fetch()){
						if($row[$perm]){
							return true;
						}
					}
				}
			}
			return false;
		}
		/**
		 * @desc			Attempts to sort out the weird permissions array.
		 * @param	string	$permArray the permission array to be sorted
		 * @return	array	sorted permissions
		 * @author			Matthias Reuter
		 * @sample
		 * <code>
		 * $ipbwi->permissions->sort(array('show' => '*','read' => '*','start' => '*','reply' => '*','upload' => '*','download' => '*'));
		 * </code>
		 * @since			2.0
		 */
		public function sort($permArray){
			$perms = unserialize(stripslashes($permArray));
			$fr['read_perms']   = $perms['read_perms'];
			$fr['reply_perms']  = $perms['reply_perms'];
			$fr['start_perms']  = $perms['start_perms'];
			$fr['upload_perms'] = $perms['upload_perms'];
			$fr['show_perms']   = $perms['show_perms'];
			return $fr;
		}
		/**
		 * @desc			Returns the best perms a user has for something...
		 * @param	string	$perm the permission to be worked out
		 * @param	int		$user the user to have permissions checked. if left blank, currently logged in user used.
		 * @param	bool	$zero if true, zero is best
		 * @return	array	best permissions
		 * @author			Matthias Reuter
		 * @sample
		 * <code>
		 * $ipbwi->permissions->best(array('g_max_messages');
		 * </code>
		 * @since			2.0
		 */
		public function best($perm,$user=false,$zero=true){
			if(substr($perm,0,2) != 'g_'){
				$this->ipbwi->addSystemMessage('Error',$this->ipbwi->getLibLang('badPermID'),'Located in file <strong>'.__FILE__.'</strong> at class <strong>'.__CLASS__.'</strong> in function <strong>'.__FUNCTION__.'</strong> on line #<strong>'.__LINE__.'</strong>');
				return false;
			}
			$perm = preg_replace('#[^a-z_]#','',$perm);
			$info = $this->ipbwi->member->info($user);
			if(!is_array($info)){
				$this->ipbwi->addSystemMessage('Error',$this->ipbwi->getLibLang('badMemID'),'Located in file <strong>'.__FILE__.'</strong> at class <strong>'.__CLASS__.'</strong> in function <strong>'.__FUNCTION__.'</strong> on line #<strong>'.__LINE__.'</strong>');
				return false;
			}
			$init = $info[$perm];
			if(intval($init) == 0 && $zero){
				return 0;
			}
			// Take a look at secondary groups
			$info['mgroup_others'] = substr($info['mgroup_others'],1,strlen($info['mgroup_others'])-2);
			$info['mgroup_others'] = explode(',',$info['mgroup_others']);
			$info['mgroup_others'] = implode(',',$info['mgroup_others']);
			if($info['mgroup_others'] != ''){
				$this->ipbwi->ips_wrapper->DB->query('SELECT '.$perm.' FROM '.$this->ipbwi->board['sql_tbl_prefix'].'groups WHERE g_id IN('.$info['mgroup_others'].')');
				while($row = $this->ipbwi->ips_wrapper->DB->fetch()){
					if($row[$perm] > $init){
						$init = $row[$perm];
					}
					if(intval($init) == 0 && $zero){
						return 0;
					}
				}
			}
			return $init;
		}
	}
?>