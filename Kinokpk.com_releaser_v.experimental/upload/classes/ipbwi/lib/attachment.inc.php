<?php
/**
 * @author            Matthias Reuter ($LastChangedBy: matthias $)
 * @version            $LastChangedDate: 2009-08-26 19:19:41 +0200 (Mi, 26 Aug 2009) $
 * @package            attachment
 * @copyright        2007-2010 IPBWI development team
 * @link            http://ipbwi.com/examples/attachment.php
 * @since            2.0
 * @license            http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 */
class ipbwi_attachment extends ipbwi
{
    private $ipbwi = null;
    private $mimeTypes = array();
    private $memoryLimit = false;
    private $maxFileSize = false;
    private $postMaxSize = false;
    public $hash = false;

    /**
     * @desc            Loads and checks different vars when class is initiating
     * @param    object    ipbwi The ipbwi class object
     * @author            Matthias Reuter
     * @since            2.0
     * @ignore
     */
    public function __construct($ipbwi)
    {
        // loads common classes
        $this->ipbwi = $ipbwi;

        // load mimetypes
        $mimeTypes = array();
        require_once(ipbwi_ROOT_PATH . 'mimetypes.inc.php');
        $this->mimeTypes = $mimeTypes;

        // load upload max filesize
        $this->memoryLimit = ini_get('memory_limit');
        $this->maxFileSize = ini_get('upload_max_filesize');
        $this->postMaxSize = ini_get('post_max_size');

        // create table for attachment deeplink protection
        $sql_create = '
			CREATE TABLE IF NOT EXISTS ' . ipbwi_DB_prefix . 'attach_protect (
			hashkey TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
			hashtime TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
			) ENGINE = MYISAM;';
        $this->ipbwi->ips_wrapper->DB->query($sql_create);

        // check if 24h are past
        $sql_select = 'SELECT * FROM ' . ipbwi_DB_prefix . 'attach_protect';
        $sql_select = $this->ipbwi->ips_wrapper->DB->query($sql_select);
        $oldHash = $this->ipbwi->ips_wrapper->DB->fetch($sql_select);

        // update hash after 24h
        $newHash = md5(rand() + time());
        if ($oldHash != false && is_array($oldHash) && $oldHash['hashtime'] < time()) {
            $sql_update = 'UPDATE ' . ipbwi_DB_prefix . 'attach_protect SET hashkey = "' . $newHash . '", hashtime = "' . (time() + 86400) . '" WHERE hashkey = "' . $oldHash['hashkey'] . '";';
            $this->ipbwi->ips_wrapper->DB->query($sql_update);
            $this->hash = $newHash;
        } elseif (empty($oldHash)) {
            $sql_insert = 'INSERT INTO ' . ipbwi_DB_prefix . 'attach_protect VALUES("' . $newHash . '", "' . (time() + 86400) . '");';
            $this->ipbwi->ips_wrapper->DB->query($sql_insert);
            $this->hash = $newHash;
        } else {
            $this->hash = $oldHash['hashkey'];
        }
    }

    /**
     * @desc            reads a file from filesystem and sends it to the browser (chunked)
     * @param    string    $filename serverside path and filename
     * @param    bool    $retbytes set to true, if filesize in bytes should be returned
     * @return    string    $returns filesize or status
     * @author            Matthias Reuter
     * @since            2.0
     */
    private function readFile($filename, $retbytes = true)
    {
        $chunksize = 1 * (1024 * 1024); // how many bytes per chunk
        $buffer = '';
        $cnt = 0;
        // $handle = fopen($filename, 'rb');
        $handle = fopen($filename, 'rb');
        if ($handle === false) {
            return false;
        }
        while (!feof($handle)) {
            $buffer = fread($handle, $chunksize);
            echo $buffer;
            flush();
            if ($retbytes) {
                $cnt += strlen($buffer);
            }
        }
        $status = fclose($handle);
        if ($retbytes && $status) {
            return $cnt; // return num. bytes delivered like readfile() does.
        }
        return $status;
    }

    /**
     * @desc            gets the correct mime-type for the given file-extension
     * @param    string    $ext file-extension without a dot. example: jpg
     * @return    string    correct mimetype
     * @author            Matthias Reuter
     * @since            2.0
     */
    private function mimeType($ext)
    {
        if (isset($this->mimeTypes[$ext])) {
            return $this->mimeTypes[$ext];
        } else {
            $this->ipbwi->addSystemMessage('Error', $this->ipbwi->getLibLang('attachMimeNotFound'), 'Located in file <strong>' . __FILE__ . '</strong> at class <strong>' . __CLASS__ . '</strong> in function <strong>' . __FUNCTION__ . '</strong> on line #<strong>' . __LINE__ . '</strong>');
            return false;
        }
    }

    /**
     * @desc            get's all available informations about attachments
     * @param    mixed    $IDs relIDs (post or msg IDs) of the requested attachments. Deliver them as array or single integer.
     * @param    array    $settings array with different settings for this function
     * The following settings are supported:
     * + string <b>$settings[ipbwiLink]:</b> If you want to use IPBWI for attachment-downloading, you are able to define the attachment-link here. The var %id% is required and will be replaced with the attachment-ID. The var %hash% is optional and will be replaced with current hash key for deeplink protection.
     * + string    <b>$settings[type]:</b> Define which type your IDs are, choose between post or msg
     * @param    bool    $bypassPerms set to true, to ignore permissions
     * @return    array    attachment-informations in an array
     * @author            Matthias Reuter
     * @sample
     * <code>
     * $ipbwi->attachment->info(12,array('type' => 'post','ipbwiLink' => ipbwi_WEB_URL.'examples/attachment.php?id=%id%'));
     * $ipbwi->attachment->info(12,array('type' => 'post','ipbwiLink' => ipbwi_WEB_URL.'examples/attachment.php?id=%id%&hash=%hash%'));
     * $ipbwi->attachment->info(array(20,104,55), array('type' => 'msg'),true);
     * </code>
     * @since            2.0
     */
    public function info($IDs, $settings = array(), $bypassPerms = false)
    {
        // Initialize settings
        if (empty($settings['ipbwiLink'])) {
            $settings['ipbwiLink'] = false;
        }
        // define module
        if (isset($settings['type']) && ($settings['type'] == 'forum' || $settings['type'] == 'topic' || $settings['type'] == 'post')) {
            $module = 'post';
        } elseif (isset($settings['type']) && $settings['type'] == 'msg') {
            $module = 'msg';
        } else {
            $module = false;
        }
        // get attachments list
        if ($module === false) {
            // more than one IDs?
            if (is_array($IDs)) {
                $dbID = '';
                foreach ($IDs as $ID) {
                    if ($dbID) {
                        $dbID .= ' OR attach_id = "' . intval($ID) . '"';
                    } else {
                        $dbID = 'attach_id = "' . intval($ID) . '"';
                    }
                }
            } else {
                $dbID = 'attach_id = "' . intval($IDs) . '"';
            }
            $query = $this->ipbwi->ips_wrapper->DB->query('SELECT * FROM ' . $this->ipbwi->board['sql_tbl_prefix'] . 'attachments WHERE (' . $dbID . ') ORDER BY attach_date DESC');
        } else {
            // more than one IDs?
            if (is_array($IDs)) {
                $dbID = '';
                foreach ($IDs as $ID) {
                    if ($dbID) {
                        $dbID .= ' OR attach_rel_id = "' . intval($ID) . '"';
                    } else {
                        $dbID = 'attach_rel_id = "' . intval($ID) . '"';
                    }
                }
            } else {
                $dbID = 'attach_rel_id = "' . intval($IDs) . '"';
            }
            $query = $this->ipbwi->ips_wrapper->DB->query('SELECT * FROM ' . $this->ipbwi->board['sql_tbl_prefix'] . 'attachments WHERE attach_rel_module = "' . $module . '" AND (' . $dbID . ') ORDER BY attach_date DESC');
        }
        if ($this->ipbwi->ips_wrapper->DB->getTotalRows($query) == 0) {
            return false;
        }
        while ($row = $this->ipbwi->ips_wrapper->DB->fetch($query)) {
            // define board link
            $row['boardURL'] = $this->ipbwi->getBoardVar('upload_url') . $row['attach_location'];
            $row['boardLink'] = '<a href="' . $row['boardURL'] . '">' . $row['attach_file'] . '</a>';
            if ($row['attach_is_image'] == 1) {
                // get image code
                $filePath = $this->ipbwi->getBoardVar('upload_dir') . $row['attach_location'];
                if (file_exists($filePath) && $size = getimagesize($filePath)) {
                    $row['boardImageHTML'] = '<img src="' . $row['boardURL'] . '" ' . $size[3] . ' alt="' . $row['attach_file'] . '" />';
                }
                // get thumb code
                if ($row['attach_thumb_location'] != '') {
                    $row['boardThumbHTML'] = '<a href="' . $row['boardURL'] . '"><img src="' . $this->ipbwi->getBoardVar('upload_url') . $row['attach_thumb_location'] . '" width="' . $row['attach_thumb_width'] . '" height="' . $row['attach_thumb_height'] . '" alt="' . $row['attach_file'] . '" /></a>';
                }
            }
            // define default best-case-code
            if (isset($row['boardThumbHTML'])) {
                $row['defaultHTML'] = $row['boardThumbHTML'];
            } elseif (isset($row['boardImageHTML'])) {
                $row['defaultHTML'] = $row['boardImageHTML'];
            } else {
                $row['defaultHTML'] = $row['boardLink'];
            }
            // define ipbwi link
            if ($settings['ipbwiLink']) {
                $row['ipbwiURL'] = str_replace(array('%id%', '%hash%'), array($row['attach_id'], $this->hash), $settings['ipbwiLink']);
                $row['ipbwiLink'] = '<a href="' . $row['ipbwiURL'] . '">' . $row['attach_file'] . '</a>';
                if ($row['attach_is_image'] == 1) {
                    // get image code
                    $filePath = $this->ipbwi->getBoardVar('upload_dir') . $row['attach_location'];
                    if ($size = getimagesize($filePath)) {
                        $row['ipbwiImageHTML'] = '<img src="' . $row['ipbwiURL'] . '" ' . $size[3] . ' alt="' . $row['attach_file'] . '" />';
                    }
                    // get thumb code
                    if ($row['attach_thumb_location'] != '') {
                        $row['ipbwiThumbHTML'] = '<a href="' . $row['ipbwiURL'] . '"><img src="' . $this->ipbwi->getBoardVar('upload_url') . $row['attach_thumb_location'] . '" width="' . $row['attach_thumb_width'] . '" height="' . $row['attach_thumb_height'] . '" alt="' . $row['attach_file'] . '" /></a>';
                    }
                }
                // define default best-case-code
                if (isset($row['ipbwiThumbHTML'])) {
                    $row['defaultHTML'] = $row['ipbwiThumbHTML'];
                } elseif (isset($row['ipbwiImageHTML'])) {
                    $row['defaultHTML'] = $row['ipbwiImageHTML'];
                } else {
                    $row['defaultHTML'] = $row['ipbwiLink'];
                }
            }
            $attachments[$row['attach_id']] = $row;
        }
        // prevent a multidimensional array when there is just one entry
        if (count($attachments) == 1) {
            foreach ($attachments as $attachment) {
                $attachments = $attachment;
            }
        }
        return $attachments;
    }

    /**
     * @desc            get's all available informations about attachments either from a list of forums, topics, posts or private messages
     * @param    mixed    $IDs  IDs of the requested forums, topics, posts or PMs. Deliver them as array or single integer.
     * @param    array    $settings array with different settings for this function
     * The following settings are supported:
     * + string <b>$settings[start]:</b> For performance purposes, you are able to set a start parameter to set a section of the attachment list. This is used for forum-attachment-list only.
     * + string <b>$settings[limit]:</b> For performance purposes, you are able to set a limit parameter to set a section of the attachment list. This is used for forum-attachment-list only.
     * + string <b>$settings[ipbwiLink]:</b> If you want to use IPBWI for attachment-downloading, you are able to define the attachment-link here. The var %id% is required and will be replaced with the attachment-ID.
     * + string    <b>$settings[type]:</b> Define which type your IDs are, choose between post or msg
     * @param    bool    $bypassPerms set to true, to ignore permissions
     * @return    array    attachment-informations in an array
     * @author            Matthias Reuter
     * @sample
     * <code>
     * $ipbwi->attachment->getList(12,array('type' => 'forum','start' => 10, 'limit' => 20));
     * $ipbwi->attachment->getList(array(20,104,55), array('type' => 'topic'),true);
     * </code>
     * @since            2.0
     */
    public function getList($IDs, $settings = array(), $bypassPerms = false)
    {
        // retrieve attachments of a forum
        if ($settings['type'] == 'forum') {
            $IDs = $this->ipbwi->topic->getListIDs($IDs, $settings['start'], $settings['limit'], $bypassPerms);
            // retrieve attachments of a topic
        } elseif ($settings['type'] == 'topic') {
            $IDs = $this->ipbwi->post->getListIDs($IDs);
        }
        // return attachment informations
        return $this->info($IDs, $settings, $bypassPerms);
    }

    /**
     * @desc            loads and views an attachment
     * @param    int        $attachID ID of the attachment
     * @param    bool    $bypassPerms set to true, to ignore permissions
     * @return    file    returns attachment-file to the browser
     * @author            Matthias Reuter
     * @sample
     * <code>
     * $ipbwi->attachment->load(10,true);
     * </code>
     * @since            2.0
     */
    public function load($attachID, $bypassPerms = false)
    {
        $attachInfo = $this->info($attachID, $bypassPerms);
        if ($attachInfo) {
            $fileName = $attachInfo['attach_file'];
            $fileAddress = $this->ipbwi->getBoardVar('upload_dir') . $attachInfo['attach_location'];
            if (file_exists($fileAddress) && is_file($fileAddress)) {
                header('Content-Length: ' . filesize($fileAddress));
                // if attachment is a known image
                if ($attachInfo['attach_is_image'] && $this->mimeType($attachInfo['attach_ext'])) {
                    header('Content-Disposition: inline; filename="' . $fileName . '"');
                    header('Content-Type: ' . $this->mimeType($attachInfo['attach_ext']));
                    $this->readFile($fileAddress);
                    die();
                } // if attachment is a known filetype
                elseif ($this->mimeType($attachInfo['attach_ext'])) {
                    header('Content-Disposition: attachment; filename="' . $fileName . '"');
                    header('Content-Type: ' . $this->mimeType($attachInfo['attach_ext']));
                    $this->readFile($fileAddress);
                    die();
                }
                // if attachment is an unknown filetype
                else {
                    header('Content-Type: x-application/x-octet-stream');
                    header('Content-Disposition: attachment; filename="' . $fileName . '"');
                    $this->readFile($fileAddress);
                    die();
                }
            } else {
                $this->ipbwi->addSystemMessage('Error', $this->ipbwi->getLibLang('attachNotFoundFS'), 'Located in file <strong>' . __FILE__ . '</strong> at class <strong>' . __CLASS__ . '</strong> in function <strong>' . __FUNCTION__ . '</strong> on line #<strong>' . __LINE__ . '</strong>');
                return false;
            }
        } else {
            $this->ipbwi->addSystemMessage('Error', $this->ipbwi->getLibLang('attachNotFoundDB'), 'Located in file <strong>' . __FILE__ . '</strong> at class <strong>' . __CLASS__ . '</strong> in function <strong>' . __FUNCTION__ . '</strong> on line #<strong>' . __LINE__ . '</strong>');
            return false;
        }
    }

    // @todo implement in later versions
    private function create()
    {

    }

    private function uploadFile()
    {

    }

    private function moveFile()
    {
        rename($dir . $_POST['file_select'], $GLOBALS['straightCMS']['config']['root']['uploadpath'] . $new_file_name);
    }

    private function copyFile($fileName, $postID)
    {
        if ($this->ipbwi->member->isLoggedIn()) {
            $member = $this->ipbwi->member->info();
            $post = $this->ipbwi->post->info($postID);
            // @todo implement permission check including bypass perms
            $files = scandir(ipbwi_UPLOAD_PATH);
            if (in_array($_POST['file_select'], $files)) {
                // copy file
                $newFileName = 'post-' . $member['id'] . '-' . time() . '.ipb';
                $newDest = $this->ipbwi->getBoardVar('upload_dir') . $newFileName;
                $fileParts = explode('.', $fileName);
                $filePartsCount = count($fileParts);
                $fileExt = $fileParts[$filePartsCount - 1];
                copy(ipbwi_UPLOAD_PATH . $fileName, $newDest);
                // check of allowed file extension
                $this->ipbwi->ips_wrapper->DB->query('SELECT atype_post FROM ' . $this->ipbwi->board['sql_tbl_prefix'] . 'attachments_type WHERE atype_extension = ' . $fileExt);
                if ($row = $this->ipbwi->ips_wrapper->DB->fetch()) {
                    if ($row['atype_post'] != 1) {
                        $this->ipbwi->addSystemMessage('Error', $this->ipbwi->getLibLang('attachFileExtNotAllowed'), 'Located in file <strong>' . __FILE__ . '</strong> at class <strong>' . __CLASS__ . '</strong> in function <strong>' . __FUNCTION__ . '</strong> on line #<strong>' . __LINE__ . '</strong>');
                        return false;
                    }
                } else {
                    $this->ipbwi->addSystemMessage('Error', $this->ipbwi->getLibLang('attachFileExtNotExists'), 'Located in file <strong>' . __FILE__ . '</strong> at class <strong>' . __CLASS__ . '</strong> in function <strong>' . __FUNCTION__ . '</strong> on line #<strong>' . __LINE__ . '</strong>');
                    return false;
                }
                // check if file exceeds max file space per attachment of user
                $group = $this->ipbwi->group->info();
                if (filesize($newDest) > $group['g_attach_per_post']) {
                    $this->ipbwi->addSystemMessage('Error', $this->ipbwi->getLibLang('attachFileTooBig'), 'Located in file <strong>' . __FILE__ . '</strong> at class <strong>' . __CLASS__ . '</strong> in function <strong>' . __FUNCTION__ . '</strong> on line #<strong>' . __LINE__ . '</strong>');
                    return false;
                }
                // check if file exceeds max file space of user
                if (filesize($newDest) > $group['g_attach_max']) {
                    $this->ipbwi->addSystemMessage('Error', $this->ipbwi->getLibLang('attachFileExceedsUserSpace'), 'Located in file <strong>' . __FILE__ . '</strong> at class <strong>' . __CLASS__ . '</strong> in function <strong>' . __FUNCTION__ . '</strong> on line #<strong>' . __LINE__ . '</strong>');
                    return false;
                }
                // check for permissions
                $topic = $this->ipbwi->topic->info($post['topic_id']);
                $forum = $this->ipbwi->forum->info($topic['forum_id']);
                $permission = unserialize($forum['permission_array']);
                $permission = $permission['upload_perms'];
                if (!in_array($group['g_id'], explode(',', $permission)) && !$bypassPerms) {
                    $this->ipbwi->addSystemMessage('Error', $this->ipbwi->getLibLang('noPerms'), 'Located in file <strong>' . __FILE__ . '</strong> at class <strong>' . __CLASS__ . '</strong> in function <strong>' . __FUNCTION__ . '</strong> on line #<strong>' . __LINE__ . '</strong>');
                    return false;
                }
                // @todo implement thumb creation
                // update database
                $hash = $post['post_key'];
                $sql =
                    'INSERT INTO ' . $this->ipbwi->board['sql_tbl_prefix'] . 'attachments' .
                        '(' .
                        'attach_ext,
						attach_file,
						attach_location,
						attach_thumb_location,
						attach_thumb_width,
						attach_thumb_height,
						attach_is_image,
						attach_hits,
						attach_date,
						attach_temp,
						attach_pid,
						attach_post_key,
						attach_msg,
						attach_member_id,
						attach_filesize' .
                        ')' .
                        'VALUES' .
                        '(' .
                        '"' . $fileExt . '",
						"' . $fileName . '",
						"' . $newFileName . '",
						"",
						"0",
						"0",
						"0",
						"0",
						"' . time() . '",
						"0",
						"' . $postID . '",
						"' . $hash . '",
						"0",
						"' . $member['id'] . '",
						"' . filesize($this->ipbwi->getBoardVar('upload_dir') . $newFileName) . '"' .
                        ');';
                if ($this->ipbwi->ips_wrapper->DB->query($sql)) {
                    $this->ipbwi->addSystemMessage('Success', $this->ipbwi->getLibLang('attachCreated'), 'Located in file <strong>' . __FILE__ . '</strong> at class <strong>' . __CLASS__ . '</strong> in function <strong>' . __FUNCTION__ . '</strong> on line #<strong>' . __LINE__ . '</strong>');
                    return true;
                } else {
                    $this->ipbwi->addSystemMessage('Error', $this->ipbwi->getLibLang('attachCreationFailed'), 'Located in file <strong>' . __FILE__ . '</strong> at class <strong>' . __CLASS__ . '</strong> in function <strong>' . __FUNCTION__ . '</strong> on line #<strong>' . __LINE__ . '</strong>');
                    return false;
                }
            } else {
                $this->ipbwi->addSystemMessage('Error', $this->ipbwi->getLibLang('attachFileNotInUploadDir'), 'Located in file <strong>' . __FILE__ . '</strong> at class <strong>' . __CLASS__ . '</strong> in function <strong>' . __FUNCTION__ . '</strong> on line #<strong>' . __LINE__ . '</strong>');
                return false;
            }
        } else {
            $this->ipbwi->addSystemMessage('Error', $this->ipbwi->getLibLang('membersOnly'), 'Located in file <strong>' . __FILE__ . '</strong> at class <strong>' . __CLASS__ . '</strong> in function <strong>' . __FUNCTION__ . '</strong> on line #<strong>' . __LINE__ . '</strong>');
            return false;
        }
    }

    private function delete()
    {

    }
}

?>