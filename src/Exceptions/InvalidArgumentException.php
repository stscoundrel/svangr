<?php
/**
 * Invalid Argument Exception.
 *
 * @package Svangr.
 */

namespace Silvanus\Svangr\Exceptions;

use \Psr\SimpleCache as PSR16;
use \Exception;

/**
 * Handle invalid arguments in cache.
 */
class InvalidArgumentException extends Exception implements PSR16\InvalidArgumentException
{
    protected $message = 'Invalid argument exception';
}
