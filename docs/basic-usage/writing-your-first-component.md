---
title: Writing your first component
weight: 1
---

The contents of a component can be stored in a simple Blade view.

```html
{{-- resources/views/components/myAlert.blade.php --}}

<div class="{{ $type }}">
   {{ $message }}
</div>
```

Before using that component you must first register it. Typically you would do this in the `AppServiceProvider boot() method` or a service provider of your own

```php
BladeX::component('components.myAlert');
```

BladeX will automatically kebab-case your Blade view name and use that as the tag for your component. So for the example above the tag to use your component would be `my-alert`.

If you want to use a custom tag name use the `tag`-method.

```php
BladeX::component('components.myAlert')->tag('my-custom-tag');
```

You can also register an entire directory like this.

```php
// This will register all Blade views that are stored in `resources/views/components`

BladeX::component('components.*');
```

Or you can register multiple directories like this.

```php
// This will register all Blade views that are stored in both `resources/views/components` and `resources/views/layouts`

BladeX::component([
   'components.*',
   'layouts.*',
]);
```

You can also register sub-directories like this.

```php
// This will register all Blade views that are stored in both `resources/views/components` and `resources/views/layouts`

BladeX::component(
   'components.**.*',
);
```

In your Blade view you can now use the component using the kebab-cased name:

```html
<h1>My view</h1>

<my-alert type="error" :message="$message" />
```
