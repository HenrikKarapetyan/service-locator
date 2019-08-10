<?php
/**
 * Created by PhpStorm.
 * User: Henrik-pc
 * Date: 02.02.2018
 * Time: 10:15
 */

namespace henrik\sl\exceptions;


use Throwable;

/**
 * Class ServiceNotFoundException
 * @package henrik\sl\exceptions
 */
class ServiceNotFoundException extends ServerException
{

    /**
     * ServiceNotFoundException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null)
   {
       parent::__construct($message, $code, $previous);
   }
}