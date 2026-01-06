<?php
// This file is part of an external plugin for Moodle - http://moodle.org/
// This plugin is licensed under GNU Public License v3.
//
// This plugin is NOT part of the Moodle core and is provided as is. It is developed independently
// and must comply with the GNU Public License v3 requirements.

namespace auth_captcha\task;

defined('MOODLE_INTERNAL') || die();

/**
 * Scheduled task to remove expired login attempts.
 */
class cleanup_attempts extends \core\task\scheduled_task {
    /**
     * Get the human-readable task name.
     *
     * @return string
     */
    public function get_name() {
        return get_string('task_cleanup_attempts', 'auth_captcha');
    }

    /**
     * Execute the cleanup task.
     */
    public function execute() {
        global $DB;

        $retentiondays = (int) get_config('auth_captcha', 'attempt_retention_days');
        if ($retentiondays <= 0) {
            return;
        }

        $cutoff = time() - ($retentiondays * DAYSECS);
        $DB->delete_records_select('auth_captcha_attempts', 'last_attempt < ?', [$cutoff]);
    }
}
