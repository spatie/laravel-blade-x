---
title: Using slots
weight: 3
---


BladeX support slots too. This layout component

```html
{{-- resources/views/components/layout.blade.php --}}

<div>
    <h1>{{ $title }}</h1>
    <div class="flex">
        <div class="w-1/3">
            {{ $sidebar }}
        </div>
        <div class="w-2/3">
            {!! $slot !!}
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
    <!-- is available as $sidebar - the name is used as variable name -->
    <slot name="sidebar">
        <ul>
            <li>Home</li>
            <li>Contact</li>
        </ul>
    </slot>

    <!-- everything else inside a component is available as $slot -->
    <main class="content">Whose motorcycle is this?</main>

    <!-- is available as $footer - the name is used as variable name -->
    <slot name="footer">It's not a motorcycle honey, it's a chopper.</slot>
</layout>
```

and will result in:

```html
<div>
    <h1>Zed's chopper</h1>
    <div class="flex">
        <div class="w-1/3">
            <ul>
                <li>Home</li>
                <li>Contact</li>
            </ul>
        </div>
        <div class="w-2/3">
            <main class="content">Whose motorcycle is this?</main>
        </div>
    </div>
    <footer>
        It's not a motorcycle honey, it's a chopper.
    </footer>
</div>
```
