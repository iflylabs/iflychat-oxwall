<?php

/**
 * @package iFlyChat
 * @version 1.0.0
 * @copyright Copyright (C) 2014 iFlyChat. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @author iFlyChat Team
 * @link https://iflychat.com
 */

//Update language
Updater::getLanguageService()->importPrefixFromZip(dirname(__FILE__).DS.'langs.zip', 'iflychat');

//Clearing cache
OW_ViewRenderer::getInstance()->clearCompiledTpl();
OW::getCacheManager()->clean(array(),OW_CacheManager::CLEAN_ALL);