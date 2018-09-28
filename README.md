# Very short description of the package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-blade-x.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-blade-x)
[![Build Status](https://img.shields.io/travis/spatie/laravel-blade-x/master.svg?style=flat-square)](https://travis-ci.org/spatie/laravel-blade-x)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/laravel-blade-x.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/laravel-blade-x)
[![StyleCI](https://github.styleci.io/repos/150733020/shield?branch=master)](https://github.styleci.io/repos/150733020)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-blade-x.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-blade-x)

This package provides an easy way to render custom html components in your Blade view.

Here's an example. Instead of this:

```blade
<h1>My view</h1>

@include('myAlert', ['type' => 'error'])
```

you can write this

```blade
<h1>My view</h1>

<my-alert type="error" />
```

## Installation

You can install the package via composer:

```bash
composer require spatie/laravel-blade-x
```

The package will automatically register itself.

## Usage



### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email freek@spatie.be instead of using the issue tracker.

## Postcardware

You're free to use this package, but if it makes it to your production environment we highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using.

Our address is: Spatie, Samberstraat 69D, 2060 Antwerp, Belgium.

We publish all received postcards [on our company website](https://spatie.be/en/opensource/postcards).

## Credits

- [Sebastian De Deyne](https://github.com/sebdedeyne)
- [Brent Roose](https://github.com/brendt)
- [Alex Vanderbist](https://github.com/alexvanderbist)
- [Ruben Van Assche](https://github.com/rubenvanassche)
- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

## Support us

Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

Does your business depend on our contributions? Reach out and support us on [Patreon](https://www.patreon.com/spatie). 
All pledges will be dedicated to allocating workforce on maintenance and new awesome stuff.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
