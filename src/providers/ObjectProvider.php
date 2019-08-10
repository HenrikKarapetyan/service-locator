<?php
/**
 * Created by PhpStorm.
 * User: Henrik
 * Date: 4/3/2018
 * Time: 9:02 PM
 */

namespace henrik\sl\providers;

/**
 * Class ObjectProvider
 * @package henrik\sl\providers
 */
abstract class ObjectProvider extends ServiceProvider
{
    /**
     * @var object
     */
    protected $instance;
}