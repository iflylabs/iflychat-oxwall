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


    private static $classInstance;


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
        OW::getEventManager()->bind('admin.add_auth_labels', array($this, 'onCollectAuthLabels'));


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


    public function onCollectAuthLabels( BASE_CLASS_EventCollector $event )
    {
        $language = OW::getLanguage();
        $event->add(
            array(
                'iflychat' => array(
                    'label' => $language->text('iflychat', 'auth_group_label'),
                    'actions' => array(
                        'add_chat' => $language->text('iflychat', 'auth_action_label_chat'),
                        'mod' => $language->text('iflychat', 'auth_action_label_admin')
                    )
                )
            )
        );

    }


}