<?php
/**
 * @package iFlyChat
 * @version 1.0.0
 * @copyright Copyright (C) 2014 iFlyChat. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @author iFlyChat Team
 * @link https://iflychat.com
 */

require_once(OW_DIR_PLUGIN.'iflychat'.DS.'helper.php');
class IFLYCHAT_CTRL_iflychat extends OW_ActionController {


    public function auth() {

        $obj = new iflychatHelper;


        $variable_get = $obj->params('iflychat_ext_d_i');

        header('Content-type: application/json');
        $settingJson  = OW::getConfig()->getValue('iflychat', 'setting_vars');
        $settingArray = (array)json_decode($settingJson);

        define('IFLYCHAT_EXTERNAL_HOST', 'http://api'.$variable_get.'.iflychat.com');
        define('IFLYCHAT_EXTERNAL_PORT', '80');
        define('IFLYCHAT_EXTERNAL_A_HOST', 'https://api'.$variable_get.'.iflychat.com');
        define('IFLYCHAT_EXTERNAL_A_PORT', '443');
        $uid = OW::getUser()->getId();
        $uname = ($uid)?BOL_UserService::getInstance()->findUserById($uid)->username:'';


        if(OW_User::getInstance()->isAdmin() || OW_User::getInstance()->isAuthorized('iflychat', 'mod') ) {
            $role = 'admin';
        }else {
            $role = 'normal';
        }

        $api_key = $settingArray['iflychat_external_api_key'];

        if($obj->params('iflychat_theme') == 1) {
            $iflychat_theme = 'light';
        }
        else {
            $iflychat_theme = 'dark';
        }


        $data = array(
            'uname' => ($uid)?$uname:$obj->iflychat_get_current_guest_name(),
            'uid' => ($uid)?(string)OW::getUser()->getId():'0-'.$obj->iflychat_get_current_guest_id(),
            'api_key' => $api_key,
            'image_path' => OW::getPluginManager()->getPlugin('iflychat')->getStaticUrl() . 'themes/' . $iflychat_theme . '/images/',
            'isLog' => TRUE,
            'role' => $role,
            'whichTheme' => 'blue',
            'enableStatus' => TRUE,
            'validState' => array('available','offline','busy','idle'),
            'rel' => '0'
        );



        //Get friend's id
        if($obj->params('iflychat_enable_friends')==2 && is_array(FRIENDS_BOL_Service::getInstance()->findAllActiveFriendships())){
            $data['rel'] = '1';
            $final_list = array();
            $final_list['1']['name'] = 'friend';
            $final_list['1']['plural'] = 'friends';
            $final_list['1']['valid_uids'] = FRIENDS_BOL_Service::getInstance()->findFriendIdList($uid,0,1000);;
            $data['valid_uids'] = $final_list;
        }

        if($obj->params('iflychat_user_picture') == 1){
            $data['up'] = $obj->iflychat_get_user_pic_url();
        }
            $data['upl'] = $obj->iflychat_get_user_profile_url();

        $options = array(
            'method' => 'POST',
            'data' => json_encode($data),
            'timeout' => 15,
            'headers' => array('Content-Type' => 'application/json'),
        );

     if(OW_User::getInstance()->isAuthorized('iflychat', 'add_chat') && !($data['rel']==1 && $uid==0)){

        $uri = IFLYCHAT_EXTERNAL_A_HOST . ':' . IFLYCHAT_EXTERNAL_A_PORT .  '/p/';
    try {

    $response = $obj->iflychat_extended_http_request($uri, $options);
    if($response->code != 200) {

    $var = array (
        'name' => ($uid)?$uname:$obj->iflychat_get_current_guest_name(),
        'uid' => ($uid)?(string)OW::getUser()->getId():'0-'.$obj->iflychat_get_current_guest_id()
    );

    exit(json_encode($var));

}

    $jsonData = json_decode($response->data);

    if(isset($jsonData->_i) && ($jsonData->_i!=$variable_get)) {

    $config = OW::getConfig();
    $configArr = $config->getValues('iflychat');

    $data = json_decode($configArr['setting_vars'], TRUE);
    $data2 = array(
        'iflychat_ext_d_i' => $jsonData->_i
    );

    $config->saveConfig('iflychat', 'setting_vars',json_encode(array_merge($data, $data2)));


    }
        $json = json_decode($response->data, TRUE);


        $json['name'] = ($uid)?$uname:$obj->iflychat_get_current_guest_name();
        $json['uid'] = ($uid)?(string)OW::getUser()->getId():'0-'.$obj->iflychat_get_current_guest_id();
        $json['up'] = $obj->iflychat_get_user_pic_url();
        $json['upl'] = $obj->iflychat_get_user_profile_url();

         exit(json_encode($json));
    }
    catch(Exception $e)
    {


    $var = array (
        'name' => ($uid)?$uname:$obj->iflychat_get_current_guest_name(),
        'uid' => ($uid)?(string)OW::getUser()->getId():'0-'.$obj->iflychat_get_current_guest_id()
    );

    exit(json_encode($var));
            }


        } else{
    exit('Access denied');
    }

    }
//Mobile auth
    public function mobileAuth() {

        $obj = new iflychatHelper;


        $variable_get = $obj->params('iflychat_ext_d_i');

        define('IFLYCHAT_EXTERNAL_HOST', 'http://api'.$variable_get.'.iflychat.com');
        define('IFLYCHAT_EXTERNAL_PORT', '80');
        define('IFLYCHAT_EXTERNAL_A_HOST', 'https://api'.$variable_get.'.iflychat.com');
        define('IFLYCHAT_EXTERNAL_A_PORT', '443');

        $data = array('settings' => array());
        $data['settings']['authUrl'] = OW::getRouter()->getBaseUrl().'iflychat/iflychat/auth';
        $data['settings']['host'] = (($obj->isSSL())?(IFLYCHAT_EXTERNAL_A_HOST):(IFLYCHAT_EXTERNAL_HOST));
        $data['settings']['port'] = (($obj->isSSL())?(IFLYCHAT_EXTERNAL_A_PORT):(IFLYCHAT_EXTERNAL_PORT));
//Http request
        $result = $obj->iflychat_extended_http_request(IFLYCHAT_EXTERNAL_A_HOST . ':' . IFLYCHAT_EXTERNAL_A_PORT .  '/m/v1/app/', array(
                'method' => 'POST',
                'data' => json_encode($data),
                'timeout' => 15,
                'headers' => array('Content-Type' => 'application/json'),
            ));

        if(($result->code == 200)) {
            $o = $result->data;
        }
        else {
            print $result->code;
        }
        print $o;
        exit();
    }
}



