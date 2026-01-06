<?php
// This file is part of an external plugin for Moodle - http://moodle.org/
// This plugin is licensed under GNU Public License v3.
//
// This plugin is NOT part of the Moodle core and is provided as is. It is developed independently
// and must comply with the GNU Public License v3 requirements.
//
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

require_once($CFG->libdir.'/authlib.php');
require_once($CFG->libdir.'/filelib.php');

/**
 * Authentication plugin for CAPTCHA login protection.
 */
class auth_plugin_captcha extends auth_plugin_base {
    /**
     * Constructor.
     */
    public function __construct() {
        $this->authtype = 'captcha';
        $this->config = get_config('auth_captcha');
    }

    /**
     * Verify CAPTCHA response.
     *
     * @param string $response The CAPTCHA response from the form
     * @return bool True if verification successful, false otherwise
     */
    private function verify_captcha($response) {
        if (empty($this->config->captcha_secret_key)) {
            return false; // Skip verification if not configured
        }

        $url = 'https://hcaptcha.com/siteverify';
        $data = [
            'secret' => $this->config->captcha_secret_key,
            'response' => $response
        ];

        $curl = new curl();
        $options = [
            'CURLOPT_RETURNTRANSFER' => true,
            'CURLOPT_POST' => true,
            'CURLOPT_POSTFIELDS' => $data
        ];
        
        $result = $curl->post($url, $data, $options);
        $response = json_decode($result);

        return isset($response->success) && $response->success === true;
    }

    /**
     * Get the client IP address for rate limiting.
     *
     * @return string
     */
    private function get_client_ip(): string {
        $ip = getremoteaddr();
        if (!is_string($ip)) {
            return '';
        }

        $ip = trim($ip);
        if ($ip === '' || $ip === '0.0.0.0') {
            return '';
        }

        return $ip;
    }

    /**
     * Check if IP is locked out due to too many failed attempts.
     *
     * @param string $ip The client IP to check
     * @return bool|int False if not locked out, lockout remaining time in seconds if locked
     */
    private function check_lockout($ip) {
        global $DB;

        if ($ip === '') {
            return false;
        }

        $max_attempts = empty($this->config->max_attempts) ? 5 : $this->config->max_attempts;
        $lockout_duration = empty($this->config->lockout_duration) ? 300 : $this->config->lockout_duration;

        $attempts = $DB->get_record('auth_captcha_attempts', ['ip' => $ip]);
        
        if (!$attempts) {
            return false;
        }

        if ($attempts->count >= $max_attempts) {
            $time_passed = time() - $attempts->last_attempt;
            if ($time_passed < $lockout_duration) {
                return $lockout_duration - $time_passed;
            }
            // Reset attempts after lockout period
            $DB->delete_records('auth_captcha_attempts', ['ip' => $ip]);
        }

        return false;
    }

    /**
     * Record failed login attempt.
     *
     * @param string $ip The client IP address
     */
    private function record_failed_attempt($ip) {
        global $DB;

        if ($ip === '') {
            return;
        }

        $attempt = $DB->get_record('auth_captcha_attempts', ['ip' => $ip]);
        
        if ($attempt) {
            $attempt->count++;
            $attempt->last_attempt = time();
            $DB->update_record('auth_captcha_attempts', $attempt);
        } else {
            $attempt = new stdClass();
            $attempt->ip = $ip;
            $attempt->count = 1;
            $attempt->last_attempt = time();
            $DB->insert_record('auth_captcha_attempts', $attempt);
        }
    }

    /**
     * Returns true if the username and password work and false if they are
     * wrong or don't exist.
     *
     * @param string $username The username
     * @param string $password The password
     * @return bool Authentication success or failure.
     */
    public function user_login($username, $password) {
        global $CFG, $DB, $SESSION, $PAGE;

        $ip = $this->get_client_ip();

        // Check for lockout
        if ($lockout = $this->check_lockout($ip)) {
            throw new moodle_exception('error_too_many_attempts', 'auth_captcha', '', $lockout);
        }
        
        $user = $DB->get_record('user', array('username' => $username, 'mnethostid' => $CFG->mnet_localhost_id));

        if ($user) {
            // Check if CAPTCHA is enabled
            $captcha_enabled = !empty($this->config->captcha_site_key) && !empty($this->config->captcha_secret_key);
            
            // Get CAPTCHA response from the form submission
            $captcha_response = optional_param('captcha-response', '', PARAM_RAW);
            if ($captcha_response === '') {
                $captcha_response = optional_param('h-captcha-response', '', PARAM_RAW);
            }
            
            // Verify captcha if enabled
            $captcha_verified = true; // Default to true if captcha is not enabled
            if ($captcha_enabled) {
                $captcha_verified = $this->verify_captcha($captcha_response);
                if (!$captcha_verified) {
                    // Failed captcha verification
                    $this->record_failed_attempt($ip);
                    throw new moodle_exception('error_captcha', 'auth_captcha');
                }
            }

            // Check password
            if (validate_internal_user_password($user, $password) and $captcha_verified) {
                // Reset failed attempts on successful login
                $DB->delete_records('auth_captcha_attempts', ['ip' => $ip]);
                return true;
            }
        }
        
        // Record failed attempt
        $this->record_failed_attempt($ip);
        return false;
    }

    /**
     * Returns true if this authentication plugin can change the user's password.
     *
     * @return bool
     */
    public function can_change_password() {
        return false;
    }

    /**
     * Returns true if this authentication plugin is 'internal'.
     *
     * @return bool
     */
    public function is_internal() {
        return true;
    }

    /**
     * Returns true if this authentication plugin can be manually set.
     *
     * @return bool
     */
    public function can_be_manually_set() {
        return true;
    }

    /**
     * Hook for login page
     *
     * This method is called from the login page to add additional elements to the login form
     */
    public function loginpage_hook() {
        global $PAGE, $SESSION;

        // If user is already logged in, no need to modify the login page
        if (isloggedin() || !empty($SESSION->auth_captcha_verified)) {
            return;
        }

        $captcha_key = !empty($this->config->captcha_site_key) ? $this->config->captcha_site_key : '';
        if (empty($captcha_key)) {
            return;
        }

        $PAGE->requires->js_call_amd('auth_captcha/captcha', 'init', [
            $captcha_key,
            get_string('entercaptcha', 'auth_captcha'),
            get_string('pleaseverifycaptcha', 'auth_captcha'),
        ]);
    }

}
