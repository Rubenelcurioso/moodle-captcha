<?php
// This file is part of an external plugin for Moodle - http://moodle.org/
// This plugin is licensed under GNU Public License v3.
//
// This plugin is NOT part of the Moodle core and is provided as is. It is developed independently
// and must comply with the GNU Public License v3 requirements.

namespace auth_captcha\output;

defined('MOODLE_INTERNAL') || die();

use plugin_renderer_base;

/**
 * Renderer for auth_captcha.
 */
class renderer extends plugin_renderer_base {
    /**
     * Render the custom login page.
     *
     * @param custom_login $page The custom login page
     * @return string HTML
     */
    public function render_custom_login(custom_login $page) {
        $data = $page->export_for_template($this);
        return $this->render_from_template('auth_captcha/custom_login', $data);
    }
}
