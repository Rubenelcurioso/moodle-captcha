# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).

## [Unreleased]

### Added
- Privacy API provider for login attempt records
- Scheduled cleanup task for expired login attempts
- Login attempt retention setting

### Changed
- Login CAPTCHA integration now uses proper AMD module loading
- Installation no longer sets the auth method automatically
- Login rate limiting is now keyed by client IP instead of username
- Default maximum login attempts increased to 5

## [1.0.0-beta] - 2025-02-27

### Added
- Initial release of the CAPTCHA Login Protection Authentication Plugin
- Core authentication functionality with CAPTCHA verification and login attempt limits
- Custom login page renderer
- Installation script to set CAPTCHA Login Protection as primary authentication method
- Language support for English

### Changed
- Updated plugin maturity from ALPHA to BETA status
- Renamed plugin to "CAPTCHA Login Protection Authentication" for clarity
- Updated all PHP files to clearly indicate external plugin status and GPL v3 licensing
- Improved README.md with clearer navigation instructions

### Security
- Implemented CAPTCHA verification and login attempt limits
- Added proper GPL v3 license notices across all files
- Clear indication of external plugin status for security transparency

## [0.1.0-alpha] - 2025-02-17

### Added
- Initial alpha release
- Basic authentication framework
- Configuration settings interface
- Initial documentation
