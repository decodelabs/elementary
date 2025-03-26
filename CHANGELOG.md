## v0.4.10 (2025-03-26)
* Render child before checking renderEmpty

## v0.4.9 (2025-03-25)
* Added Buffer override to Tag interface

## v0.4.8 (2025-03-25)
* Fixed renderWith Buffer return type

## v0.4.7 (2025-03-25)
* Added Renderable interface

## v0.4.6 (2025-03-14)
* Improved pretty rendering

## v0.4.5 (2025-02-20)
* Upgraded Coercion dependency

## v0.4.4 (2025-02-17)
* Improved attribute value handling

## v0.4.3 (2025-02-16)
* Fixed attribute interface handling

## v0.4.2 (2025-02-14)
* Upgraded PHPStan to v2
* Updated dependencies
* Tidied boolean logic
* Fixed Exceptional syntax
* Added PHP8.4 to CI workflow
* Made PHP8.4 minimum version

## v0.4.1 (2025-02-07)
* Fixed implicit nullable arguments
* Added @phpstan-require-implements constraints

## v0.4.0 (2024-08-21)
* Converted consts to protected PascalCase

## v0.3.0 (2024-05-07)
* Added JsonSerializable to Markup interface

## v0.2.7 (2024-04-23)
* Only re-align pretty content after tag close

## v0.2.6 (2024-04-23)
* Tag renderWith() return Buffer
* Made PHP8.1 minimum version

## v0.2.5 (2023-01-25)
* Added normalize() method to Elements

## v0.2.4 (2023-01-04)
* Improved :attr binding support

## v0.2.3 (2023-01-03)
* Fixed attribute boolean resolution in string input
* Migrated to use effigy in CI workflow
* Fixed PHP8.1 testing
* Updated composer check script

## v0.2.2 (2022-09-08)
* Updated Collections dependency
* Updated CI environment

## v0.2.1 (2022-08-24)
* Added concrete types to all members

## v0.2.0 (2022-08-23)
* Removed PHP7 compatibility
* Updated ECS to v11
* Updated PHPUnit to v9

## v0.1.8 (2022-03-11)
* Explicitly added Stringable to Markup interface

## v0.1.7 (2022-03-09)
* Transitioned from Travis to GHA
* Updated PHPStan and ECS dependencies

## v0.1.6 (2021-05-12)
* Fixed setStyles() handler in ContainerTrait

## v0.1.5 (2021-04-30)
* Allow Stringable, int and float as value in setStyle()
* Updated return type defs

## v0.1.4 (2021-04-07)
* Updated Collections

## v0.1.3 (2021-04-02)
* Added generator return value extraction to renderer

## v0.1.2 (2021-04-01)
* Improved container interface return hints
* Added version notice to readme
* Removed mb-string polyfill dependency

## v0.1.1 (2021-03-30)
* Fixed Element interface inheritance on PHP 7.2/3

## v0.1.0 (2021-03-30)
* Ported shared Markup library from Tagged
* Made base library fully abstract
* Full max level PHPStan conformance
