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
 * Edit Form
 *
 * @package   block_faq
 * @copyright 2020 - CALL Learning - Laurent David <laurent@call-learning>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Class block_faq_edit_form
 *
 * @copyright 2020 - CALL Learning - Laurent David <laurent@call-learning>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_faq_edit_form extends block_edit_form {

    /**
     * Form definition
     *
     * @param object $mform
     * @throws coding_exception
     */
    protected function specific_definition($mform) {

        // Section header title according to language file.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        $editoroptions = array('maxfiles' => EDITOR_UNLIMITED_FILES, 'noclean' => true, 'context' => $this->block->context);
        $mform->addElement('editor',
            'config_text',
            get_string('config:text', 'block_faq'),
            get_string('config:text_help', 'block_faq'),
            $editoroptions);
        $mform->addRule('config_text', null, 'required', null, 'client');
        $mform->setType('config_text', PARAM_RAW); // XSS is prevented when printing the block contents and serving files.
    }

    /**
     * Set data
     *
     * @param array|stdClass $defaults
     */
    public function set_data($defaults) {
        if (!empty($this->block->config) && is_object($this->block->config)) {
            $text = $this->block->config->text;
            $draftideditor = file_get_submitted_draft_itemid('config_text');
            if (empty($text)) {
                $currenttext = '';
            } else {
                $currenttext = $text;
            }
            $defaults->config_text['text'] =
                file_prepare_draft_area(
                    $draftideditor,
                    $this->block->context->id,
                    'block_faq',
                    'content', 0,
                    array('subdirs' => true),
                    $currenttext);
            $defaults->config_text['itemid'] = $draftideditor;
            $defaults->config_text['format'] = FORMAT_HTML;
        } else {
            $text = '';
        }

        // TODO change this (see block_mcms).
        // Have to delete text here, otherwise parent::set_data will empty content.
        // of editor.
        unset($this->block->config->text);
        parent::set_data($defaults);
        // Restore $text.
        if (!isset($this->block->config)) {
            $this->block->config = new stdClass();
        }
        $this->block->config->text = $text;
    }
}
