<?php
	/**
	 * @author			Matthias Reuter ($LastChangedBy: matthias $)
	 * @version			$LastChangedDate: 2009-08-26 19:19:41 +0200 (Mi, 26 Aug 2009) $
	 * @package			post
	 * @copyright		2007-2010 IPBWI development team
	 * @link			http://ipbwi.com/examples/post.php
	 * @since			2.0
	 * @license			http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
	 */
	class ipbwi_post extends ipbwi {
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
		 * @desc			Adds a new post.
		 * @param	int		$topicID Topic ID of the Post
		 * @param	string	$post Message body
		 * @param	bool	$useEmo Default: true = enable emoticons, false = disable
		 * @param	bool	$useSig Default: true = enable signatures, false = disable
		 * @param	bool	$bypassPerms Default: false = repect board permission, true = bypass permissions
		 * @param	string	$guestname Name for Guest user, Default: false
		 * @return	int		New post ID or false on failure
		 * @author			Matthias Reuter
		 * @sample
		 * <code>
		 * $ipbwi->post->create(55,'[b]post[/b]');
		 * $ipbwi->post->create(77,'[i]post[/i]', true, true, true, 'Mr. Guest');
		 * </code>
		 * @since			2.0
		 */
		public function create($topicid, $post, $useEmo = true, $useSig = true, $bypassPerms = false, $guestname = false){
			if($this->ipbwi->member->isLoggedIn()){
				$postname = $this->ipbwi->member->myInfo['members_display_name'];
			}elseif($guestname){
				$postname = $this->ipbwi->ips_wrapper->settings['guest_name_pre'].$this->makeSafe($guestname).$this->ipbwi->ips_wrapper->settings['guest_name_suf'];
			}else{
				$postname = $this->ipbwi->member->myInfo['members_display_name'];
			}
			// No Posting
			if($this->ipbwi->member->myInfo['restrict_post']){
				$this->ipbwi->addSystemMessage('Error',$this->ipbwi->getLibLang('noPerms'),'Located in file <strong>'.__FILE__.'</strong> at class <strong>'.__CLASS__.'</strong> in function <strong>'.__FUNCTION__.'</strong> on line #<strong>'.__LINE__.'</strong>');
				return false;
			}
			// Flooding
			if($this->ipbwi->ips_wrapper->settings['flood_control'] AND !$this->ipbwi->permissions->has('g_avoid_flood')){
				if((time() - $this->ipbwi->member->myInfo['last_post']) < $this->ipbwi->ips_wrapper->settings['flood_control']){
					$this->ipbwi->addSystemMessage('Error',sprintf($this->ipbwi->getLibLang('floodControl'), $this->ipbwi->ips_wrapper->settings['flood_control']),'Located in file <strong>'.__FILE__.'</strong> at class <strong>'.__CLASS__.'</strong> in function <strong>'.__FUNCTION__.'</strong> on line #<strong>'.__LINE__.'</strong>');
					return false;
				}
			}
			// Check some Topic Stuff
			$this->ipbwi->ips_wrapper->DB->query('SELECT t.*, f.* FROM '.$this->ipbwi->board['sql_tbl_prefix'].'topics t LEFT JOIN '.$this->ipbwi->board['sql_tbl_prefix'].'forums f ON (t.forum_id=f.id) WHERE t.tid="'.intval($topicid).'"');
			if($row = $this->ipbwi->ips_wrapper->DB->fetch()){
				// Check User can Post to Forum
				if($this->ipbwi->forum->isPostable($row['forum_id']) OR $bypassPerms){
					// Post Queue
					if($row['preview_posts'] OR $this->ipbwi->member->myInfo['mod_posts']){
						$preview = 1;
					}else{
						$preview = 0;
					}
					// What if the topic is locked
					if($row['state'] != 'open' AND !$this->ipbwi->permissions->has('g_post_closed')){
						$this->ipbwi->addSystemMessage('Error',$this->ipbwi->getLibLang('noPerms'),'Located in file <strong>'.__FILE__.'</strong> at class <strong>'.__CLASS__.'</strong> in function <strong>'.__FUNCTION__.'</strong> on line #<strong>'.__LINE__.'</strong>');
						return false;
					}
					// Check they can reply
					if($row['starter_id'] == $this->ipbwi->member->myInfo['member_id'] && !$this->ipbwi->permissions->has('g_reply_own_topics')){
						$this->ipbwi->addSystemMessage('Error',$this->ipbwi->getLibLang('noPerms'),'Located in file <strong>'.__FILE__.'</strong> at class <strong>'.__CLASS__.'</strong> in function <strong>'.__FUNCTION__.'</strong> on line #<strong>'.__LINE__.'</strong>');
						return false;
					}elseif(!$this->ipbwi->permissions->has('g_reply_other_topics')){
						$this->ipbwi->addSystemMessage('Error',$this->ipbwi->getLibLang('noPerms'),'Located in file <strong>'.__FILE__.'</strong> at class <strong>'.__CLASS__.'</strong> in function <strong>'.__FUNCTION__.'</strong> on line #<strong>'.__LINE__.'</strong>');
						return false;
					}
					$time = time();
					// If we're still here, we should be ok to add the post
					$this->ipbwi->ips_wrapper->parser->parse_bbcode		= $row['use_ibc'];
					$this->ipbwi->ips_wrapper->parser->strip_quotes		= 1;
					$this->ipbwi->ips_wrapper->parser->parse_nl2br		= 1;
					$this->ipbwi->ips_wrapper->parser->parse_html		= 0;
					$this->ipbwi->ips_wrapper->parser->parse_smilies	= ($useEmo ? 1 : 0);
					$post = $this->ipbwi->ips_wrapper->parser->preDbParse($post);
					if($useEmo == 0){
						$post	= $this->ipbwi->bbcode->html2bbcode($post);
					}
					$post	= $this->ipbwi->ips_wrapper->DB->addSlashes($this->ipbwi->makeSafe($post));
					// POST KEY!
					$this->ipbwi->ips_wrapper->DB->query('INSERT INTO '.$this->ipbwi->board['sql_tbl_prefix'].'posts (author_id, author_name, use_emo, use_sig, ip_address, post_date, post, queued, topic_id, post_key) VALUES ("'.$this->ipbwi->member->myInfo['member_id'].'", "'.$postname.'", "'.($useEmo ? 1 : 0).'", "'.($useSig ? 1 : 0).'", "'.$_SERVER['REMOTE_ADDR'].'", "'.$time.'", "'.$post.'", "'.$preview.'", "'.$row['tid'].'", "'.md5(microtime()).'")');
					$postID = $this->ipbwi->ips_wrapper->DB->getInsertId();
					// Update the Topics
					$this->ipbwi->ips_wrapper->DB->query('UPDATE '.$this->ipbwi->board['sql_tbl_prefix'].'topics SET last_poster_id="'.$this->ipbwi->member->myInfo['member_id'].'", last_poster_name="'.$postname.'", posts=posts+1, last_post="'.$time.'" WHERE tid="'.intval($topicid).'"');
					// Finally update the forums
					$this->ipbwi->ips_wrapper->DB->query('UPDATE '.$this->ipbwi->board['sql_tbl_prefix'].'forums SET last_poster_id="'.$this->ipbwi->member->myInfo['member_id'].'", last_poster_name="'.$postname.'", posts=posts+1, last_post="'.$time.'", last_title="'.addslashes($row['title']).'", last_id="'.intval($topicid).'" WHERE id="'.intval($row['forum_id']).'"');
					// Oh yes, any update the post count for the user
					if($this->ipbwi->member->myInfo['member_id'] != '0' && $row['inc_postcount']){
						$this->ipbwi->ips_wrapper->DB->query('UPDATE '.$this->ipbwi->board['sql_tbl_prefix'].'members SET posts=posts+1, last_post="'.time().'" WHERE member_id="'.$this->ipbwi->member->myInfo['member_id'].'" LIMIT 1');
					}elseif($this->ipbwi->member->myInfo['member_id'] != '0'){
						$this->ipbwi->ips_wrapper->DB->query('UPDATE '.$this->ipbwi->board['sql_tbl_prefix'].'members SET last_post="'.time().'" WHERE member_id="'.$this->ipbwi->member->myInfo['member_id'].'" LIMIT 1');
					}
					$this->ipbwi->cache->updateForum(intval($row['forum_id']),array('posts' => 1));
					
					return $postID;
				}else{
					$this->ipbwi->addSystemMessage('Error',$this->ipbwi->getLibLang('noPerms'),'Located in file <strong>'.__FILE__.'</strong> at class <strong>'.__CLASS__.'</strong> in function <strong>'.__FUNCTION__.'</strong> on line #<strong>'.__LINE__.'</strong>');
					return false;
				}
			}else{
				$this->ipbwi->addSystemMessage('Error',$this->ipbwi->getLibLang('topicNotExist'),'Located in file <strong>'.__FILE__.'</strong> at class <strong>'.__CLASS__.'</strong> in function <strong>'.__FUNCTION__.'</strong> on line #<strong>'.__LINE__.'</strong>');
				return false;
			}
		}
		/**
		 * @desc			Deletes Topic-Post contains delivered post_id
		 * @param	int		$postID ID of the Post
		 * @return	bool	true on success, otherwise false
		 * @author			Matthias Reuter
		 * @sample
		 * <code>
		 * $ipbwi->post->delete(55);
		 * </code>
		 * @since			2.0
		 */
		public function delete($postID){
			$pInfo = $this->info($postID);
			$this->ipbwi->ips_wrapper->DB->query('DELETE FROM '.$this->ipbwi->board['sql_tbl_prefix'].'posts WHERE pid = "'.intval($postID).'"');
			// Update the Topics
			$this->ipbwi->ips_wrapper->DB->query('UPDATE '.$this->ipbwi->board['sql_tbl_prefix'].'topics SET posts=posts-1 WHERE tid="'.$pInfo['topic_id'].'"');
			// Finally update the forums
			if($this->ipbwi->cache->updateForum($pInfo['forum_id'],array('posts' => -1))){
				return true;
			}else{
				return false;
			}
		}
		/**
		 * @desc			Edits a post (adapted from add_post)
		 * @param	int		$postID ID of the Post
		 * @param	string	$post Message body
		 * @param	bool	$disableemos Default: false = disable emoticons, true = enable
		 * @param	bool	$disablesig Default: false = disable signatures, true = enable
		 * @param	bool	$bypassPerms Default: false = repect board permission, true=bypass permissions
		 * @param	bool	$appendedit Default: true = adds the 'edited' line afer the post, false = doesn't add
		 * @return	bool	true on success, false on failure
		 * @author			Matthias Reuter
		 * @sample
		 * <code>
		 * $ipbwi->post->edit(55,'[b]post[/b]');
		 * $ipbwi->post->edit(77,'[i]post[/i]', true, true, false, true);
		 * </code>
		 * @since			2.0
		 */
		public function edit($postID, $post, $useEmo = false, $useSig = false, $bypassPerms = false, $appendedit = true){
			if(!$this->ipbwi->member->isLoggedIn()){
				// Oh dear... not sure you can go around having guests editing posts...
				$this->ipbwi->addSystemMessage('Error',$this->ipbwi->getLibLang('noPerms'),'Located in file <strong>'.__FILE__.'</strong> at class <strong>'.__CLASS__.'</strong> in function <strong>'.__FUNCTION__.'</strong> on line #<strong>'.__LINE__.'</strong>');
				return false;
			}
			// No Posting
			if($this->ipbwi->member->myInfo['restrict_post']){
				$this->ipbwi->addSystemMessage('Error',$this->ipbwi->getLibLang('noPerms'),'Located in file <strong>'.__FILE__.'</strong> at class <strong>'.__CLASS__.'</strong> in function <strong>'.__FUNCTION__.'</strong> on line #<strong>'.__LINE__.'</strong>');
				return false;
			}
			// Flooding
			if($this->ipbwi->ips_wrapper->settings['flood_control'] AND !$this->ipbwi->permissions->has('g_avoid_flood') && ((time() - $this->ipbwi->member->myInfo['last_post']) < $this->ipbwi->ips_wrapper->settings['flood_control'])){
				$this->ipbwi->addSystemMessage('Error',sprintf($this->ipbwi->getLibLang('floodControl'), $this->ips->vars['flood_control']),'Located in file <strong>'.__FILE__.'</strong> at class <strong>'.__CLASS__.'</strong> in function <strong>'.__FUNCTION__.'</strong> on line #<strong>'.__LINE__.'</strong>');
				return false;
			}
			// Check some Topic Stuff
			$this->ipbwi->ips_wrapper->DB->query('SELECT  f.*,p.*,t.* FROM '.$this->ipbwi->board['sql_tbl_prefix'].'topics t LEFT JOIN '.$this->ipbwi->board['sql_tbl_prefix'].'forums f ON (t.forum_id=f.id) LEFT JOIN '.$this->ipbwi->board['sql_tbl_prefix'].'posts p ON(p.topic_id=t.tid) WHERE p.pid="'.intval($postID).'"');
			if($row = $this->ipbwi->ips_wrapper->DB->fetch()){
				// Check User can Post to Forum
				if($this->ipbwi->forum->isPostable($row['forum_id']) OR $bypassPerms){
					// Post Queue
					if($row['preview_posts'] OR $this->ipbwi->member->myInfo['mod_posts']){
						$preview = 1;
					}else{
						$preview = 0;
					}
					// What if the topic is locked
					if($row['state'] != 'open' AND !$this->ipbwi->permissions->has('g_post_closed')){
						$this->ipbwi->addSystemMessage('Error',$this->ipbwi->getLibLang('noPerms'),'Located in file <strong>'.__FILE__.'</strong> at class <strong>'.__CLASS__.'</strong> in function <strong>'.__FUNCTION__.'</strong> on line #<strong>'.__LINE__.'</strong>');
						return false;
					}
					// Check they can edit posts
					if($row['author_id'] == $this->ipbwi->member->myInfo['member_id'] && !$this->ipbwi->permissions->has('g_edit_posts')){
						$this->ipbwi->addSystemMessage('Error',$this->ipbwi->getLibLang('noPerms'),'Located in file <strong>'.__FILE__.'</strong> at class <strong>'.__CLASS__.'</strong> in function <strong>'.__FUNCTION__.'</strong> on line #<strong>'.__LINE__.'</strong>');
						return false;
					}elseif($row['author_id'] != $this->ipbwi->member->myInfo['member_id'] && !$this->ipbwi->permissions->has('g_is_supmod')){
						$this->ipbwi->addSystemMessage('Error',$this->ipbwi->getLibLang('noPerms'),'Located in file <strong>'.__FILE__.'</strong> at class <strong>'.__CLASS__.'</strong> in function <strong>'.__FUNCTION__.'</strong> on line #<strong>'.__LINE__.'</strong>');
						return false;
					}
					// Append_Edit?
					if(!$bypassPerms && !$appendedit){
						$appendedit = $this->ipbwi->permissions->has('g_append_edit') ? 0 : 1;
					}
					$time = time();
					$this->ipbwi->ips_wrapper->parser->parse_bbcode		= $row['use_ibc'];
					$this->ipbwi->ips_wrapper->parser->strip_quotes		= 1;
					$this->ipbwi->ips_wrapper->parser->parse_nl2br		= 0;
					$this->ipbwi->ips_wrapper->parser->parse_html		= 0;
					$this->ipbwi->ips_wrapper->parser->parse_smilies	= ($useEmo ? 1 : 0);
					$post = $this->ipbwi->ips_wrapper->parser->preDbParse($post);
					if($useEmo == 0){
						$post	= $this->ipbwi->bbcode->html2bbcode($post);
					}
					$post	= $this->ipbwi->ips_wrapper->DB->addSlashes($this->ipbwi->makeSafe($post));
					// updatepost
					$this->ipbwi->ips_wrapper->DB->query('REPLACE INTO '.$this->ipbwi->board['sql_tbl_prefix'].'posts (pid, author_id, author_name, use_emo, use_sig, ip_address, edit_time, post, queued, topic_id, append_edit, edit_name, post_date,post_key,post_htmlstate,new_topic,icon_id) VALUES ("'.$row['pid'].'", "'.$row['author_id'].'", "'.$row['author_name'].'", "'.($useEmo ? 1 : 0).'", "'.($useSig ? 1 : 0).'", "'.$_SERVER['REMOTE_ADDR'].'", "'.$time.'", "'.$post.'", "'.$preview.'", "'.$row['tid'].'", "'.$appendedit.'", "'.$this->ipbwi->member->myInfo['name'].'", "'.$row['post_date'].'", "'.$row['post_key'].'", 0, "'.$row['new_topic'].'", "'.$row['icon_id'].'")');
					// update cache
					$this->ipbwi->ips_wrapper->DB->query('REPLACE INTO '.$this->ipbwi->board['sql_tbl_prefix'].'content_cache_posts (cache_content_id, cache_content, cache_updated) VALUES ("'.$row['pid'].'", "'.$post.'", "'.time().'")');
					
					return true;
				}else{
					$this->ipbwi->addSystemMessage('Error',$this->ipbwi->getLibLang('noPerms'),'Located in file <strong>'.__FILE__.'</strong> at class <strong>'.__CLASS__.'</strong> in function <strong>'.__FUNCTION__.'</strong> on line #<strong>'.__LINE__.'</strong>');
					return false;
				}
			}else{
				$this->ipbwi->addSystemMessage('Error',$this->ipbwi->getLibLang('postNotExist'),'Located in file <strong>'.__FILE__.'</strong> at class <strong>'.__CLASS__.'</strong> in function <strong>'.__FUNCTION__.'</strong> on line #<strong>'.__LINE__.'</strong>');
				return false;
			}
		}
		/**
		 * @desc			Returns information on a post.
		 * @param	int		$postID ID of the Post
		 * @param	bool	$replacePostVars replace attachment post vars with attachment-code, default: true
		 * @param	string	$ipbwiLink If you want to use IPBWI for attachment-downloading, you are able to define the attachment-link here. The var %id% is required and will be replaced with the attachment-ID.
		 * @return	array	Post Information
		 * @author			Matthias Reuter
		 * @sample
		 * <code>
		 * $ipbwi->post->info(55);
		 * </code>
		 * @since			2.0
		 */
		public function info($postID, $replacePostVars = true, $ipbwiLink = false, $list = false, $topicInfo = false){
			if(isset($list['sql'])){
				// allow SUB SELECT query joins
				$this->ipbwi->ips_wrapper->DB->allow_sub_select=1;
				
				// query list
				$query = $list['sql'];
			}else{
				// Check for Post Cache
				if($cache = $this->ipbwi->cache->get('postInfo', $postID)){
					return $cache;
				}else{
					// query single topic
					$query = 'SELECT m.*, p.*, t.forum_id, t.title AS topic_name, g.g_dohtml AS usedohtml FROM '.$this->ipbwi->board['sql_tbl_prefix'].'posts p LEFT JOIN '.$this->ipbwi->board['sql_tbl_prefix'].'topics t ON (p.topic_id=t.tid) LEFT JOIN '.$this->ipbwi->board['sql_tbl_prefix'].'members m ON (p.author_id=m.member_id) LEFT JOIN '.$this->ipbwi->board['sql_tbl_prefix'].'groups g ON (m.member_group_id=g.g_id) WHERE p.pid="'.intval($postID).'"';
				}
			}
			$sql = $this->ipbwi->ips_wrapper->DB->query($query);
			if($this->ipbwi->ips_wrapper->DB->getTotalRows($sql) == 0){
				return false;
			}
			while($row = $this->ipbwi->ips_wrapper->DB->fetch($sql)){
				// sort out posts which are the topic's first post (non-reply)
				if($row['new_topic']){
					continue;
				}
				// remember first array entry
				if(empty($firstEntry)){
					$firstEntry = $row['pid'];
				}
				
				$this->ipbwi->ips_wrapper->parser->parse_smilies			= $row['use_emo'];
				$this->ipbwi->ips_wrapper->parser->parse_html				= 0;
				$this->ipbwi->ips_wrapper->parser->parse_nl2br				= 0;
				$this->ipbwi->ips_wrapper->parser->parse_bbcode				= (isset($row['use_ibc']) ? $row['use_ibc'] : 0);
				$this->ipbwi->ips_wrapper->parser->parsing_section			= 'topics';
				$this->ipbwi->ips_wrapper->parser->parsing_mgroup			= $row['member_group_id'];
				$this->ipbwi->ips_wrapper->parser->parsing_mgroup_others	= $row['mgroup_others'];
				
				// make proper XHTML
				$post[$row['pid']]										= $row;
				$post[$row['pid']]['post']								= $this->ipbwi->properXHTML($this->ipbwi->bbcode->bbcode2html($post[$row['pid']]['post']));
				$post[$row['pid']]['post']								= str_replace('{style_image_url}',$this->ipbwi->getBoardVar('img_url'),$post[$row['pid']]['post']);
				$post[$row['pid']]['post_title']						= $this->ipbwi->properXHTML($post[$row['pid']]['post_title']);
				$post[$row['pid']]['topic_name']						= $this->ipbwi->properXHTML($post[$row['pid']]['topic_name']);
				$post[$row['pid']]['post_edit_reason']					= $this->ipbwi->properXHTML($post[$row['pid']]['post_edit_reason']);
				$post[$row['pid']]['author_name']						= $this->ipbwi->properXHTML($post[$row['pid']]['author_name']);

				// replace attachment post vars with attachment-code
				if($replacePostVars === true){
					$attachInfo = $this->ipbwi->attachment->getList($row['pid'],array('type' => 'post', 'ipbwiLink' => $ipbwiLink));
					if(is_array($attachInfo) && count($attachInfo) > 0){
						foreach($attachInfo as $attachList){
							if(strpos($post[$row['pid']]['post'],'[attachment='.$attachList['attach_id'].':') != false){
								$post[$row['pid']]['AttachmentNotInlineInfo'][$attachList['attach_id']] = $this->ipbwi->attachment->info($attachList['attach_id'],array('ipbwiLink' => $ipbwiLink));
							}
						}
						if(isset($attachInfo['defaultHTML'])){
							$post[$row['pid']]['post'] = preg_replace('/\[attachment=([!0-9]*):([!a-zA-Z0-9_\-.]*)\]/smeU','$attachInfo["defaultHTML"]',$post[$row['pid']]['post']);
						}else{
							$post[$row['pid']]['post'] = preg_replace('/\[attachment=([!0-9]*):([!a-zA-Z0-9_\-.]*)\]/smeU','$attachInfo["\1"]["defaultHTML"]',$post[$row['pid']]['post']);
						}
					}
				}
				// Save Post  In Cache and Return
				$this->ipbwi->cache->save('postInfo', $postID, $row);
			}
			if(isset($list['sql'])){
				return $post;
			}else{
				return $post[$firstEntry];
			}
			
		}
		/**
		 * @desc			array with post IDs of the given Topics
		 * @param	int		$topicIDs The topic IDs where the post IDs should be retrieved from
		 * @return	array	Post IDs
		 * @author			Matthias Reuter
		 * @sample
		 * <code>
		 * $ipbwi->post->getListIDs(array(55,22,77,99));
		 * </code>
		 * @since			2.0
		 */
		public function getListIDs($topicIDs){
			// posts
			if(is_array($topicIDs)){
				$topics = '';
				foreach($topicIDs as $topicID){
					if($topics){
						$topics .= '" OR "'.intval($topicID).'"';
					}else{
						$topics = '"'.intval($topicID).'"';
					}
				}
			}else{
				$topics = '"'.$topicIDs.'"';
			}
			$query = $this->ipbwi->ips_wrapper->DB->query('SELECT pid FROM '.$this->ipbwi->board['sql_tbl_prefix'].'posts WHERE (topic_id = '.$topics.')');
			if($this->ipbwi->ips_wrapper->DB->getTotalRows() == 0){
				return false;
			}
			while($row = $this->ipbwi->ips_wrapper->DB->fetch($query)){
				$postIDs[] = $row['pid'];
			}
			return $postIDs;
		}
		/**
		 * @desc			Lists posts in a topic.
		 * @param	mixed	$topicID The topic ID (array-list, int or '*' for all board topics)
		 * @param	array	$settings optional query settings. Settings allowed: order, orderby limit and start
		 * + string order = ASC or DESC, default ASC
		 * + string orderby = pid, author_id, author_name, post_date, post or random. Default: post_date
		 * + int start = Default: 0
		 * + int limit = Default: 15
		 * + bool replacePostVars replace attachment post vars with attachment-code, default: true
		 * + string ipbwiLink If you want to use IPBWI for attachment-downloading, you are able to define the attachment-link here. The var %id% is required and will be replaced with the attachment-ID.
		 * @param	bool	$bypassPerms Default: false = respect board permission, true = bypass permissions
		 * @param	bool	$countView Default: false = do not add view count, true = add the view count
		 * @return	array	Topic Posts
		 * @author			Matthias Reuter
		 * @sample
		 * <code>
		 * $ipbwi->post->getList(55);
		 * $ipbwi->post->getList(array(55,22,77,99));
		 * $ipbwi->post->getList('*');
		 * $ipbwi->post->getList(55,array('order' => 'DESC', 'orderby' => 'pid', 'start' => 10, 'limit' => 20),true,true);
		 * </code>
		 * @since			2.0
		 */
		public function getList($topicID, $settings = array(), $bypassPerms = false, $countView = false){
			if(empty($settings['order'])){
				$settings['order'] = 'asc';
			}else{
				$settings['order'] = strtolower($settings['order']);
			}
			if(empty($settings['limit'])){
				$settings['limit'] = 15;
			}
			if(empty($settings['start'])){
				$settings['start'] = 0;
			}
			if(empty($settings['orderby'])){
				$settings['orderby'] = 'post_date';
			}
			if(empty($settings['memberid'])){
				$settings['memberid'] = false;
			}
			if(empty($settings['replacePostVars'])){
				$settings['replacePostVars'] = true;
			}
			if(empty($settings['ipbwiLink'])){
				$settings['ipbwiLink'] = false;
			}
			
			// get data from a specific user
			if($settings['memberid']){
				$specificMember = 'p.author_id = "'.intval($settings['memberid']).'" AND ';
			}else{
				$specificMember = false;
			}
			$sqlwhere = '';
			if(is_array($topicID)){
				// get_topic_info() is too inefficent when we have alot of topic ids.
				$topics = '';
				foreach($topicID as $i){
					$i = intval($i);
					if($topics){
						$topics .= ' OR tid="'.$i.'"';
					}else{
						$topics = ' tid="'.$i.'"';
					}
				}
				// Query
				$getfid = $this->ipbwi->ips_wrapper->DB->query('SELECT tid, forum_id FROM '.$this->ipbwi->board['sql_tbl_prefix'].'topics WHERE '.$topics);
				// Now we should how topic ids and their forum ids.
				while($row = $this->ipbwi->ips_wrapper->DB->fetch($getfid)){
					if($this->ipbwi->forum->isReadable($row['forum_id']) OR $bypassPerms){
						if(!$sqlwhere){
							$sqlwhere .= '(topic_id="'. $row['tid'].'"';
						}else{
							$sqlwhere .= ' OR topic_id="'.$row['tid'].'"';
						}
					}
				}
				if($sqlwhere){
					$sqlwhere .= ') AND ';
					$cando = 1;
				}else{
					$this->ipbwi->addSystemMessage('Error',$this->ipbwi->getLibLang('noPerms'),'Located in file <strong>'.__FILE__.'</strong> at class <strong>'.__CLASS__.'</strong> in function <strong>'.__FUNCTION__.'</strong> on line #<strong>'.__LINE__.'</strong>');
					return false;
				}
			}elseif($topicID == '*'){
				if($bypassPerms){
					// Grab posts from the whole board
					$sqlwhere = false;
					$cando = 1;
				}else{
					// All topics. So we can grab them from all readable forums.
					$readable = $this->ipbwi->forum->getReadable();
					foreach($readable as $j => $k){
						if(!$sqlwhere){
							$sqlwhere .= '(forum_id="'.$j.'"';
						}else{
							$sqlwhere .= ' OR forum_id="'.$j.'"';
						}
					}
					if($sqlwhere OR isset($cando)){
						$sqlwhere .= ') AND ';
						$cando = 1;
					}else{
						$this->ipbwi->addSystemMessage('Error',$this->ipbwi->getLibLang('noPerms'),'Located in file <strong>'.__FILE__.'</strong> at class <strong>'.__CLASS__.'</strong> in function <strong>'.__FUNCTION__.'</strong> on line #<strong>'.__LINE__.'</strong>');
						return false;
					}
				}
			}else{
				// Classic Posts from Topic Export
				// Grab Topic Info then check whether forum is readable.
				$topicinfo = $this->ipbwi->topic->info($topicID,$countView);
				if($this->ipbwi->forum->isReadable($topicinfo['forum_id']) OR $bypassPerms){
					$sqlwhere = 'topic_id="'.intval($topicID).'" AND ';
					$cando = 1;
				}else{
					$this->ipbwi->addSystemMessage('Error',$this->ipbwi->getLibLang('noPerms'),'Located in file <strong>'.__FILE__.'</strong> at class <strong>'.__CLASS__.'</strong> in function <strong>'.__FUNCTION__.'</strong> on line #<strong>'.__LINE__.'</strong>');
					return false;
				}
			}
			if($cando){
				// What shall I order it by guv?
				$allowedorder = array('pid', 'author_id', 'author_name', 'post_date', 'post');
				if(in_array($settings['orderby'], $allowedorder)){
					$order = $settings['orderby'].' '.(($settings['order'] == 'desc') ? 'DESC' : 'ASC');
				}elseif($settings['orderby'] == 'random'){
					$order = 'RAND()';
				}else{
					$order = 'post_date '.(($settings['order'] == 'desc') ? 'DESC' : 'ASC');
				}
				// Grab Posts
				$limit = $settings['limit'] ? intval($settings['limit']) : 15;
				$start = $settings['start'] ? intval($settings['start']) : 0;
				
				$query = 'SELECT m.*, p.*, t.forum_id, t.title AS topic_name, g.g_dohtml AS usedohtml FROM '.$this->ipbwi->board['sql_tbl_prefix'].'posts p LEFT JOIN '.$this->ipbwi->board['sql_tbl_prefix'].'members m ON (p.author_id=m.member_id) LEFT JOIN '.$this->ipbwi->board['sql_tbl_prefix'].'groups g ON (m.member_group_id=g.g_id) LEFT JOIN '.$this->ipbwi->board['sql_tbl_prefix'].'topics t ON(p.topic_id=t.tid) WHERE p.pid != topic_firstpost AND '.$specificMember.$sqlwhere.'p.queued="0" ORDER BY '.$order.' LIMIT '.$start.','.$limit;

				return $this->info(false, $settings['replacePostVars'], $settings['ipbwiLink'], array('sql' => $query),$topicinfo);
			}else{
				$this->ipbwi->addSystemMessage('Error',$this->ipbwi->getLibLang('noPerms'),'Located in file <strong>'.__FILE__.'</strong> at class <strong>'.__CLASS__.'</strong> in function <strong>'.__FUNCTION__.'</strong> on line #<strong>'.__LINE__.'</strong>');
				return false;
			}
		}
	}
?>