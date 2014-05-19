<?php
/**
 * @package iFlyChat
 * @version 1.0.0
 * @copyright Copyright (C) 2014 iFlyChat. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @author iFlyChat Team
 * @link https://iflychat.com
 */

OW::getConfig()->addConfig('iflychat', 'setting_vars', '{}');
//OW::getPluginManager()->addPluginSettingsRouteName('iflychat', 'iflychat_admin');

OW::getPluginManager()->addPluginSettingsRouteName('iflychat', 'iflychat_admin');
OW::getLanguage()->importPluginLangs(OW::getPluginManager()->getPlugin('iflychat')->getRootDir() . 'langs.zip', 'iflychat');
$authorization = OW::getAuthorization();
$groupName = 'iflychat';
$authorization->addGroup($groupName);
$authorization->addAction($groupName, 'add_chat', true);
//$authorization->addAction($groupName, 'view_event', true);
//$authorization->addAction($groupName, 'add_comment');
/*$config = OW::getConfig();
$config->addConfig('iflychat', 'iflychat_external_api_key', '');
$config->addConfig('iflychat', 'iflychat_show_admin_list', '1');
$config->addConfig('iflychat', 'iflychat_theme', '1');
$config->addConfig('iflychat', 'iflychat_notification_sound', '1');
$config->addConfig('iflychat', 'iflychat_user_picture', '1');
$config->addConfig('iflychat', 'iflychat_enable_smileys', '1');
$config->addConfig('iflychat', 'iflychat_log_messages', '1');
$config->addConfig('iflychat', 'iflychat_anon_prefix', 'Guest');
$config->addConfig('iflychat', 'iflychat_anon_use_name', '1');
$config->addConfig('iflychat', 'iflychat_anon_change_name', '1');
$config->addConfig('iflychat', 'iflychat_load_chat_async', '1');
$config->addConfig('iflychat', 'iflychat_ext_d_i', '');
$config->addConfig('iflychat', 'iflychat_support_chat_init_label', 'Chat with us');
$config->addConfig('iflychat', 'iflychat_support_chat_box_header', 'Support');
$config->addConfig('iflychat', 'iflychat_support_chat_box_company_name', 'Support Team');
$config->addConfig('iflychat', 'iflychat_support_chat_box_company_tagline', $data['iflychat_support_chat_box_company_tagline']);
$config->addConfig('iflychat', 'iflychat_support_chat_auto_greet_enable', $data['iflychat_support_chat_auto_greet_enable']);
$config->addConfig('iflychat', 'iflychat_support_chat_auto_greet_message', $data['iflychat_support_chat_auto_greet_message']);
$config->addConfig('iflychat', 'iflychat_support_chat_auto_greet_time', $data['iflychat_support_chat_auto_greet_time']);
$config->addConfig('iflychat', 'iflychat_support_chat_init_label_off', $data['iflychat_support_chat_init_label_off']);
$config->addConfig('iflychat', 'iflychat_support_chat_offline_message_desc', $data['iflychat_support_chat_offline_message_desc']);
$config->addConfig('iflychat', 'iflychat_support_chat_offline_message_label', $data['iflychat_support_chat_offline_message_label']);
$config->addConfig('iflychat', 'iflychat_support_chat_offline_message_contact', $data['iflychat_support_chat_offline_message_contact']);
$config->addConfig('iflychat', 'iflychat_support_chat_offline_message_send_button', $data['iflychat_support_chat_offline_message_send_button']);
$config->addConfig('iflychat', 'iflychat_support_chat_offline_message_email', $data['iflychat_support_chat_offline_message_email']);
$config->addConfig('iflychat', 'iflychat_enable_chatroom', $data['iflychat_enable_chatroom']);
$config->addConfig('iflychat', 'iflychat_stop_word_list', $data['iflychat_stop_word_list']);
$config->addConfig('iflychat', 'iflychat_use_stop_word_list', $data['iflychat_use_stop_word_list']);
$config->addConfig('iflychat', 'iflychat_stop_links', $data['iflychat_stop_links']);
$config->addConfig('iflychat', 'iflychat_allow_anon_links', $data['iflychat_allow_anon_links']);
$config->addConfig('iflychat', 'iflychat_allow_render_images', $data['iflychat_allow_render_images']);
$config->addConfig('iflychat', 'iflychat_allow_single_message_delete', $data['iflychat_allow_single_message_delete']);
$config->addConfig('iflychat', 'iflychat_allow_clear_room_history', $data['iflychat_allow_clear_room_history']);
$config->addConfig('iflychat', 'iflychat_allow_user_font_color', $data['iflychat_allow_user_font_color']);
$config->addConfig('iflychat', 'iflychat_path_visibility', $data['iflychat_path_visibility']);
$config->addConfig('iflychat', 'iflychat_path_pages', $data['iflychat_path_pages']);
$config->addConfig('iflychat', 'iflychat_chat_topbar_color', $data['iflychat_chat_topbar_color']);
$config->addConfig('iflychat', 'iflychat_chat_topbar_text_color', $data['iflychat_chat_topbar_text_color']);
$config->addConfig('iflychat', 'iflychat_font_color', $data['iflychat_font_color']);
$config->addConfig('iflychat', 'iflychat_public_chatroom_header', $data['iflychat_public_chatroom_header']);
$config->addConfig('iflychat', 'iflychat_chat_list_header', $data['iflychat_chat_list_header']);
$config->addConfig('iflychat', 'iflychat_minimize_chat_user_list', $data['iflychat_minimize_chat_user_list']);
$config->addConfig('iflychat', 'iflychat_enable_search_bar', $data['iflychat_enable_search_bar']);
$config->addConfig('iflychat', 'iflychat_rel', $data['iflychat_rel']);
$config->addConfig('iflychat', 'iflychat_ur_name', $data['iflychat_ur_name']);
$config->addConfig('iflychat', 'iflychat_only_loggedin', $data['iflychat_only_loggedin']);*/