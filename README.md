# Supercharged Blade components

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-blade-x.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-blade-x)
[![Build Status](https://img.shields.io/travis/spatie/laravel-blade-x/master.svg?style=flat-square)](https://travis-ci.org/spatie/laravel-blade-x)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/laravel-blade-x.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/laravel-blade-x)
[![StyleCI](https://github.styleci.io/repos/150733020/shield?branch=master)](https://github.styleci.io/repos/150733020)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-blade-x.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-blade-x)

This package provides an easy way to render custom HTML components in your Blade views.

Here's an example. Instead of this

```blade
<h1>My view</h1>

@include('myAlert', ['type' => 'error', 'message' => $message])
```

you can write this:

```blade
<h1>My view</h1>

<my-alert type="error" :message="$message" />
```

You can place the content of that alert in a simple Blade view that needs to be [registered](https://docs.spatie.be/laravel-blade-x/v2/basic-usage/writing-your-first-component) before using the `my-alert` component.

```blade
{{-- resources/views/components/myAlert.blade.php --}}

<div class="{{ $type }}">
   {{ $message }}
</div>
```

## Installation

You can install the package via Composer:

```bash
composer require spatie/laravel-blade-x
```

The package will automatically register itself.

## Documentation

You'll find the documentation on [https://docs.spatie.be/laravel-blade-x/v2/introduction](https://docs.spatie.be/laravel-blade-x/v2/introduction).

Find yourself stuck using the package? Found a bug? Do you have general questions or suggestions for improving the media library? Feel free to [create an issue on GitHub](https://github.com/spatie/laravel-blade-x/issues), we'll try to address it as soon as possible.

If you've found a bug regarding security please mail [freek@spatie.be](mailto:freek@spatie.be) instead of using the issue tracker.

## Upgrading major versions

Please see [UPGRADING](UPGRADING.md) for more information on how to upgrade from one major version to the other.

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Postcardware

You're free to use this package, but if it makes it to your production environment we highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using.

Our address is: Spatie, Samberstraat 69D, 2060 Antwerp, Belgium.

We publish all received postcards [on our company website](https://spatie.be/en/opensource/postcards).

## Credits

- [Brent Roose](https://github.com/brendt)
- [Alex Vanderbist](https://github.com/alexvanderbist)
- [Ruben Van Assche](https://github.com/rubenvanassche)
- [Sebastian De Deyne](https://github.com/sebdedeyne)
- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

## Support us

Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

Does your business depend on our contributions? Reach out and support us on [Patreon](https://www.patreon.com/spatie).
All pledges will be dedicated to allocating workforce on maintenance and new awesome stuff.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
