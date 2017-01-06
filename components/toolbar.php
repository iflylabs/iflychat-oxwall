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
        $uid = OW::getUser()->getId();
        $r = '';
        $r .= 'var iflychat_bundle = document.createElement("script");';
        $r .= 'iflychat_bundle.src = "//'.IFLYCHAT_EXTERNAL_CDN_HOST.'/js/iflychat-v2.min.js?app_id=' . $obj->params('iflychat_app_id') . '";';
        $r .= 'iflychat_bundle.async="async";';
        $r .= 'document.body.appendChild(iflychat_bundle);';
        $r .= '';
//        if ($obj->params('iflychat_enable_friends') == '1') {
//            if (isset($_SESSION['token']) && !empty($_SESSION['token'])) {
//                $r .= 'var iflychat_auth_token = "' . $_SESSION['token'] . '";';
//            }
//        }
        if ($uid) {

            $r .= ' var iflychat_auth_url = "' . OW::getRouter()->getBaseUrl() . 'iflychat/iflychat/auth";';
        }
        if (($obj->params('iflychat_show_popup_chat') == '1')) {
            $r .= 'var iFlyChatDiv = document.createElement("div");';
            $r .= 'iFlyChatDiv.className = \'iflychat-popup\';';
            $r .= 'document.body.appendChild(iFlyChatDiv);';
//			$r .= '';
        } else if ($obj->params('iflychat_show_popup_chat') == '2' && !OW_User::getInstance()->isAdmin()) {
            $r .= 'var iFlyChatDiv = document.createElement("div");';
            $r .= 'iFlyChatDiv.className = \'iflychat-popup\';';
            $r .= 'document.body.appendChild(iFlyChatDiv);';
//			$r .= '';
        } else if (($obj->params('iflychat_show_popup_chat') == '3' || $obj->params('iflychat_show_popup_chat') == '4') && $obj->iflychat_path_check()) {
            $r .= 'var iFlyChatDiv = document.createElement("div");';
            $r .= 'iFlyChatDiv.className = \'iflychat-popup\';';
            $r .= 'document.body.appendChild(iFlyChatDiv);';
//			$r .= '';
        }
        OW::getDocument()->addScriptDeclarationBeforeIncludes($r);
        return parent::render();
    }
}
