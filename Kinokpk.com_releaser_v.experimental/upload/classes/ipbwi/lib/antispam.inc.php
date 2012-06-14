<?php
/**
 * @author            Matthias Reuter ($LastChangedBy: matthias $)
 * @version            $LastChangedDate: 2009-08-26 19:19:41 +0200 (Mi, 26 Aug 2009) $
 * @package            antispam
 * @copyright        2007-2010 IPBWI development team
 * @link            http://ipbwi.com/examples/attachment.php
 * @since            2.0
 * @license            http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 */
class ipbwi_antispam extends ipbwi
{
    private $ipbwi = null;
    private $captchaClass = null;
    private $captcha = null;
    private $uniqueId = null;
    private $recaptchaPrivateKey = ipbwi_RECAPTCHA_PRIVATE_KEY;
    private $recaptchaPublicKey = ipbwi_RECAPTCHA_PUBLIC_KEY;

    /**
     * @desc            Loads and checks different vars when class is initiating
     * @param    object    $ipbwi The ipbwi class object
     * @author            Matthias Reuter
     * @since            2.0
     * @ignore
     */
    public function __construct($ipbwi)
    {
        // loads common classes
        $this->ipbwi = $ipbwi;

        // loads recaptcha lib
        require_once(ipbwi_ROOT_PATH . 'lib/third_party/recaptchalib.inc.php');

        // loads IP.Board Captcha Class
        require_once(ipbwi_BOARD_PATH . 'ips_kernel/classCaptcha.php');
        $this->captcha = new classCaptcha($this->ipbwi->ips_wrapper->registry, $this->ipbwi->ips_wrapper->settings['bot_antispam_type']);

        // if the captcha mode is auto, and the board uses recaptcha we have
        // to use the public and private key that is set in the board
        if (ipbwi_CAPTCHA_MODE == 'auto') {
            if ($this->ipbwi->ips_wrapper->settings['bot_antispam_type'] == 'recaptcha') {
                $this->recaptchaPrivateKey = $this->ipbwi->ips_wrapper->settings['recaptcha_private_key'];
                $this->recaptchaPublicKey = $this->ipbwi->ips_wrapper->settings['recaptcha_public_key'];
            }
        }
    }

    /**
     * @desc            Returns the Captcha HTML Code
     * @param    string    $ajaxUrl The URL that should be called to renew the captcha Image (only for GD Based captchas)
     * @return    string    captcha html code
     * @author            Jan Ecker
     * @since            2.0
     */
    public function getHTML($ajaxUrl = false)
    {
        if (isset($ajaxUrl)) {
            $ajaxUrl = urlencode($ajaxUrl);
        }
        switch (ipbwi_CAPTCHA_MODE) {
            case 'gd':
                return $this->getGdHtml($ajaxUrl);
                break;
            case 'recaptcha':
                return $this->getRecaptchaHtml();
                break;
            case 'auto':
                if ($this->ipbwi->ips_wrapper->settings['bot_antispam_type'] == 'recaptcha') {
                    return $this->getRecaptchaHtml();
                } elseif ($this->ipbwi->ips_wrapper->settings['bot_antispam_type'] == 'none') {
                    return;
                } else {
                    return $this->getGdHtml($ajaxUrl);
                }
                break;
            case 'none':
                return;
                break;
            default:
                die('Wrong config value, please check ipbwi_CAPTCHA_MODE');
        }
    }

    /**
     * @desc            Checks weather the entered string was correct
     * @return    bool    True if the entered string was correct, otherwise false
     * @author            Jan Ecker
     * @since            2.0
     */
    public function validate()
    {
        $result = false;
        // Checks which captcha mode was used and than validate it
        if (isset($_POST['ipbwiCaptchaString']) && isset($_POST['ipbwiCaptchaUniqueId'])) {
            $result = $this->validateGd($_POST['ipbwiCaptchaUniqueId'], $_POST['ipbwiCaptchaString']);
        } elseif (isset($_POST['recaptcha_response_field'])) {
            $result = $this->validateRecaptcha($_POST['recaptcha_response_field'], $_POST['recaptcha_challenge_field']);
        }
        return $result;
    }

    /**
     * @desc            Generates a new GD Based Captcha
     * @return    string    The unique id of the new GD based captcha
     * @author            Jan Ecker
     * @since            2.0
     */
    public function renewGdImage()
    {
        // Clean up database
        $this->clearGdDatabase();
        $captchaString = $this->createRandomString();
        $uniqueId = $this->createUniqueId();
        $this->uniqueId = $uniqueId;
        // Save the new captcha data in Database
        $this->ipbwi->ips_wrapper->DB->insert('captcha', array('captcha_unique_id' => $uniqueId, 'captcha_string' => $captchaString, 'captcha_ipaddress' => $_SERVER['REMOTE_ADDR'], 'captcha_date' => time()));
        return $uniqueId;
    }

    /**
     * @desc            Checks if the entered string in the recaptcha was correct
     * @param    string    $responseField The entered captcha string
     * @param    string    $challengeField The unique id of the captcha image
     * @return    bool    True if the entered string was correct, otherwise false
     * @author            Jan Ecker
     * @since            2.0
     */
    private function validateRecaptcha($responseField, $challengeField)
    {
        // Send the entered string to the recaptcha server and listen if it was correct
        $resp = recaptcha_check_answer($this->recaptchaPrivateKey, $_SERVER['REMOTE_ADDR'], $challengeField, $responseField);
        if ($resp->is_valid) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @desc            Checks if the entered string in the GD Based captcha was correct
     * @param    string    $uniqueId The unique id of the GD based captcha
     * @param    string    $userCaptchaString The entered captcha string
     * @return    bool    True if the entered String was correct, otherwise false
     * @author            Jan Ecker
     * @since            2.0
     */
    private function validateGd($uniqueId, $userCaptchaString)
    {
        // Get the correct Captcha String from the database, using the unique id
        $this->ipbwi->ips_wrapper->DB->query('SELECT captcha_string FROM ' . $this->ipbwi->board['sql_tbl_prefix'] . 'captcha WHERE captcha_unique_id="' . addslashes($uniqueId) . '"');
        $row = $this->ipbwi->ips_wrapper->DB->fetch();
        if (isset($row) && !empty($row)) {
            // Is not case sesetive! Maybe change later?
            if (strtoupper($row['captcha_string']) == strtoupper($userCaptchaString)) {
                // Captcha was correct! Clear seassion and return true
                $this->clearGdDatabase();
                return true;
            } else {
                // Captcha was not correct! Report the error
                $this->ipbwi->addSystemMessage('Error', $this->ipbwi->getLibLang('badKey'), 'Located in file ' . __FILE__ . ' at class ' . __CLASS__ . ' in function ' . 'Located in file <strong>' . __FILE__ . '</strong> at class <strong>' . __CLASS__ . '</strong> in function <strong>' . __FUNCTION__ . '</strong> on line #<strong>' . __LINE__ . '</strong>');
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * @desc            Get the HTML code from the recaptcha server and returns it
     * @return    string    The HTML code to display the recaptcha
     * @author            Jan Ecker
     * @since            2.0
     */
    private function getRecaptchaHtml()
    {
        $error = null;
        // Get the HTML data from the recaptcha server
        return recaptcha_get_html($this->recaptchaPublicKey, $error);
    }

    /**
     * @desc            Creates the HTML code for the GD Based captchas and returns it
     * @param    string    $ajaxUrl If given it will additionally return ajax code to refresh the GD based Captcha
     * @return    string    The HTML code to display the GD based captcha
     * @author            Jan Ecker
     * @author            Matthias Reuter
     * @since            2.0
     */
    private function getGdHtml($ajaxUrl = false)
    {
        // Create a new image and get the unique id
        $uniqueId = $this->renewGdImage();

        // ajax support for on demand reloading of anti_spam-Image
        $ajaxCode = false;
        $ajaxLink = false;
        if (isset($ajaxUrl) && !empty($ajaxUrl)) {
            $ajaxCode =
                <<<AJAXCODE
                	<script type="text/javascript">
		var keycode_id = '{$uniqueId}';

		function get_new_hash(){
			var url = unescape('{$ajaxUrl}');
			var xmlHttp = window.ActiveXObject ? new ActiveXObject("Microsoft.XMLHTTP") : new XMLHttpRequest();
			if(xmlHttp){
				xmlHttp.open('GET', url, true);
				xmlHttp.onreadystatechange = function (){
					if(xmlHttp.readyState == 4){
						keycode_id = xmlHttp.responseText;
						document.getElementById("anti_spam_image").src = unescape('{$this->ipbwi->getBoardVar('url')}index.php%3Fapp%3Dcore%26module%3Dglobal%26section%3Dcaptcha&do%3Dshowimage%26captcha_unique_id%3D') + keycode_id;
						document.getElementById("anti_spam_session_id").value = keycode_id;
					}
				}
				xmlHttp.send(null);
			}
		}
	</script>
AJAXCODE;
            $ajaxLink = ' onclick="get_new_hash();" style="cursor:pointer;" title="Click here to refresh Spam-Image"';
        }
        $html = '<p>' . $ajaxCode . '<img' . $ajaxLink . ' src="' . $this->ipbwi->getBoardVar('url') . 'index.php?app=core&module=global&section=captcha&do=showimage&captcha_unique_id=' . $uniqueId . '" alt="Code Bit" id="anti_spam_image" /></p>';
        $html .= '<p><input type="hidden" name="ipbwiCaptchaUniqueId" value="' . $this->uniqueId . '" id="anti_spam_session_id" /></p>';
        $html .= '<p><strong>Insert Code</strong></p>';
        $html .= '<p><input type="text" name="ipbwiCaptchaString" value="" id="keycode" /></p>';
        return $html;
    }

    /**
     * @desc            Clears the database for the GD Based captchas
     * @param    string    $ajaxUrl If given it will additionally return ajax code to refresh the GD based Captcha
     * @return    bool    Always returns true
     * @author            Jan Ecker
     * @since            2.0
     */
    private function clearGdDatabase()
    {
        $time = time() - 60 * 3600;
        // Delete all old entries and the seassion of the actual user
        $this->ipbwi->ips_wrapper->DB->query('DELETE FROM ' . $this->ipbwi->board['sql_tbl_prefix'] . 'captcha WHERE captcha_date < "' . $time . '" OR captcha_ipaddress = "' . $_SERVER['REMOTE_ADDR'] . '"');
        return true;
    }

    /**
     * @desc            Generates and returns an unique id
     * @return    string    The generated unique id
     * @author            Jan Ecker
     * @since            2.0
     */
    private function createUniqueId()
    {
        srand(microtime() * 1000000);
        return md5(uniqid(rand(), true));
    }

    /**
     * @desc            Generates a random string and returns it
     * @param    int        $length The length of the generated random string
     * @return    string    The generated string
     * @author            Jan Ecker
     * @since            2.0
     */
    private function createRandomString($length = 6)
    {
        srand(microtime() * 1000000);
        $captchaString = '';
        // Generate an array of all letters and numbers
        $chars = array();
        $smallLetter = 'a';
        $bigLetter = 'A';
        for ($i = 0; $i <= 25; $i++) {
            $chars[] = $smallLetter++;
            $chars[] = $bigLetter++;
        }
        for ($i = 0; $i <= 9; $i++) {
            $chars[] = $i;
        }
        // Generate a string of the given length with randomly taken letters and numbers
        for ($i = 0; $i < $length; $i++) {
            $index = rand(0, count($chars) - 1);
            $captchaString .= $chars[$index];
        }
        return $captchaString;
    }


    public function qandaGet()
    {
        $question = $this->ipbwi->ips_wrapper->DB->buildAndFetch(array('select' => '*', 'from' => 'question_and_answer', 'order' => $this->ipbwi->ips_wrapper->DB->buildRandomOrder(), 'limit' => array(1)));
        if ($question AND count($question)) {
            return $question;
        } else {
            return false;
        }
    }

    public function qandaCheck()
    {
        $id = $_POST['qanda_id'];
        $answerUser = $_POST['qa_answer'];
        $answers = $this->ipbwi->ips_wrapper->DB->buildAndFetch(array('select' => 'qa_answers', 'from' => 'question_and_answer', 'where' => 'qa_id=' . intval($id), 'order' => $this->ipbwi->ips_wrapper->DB->buildRandomOrder(), 'limit' => array(1)));
        if ($answers AND count($answers)) {
            foreach (explode("\n", str_replace("\r", "", $answers['qa_answers'])) as $answer) {
                if (strtolower(trim($answerUser)) == strtolower(trim($answer))) {
                    return true;
                }
            }
            return false;
        } else {
            return false;
        }
    }
}

?>