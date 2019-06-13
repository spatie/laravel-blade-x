---
title: Transforming data with view models
weight: 1
---

Before rendering a BladeX component you might want to transform the passed data, or add inject view data. You can do this using a view model. Let's take a look at an example where we render a `select` element with a list of countries.

To make a BladeX component use a view model, pass a class name to the `viewModel` method.

```php
BladeX::component('select-field')->viewModel(SelectFieldViewModel::class);
```

Before reviewing the contents of the component and the view model itself, let's take a look at the `select-field` component in use.

```blade
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

Next, let's take a look at the `SelectFieldViewModel::class`:

```php
class SelectFieldViewModel extends ViewModel
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

Notice that this class extends `\Spatie\BladeX\ViewModel`. Every attribute on the `select-field` will be passed to its constructor. This happens based on the attribute names: the `name` attribute will be passed to the `$name` constructor argument, the `options` attribute will be passed to the `$options` argument and so on. Any other argument will be resolved out of Laravel's [IoC container](https://laravel.com/docs/5.6/container), so you can inject external dependencies.

All public properties and methods on the view model will be passed to the Blade view that will render the `select-field` component. Public methods will be available in as a closure stored in the variable that is named after the public method in view model. This is what that view looks like.

```blade
<select name="{{ $name }}">
    @foreach($options as $value => $label)
        <option {!! $isSelected($value) ? 'selected="selected"' : '' !!} value="{{ $value }}">{{ $label }}</option>
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
