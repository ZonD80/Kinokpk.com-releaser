<?php
	/**
	 * @author			Matthias Reuter ($LastChangedBy: matthias $)
	 * @version			$LastChangedDate: 2008-10-31 23:53:28 +0000 (Fr, 31 Okt 2008) $
	 * @package			search
	 * @copyright		2007-2010 IPBWI development team
	 * @link			http://ipbwi.com
	 * @since			2.0
	 * @license			http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
	 */
	class ipbwi_search extends ipbwi {
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
		 * @desc			Returns the search results of a search.
		 * @param	int		$searchID ID of the Search
		 * @return	array	Search Results or false on failure. BBCode is stripped off the results. To hilight the search string use str_replace() in your main script.
		 * @author			Matthias Reuter
		 * @sample
		 * <code>
		 * $searchID = $ipbwi->search->simple('search string');
		 * $ipbwi->search->results($searchID);
		 * </code>
		 * @since			2.0
		 */
		public function results($searchID){
			// Select the correct/current search from the database
			$this->ipbwi->ips_wrapper->DB->query('SELECT * FROM '.$this->ipbwi->board['sql_tbl_prefix'].'search_results WHERE id="'.$searchID.'"');
			if($row = $this->ipbwi->ips_wrapper->DB->fetch()){
				$searchinfo = $row;
				$tomato = stripslashes($row['query_cache']);
				$this->ipbwi->ips_wrapper->DB->query($tomato);
				$results = array();
				while($row = $this->ipbwi->ips_wrapper->DB->fetch()){
					// make proper XHTML
					$row['post']				= $this->ipbwi->ips_wrapper->parser->pre_display_parse($row['post']);
					$row['post']				= $this->ipbwi->properXHTML($row['post']);
					$row['title']				= $this->ipbwi->properXHTML($row['title']);
					$row['author_name']			= $this->ipbwi->properXHTML($row['author_name']);
					$results[] = $row;
				}
				$searchinfo['results'] = $results;
				return $searchinfo;
			}
			$this->ipbwi->addSystemMessage('Error',$this->ipbwi->getLibLang('searchIDnotExist'),'Located in file <strong>'.__FILE__.'</strong> at class <strong>'.__CLASS__.'</strong> in function <strong>'.__FUNCTION__.'</strong> on line #<strong>'.__LINE__.'</strong>');
			return false;
		}
		/**
		 * @desc			Performs a simple search and returns a search id.
		 * @param	string	$string Text to search for (SQL rules apply)
		 * @param	string	$forums Default: '*' (any), or comma-separated list of forum IDs
		 * @param	bool	$dateorder Default: false, true = Order by post_date
		 * @param	bool	$recursive Default: false, true = Get subforums, too
		 * @return	string	Search ID or false on failure
		 * @author			Matthias Reuter
		 * @sample
		 * <code>
		 * $ipbwi->search->simple('search string');
		 * </code>
		 * @since			2.0
		 */
		public function simple($string, $forums = '*', $dateorder = false, $recursive = false){
			$string = trim($string);
			// get all subforum ids
			if($recursive == 1 && is_array($forums)){
				// make proper array for get_all_subforums function
				foreach($forums as $get_sub){
					$get_sub_forums[]['id'] = $get_sub;
				}
				$forums = array(); // reset array
				// revert array-syntax after getting all subforums
				foreach($this->ipbwi->forum->getAllSubs($get_sub_forums,'array_ids_only') as $forum){
					$forums[] = $forum['id'];
				}
			}
			// Lets get all the IDs of readable topics and create a comma-separated string from them
			// Use the handy list_forum_topics function to create a multi-dimensional array of all readable topics in the given forums (or all forums if none specified)...
			// I figured no forum will ever have more than 100000 topics :D
			$multiarray = $this->ipbwi->topic->getList($forums,array('orderby' => 'tid', 'limit' => 100000));
			// For each topic, grab it's ID and add it to the topics string (with a comma of course)
			if(is_array($multiarray) && count($multiarray) > 0){
				foreach($multiarray as $topic){
					$topics[] = $topic['tid'];
				}
				$topics = implode(',',$topics);
			}
			// Make sure we have a list of topics to search in (presumable greater than 2 chars :D)
			if(strlen($topics) < 2){
				// Hmmm something went wrong, so lets output a friendly-ish error message
				$this->ipbwi->addSystemMessage('Error',$this->ipbwi->getLibLang('searchNoResults'),'Located in file <strong>'.__FILE__.'</strong> at class <strong>'.__CLASS__.'</strong> in function <strong>'.__FUNCTION__.'</strong> on line #<strong>'.__LINE__.'</strong>');
				return false;
			}
			// Only work out readable forums if we haven't been given a list of forums to read
			if($forums = '*'){
				// Get rid of the '*' :D
				$forums = '';
				// Lets get all the IDs of readable forums and create a comma-separated string from them
				// Use the handy get_member_readable_forums function to create a mulit-dimensional array of all readable forums
				$multiarray = $this->ipbwi->forum->getReadable();
				// For each forum, grab it's ID and add it to the topics string (with a comma of course)
				if(is_array($multiarray) && count($multiarray) > 0){
					foreach($multiarray as $forum){
						$forums[] = $forum['id'];
					}
					$forums = implode(',',$forums);
				}
				// Make sure we have a list of forums to search in
				if(!$forums){
					// Hmmm something went wrong, so lets output a friendly-ish error message
					$this->ipbwi->addSystemMessage('Error',$this->ipbwi->getLibLang('searchNoResults'),'Located in file <strong>'.__FILE__.'</strong> at class <strong>'.__CLASS__.'</strong> in function <strong>'.__FUNCTION__.'</strong> on line #<strong>'.__LINE__.'</strong>');
					return false;
				}
			}else{
				if(is_array($forums)){
					$forums = implode(',',$forums);
				}
			}
			// Weird thing - MySQL versions greater than 40010 make this function buggy unless we remove certain characters
			// Get (eventually!) the MySQL version
			$this->ipbwi->ips_wrapper->DB->query('SELECT VERSION() AS version');
			if(!$row = $this->ipbwi->ips_wrapper->DB->fetch()){
				$this->ipbwi->ips_wrapper->DB->query('SHOW VARIABLES LIKE "version"');
				$row = $this->ipbwi->ips_wrapper->DB->fetch();
			}
			$version = explode('.', preg_replace('/^(.+?)[-_]?/', '\\1', $row['version']));
			$version['0'] = (!isset($version) OR !isset($version['0'])) ? '3' : $version['0'];
			$version['1'] = (!isset($version['1'])) ? '21' : $version['1'];
			$version['2'] = (!isset($version['2'])) ? '0' : $version['2'];
			$version = intval(sprintf('%d%02d%02d', $version['0'], $version['1'], intval($version['2'])));
			// We now have the mysql version in an int for later use.
			if($version >= '40010'){
				// Remove stuff we cant have
				$string = str_replace(array('|', '&quot;', '&gt;', '%'), array('|', '\'', '>', ''), trim($string));
			}else{
				$string = str_replace(array('%', '_', '|'), array('\\%', '\\_', '|'), trim(strtolower($string)));
				$string = preg_replace('/\s+(and|or)$/' , '' , $string);
			}
			// Complicated MySQL query =] Basically this counts how many times the search string is found within any topic in any of the readable forums...
			// Oh, and if the MySQL version is greater than 40010 we have to add "IN BOOLEAN MODE" for complicated MySQL reasons :D
			$this->ipbwi->ips_wrapper->DB->query('SELECT COUNT(*) as count FROM '.$this->ipbwi->board['sql_tbl_prefix'].'posts p WHERE p.topic_id IN ('.$topics.') AND MATCH(post) AGAINST ("'.$string.'" '.(($version >= '40010') ? 'IN BOOLEAN MODE' : '').')');
			$row = $this->ipbwi->ips_wrapper->DB->fetch();
			// MySQL counted 0 matches of the search string - it isn't there...
			if($row['count'] < '1'){
				// No results, so lets output a friendly error message
				$this->ipbwi->addSystemMessage('Error',$this->ipbwi->getLibLang('searchNoResults'),'Located in file <strong>'.__FILE__.'</strong> at class <strong>'.__CLASS__.'</strong> in function <strong>'.__FUNCTION__.'</strong> on line #<strong>'.__LINE__.'</strong>');
				return false;
			}
			// Ok, so we found at least one match... Lets build another complicated MySQL query to store in the database for the search_results function to query.
			$store = 'SELECT MATCH(post) AGAINST ("'.$string.'" '.(($version >= '40010') ? 'IN BOOLEAN MODE' : '').') as score, t.approved, t.tid, t.posts AS topic_posts, t.title AS topic_title, t.views, t.forum_id, p.post, p.author_id, p.author_name, p.post_date, p.queued, p.pid, p.post_htmlstate, m. * , me. * , pp. * FROM '.$this->ipbwi->board['sql_tbl_prefix'].'posts p LEFT JOIN '.$this->ipbwi->board['sql_tbl_prefix'].'topics t ON ( p.topic_id = t.tid ) LEFT JOIN '.$this->ipbwi->board['sql_tbl_prefix'].'members m ON ( m.id = p.author_id ) LEFT JOIN '.$this->ipbwi->board['sql_tbl_prefix'].'member_extra me ON ( me.id = p.author_id ) LEFT JOIN '.$this->ipbwi->board['sql_tbl_prefix'].'profile_portal pp ON ( pp.pp_member_id = p.author_id ) WHERE t.forum_id IN ('.$forums.') AND t.tid IN ('.$topics.') AND t.title IS NOT NULL AND p.queued IN ( 0, 1 )  AND MATCH(post) AGAINST ("'.$string.'" '.(($version >= '40010') ? 'IN BOOLEAN MODE' : '').')';
			// Date order?
			if($dateorder){
				$store .= ' ORDER BY p.post_date DESC';
			}
			// Generate a unique search id
			$searchid = md5(uniqid(microtime(), 1));
			// Insert it into the database
			// "it" being the search ID we just generated, the current date (timestamp), two topic things which I haven't worked out the point of (yet),
			// the ID of the logged in member who did the search, their current IP address, a pointless nothing, and obviously, finally, the search results MySQL query...
			$this->ipbwi->ips_wrapper->DB->query('INSERT INTO '.$this->ipbwi->board['sql_tbl_prefix'].'search_results (id, search_date, topic_id, topic_max, member_id, ip_address, post_id, query_cache) VALUES("'.$searchid.'", "'.time().'", "'.$topics.'", "'.$row['count'].'", "'.$this->ipbwi->member->myInfo['member_id'].'", "'.$this->ipbwi->ips_wrapper->input['IP_ADDRESS'].'", NULL, "'.addslashes($store).'")');
			// Return the unique search id.
			return $searchid;
		}
	}
?>