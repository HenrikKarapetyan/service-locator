<?php
/**
 * Created by PhpStorm.
 * User: Henrik
 * Date: 4/3/2018
 * Time: 8:48 PM.
 */
declare(strict_types=1);

namespace henrik\sl\Providers;

use henrik\sl\Definition;
use henrik\sl\Injector;

/**
 * Class ServiceProvider.
 */
abstract class ServiceProvider implements ProviderInterface
{
    /**
     * ServiceProvider constructor.
     *
     * @param Injector   $injector
     * @param Definition $definition
     */
    public function __construct(
        protected Injector $injector,
        protected Definition $definition
    ) {}
}