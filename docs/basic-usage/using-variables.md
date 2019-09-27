---
title: Using variables
weight: 2
---


When using a BladeX component all attributes will be passed as variables to the underlying Blade view.

```html
{{-- the `myAlert` view will receive a variable named `type` with a value of `error` --}}

<my-alert type="error">
```

If you're using basic Blade echo statements, BladeX will handle that for you automatically:

```html
{{-- the `myAlert` view will receive a variable named `message` with an HtmlString having the value `'Oops: '.e($message)` --}}
<my-alert type="error" message="Oops: {{ $message }}">
```

Please note that BladeX uses `Illuminate\Support\HtmlString` objects to help prevent double-encoding of variables, 
so in the edge-case where you absolutely need the variable to be a string, you must cast it using `(string) $message`.

If you want to pass on a something that needs to be evaluated you must prefix the attribute name with `:`.

```html
{{-- the `myAlert` view will receive the array of messages supplied --}}
<my-alert type="error" :messages="[$message1, $message2, $message3]">
```

Boolean attributes (attributes without a value), e.g. `<checkbox checked />` will be passed to the component as variables evaluating to `true`.

```html
{{-- the `checkboxInput` view will receive a `$checked` variable that evaluates as true --}}
<checkbox-input checked />
```
