<?php
/**
 * Created by PhpStorm.
 * User: Henrik
 * Date: 4/3/2018
 * Time: 8:48 PM.
 */
declare(strict_types=1);

namespace henrik\sl\Providers;

use henrik\sl\Injector;

/**
 * Class ServiceProvider.
 */
abstract class ServiceProvider extends Provider
{
    /**
     * @var string
     */
    protected string $value;
    /**
     * @var array
     */
    protected array $params = [];
    /**
     * @var Injector
     */
    protected Injector $injector;

    /**
     * ServiceProvider constructor.
     *
     * @param Injector $injector
     * @param string $value
     * @param array $params
     */
    public function __construct(Injector $injector, string $value, array $params = [])
    {
        $this->value = $value;
        $this->params = $params;
        $this->injector = $injector;
    }
}