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
IFLYCHAT_CLASS_EventHandler::getInstance()->init();