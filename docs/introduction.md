---
title: Introduction
weight: 1
---

# Notice

We have abandoned this package because Laravel 7 introduced native support for Blade-X style components. 

Only use this package if you're on Laravel 6 or below.

When upgrading to Laravel 7 you should convert your Blade X components to native Laravel Blade components

# Introduction

This package provides an easy way to render custom HTML components in your Blade views.

Instead of this:

```blade
<h1>My view</h1>

@include('myAlert', ['type' => 'error', 'message' => $message])
```

you can write this

```blade
<h1>My view</h1>

<my-alert type="error" :message="$message" />
```

You can place the content of that alert in a simple blade view that needs to be [registered](https://docs.spatie.be/laravel-blade-x/v2/basic-usage/writing-your-first-component) before using the `my-alert` component.

```blade
{{-- resources/views/components/myAlert.blade.php --}}

<div class="{{ $type }}">
   {{ $message }}
</div>
```

### A note on performance

Because our package operates before Blade compiles views there is no performance penalty. Blade can just cache all views. 

Because all the transformations are done serverside, there are no interop problems with a clientside framework such as Vue or React.

For more information on how the transformation is done, checkout [the "From BladeX to Blade" section](https://docs.spatie.be/laravel-blade-x/v2/under-the-hood/from-bladex-to-blade).
