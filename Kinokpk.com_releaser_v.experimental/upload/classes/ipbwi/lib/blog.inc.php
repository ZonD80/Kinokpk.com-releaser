<?php
/**
 * @author            Matthias Reuter ($LastChangedBy: matthias $)
 * @version            $LastChangedDate: 2009-01-18 03:52:31 +0000 (So, 18 Jan 2009) $
 * @package            blog
 * @copyright        2007-2010 IPBWI development team
 * @link            http://ipbwi.com/examples/topic.php
 * @since            2.0
 * @license            http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 */
class ipbwi_blog extends ipbwi
{
    private $ipbwi = null;
    public $installed = false;
    public $online = false;

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

        // check if IP.gallery is installed
        $query = $this->ipbwi->ips_wrapper->DB->query('SELECT conf_value,conf_default FROM ' . $this->ipbwi->board['sql_tbl_prefix'] . 'core_sys_conf_settings WHERE conf_key="blog_online"');
        if ($this->ipbwi->ips_wrapper->DB->getTotalRows($query) != 0) {
            $data = $this->ipbwi->ips_wrapper->DB->fetch($query);
            // retrieve Gallery URL
            $this->online = (($data['conf_value'] != '') ? $data['conf_value'] : $data['conf_default']);
            $this->installed = true;
        }
    }

    /**
     * @desc            lists latest Blog Entries from IP.blog
     * @param    mixed    $blogIDs The blog IDs where the entries should be retrieved from (array-list or int) Use '*', leave empty or set to false for entries from all blogs)
     * @param    array    $settings optional query settings. Settings allowed: limit and start
     * + int start = Default: 0
     * + int limit = Default: 15
     * @return    array    Blog-Entry-Informations as multidimensional array
     * @author            Matthias Reuter
     * @since            2.04
     */
    public function getLatestList($blogIDs = false, $settings = array())
    {
        if ($this->installed === true) {
            if (is_array($blogIDs)) {
                // todo
            } elseif ($blogIDs == '*') {
                $viewable = $this->getViewable();
                if (isset($viewable[1])) {
                    $viewable[0] = '0';
                }
                $blogQuery = ' AND (e.blog_id="' . implode('" OR e.blog_id="', $viewable) . '")';
            } elseif (intval($blogIDs) != 0) {
                $blogQuery = ' AND e.blog_id="' . $blogIDs . '"';
            } else {
                $blogQuery = false;
            }
            if (empty($settings['start'])) {
                $settings['start'] = 0;
            }
            if (empty($settings['limit'])) {
                $settings['limit'] = 15;
            }

            // get latest blog entries
            $query = $this->ipbwi->ips_wrapper->DB->query('SELECT e.*,b.* FROM ' . $this->ipbwi->board['sql_tbl_prefix'] . 'blog_entries e LEFT JOIN ' . $this->ipbwi->board['sql_tbl_prefix'] . 'blog_blogs b ON (b.blog_id=e.blog_id) WHERE e.entry_status="published"' . $blogQuery . ' ORDER BY e.entry_id DESC LIMIT ' . intval($settings['start']) . ',' . intval($settings['limit']));
            if ($this->ipbwi->ips_wrapper->DB->getTotalRows($query) == 0) {
                return false;
            }
            $data = array();
            while ($row = $this->ipbwi->ips_wrapper->DB->fetch($query)) {
                $row['entry_author_name'] = $this->ipbwi->properXHTML($row['entry_author_name']);
                $row['entry_name'] = $this->ipbwi->properXHTML($row['entry_name']);
                $row['entry'] = $this->ipbwi->properXHTML($row['entry']);
                $row['entry_edit_name'] = $this->ipbwi->properXHTML($row['entry_edit_name']);
                $row['blog_name'] = $this->ipbwi->properXHTML($row['blog_name']);
                $row['blog_desc'] = $this->ipbwi->properXHTML($row['blog_desc']);
                $data[] = $row;
            }
            return $data;
        } else {
            return false;
        }
    }
}

?>