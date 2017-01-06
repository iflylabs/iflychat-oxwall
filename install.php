<?php
/**
 * @package iFlyChat
 * @version 1.0.0
 * @copyright Copyright (C) 2014 iFlyChat. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @author iFlyChat Team
 * @link https://iflychat.com
 */

//OW::getConfig()->addConfig('iflychat', 'setting_vars', '{}');
$config = OW::getConfig();

if (!$config->configExists('iflychat', 'iflychat_external_api_key')) {
  $config->addConfig('iflychat', 'iflychat_external_api_key', '', 'iFlyChat Api Key');
}
if (!$config->configExists('iflychat', 'iflychat_app_id')) {
  $config->addConfig('iflychat', 'iflychat_app_id', '', 'iFlyChat App ID');
}
if (!$config->configExists('iflychat', 'iflychat_show_popup_chat')) {
  $config->addConfig('iflychat', 'iflychat_show_popup_chat', '1', 'Select where to show the pop up chat. ');
}
if (!$config->configExists('iflychat', 'iflychat_enable_friends')) {
  $config->addConfig('iflychat', 'iflychat_enable_friends', '1', 'Enable friends in user list');
}
if (!$config->configExists('iflychat', 'iflychat_path_pages')) {
  $config->addConfig('iflychat', 'iflychat_path_pages', '', 'Page list');
}
if (!$config->configExists('iflychat', 'iflychat_moderators')) {
  $config->addConfig('iflychat', 'iflychat_moderators', '', 'iFlyChat Moderators');
}
if (!$config->configExists('iflychat', 'iflychat_administers')) {
  $config->addConfig('iflychat', 'iflychat_administers', '', 'iFlyChat Admins');
}


OW::getPluginManager()->addPluginSettingsRouteName('iflychat', 'iflychat_admin');
OW::getLanguage()->importPluginLangs(OW::getPluginManager()->getPlugin('iflychat')->getRootDir() . 'langs.zip', 'iflychat');

OW_ViewRenderer::getInstance()->clearCompiledTpl();
OW::getCacheManager()->clean(array(), OW_CacheManager::CLEAN_ALL);

//$authorization = OW::getAuthorization();
//$groupName = 'iflychat';
//$authorization->addGroup($groupName);
//$authorization->addAction($groupName, 'add_chat', true);
//$authorization->addAction($groupName, 'mod');

