<?php
/**
 * @package iFlyChat
 * @version 1.0.0
 * @copyright Copyright (C) 2014 iFlyChat. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @author iFlyChat Team
 * @link https://iflychat.com
 */

class IFLYCHAT_CTRL_Admin extends ADMIN_CTRL_Abstract {

    private $timers;



    public function index()
    {
        $language = OW::getLanguage();
        $this->setPageHeading($language->text("iflychat", "iflychat_setting_page"));
        $this->setPageHeadingIconClass('ow_ic_gear_wheel');
        OW::getDocument()->addScript(OW::getPluginManager()->getPlugin('iflychat')->getStaticJsUrl() . 'iflychat-admin.js');

        $configs = OW::getConfig()->getValues('iflychat');
        $this->assign('configs', $configs);
        $form = new IFLYCHAT_ConfigSaveForm($configs);
        $this->addForm($form);


        if( OW::getRequest()->isPost() && $form->isValid($_POST) )
        { if ( $form->process($_POST) ){

            $data = $form->getValues();

            $variable_get = $this->params('iflychat_ext_d_i');


            define('IFLYCHAT_EXTERNAL_HOST', 'http://api'.$variable_get.'.iflychat.com');
            define('IFLYCHAT_EXTERNAL_PORT', '80');
            define('IFLYCHAT_EXTERNAL_A_HOST', 'https://api'.$variable_get.'.iflychat.com');
            define('IFLYCHAT_EXTERNAL_A_PORT', '443');

            $pdata = array(
                'api_key' => $data['iflychat_external_api_key'],
                'enable_chatroom' => $data['iflychat_enable_chatroom'],
                'theme' => ($data['iflychat_theme'] == 1)?'light':'dark',
                'notify_sound' => $data['iflychat_notification_sound'],
                'smileys' => $data['iflychat_enable_smileys'],
                'log_chat' => $data['iflychat_log_messages'],
                'chat_topbar_color' => $data['iflychat_chat_topbar_color'],
                'chat_topbar_text_color' => $data['iflychat_chat_topbar_text_color'],
                'font_color' => $data['iflychat_font_color'],
                'chat_list_header' => $data['iflychat_chat_list_header'],
                'public_chatroom_header' => $data['iflychat_public_chatroom_header'],
                'rel' => $data['iflychat_rel'],
                'version' => 'Oxwall-1.6.0',
                'show_admin_list' => $data['iflychat_show_admin_list'],
                'clear' => $data['iflychat_allow_single_message_delete'],
                'delmessage' => $data['iflychat_allow_clear_room_history'],
                'ufc' => $data['iflychat_allow_user_font_color'],
                'guest_prefix' => ($data['iflychat_anon_prefix'] . " "),
                'enable_guest_change_name' => $data['iflychat_anon_change_name'],
                'use_stop_word_list' => $data['iflychat_use_stop_word_list'],
                'stop_word_list' => $data['iflychat_stop_word_list'],
            );


            $d = json_encode($pdata);
            $options = array(
                'method' => 'POST',
                'data' => $d,
                'timeout' => 15,
                'headers' => array('Content-Type' => 'application/json'),
            );


            $uri = IFLYCHAT_EXTERNAL_A_HOST . ':' . IFLYCHAT_EXTERNAL_A_PORT .  '/p/';


                $this->iflychat_extended_http_request($uri, $options);

            $form->process(json_encode($data));

            OW::getFeedback()->info($language->text('iflychat', 'settings_updated'));
        }else {

            OW::getFeedback()->error(OW::getLanguage()->text('iflychat', 'admin_index_form_save_error_message'));
        }
            $this->redirect();
        }


$default = array(

    'iflychat_external_api_key' => '',
    'iflychat_show_admin_list' => '1',
    'iflychat_theme' => '1',
    'iflychat_notification_sound' => '1',
    'iflychat_user_picture' => '1',
    'iflychat_enable_smileys' => '1',
    'iflychat_log_messages' => '1',
    'iflychat_anon_prefix' => 'Guest',
    'iflychat_anon_use_name' => '1',
    'iflychat_anon_change_name' => '1',
    'iflychat_load_chat_async' => '1',
    'iflychat_ext_d_i' => '',
    'iflychat_support_chat_init_label' => 'Chat with us',
    'iflychat_support_chat_box_header' => 'Support',
    'iflychat_support_chat_box_company_name' => 'Support Team',
    'iflychat_support_chat_box_company_tagline' => 'Ask us anything...',
    'iflychat_support_chat_auto_greet_enable' => '1',
    'iflychat_support_chat_auto_greet_message' => 'Hi there! Welcome to our website. Let us know if you have any query!',
    'iflychat_support_chat_auto_greet_time' => '1',
    'iflychat_support_chat_init_label_off' => 'Leave Message',
    'iflychat_support_chat_offline_message_desc' => 'Hello there. We are currently offline. Please leave us a message. Thanks.',
    'iflychat_support_chat_offline_message_label' => 'Message',
    'iflychat_support_chat_offline_message_contact' => 'Contact Details',
    'iflychat_support_chat_offline_message_send_button' => 'Send Message',
    'iflychat_support_chat_offline_message_email' => '',
    'iflychat_enable_chatroom' => '1',
    'iflychat_stop_word_list' => 'asshole,assholes,bastard,beastial,beastiality,beastility,bestial,bestiality,bitch,bitcher,bitchers,bitches,bitchin,bitching,blowjob,blowjobs,bullshit,clit,cock,cocks,cocksuck,cocksucked,cocksucker,cocksucking,cocksucks,cum,cummer,cumming,cums,cumshot,cunillingus,cunnilingus,cunt,cuntlick,cuntlicker,cuntlicking,cunts,cyberfuc,cyberfuck,cyberfucked,cyberfucker,cyberfuckers,cyberfucking,damn,dildo,dildos,dick,dink,dinks,ejaculate,ejaculated,ejaculates,ejaculating,ejaculatings,ejaculation,fag,fagging,faggot,faggs,fagot,fagots,fags,fart,farted,farting,fartings,farts,farty,felatio,fellatio,fingerfuck,fingerfucked,fingerfucker,fingerfuckers,fingerfucking,fingerfucks,fistfuck,fistfucked,fistfucker,fistfuckers,fistfucking,fistfuckings,fistfucks,fuck,fucked,fucker,fuckers,fuckin,fucking,fuckings,fuckme,fucks,fuk,fuks,gangbang,gangbanged,gangbangs,gaysex,goddamn,hardcoresex,horniest,horny,hotsex,jism,jiz,jizm,kock,kondum,kondums,kum,kumer,kummer,kumming,kums,kunilingus,lust,lusting,mothafuck,mothafucka,mothafuckas,mothafuckaz,mothafucked,mothafucker,mothafuckers,mothafuckin,mothafucking,mothafuckings,mothafucks,motherfuck,motherfucked,motherfucker,motherfuckers,motherfuckin,motherfucking,motherfuckings,motherfucks,niger,nigger,niggers,orgasim,orgasims,orgasm,orgasms,phonesex,phuk,phuked,phuking,phukked,phukking,phuks,phuq,pis,piss,pisser,pissed,pisser,pissers,pises,pisses,pisin,pissin,pising,pissing,pisof,pissoff,porn,porno,pornography,pornos,prick,pricks,pussies,pusies,pussy,pusy,pussys,pusys,slut,sluts,smut,spunk',
    'iflychat_use_stop_word_list' => '1',
    'iflychat_stop_links' => '1',
    'iflychat_allow_anon_links' => '1',
    'iflychat_allow_render_images' => '1',
    'iflychat_allow_single_message_delete' => '1',
    'iflychat_allow_clear_room_history' => '1',
    'iflychat_allow_user_font_color' => '1',
    'iflychat_path_visibility' => '1',
    'iflychat_path_pages' => '',
    'iflychat_chat_topbar_color' => '#222222',
    'iflychat_chat_topbar_text_color' => '#FFFFFF',
    'iflychat_font_color' => '#FFFFFF',
    'iflychat_public_chatroom_header' => 'Public Chatroom',
    'iflychat_chat_list_header' => 'Chat',
    'iflychat_minimize_chat_user_list' => '2',
    'iflychat_enable_search_bar' => '1',
    'iflychat_rel' => '1',
    'iflychat_ur_name' => '',
    'iflychat_only_loggedin' => '1',

);


        $settingsJson = OW::getConfig()->getValue('iflychat', 'setting_vars');
           // print_r($settingsJson);
        $settingsArray = (array)json_decode($settingsJson);
        foreach ($settingsArray as $key => $value) {

            if(empty($value)) {

                $settingsArray[$key] = $default[$key];

                $form->setValues($settingsArray);
                $this->addForm($form);
        }


}




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

    private function iflychat_extended_http_request($url, array $options = array()) {
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
    function defaultValue($field) {

        $dVal = array (
            'iflychat_ext_d_i' => '',
            'iflychat_external_api_key' => 'NULL',
            'iflychat_theme' => '1',
            'iflychat_notification_sound' => '1',
            'iflychat_anon_use_name' => '1',
            'iflychat_show_admin_list' => '2',
            'iflychat_anon_prefix' => 'Guest',
            'iflychat_enable_smileys' => '1',
            'iflychat_user_picture' => '1',
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
            'iflychat_enable_search_bar' => '1'
        );

        return $dVal["$field"];
    }

    function params($field) {

        $config = OW::getConfig();
        $configArr = $config->getValues('iflychat');
        $params = json_decode($configArr['setting_vars'], TRUE);
        if(empty($params["$field"])){

            return $this->defaultValue($field);

        }else{

            return $params["$field"];

        }

    }
}
class IFLYCHAT_ConfigSaveForm extends Form {

    public function __construct( $configs ) {

        parent::__construct('IFLYCHAT_ConfigSaveForm');

        $language = OW::getLanguage();


//General settings

        $textField = new TextField("iflychat_external_api_key");
        $textField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_API_KEY"));
        $textField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_API_KEY_DESC"));
        $textField->setRequired();
        $this->addElement($textField);

        $selectField = new Selectbox("iflychat_show_admin_list");
        $selectField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_SHOW_ADMIN_LIST"));
        $selectField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_SHOW_ADMIN_LIST_DESC"));
        $selectField->setValue('1');
        $selectField->setOptions(array(
            "2" => "Community Chat",
            "1" => "Support Chat"
        ));
        $this->addElement($selectField);


        $selectField = new Selectbox("iflychat_theme");
        $selectField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_THEME"));
        $selectField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_SHOW_ADMIN_LIST_DESC"));
        $selectField->setInvitation('Select any');
        $selectField->setValue('1');
        $selectField->setOptions(array(
            "1" => "Light",
            "2" => "Dark"
        ));
        $this->addElement($selectField);

        $field = new CheckboxField("iflychat_notification_sound");
        $field->setLabel($language->text("iflychat", "MOD_IFLYCHAT_NOTIFICATION_SOUND"));
        $field->setDescription($language->text("iflychat", "MOD_IFLYCHAT_NOTIFICATION_SOUND_DESC"));
        $field->setValue('1');
        $this->addElement($field);

        $field = new CheckboxField("iflychat_user_picture");
        $field->setLabel($language->text("iflychat", "MOD_IFLYCHAT_USER_PICTURE"));
        $field->setDescription($language->text("iflychat", "MOD_IFLYCHAT_USER_PICTURE_DESC"));
        $field->setValue('1');
        $this->addElement($field);

        $field = new CheckboxField("iflychat_enable_smileys");
        $field->setLabel($language->text("iflychat", "MOD_IFLYCHAT_ENABLE_SMILEYS"));
        $field->setDescription($language->text("iflychat", "MOD_IFLYCHAT_ENABLE_SMILEYS_DESC"));
        $field->setValue('1');
        $this->addElement($field);

        $field = new CheckboxField("iflychat_log_messages");
        $field->setLabel($language->text("iflychat", "MOD_IFLYCHAT_LOG_MESSAGES"));
        $field->setDescription($language->text("iflychat", "MOD_IFLYCHAT_LOG_MESSAGES_DESC"));
        $field->setValue('1');
        $this->addElement($field);

        $textField = new TextField("iflychat_anon_prefix");
        $textField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_ANON_PREFIX"));
        $textField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_ANON_PREFIX_DESC"));
        $textField->setRequired();
        $textField->setValue('Guest');
        $this->addElement($textField);

        $selectField = new Selectbox("iflychat_anon_use_name");
        $selectField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_ANON_USE_NAME"));
        $selectField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_ANON_USE_NAME_DESC"));
        $selectField->setValue('1');
        $selectField->setOptions(array(
            "1" => "Name",
            "2" => "Number"
        ));
        $this->addElement($selectField);

        $field = new CheckboxField("iflychat_anon_change_name");
        $field->setLabel($language->text("iflychat", "MOD_IFLYCHAT_ANON_CHANGE_NAME"));
        $field->setDescription($language->text("iflychat", "MOD_IFLYCHAT_ANON_CHANGE_NAME_DESC"));
        $field->setValue('1');
        $this->addElement($field);

        $field = new CheckboxField("iflychat_load_chat_async");
        $field->setLabel($language->text("iflychat", "MOD_IFLYCHAT_LOAD_CHAT_ASYNC"));
        $field->setDescription($language->text("iflychat", "MOD_IFLYCHAT_LOAD_CHAT_ASYNC_DESC"));
        $field->setValue('1');
        $this->addElement($field);

        $hiddenField = new HiddenField("iflychat_ext_d_i");
        $hiddenField->setValue("");
        $this->addElement($hiddenField);

//Support settings

        $textField = new TextField("iflychat_support_chat_init_label");
        $textField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_SUPPORT_CHAT_INIT_LABEL"));
        $textField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_SUPPORT_CHAT_INIT_LABEL_DESC"));
        $textField->setValue('Chat with us');
        $this->addElement($textField);

        $textField = new TextField("iflychat_support_chat_box_header");
        $textField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_SUPPORT_CHAT_BOX_HEADER"));
        $textField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_SUPPORT_CHAT_BOX_HEADER_DESC"));
        $textField->setValue('Support');
        $this->addElement($textField);

        $textField = new TextField("iflychat_support_chat_box_company_name");
        $textField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_SUPPORT_CHAT_BOX_COMPANY_NAME"));
        $textField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_SUPPORT_CHAT_BOX_COMPANY_NAME_DESC"));
        $textField->setValue('Support Team');
        $this->addElement($textField);

        $textField = new TextField("iflychat_support_chat_box_company_tagline");
        $textField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_SUPPORT_CHAT_BOX_COMPANY_TAGLINE"));
        $textField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_SUPPORT_CHAT_BOX_COMPANY_TAGLINE_DESC"));
        $textField->setValue('Ask us anything...');
        $this->addElement($textField);

        $field = new CheckboxField("iflychat_support_chat_auto_greet_enable");
        $field->setLabel($language->text("iflychat", "MOD_IFLYCHAT_SUPPORT_CHAT_AUTO_GREET_ENABLE"));
        $field->setValue('1');
        $this->addElement($field);

        $textareaField = new Textarea("iflychat_support_chat_auto_greet_message");
        $textareaField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_SUPPORT_CHAT_AUTO_GREET_MESSAGE"));
        $textareaField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_SUPPORT_CHAT_AUTO_GREET_MESSAGE_DESC"));
        $textareaField->setValue('Hi there! Welcome to our website. Let us know if you have any query!');
        $this->addElement($textareaField);

        $textField = new TextField("iflychat_support_chat_init_label_off");
        $textField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_SUPPORT_CHAT_INIT_LABEL_OFF"));
        $textField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_SUPPORT_CHAT_INIT_LABEL_OFF_DESC"));
        $textField->setValue('Leave Message');
        $this->addElement($textField);

        $textareaField = new Textarea("iflychat_support_chat_offline_message_desc");
        $textareaField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_SUPPORT_CHAT_OFFLINE_MESSAGE_DESC"));
        $textareaField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_SUPPORT_CHAT_OFFLINE_MESSAGE_DESC_DESC"));
        $textareaField->setValue('Hello there. We are currently offline. Please leave us a message. Thanks.');
        $this->addElement($textareaField);

        $textField = new TextField("iflychat_support_chat_offline_message_label");
        $textField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_SUPPORT_CHAT_OFFLINE_MESSAGE_LABEL"));
        $textField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_SUPPORT_CHAT_OFFLINE_MESSAGE_LABEL_DESC"));
        $textField->setValue('Message');
        $this->addElement($textField);

        $textField = new TextField("iflychat_support_chat_offline_message_contact");
        $textField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_SUPPORT_CHAT_OFFLINE_MESSAGE_CONTACT"));
        $textField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_SUPPORT_CHAT_OFFLINE_MESSAGE_CONTACT_DESC"));
        $textField->setValue('Contact Details');
        $this->addElement($textField);

        $textField = new TextField("iflychat_support_chat_offline_message_send_button");
        $textField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_SUPPORT_CHAT_OFFLINE_MESSAGE_SEND_BUTTON"));
        $textField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_SUPPORT_CHAT_OFFLINE_MESSAGE_SEND_BUTTON_DESC"));
        $textField->setValue('Send Message');
        $this->addElement($textField);

        $textField = new TextField("iflychat_support_chat_offline_message_email");
        $textField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_SUPPORT_CHAT_OFFLINE_MESSAGE_EMAIL"));
        $textField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_SUPPORT_CHAT_OFFLINE_MESSAGE_EMAIL_DESC"));
        $this->addElement($textField);

//Chat moderation

        $field = new CheckboxField("iflychat_enable_chatroom");
        $field->setLabel($language->text("iflychat", "MOD_IFLYCHAT_ENABLE_CHATROOM"));
        $field->setValue('1');
        $this->addElement($field);

        $textareaField = new Textarea("iflychat_stop_word_list");
        $textareaField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_STOP_WORD_LIST"));
        $textareaField->setValue('asshole,assholes,bastard,beastial,beastiality,beastility,bestial,bestiality,bitch,bitcher,bitchers,bitches,bitchin,bitching,blowjob,blowjobs,bullshit,clit,cock,cocks,cocksuck,cocksucked,cocksucker,cocksucking,cocksucks,cum,cummer,cumming,cums,cumshot,cunillingus,cunnilingus,cunt,cuntlick,cuntlicker,cuntlicking,cunts,cyberfuc,cyberfuck,cyberfucked,cyberfucker,cyberfuckers,cyberfucking,damn,dildo,dildos,dick,dink,dinks,ejaculate,ejaculated,ejaculates,ejaculating,ejaculatings,ejaculation,fag,fagging,faggot,faggs,fagot,fagots,fags,fart,farted,farting,fartings,farts,farty,felatio,fellatio,fingerfuck,fingerfucked,fingerfucker,fingerfuckers,fingerfucking,fingerfucks,fistfuck,fistfucked,fistfucker,fistfuckers,fistfucking,fistfuckings,fistfucks,fuck,fucked,fucker,fuckers,fuckin,fucking,fuckings,fuckme,fucks,fuk,fuks,gangbang,gangbanged,gangbangs,gaysex,goddamn,hardcoresex,horniest,horny,hotsex,jism,jiz,jizm,kock,kondum,kondums,kum,kumer,kummer,kumming,kums,kunilingus,lust,lusting,mothafuck,mothafucka,mothafuckas,mothafuckaz,mothafucked,mothafucker,mothafuckers,mothafuckin,mothafucking,mothafuckings,mothafucks,motherfuck,motherfucked,motherfucker,motherfuckers,motherfuckin,motherfucking,motherfuckings,motherfucks,niger,nigger,niggers,orgasim,orgasims,orgasm,orgasms,phonesex,phuk,phuked,phuking,phukked,phukking,phuks,phuq,pis,piss,pisser,pissed,pisser,pissers,pises,pisses,pisin,pissin,pising,pissing,pisof,pissoff,porn,porno,pornography,pornos,prick,pricks,pussies,pusies,pussy,pusy,pussys,pusys,slut,sluts,smut,spunk');
        $this->addElement($textareaField);

        $selectField = new Selectbox("iflychat_use_stop_word_list");
        $selectField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_USE_STOP_WORD_LIST"));
        $selectField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_USE_STOP_WORD_LIST_DESC"));
        $selectField->setValue('1');
        $selectField->setOptions(array(
            "1" => "Don't filter",
            "2" => "Filter in public chatroom",
            "3" => "Filter in private chats",
            "4" => "Filter in all rooms"
        ));
        $this->addElement($selectField);

        $selectField = new Selectbox("iflychat_stop_links");
        $selectField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_STOP_LINKS"));
        $selectField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_STOP_LINKS_DESC"));
        $selectField->setValue('1');
        $selectField->setOptions(array(
            "1" => "Don't filter",
            "2" => "Filter in public chatroom",
            "3" => "Filter in private chats",
            "4" => "Filter in all rooms"
        ));
        $this->addElement($selectField);

        $selectField = new Selectbox("iflychat_allow_anon_links");
        $selectField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_ALLOW_ANON_LINKS"));
        $selectField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_ALLOW_ANON_LINKS_DESC"));
        $selectField->setValue('1');
        $selectField->setOptions(array(
            "1" => "Yes, apply only to anonymous users",
            "2" => "No, apply to all users"
        ));
        $this->addElement($selectField);

        $selectField = new Selectbox("iflychat_allow_render_images");
        $selectField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_ALLOW_RENDER_IMAGES"));
        $selectField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_ALLOW_RENDER_IMAGES_DESC"));
        $selectField->setValue('1');
        $selectField->setOptions(array(
            "1" => "Yes",
            "2" => "No"
        ));
        $this->addElement($selectField);

        $selectField = new Selectbox("iflychat_allow_single_message_delete");
        $selectField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_ALLOW_SINGLE_MESSAGE_DELETE"));
        $selectField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_ALLOW_SINGLE_MESSAGE_DELETE_DESC"));
        $selectField->setValue('1');
        $selectField->setOptions(array(
            "1" => "Allow all users",
            "2" => "Allow only moderators",
            "3" => "Disable"
        ));
        $this->addElement($selectField);

        $selectField = new Selectbox("iflychat_allow_clear_room_history");
        $selectField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_ALLOW_CLEAR_ROOM_HISTORY"));
        $selectField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_ALLOW_CLEAR_ROOM_HISTORY_DESC"));
        $selectField->setValue('1');
        $selectField->setOptions(array(
            "1" => "Allow all users",
            "2" => "Allow only moderators",
            "3" => "Disable"
        ));
        $this->addElement($selectField);

        $selectField = new Selectbox("iflychat_allow_user_font_color");
        $selectField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_ALLOW_USER_FONT_COLOR"));
        $selectField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_ALLOW_USER_FONT_COLOR_DESC"));
        $selectField->setValue('1');
        $selectField->setOptions(array(
            "1" => "Yes",
            "2" => "No"
        ));
        $this->addElement($selectField);

//Iflychat visibility

        $selectField = new Selectbox("iflychat_path_visibility");
        $selectField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_PATH_VISIBILITY"));
        $selectField->setValue('1');
        $selectField->setOptions(array(
            "1" => "All pages except those listed ",
            "2" => " Only the listed pages",
            "3" => "Pages on which this PHP code retuts only)"
        ));
        $this->addElement($selectField);

        $textareaField = new Textarea("iflychat_path_pages");
        $textareaField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_PATH_PAGES"));
        $textareaField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_PATH_PAGES_DESC"));
        $this->addElement($textareaField);


//Theme customisation

        $textField = new TextField("iflychat_chat_topbar_color");
        $textField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_CHAT_TOPBAR_COLOR"));
        $textField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_CHAT_TOPBAR_COLOR_DESC"));
        $textField->setValue('#222222');
        $textField->setRequired();
        $this->addElement($textField);

        $textField = new TextField("iflychat_chat_topbar_text_color");
        $textField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_CHAT_TOPBAR_TEXT_COLOR"));
        $textField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_CHAT_TOPBAR_TEXT_COLOR_DESC"));
        $textField->setValue('#FFFFFF');
        $textField->setRequired();
        $this->addElement($textField);

        $textField = new TextField("iflychat_font_color");
        $textField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_FONT_COLOR"));
        $textField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_FONT_COLOR_DESC"));
        $textField->setValue('#222222');
        $textField->setRequired();
        $this->addElement($textField);

        $textField = new TextField("iflychat_public_chatroom_header");
        $textField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_PUBLIC_CHATROOM_HEADER"));
        $textField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_PUBLIC_CHATROOM_HEADER_DESC"));
        $textField->setValue('Public Chatroom');
        $textField->setRequired();
        $this->addElement($textField);

        $textField = new TextField("iflychat_chat_list_header");
        $textField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_CHAT_LIST_CHATROOM_HEADER"));
        $textField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_CHAT_LIST_CHATROOM_HEADER_DESC"));
        $textField->setValue('Chat');
        $textField->setRequired();
        $this->addElement($textField);

//Iflychat user online list control

        $selectField = new Selectbox("iflychat_minimize_chat_user_list");
        $selectField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_MINIMIZE_CHAT_USER_LIST"));
        $selectField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_MINIMIZE_CHAT_USER_LIST_DESC"));
        $selectField->setValue('2');
        $selectField->setOptions(array(
            "1" => "Yes",
            "2" => "No"
        ));
        $this->addElement($selectField);

        $selectField = new Selectbox("iflychat_enable_search_bar");
        $selectField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_ENABLE_SEARCH_BAR"));
        $selectField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_ENABLE_SEARCH_BAR_DESC"));
        $selectField->setValue('1');
        $selectField->setOptions(array(
            "1" => "Yes",
            "2" => "No"
        ));
        $this->addElement($selectField);

        $field = new CheckboxField("iflychat_rel");
        $field->setLabel($language->text("iflychat", "MOD_IFLYCHAT_REL"));
        $field->setDescription($language->text("iflychat", "MOD_IFLYCHAT_REL_DESC"));
        $field->setValue('1');
        $this->addElement($field);

        $textField = new TextField("iflychat_ur_name");
        $textField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_UR_NAME"));
        $textField->setDescription($language->text("iflychat", "MOD_IFLYCHAT_UR_NAME_DESC"));

        $this->addElement($textField);

        $selectField = new Selectbox("iflychat_only_loggedin");
        $selectField->setLabel($language->text("iflychat", "MOD_IFLYCHAT_ONLY_LOGGEDIN"));
        $selectField->setValue('1');
        $selectField->setOptions(array(
            "1" => "Yes",
            "2" => "No"
        ));
        $this->addElement($selectField);


        $submit = new Submit('submit');
        $submit->setValue('Save');
        $this->addElement($submit);

    }
    public function process( $data )
    {
        $config = OW::getConfig();
        $config->saveConfig('iflychat', 'setting_vars', $data);
      /*  $config->saveConfig('iflychat', 'iflychat_external_api_key', $data['iflychat_external_api_key']);
        $config->saveConfig('iflychat', 'iflychat_show_admin_list', $data['iflychat_show_admin_list']);
        $config->saveConfig('iflychat', 'iflychat_theme', $data['iflychat_theme']);
        $config->saveConfig('iflychat', 'iflychat_notification_sound', $data['iflychat_notification_sound']);
        $config->saveConfig('iflychat', 'iflychat_user_picture', $data['iflychat_user_picture']);
        $config->saveConfig('iflychat', 'iflychat_enable_smileys', $data['iflychat_enable_smileys']);
        $config->saveConfig('iflychat', 'iflychat_log_messages', $data['iflychat_log_messages']);
        $config->saveConfig('iflychat', 'iflychat_anon_prefix', $data['iflychat_anon_prefix']);
        $config->saveConfig('iflychat', 'iflychat_anon_use_name', $data['iflychat_anon_use_name']);
        $config->saveConfig('iflychat', 'iflychat_anon_change_name', $data['iflychat_anon_change_name']);
        $config->saveConfig('iflychat', 'iflychat_load_chat_async', $data['iflychat_load_chat_async']);
        $config->saveConfig('iflychat', 'iflychat_ext_d_i', $data['iflychat_ext_d_i']);
        $config->saveConfig('iflychat', 'iflychat_support_chat_init_label', $data['iflychat_support_chat_init_label']);
        $config->saveConfig('iflychat', 'iflychat_support_chat_box_header', $data['iflychat_support_chat_box_header']);
        $config->saveConfig('iflychat', 'iflychat_support_chat_box_company_name', $data['iflychat_support_chat_box_company_name']);
        $config->saveConfig('iflychat', 'iflychat_support_chat_box_company_tagline', $data['iflychat_support_chat_box_company_tagline']);
        $config->saveConfig('iflychat', 'iflychat_support_chat_auto_greet_enable', $data['iflychat_support_chat_auto_greet_enable']);
        $config->saveConfig('iflychat', 'iflychat_support_chat_auto_greet_message', $data['iflychat_support_chat_auto_greet_message']);
        $config->saveConfig('iflychat', 'iflychat_support_chat_auto_greet_time', $data['iflychat_support_chat_auto_greet_time']);
        $config->saveConfig('iflychat', 'iflychat_support_chat_init_label_off', $data['iflychat_support_chat_init_label_off']);
        $config->saveConfig('iflychat', 'iflychat_support_chat_offline_message_desc', $data['iflychat_support_chat_offline_message_desc']);
        $config->saveConfig('iflychat', 'iflychat_support_chat_offline_message_label', $data['iflychat_support_chat_offline_message_label']);
        $config->saveConfig('iflychat', 'iflychat_support_chat_offline_message_contact', $data['iflychat_support_chat_offline_message_contact']);
        $config->saveConfig('iflychat', 'iflychat_support_chat_offline_message_send_button', $data['iflychat_support_chat_offline_message_send_button']);
        $config->saveConfig('iflychat', 'iflychat_support_chat_offline_message_email', $data['iflychat_support_chat_offline_message_email']);
        $config->saveConfig('iflychat', 'iflychat_enable_chatroom', $data['iflychat_enable_chatroom']);
        $config->saveConfig('iflychat', 'iflychat_stop_word_list', $data['iflychat_stop_word_list']);
        $config->saveConfig('iflychat', 'iflychat_use_stop_word_list', $data['iflychat_use_stop_word_list']);
        $config->saveConfig('iflychat', 'iflychat_stop_links', $data['iflychat_stop_links']);
        $config->saveConfig('iflychat', 'iflychat_allow_anon_links', $data['iflychat_allow_anon_links']);
        $config->saveConfig('iflychat', 'iflychat_allow_render_images', $data['iflychat_allow_render_images']);
        $config->saveConfig('iflychat', 'iflychat_allow_single_message_delete', $data['iflychat_allow_single_message_delete']);
        $config->saveConfig('iflychat', 'iflychat_allow_clear_room_history', $data['iflychat_allow_clear_room_history']);
        $config->saveConfig('iflychat', 'iflychat_allow_user_font_color', $data['iflychat_allow_user_font_color']);
        $config->saveConfig('iflychat', 'iflychat_path_visibility', $data['iflychat_path_visibility']);
        $config->saveConfig('iflychat', 'iflychat_path_pages', $data['iflychat_path_pages']);
        $config->saveConfig('iflychat', 'iflychat_chat_topbar_color', $data['iflychat_chat_topbar_color']);
        $config->saveConfig('iflychat', 'iflychat_chat_topbar_text_color', $data['iflychat_chat_topbar_text_color']);
        $config->saveConfig('iflychat', 'iflychat_font_color', $data['iflychat_font_color']);
        $config->saveConfig('iflychat', 'iflychat_public_chatroom_header', $data['iflychat_public_chatroom_header']);
        $config->saveConfig('iflychat', 'iflychat_chat_list_header', $data['iflychat_chat_list_header']);
        $config->saveConfig('iflychat', 'iflychat_minimize_chat_user_list', $data['iflychat_minimize_chat_user_list']);
        $config->saveConfig('iflychat', 'iflychat_enable_search_bar', $data['iflychat_enable_search_bar']);
        $config->saveConfig('iflychat', 'iflychat_rel', $data['iflychat_rel']);
        $config->saveConfig('iflychat', 'iflychat_ur_name', $data['iflychat_ur_name']);
        $config->saveConfig('iflychat', 'iflychat_only_loggedin', $data['iflychat_only_loggedin']);

*/
        return true;
    }

}