<?php
/**
 * Transient class
 *
 * @package Svangr.
 */

namespace Silvanus\Svangr;

// Contracts.
use \Psr\SimpleCache as PSR16;

// Exceptions.
use Silvanus\Svangr\Exceptions\CacheException;
use Silvanus\Svangr\Exceptions\InvalidArgumentException;
use Silvanus\Svangr\Exceptions\Transient\TooLongKeyException;

/**
 * --> Transients cache strategy
 * --> Provides PSR-16 implementation for transient caching.
 */
class Transient implements PSR16\CacheInterface
{

    /**
     * Cache namespace.
     * Used to avoid conflicts with other transients.
     *
     * @var string.
     */
    protected $namespace;

    /**
     * Allowed max for transient keys.
     *
     * @var int.
     */
    protected $allowed_key_length;

    /**
     * Default transient expire time if not given.
     *
     * @var int.
     */
    const DEFAULT_EXPIRE = 3600;

    /**
     * General WP transient length limit.
     * Used to adjust cache key length with namespace.
     *
     * @var int.
     */
    const WP_TRANSIENT_MAX = 172;

    /**
     * Creates new cache instance.
     *
     * @param string $namespace of cache.
     */
    public function __construct(string $namespace)
    {
        $this->checkKeyValidity($namespace);

        $this->namespace = $namespace;

        $this->allowed_key_length = self::WP_TRANSIENT_MAX - strlen($namespace);
    }

    /**
     * Fetches a value from the cache.
     *
     * @param string $key     The unique key of this item in the cache.
     * @param mixed  $default Default value to return if the key does not exist.
     *
     * @return mixed The value of the item from the cache, or $default in case of cache miss.
     */
    public function get($key, $default = null)
    {
        $key = $this->get_namespaced_key($key);

        $transient = \get_transient( $key );

        $value = $transient !== false ? $transient : $default;

        return $value;
    }

    /**
     * Delete transient by key.
     *
     * @param string $key of transient.
     * @param string $value to be stored.
     * @param string $ttl expiration of transient.
     * @return bool $transient true/false.
     */
    public function set($key, $value, $ttl = null)
    {
        $key = $this->get_namespaced_key($key);

        $ttl = $ttl ?? self::DEFAULT_EXPIRE;

        $transient = \set_transient($key, $value, $ttl );

        return $transient;
    }

    /**
     * Delete transient by key.
     *
     * @param string $key of transient.
     * @return bool $transient true/false.
     */
    public function delete($key)
    {
        $key       = $this->get_namespaced_key($key);
        $transient = \delete_transient( $key );

        return $transient;
    }

    /**
     * Checks transient existanxe by key.
     *
     * @param string $key of transient.
     * @return bool $result true/false.
     */
    public function has($key) {
        $key = $this->get_namespaced_key($key);

        $transient = \get_transient( $key );

        $result = $transient ? true : false;

        return $result;
    }

    /**
     * Wipes clean the entire cache's keys.
     *
     * @return bool True on success and false on failure.
     */
    abstract public function clear();

    /**
     * Obtains multiple cache items by their unique keys.
     *
     * @param iterable $keys    A list of keys that can be obtained in a single operation.
     * @param mixed    $default Default value to return for keys that do not exist.
     *
     * @return iterable A list of key => value pairs. Cache keys that do not exist or are stale will have $default as value.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *   MUST be thrown if $keys is neither an array nor a Traversable,
     *   or if any of the $keys are not a legal value.
     */
    abstract public function getMultiple($keys, $default = null);

    /**
     * Persists a set of key => value pairs in the cache, with an optional TTL.
     *
     * @param iterable               $values A list of key => value pairs for a multiple-set operation.
     * @param null|int|\DateInterval $ttl    Optional. The TTL value of this item. If no value is sent and
     *                                       the driver supports TTL then the library may set a default value
     *                                       for it or let the driver take care of that.
     *
     * @return bool True on success and false on failure.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *   MUST be thrown if $values is neither an array nor a Traversable,
     *   or if any of the $values are not a legal value.
     */
    abstract public function setMultiple($values, $ttl = null);

    /**
     * Deletes multiple cache items in a single operation.
     *
     * @param iterable $keys A list of string-based keys to be deleted.
     *
     * @return bool True if the items were successfully removed. False if there was an error.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *   MUST be thrown if $keys is neither an array nor a Traversable,
     *   or if any of the $keys are not a legal value.
     */
    abstract public function deleteMultiple($keys);

    /**
     * Gets namespaced key.
     * Transient strategy appends namespace string before DB saving.
     *
     * @param string $key to modify.
     * @return string $key which was modified.
     */
    protected function get_namespaced_key(string $key) : string
    {
        $this->checkKeyValidity($key);
        
        $key = $this->namespace . '_' . $key;

        return $key;
    }

    /**
     * Check if given key is valid.
     *
     * @param string $key to be checked.
     */
    protected function checkKeyValidity(string $key) : void
    {
        // Generally usable.
        if (!is_string($key) || strlen($key) === 0) :
            throw new InvalidArgumentException('Invalid key for cache: ' . $key);
        endif;

        // Abides WP requirements.
        if(strlen($key) > $this->allowed_key_length) ) :
            throw new TooLongKeyException();
        endif;
    }
}
