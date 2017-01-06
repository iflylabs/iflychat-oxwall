<?php
/**
 * @package iFlyChat
 * @version 1.0.0
 * @copyright Copyright (C) 2014 iFlyChat. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @author iFlyChat Team
 * @link https://iflychat.com
 */

require_once(OW_DIR_PLUGIN . 'iflychat' . DS . 'helper.php');

class IFLYCHAT_CTRL_iflychat extends OW_ActionController
{


    public function auth()
    {
        $obj = new iflychatHelper;
        header('Content-type: application/json');
        $setting = OW::getConfig()->getValues('iflychat');
        $response = $obj->generateToken($setting['iflychat_external_api_key']);
        exit(($response));
    }

}



