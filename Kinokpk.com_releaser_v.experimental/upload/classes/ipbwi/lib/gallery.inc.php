<?php
	/**
	 * @author			Matthias Reuter ($LastChangedBy: matthias $)
	 * @version			$LastChangedDate: 2009-01-18 03:52:31 +0000 (So, 18 Jan 2009) $
	 * @package			gallery
	 * @copyright		2007-2010 IPBWI development team
	 * @link			http://ipbwi.com/examples/topic.php
	 * @since			2.0
	 * @license			http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
	 */
	class ipbwi_gallery extends ipbwi {
		private $ipbwi			= null;
		public $installed		= false;
		public $url				= false;

		/**
		 * @desc			Loads and checks different vars when class is initiating
		 * @author			Matthias Reuter
		 * @since			2.0
		 * @ignore
		 */
		public function __construct($ipbwi){
			// loads common classes
			$this->ipbwi = $ipbwi;

			// check if IP.gallery is installed
			$query = $this->ipbwi->ips_wrapper->DB->query('SELECT conf_value,conf_default FROM '.$this->ipbwi->board['sql_tbl_prefix'].'core_sys_conf_settings WHERE conf_key="gallery_images_url"');
			if($this->ipbwi->ips_wrapper->DB->getTotalRows($query) != 0){
				$data = $this->ipbwi->ips_wrapper->DB->fetch($query);
				// retrieve Gallery URL
				$this->url = (($data['conf_value'] != '') ? $data['conf_value'] : $data['conf_default']).'/';
				$this->installed = true;
			}
		}
		/**
		 * @desc			Returns categories readable by the current member.
		 * @return	array	Readable category IDs
		 * @author			Matthias Reuter
		 * @author			Pita <peter@randomnity.com>
		 * @sample
		 * <code>
		 * $ipbwi->gallery->getViewable();
		 * </code>
		 * @since			2.0
		 */
		public function getViewable(){
			if($cache = $this->ipbwi->cache->get('galleryGetViewable', $this->ipbwi->member->myInfo['member_id'])){
				return $cache;
			}else{
				$this->ipbwi->ips_wrapper->DB->query('SELECT * FROM '.$this->ipbwi->board['sql_tbl_prefix'].'gallery_albums_main');
				$cats = array();
				while($row = $this->ipbwi->ips_wrapper->DB->fetch()){
					if($this->ipbwi->group->isInGroup($row['perms_view'])){
						$cats[$row['album_id']] = $row['album_id'];
					}
				}
				$this->ipbwi->cache->save('galleryGetViewable', $this->ipbwi->member->myInfo['member_id'], $cats);
				return $cats;
			}
		}
		/**
		 * @desc			lists latest images from IP.gallery.
		 * @return	array	Image-Informations as multidimensional array
		 * @author			Matthias Reuter
		 * @since			2.02
		 */
		public function getLatestList($catIDs=false,$settings=array()){
			if($this->installed === true){
				if(is_array($catIDs)){
					// todo
				}elseif($catIDs == '*'){
					$viewable = $this->getViewable();
					if(isset($viewable[1])){
						$viewable[0] = '0';
					}
					$catquery = ' AND (img_album_id="'.implode('" OR img_album_id="',$viewable).'")';
				}elseif(intval($catIDs) != 0){
					$catquery = ' AND img_album_id="'.$catIDs.'"';
				}else{
					$catquery = false;
				}
				if(empty($settings['start'])){
					$settings['start'] = 0;
				}
				if(empty($settings['limit'])){
					$settings['limit'] = 15;
				}
				if(empty($settings['memberid'])){
					$fromMember = false;
				}else{
					$fromMember = ' AND album_owner_id = "'.$settings['memberid'].'"';
				}

				// get latest images
				$query = $this->ipbwi->ips_wrapper->DB->query('SELECT * FROM '.$this->ipbwi->board['sql_tbl_prefix'].'gallery_images WHERE approved="1"'.$catquery.$fromMember.' ORDER BY id DESC LIMIT '.intval($settings['start']).','.intval($settings['limit']));
				if($this->ipbwi->ips_wrapper->DB->getTotalRows($query) == 0){
					return false;
				}
				$data = array();
				while($row = $this->ipbwi->ips_wrapper->DB->fetch($query)){
					$row['caption']			= $this->ipbwi->properXHTML($this->ipbwi->bbcode->bbcode2html($this->ipbwi->bbcode->html2bbcode($row['caption']),false));
					$row['description']		= $this->ipbwi->properXHTML($this->ipbwi->bbcode->bbcode2html($this->ipbwi->bbcode->html2bbcode($row['description']),false));
					$row['copyright']		= $this->ipbwi->properXHTML($this->ipbwi->bbcode->bbcode2html($this->ipbwi->bbcode->html2bbcode($row['copyright']),false));
					$data[] = $row;
				}
				return $data;
			}else{
				return false;
			}
		}
		/**
		 * @desc			Returns all subcategories of the delivered cats.
		 * @param	mixed	$forums category IDs as int or array
		 * @param	string	$outputType The following output types are supported:<br>
		 * 					'html_form' to get a list of <option>-tags<br>
		 * 					'array' (default) for an array-list<br>
		 * 					'array_ids_only' for an array-list with forum IDs only<br>
		 * 					'name_id_with_indent' for an array list of names with indent according to the forum structure
		 * @param	string	$indentString The string for indent, default is '-'
		 * @return	mixed	List of all subcategories
		 * @author			Matthias Reuter
		 * @sample
		 * <code>
		 * $ipbwi->gallery->getAllSubs(array(55,22,77),'html_form');
		 * </code>
		 * @since			2.0
		 */
		public function getAllSubs($cats,$outputType='array',$indentString='—',$indent=false,$selectedID=false){
			if($this->installed === true){
				$output = false;
				// get all categories, if needed
				if(is_string($cats) && $cats == '*'){
					$cats = $this->catList();
				// get forum information of requested category
				}elseif(is_string($cats)){
					$cats = array($this->info($cats));
				}
				// save original indent string
				if(isset($indent)){
					$orig_indent = $indent;
				}else{
					$orig_indent = false;
				}
				// grab all forums from every delivered cat-id
				if(is_array($cats) && count($cats) > 0){
					foreach($cats as $i){
						if($outputType == 'html_form'){ // give every forum its own option-tag
							$select = 'album_id,album_name';
							$output .= '<option'.(($selectedID == $i['album_id']) ? ' selected="selected"' : '').(($i['parent'] == '0') ? ' style="background-color:#2683AE;color:#FFF;font-weight:bold;"' : ' style="color:#666;"').' value="'.$i['album_id'].'">&nbsp;&nbsp;'.$indent.'&nbsp;&nbsp;'.$i['album_name'].'</option>';
						}elseif($outputType == 'array'){ // merge all forum-data in one, big array
							$select = '*';
							$output[$i['album_id']] = $i;
						}elseif($outputType == 'array_ids_only'){ // merge all forum-data in one, big array
							$select = 'album_id';
							if(is_array($i)){
								$output[$i['album_id']] = $i['album_id'];
							}else{
								$output[$i] = $i;
							}
						}elseif($outputType == 'name_id_with_indent'){ // return name and id, with indent
							$select = 'album_id,album_name';
							$output[$i['album_id']] = $i;
							$output[$i['album_id']]['album_name'] = $indent.$i['album_name'];
						}
						// grab all subforums from each delivered cat-id
						$sql = 'SELECT '.$select.' FROM '.$this->ipbwi->board['sql_tbl_prefix'].'gallery_albums_main WHERE album_parent_id = '.intval($i['album_id']).' ORDER BY album_position ASC';
						if($subqery = $this->ipbwi->ips_wrapper->DB->query($sql)){
							// extend indent-string
							$indent = $indent.$indentString;
							// get all subforums in an array
							while($row = $this->ipbwi->ips_wrapper->DB->fetch($subqery)){
								if($outputType == 'array_ids_only'){
									$subforums[$row['album_id']] = $row['album_id'];
								}else{
									$row['last_pic_name'] = $this->ipbwi->properXHTML($row['last_pic_name']);
									$row['album_name'] = $this->ipbwi->properXHTML($row['album_name']);
									$row['description'] = $this->ipbwi->properXHTML($row['description']);
									$subforums[] = $row;
								}
							}
							// make it rekursive
							if(isset($subforums) && is_array($subforums) && count($subforums) > 0){
								if($outputType == 'html_form'){
									// give every forum its own option-tag
									$output .= $this->getAllSubs($subforums,$outputType,$indentString,$indent,$selectedID);
								}elseif($outputType == 'array' || $outputType == 'array_ids_only'){
									// merge all forum-data in one, big array
									$output = $output+$this->getAllSubs($subforums,$outputType,$indentString,$indent,$selectedID);
								}elseif($outputType == 'name_id_with_indent'){
									$output = $output+$this->getAllSubs($subforums,$outputType,$indentString,$indent,$selectedID);
								}
							}
							// reset the temp-values
							$subforums = false;
							$indent = $orig_indent;
						}
					}
				}else{
					return false;
				}
				return $output;
			}else{
				return false;
			}
		}
		/**
		 * @desc			List categories.
		 * @return	array	Gallery's Categories
		 * @author			Matthias Reuter
		 * @sample
		 * <code>
		 * $ipbwi->forum->catList();
		 * </code>
		 * @since			2.0
		 */
		public function catList(){
			if($this->installed === true){
				if($cache = $this->ipbwi->cache->get('listGalleryCategories', '1')){
					return $cache;
				}else{
					$query = $this->ipbwi->ips_wrapper->DB->query('SELECT * FROM '.$this->ipbwi->board['sql_tbl_prefix'].'gallery_albums_main WHERE album_parent_id="0"');
					$cat = array();
					while($row = $this->ipbwi->ips_wrapper->DB->fetch($query)){
						$row['last_pic_name'] = $this->ipbwi->properXHTML($row['last_pic_name']);
						$row['name'] = $this->ipbwi->properXHTML($row['name']);
						$row['description'] = $this->ipbwi->properXHTML($row['description']);
						$cat[$row['album_id']] = $row;
					}
					$this->ipbwi->cache->save('listGalleryCategories', '1', $cat);
					return $cat;
				}
			}else{
				return false;
			}
		}

		public function info($imgID){
			if($this->installed === true){
				// get image info
				$query = $this->ipbwi->ips_wrapper->DB->query('SELECT * FROM '.$this->ipbwi->board['sql_tbl_prefix'].'gallery_images WHERE id="'.intval($imgID).'"');
				if($this->ipbwi->ips_wrapper->DB->getTotalRows($query) == 0){
					return false;
				}
				$data = array();
				while($row = $this->ipbwi->ips_wrapper->DB->fetch($query)){
					$row['caption']			= $this->ipbwi->properXHTML($this->ipbwi->bbcode->bbcode2html($this->ipbwi->bbcode->html2bbcode($row['caption']),false));
					$row['description']		= $this->ipbwi->properXHTML($this->ipbwi->bbcode->bbcode2html($this->ipbwi->bbcode->html2bbcode($row['description']),false));
					$row['copyright']		= $this->ipbwi->properXHTML($this->ipbwi->bbcode->bbcode2html($this->ipbwi->bbcode->html2bbcode($row['copyright']),false));
					$data = $row;
				}
				return $data;
			}else{
				return false;
			}
		}
	}
?>