---
title: Using variables
weight: 2
---


When using a BladeX component all attributes will be passed as variables to the underlying Blade view.

```html
{{-- the `myAlert` view will receive a variable named `type` with a value of `error` --}}

<my-alert type="error">
```

If you want to pass on a PHP variable or something that needs to be evaluated you must prefix the attribute name with `:`.

```html
{{-- the `myAlert` view will receive the contents of `$message` --}}
<my-alert type="error" :message="$message">

{{-- the `myAlert` view will receive the uppercased contents of `$message` --}}
<my-alert type="error" :message="strtoupper($message)">
```

## Spread operator for attributes

Passing an array of component attributes to a BladeX component can be achieved using the spread operator:

```html
<text-input ...$input />
``` 

Combining multiple destructured arrays and normal attributes is supported too! Normal attributes will override attributes in the spreaded attributes array.

```html
<text-field
    label="E-Mail"
    ...$input
    ...$email
    :var="$foo"
/>
```

## Boolean attributes

Boolean attributes (attributes without a value), e.g. `<checkbox checked />` will be passed to the component as variables evaluating to `true`.

```html
{{-- the `checkboxInput` view will receive a `$checked` variable that evaluates as true --}}
<checkbox-input checked />
```
