<?php
/**
 * Cache Exception.
 *
 * @package Svangr.
 */

namespace Silvanus\Svangr\Exceptions;

use \Psr\SimpleCache as PSR16;
use \Exception;

/**
 * Handle generic cache exception.
 */
class CacheException extends Exception implements PSR16\CacheException
{
    protected $message = 'Generic cache exception';
}
