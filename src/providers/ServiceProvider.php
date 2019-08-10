<?php
/**
 * Created by PhpStorm.
 * User: Henrik
 * Date: 4/3/2018
 * Time: 8:48 PM
 */

namespace henrik\sl\providers;


use henrik\sl\ServiceScope;

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
     * ServiceProvider constructor.
     * @param $value
     * @param array $params
     */
    public function __construct($value, $params = [])
    {
        $this->value = $value;
        $this->params = $params;
    }
}