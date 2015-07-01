# Metaboxes

## Installation

First, pull in the package through Composer.

```js
"require": {
    "tonning/flash": "~0.1"
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