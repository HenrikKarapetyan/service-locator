<?php
/**
 * Created by PhpStorm.
 * User: Henrik
 * Date: 4/3/2018
 * Time: 8:48 PM.
 */
declare(strict_types=1);

namespace henrik\sl\Providers;

/**
 * Class Provider.
 */
abstract class Provider
{
    /**
     * @return mixed
     */
    abstract public function provide(): mixed;
}