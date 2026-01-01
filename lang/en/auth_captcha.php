<?php
// This file is part of an external plugin for Moodle - http://moodle.org/
// This plugin is licensed under GNU Public License v3.
//
// This plugin is NOT part of the Moodle core and is provided as is. It is developed independently
// and must comply with the GNU Public License v3 requirements.
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'CAPTCHA Login Protection Authentication';
$string['auth_captchadescription'] = 'Adds CAPTCHA verification and login attempt limits to Moodle authentication.';
$string['auth_captchatitle'] = 'CAPTCHA Login Protection Authentication';

// CAPTCHA settings.
$string['captchasettings'] = 'CAPTCHA Settings';
$string['captchasettingsinfo'] = 'Configure CAPTCHA credentials for login protection.';
$string['captcha_site_key'] = 'CAPTCHA Site Key';
$string['captcha_site_key_desc'] = 'The site key from your CAPTCHA provider.';
$string['captcha_secret_key'] = 'CAPTCHA Secret Key';
$string['captcha_secret_key_desc'] = 'The secret key from your CAPTCHA provider.';

// Login protection settings.
$string['loginprotectionsettings'] = 'Login Protection Settings';
$string['loginprotectionsettingsinfo'] = 'Configure limits for failed login attempts.';
$string['max_attempts'] = 'Maximum Login Attempts';
$string['max_attempts_desc'] = 'Number of failed login attempts before temporary lockout';
$string['lockout_duration'] = 'Lockout Duration';
$string['lockout_duration_desc'] = 'Duration of the lockout in seconds';

// Error messages.
$string['error_captcha'] = 'The CAPTCHA verification failed. Please try again.';
$string['error_too_many_attempts'] = 'Too many failed login attempts. Your account has been temporarily locked for {$a} seconds.';
$string['error_login'] = 'The username or password is incorrect.';

// Custom login form.
$string['entercaptcha'] = 'Please complete the CAPTCHA verification';
$string['pleaseverifycaptcha'] = 'Please complete the CAPTCHA verification before logging in';
$string['username'] = 'Username';
$string['password'] = 'Password';
$string['login'] = 'Log in';
