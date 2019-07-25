# Changelog

All notable changes to `laravel-blade-x` will be documented in this file

## 2.2.3 - 2019-07-25

- do not use deprecated Laravel helpers

## 2.2.2 - 2019-01-07

- loading views moved from `register` method to `boot`

## 2.2.1 - 2019-06-28

- fix multi-line closing tags triggering other components

## 2.2.0 - 2018-02-27

- drop support for Laravel 5.7 and lower
- drop support for PHP 7.1 and lower

## 2.1.2 - 2018-02-27

- add support for Laravel 5.8

## 2.1.1 - 2018-02-01

- use Arr:: and Str:: functions

## 2.1.0 - 2018-10-30

- add support for namespaced subdirectories

## 2.0.3 - 2018-10-22

- fix compiling empty tag attributes

## 2.0.2 - 2018-10-08

- fix edge-case for self-closing tags with newlines

## 2.0.1 - 2018-10-08

- fix edge-case for boolean attributes in opening tags

## 2.0.0 - 2018-10-08

- simplified component registration
- internal cleanup
- some edge case fixes

## 1.2.3 - 2018-10-07

- fix nested components and slots without spaces or on a single line
- fix edge-case for slots with weird content

## 1.2.2 - 2018-10-04

- remove unnecessary dependencies `symfony/css-selector` and `symfony/dom-crawler`

## 1.2.1 - 2018-10-04

- fix test

## 1.2.0 - 2018-10-04

- add support for context
- add closure based view models
- bugfixes for component attributes with weird characters

## 1.1.2 - 2018-10-02

- make sure a component is registered only once
- make sure kebab-cased props get passed to components camelCased

## 1.1.1 - 2018-10-01

- add view models

## 1.0.0 - 2018-10-01

- initial release
