<?php
/**
 * @author            Matthias Reuter ($LastChangedBy: matthias $)
 * @version            $LastChangedDate: 2008-10-31 23:53:28 +0000 (Fr, 31 Okt 2008) $
 * @package            skin
 * @copyright        2007-2010 IPBWI development team
 * @link            http://ipbwi.com/examples/skin.php
 * @since            2.0
 * @license            http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 */
class ipbwi_skin extends ipbwi
{
    public $emoURL = false;
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

        $this->emoURL = str_replace('<#EMO_DIR#>', $this->emoDir(), $this->ipbwi->ips_wrapper->settings['emoticons_url']);
    }

    /**
     * @desc            Returns the Skin ID of the skin used by a member.
     * @param    int        $memberID Member ID. If Member ID is ommitted, the current User will be used.
     * @return    int        Skin ID or false on failure
     * @author            Matthias Reuter
     * @sample
     * <code>
     * $ipbwi->skin->id($memberID);
     * </code>
     * @since            2.0
     */
    public function id($memberID = false)
    {
        $member = $this->ipbwi->member->info(); // get user info...
        if (isset($member['skin']) && $member['skin'] != '' && $member['skin'] != 0) {
            return $member['skin'];
        } else {
            $sql = $this->ipbwi->ips_wrapper->DB->query('SELECT set_id FROM ' . $this->ipbwi->board['sql_tbl_prefix'] . 'skin_collections WHERE set_is_default = "1"');
            if ($row = $this->ipbwi->ips_wrapper->DB->fetch($sql)) {
                return $row['set_id'];
            } else {
                return false;
            }
        }
    }

    /**
     * @desc            Gets information on a skin.
     * @param    int        $skinID ID of the Skin
     * @return    array    Information on Skin or false on failure
     * @author            Matthias Reuter
     * @sample
     * <code>
     * $ipbwi->skin->info($skinID);
     * </code>
     * @since            2.0
     */
    public function info($skinID)
    {
        if ($skinID >= 0) { // If they've specified a skin
            $sql = $this->ipbwi->ips_wrapper->DB->query('SELECT * FROM ' . $this->ipbwi->board['sql_tbl_prefix'] . 'skin_collections WHERE set_id="' . $skinID . '"');
            if ($row = $this->ipbwi->ips_wrapper->DB->fetch($sql)) {
                return $row;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * @desc            Gets emoticon directory of currently logged in user.
     * @return    array    emoticon dir
     * @author            Matthias Reuter
     * @sample
     * <code>
     * $ipbwi->skin->info($skinID);
     * </code>
     * @since            2.0
     */
    public function emoDir()
    {
        $member = $this->ipbwi->member->info(); // get user info...
        if (isset($member['skin']) && $member['skin'] != '' && intval($member['skin']) > 0) { // for guests or if no skin is set...
            $skinID = $member['skin']; // ...for skin id
            $default = false; // ...make it the default skin
        } else {
            $skinID = false;
            $default = true;
        }

        $sql = $this->ipbwi->ips_wrapper->DB->query('SELECT set_emo_dir FROM ' . $this->ipbwi->board['sql_tbl_prefix'] . 'skin_collections WHERE ' . (($default === true) ? 'set_key = "default"' : 'set_id = ' . $skinID));
        $emodir = $this->ipbwi->ips_wrapper->DB->fetch($sql);
        return $emodir['set_emo_dir'] . '/';
    }

    /**
     * @desc            Pulls and displays CSS from forums depending on user's skin.
     * @return    array    stylesheet fields
     * @author            Matthias Reuter
     * @sample
     * <code>
     * $ipbwi->skin->css();
     * $ipbwi->skin->css(true,false);
     * </code>
     * @since            2.0
     */
    public function css()
    {
        $member = $this->ipbwi->member->info(); // get user info...

        if (isset($member['skin']) && $member['skin'] != '' && $member['skin'] != 0) { // for guests or if no skin is set...
            $skinID = $member['skin']; // ...for skin id
            $default = false; // ...make it the default skin
        } else {
            $skinID = false;
            $default = true;
        }

        // get css groups
        $sql = $this->ipbwi->ips_wrapper->DB->query('SELECT set_css_groups,set_image_dir FROM ' . $this->ipbwi->board['sql_tbl_prefix'] . 'skin_collections WHERE ' . (($default === true) ? ('set_is_default = "1"') : (' set_id = ' . $skinID)));
        $skin = $this->ipbwi->ips_wrapper->DB->fetch($sql);
        $CSSgroups = unserialize($skin['set_css_groups']);
        $c = count($CSSgroups);
        $i = 1;
        $CSSids['list'] = 'WHERE (css_id = ';
        foreach ($CSSgroups as $IDs => $g) {
            if ($i < $c) {
                $delimiter = ') OR (css_id = ';
            } else {
                $delimiter = ')';
            }
            $i++;
            $IDs = explode('.', $IDs);
            $CSSids[] = $IDs[1];
            $CSSids['list'] .= $IDs[1] . $delimiter;
        }

        // get CSS fields from DB
        $query = 'SELECT * FROM ' . $this->ipbwi->board['sql_tbl_prefix'] . 'skin_css ' . $CSSids['list'] . ' ORDER BY css_position ASC';
        $sql = $this->ipbwi->ips_wrapper->DB->query($query);
        while ($entry = $this->ipbwi->ips_wrapper->DB->fetch($sql)) {
            $style[$entry['css_group']] = str_replace('{style_images_url}', $skin['set_image_dir'], $entry['css_content']);
        }
        return $style;
    }

    /**
     * @desc            Grabs the IDs of all the avaliable skins.
     * @return    array    Skin IDs
     * @author            Matthias Reuter
     * @sample
     * <code>
     * $ipbwi->skin->getList();
     * </code>
     * @since            2.0
     */
    public function getList()
    {
        // Grab all skins which aren't hidden
        $sql = $this->ipbwi->ips_wrapper->DB->query('SELECT set_id FROM ' . $this->ipbwi->board['sql_tbl_prefix'] . 'skin_collections WHERE set_hide_from_list="0"');
        $skins = array();
        while ($row = $this->ipbwi->ips_wrapper->DB->fetch($sql)) {
            $skins[] = $row['set_id'];
        }
        return $skins;
    }

    /**
     * @desc            Changes the current user's skin.
     * @param    int        $skinID Skin ID
     * @param    int        $memberID Member ID. If Member ID is ommitted, the current User will be used.
     * @return    bool    true on success, otherwise false
     * @author            Matthias Reuter
     * @sample
     * <code>
     * $ipbwi->skin->set();
     * </code>
     * @since            2.0
     */
    public function set($skinID, $memberID = false)
    {
        // Check it exists
        if ($this->info($skinID)) {
            // Grab current member id unless specified
            $member = $this->ipbwi->member->info(); // get user info...
            if ($this->ipbwi->member->updateMember(array('skin' => $skinID), $member['member_id'])) {
                return true;
            } else {
                return false;
            }
        } else {
            $this->ipbwi->addSystemMessage('Error', $this->ipbwi->getLibLang('skinNotExist'), 'Located in file <strong>' . __FILE__ . '</strong> at class <strong>' . __CLASS__ . '</strong> in function <strong>' . __FUNCTION__ . '</strong> on line #<strong>' . __LINE__ . '</strong>');
            return false;
        }
    }
}

?>