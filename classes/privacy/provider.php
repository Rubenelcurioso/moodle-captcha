<?php
// This file is part of an external plugin for Moodle - http://moodle.org/
// This plugin is licensed under GNU Public License v3.
//
// This plugin is NOT part of the Moodle core and is provided as is. It is developed independently
// and must comply with the GNU Public License v3 requirements.

namespace auth_captcha\privacy;

defined('MOODLE_INTERNAL') || die();

use context;
use context_system;
use core_privacy\local\metadata\collection;
use core_privacy\local\metadata\provider as metadata_provider;
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\contextlist;
use core_privacy\local\request\plugin\provider as plugin_provider;

/**
 * Privacy provider for auth_captcha.
 */
class provider implements metadata_provider, plugin_provider {
    /**
     * Returns metadata about personal data stored by this plugin.
     *
     * @param collection $collection The metadata collection to populate.
     * @return collection The updated collection.
     */
    public static function get_metadata(collection $collection): collection {
        $collection->add_database_table('auth_captcha_attempts', [
            'ip' => 'privacy:metadata:auth_captcha_attempts:ip',
            'count' => 'privacy:metadata:auth_captcha_attempts:count',
            'last_attempt' => 'privacy:metadata:auth_captcha_attempts:last_attempt',
        ], 'privacy:metadata:auth_captcha_attempts');

        return $collection;
    }

    /**
     * Returns the contexts that contain user data for the given user.
     *
     * @param int $userid The user ID.
     * @return contextlist The list of contexts containing user data.
     */
    public static function get_contexts_for_userid(int $userid): contextlist {
        $contextlist = new contextlist();
        return $contextlist;
    }

    /**
     * Exports user data for the approved contexts.
     *
     * @param approved_contextlist $contextlist The approved contexts list.
     */
    public static function export_user_data(approved_contextlist $contextlist): void {
        return;
    }

    /**
     * Deletes all user data within a context.
     *
     * @param context $context The context to delete data within.
     */
    public static function delete_data_for_all_users_in_context(context $context): void {
        global $DB;

        if ($context->id !== context_system::instance()->id) {
            return;
        }

        $DB->delete_records('auth_captcha_attempts');
    }

    /**
     * Deletes user data for the approved contexts.
     *
     * @param approved_contextlist $contextlist The approved contexts list.
     */
    public static function delete_data_for_user(approved_contextlist $contextlist): void {
        return;
    }
}
