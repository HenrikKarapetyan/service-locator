<?php
/**
 * Created by PhpStorm.
 * User: Henrik
 * Date: 2/23/2018
 * Time: 9:28 AM
 */

namespace henrik\sl\exceptions;


use Throwable;

/**
 * Class ServerException
 * @package henrik\sl\exceptions
 */
class ServerException extends \Exception
{
    /**
     * ServerException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}