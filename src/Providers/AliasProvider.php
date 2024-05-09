<?php
/**
 * Created by PhpStorm.
 * User: Henrik
 * Date: 4/3/2018
 * Time: 9:13 PM.
 */
declare(strict_types=1);

namespace henrik\sl\Providers;

use Exception;
use henrik\container\exceptions\ServiceNotFoundException;
use henrik\sl\Exceptions\InvalidAliasException;
use henrik\sl\DependencyInjector;

class AliasProvider extends ServiceProvider
{
    /**
     * @throws Exception
     * @throws ServiceNotFoundException
     *
     * @return mixed
     */
    public function provide(): mixed
    {
        if (is_string($this->definition->getValue())) {
            return DependencyInjector::instance()->get($this->definition->getValue());
        }

        throw new InvalidAliasException('Invalid alias provided');
    }
}