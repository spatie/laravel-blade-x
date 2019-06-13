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