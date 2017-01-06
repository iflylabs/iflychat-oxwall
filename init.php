<?php
/**
 * @package iFlyChat
 * @version 1.0.0
 * @copyright Copyright (C) 2014 iFlyChat. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @author iFlyChat Team
 * @link https://iflychat.com
 */

OW::getRouter()->addRoute(new OW_Route('iflychat_admin', 'admin/plugins/iflychat', 'IFLYCHAT_CTRL_Admin', 'index'));
OW::getRouter()->addRoute(new OW_Route('iflychat_admin_customization', 'admin/plugins/iflychat/app-setting', 'IFLYCHAT_CTRL_Admin', 'customization'));
OW::getRouter()->addRoute(new OW_Route('iflychat_admin_dashboard', 'iflychat/app-dashboard', 'IFLYCHAT_CTRL_Admin', 'dashboard'));
//OW::getConfig()->deleteConfig('iflychat', 'setting_vars');
IFLYCHAT_CLASS_EventHandler::getInstance()->init();