# Changelog

All notable changes to `laravel-blade-x` will be documented in this file

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
