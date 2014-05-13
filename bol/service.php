<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 4/22/14
 * Time: 2:25 PM
 */
class Iflychat_BOL_Service {

    private static $classInstance;

    public static function getInstance()
    {
        if ( self::$classInstance === null )
        {
            self::$classInstance = new self();
        }

        return self::$classInstance;
    }


}

