<?php
/**
 * Created by PhpStorm.
 * User: Henrik
 * Date: 4/3/2018
 * Time: 8:48 PM
 */

namespace henrik\sl\providers;
/**
 * Class Provider
 * @package henrik\sl\providers
 */
abstract class Provider
{
    /**
     * @return mixed
     */
    abstract function provide();
}