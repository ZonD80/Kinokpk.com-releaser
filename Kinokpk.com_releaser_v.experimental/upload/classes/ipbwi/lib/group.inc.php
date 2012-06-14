<?php
/**
 * @author            Matthias Reuter ($LastChangedBy: matthias $)
 * @version            $LastChangedDate: 2009-08-26 19:19:41 +0200 (Mi, 26 Aug 2009) $
 * @package            group
 * @copyright        2007-2010 IPBWI development team
 * @link            http://ipbwi.com/examples/group.php
 * @since            2.0
 * @license            http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 */
class ipbwi_group extends ipbwi
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
     * @desc            Returns information on a group.
     * @param    int        $group Group ID. If $group is ommited, the last known group (of the last member) is used.
     * @return    array    Group Information
     * @author            Matthias Reuter
     * @sample
     * <code>
     * $ipbwi->group->info(5);
     * </code>
     * @since            2.0
     */
    public function info($group = false)
    {
        if (!$group) {
            // No Group? Return current group info
            $group = $this->ipbwi->member->myInfo['member_group_id'];
        }
        // Check for cache - if exists don't bother getting it again
        if ($cache = $this->ipbwi->cache->get('groupInfo', $group)) {
            return $cache;
        } else {
            // Return group info if group given
            $this->ipbwi->ips_wrapper->DB->query('SELECT g.* FROM ' . $this->ipbwi->board['sql_tbl_prefix'] . 'groups g WHERE g_id="' . intval($group) . '"');
            if ($this->ipbwi->ips_wrapper->DB->getTotalRows()) {
                $info = $this->ipbwi->ips_wrapper->DB->fetch();
                $this->ipbwi->cache->save('groupInfo', $group, $info);
                return $info;
            } else {
                return false;
            }
        }
    }

    /**
     * @desc            Changes Member group to delivered group-id.
     * @param    int        $group Group ID
     * @param    int        $member Member ID. If no Member-ID is delivered, the currently logged in member will moved.
     * @param    array    $extra secondary Group-IDs
     * @return    bool    true on success, otherwise false
     * @author            Matthias Reuter
     * @sample
     * <code>
     * $ipbwi->group->change(5);
     * $ipbwi->group->change(7,12,array(1,2,3,4));
     * </code>
     * @since            2.0
     */
    public function change($group, $member = false, $extra = false)
    {
        if (!$member) {
            $member = $this->ipbwi->member->myInfo['member_id'];
        }
        if ($extra !== false) {
            $sql_extra = ',mgroup_others="' . implode(',', $extra) . '"';
        } else {
            $sql_extra = '';
        }

        $SQL = 'UPDATE ' . $this->ipbwi->board['sql_tbl_prefix'] . 'members SET member_group_id="' . $group . '"' . $sql_extra . ' WHERE member_id="' . intval($member) . '"';

        if ($this->ipbwi->ips_wrapper->DB->query($SQL)) {
            $this->ipbwi->member->myInfo['member_group_id'] = $group;
            return true;
        } else {
            return false;
        }

        // set DB to WP again
        if (defined('IPBWIwpDB')) {
            $wpdb->query('USE ' . IPBWIwpDB);
        }
    }

    /**
     * @desc            Returns whether a member is in the specified group(s).
     * @param    int        $group Group ID or array of groups-ids separated with comma: 2,5,7
     * @param    int        $member Member ID to find
     * @param    bool    $extra Include secondary groups to test against?
     * @return    mixed    Whether member is in group(s)
     * @author            Matthias Reuter
     * @sample
     * <code>
     * $ipbwi->group->isInGroup(5);
     * $ipbwi->group->isInGroup(7,12,true);
     * </code>
     * @since            2.0
     */
    function isInGroup($group, $member = false, $extra = true)
    {
        if (!is_array($group)) $group = explode(',', $group);
        settype($group, 'array');
        if ($member) {
            $this->ipbwi->ips_wrapper->DB->query('SELECT member_group_id,mgroup_others FROM ' . $this->ipbwi->board['sql_tbl_prefix'] . 'members WHERE member_id="' . $member . '"');
            if ($row = $this->ipbwi->ips_wrapper->DB->fetch()) {
                if (in_array($row['member_group_id'], $group)) {
                    return true;
                }
                if ($extra) {
                    $others = explode(',', $row['mgroup_others']);
                    foreach ($others as $other) {
                        if (in_array($other, $group)) {
                            return true;
                        }
                    }
                }
            }
            return false;
        } else {
            if (in_array($this->ipbwi->member->myInfo['member_group_id'], $group)) {
                return true;
            } else {
                // START CHANGE
                $other = explode(',', $this->ipbwi->member->myInfo['mgroup_others']);
                if (is_array($other)) {
                    foreach ($other as $v) {
                        if (in_array($v, $group)) {
                            return true;
                        }
                    }
                }
                // END CHANGE
                return false;
            }
        }
    }
}

?>