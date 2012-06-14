<?php
/**
 * @author            Matthias Reuter ($LastChangedBy: matthias $)
 * @version            $LastChangedDate: 2009-08-26 19:19:41 +0200 (Mi, 26 Aug 2009) $
 * @package            pm
 * @copyright        2007-2010 IPBWI development team
 * @link            http://ipbwi.com/examples/pm.php
 * @since            2.0
 * @license            http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 */
class ipbwi_pm extends ipbwi
{
    private $ipbwi = null;

    /**
     * @desc            Loads and checks different vars when class is initiating
     * @author            Matthias Reuter
     * @since            2.0
     * @ignore
     */
    public function __construct($ipbwi)
    {
        // loads common classes
        $this->ipbwi = $ipbwi;
    }

    /**
     * @desc            Moves a personal message to another folder.
     * @param    int        $messageID Message ID to be moved
     * @param    int        $targetID Target folder ID.
     * @return    bool    true on success, otherwise false
     * @author            Matthias Reuter
     * @sample
     * <code>
     * $ipbwi->pm->move(5,4);
     * </code>
     * @since            2.0
     */
    public function move($messageID, $targetID)
    {
        if ($this->ipbwi->member->isLoggedIn()) {
            // Grab PM Info
            if ($info = $this->info($messageID, 0)) {
                // Check the Dest Folder Exists
                if ($this->folderExists($targetID) && ($targetID != 'drafts' || $targetID != 'new')) {
                    $this->ipbwi->ips_wrapper->DB->query('UPDATE ' . $this->ipbwi->board['sql_tbl_prefix'] . 'message_topic_user_map SET map_folder_id="' . $targetID . '" WHERE map_topic_id="' . $messageID . '" AND map_user_id="' . $this->ipbwi->member->myInfo['member_id'] . '" LIMIT 1');
                    if ($this->ipbwi->ips_wrapper->DB->getAffectedRows()) {
                        // Update Cache
                        $this->ipbwi->cache->updatePM($this->ipbwi->member->myInfo['member_id']);

                        return true;
                    } else {
                        $this->ipbwi->addSystemMessage('Error', $this->ipbwi->getLibLang('pmMsgNoMove'), 'Located in file <strong>' . __FILE__ . '</strong> at class <strong>' . __CLASS__ . '</strong> in function <strong>' . __FUNCTION__ . '</strong> on line #<strong>' . __LINE__ . '</strong>');
                        return false;
                    }
                } else {
                    $this->ipbwi->addSystemMessage('Error', $this->ipbwi->getLibLang('pmFolderNotExist'), 'Located in file <strong>' . __FILE__ . '</strong> at class <strong>' . __CLASS__ . '</strong> in function <strong>' . __FUNCTION__ . '</strong> on line #<strong>' . __LINE__ . '</strong>');
                    return false;
                }
            } else {
                $this->ipbwi->addSystemMessage('Error', $this->ipbwi->getLibLang('pmMsgNoMove'), 'Located in file <strong>' . __FILE__ . '</strong> at class <strong>' . __CLASS__ . '</strong> in function <strong>' . __FUNCTION__ . '</strong> on line #<strong>' . __LINE__ . '</strong>');
                return false;
            }
        } else {
            $this->ipbwi->addSystemMessage('Error', $this->ipbwi->getLibLang('membersOnly'), 'Located in file <strong>' . __FILE__ . '</strong> at class <strong>' . __CLASS__ . '</strong> in function <strong>' . __FUNCTION__ . '</strong> on line #<strong>' . __LINE__ . '</strong>');
            return false;
        }
    }

    /**
     * @desc            Removes a personal message folder.
     * @param    int        $folderID folder ID
     * @return    bool    true on success, otherwise false
     * @author            Matthias Reuter
     * @sample
     * <code>
     * $ipbwi->pm->folderDelete(55);
     * </code>
     * @since            2.0
     */
    public function folderDelete($folderID)
    {
        if ($this->ipbwi->member->isLoggedIn()) {
            $folders = $this->getFolders();
            $foldersi = array();
            if ($this->folderExists($folderID)) {
                // Check if it's Inbox or Sent Items
                if ($folderID != 'new' && $folderID != 'myconvo' && $folderID != 'drafts') {
                    // Good. Now, try and delete the messages firstly.
                    $this->folderFlush($folderID, 0);
                    // Now Delete the Folder
                    foreach ($folders as $i) {
                        if ($i['id'] != $folderID) {
                            $foldersi[$i['id']] = $i;
                        }
                    }
                    $newvids = serialize($foldersi);
                    $this->ipbwi->ips_wrapper->DB->query('UPDATE ' . $this->ipbwi->board['sql_tbl_prefix'] . 'profile_portal SET pconversation_filters="' . addslashes($newvids) . '" WHERE pp_member_id="' . $this->ipbwi->member->myInfo['member_id'] . '" LIMIT 1');
                    return true;
                } else {
                    $this->ipbwi->addSystemMessage('Error', $this->ipbwi->getLibLang('pmFolderNoRem'), 'Located in file <strong>' . __FILE__ . '</strong> at class <strong>' . __CLASS__ . '</strong> in function <strong>' . __FUNCTION__ . '</strong> on line #<strong>' . __LINE__ . '</strong>');
                    return false;
                }
            } else {
                $this->ipbwi->addSystemMessage('Error', $this->ipbwi->getLibLang('pmFolderNotExist'), 'Located in file <strong>' . __FILE__ . '</strong> at class <strong>' . __CLASS__ . '</strong> in function <strong>' . __FUNCTION__ . '</strong> on line #<strong>' . __LINE__ . '</strong>');
                return false;
            }
        } else {
            $this->ipbwi->addSystemMessage('Error', $this->ipbwi->getLibLang('membersOnly'), 'Located in file <strong>' . __FILE__ . '</strong> at class <strong>' . __CLASS__ . '</strong> in function <strong>' . __FUNCTION__ . '</strong> on line #<strong>' . __LINE__ . '</strong>');
            return false;
        }
    }

    /**
     * @desc            Empties PMs in a personal message folder.
     * @param    int        $folderID folder ID
     * @param    int        $keepunread Default: 0=also delete unread msgs, 1=keep unread messages
     * @return    bool    count of deleted messages, otherwise false
     * @author            Matthias Reuter
     * @sample
     * <code>
     * $ipbwi->pm->folderFlush(55,1);
     * </code>
     * @since            2.0
     */
    public function folderFlush($folderID, $keepUnread = 0)
    {
        if ($this->ipbwi->member->isLoggedIn()) {
            if ($this->folderExists($folderID)) {
                if ($keepUnread) {
                    $sql_keep_unread = ' AND m.map_has_unread="0"';
                }

                // Just so we can decrement total
                $queryCount = $this->ipbwi->ips_wrapper->DB->query('SELECT COUNT(t.mt_id) AS messagescount FROM ' . $this->ipbwi->board['sql_tbl_prefix'] . 'message_topic_user_map m LEFT JOIN ' . $this->ipbwi->board['sql_tbl_prefix'] . 'message_topics t ON (map_topic_id=t.mt_id) WHERE m.map_folder_id="' . $folderID . '" AND m.map_user_id="' . $this->ipbwi->member->myInfo['member_id'] . '"' . $sql_keep_unread);
                $row = $this->ipbwi->ips_wrapper->DB->fetch($queryCount);
                $del = $row['messagescount'];

                // Get message text ids and check deletion state
                $query = $this->ipbwi->ips_wrapper->DB->query('SELECT t.mt_id,m.map_user_active as active FROM ' . $this->ipbwi->board['sql_tbl_prefix'] . 'message_topic_user_map m LEFT JOIN ' . $this->ipbwi->board['sql_tbl_prefix'] . 'message_topics t ON (map_topic_id=t.mt_id) WHERE m.map_folder_id="' . $folderID . '" AND m.map_user_id="' . $this->ipbwi->member->myInfo['member_id'] . '"' . $sql_keep_unread);
                // start deleting
                while ($row = $this->ipbwi->ips_wrapper->DB->fetch($query)) {
                    if ($row['mt_id'] != '') {
                        if ($row['active'] == 0) {
                            $row['deleted'] = 1;
                        }
                        $this->_internal_delete($row['mt_id'], intval($row['deleted']));
                    }
                }

                // Update Total
                $this->ipbwi->ips_wrapper->DB->query('UPDATE ' . $this->ipbwi->board['sql_tbl_prefix'] . 'members SET msg_count_total=msg_count_total-' . intval($del) . ' WHERE member_id="' . $this->ipbwi->member->myInfo['member_id'] . '" LIMIT 1');

                // Update Cache
                $this->ipbwi->cache->updatePM($this->ipbwi->member->myInfo['member_id']);

                return $del;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * @desc            Renames a personal message folder.
     * @param    int        $folderID folder ID
     * @param    string    $newName New folder name
     * @return    bool    true on success, otherwise false
     * @author            Matthias Reuter
     * @sample
     * <code>
     * $ipbwi->pm->folderRename(55,'new folder name');
     * </code>
     * @since            2.0
     */
    public function folderRename($folderID, $newName)
    {
        if ($this->ipbwi->member->isLoggedIn()) {
            // Get Folders
            $folders = $this->getFolders();

            // Check it exists
            if ($folders[$folderID]) {
                $folders[$folderID]['real'] = $newName;

                // Rename the Folder
                $this->ipbwi->ips_wrapper->DB->query('UPDATE ' . $this->ipbwi->board['sql_tbl_prefix'] . 'profile_portal SET pconversation_filters="' . addslashes(serialize($folders)) . '" WHERE pp_member_id="' . $this->ipbwi->member->myInfo['member_id'] . '" LIMIT 1');

                return true;
            } else {
                $this->ipbwi->addSystemMessage('Error', $this->ipbwi->getLibLang('pmFolderNotExist'), 'Located in file <strong>' . __FILE__ . '</strong> at class <strong>' . __CLASS__ . '</strong> in function <strong>' . __FUNCTION__ . '</strong> on line #<strong>' . __LINE__ . '</strong>');
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * @desc            Creates a personal message folder.
     * @param    int        $folderID folder ID
     * @param    string    $newName folder name
     * @return    bool    true on success, otherwise false
     * @author            Matthias Reuter
     * @sample
     * <code>
     * $ipbwi->pm->folderAdd('folder name');
     * </code>
     * @since            2.0
     */
    public function folderAdd($name)
    {
        if ($this->ipbwi->member->isLoggedIn()) {
            // Get Folders
            $folders = $this->getFolders();

            $foldersno = count($folders);
            // Just to check
            if (empty($folders['dir_' . $foldersno])) {
                $newFolders = $folders;
                $newFolders['dir_' . $foldersno]['id'] = 'dir_' . $foldersno;
                $newFolders['dir_' . $foldersno]['real'] = $name;
                $newFolders['dir_' . $foldersno]['count'] = 0;
                $newFolders['dir_' . $foldersno]['protected'] = 0;

                $this->ipbwi->ips_wrapper->DB->query('UPDATE ' . $this->ipbwi->board['sql_tbl_prefix'] . 'profile_portal SET pconversation_filters="' . addslashes(serialize($newFolders)) . '" WHERE pp_member_id="' . $this->ipbwi->member->myInfo['member_id'] . '" LIMIT 1');
                return 'dir_' . $foldersno;
            } else {
                // Just incase
                while ($foldersno < 100) {
                    if (!$folders['dir_' . $foldersno]) {
                        $newFolders = $folders;
                        $newFolders['dir_' . $foldersno]['id'] = 'dir_' . $foldersno;
                        $newFolders['dir_' . $foldersno]['real'] = $name;
                        $newFolders['dir_' . $foldersno]['count'] = 0;
                        $newFolders['dir_' . $foldersno]['protected'] = 0;

                        $this->ipbwi->ips_wrapper->DB->query('UPDATE ' . $this->ipbwi->board['sql_tbl_prefix'] . 'profile_portal SET pconversation_filters="' . addslashes(serialize($newFolders)) . '" WHERE pp_member_id="' . $this->ipbwi->member->myInfo['member_id'] . '" LIMIT 1');
                        return 'dir_' . $foldersno;
                    }
                    ++$foldersno;
                }
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * @desc            Returns folder name associated with folder id of a member.
     * @param    int        $folderID folder ID
     * @param    int        $userID If $userID is omitted, the currently active user is used.
     * @return    string    Folder Name associated with id
     * @author            Matthias Reuter
     * @sample
     * <code>
     * $ipbwi->pm->folderid2name('folder name');
     * </code>
     * @since            2.0
     */
    public function folderid2name($folderID, $userID = false)
    {
        $folders = $this->getFolders($userID);
        if (isset($folders[$folderID]['real'])) {
            return $folders[$folderID]['real'];
        } else {
            return false;
        }
    }

    /**
     * @desc            Returns whether a PM folder exists for a given member.
     * @param    int        $folderID folder ID
     * @param    int        $userID If $userID is omitted, the currently active user is used.
     * @return    bool    Folder Existance Status
     * @author            Matthias Reuter
     * @sample
     * <code>
     * $ipbwi->pm->folderExists(3,55);
     * </code>
     * @since            2.0
     */
    public function folderExists($folderID, $userID = false)
    {
        // these boxes are good
        if ($folderID == 'new' || $folderID == 'myconvo' || $folderID == 'drafts') {
            return true;
        }
        // 'unsent' should be an bad folder name anyway, but put this so as not to screw up other functions
        if ($folderID == 'unsent') {
            return false;
        }

        $memberInfo = $this->ipbwi->member->info($userID);
        $folders = $this->getFolders();

        if (isset($folders[$folderID])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @desc            Returns the current user's PM folders.
     * @param    int        $userID If $userID is omitted, the currently active user is used.
     * @return    array    Current user's PM System Folders
     * @author            Matthias Reuter
     * @sample
     * <code>
     * $ipbwi->pm->getFolders();
     * </code>
     * @since            2.0
     */
    public function getFolders($userID = false)
    {
        // Check for cache - if exists don't bother getting it again
        if ($cache = $this->ipbwi->cache->get('pmFolderList', intval($userID))) {
            return $cache;
        } else {
            if (($this->ipbwi->member->isLoggedIn() && $this->ipbwi->permissions->has('g_use_pm')) || $userID) {
                $memberInfo = $this->ipbwi->member->info($userID);
                $sql = 'SELECT pconversation_filters FROM ' . $this->ipbwi->board['sql_tbl_prefix'] . 'profile_portal WHERE pp_member_id="' . $memberInfo['member_id'] . '"';
                $query = $this->ipbwi->ips_wrapper->DB->query($sql);

                if ($row = $this->ipbwi->ips_wrapper->DB->fetch($query)) {
                    $folders = unserialize($row['pconversation_filters']);
                    $this->ipbwi->cache->save('pmFolderList', intval($userID), $folders);
                    return $folders;
                } else {
                    return false;
                }

            } else {
                return false;
            }
        }
    }

    /**
     * @desc            Returns PM space usage in percentage.
     * @return    int        PM Space Usage in Percent
     * @author            Matthias Reuter
     * @sample
     * <code>
     * $ipbwi->pm->spaceUsage();
     * </code>
     * @since            2.0
     */
    public function spaceUsage()
    {
        $PMs = $this->numTotalPms();
        $maximumPMs = $this->ipbwi->permissions->best('g_max_messages');
        // Remove possible division by zero...
        if ($maximumPMs == 0) {
            return 0;
        }
        $percent = round(($PMs / $maximumPMs) * 100);
        return $percent;
    }

    /**
     * @desc            Returns number of PMs in a folder.
     * @param    int        $folderID Folder ID
     * @return    int        Number of PMs in Folder
     * @author            Matthias Reuter
     * @sample
     * <code>
     * $ipbwi->pm->numFolderPMs(55);
     * </code>
     * @since            2.0
     */
    public function numFolderPMs($folderID)
    {
        if (!$this->ipbwi->member->isLoggedIn() AND !$this->permissions->has('g_use_pm')) {
            $this->ipbwi->addSystemMessage('Error', $this->ipbwi->getLibLang('membersOnly'), 'Located in file <strong>' . __FILE__ . '</strong> at class <strong>' . __CLASS__ . '</strong> in function <strong>' . __FUNCTION__ . '</strong> on line #<strong>' . __LINE__ . '</strong>');
            return false;
        }

        $folders = $this->getFolders();

        if (isset($folders[$folderID]['count'])) {
            return $folders[$folderID]['count'];
        } else {
            return false;
        }
    }

    /**
     * @desc            Deletes a Personal Message.
     * @param    int        $messageID Message to be deleted
     * @return    bool    true on success, otherwise false
     * @author            Matthias Reuter
     * @sample
     * <code>
     * $ipbwi->pm->delete(55);
     * </code>
     * @since            2.0
     */
    public function delete($messageID)
    {
        if (!$this->member->isLoggedIn()) {
            $this->ipbwi->addSystemMessage('Error', $this->ipbwi->getLibLang('membersOnly'), 'Located in file <strong>' . __FILE__ . '</strong> at class <strong>' . __CLASS__ . '</strong> in function <strong>' . __FUNCTION__ . '</strong> on line #<strong>' . __LINE__ . '</strong>');
            return false;
        }

        // check deletion state
        #$query = $this->ipbwi->ips_wrapper->DB->query('SELECT mt_is_deleted as deleted FROM '.$this->ipbwi->board['sql_tbl_prefix'].'message_topics WHERE mt_id = "'.intval($messageID).'"');
        $query = $this->ipbwi->ips_wrapper->DB->query('SELECT map_user_active as active FROM ' . $this->ipbwi->board['sql_tbl_prefix'] . 'message_topic_user_map WHERE map_user_id="' . $this->ipbwi->member->myInfo['member_id'] . '" AND map_topic_id="' . intval($messageID) . '"');
        $state = $this->ipbwi->ips_wrapper->DB->fetch($query);
        if ($state['active'] == 0) {
            $state['deleted'] = 1;
        }
        $return = $this->_internal_delete($messageID, intval($state['deleted']));

        // Update Total
        $this->ipbwi->ips_wrapper->DB->query('UPDATE ' . $this->ipbwi->board['sql_tbl_prefix'] . 'members SET msg_count_total=msg_count_total-1 WHERE member_id="' . $this->ipbwi->member->myInfo['member_id'] . '" LIMIT 1');

        // Update Cache
        $this->ipbwi->cache->updatePM($this->ipbwi->member->myInfo['member_id']);

        return $return;
    }


    private function _internal_delete($messageID, $isDeleted)
    {
        // Mark Topic as deleted
        $messageID = intval($messageID);
        if ($messageID > 0) {
            if ($isDeleted != 1) {
                #$this->ipbwi->ips_wrapper->DB->query('UPDATE '.$this->ipbwi->board['sql_tbl_prefix'].'message_topics SET mt_is_deleted=1 WHERE mt_id="'.$messageID.'" LIMIT 1');
                $this->ipbwi->ips_wrapper->DB->query('UPDATE ' . $this->ipbwi->board['sql_tbl_prefix'] . 'message_topic_user_map SET map_user_active=1 WHERE map_user_id="' . $this->ipbwi->member->myInfo['member_id'] . '" AND map_topic_id="' . $messageID . '" LIMIT 1');
                // Delete Topic and Posts permanently if already marked as deleted
            } else {
                // delete topics
                $this->ipbwi->ips_wrapper->DB->query('DELETE FROM ' . $this->ipbwi->board['sql_tbl_prefix'] . 'message_topics WHERE mt_id="' . $messageID . '" AND mt_is_deleted="1"');
                // delete posts
                $this->ipbwi->ips_wrapper->DB->query('DELETE FROM ' . $this->ipbwi->board['sql_tbl_prefix'] . 'message_posts WHERE msg_topic_id = "' . $messageID . '"');
            }

            // delete user map
            $this->ipbwi->ips_wrapper->DB->query('DELETE FROM ' . $this->ipbwi->board['sql_tbl_prefix'] . 'message_topic_user_map WHERE map_topic_id="' . $messageID . '" AND map_user_id="' . $this->ipbwi->member->myInfo['member_id'] . '"');
            return true;
        } else {
            return false;
        }
    }

    /**
     * @desc            Sends a reply to a PM
     *
     * @param    int        $topicID Topic ID
     * @param    string    $msgContent Message Content
     * @return    mixed    Msg ID or exception
     * @author            Matthias Reuter
     * @sample
     * <code>
     * $ipbwi->pm->send(5,'message title','message content,array('55','77'));
     * </code>
     * @since            2.0
     */
    public function reply($topicID, $msgContent, $options = array())
    {
        if (!$this->ipbwi->member->isLoggedIn()) {
            $this->ipbwi->addSystemMessage('Error', $this->ipbwi->getLibLang('membersOnly'), 'Located in file <strong>' . __FILE__ . '</strong> at class <strong>' . __CLASS__ . '</strong> in function <strong>' . __FUNCTION__ . '</strong> on line #<strong>' . __LINE__ . '</strong>');
            return false;
        }
        if (!$msgContent OR strlen($msgContent) < 2) {
            $this->ipbwi->addSystemMessage('Error', $this->ipbwi->getLibLang('pmMessage'), 'Located in file <strong>' . __FILE__ . '</strong> at class <strong>' . __CLASS__ . '</strong> in function <strong>' . __FUNCTION__ . '</strong> on line #<strong>' . __LINE__ . '</strong>');
            return false;
        }

        // send it
        return $this->ipbwi->ips_wrapper->messenger->sendReply($this->ipbwi->member->myInfo['member_id'], $topicID, $msgContent);
    }

    /**
     * @desc            Sends a PM.
     * @param    int        $toID Member ID to receive the message
     * @param    string    $title Message title
     * @param    string    $message Message body
     * @param    array    $inviteUsers Array of InviteUser Names (display name)
     * @param    array     $options Options array[ 'isSystem' (if true, then user will have no record of sending this PM) postKey, 'isDraft', 'sendMode' (invite/copy), 'topicID' ] If a topicID is passed, it's presumed that it was a draft....
     * @return    bool    true on success, otherwise false
     * @author            Matthias Reuter
     * @sample
     * <code>
     * $ipbwi->pm->send(5,'message title','message content,array('55','77'));
     * </code>
     * @since            2.0
     */
    public function send($toID, $title, $message, $inviteUsers = array(), $options = array())
    {
        if (!$this->ipbwi->member->isLoggedIn()) {
            $this->ipbwi->addSystemMessage('Error', $this->ipbwi->getLibLang('membersOnly'), 'Located in file <strong>' . __FILE__ . '</strong> at class <strong>' . __CLASS__ . '</strong> in function <strong>' . __FUNCTION__ . '</strong> on line #<strong>' . __LINE__ . '</strong>');
            return false;
        }
        if (!$toID) {
            $this->ipbwi->addSystemMessage('Error', $this->ipbwi->getLibLang('pmNoRecipient'), 'Located in file <strong>' . __FILE__ . '</strong> at class <strong>' . __CLASS__ . '</strong> in function <strong>' . __FUNCTION__ . '</strong> on line #<strong>' . __LINE__ . '</strong>');
            return false;
        }
        if ($toID == $this->ipbwi->member->myInfo['member_id']) {
            $this->ipbwi->addSystemMessage('Error', $this->ipbwi->getLibLang('pmCantSendToSelf'), 'Located in file <strong>' . __FILE__ . '</strong> at class <strong>' . __CLASS__ . '</strong> in function <strong>' . __FUNCTION__ . '</strong> on line #<strong>' . __LINE__ . '</strong>');
            return false;
        }
        if (!$title OR strlen($title) < 2) {
            $this->ipbwi->addSystemMessage('Error', $this->ipbwi->getLibLang('pmTitle'), 'Located in file <strong>' . __FILE__ . '</strong> at class <strong>' . __CLASS__ . '</strong> in function <strong>' . __FUNCTION__ . '</strong> on line #<strong>' . __LINE__ . '</strong>');
            return false;
        }
        if (!$message OR strlen($message) < 2) {
            $this->ipbwi->addSystemMessage('Error', $this->ipbwi->getLibLang('pmMessage'), 'Located in file <strong>' . __FILE__ . '</strong> at class <strong>' . __CLASS__ . '</strong> in function <strong>' . __FUNCTION__ . '</strong> on line #<strong>' . __LINE__ . '</strong>');
            return false;
        }
        $this->ipbwi->ips_wrapper->DB->query('SELECT member_id FROM ' . $this->ipbwi->board['sql_tbl_prefix'] . 'members WHERE member_id="' . intval($toID) . '"');
        if ($row = $this->ipbwi->ips_wrapper->DB->fetch()) {
            // Just incase
            if (!$row['member_id']) {
                $this->ipbwi->addSystemMessage('Error', $this->ipbwi->getLibLang('pmMemNotExist'), 'Located in file <strong>' . __FILE__ . '</strong> at class <strong>' . __CLASS__ . '</strong> in function <strong>' . __FUNCTION__ . '</strong> on line #<strong>' . __LINE__ . '</strong>');
                return false;
            }
            // Actually send it
            $this->ipbwi->ips_wrapper->messenger->sendNewPersonalTopic($toID, $this->ipbwi->member->myInfo['member_id'], $inviteUsers, $title, $message, $options);
            return true;
        } else {
            $this->ipbwi->addSystemMessage('Error', $this->ipbwi->getLibLang('pmMemNotExist'), 'Located in file <strong>' . __FILE__ . '</strong> at class <strong>' . __CLASS__ . '</strong> in function <strong>' . __FUNCTION__ . '</strong> on line #<strong>' . __LINE__ . '</strong>');
            return false;
        }
    }

    /**
     * @desc            Returns information on a Personal Message.
     * @param    int        $ID PM Topic ID
     * @param    bool    $markRead Default: 1=mark read, 0=keep unread
     * @return    array    Information of a PM
     * @author            Matthias Reuter
     * @sample
     * <code>
     * $ipbwi->pm->info(5,true,false);
     * </code>
     * @since            2.0
     */
    public function info($ID, $markRead = true)
    {
        if (!$this->ipbwi->member->isLoggedIn() AND !$this->ipbwi->permissions->has('g_use_pm')) {
            $this->ipbwi->addSystemMessage('Error', $this->ipbwi->getLibLang('membersOnly'), 'Located in file <strong>' . __FILE__ . '</strong> at class <strong>' . __CLASS__ . '</strong> in function <strong>' . __FUNCTION__ . '</strong> on line #<strong>' . __LINE__ . '</strong>');
            return false;
        }

        if ($cache = $this->ipbwi->cache->get('pmInfo', $folder)) {
            return $cache;
        }

        $sql = 'SELECT t.*, p.*, m.*, map.* FROM ' . $this->ipbwi->board['sql_tbl_prefix'] . 'message_topics t
			LEFT JOIN ' . $this->ipbwi->board['sql_tbl_prefix'] . 'message_posts p ON (p.msg_topic_id=t.mt_id)
			LEFT JOIN ' . $this->ipbwi->board['sql_tbl_prefix'] . 'members m ON (m.member_id=p.msg_author_id)
			LEFT JOIN ' . $this->ipbwi->board['sql_tbl_prefix'] . 'message_topic_user_map map ON (map.map_topic_id=t.mt_id)
			WHERE p.msg_topic_id = "' . $ID . '" AND map.map_user_id="' . $this->ipbwi->member->myInfo['member_id'] . '" ORDER BY p.msg_date ASC';

        $query = $this->ipbwi->ips_wrapper->DB->query($sql);
        if ($this->ipbwi->ips_wrapper->DB->getTotalRows($query)) {
            // get em
            $i = 1;
            while ($row = $this->ipbwi->ips_wrapper->DB->fetch($query)) {

                //mark as read
                if ($i == 1 && $markRead && $row['map_has_unread'] == 1) {
                    $this->ipbwi->ips_wrapper->DB->query('UPDATE ' . $this->ipbwi->board['sql_tbl_prefix'] . 'message_topic_user_map SET map_has_unread="0", map_read_time="' . time() . '", map_folder_id="myconvo" WHERE map_topic_id="' . $ID . '" AND map_user_id="' . $this->ipbwi->member->myInfo['member_id'] . '" LIMIT 1');
                    $this->ipbwi->ips_wrapper->DB->query('UPDATE ' . $this->ipbwi->board['sql_tbl_prefix'] . 'members SET msg_count_new=msg_count_new-1 WHERE member_id="' . $this->ipbwi->member->myInfo['member_id'] . '" AND msg_count_new > 0');

                    $folders = $this->getFolders();
                    if ($folders['new']['count'] > 0) {
                        $folders['new']['count'] = $folders['new']['count'] - 1;
                        $folders = serialize($folders);
                        $this->ipbwi->ips_wrapper->DB->query('UPDATE ' . $this->ipbwi->board['sql_tbl_prefix'] . 'profile_portal SET pconversation_filters="' . addslashes($folders) . '" WHERE pp_member_id="' . $this->ipbwi->member->myInfo['member_id'] . '"');
                    }
                }

                $this->ipbwi->ips_wrapper->parser->parse_smilies = 1;
                $this->ipbwi->ips_wrapper->parser->parse_html = 0;
                $this->ipbwi->ips_wrapper->parser->parse_bbcode = 1;
                $this->ipbwi->ips_wrapper->parser->strip_quotes = 1;
                $this->ipbwi->ips_wrapper->parser->parse_nl2br = 1;
                // make proper XHTML
                $row['msg_post_bbcode'] = $this->ipbwi->properXHTML($this->ipbwi->bbcode->html2bbcode($row['msg_post']));
                $row['msg_post'] = $this->ipbwi->ips_wrapper->parser->preDisplayParse($row['msg_post']);
                $row['msg_post'] = $this->ipbwi->properXHTML($row['msg_post']);
                $row['mt_title'] = $this->ipbwi->properXHTML($row['mt_title']);
                $row['name'] = $this->ipbwi->properXHTML($row['name']);
                $row['mt_vid_folder'] = $this->ipbwi->properXHTML($row['mt_vid_folder']);
                $row['recipient_name'] = $this->ipbwi->properXHTML($row['recipient_name']);
                $PM[] = $row;

                $i++;
            }
            return $PM;
            $this->ipbwi->cache->save('pmInfo', $folder, $PM);
        } else {
            return false;
        }
    }

    /**
     * @desc            Lists PMs in a folder.
     * @param    string    $folder Keyname of Inbox folder
     * The following options can be used to overwrite the default query results.
     * <br>'order' default: 'asc'
     * <br>'start' default: '0' start with first record
     * <br>'limit' default: '30' no. of PMs per page
     * <br>'orderby' default: 'last_topic_reply' other keys: id, user_id, topic_id
     * @return    array    Information of PMs in folder.
     * @author            Matthias Reuter
     * @sample
     * <code>
     * $ipbwi->pm->getList(); // returns "My Conversations" folder
     * $ipbwi->pm->getList('new');
     * </code>
     * @since            2.0
     */
    public function getList($folder = 'myconvo', $options = array('order' => 'desc', 'start' => '0', 'limit' => '30', 'orderby' => 'name'))
    {
        // has perms?
        if (!$this->ipbwi->member->isLoggedIn() AND !$this->ipbwi->permissions->has('g_use_pm')) {
            $this->ipbwi->addSystemMessage('Error', $this->ipbwi->getLibLang('membersOnly'), 'Located in file <strong>' . __FILE__ . '</strong> at class <strong>' . __CLASS__ . '</strong> in function <strong>' . __FUNCTION__ . '</strong> on line #<strong>' . __LINE__ . '</strong>');
            return false;
        }
        // folder exists?
        if (!$this->folderExists($folder)) {
            return false;
        }

        if (!isset($options['order'])) {
            $options['order'] = 'desc';
        }
        if (!isset($options['start'])) {
            $options['start'] = '0';
        }
        if (!isset($options['limit'])) {
            $options['limit'] = '30';
        }
        if (!isset($options['orderby'])) {
            $options['orderby'] = 'name';
        }

        if ($cache = $this->ipbwi->cache->get('pmList', $folder)) {
            return $cache;
        }

        // Ordering
        $orders = array('last_topic_reply', 'id', 'user_id', 'topic_id');
        if (!in_array($options['orderby'], $orders)) {
            $options['orderby'] = 'last_topic_reply';
        }
        // Order By
        $options['order'] = ($options['order'] == 'asc') ? 'ASC' : 'DESC';
        // Start and Limit
        $filter = 'LIMIT ' . intval($options['start']) . ',' . intval($options['limit']);

        // filter new pm's if needed
        if ($folder == 'new') {
            $onlyNew = ' AND map.map_has_unread = 1';
            $folder = 'myconvo';
        } else {
            $onlyNew = '';
        }

        $PMs = array();
        $sql = 'SELECT map.*, t.*, m.* FROM ' . $this->ipbwi->board['sql_tbl_prefix'] . 'message_topic_user_map map
			LEFT JOIN ' . $this->ipbwi->board['sql_tbl_prefix'] . 'message_topics t ON (t.mt_id=map.map_topic_id)
			LEFT JOIN ' . $this->ipbwi->board['sql_tbl_prefix'] . 'members m ON (m.member_id=t.mt_starter_id)
			WHERE map.map_user_id = "' . $this->ipbwi->member->myInfo['member_id'] . '" AND map.map_user_active="1" AND map.map_folder_id = "' . $folder . '"' . $onlyNew . ' ORDER BY map.map_' . $options['orderby'] . ' ' . $options['order'] . ' ' . $filter;

        $query = $this->ipbwi->ips_wrapper->DB->query($sql);
        if ($this->ipbwi->ips_wrapper->DB->getTotalRows($query)) {
            $this->ipbwi->ips_wrapper->parser->parse_smilies = 1;
            $this->ipbwi->ips_wrapper->parser->parse_html = 0;
            $this->ipbwi->ips_wrapper->parser->parse_bbcode = 1;
            $this->ipbwi->ips_wrapper->parser->strip_quotes = 1;
            $this->ipbwi->ips_wrapper->parser->parse_nl2br = 1;
            while ($row = $this->ipbwi->ips_wrapper->DB->fetch($query)) {
                $PMs[] = $row;
            }
            $this->ipbwi->cache->save('pmList', $folder, $PMs);
            return $PMs;
        } else {
            return false;
        }
    }

    /**
     * @desc            Gets number of new PMs.
     * @return    int        New Unread Messages Count
     * @author            Matthias Reuter
     * @sample
     * <code>
     * $ipbwi->pm->numNewPMs();
     * </code>
     * @since            2.0
     */
    public function numNewPMs()
    {
        if (!$this->ipbwi->member->isLoggedIn() AND !$this->ipbwi->permissions->has('g_use_pm')) {
            $this->ipbwi->addSystemMessage('Error', $this->ipbwi->getLibLang('membersOnly'), 'Located in file <strong>' . __FILE__ . '</strong> at class <strong>' . __CLASS__ . '</strong> in function <strong>' . __FUNCTION__ . '</strong> on line #<strong>' . __LINE__ . '</strong>');
            return false;
        }
        $this->ipbwi->ips_wrapper->DB->query('SELECT msg_count_new FROM ' . $this->ipbwi->board['sql_tbl_prefix'] . 'members WHERE member_id="' . $this->ipbwi->member->myInfo['member_id'] . '"');
        if ($messages = $this->ipbwi->ips_wrapper->DB->fetch()) {
            return (int)$messages['msg_count_new'];
        } else {
            return false;
        }
    }

    /**
     * @desc            Gets total number of PMs.
     * @return    int        Total Messages Count
     * @author            Matthias Reuter
     * @sample
     * <code>
     * $ipbwi->pm->numTotalPMs();
     * </code>
     * @since            2.0
     */
    public function numTotalPMs()
    {
        if (!$this->ipbwi->member->isLoggedIn() AND !$this->ipbwi->permissions->has('g_use_pm')) {
            $this->ipbwi->addSystemMessage('Error', $this->ipbwi->getLibLang('membersOnly'), 'Located in file <strong>' . __FILE__ . '</strong> at class <strong>' . __CLASS__ . '</strong> in function <strong>' . __FUNCTION__ . '</strong> on line #<strong>' . __LINE__ . '</strong>');
            return false;
        }
        $this->ipbwi->ips_wrapper->DB->query('SELECT msg_count_total FROM ' . $this->ipbwi->board['sql_tbl_prefix'] . 'members WHERE member_id="' . $this->ipbwi->member->myInfo['member_id'] . '"');
        if ($messages = $this->ipbwi->ips_wrapper->DB->fetch()) {
            return $messages['msg_count_total'];
        } else {
            return false;
        }
    }

    /**
     * @desc            Returns whether a member has blocked another member.
     * @param    int        $blocked Member ID of receiver (the one who is blocked)
     * @param    int        $by Member ID of sender (the one who blocked)
     * @return    bool    Block Status
     * @author            Matthias Reuter
     * @sample
     * <code>
     * $ipbwi->pm->isBlocked(55,77);
     * </code>
     * @since            2.0
     */
    public function isBlocked($blocked, $by)
    {
        $query = $this->ipbwi->ips_wrapper->DB->query('SELECT ignore_messages FROM ' . $this->ipbwi->board['sql_tbl_prefix'] . 'ignored_users WHERE ignore_ignore_id="' . $blocked . '" AND ignore_owner_id="' . $by . '"');
        if ($cando = $this->ipbwi->ips_wrapper->DB->fetch($query)) {
            if ($cando['ignore_messages'] == 1) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * @desc            Blocks a contact.
     * @param    int        $userID Member ID to be added
     * @return    bool    true on success, otherwise false
     * @author            Matthias Reuter
     * @sample
     * <code>
     * $ipbwi->pm->blockContact(55);
     * </code>
     * @since            2.0
     */
    public function blockContact($userID)
    {
        if ($this->isLoggedIn()) {
            // Check user exists
            if (!$userID OR !$this->info(intval($userID))) {
                return false;
            }
            // o_O. Firstly check if there is already an entry.
            if ($this->isBlocked($userID, $this->ipbwi->member->myInfo['member_id'])) {
                return true;
            } else {
                // We can just add an entry because theres nothing there.
                $this->ipbwi->ips_wrapper->DB->query('INSERT INTO ' . $this->ipbwi->board['sql_tbl_prefix'] . 'ignored_users VALUES ("", "' . $this->ipbwi->member->myInfo['member_id'] . '", "' . intval($userID) . '", "1", "0")');
                return true;
            }
        } else {
            return false;
        }
    }

    /**
     * @desc            Returns list of IDs from blocked members
     * @return    array    Blocked Members IDs of currently logged in user
     * @author            Matthias Reuter
     * @sample
     * <code>
     * $ipbwi->pm->blockedList();
     * </code>
     * @since            2.0
     */
    public function blockedList()
    {
        if ($this->ipbwi->member->isLoggedIn()) {
            $this->ipbwi->ips_wrapper->DB->query('SELECT ignore_ignore_id FROM ' . $this->ipbwi->board['sql_tbl_prefix'] . 'ibf_ignored_users WHERE ignore_owner_id="' . $this->ipbwi->member->myInfo['member_id'] . '" AND ignore_messages="1"');
            $blocked = array();
            while ($row = $this->ipbwi->ips_wrapper->DB->fetch()) {
                $blocked[$row['contact_id']] = $row;
            }
            return $blocked;
        } else {
            return false;
        }
    }
}

?>