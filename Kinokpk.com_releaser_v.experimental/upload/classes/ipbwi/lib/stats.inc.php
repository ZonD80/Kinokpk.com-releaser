<?php
	/**
	 * @author			Matthias Reuter ($LastChangedBy: matthias $)
	 * @version			$LastChangedDate: 2009-08-26 19:19:41 +0200 (Mi, 26 Aug 2009) $
	 * @package			stats
	 * @copyright		2007-2010 IPBWI development team
	 * @link			http://ipbwi.com/examples/stats.php
	 * @since			2.0
	 * @license			http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
	 */
	class ipbwi_stats extends ipbwi {
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
		 * @desc			Gets board statistics.
		 * @return	array	Board Statistics
		 * @author			Matthias Reuter
		 * @sample
		 * <code>
		 * $ipbwi->stats->board();
		 * </code>
		 * @since			2.0
		 */
		public function board(){
			// Check for cache
			if($cache = $this->ipbwi->cache->get('statsBoard', '1')){
				return $cache;
			}else{
				$this->ipbwi->ips_wrapper->DB->query('SELECT cs_value FROM '.$this->ipbwi->board['sql_tbl_prefix'].'cache_store WHERE cs_key = "stats"');
				$row = $this->ipbwi->ips_wrapper->DB->fetch();
				$stats = unserialize(stripslashes($row['cs_value']));
				$this->ipbwi->cache->save('statsBoard', 1, $stats);
				return $stats;
			}
		}
		/**
		 * @desc			Returns the active user count.
		 * @return	array	Active User Count
		 * @author			Matthias Reuter
		 * @sample
		 * <code>
		 * $ipbwi->stats->activeCount();
		 * </code>
		 * @since			2.01
		 */
		 function activeCount() {
			if($cache = $this->ipbwi->cache->get('activeCount', '1')){
				return $cache;
			}else{
				// Init
				$count = array('total' => '0', 'anon' => '0', 'guests' => '0', 'members' => '0');
				$cutoff = $this->ipbwi->getBoardVar('au_cutoff') ? $this->ipbwi->getBoardVar('au_cutoff') : '15';
				$timecutoff = time() - ($cutoff * 60);
				$this->ipbwi->ips_wrapper->DB->query('SELECT member_id, login_type FROM '.$this->ipbwi->board['sql_tbl_prefix'].'sessions WHERE running_time > "'.$timecutoff.'"');
				// Let's cache so we don't screw ourselves over :)
				$cached = array();
				// We need to make sure our man's in this count...
				if($this->ipbwi->member->isLoggedIn()){
					if(substr($this->ipbwi->member->myInfo['login_anonymous'],0, 1) == '1'){
						++$count['anon'];
					}else{
						++$count['members'];
					}
					$cached[$this->ipbwi->member->myInfo['member_id']] = 1;
				}
				while($row = $this->ipbwi->ips_wrapper->DB->fetch()){
					// Add up members
					if($row['login_type'] == '1' && !array_key_exists($row['member_id'],$cached)){
						++$count['anon'];
						$cached[$row['member_id']] = 1;
					}elseif($row['member_id'] == '0'){
						++$count['guests'];
					}elseif(!array_key_exists($row['member_id'],$cached)){
						++$count['members'];
						$cached[$row['member_id']] = 1;
					}
				}
				$count['total'] = $count['anon'] + $count['guests'] + $count['members'];
				$this->ipbwi->cache->save('activeCount', 'detail', $count);
				return $count;
			}
		}
		/**
		 * @desc			Returns members born on the given day of a month.
		 * @param	int		$day Optional. Current day is used if left as an empty string or zero.
		 * @param	int		$month Optional. Current month is used if left as an empty string or zero.
		 * @return	array	Birthday Members
		 * @author			Matthias Reuter
		 * @sample
		 * <code>
		 * $ipbwi->stats->birthdayMembers();
		 * $ipbwi->stats->birthdayMembers(22,7);
		 * </code>
		 * @since			2.01
		 */
		function birthdayMembers($day = 0, $month = 0) {
			if((int)$day<=0){
				$day = $this->ipbwi->date(false,'%e');
			}
			if((int)$month<=0){
				$month = $this->ipbwi->date(false,'%m');
			}
			
			$query = 'SELECT m.*, g.*, cf.* FROM '.$this->ipbwi->board['sql_tbl_prefix'].'members m LEFT JOIN '.$this->ipbwi->board['sql_tbl_prefix'].'groups g ON (m.member_group_id=g.g_id) LEFT JOIN '.$this->ipbwi->board['sql_tbl_prefix'].'pfields_content cf ON (cf.member_id=m.member_id) WHERE m.bday_day="'.intval($day).'" AND m.bday_month="'.intval($month).'"';
			$this->ipbwi->ips_wrapper->DB->query($query);
			$return = array();
			$thisyear = $this->ipbwi->date(false,'%j');
			while($row = $this->ipbwi->ips_wrapper->DB->fetch()){
				$row['age'] = $thisyear - $row['bday_year'];
				$return[] = $row;
			}
			return $return;
		}
	}
?>