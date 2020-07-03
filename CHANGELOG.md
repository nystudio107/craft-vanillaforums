# Vanillaforums Changelog

## 1.0.3 - 2020.07.03
### Fixed
* Fixed an issue where the incorrect `Content-Type` header was being sent back via [#4](https://github.com/nystudio107/craft-vanillaforums/pull/4)

### Changed
* Renamed SsoDataEvent::$data to SsoDataEvent::$ssoData via [#6](https://github.com/nystudio107/craft-vanillaforums/pull/6)
* Updated docs to latest packages

## 1.0.2 - 2020.04.16
### Fixed
* Fixed Asset Bundle namespace case

## 1.0.1 - 2019.10.17
### Added
* Added a **Hash Algorithm** dropdown in Settings to allow you to choose the hash algorithm type
* Added **Authentication URL** to the Settings page, which you can copy & paste into your Vanilla Forums settings

### Changed
* `vanillaForumsClientID` & `vanillaForumsSecret` are now validated via `EnvAttributeParserBehavior`
* Updated the documentation to include information on the `SsoDataEvent` that Vanilla Forums throws
 
## 1.0.0 - 2019.10.16
### Added
- Initial release
