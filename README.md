# Svangr
Programmatic cache for WP. Implements [PSR16 cache interface](https://www.php-fig.org/psr/psr-16/).

## Install

Via Composer:

`composer require silvanus/svangr`

To use autoloading mechanism, you must include `vendor/autoload.php` file in your code.

## Usage.

Currently has one strategy: transients.

### Transient

Working with transients in WP can be a bit awkward and lead to lot of boilerplate code. Svangr offers simple api to work with transients.


#### Set / Get / Delete
```php
<?php

// Transient cache strategy.
use Silvanus\Svangr\Cache\Transient as Cache;

/**
 * Create new cache instance with key.
 * Key is used as a namespace
 * that binds key/value pairs together.
 */
$cache = new Cache('resources');

// Check existance of value in cache.
$cache->has('myStuff'); // true / false.

// Set individual value.
$cache->set('myStuff', myTimeConsumingFunction());

// Set individual value with refresh time -> defaults to 60 minutes if empty.
$cache->set('willBeGoneInFiveMinutes', myFiveMinuteStuff(), 300)

// Get individual value.
$cache->get('myStuff');

// Delete individual value.
$cache->delete('myStuff'):

```

#### Working with multiple values
```php
<?php

// Transient cache strategy.
use Silvanus\Svangr\Cache\Transient as Cache;

// Namespaced cache instance.
$cache = new Cache('resources');

// Set many values
$cache->setMultiple( array( 'events' => myFoo(), 'items' => myBar(), 'resources' => myBaz() ) );

// Get many values. Returned as key => value array.
$cache->getMultiple( 'events', 'items', 'resources' );

// Delete multiple values.
$cache->deleteMultiple( array( 'events', 'items', 'resources' ) ):

```

### Clearing all transients

```php
<?php

// Transient cache strategy.
use Silvanus\Svangr\Cache\Transient as Cache;

// Namespaced cache instance.
$cache = new Cache('resources');

/**
 * Clear all cache values in namespace.
 * NOTE: uses WPDB.
 * If your installation has drop-in replacement for transients,
 * clear might not work. Use delete() method then.
 */
$cache->clear();

```

## Whats in the name?

["Svangr"](https://en.wiktionary.org/wiki/svangr) is [Old Norse](https://en.wikipedia.org/wiki/Old_Norse) word for "hungry" or "thin". Caches are hungry, and this is implementation is a thin abstraction.
