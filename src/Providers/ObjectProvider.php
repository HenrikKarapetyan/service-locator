<?php
/**
 * Created by PhpStorm.
 * User: Henrik
 * Date: 4/3/2018
 * Time: 9:02 PM.
 */
declare(strict_types=1);

namespace henrik\sl\Providers;

/**
 * Class ObjectProvider.
 */
abstract class ObjectProvider extends ServiceProvider
{
    /**
     * @var ?object
     */
    protected ?object $instance = null;
}