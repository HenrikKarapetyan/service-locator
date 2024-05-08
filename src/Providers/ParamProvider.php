<?php
/**
 * Created by PhpStorm.
 * User: Henrik
 * Date: 4/3/2018
 * Time: 9:17 PM.
 */
declare(strict_types=1);

namespace henrik\sl\Providers;

class ParamProvider extends ServiceProvider
{
    /**
     * @return mixed
     */
    public function provide(): mixed
    {
        return $this->definition->getValue();
    }
}