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
 * Renderable
 *
 * @package   block_faq
 * @copyright 2020 - CALL Learning - Laurent David <laurent@call-learning>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_faq\output;
defined('MOODLE_INTERNAL') || die();

use DOMDocument;
use DOMXPath;
use renderable;
use renderer_base;
use templatable;

/**
 * Class faq
 *
 * @package   block_faq
 * @copyright 2020 - CALL Learning - Laurent David <laurent@call-learning>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class faq implements renderable, templatable {
    const XPATH_ALL_CATEGORIES = '//h4';
    /**
     * @var array orgs
     */
    public $categories = [];

    /**
     * faq constructor.
     * Parse the FAQ text so we have a category per h3 level, a question per h4 level and the rest
     * stays as html.
     *
     * @param text $text
     * @param int $blockcontextid
     * @throws \coding_exception
     * @throws \moodle_exception
     */
    public function __construct($text) {
        $domdocument = new DOMDocument();
        @$domdocument->loadHTML('<?xml encoding="UTF-8">'.$text);
        $this->categories = [];
        if ($domdocument) {
            $xpath = new DOMXPath($domdocument);
            $tagcats = $xpath->query(self::XPATH_ALL_CATEGORIES);
            foreach ($tagcats as $tagcat) {
                $categorynode = $tagcat->nextSibling;
                $questions = [];
                while ($categorynode
                    && !( $categorynode->nodeType == XML_ELEMENT_NODE && $categorynode->tagName == 'h4')) {
                    if ( $categorynode->nodeType == XML_ELEMENT_NODE  && $categorynode->tagName == 'h5') {
                        $questionnode = $categorynode->nextSibling;

                        $question = (object) [
                            'text' => $categorynode->textContent,
                            'answer' => '',
                            'id' => \html_writer::random_id('qu')
                        ];
                        while ($questionnode
                            && !($questionnode->nodeType == XML_ELEMENT_NODE
                                && ($questionnode->tagName == 'h5' || $questionnode->tagName == 'h4'))) {
                            $question->answer .= $questionnode->ownerDocument->saveXML($questionnode);
                            $questionnode = $questionnode->nextSibling;
                        }
                        $questions[] = $question;
                    }
                    $categorynode = $categorynode->nextSibling;
                }
                // Look for image.
                $imgsrcinfo = $xpath->query('.//img/@src', $tagcat);
                $imagesrc = '';
                if ($imgsrcinfo->length > 0) {
                    $imagesrc = (new \moodle_url($imgsrcinfo->item(0)->nodeValue))->out();
                }

                $xpath->query('//img/@src', $tagcat);
                $this->categories[] = (object) [
                    'id' => \html_writer::random_id('cat'),
                    'title' => $tagcat->textContent,
                    'questions' => $questions,
                    'imageurl' => $imagesrc
                ];
            }
        }
    }

    /**
     * Export the faq entity
     *
     * @param renderer_base $renderer
     * @return array|\stdClass
     */
    public function export_for_template(renderer_base $renderer) {
        $exportedvalue = [
            'categories' => array_values((array) $this->categories),
            'count' => count($this->categories)
        ];
        return $exportedvalue;
    }
}