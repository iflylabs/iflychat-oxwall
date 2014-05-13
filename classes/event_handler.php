<?php

/**
 * @package iFlyChat
 * @version 1.0.0
 * @copyright Copyright (C) 2014 iFlyChat. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @author iFlyChat Team
 * @link https://iflychat.com
 */
class IFLYCHAT_CLASS_EventHandler
{
    /**
     * Singleton instance.
     *
     * @var AJAXIM_CLASS_EventHandler
     */

    private static $classInstance;

    /**
     * Returns an instance of class (singleton pattern implementation).
     *
     * @return AJAXIM_CLASS_EventHandler
     */
    public static function getInstance()
    {
        if ( self::$classInstance === null )
        {
            self::$classInstance = new self();
        }

        return self::$classInstance;
    }

    public function genericInit()
    {
        OW::getEventManager()->bind(OW_EventManager::ON_FINALIZE, array($this, 'onPluginInit'));


    }

    public function init()
    {
        $this->genericInit();
    }

    public function onPluginInit()
    {


        $im_toolbar = new IFLYCHAT_CMP_Toolbar();
        OW::getDocument()->appendBody($im_toolbar->render());
    }

    public function hi(){

        return 'hi';
    }



}