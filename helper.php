<?php
/**
 * @package iFlyChat
 * @version 1.0.0
 * @copyright Copyright (C) 2014 iFlyChat. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @author iFlyChat Team
 * @link https://iflychat.com
 */
class IflychatHelper {
    private $timers;


    // public function check(){

    //     return 'check';
    // }
    public function iflychat_check_chat_admin(){

        if(OW_User::getInstance()->isAdmin()){
            return TRUE;
        }else
            return FALSE;
    }
    public function roleArray() {
        $arr = BOL_AuthorizationRoleDao::getInstance()->findAll();
        $roleArr=array();
        for($i=0;$i<sizeof($arr);$i++){
            $roleArr +=  array($arr[$i]->id => $arr[$i]->name);

        }
        return $roleArr;
    }
    private function iflychat_timer_read($name) {
        //global $this->timers;

        if (isset($this->timers[$name]['start'])) {
            $stop = microtime(TRUE);
            $diff = round(($stop - $this->timers[$name]['start']) * 1000, 2);

            if (isset($this->timers[$name]['time'])) {
                $diff += $this->timers[$name]['time'];
            }
            return $diff;
        }
        return $this->timers[$name]['time'];
    }
    private function iflychat_timer_start($name) {
        //global $this->timers;

        $this->timers[$name]['start'] = microtime(TRUE);
        $this->timers[$name]['count'] = isset($this->timers[$name]['count']) ? ++$this->timers[$name]['count'] : 1;
    }

    public function iflychat_extended_http_request($url, array $options = array()) {
        $result = new stdClass();
        // Parse the URL and make sure we can handle the schema.
        $uri = @parse_url($url);
        if ($uri == FALSE) {
            $result->error = 'unable to parse URL';
            $result->code = -1001;
            return $result;
        }

        if (!isset($uri['scheme'])) {
            $result->error = 'missing schema';
            $result->code = -1002;
            return $result;
        }

        $this->iflychat_timer_start(__FUNCTION__);

        // Merge the default options.
        $options += array(
            'headers' => array(),
            'method' => 'POST',
            'data' => NULL,
            'max_redirects' => 3,
            'timeout' => 30.0,
            'context' => NULL,
        );

        // Merge the default headers.
        $options['headers'] += array(
            'User-Agent' => 'Drupal (+http://drupal.org/)',
        );

        // stream_socket_client() requires timeout to be a float.
        $options['timeout'] = (float) $options['timeout'];

        // Use a proxy if one is defined and the host is not on the excluded list.
        $proxy_server = '';
        if ($proxy_server && _drupal_http_use_proxy($uri['host'])) {
            // Set the scheme so we open a socket to the proxy server.
            $uri['scheme'] = 'proxy';
            // Set the path to be the full URL.
            $uri['path'] = $url;
            // Since the URL is passed as the path, we won't use the parsed query.
            unset($uri['query']);

            // Add in username and password to Proxy-Authorization header if needed.
            if ($proxy_username = '') {
                $proxy_password = '';
                $options['headers']['Proxy-Authorization'] = 'Basic ' . base64_encode($proxy_username . (!empty($proxy_password) ? ":" . $proxy_password : ''));
            }
            // Some proxies reject requests with any User-Agent headers, while others
            // require a specific one.
            $proxy_user_agent = '';
            // The default value matches neither condition.
            if ($proxy_user_agent === NULL) {
                unset($options['headers']['User-Agent']);
            }
            elseif ($proxy_user_agent) {
                $options['headers']['User-Agent'] = $proxy_user_agent;
            }
        }

        switch ($uri['scheme']) {
            case 'proxy':
                // Make the socket connection to a proxy server.
                $socket = 'tcp://' . $proxy_server . ':' . 8080;
                // The Host header still needs to match the real request.
                $options['headers']['Host'] = $uri['host'];
                $options['headers']['Host'] .= isset($uri['port']) && $uri['port'] != 80 ? ':' . $uri['port'] : '';
                break;

            case 'http':
            case 'feed':
                $port = isset($uri['port']) ? $uri['port'] : 80;
                $socket = 'tcp://' . $uri['host'] . ':' . $port;
                // RFC 2616: "non-standard ports MUST, default ports MAY be included".
                // We don't add the standard port to prevent from breaking rewrite rules
                // checking the host that do not take into account the port number.
                $options['headers']['Host'] = $uri['host'] . ($port != 80 ? ':' . $port : '');
                break;

            case 'https':
                // Note: Only works when PHP is compiled with OpenSSL support.
                $port = isset($uri['port']) ? $uri['port'] : 443;
                $socket = 'ssl://' . $uri['host'] . ':' . $port;
                $options['headers']['Host'] = $uri['host'] . ($port != 443 ? ':' . $port : '');
                break;

            default:
                $result->error = 'invalid schema ' . $uri['scheme'];
                $result->code = -1003;
                return $result;
        }

        if (empty($options['context'])) {
            $fp = @stream_socket_client($socket, $errno, $errstr, $options['timeout']);
        }
        else {
            // Create a stream with context. Allows verification of a SSL certificate.
            $fp = @stream_socket_client($socket, $errno, $errstr, $options['timeout'], STREAM_CLIENT_CONNECT, $options['context']);
        }

        // Make sure the socket opened properly.
        if (!$fp) {
            // When a network error occurs, we use a negative number so it does not
            // clash with the HTTP status codes.
            $result->code = -$errno;
            $result->error = trim($errstr) ? trim($errstr) : 'Error opening socket @socket';

            // Mark that this request failed. This will trigger a check of the web
            // server's ability to make outgoing HTTP requests the next time that
            // requirements checking is performed.
            // See system_requirements().
            //variable_set('drupal_http_request_fails', TRUE);

            return $result;
        }

        // Construct the path to act on.
        $path = isset($uri['path']) ? $uri['path'] : '/';
        if (isset($uri['query'])) {
            $path .= '?' . $uri['query'];
        }

        // Only add Content-Length if we actually have any content or if it is a POST
        // or PUT request. Some non-standard servers get confused by Content-Length in
        // at least HEAD/GET requests, and Squid always requires Content-Length in
        // POST/PUT requests.
        $content_length = strlen($options['data']);
        if ($content_length > 0 || $options['method'] == 'POST' || $options['method'] == 'PUT') {
            $options['headers']['Content-Length'] = $content_length;
        }

        // If the server URL has a user then attempt to use basic authentication.
        if (isset($uri['user'])) {
            $options['headers']['Authorization'] = 'Basic ' . base64_encode($uri['user'] . (isset($uri['pass']) ? ':' . $uri['pass'] : ''));
        }

        // If the database prefix is being used by SimpleTest to run the tests in a copied
        // database then set the user-agent header to the database prefix so that any
        // calls to other Drupal pages will run the SimpleTest prefixed database. The
        // user-agent is used to ensure that multiple testing sessions running at the
        // same time won't interfere with each other as they would if the database
        // prefix were stored statically in a file or database variable.
        $test_info = &$GLOBALS['drupal_test_info'];
        if (!empty($test_info['test_run_id'])) {
            $options['headers']['User-Agent'] = drupal_generate_test_ua($test_info['test_run_id']);
        }

        $request = $options['method'] . ' ' . $path . " HTTP/1.0\r\n";
        foreach ($options['headers'] as $name => $value) {
            $request .= $name . ': ' . trim($value) . "\r\n";
        }
        $request .= "\r\n" . $options['data'];
        $result->request = $request;
        // Calculate how much time is left of the original timeout value.
        $timeout = $options['timeout'] - $this->iflychat_timer_read(__FUNCTION__) / 1000;
        if ($timeout > 0) {
            stream_set_timeout($fp, floor($timeout), floor(1000000 * fmod($timeout, 1)));
            fwrite($fp, $request);
        }

        // Fetch response. Due to PHP bugs like http://bugs.php.net/bug.php?id=43782
        // and http://bugs.php.net/bug.php?id=46049 we can't rely on feof(), but
        // instead must invoke stream_get_meta_data() each iteration.
        $info = stream_get_meta_data($fp);
        $alive = !$info['eof'] && !$info['timed_out'];
        $response = '';

        while ($alive) {
            // Calculate how much time is left of the original timeout value.
            $timeout = $options['timeout'] - $this->iflychat_timer_read(__FUNCTION__) / 1000;
            if ($timeout <= 0) {
                $info['timed_out'] = TRUE;
                break;
            }
            stream_set_timeout($fp, floor($timeout), floor(1000000 * fmod($timeout, 1)));
            $chunk = fread($fp, 1024);
            $response .= $chunk;
            $info = stream_get_meta_data($fp);
            $alive = !$info['eof'] && !$info['timed_out'] && $chunk;
        }
        fclose($fp);

        if ($info['timed_out']) {
            $result->code = HTTP_REQUEST_TIMEOUT;
            $result->error = 'request timed out';
            return $result;
        }
        // Parse response headers from the response body.
        // Be tolerant of malformed HTTP responses that separate header and body with
        // \n\n or \r\r instead of \r\n\r\n.
        list($response, $result->data) = preg_split("/\r\n\r\n|\n\n|\r\r/", $response, 2);
        $response = preg_split("/\r\n|\n|\r/", $response);

        // Parse the response status line.
        list($protocol, $code, $status_message) = explode(' ', trim(array_shift($response)), 3);
        $result->protocol = $protocol;
        $result->status_message = $status_message;

        $result->headers = array();

        // Parse the response headers.
        while ($line = trim(array_shift($response))) {
            list($name, $value) = explode(':', $line, 2);
            $name = strtolower($name);
            if (isset($result->headers[$name]) && $name == 'set-cookie') {
                // RFC 2109: the Set-Cookie response header comprises the token Set-
                // Cookie:, followed by a comma-separated list of one or more cookies.
                $result->headers[$name] .= ',' . trim($value);
            }
            else {
                $result->headers[$name] = trim($value);
            }
        }

        $responses = array(
            100 => 'Continue',
            101 => 'Switching Protocols',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Time-out',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Large',
            415 => 'Unsupported Media Type',
            416 => 'Requested range not satisfiable',
            417 => 'Expectation Failed',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Time-out',
            505 => 'HTTP Version not supported',
        );
        // RFC 2616 states that all unknown HTTP codes must be treated the same as the
        // base code in their class.
        if (!isset($responses[$code])) {
            $code = floor($code / 100) * 100;
        }
        $result->code = $code;

        switch ($code) {
            case 200: // OK
            case 304: // Not modified
                break;
            case 301: // Moved permanently
            case 302: // Moved temporarily
            case 307: // Moved temporarily
                $location = $result->headers['location'];
                $options['timeout'] -= $this->iflychat_timer_read(__FUNCTION__) / 1000;
                if ($options['timeout'] <= 0) {
                    $result->code = HTTP_REQUEST_TIMEOUT;
                    $result->error = 'request timed out';
                }
                elseif ($options['max_redirects']) {
                    // Redirect to the new location.
                    $options['max_redirects']--;
                    $result = $this->iflychat_extended_http_request($location, $options);
                    $result->redirect_code = $code;
                }
                if (!isset($result->redirect_url)) {
                    $result->redirect_url = $location;
                }
                break;
            default:
                $result->error = $status_message;
        }

        return $result;
    }

    public function iflychat_get_random_name() {

        $path = OW::getPluginManager()->getPlugin('iflychat')->getStaticUrl(). "guest_names/iflychat_guest_random_names.txt";
        $f_contents = file($path);
        $line = trim($f_contents[rand(0, count($f_contents) - 1)]);
        return $line;
    }

    public function iflychat_get_current_guest_name() {


        if(isset($_SESSION) && isset($_SESSION['iflychat_guest_name'])) {
            //if(!isset($_COOKIE) || !isset($_COOKIE['drupalchat_guest_name'])) {
            setrawcookie('iflychat_guest_name', rawurlencode($_SESSION['iflychat_guest_name']), time()+60*60*24*365);
            //}
        }
        else if(isset($_COOKIE) && isset($_COOKIE['iflychat_guest_name']) && isset($_COOKIE['iflychat_guest_session'])&& ($_COOKIE['iflychat_guest_session']==$this->iflychat_compute_guest_session($this->iflychat_get_current_guest_id()))) {
            $_SESSION['iflychat_guest_name'] = $this->check_plain($_COOKIE['iflychat_guest_name']);

        }
        else {
            if($this->params('iflychat_anon_use_name')==1) {

                $_SESSION['iflychat_guest_name'] = $this->check_plain($this->params('iflychat_anon_prefix') . ' ' . $this->iflychat_get_random_name());
            }
            else {
                $_SESSION['iflychat_guest_name'] = $this->check_plain($this->params('iflychat_anon_prefix') . time());
            }
            setrawcookie('iflychat_guest_name', rawurlencode($_SESSION['iflychat_guest_name']), time()+60*60*24*365);
        }
        return $_SESSION['iflychat_guest_name'];
    }

    public function iflychat_get_current_guest_id() {
        if(isset($_SESSION) && isset($_SESSION['iflychat_guest_id'])) {
            //if(!isset($_COOKIE) || !isset($_COOKIE['drupalchat_guest_id'])) {
            setrawcookie('iflychat_guest_id', rawurlencode($_SESSION['iflychat_guest_id']), time()+60*60*24*365);
            setrawcookie('iflychat_guest_session', rawurlencode($_SESSION['iflychat_guest_session']), time()+60*60*24*365);
            //}
        }
        else if(isset($_COOKIE) && isset($_COOKIE['iflychat_guest_id']) && isset($_COOKIE['iflychat_guest_session']) && ($_COOKIE['iflychat_guest_session']==$this->iflychat_compute_guest_session($_COOKIE['iflychat_guest_id']))) {
            $_SESSION['iflychat_guest_id'] = $this->check_plain($_COOKIE['iflychat_guest_id']);
            $_SESSION['iflychat_guest_session'] = $this->check_plain($_COOKIE['iflychat_guest_session']);
        }
        else {
            $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
            $iflychatId = time();
            for ($i = 0; $i < 5; $i++) {
                $iflychatId .= $characters[rand(0, strlen($characters) - 1)];
            }
            $_SESSION['iflychat_guest_id'] = $iflychatId;
            $_SESSION['iflychat_guest_session'] = $this->iflychat_compute_guest_session($_SESSION['iflychat_guest_id']);
            setrawcookie('iflychat_guest_id', rawurlencode($_SESSION['iflychat_guest_id']), time()+60*60*24*365);
            setrawcookie('iflychat_guest_session', rawurlencode($_SESSION['iflychat_guest_session']), time()+60*60*24*365);
        }
        return $_SESSION['iflychat_guest_id'];
    }

    public function iflychat_compute_guest_session($id) {

        return md5(substr($this->params('iflychat_external_api_key'), 0, 5) . $id);
    }

    public function check_plain($text) {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }

    public function iflychat_get_user_pic_url() {
        $url = '';

        if($this->params('iflychat_theme') == 1) {

            $iflychat_theme = 'light';
        }
        else {
            $iflychat_theme = 'dark';


        }
        $id = OW::getUser()->getId();
        $pUrl = BOL_AvatarService::getInstance()->getAvatarUrl($id);
        if(empty($pUrl)) {
            $url = OW::getPluginManager()->getPlugin('iflychat')->getStaticUrl() . 'themes/' . $iflychat_theme . '/images/default_avatar.png';
        }
        else  {
            $url = BOL_AvatarService::getInstance()->getAvatarUrl($id);
        }

        $pos = strpos($url, ':');
        if($pos !== false) {
            $url = substr($url, $pos+1);
        }
        return $url;
    }

    public function iflychat_get_user_profile_url() {

        $id = OW::getUser()->getId();
        $upl = BOL_UserService::getInstance()->getUserUrl($id);
        $pos = strpos($upl, ':');
        if($pos !== false) {
            $upl = substr($upl, $pos+1);
        }
        return $upl;
    }
    private function defaultValue($field) {

        $dVal = array (
            'iflychat_ext_d_i' => '3',
            'iflychat_external_api_key' => 'NULL',
            'iflychat_theme' => '1',
            'iflychat_notification_sound' => '1',
            'iflychat_anon_use_name' => '1',
            'iflychat_show_admin_list' => '2',
            'iflychat_anon_prefix' => 'Guest',
            'iflychat_enable_smileys' => '1',
            'iflychat_user_picture' => '1',
            'iflychat_path_pages' => '',
            'iflychat_path_visibility' => '1',
            'iflychat_support_chat_init_label' => 'Chat with us',
            'iflychat_support_chat_box_header' => 'Support',
            'iflychat_support_chat_box_company_name' => 'Support Team',
            'iflychat_support_chat_box_company_tagline' => 'Ask us anything...',
            'iflychat_support_chat_auto_greet_enable' => '1',
            'iflychat_support_chat_auto_greet_message' => 'Hi there! Welcome to our website. Let us know if you have any query!',
            'iflychat_support_chat_auto_greet_time' => '',
            'iflychat_support_chat_offline_message_label' => 'Message',
            'iflychat_support_chat_offline_message_contact' => 'Contact Details',
            'iflychat_support_chat_offline_message_send_button' => 'Send Message',
            'iflychat_support_chat_offline_message_desc' => 'Hello there. We are currently offline. Please leave us a message. Thanks.',
            'iflychat_support_chat_init_label_off' => 'Leave Message',
            'iflychat_minimize_chat_user_list' => '2',
            'iflychat_use_stop_word_list' => '1',
            'iflychat_stop_links' => '1',
            'iflychat_allow_anon_links' => '1',
            'iflychat_allow_render_images' => '1',
            'iflychat_enable_search_bar' => '1',
            'iflychat_enable_friends' => '1',
            'iflychat_enable_file_attachment' => '1',
            'iflychat_enable_mobile_browser_app' => '1'
        );

        return $dVal["$field"];
    }

    public function params($field) {

        $config = OW::getConfig();
        $configArr = $config->getValues('iflychat');
        $params = json_decode($configArr['setting_vars'], TRUE);
        if(empty($params["$field"])){

            return $this->defaultValue($field);

        }else{

            return $params["$field"];

        }

    }
    public function isSSL(){
        $u = OW::getRouter()->getBaseUrl();
        $var = explode(":", $u);
        if($var[0] === 'https') {
            return true;
        }
    }

    public function iflychat_path_check() {
        $page_match = FALSE;
//Check default path
        if($this->default_path()){
            return FALSE;

        }

        if (trim($this->params('iflychat_path_pages')) != '') {
            if(function_exists('mb_strtolower')) {
                $pages = mb_strtolower($this->params('iflychat_path_pages'));
                $path = mb_strtolower(OW::getRouter()->getUri());
            }
            else {
                $pages = strtolower($this->params('iflychat_path_pages'));
                $path = strtolower(OW::getRouter()->getUri());
            }
            $page_match = $this->iflychat_match_path($path, $pages);
            $page_match = ($this->params('iflychat_path_visibility') == '1')?(!$page_match):$page_match;
        }
        else if($this->params('iflychat_path_visibility') == '1'){
            $page_match = TRUE;
        }
        return $page_match;
    }
    public function iflychat_match_path($path, $patterns) {
        $to_replace = array(
            '/(\r\n?|\n)/',
            '/\\\\\*/',
        );
        $replacements = array(
            '|',
            '.*',
        );
        $patterns_quoted = preg_quote($patterns, '/');
        $regexps[$patterns] = '/^(' . preg_replace($to_replace, $replacements, $patterns_quoted) . ')$/';
        return (bool) preg_match($regexps[$patterns], $path);
    }
    // Chat not load on default path
    public function default_path(){

        $defaultPath = 'base/media-panel';
        $path = mb_strtolower(OW::getRouter()->getUri());
        $var = explode("/", $path);
        if(!empty($var[1])){
        $path = $var[0]."/".$var[1];
        $page_match = $this->iflychat_match_path($path, $defaultPath);

        return $page_match;
        }
        else return null;
    }
    

}
