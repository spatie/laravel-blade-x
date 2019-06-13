---
title: Prefixing components
weight: 3
---

If you're using Vue components in combination with BladeX components, it might be worth prefixing your BladeX components to make them easily distinguishable from the rest.

Setting a global prefix can easily be done before or after registering components:

```php
BladeX::component('components.myAlert');

BladeX::prefix('x');
```

All your registered components can now be used like this:

```html
<x-my-alert message="Notice the prefix!" />
```