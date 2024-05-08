<?php
/**
 * Created by PhpStorm.
 * User: Henrik
 * Date: 2/23/2018
 * Time: 9:31 AM.
 */
declare(strict_types=1);

namespace henrik\sl\Exceptions;

use Throwable;

/**
 * Class ConfigurationException.
 */
class ConfigurationException extends InjectorException
{
    /**
     * ConfigurationException constructor.
     *
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}