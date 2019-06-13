---
title: From BladeX to Blade
weight: 1
---

When you register a component

```php
BladeX::component('components.myAlert')
```
with this HTML

```html
{{-- resources/views/components/myAlert.blade.php --}}
<div class="{{ $type }}">
   {{ $message }}
</div>
```

and use it in your Blade view like this,

```html
<my-alert type="error" :message="$message" />
```

our package will replace that HTML in your view with this:

```html
@component('components/myAlert', ['type' => 'error','message' => $message,])@endcomponent
```

After that conversion Blade will compile (and possibly cache) that view.

Because all this happens before any HTML is sent to the browser, client side frameworks like Vue.js will never see the original html you wrote (with the custom tags).
