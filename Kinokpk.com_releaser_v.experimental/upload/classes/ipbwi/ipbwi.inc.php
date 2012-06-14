<?php
/**
 * @desc            This file loads all IPBWI functions. Include this file to your
 *                     php-scripts and load the ipbwi-class to use the functions.
 * @author            Matthias Reuter ($LastChangedBy: matthias $)
 * @version            $LastChangedDate: 2009-08-26 19:49:26 +0200 (Mi, 26 Aug 2009) $
 * @package            IPBWI
 * @copyright        2007-2010 IPBWI development team
 * @link            http://ipbwi.com
 * @since            2.0
 * @license            http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 */
// load config file
require_once('config.inc.php');
// check if PHP version is 5 or higher
if (version_compare(PHP_VERSION, '5.0.0', '<')) {
    die('<p>ERROR: You need PHP 5 or higher to use IPBWI. Your current version is ' . PHP_VERSION . '</p>');
}
// check if board path is set
if (!defined('ipbwi_BOARD_PATH') || ipbwi_BOARD_PATH == '') {
    die('<p>ERROR: You have to define a board\'s path in your IPBWI config file.</p>');
}
// check if ipbwi path is set
if (!defined('ipbwi_ROOT_PATH') || ipbwi_ROOT_PATH == '') {
    die('<p>ERROR: You have to define the root path of your IPBWI installation in your IPBWI config file.</p>');
}
class ipbwi
{
    const                 VERSION = '3.1.5';
    const                 TITLE = 'IPBWI';
    const                 PROJECT_LEADER = 'Matthias Reuter';
    const                 DEV_TEAM = 'Matthias Reuter';
    const                 WEBSITE = 'http://ipbwi.com/';
    const                 DOCS = 'http://docs.ipbwi.com/';
    private static $lang = ipbwi_LANG;
    private static $libLang = array();
    protected static $ips = null;
    protected $common = array();
    private static $systemMessage = array();
    protected $board = array();
    public $DBlog = null;

    /**
     * @desc            Load's requested libraries dynamicly
     * @param    string    $name library-name
     * @return            class object of the requested library
     * @author            Matthias Reuter
     * @since            2.0
     * @ignore
     */
    public function __get($name)
    {
        if (!defined('IPBWI_INCORRECT_BOARD_PATH')) {
            if (file_exists(ipbwi_ROOT_PATH . 'lib/' . $name . '.inc.php')) {
                require_once(ipbwi_ROOT_PATH . 'lib/' . $name . '.inc.php');
                $classname = 'ipbwi_' . $name;
                $this->$name = new $classname($this);
                return $this->$name;
            } else {
                die('Class ' . $name . ' could not be loaded (tried to load class-file ' . ipbwi_ROOT_PATH . 'lib/' . $name . '.inc.php' . ')');
            }
        }
    }

    /**
     * @desc            Loads and checks different vars when class is initiating
     * @author            Matthias Reuter
     * @since            2.0
     * @ignore
     */
    public function __construct()
    {
        // check for DB prefix
        if (ipbwi_DB_prefix == '') {
            define('ipbwi_DB_prefix', 'ipbwi_');
        }
        if (defined('ipbwi_LANG')) {
            self::setLang(ipbwi_LANG);
        } else {
            self::setLang('en');
        }

        // initialize IP.board Interface
        require_once(ipbwi_ROOT_PATH . 'lib/ips_wrapper.inc.php');

        if (!defined('IPBWI_INCORRECT_BOARD_PATH')) {
            $this->ips_wrapper = new ipbwi_ips_wrapper();

            if (defined('ipbwi_COOKIE_DOMAIN') && ipbwi_COOKIE_DOMAIN != '') {
                $this->board['cookie_domain'] = ipbwi_COOKIE_DOMAIN;
                $this->ips_wrapper->settings['cookie_domain'] = ipbwi_COOKIE_DOMAIN;
                ipsRegistry::$settings['cookie_domain'] = ipbwi_COOKIE_DOMAIN;
            }
            ipsRegistry::cache()->updateCacheWithoutSaving('settings', ipsRegistry::$settings);

            // retrieve common vars
            $this->board = $this->ips_wrapper->settings;
            $this->board['version'] = $this->ips_wrapper->caches['app_cache']['core']['app_version'];
            $this->board['version_long'] = $this->ips_wrapper->caches['app_cache']['core']['app_long_version'];
            $this->board['url'] = str_replace('?', '', $this->ips_wrapper->settings['board_url']) . '/';
            $this->board['name'] = $this->ips_wrapper->settings['board_name'];
            $this->board['basedir'] = ipbwi_BOARD_PATH;
            $this->board['upload_dir'] = $this->ips_wrapper->settings['upload_dir'] . '/';
            $this->board['upload_url'] = $this->ips_wrapper->settings['upload_url'] . '/';
            $this->board['home_name'] = $this->ips_wrapper->settings['home_name'];
            $this->board['home_url'] = $this->ips_wrapper->settings['home_url'] . '/';
            $this->board['emo_url'] = str_replace('<#EMO_DIR#>', 'default', $this->ips_wrapper->settings['emoticons_url']) . '/';
            $this->board['form_hash'] = ipsRegistry::member()->form_hash;
        }
    }

    public function __destruct()
    {
    }

    /**
     * @desc            Set's current SDK language
     * @param    string    $lang language-name
     * @return            true if language was loaded, otherwise false
     * @author            Matthias Reuter
     * @since            2.0
     */
    public static function setLang($lang)
    {
        $libLang = array();
        if (file_exists(ipbwi_ROOT_PATH . 'lib/lang/' . $lang . '.inc.php')) {
            if (include(ipbwi_ROOT_PATH . 'lib/lang/' . $lang . '.inc.php')) {
//					ipbwi_OVERWRITE_LOCAL
//					ipbwi_OVERWRITE_CHARSET
                if (ipbwi_UTF8) {
                    $encoding = 'UTF-8';
                }
                if (defined('ipbwi_OVERWRITE_LOCAL') && ipbwi_OVERWRITE_LOCAL !== false) {
                    $local = ipbwi_OVERWRITE_LOCAL;
                }
                if (defined('ipbwi_OVERWRITE_ENCODING') && ipbwi_OVERWRITE_ENCODING !== false) {
                    $encoding = ipbwi_OVERWRITE_ENCODING;
                }
                @setlocale(LC_ALL, "$local.$encoding");

                // Change $this->lang,
                self::$lang = $lang;
                self::$libLang = $libLang;
                return true;
            } else {
                // Can't include it. Return false.
                self::addSystemMessage('Error', 'Language-File <strong>' . $lang . '</strong> exists, but can\'t be loaded');
                return false;
            }
        } else {
            // Doesn't exist. Invalid Language.
            self::addSystemMessage('Error', 'Language-File <strong>' . $lang . '</strong> does not exist.');
            return false;
        }
    }

    /**
     * @desc            gets the language-var from actual requested native language-bit
     * @param    string    $var language-var-name
     * @return            native language bit or error msg
     * @author            Matthias Reuter
     * @since            2.0
     */
    public function getLibLang($var = false)
    {
        if (isset($var)) {
            if (isset(self::$libLang[$var])) {
                if (defined('ipbwi_OVERWRITE_ENCODING') && ipbwi_OVERWRITE_ENCODING != '') {
                    return iconv('ISO-8859-1', ipbwi_OVERWRITE_ENCODING, self::$libLang[$var]);
                } else {
                    return self::$libLang[$var];
                }
            } else {
                return 'The requested libLang <strong>' . $var . '</strong> is not defined.';
            }
        } else {
            return self::libLang;
        }
    }

    /**
     * @desc            gets the requested board-var
     * @param    string    $var board-var-name
     * @return            board-var-value, returns false if not exists
     * @author            Matthias Reuter
     * @since            2.0
     */
    public function getBoardVar($var)
    {
        if (isset($this->board[$var])) {
            return $this->board[$var];
        } else {
            return false;
        }
    }

    /**
     * @desc            informations about the current IPBWI and PHP installation
     * @return    string    HTML-code including the informations
     * @author            Matthias Reuter
     * @since            2.0
     */
    public function sysInfo()
    {
        if ($this->member->isAdmin()) {
            ob_start();
            phpinfo();
            $phpinfo = ob_get_clean();
            return '
				<div class="center">
					<table border="0" cellpadding="3" width="600">
						<tr class="h"><td><h1 class="p">' . self::TITLE . '</h1></td></tr>
					</table><br />
					<table border="0" cellpadding="3" width="600">
						<tr><td class="e">Default Language:</td><td class="v">' . ipbwi_LANG . '</td></tr>
						<tr><td class="e">Current Language:</td><td class="v">' . self::$lang . '</td></tr>
						<tr><td class="e">IPBWI Version:</td><td class="v">' . self::VERSION . '</td></tr>
						<tr><td class="e">IPBWI Website:</td><td class="v">' . self::WEBSITE . '</td></tr>
						<tr><td class="e">Project Leader:</td><td class="v">' . self::PROJECT_LEADER . '</td></tr>
						<tr><td class="e">Development Team:</td><td class="v">' . self::DEV_TEAM . '</td></tr>
					</table><br />
				</div>
				<div class="center">
					<table border="0" cellpadding="3" width="600">
						<tr class="h"><td><h1 class="p">Invision Power Board</h1></td></tr>
					</table><br />
					<table border="0" cellpadding="3" width="600">
						<tr><td class="e">Version</td><td class="v">' . $this->getBoardVar('version') . '</td></tr>
						<tr><td class="e">Detailed Version</td><td class="v">' . $this->getBoardVar('version_long') . '</td></tr>
						<tr><td class="e">URL</td><td class="v">' . $this->getBoardVar('url') . '</td></tr>
						<tr><td class="e">Name</td><td class="v">' . $this->getBoardVar('name') . '</td></tr>
						<tr><td class="e">Base Directory</td><td class="v">' . $this->getBoardVar('basedir') . '</td></tr>
						<tr><td class="e">Upload Directory</td><td class="v">' . $this->getBoardVar('upload_dir') . '</td></tr>
						<tr><td class="e">Upload URL</td><td class="v">' . $this->getBoardVar('upload_url') . '</td></tr>
						<tr><td class="e">Home Name</td><td class="v">' . $this->getBoardVar('home_name') . '</td></tr>
						<tr><td class="e">Home URL</td><td class="v">' . $this->getBoardVar('home_url') . '</td></tr>
					</table><br />
				</div>
				' . $phpinfo;
        } else {
            $this->addSystemMessage('Error', $this->getLibLang('noAdmin'), 'Located in file <strong>' . __FILE__ . '</strong> at class <strong>' . __CLASS__ . '</strong> in function <strong>' . __FUNCTION__ . '</strong> on line #<strong>' . __LINE__ . '</strong>');
            return false;
        }
    }

    /**
     * @desc            adds a system message
     * @param    string    $level Message-Level, common levels: Notice, Error, Hidden
     * @param    string    $message Message-Content
     * @return    bool    this function always returns true
     * @author            Matthias Reuter
     * @since            2.0
     */
    public static function addSystemMessage($level, $message, $location = false)
    {
        self::$systemMessage[$level][] = array(
            'message' => $message,
            'location' => $location
        );
        return true;
    }

    /**
     * @desc            prints system messages
     * @param    string    $level Message-Level, common levels: Notice, Error, Hidden
     * @return    true    HTML-Code containing requested messages
     * @author            Matthias Reuter
     * @since            2.0
     */
    public function printSystemMessages($level = false, $simple = false)
    {
        if ($simple === true) {
            $output = '';
            foreach (self::$systemMessage as $k => $levels) {
                if (($k == 'Hidden' && $this->member->isAdmin()) || $k != 'Hidden') {
                    $i = 1;
                    foreach ($levels as $m) {
                        $output .= '<strong>' . $this->getLibLang('sysMsg_' . $k) . '</strong> ' . $m['message'] . (($i < count($levels) ? '<br />' : ''));
                        $i++;
                    }
                }
            }
        } else {
            $output = '<div class="center"><table border="0" cellpadding="3">';
            // specific message-level requested, print only this
            if (isset($level) && is_string($level) && is_array(self::$systemMessage) && count(self::$systemMessage[$level]) > 0) {
                if ($level == 'Hidden' && !$this->member->isAdmin()) {
                    return false;
                } elseif ($this->member->isAdmin()) {
                    $hLocation = '<th>Location</th>';
                } else {
                    $hLocation = false;
                }
                $output .= '<tr class="h"><th colspan="2">IPBWI ' . $level . 's</th>' . $hLocation . '</tr>';
                $i = 1;
                foreach (self::$systemMessage[$level] as $m) {
                    if ($this->member->isAdmin()) {
                        $location = '<td class="v">' . $m['location'] . '</td>';
                    } else {
                        $location = false;
                    }
                    $output .= '<tr><td class="e">' . $this->getLibLang('sysMsg_' . $level) . ' #' . $i . ':</td><td class="v">' . $m['message'] . '</td>' . $location . '</tr>';
                    $i++;
                }
            } elseif (is_array(self::$systemMessage) && count(self::$systemMessage) > 0) {
                // print all messages
                $i = 1;
                foreach (self::$systemMessage as $k => $levels) {
                    if (($k == 'Hidden' && $this->member->isAdmin()) || $k != 'Hidden') {
                        if ($this->member->isAdmin()) {
                            $hLocation = '<th>Location</th>';
                        } else {
                            $hLocation = '';
                        }
                        $output .= '<tr class="h"><th colspan="2">IPBWI ' . $k . 's</th>' . $hLocation . '</tr>';
                        $location = '';
                        foreach ($levels as $m) {
                            if ($this->member->isAdmin()) {
                                $location = '<td class="v">' . $m['location'] . '</td>';
                            }
                            $output .= '<tr><td class="e">' . $this->getLibLang('sysMsg_' . $k) . ' #' . $i . ':</td><td class="v">' . $m['message'] . '</td>' . $location . '</tr>';
                            $i++;
                        }
                        $i = 1;
                    }
                }
            } else {
                return false;
            }
            $output .= '</table><br /></div>';
        }
        return $output;
    }

    /**
     * @desc            filtering html-strings, e.g. for db-queries
     * @param    string    $var html-string
     * @return            proper and safe string
     * @author            Matthias Reuter
     * @since            2.0
     */
    public function makeSafe($var)
    {
        $var = stripslashes($var);
        $var = trim($var);
        $search = array(
            '!',
            ' & ',
            '&#032;',
            "<br />\n",
        );
        $replace = array(
            '&#33;',
            ' &amp; ',
            ' ',
            '<br />',
        );
        $var = str_replace($search, $replace, $var);
        $search = array(
            '/\\\$/', // replace $-var
        );
        $replace = array(
            '&#036;',
        );
        $var = preg_replace($search, $replace, $var);
        if (defined('ipbwi_OVERWRITE_ENCODING') && ipbwi_OVERWRITE_ENCODING != '') {
            $var = iconv(ipbwi_OVERWRITE_ENCODING, 'ISO-8859-1', $var);
        } elseif (ipbwi_UTF8) {
            $var = iconv('ISO-8859-1', 'UTF-8', $var);
        }

        return $var;
    }

    /**
     * @desc            filtering HTML strings
     * @param    string    $var html-string
     * @return            proper XHTML string
     * @author            Matthias Reuter
     * @since            2.0
     */
    public function properXHTML($var)
    {
        $search = array(
            '<#EMO_DIR#>/',
            ' border="0"',
            ' target="_blank"',
            ' & '
        );
        $replace = array(
            $this->skin->emoDir(),
            '',
            '',
            ' &amp; '
        );
        $var = str_replace($search, $replace, $var);
        /*
                    $search = array(
                        '/ emoid=\"(.*)\"/U',
                    );
                    $replace = array(
                        '',
                    );
                    $var = preg_replace($search,$replace,$var);
        */
        if (defined('ipbwi_OVERWRITE_ENCODING') && ipbwi_OVERWRITE_ENCODING != '') {
            $var = iconv('ISO-8859-1', ipbwi_OVERWRITE_ENCODING, $var);
        } elseif (ipbwi_UTF8) {
            $var = iconv('UTF-8', 'ISO-8859-1', $var);
        }
        return $var;
    }

    /**
     * @desc            Returns textual/timestamp offsetted date by board
     *                     and by member offset and DST setting.
     * @param    int        $timeStamp Numeric representation of the time beeing formatted
     * @param    string    $dateFormat strftime() compliant format (see PHP manual)
     * @param    int        $noBoard true = Offset with Board Time firstly, default = false
     * @param    int        $noMember true = Bypass member's time offset and DST, default = false
     * @return    string    formatted, localized date
     * @author            Matthias Reuter
     * @author            Cow <khlo@global-centre.com>
     * @since            2.0
     */
    public function date($timeStamp = false, $dateFormat = '%A, %d. %B %Y @ %T', $noBoard = false, $noMember = false)
    {
        $info = $this->member->info();
        // If theres no timestamp make it current time using time()
        if (!$timeStamp) {
            $timeStamp = time();
        }
        // Offset with Board Time firstly, if enabled
        // Also Check no member offset

        if (!$noBoard) {
            if (!$noMember && empty($info['time_offset'])) {
                $timeStamp = $timeStamp + ($this->ips_wrapper->settings['time_offset'] * 3600);
            }
        }
        // Board Time Adjust
        if ($this->ips_wrapper->settings['time_adjust']) {
            $timeStamp = $timeStamp + ($this->ips_wrapper->settings['time_adjust'] * 60);
        }
        // If member has set an indiviual offset in the User CP
        // because they may be in a totally different country
        // using DST or whatever we can make those times affect it
        // across the whole website as well :D
        if ($this->member->isLoggedIn() && !$noMember) {
            if ($info['time_offset']) {
                $timeStamp = $timeStamp + ($info['time_offset'] * 3600);
            }
            if ($info['dst_in_use']) {
                $timeStamp = $timeStamp + 3600;
            }
        }
        if ($dateFormat) {
            $timeStamp = strftime($dateFormat, $timeStamp);
        }
        return $timeStamp;
    }
}

// start class
if (empty($ipbwi)) {
    $ipbwi = new ipbwi();
} else {
    die('<p>Error: You have to include and load IPBWI once only.</p>');
}
?>