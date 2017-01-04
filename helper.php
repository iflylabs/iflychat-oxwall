<?php
define('IFLYCHAT_EXTERNAL_HOST', 'http://api.iflychat.com');
define('IFLYCHAT_EXTERNAL_PORT', '80');
define('IFLYCHAT_EXTERNAL_A_HOST', 'https://api.iflychat.com');
define('IFLYCHAT_EXTERNAL_A_PORT', '443');
define('IFLYCHAT_EXTERNAL_CDN_HOST', 'cdn.iflychat.com');

/**
 * @package   iFlyChat
 * @version   1.0.0
 * @copyright Copyright (C) 2014 iFlyChat. All rights reserved.
 * @license   GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @author    iFlyChat Team
 * @link      https://iflychat.com
 */
class IflychatHelper {


  public function iflychat_check_chat_admin() {
    if (OW_User::getInstance()->isAdmin()) {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

  public function roleArray() {
    $arr = BOL_AuthorizationRoleDao::getInstance()->findAll();
    $roleArr = array();
    for ($i = 0; $i < sizeof($arr); $i++) {
      $roleArr += array($arr[$i]->id => $arr[$i]->name);

    }

    return $roleArr;
  }

  public function iflychat_extended_http_request($url, $data_json) {

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $result = curl_exec($ch);
    $res_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $result = json_decode($result);
    if (empty($result)) {
      $result = (object) array('code' => $res_code);
    }
    else {
      $result->code = $res_code;
    }
    curl_close($ch);
    return $result;
  }

  public function generateToken($api_key) {
    $role = '';
    $chat_role = 'participant';
    $uid = OW::getUser()->getId();
    $uname = ($uid) ? BOL_UserService::getInstance()
      ->findUserById($uid)->username : '';
    if ($this->iflychat_check_chat_admin() || $this->iflychat_check_chat($uname, $this->params('iflychat_administers'))) {
      $role = 'admin';
      $chat_role = 'admin';
    }
    elseif ($this->iflychat_check_chat($uname, $this->params('iflychat_moderators'))) {
      $chat_role = 'moderator';
    }

    if ($uid) {
      $data = array(
        'user_name' => $uname,
        'user_id' => strval($uid),
        'app_id' => $this->params('iflychat_app_id'),
        'api_key' => $api_key,
        'user_roles' => $role,
        'version' => 'Oxwall-2.0.0',
        'user_list_filter' => 'all',
        'user_status' => TRUE,
        'chat_role' => $chat_role
      );
    }

    //Add aRole parameter in data array
    if ($role == 'admin') {
      $data['user_site_roles'] = $this->roleArray();
    }

    //Get friend's id
    if ($this->params('iflychat_enable_friends') == 2 && is_array(FRIENDS_BOL_Service::getInstance()
        ->findAllActiveFriendships())
    ) {
      $data['user_list_filter'] = 'friend';
      $final_list = array();
      $final_list['1']['name'] = 'friend';
      $final_list['1']['plural'] = 'friends';
      $final_list['1']['valid_uids'] = FRIENDS_BOL_Service::getInstance()
        ->findFriendIdList($uid, 0, 1000);;
      $data['user_relationships'] = $final_list;
    }
    else {
      $data['user_list_filter'] = 'all';
    }

    $data['user_avatar_url'] = $this->iflychat_get_user_pic_url($uid);
    $data['user_profile_url'] = $this->iflychat_get_user_profile_url($uid);

//print_r($data);
    $uri = IFLYCHAT_EXTERNAL_A_HOST . ':' . IFLYCHAT_EXTERNAL_A_PORT . '/api/1.1/token/generate';
    try {
      $response = $this->iflychat_extended_http_request($uri, json_encode($data));
      if (isset($response->code) && $response->code != 200) {
        $object = (Array) ['code' => $response->code];
        return json_encode($object);
      }
//                if (isset($response->app_id)) {
//                    $config = OW::getConfig();
//                    $configArr = $config->getValues('iflychat');
//                    $data = json_decode($configArr['setting_vars'], true);
//                    $data2 = array(
//                        'iflychat_app_id' => $response->app_id
//                    );
//                    $config->saveConfig('iflychat', 'setting_vars', json_encode(array_merge($data, $data2)));
//                }
      return json_encode($response);
    } catch (Exception $e) {
      $var = array(
        'key' => NULL,
        'expires_in' => NULL
      );
      return json_encode($var);
    }
  }

  public function iflychat_get_user_pic_url($uid) {
    $url = BOL_AvatarService::getInstance()->getAvatarUrl($uid);
    $pos = strpos($url, ':');
    if ($pos !== FALSE) {
      $url = substr($url, $pos + 1);
    }
    return $url;
  }

  public function iflychat_check_chat($uname, $allNames) {
    if (!empty($allNames) && ($uname)) {
      $a_names = explode(",", $allNames);
      foreach ($a_names as $an) {
        $aa = trim($an);
        if ($aa == $uname) {
          return TRUE;
          break;
        }
      }
    }
    return FALSE;
  }

  public function iflychat_get_user_profile_url($uid) {

    $upl = BOL_UserService::getInstance()->getUserUrl($uid);
    $pos = strpos($upl, ':');
    if ($pos !== FALSE) {
      $upl = substr($upl, $pos + 1);
    }
    return $upl;
  }

  private function defaultValue($field) {

    $dVal = array(
      'iflychat_external_api_key' => '',
      'iflychat_app_id' => '',
      'iflychat_show_popup_chat' => '1',
      'iflychat_enable_friends' => '1',
      'iflychat_moderators' => '',
      'iflychat_administers' => ''
    );

    return $dVal["$field"];
  }

  public function params($field) {

    $config = OW::getConfig();
    $configArr = $config->getValues('iflychat');
    if (!isset($configArr["$field"]) && empty($params["$field"])) {
      return $this->defaultValue($field);
    }
    else {
      return $configArr["$field"];
    }
  }

  public function iflychat_path_check() {
    $page_match = FALSE;
//Check default path
    if ($this->default_path()) {
      return FALSE;

    }

    if (trim($this->params('iflychat_path_pages')) != '') {
      if (function_exists('mb_strtolower')) {
        $pages = mb_strtolower($this->params('iflychat_path_pages'));
        $path = mb_strtolower(OW::getRouter()->getUri());
      }
      else {
        $pages = strtolower($this->params('iflychat_path_pages'));
        $path = strtolower(OW::getRouter()->getUri());
      }
      $page_match = $this->iflychat_match_path($path, $pages);
      $page_match = ($this->params('iflychat_path_visibility') == '1') ? (!$page_match) : $page_match;
    }
    else {
      if ($this->params('iflychat_path_visibility') == '1') {
        $page_match = TRUE;
      }
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
  public function default_path() {

    $defaultPath = 'base/media-panel';
    $path = mb_strtolower(OW::getRouter()->getUri());
    $var = explode("/", $path);
    if (!empty($var[1])) {
      $path = $var[0] . "/" . $var[1];
      $page_match = $this->iflychat_match_path($path, $defaultPath);

      return $page_match;
    }
    else {
      return NULL;
    }
  }


}
