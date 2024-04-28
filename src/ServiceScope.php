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
    case SINGLETON = 'singleton';

    /**
     * DI prototypes.
     */
    case PROTOTYPE = 'prototype';

    /**
     * DI factories.
     */
    case FACTORY = 'factory';
    /**
     * DI parameters.
     */
    case PARAM = 'param';

    /**
     * DI Aliases.
     */
    case ALIAS = 'alias';
}