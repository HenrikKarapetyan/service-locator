<?php
/**
 * Created by PhpStorm.
 * User: Henrik
 * Date: 2/23/2018
 * Time: 9:31 AM
 */

namespace henrik\sl\exceptions;


use Exception;
use Throwable;

/**
 * Class ConfigurationException
 * @package henrik\sl\exceptions
 */
class ConfigurationException extends InjectorException
{
    /**
     * ConfigurationException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}