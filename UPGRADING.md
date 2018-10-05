## From v1 to v2

The way components are registered is greatly simplified.

In `v1` you could pass a custom tag of your component as a second parameter. In `v2` you'll need to use the `tag` method.

```php
// v1
BladeX::component('components.myAlert', 'my-custom-tag')

// v2
BladeX::component('components.myAlert')->tag('my-custom-tag');
```

In v1 you could register an entire directory of components using the `components()` method. This method has now been deprecated. Use the `component` method and the `.*` notation to register a directory.

```php
// v1
BladeX::components('components');

// v2
BladeX::component('components.*');
```