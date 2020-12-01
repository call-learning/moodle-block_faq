<?php
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
 * block_faq block definition.
 *
 * @package    block_faq
 * @copyright 2020 - CALL Learning - Laurent David <laurent@call-learning>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use block_faq\output\faq;

defined('MOODLE_INTERNAL') || die();

/**
 * Class block_faq
 *
 * @package    block_faq
 * @copyright 2020 - CALL Learning - Laurent David <laurent@call-learning>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_faq extends block_base {

    /**
     * Init function
     *
     * @throws coding_exception
     */
    public function init() {
        $this->title = get_string('pluginname', 'block_faq');
    }

    /**
     * Content for the block
     *
     * @return \stdClass|string|null
     * @throws coding_exception
     * @throws moodle_exception
     */
    public function get_content() {

        if ($this->content !== null) {
            return $this->content;
        }

        if (empty($this->instance)) {
            $this->content = '';
            return $this->content;
        }

        $this->content = '';

        if ($this->config && $this->config->text) {
            $this->content = new stdClass();
            $this->content->items = array();
            $this->content->icons = array();
            $this->content->footer = '';

            // Rewrite urls.
            $this->config->text = file_rewrite_pluginfile_urls(
                $this->config->text, 'pluginfile.php', $this->context->id, 'block_faq', 'content', 0);
            $text = format_text($this->config->text, FORMAT_HTML);

            $renderer = $this->page->get_renderer('core');
            $this->content->text = $renderer->render(
                new faq($text)
            );
        }
        return $this->content;
    }

    /**
     * All applicable formats
     *
     * @return array
     */
    public function applicable_formats() {
        return array('all' => true);
    }

    /**
     * Multiple blocks ?
     *
     * @return bool
     */
    public function instance_allow_multiple() {
        return true;
    }

    /**
     * Has configuration ?
     *
     * @return bool
     */
    public function has_config() {
        return true;
    }

    /**
     * Serialize and store config data
     * @param mixed $data
     * @param false $nolongerused
     */
    public function instance_config_save($data, $nolongerused = false) {
        $config = clone($data);
        // Move embedded files into a proper filearea and adjust HTML links to match.
        // config->text is now just a string.
        $config->text = file_save_draft_area_files(
            $data->text['itemid'],
            $this->context->id,
            'block_faq',
            'content',
            0,
            array('subdirs' => true),
            $data->text['text']);

        parent::instance_config_save($config, $nolongerused);
    }

    /**
     * Hide header
     *
     * @return bool
     */
    public function hide_header() {
        return true;
    }
}
