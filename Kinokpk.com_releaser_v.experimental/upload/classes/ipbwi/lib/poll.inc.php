<?php
	/**
	 * @author			Matthias Reuter ($LastChangedBy: matthias $)
	 * @version			$LastChangedDate: 2009-08-26 19:19:41 +0200 (Mi, 26 Aug 2009) $
	 * @package			poll
	 * @copyright		2007-2010 IPBWI development team
	 * @link			http://ipbwi.com/examples/poll.php
	 * @since			2.0
	 * @license			http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
	 */
	class ipbwi_poll extends ipbwi {
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
		 * @desc			Returns whether a member has voted in the poll in a topic.
		 * @param	int		$topicID Topic ID of the Poll
		 * @param	int		$memberID If $memberID is ommitted the last known member is used.
		 * @return	mixed	Poll Vote Date if voted, false otherwise
		 * @author			Matthias Reuter
		 * @sample
		 * <code>
		 * $ipbwi->poll->voted(55,77);
		 * </code>
		 * @since			2.0
		 */
		public function voted($topicID, $memberID = false){
			if(!$memberID){
				$memberID = $this->ipbwi->member->myInfo['member_id'];
			}
			$this->ipbwi->ips_wrapper->DB->query('SELECT vote_date FROM '.$this->ipbwi->board['sql_tbl_prefix'].'voters WHERE tid="'.$topicID.'" AND member_id="'.$memberID.'"');
			if($row = $this->ipbwi->ips_wrapper->DB->fetch()){
				return $row['vote_date'];
			}else{
				return false;
			}
		}
		/**
		 * @desc			Returns information on a poll.
		 * @param	int		$topicID Topic ID of the Poll
		 * @return	array	Poll Information
		 * @author			Matthias Reuter
		 * @sample
		 * <code>
		 * $ipbwi->poll->info(55);
		 * </code>
		 * @since			2.0
		 */
		public function info($topicID){
			if($cache = $this->ipbwi->cache->get('pollInfo', $topicID)){
				return $cache;
			}else{
				$this->ipbwi->ips_wrapper->DB->query('SELECT * FROM '.$this->ipbwi->board['sql_tbl_prefix'].'polls p LEFT JOIN '.$this->ipbwi->board['sql_tbl_prefix'].'members m ON (p.starter_id=m.member_id) WHERE p.tid="'.$topicID.'"');
				if($row = $this->ipbwi->ips_wrapper->DB->fetch()){
					$choices = unserialize(stripslashes($row['choices']));
					$row['choices'] = array();
					// Make choices more readable
					foreach($choices as $k => $i){
						$row['choices'][$k]['question'] = $i['question'];
						$row['choices'][$k]['multi'] = $i['multi'];
						foreach($i['choice'] as $c => $d){
							$row['choices'][$k][$c] = array('option_id' => $c,
								'option_title' => $d,
								'votes' => $i['votes'][$c],
								'percentage' => array_sum($i['votes']) ? intval(($i['votes'][$c] / array_sum($i['votes'])) * 100) : '0',
							);
						}
					}
					// I think leaving this as 'poll_question' is silly...
					$row['title'] = $row['poll_question'];
					$this->ipbwi->cache->save('pollInfo', $topicID, $row);
					return $row;
				}else{
					return false;
				}
			}
		}
		/**
		 * @desc			Returns total number of votes in a poll.
		 * @param	int		$topicID Topic ID of the Poll
		 * @return	int		Poll Votes
		 * @author			Matthias Reuter
		 * @sample
		 * <code>
		 * $ipbwi->poll->totalVotes(55);
		 * </code>
		 * @since			2.0
		 */
		public function totalVotes($topicID){
			if($info = $this->info($topicID)){
				return $info['votes'];
			}else{
				return false;
			}
		}
		/**
		 * @desc			Returns Topic ID associated with Poll ID.
		 * @param	int		$pollID Poll ID of the Poll
		 * @return	int		Topic ID associated with Poll ID
		 * @author			Matthias Reuter
		 * @sample
		 * <code>
		 * $ipbwi->poll->id2topicid(55);
		 * </code>
		 * @since			2.0
		 */
		public function id2topicid($pollID){
			if(is_array($pollID)){
				$topics = array();
				foreach($pollID as $i => $j){
					$this->ipbwi->ips_wrapper->DB->query('SELECT tid FROM '.$this->ipbwi->board['sql_tbl_prefix'].'polls WHERE pid="'.$j.'" LIMIT 1');
					if($row = $this->ipbwi->ips_wrapper->DB->fetch()){
						$topics[$i] = $row['tid'];
					}else{
						$topics[$i] = false;
					}
				}
				return $topics;
			}else{
				$this->ipbwi->ips_wrapper->DB->query('SELECT tid FROM '.$this->ipbwi->board['sql_tbl_prefix'].'polls WHERE pid="'.$pollID.'" LIMIT 1');
				if($row = $this->ipbwi->ips_wrapper->DB->fetch()){
					return $row['tid'];
				}else{
					return false;
				}
			}
		}
		/**
		 * @desc			Casts a vote in a poll.
		 * @param	int		$topicID Topic ID of the Poll
		 * @param	array	$optionid In format 'question number' => 'option'
		 * @param	int		$userID If no UserID is specified, the currently logged in user will vote
		 * @return	bool	true on success, otherwise false
		 * @author			Matthias Reuter
		 * @sample
		 * <code>
		 * $ipbwi->poll->vote(55,'1'=>'2');
		 * $ipbwi->poll->vote(77,'1'=>'3',55);
		 * </code>
		 * @since			2.0
		 */
		public function vote($topicID, $optionid = array('1'=>''), $userID = false){
			if(!$this->ipbwi->member->isLoggedIn() && empty($userID)){
				$this->ipbwi->addSystemMessage('Error',$this->ipbwi->getLibLang('membersOnly'),'Located in file <strong>'.__FILE__.'</strong> at class <strong>'.__CLASS__.'</strong> in function <strong>'.__FUNCTION__.'</strong> on line #<strong>'.__LINE__.'</strong>');
				return false;
			}
			if(empty($userID) && isset($this->ipbwi->member->myInfo['member_id'])){
				$userID = $this->ipbwi->member->myInfo['member_id'];
			}elseif(empty($userID) && empty($this->ipbwi->member->myInfo['member_id'])){
				$this->ipbwi->addSystemMessage('Error',$this->ipbwi->getLibLang('membersOnly'),'Located in file <strong>'.__FILE__.'</strong> at class <strong>'.__CLASS__.'</strong> in function <strong>'.__FUNCTION__.'</strong> on line #<strong>'.__LINE__.'</strong>');
				return false;
			}
			if(!$this->ipbwi->permissions->has('g_vote_polls',$userID)){
				$this->ipbwi->addSystemMessage('Error',$this->ipbwi->getLibLang('noPerms'),'Located in file <strong>'.__FILE__.'</strong> at class <strong>'.__CLASS__.'</strong> in function <strong>'.__FUNCTION__.'</strong> on line #<strong>'.__LINE__.'</strong>');
				return false;
			}
			if(!is_array($optionid)){
				$optionid = array("1" => $optionid);
			}
			if($this->voted($topicID)){
				$this->ipbwi->addSystemMessage('Error',$this->ipbwi->getLibLang('pollAlreadyVoted'),'Located in file <strong>'.__FILE__.'</strong> at class <strong>'.__CLASS__.'</strong> in function <strong>'.__FUNCTION__.'</strong> on line #<strong>'.__LINE__.'</strong>');
				return false;
			}else{
				// Insert Vote into Database
				$this->ipbwi->ips_wrapper->DB->query('SELECT * FROM '.$this->ipbwi->board['sql_tbl_prefix'].'polls WHERE tid="'.$topicID.'"');
				if($row = $this->ipbwi->ips_wrapper->DB->fetch()){
					$choices = unserialize(stripslashes($row['choices']));
					$userVotes		= array();
					foreach($optionid as $q => $o){
						if(!isset($choices[$q])){
							$this->ipbwi->addSystemMessage('Error',$this->ipbwi->getLibLang('pollInvalidVote'),'Located in file <strong>'.__FILE__.'</strong> at class <strong>'.__CLASS__.'</strong> in function <strong>'.__FUNCTION__.'</strong> on line #<strong>'.__LINE__.'</strong>');
							return false;
						}
						// count single votes (radio)
						if(!is_array($o) && (int)$o > 0){
							if(!isset($choices[$q]['choice'][$o])){
								$this->ipbwi->addSystemMessage('Error',$this->ipbwi->getLibLang('pollInvalidVote'),'Located in file <strong>'.__FILE__.'</strong> at class <strong>'.__CLASS__.'</strong> in function <strong>'.__FUNCTION__.'</strong> on line #<strong>'.__LINE__.'</strong>');
								return false;
							}
							++$choices[$q]['votes'][$o];
							// stack uservote-array
							$userVotes[$q] = array($o);
						// count multi votes (checkboxes)
						}elseif(is_array($o) && count($o) > 0){
							foreach($o as $s => $t){
								if(!isset($choices[$q]['choice'][$s])){
									$this->ipbwi->addSystemMessage('Error',$this->ipbwi->getLibLang('pollInvalidVote'),'Located in file <strong>'.__FILE__.'</strong> at class <strong>'.__CLASS__.'</strong> in function <strong>'.__FUNCTION__.'</strong> on line #<strong>'.__LINE__.'</strong>');
									return false;
								}
								++$choices[$q]['votes'][$s];
								// stack uservote-array
								$userVotes[$q][]	= $s;
							}
						}
					}
					$choices = addslashes(serialize($choices));
					
					$userVotes = addslashes(serialize($userVotes));
					$this->ipbwi->ips_wrapper->DB->query('UPDATE '.$this->ipbwi->board['sql_tbl_prefix'].'polls SET choices="'.$choices.'", votes=votes+1 WHERE tid="'.$topicID.'"');
					$this->ipbwi->ips_wrapper->DB->query('INSERT INTO '.$this->ipbwi->board['sql_tbl_prefix'].'voters (ip_address, vote_date, tid, member_id, forum_id, member_choices) VALUES ("'.$_SERVER['REMOTE_ADDR'].'", "'.time().'", "'.$row['tid'].'", "'.$userID.'", "'.$row['forum_id'].'", "'.$userVotes.'")');
					return true;
				}else{
					$this->ipbwi->addSystemMessage('Error',$this->ipbwi->getLibLang('pollNotExist'),'Located in file <strong>'.__FILE__.'</strong> at class <strong>'.__CLASS__.'</strong> in function <strong>'.__FUNCTION__.'</strong> on line #<strong>'.__LINE__.'</strong>');
					return false;
				}
			}
		}
		/**
		 * @desc			Casts a null vote in a poll.
		 * @param	int		$topicID Topic ID of the Poll
		 * @return	bool	true on success, otherwise false
		 * @author			Matthias Reuter
		 * @sample
		 * <code>
		 * $ipbwi->poll->vote(55);
		 * </code>
		 * @since			2.0
		 */
		public function nullVote($topicID){
			// No Guests Please
			if(!$this->ipbwi->member->isLoggedIn()){
				$this->ipbwi->addSystemMessage('Error',$this->ipbwi->getLibLang('membersOnly'),'Located in file <strong>'.__FILE__.'</strong> at class <strong>'.__CLASS__.'</strong> in function <strong>'.__FUNCTION__.'</strong> on line #<strong>'.__LINE__.'</strong>');
				return false;
			}
			if(!$this->ipbwi->permissions->has('g_vote_polls')){
				$this->ipbwi->addSystemMessage('Error',$this->ipbwi->getLibLang('noPerms'),'Located in file <strong>'.__FILE__.'</strong> at class <strong>'.__CLASS__.'</strong> in function <strong>'.__FUNCTION__.'</strong> on line #<strong>'.__LINE__.'</strong>');
				return false;
			}
			if($this->voted($topicID)){
				$this->ipbwi->addSystemMessage('Error',$this->ipbwi->getLibLang('pollAlreadyVoted'),'Located in file <strong>'.__FILE__.'</strong> at class <strong>'.__CLASS__.'</strong> in function <strong>'.__FUNCTION__.'</strong> on line #<strong>'.__LINE__.'</strong>');
				return false;
			}else{
				// Insert Vote into Database
				$this->ipbwi->ips_wrapper->DB->query('SELECT * FROM '.$this->ipbwi->board['sql_tbl_prefix'].'polls WHERE tid="'.$topicID.'"');
				if($row = $this->ipbwi->ips_wrapper->DB->fetch()){
					$this->ipbwi->ips_wrapper->DB->query('INSERT INTO '.$this->ipbwi->board['sql_tbl_prefix'].'voters (ip_address, vote_date, tid, member_id, forum_id) VALUES ("'.$_SERVER['REMOTE_ADDR'].'", "'.time().'", "'.$row['tid'].'", "'.$this->ipbwi->member->myInfo['member_id'].'", "'.$row['forum_id'].'")');
					return false;
				}else{
					$this->ipbwi->addSystemMessage('Error',$this->ipbwi->getLibLang('pollNotExist'),'Located in file <strong>'.__FILE__.'</strong> at class <strong>'.__CLASS__.'</strong> in function <strong>'.__FUNCTION__.'</strong> on line #<strong>'.__LINE__.'</strong>');
					return false;
				}
			}
		}
		/**
		 * @desc			Creates a new poll.
		 * @param	int		$topicID Topic ID of the Poll
		 * @param	array	$question Questions.
		 * @param	array	$choices The options to vote for for each question
		 * @param	string	$title The title of the poll
		 * @param	bool	$pollOnly Make the topic a poll only
		 * @param	bool	$viewVoters Show Voters of the poll
		 * @param	array	$multi To define questions as multiplechoice, declare an array via poll_multi with question-id as array-key and 1 or 0 as array-value. 1 = multiplechoice/checkbox, 0 = singlechoice/radio-button
		 * @return	bool	true on success, otherwise false
		 * @author			Matthias Reuter
		 * @sample
		 * <code>
		 * $ipbwi->poll->create(55,array('1' => 'Do you think IPBWI is useful?'),'1' => array('yes', 'no'),'Your opinion about IPBWI.');
		 * </code>
		 * @since			2.0
		 */
		public function create($topicID, $questions = array(), $choices = array(), $title='',$pollOnly=false,$viewVoters=false,$multi=array()){
			// Check if we can do polls
			if($this->ipbwi->permissions->has('g_post_polls')){
				// Check we have a good number of choices :)
				if(!is_array($questions) && strlen($questions) > 0){
					$questions = array($questions);
				}
				if(is_array($questions) AND count($questions) > 0 AND count($questions) <= $this->ipbwi->getBoardVar('max_poll_questions')){
					$title = ($title=='') ? $questions[0] : $title;
					// Some last-minute checks...
					if(count($choices) > count($questions)){
						$choices = array(0 => $choices);
					}
					$thelot = array();
					$count = 1;
					// Check our Topic exists
					if(!$topicinfo = $this->ipbwi->topic->info(intval($topicID))){
						$this->ipbwi->addSystemMessage('Error',$this->ipbwi->getLibLang('topicNotExist'),'Located in file <strong>'.__FILE__.'</strong> at class <strong>'.__CLASS__.'</strong> in function <strong>'.__FUNCTION__.'</strong> on line #<strong>'.__LINE__.'</strong>');
						return false;
					}
					foreach($questions as $k => $v){
						if(is_array($choices[$k]) AND count($choices[$k]) > 1 AND count($choices[$k]) <= $this->ipbwi->getBoardVar('max_poll_choices')){
							if(is_array($multi) && isset($multi[$k])){
								$is_multi = $multi[$k];
							}else{
								$is_multi = 0;
							}
							$thechoices = array(); // Init
							$choicecount = '1';
							foreach($choices[$k] as $i){
								$thechoices[$choicecount] = $this->ipbwi->ips_wrapper->DB->addSlashes($this->ipbwi->makeSafe($i));
								$thevotes[$choicecount] = 0;
								$choicecount++;
							}
							$thelot[$count] = array('question' => $v,'multi' => $is_multi,'choice' => $thechoices, 'votes' => $thevotes);
							$count++;
						}
						else {
							$this->ipbwi->addSystemMessage('Error',sprintf($this->ipbwi->getLibLang('pollInvalidOpts'), $this->ipbwi->getBoardVar('max_poll_choices')),'Located in file <strong>'.__FILE__.'</strong> at class <strong>'.__CLASS__.'</strong> in function <strong>'.__FUNCTION__.'</strong> on line #<strong>'.__LINE__.'</strong>');
							return false;
						}
					}
					// Now add it into the polls table
					$sql = 'INSERT INTO '.$this->ipbwi->board['sql_tbl_prefix'].'polls VALUES ("", "'.intval($topicID).'", "'.time().'", "'.addslashes(serialize($thelot)).'", "'.$this->ipbwi->member->myInfo['member_id'].'", "0", "'.$topicinfo['forum_id'].'","'.$this->ipbwi->ips_wrapper->DB->addSlashes($this->ipbwi->makeSafe($title)).'","'.intval($pollOnly).'","'.intval($viewVoters).'")';
					$this->ipbwi->ips_wrapper->DB->query($sql);
					// And change the topic's poll status to open
					$this->ipbwi->ips_wrapper->DB->query('UPDATE '.$this->ipbwi->board['sql_tbl_prefix'].'topics SET poll_state="1" WHERE tid="'.intval($topicID).'"');
					return true;
				}else{
					$this->ipbwi->addSystemMessage('Error',sprintf($this->ipbwi->getLibLang('pollInvalidQuestions'), $this->ipbwi->getBoardVar('max_poll_questions')),'Located in file <strong>'.__FILE__.'</strong> at class <strong>'.__CLASS__.'</strong> in function <strong>'.__FUNCTION__.'</strong> on line #<strong>'.__LINE__.'</strong>');
					return false;
				}
			}else{
				$this->ipbwi->addSystemMessage('Error',$this->ipbwi->getLibLang('noPerms'),'Located in file <strong>'.__FILE__.'</strong> at class <strong>'.__CLASS__.'</strong> in function <strong>'.__FUNCTION__.'</strong> on line #<strong>'.__LINE__.'</strong>');
				return false;
			}
		}
		/**
		 * @desc			Deletes Topic-Poll
		 * @param	int		$pollID ID of the Poll
		 * @return	bool	true on success, otherwise false
		 * @author			Matthias Reuter
		 * @sample
		 * <code>
		 * $ipbwi->poll->delete(55);
		 * </code>
		 * @since			2.0
		 */
		public function delete($pollID){
			$this->ipbwi->ips_wrapper->DB->query('DELETE FROM '.$this->ipbwi->board['sql_tbl_prefix'].'polls WHERE pid = "'.intval($pollID).'"');
			// Update the Topic
			if($this->ipbwi->ips_wrapper->DB->query('UPDATE '.$this->ipbwi->board['sql_tbl_prefix'].'topics SET poll_state="0",last_vote="0",total_votes="0" WHERE tid="'.$this->id2topicid($pollID).'"')){
				return true;
			}else{
				return false;
			}
		}
	}
?>