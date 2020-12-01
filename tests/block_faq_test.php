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
 * Base class for FAQ block tests
 *
 * @package   block_faq
 * @copyright 2020 - CALL Learning - Laurent David <laurent@call-learning>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
use block_mcms\output\layout_four;
use block_mcms\output\layout_one;
use block_mcms\output\layout_three;
use block_mcms\output\layout_two;

/**
 * Unit tests for block_faq
 *
 * @copyright 2020 - CALL Learning - Laurent David <laurent@call-learning>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_faq_test extends advanced_testcase {

    public function test_faq_convert() {
        $this->resetAfterTest();
        $converted = \block_faq\output\faq::convert_text_to_faq_categories(self::FAQ_SAMPLE_1);
        $this->assertNotNull($converted);
        $this->assertEquals('Category 1', $converted[0]->title);
        $this->assertEquals('Category 2', $converted[1]->title);
        $this->assertCount(3, $converted[0]->questions);
        $this->assertCount(2, $converted[1]->questions);
        $this->assertStringStartsWith("3.", $converted[0]->questions[2]->text);
        $this->assertStringStartsWith("A.", html_to_text($converted[0]->questions[2]->answer));
    }

    // @codingStandardsIgnoreStart
    // phpcs:disable
    /**
     * Sample FAQ entered as HTML.
     */
    const FAQ_SAMPLE_1 = <<<EOF
<h4>Category 1</h4>
<h5><span style="font-size: 1rem;">1. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua</span></h5>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua</p>
<h5>2. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua</h5>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
<h5><span style="font-size: 1rem;">3. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua</span></h5>
<p>A.Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua</p>
<p>B.Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua</p>
<h4>Category 2</h4>
<h5><span style="font-size: 1rem;">1. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua</span></h5>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua</p>
<h5>2. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua</h5>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
EOF;
    // phpcs:enable
    // @codingStandardsIgnoreEnd

}
