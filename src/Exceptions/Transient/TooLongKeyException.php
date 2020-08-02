<?php
/**
 * Too long key Exception.
 *
 * @package Svangr.
 */

namespace Silvanus\Svangr\Exceptions\Transient;

use \Exception;

/**
 * Handle invalid arguments in cache.
 */
class TooLongKeyException extends Exception
{
    protected $message = 'Too long key provided for transient. WP required keys to be less than 172 characters.';
}
