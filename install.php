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


OW::getPluginManager()->addPluginSettingsRouteName('iflychat', 'iflychat_admin');
OW::getLanguage()->importPluginLangs(OW::getPluginManager()->getPlugin('iflychat')->getRootDir() . 'langs.zip', 'iflychat');

OW_ViewRenderer::getInstance()->clearCompiledTpl();
OW::getCacheManager()->clean(array(),OW_CacheManager::CLEAN_ALL);

$authorization = OW::getAuthorization();
$groupName = 'iflychat';
$authorization->addGroup($groupName);
$authorization->addAction($groupName, 'add_chat', true);
$authorization->addGroup($groupName);
$authorization->addAction($groupName, 'mod');
