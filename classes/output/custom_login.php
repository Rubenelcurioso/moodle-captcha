<?php
// This file is part of an external plugin for Moodle - http://moodle.org/
// This plugin is licensed under GNU Public License v3.
//
// This plugin is NOT part of the Moodle core and is provided as is. It is developed independently
// and must comply with the GNU Public License v3 requirements.

namespace auth_captcha\output;

defined('MOODLE_INTERNAL') || die();

use renderable;
use templatable;
use renderer_base;

/**
 * Custom login page renderable.
 */
class custom_login implements renderable, templatable {
    /** @var array The data for the template */
    protected $data;

    /**
     * Constructor.
     *
     * @param array $data The data for the template
     */
    public function __construct($data) {
        $this->data = $data;
    }

    /**
     * Export the data for the mustache template.
     *
     * @param renderer_base $output The renderer
     * @return array The data for the template
     */
    public function export_for_template(renderer_base $output) {
        return $this->data;
    }
}
