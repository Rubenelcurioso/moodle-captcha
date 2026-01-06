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

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    // CAPTCHA settings.
    $settings->add(new admin_setting_heading('auth_captcha/captchasettings',
        get_string('captchasettings', 'auth_captcha'),
        get_string('captchasettingsinfo', 'auth_captcha')));

    $settings->add(new admin_setting_configtext('auth_captcha/captcha_site_key',
        get_string('captcha_site_key', 'auth_captcha'),
        get_string('captcha_site_key_desc', 'auth_captcha'),
        '', PARAM_TEXT));

    $settings->add(new admin_setting_configtext('auth_captcha/captcha_secret_key',
        get_string('captcha_secret_key', 'auth_captcha'),
        get_string('captcha_secret_key_desc', 'auth_captcha'),
        '', PARAM_TEXT));

    // Login protection settings.
    $settings->add(new admin_setting_heading('auth_captcha/loginprotectionsettings',
        get_string('loginprotectionsettings', 'auth_captcha'),
        get_string('loginprotectionsettingsinfo', 'auth_captcha')));

    $settings->add(new admin_setting_configtext('auth_captcha/max_attempts',
        get_string('max_attempts', 'auth_captcha'),
        get_string('max_attempts_desc', 'auth_captcha'),
        '5', PARAM_INT));

    $settings->add(new admin_setting_configtext('auth_captcha/lockout_duration',
        get_string('lockout_duration', 'auth_captcha'),
        get_string('lockout_duration_desc', 'auth_captcha'),
        '300', PARAM_INT));

    $settings->add(new admin_setting_configtext('auth_captcha/attempt_retention_days',
        get_string('attempt_retention_days', 'auth_captcha'),
        get_string('attempt_retention_days_desc', 'auth_captcha'),
        '90', PARAM_INT));
}
