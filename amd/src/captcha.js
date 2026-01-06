// This file is part of Moodle - http://moodle.org/
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

/**
 * AMD module for CAPTCHA integration
 *
 * @module     auth_captcha/captcha
 * @copyright  2025 Your Name <your@email.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define(['jquery'], function($) {
    return {
        init: function(siteKey, labelText, errorText) {
            if (!siteKey) {
                return;
            }

            $(function() {
                // Load the CAPTCHA provider script
                if (typeof hcaptcha === 'undefined') {
                    var script = document.createElement('script');
                    script.src = 'https://js.hcaptcha.com/1/api.js';
                    script.async = true;
                    script.defer = true;
                    document.head.appendChild(script);
                }

                var $loginForm = $('#login');
                var $loginButtonGroup = $('#login #loginbtn').closest('.form-group');
                if ($loginForm.length && $loginButtonGroup.length && $loginForm.find('.h-captcha').length === 0) {
                    var label = labelText || 'CAPTCHA';
                    var captchaHtml = '<div class="form-group">' +
                        '<label>' + label + '</label>' +
                        '<div class="h-captcha" data-sitekey="' + siteKey + '"></div>' +
                        '</div>';
                    $loginButtonGroup.before(captchaHtml);
                }

                $loginForm.off('submit.authCaptcha').on('submit.authCaptcha', function(e) {
                    if (typeof hcaptcha === 'undefined') {
                        return true;
                    }

                    var response = hcaptcha.getResponse();
                    if (!response || response.length === 0) {
                        e.preventDefault();
                        alert(errorText || 'Please complete the CAPTCHA verification before logging in');
                        return false;
                    }

                    var $input = $loginForm.find('input[name="captcha-response"]');
                    if ($input.length === 0) {
                        $input = $('<input>').attr({
                            type: 'hidden',
                            name: 'captcha-response'
                        }).appendTo($loginForm);
                    }
                    $input.val(response);
                    return true;
                });
            });
        }
    };
});
