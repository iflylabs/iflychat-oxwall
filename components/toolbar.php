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

class IFLYCHAT_CMP_Toolbar extends OW_Component
{

    public function render()
    {

        $obj = new iflychatHelper;


        $variable_get = $obj->params('iflychat_ext_d_i');


        define('IFLYCHAT_EXTERNAL_HOST', 'http://api'.$variable_get.'.iflychat.com');
        define('IFLYCHAT_EXTERNAL_PORT', '80');
        define('IFLYCHAT_EXTERNAL_A_HOST', 'https://api'.$variable_get.'.iflychat.com');
        define('IFLYCHAT_EXTERNAL_A_PORT', '443');


        if($obj->params('iflychat_theme') == 1) {
            $iflychat_theme = 'light';
        }
        else {
            $iflychat_theme = 'dark';
        }

        $language = OW::getLanguage();

        $iflychat_settings = array(

            //  'username' => ($user->id)?$user->name:'default', //$a_name
            // 'uid' =>  $user->id,    //($user->id)?$user->id:'0-'._drupalchat_get_sid(),
            'current_timestamp' => time(),
            'polling_method' => '', //$polling_method
            'pollUrl' => '',
            'sendUrl' => '',
            'statusUrl' => '',
            'status' => '',
            'goOnline' => $language->text('iflychat','MOD_GO_ONLINE'),
            'goIdle' => $language->text('iflychat','MOD_GO_IDLE'),
            'newMessage' => $language->text('iflychat','MOD_NEW_CHAT_MESSAGE'),
            'images' => OW::getPluginManager()->getPlugin('iflychat')->getStaticUrl() . 'themes/' . $iflychat_theme . '/images/',
            'sound' => OW::getPluginManager()->getPlugin('iflychat')->getStaticUrl() . 'swf/sound.swf',
            'soundFile' => OW::getPluginManager()->getPlugin('iflychat')->getStaticUrl() . 'wav/notification.mp3',
            'noUsers' => '',
            'smileyURL' => OW::getPluginManager()->getPlugin('iflychat')->getStaticUrl(),
            'addUrl' => '',
            'notificationSound' => $obj->params('iflychat_notification_sound'),
            'exurl' => OW::getRouter()->getBaseUrl().'iflychat/iflychat/auth',
            'soffurl' => '',
            'chat_type' => $obj->params('iflychat_show_admin_list'),
            'guestPrefix' => $obj->params('iflychat_anon_prefix'),
            'changeurl' => '',
            'allowSmileys' => $obj->params('iflychat_enable_smileys')

        );

        $iflychat_settings['iup'] = $obj->params('iflychat_user_picture');
        if($params['iflychat_user_picture']=$obj->params('iflychat_user_picture')) {
            $iflychat_settings['default_up'] = OW::getPluginManager()->getPlugin('iflychat')->getStaticUrl() . 'themes/' . $iflychat_theme . '/images/default_avatar.png';
            $iflychat_settings['default_cr'] = OW::getPluginManager()->getPlugin('iflychat')->getStaticUrl() . 'themes/' . $iflychat_theme . '/images/default_room.png';
            $iflychat_settings['default_team'] = OW::getPluginManager()->getPlugin('iflychat')->getStaticUrl() . 'themes/' . $iflychat_theme . '/images/default_team.png';
        }

        if($obj->isSSL()) {
            $iflychat_settings['external_host'] = IFLYCHAT_EXTERNAL_A_HOST;
            $iflychat_settings['external_port'] = IFLYCHAT_EXTERNAL_A_PORT;
            $iflychat_settings['external_a_host'] = IFLYCHAT_EXTERNAL_A_HOST;
            $iflychat_settings['external_a_port'] = IFLYCHAT_EXTERNAL_A_PORT;
        }
        else {
            $iflychat_settings['external_host'] = IFLYCHAT_EXTERNAL_HOST;
            $iflychat_settings['external_port'] = IFLYCHAT_EXTERNAL_PORT;
            $iflychat_settings['external_a_host'] = IFLYCHAT_EXTERNAL_HOST;
            $iflychat_settings['external_a_port'] = IFLYCHAT_EXTERNAL_PORT;
        }



        $iflychat_settings['text_currently_offline'] = $language->text('iflychat','MOD_USER_CURRENTLY_OFFLINE');
        $iflychat_settings['text_is_typing'] = $language->text('iflychat','MOD_USER_IS_TYPING');
        $iflychat_settings['text_close'] = $language->text('iflychat','MOD_CLOSE');
        $iflychat_settings['text_minimize'] = $language->text('iflychat','MOD_MINIMIZE');
        $iflychat_settings['text_mute'] = $language->text('iflychat','MOD_CLICK_TO_MUTE');
        $iflychat_settings['text_unmute'] = $language->text('iflychat','MOD_CLICK_TO_UNMUTE');
        $iflychat_settings['text_available'] = $language->text('iflychat','MOD_AVAILABLE');
        $iflychat_settings['text_idle'] = $language->text('iflychat','MOD_IDLE');
        $iflychat_settings['text_busy'] = $language->text('iflychat','MOD_BUSY');
        $iflychat_settings['text_offline'] = $language->text('iflychat','MOD_OFFLINE');
        $iflychat_settings['text_lmm'] = $language->text('iflychat','MOD_LOAD_MORE_MESSAGES');
        $iflychat_settings['text_nmm'] = $language->text('iflychat','MOD_NO_MORE_MESSAGES');
        $iflychat_settings['text_clear_room'] = $language->text('iflychat','MOD_CLEAR_ALL_MESSAGES');
        $iflychat_settings['msg_p'] = $language->text('iflychat','MOD_TYPE_AND_PRESS_ENTER');
        if(($obj->params('iflychat_show_admin_list') == 1)) {
            $iflychat_settings['text_support_chat_init_label'] = $obj->params('iflychat_support_chat_init_label');
            $iflychat_settings['text_support_chat_box_header'] = $obj->params('iflychat_support_chat_box_header');
            $iflychat_settings['text_support_chat_box_company_name'] = $obj->params('iflychat_support_chat_box_company_name');
            $iflychat_settings['text_support_chat_box_company_tagline'] = $obj->params('iflychat_support_chat_box_company_tagline');
            $iflychat_settings['text_support_chat_auto_greet_enable'] = $obj->params('iflychat_support_chat_auto_greet_enable');
            $iflychat_settings['text_support_chat_auto_greet_message'] = $obj->params('iflychat_support_chat_auto_greet_message');
            $iflychat_settings['text_support_chat_auto_greet_time'] = $obj->params('iflychat_support_chat_auto_greet_time');
            $iflychat_settings['text_support_chat_offline_message_label'] = $obj->params('iflychat_support_chat_offline_message_label');
            $iflychat_settings['text_support_chat_offline_message_contact'] = $obj->params('iflychat_support_chat_offline_message_contact');
            $iflychat_settings['text_support_chat_offline_message_send_button'] = $obj->params('iflychat_support_chat_offline_message_send_button');
            $iflychat_settings['text_support_chat_offline_message_desc'] = $obj->params('iflychat_support_chat_offline_message_desc');
            $iflychat_settings['text_support_chat_init_label_off'] = $obj->params('iflychat_support_chat_init_label_off');
        }
        $iflychat_settings['open_chatlist_default'] = ($obj->params('iflychat_minimize_chat_user_list')==2)?'1':'2';


        $iflychat_settings['useStopWordList'] = $obj->params('iflychat_use_stop_word_list');
        $iflychat_settings['blockHL'] = $obj->params('iflychat_stop_links');
        $iflychat_settings['allowAnonHL'] = $obj->params('iflychat_allow_anon_links');
        $iflychat_settings['renderImageInline'] = ($obj->params('iflychat_allow_render_images')=='1')?'1':'2';
        $iflychat_settings['searchBar'] = ($obj->params('iflychat_enable_search_bar')=='1')?'1':'2';
        $iflychat_settings['text_search_bar'] = $language->text('iflychat','MOD_TYPE_HERE_TO_SEARCH');


        OW::getDocument()->addScript(OW::getPluginManager()->getPlugin('iflychat')->getStaticJsUrl() . 'iflychat.js');
        OW::getDocument()->addScriptDeclarationBeforeIncludes("Drupal={};Drupal.settings={};Drupal.settings.drupalchat=" . json_encode($iflychat_settings).";\n ");
        OW::getDocument()->addScriptDeclarationBeforeIncludes('window.my_var_handle ="' . OW::getPluginManager()->getPlugin('iflychat')->getStaticUrl() . '"');
        return parent::render();
    }

}
