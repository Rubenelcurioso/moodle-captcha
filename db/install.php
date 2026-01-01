<?php
// This file is part of an external plugin for Moodle - http://moodle.org/
// This plugin is licensed under GNU Public License v3.
//
// This plugin is NOT part of the Moodle core and is provided as is. It is developed independently
// and must comply with the GNU Public License v3 requirements.

// auth_captcha/db/install.php
function xmldb_auth_captcha_install() {
    set_config('auth', 'captcha'); // Set as primary auth method
    set_config('registerauth', 'captcha');
    return true;
}