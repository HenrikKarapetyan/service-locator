<?php
/**
 * Created by PhpStorm.
 * User: Henrik
 * Date: 4/3/2018
 * Time: 8:54 PM
 */

namespace henrik\sl;


interface ServiceScope
{
    /**
     * DI singletons
     */
    const SINGLETON = "singleton";
    /**
     * DI prototypes
     */
    const PROTOTYPE = "prototype";
    /**
     * DI parameters
     */
    const PARAM = "param";

    /**
     * DI Aliases
     */
    const ALIAS = 'alias';

    /**
     * DI scopes Array
     */
    const SCOPES = [self::SINGLETON, self::PROTOTYPE, self::ALIAS, self::PARAM];
}