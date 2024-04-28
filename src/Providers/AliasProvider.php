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
use henrik\sl\Injector;

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
        return Injector::instance()->get($this->value);
    }
}