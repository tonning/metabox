# Metaboxes

## Installation

First, pull in the package through Composer.

```js
"require": {
    "tonning/media": "~0.1"
}
```

And then, if using Laravel 5.1, include the service provider within `app/config/app.php`.

```php
'providers' => [
    Tonning\Metabox\MetaboxServiceProvider::class
];
```

And, for convenience, add a facade alias to this same file at the bottom:

```php
'aliases' => [
    'Metabox' => Tonning\Metabox\MetaboxFacade::class
];
```

On Eloquent models that uses metaboxes add
```php
/**
 * Serialize the meta data on persist
 *
 * @param $meta
 */
public function setMetaAttribute($meta)
{
    $this->attributes['meta'] = serialize($meta);
}

/**
 * Unserialize the meta data on retrival
 *
 * @param $meta
 * @return mixed
 */
public function getMetaAttribute($meta)
{
    return unserialize($meta);
}
```