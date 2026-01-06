<?php
// This file is part of an external plugin for Moodle - http://moodle.org/
// This plugin is licensed under GNU Public License v3.
//
// This plugin is NOT part of the Moodle core and is provided as is. It is developed independently
// and must comply with the GNU Public License v3 requirements.

defined('MOODLE_INTERNAL') || die();

/**
 * Upgrade steps for auth_captcha.
 *
 * @param int $oldversion The version being upgraded from.
 * @return bool
 */
function xmldb_auth_captcha_upgrade(int $oldversion): bool {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2025021900) {
        $oldtable = new xmldb_table('auth_bf_attempts');
        $newtable = new xmldb_table('auth_captcha_attempts');
        if ($dbman->table_exists($oldtable) && !$dbman->table_exists($newtable)) {
            $dbman->rename_table($oldtable, 'auth_captcha_attempts');
        }

        $oldconfig = get_config('auth_bf');
        if (!empty($oldconfig)) {
            foreach ($oldconfig as $key => $value) {
                set_config($key, $value, 'auth_captcha');
            }
        }

        upgrade_plugin_savepoint(true, 2025021900, 'auth', 'captcha');
    }

    if ($oldversion < 2025030100) {
        $table = new xmldb_table('auth_captcha_attempts');
        if ($dbman->table_exists($table)) {
            $DB->delete_records('auth_captcha_attempts');

            $ipfield = new xmldb_field('ip', XMLDB_TYPE_CHAR, '45', null, XMLDB_NOTNULL, null, '', 'id');
            if (!$dbman->field_exists($table, $ipfield)) {
                $dbman->add_field($table, $ipfield);
            }

            $usernameindex = new xmldb_index('username', XMLDB_INDEX_UNIQUE, ['username']);
            if ($dbman->index_exists($table, $usernameindex)) {
                $dbman->drop_index($table, $usernameindex);
            }

            $usernamefield = new xmldb_field('username');
            if ($dbman->field_exists($table, $usernamefield)) {
                $dbman->drop_field($table, $usernamefield);
            }

            $ipindex = new xmldb_index('ip', XMLDB_INDEX_UNIQUE, ['ip']);
            if (!$dbman->index_exists($table, $ipindex)) {
                $dbman->add_index($table, $ipindex);
            }
        }

        upgrade_plugin_savepoint(true, 2025030100, 'auth', 'captcha');
    }

    return true;
}
