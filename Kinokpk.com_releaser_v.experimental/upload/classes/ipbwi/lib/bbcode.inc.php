<?php
	/**
	 * @author			Matthias Reuter ($LastChangedBy: matthias $)
	 * @version			$LastChangedDate: 2009-08-26 19:19:41 +0200 (Mi, 26 Aug 2009) $
	 * @package			bbcode
	 * @copyright		2007-2010 IPBWI development team
	 * @link			http://ipbwi.com/examples/bbcode.php
	 * @since			2.0
	 * @license			http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
	 */
	class ipbwi_bbcode extends ipbwi {
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
		 * @desc			converts BBCode to HTML using IPB's native parser.
		 * @param	string	$input bbcode-formatted string
		 * @param	bool	$smilies set to true to parse smilies, otherwise false
		 * @return	string	HTML version of input
		 * @author			Matthias Reuter
		 * @sample
		 * <code>
		 * $ipbwi->bbcode->bbcode2html('[b]test[/b]',true);
		 * </code>
		 * @since			2.0
		 */
		public function bbcode2html($input, $smilies = true){
			$this->ipbwi->ips_wrapper->parser->parse_smilies = $smilies;
			$this->ipbwi->ips_wrapper->parser->parse_html = 0;
			$this->ipbwi->ips_wrapper->parser->parse_bbcode = 1;
			$this->ipbwi->ips_wrapper->parser->strip_quotes = 1;
			$this->ipbwi->ips_wrapper->parser->parse_nl2br = 0;
			$input = @$this->ipbwi->ips_wrapper->parser->preDbParse($input);
			// Leave this here in case things go pear-shaped...
			$input = $this->ipbwi->ips_wrapper->parser->preDisplayParse($input);
			if($smilies){
				$input	= $this->ipbwi->properXHTML($input);
			}
			return $input;
		}
		/**
		 * @desc			converts HTML to BBCode using IPB's native parser.
		 * @param	string	$input html-formatted string
		 * @return	string	BBCode version of input
		 * @author			Matthias Reuter
		 * @sample
		 * <code>
		 * $ipbwi->bbcode->html2bbcode('<b>test</b>');
		 * </code>
		 * @since			2.0
		 */
		public function html2bbcode($input){
			$this->ipbwi->ips_wrapper->parser->parse_html		= 0;
			$this->ipbwi->ips_wrapper->parser->parse_nl2br		= 0;
			$this->ipbwi->ips_wrapper->parser->parse_smilies	= 1;
			$this->ipbwi->ips_wrapper->parser->parse_bbcode		= 1;
			$this->ipbwi->ips_wrapper->parser->parsing_section	= 'myapp_comment';
			$input = $this->ipbwi->ips_wrapper->parser->preEditParse($input);
			return $input;
		}
		/**
		 * @desc			List emoticons, optional limit the result to clickable emoticons only.
		 * @param	bool	$clickable set to true to list clickable emoticons only, otherwise set to false
		 * @return	array	Assoc array with Emoticons, keys 'typed', 'image'
		 * @author			Matthias Reuter
		 * @sample
		 * <code>
		 * $ipbwi->bbcode->listEmoticons(true);
		 * </code>
		 * @since			2.0
		 */
		public function listEmoticons($clickable = false){
			if($clickable){
				$this->ipbwi->ips_wrapper->DB->query('SELECT typed, image FROM '.$this->ipbwi->board['sql_tbl_prefix'].'emoticons WHERE clickable="1"');
			}else{
				$this->ipbwi->ips_wrapper->DB->query('SELECT typed, image FROM '.$this->ipbwi->board['sql_tbl_prefix'].'emoticons');
			}
			$emos = array();
			while($row = $this->ipbwi->ips_wrapper->DB->fetch()){
				$emos[$row['typed']] = $row['image'];
			}
			return $emos;
		}
		/**
		 * @desc			Print IP.board's built in RichTextEditor (RTE). notice: if your form isn't formatted correctly, please check in your css declaration of tags, e.g. "ul", wether they conflict with IP.board's editor.
		 * @param	string	$post a string of content going to be displayed in editor. If empty, a blank editor will be loaded.
		 * @param	string	$field a string which defines the name of textarea form field
		 * @param	int		$output optional, if 1: output of css & javascript, if 2: output of form, if false: output of both all together
		 * @param	bool	$rte optional, if true: force use of rich text editor
		 * @return	string	HTML Code of IP.board's RTE
		 * @author			Matthias Reuter
		 * @sample
		 * <code>
		 * $ipbwi->bbcode->printTextEditor('post content');
		 * </code>
		 * @since			2.0
		 */
		public function printTextEditor($post='',$field='post',$output=false,$rte=false){
			$boardURL = str_replace('?','',$this->ipbwi->board['url']);
		
			$style = '
				<link rel="stylesheet" type="text/css" media="screen" href="'.$boardURL.'public/min/index.php?ipbv=31005&amp;f=public/style_css/css_'.$this->ipbwi->skin->id().'/ipb_editor.css" />
				<style type="text/css">
					<!--
						#ipboard_body ul, ol{
							list-style:none outside none;
							margin:0px;
							padding:0px;
						}
					-->
				</style>
			';
			
			$jscript = <<<EOF_SCRIPT
<script type='text/javascript'>
	jsDebug = 0; /* Must come before JS includes */
	USE_RTE = 1;
	inACP   = false;
</script>
<script type='text/javascript'>
//<![CDATA[
	/* ---- URLs ---- */
	ipb.vars['base_url'] 			= '{$boardURL}index.php?';
	ipb.vars['board_url']			= '{$boardURL}';
	ipb.vars['loading_img'] 		= '{$boardURL}public/style_images/master/loading.gif';
	ipb.vars['active_app']			= 'forums';
	ipb.vars['upload_url']			= '{$boardURL}uploads';
	
	/* ---- Other ---- */
	ipb.vars['use_rte']				= 1;
	
	/* Templates/Language */
	ipb.templates['ajax_loading'] 	= "<div id='ajax_loading'>" + ipb.lang['loading'] + "</div>";
//]]>
</script>
<script type='text/javascript'>
	Loader.boot();
</script>
<script type='text/javascript' src='{$boardURL}public/min/index.php?ipbv=31005&amp;g=js'></script>
<script type='text/javascript' src='{$boardURL}public/min/index.php?ipbv=31005&amp;charset=UTF-8&amp;f=public/js/ipb.js,public/js/ips.editor.js' charset='UTF-8'></script>
EOF_SCRIPT;

		
			IPSText::getTextClass('bbcode')->parse_html			= 0;
			IPSText::getTextClass('bbcode')->parse_wordwrap		= 0;
			IPSText::getTextClass('bbcode')->bypass_badwords	= true;
			IPSText::getTextClass('bbcode')->rte_width			= 200;
			
			$rte_post = IPSText::getTextClass('bbcode')->convertForRTE($post);
			
			$form = '<div id="ipboard_body">'.
				str_replace(
					array('<#EMO_DIR#>','undefined&amp;app=forums'),
					array('default',$this->ipbwi->getBoardVar('url').'/index.php?app=forums'),
					@IPSText::getTextClass('editor')->showEditor($rte_post, $field))
			.'</div>';
			
			// if user has set rich text editor
			if($this->ipbwi->member->myInfo['members_editor_choice'] == 'rte' || $rte == true){
				if($output == 1){
					return $style.$jscript;
				}elseif($output == 2){
					return $form;
				}else{
					return $style.$jscript.$form;
				}
			// if user has set standard text editor
			}else{
			IPSText::getTextClass('bbcode')->parse_html			= 0;
			IPSText::getTextClass('bbcode')->parse_nl2br		= 1;
			IPSText::getTextClass('bbcode')->parse_smilies		= 1;
			IPSText::getTextClass('bbcode')->parse_bbcode		= 1;
			IPSText::getTextClass('bbcode')->parsing_section	= 'global';
			
			$std = '<textarea name="'.$field.'" class="std_text" cols="40" rows="10">'.IPSText::getTextClass('bbcode')->preEditParse($post).'</textarea>';
			
			return $std;
			}
		}
	}
?>