# Use custom html components in your Blade views

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-blade-x.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-blade-x)
[![Build Status](https://img.shields.io/travis/spatie/laravel-blade-x/master.svg?style=flat-square)](https://travis-ci.org/spatie/laravel-blade-x)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/laravel-blade-x.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/laravel-blade-x)
[![StyleCI](https://github.styleci.io/repos/150733020/shield?branch=master)](https://github.styleci.io/repos/150733020)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-blade-x.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-blade-x)

This package provides an easy way to render custom html components in your Blade views.

Here's an example. Instead of this:

```blade
<h1>My view</h1>

@include('myAlert', ['type' => 'error', 'message' => $message])
```

you can write this

```blade
<h1>My view</h1>

<my-alert type="error" :message="$message" />
```

You can place the content of that alert in a simple blade view that needs to be [registered](https://github.com/spatie/laravel-blade-x#usage) before using the `my-alert` component.

```blade
{{-- resources/views/components/myAlert.blade.php --}}

<div :class="$type">
   {{ $message }}
</div>
```

## Installation

You can install the package via composer:

```bash
composer require spatie/laravel-blade-x
```

The package will automatically register itself.

## Usage

The contents of a component can be stored in a simple Blade view.

```blade
{{-- resources/views/components/myAlert.blade.php --}}

<div :class="$type">
   {{ $message }}
</div>
```

Before using that component you must first register it. Typically you would do this in the `AppServiceProvider` or a service provider of your own

```php
BladeX::component('my-alert', 'components.myAlert')
```

You can also register an entire directory like this.

```php
// This will register all Blade views that are stored in `resources/views/components`

BladeX::components('components')
```

Or you can register multiple directories like this.

```php
// This will register all Blade views that are stored in both `resources/views/components` and `resources/views/layouts`

BladeX::components(['components', 'layouts'])
```

In your Blade view you can now use the component using the kebab-cased name:

```blade
<h1>My view</h1>

<my-alert type="error" :message="$message" />
```

### Using variables

When using a BladeX component all attributes will be passed as variables to the underlying Blade view.

```html
{{-- the `myAlert` view will receive a variable named `type` with a value of `error` --}}

<my-alert type="error">
```

If you want to pass on a php variable or something that needs to be evaluated you must prefix the attribute name with `:`.

```html
{{-- the `myAlert` view will receive the contents of `$message` --}}
<my-alert type="error" :message="$message">

{{-- the `myAlert` view will receive the uppercased contents of `$message` --}}
<my-alert type="error" :message="strtoupper($message)">
```

### Using slots

BladeX support slots too. This layout component

```blade
{{-- resources/views/components/layout.blade.php --}}

<div>
    <h1>{{ $title }}</h1>
    <div class="flex">
        <div class="w-1/3">
            {{ $sidebar }}
        </div>
        <div class="w-2/3">
            {{ $slot }}
        </div>
    </div>
    <footer>
        {{ $footer }}
    </footer>
</div>
```

can be used in your views like this:

```html
<layout title="Zed's chopper">
    <slot name="sidebar">
        <ul>
            <li>Home</li>
            <li>Contact</li>
        </ul>
    </slot>

    <main class="content">Whose motorcycle is this?</main>

    <slot name="footer">It's not a motorcycle honey, it's a chopper.</slot>
</layout>
```

### Using view models

Before rendering a BladeX component you might want to transform the passed data, or add inject view data. You can do this using a view model. Let's take a look at an example where we render a `select` element with a list countries.

To make a BladeX component use a view model, pass a class name to the `viewModel` method.

```php
BladeX::component('select-field')->viewModel(SelectViewModel::class);
```

Before reviewing the contents of the component and the view model itself, let's take a look at the `select-field` component in use.

```html
@php
// In a real app this data would probably come from a controller
// or a view composer.
$countries = [
    'be' => 'Belgium',
    'fr' => 'France',
    'nl' => 'The Netherlands',
];
@endphp

<select-field name="countries" :options="$countries" selected="fr" />
```

Next, let's take a look at the `SelectViewModel::class`:

```php
class SelectViewModel extends ViewModel
{
    /** @var string */
    public $name;

    /** @var array */
    public $options;

    /** @var string */
    private $selected;

    public function __construct(string $name, array $options, string $selected = null)
    {
        $this->name = $name;

        $this->options = $options;

        $this->selected = old($name, $selected);
    }

    public function isSelected(string $optionName): bool
    {
        return $optionName === $this->selected;
    }
}
```

Notice that this class extends `\Spatie\BladeX\ViewModel`. Every attribute on the `select-field` will be passed to its constructor. This happens based on the attribute names,, the `name` attribute will be passed to the `$name` constructor argument, the `options` attribute will be passed to the `$options` argument and so on. Any other argument will be resolved out of Laravel's [IoC container](https://laravel.com/docs/5.6/container), so you can inject external dependencies.

All public properties and methods on the view model will be passed to the Blade view that will render the `select-field` component. Public methods will be available in as a closure stored in the variable that is named after the public method in view model. This is what that view looks like.

```html
<select name="{{ $name }}">
    @foreach($options as $value => $label)
        <option {!! $isSelected($value) ? 'selected="selected"' : '' !!} name="{{ $value }}">{{ $label }}</option>
    @endforeach
</select>
```

When rendering the BladeX component, this is the output:

```html
<div>
    <select name="countries">
        <option name="be">Belgium</option>
        <option selected="selected" name="fr">France</option>
        <option name="nl">The Netherlands</option>
    </select>
</div>
```

### Using context

By default BladeX components only have access to variables that are passed to them via attributes or via the view model. In some cases you might find yourself passing the same variables to multiple components. Take a look at this example where we are building a form using some BladeX components.

```html
<input-field name="first_name" :model="$user" />

<input-field name="last_name" :model="$user" />

<input-field name="email" :model="$user" />
```

You can avoid having to pass `$user` to each component separatly by using a special component called `context`.

You can rewrite the above as

```html
<context :model="$user">
    <input-field name="first_name" />

    <input-field name="last_name" />

    <input-field name="email" />
</context>
```

### Prefixing components

If you're using Vue components in combination with BladeX components, it might be worth prefixing your BladeX components to make them easily distinguishable from the rest.

Setting a global prefix can easily be done before or after registering components:

```php
BladeX::component('components.myAlert', 'my-alert')

BladeX::prefix('x');
```

All your registered components can now be used like this:

```blade
<x-my-alert message="Notice the prefix!" />
```

## Under the hood

When you register a component

```php
BladeX::component('components.myAlert', 'my-alert')
```
with this html

```blade
{{-- resources/views/components/myAlert.blade.php --}}
<div class="{{ $type }}">
   {{ $message }}
</div>
```

and use it in your Blade view like this,

```blade
<my-alert type="error" message="{{ $message }}" />
```

our package will replace that html in your view with this:

```blade
@component('components/myAlert', ['type' => 'error','message' => $message,])@endcomponent
```

After that conversion Blade will compile (and possibly cache) that view.

Because all this happens before any html is sent to the browser, client side frameworks like Vue.js will never see the original html you wrote (with the custom tags).


## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

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
