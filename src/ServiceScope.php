<?php
/**
 * Created by PhpStorm.
 * User: Henrik
 * Date: 4/3/2018
 * Time: 8:54 PM.
 */

declare(strict_types=1);

namespace henrik\sl;

enum ServiceScope: string
{
    /**
     * DI singletons.
     */
    case SINGLETON = 'SINGLETON';

    /**
     * DI prototypes.
     */
    case PROTOTYPE = 'PROTOTYPE';

    /**
     * DI factories.
     */
    case FACTORY = 'FACTORY';
    /**
     * DI parameters.
     */
    case PARAM = 'PARAM';

    /**
     * DI Aliases.
     */
    case ALIAS = 'ALIAS';
}