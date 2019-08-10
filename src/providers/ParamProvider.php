<?php
/**
 * Created by PhpStorm.
 * User: Henrik
 * Date: 4/3/2018
 * Time: 9:17 PM
 */

namespace henrik\sl\providers;


class ParamProvider extends ServiceProvider
{

    function provide()
    {
       return $this->value;
    }
}