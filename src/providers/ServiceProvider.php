<?php
/**
 * Created by PhpStorm.
 * User: Henrik
 * Date: 4/3/2018
 * Time: 8:48 PM
 */

namespace henrik\sl\providers;


use henrik\sl\Injector;
use henrik\sl\ServiceScope;

/**
 * Class ServiceProvider
 * @package henrik\sl\providers
 */
abstract class ServiceProvider extends Provider implements ServiceScope
{
    /**
     * @var string
     */
    protected $value;
    /**
     * @var array
     */
    protected $params = [];
    /**
     * @var Injector
     */
    protected $injector;

    /**
     * ServiceProvider constructor.
     * @param Injector
     * @param $value
     * @param array $params
     */
    public function __construct($injector, $value, $params = [])
    {
        $this->value = $value;
        $this->params = $params;
        $this->injector = $injector;
    }
}