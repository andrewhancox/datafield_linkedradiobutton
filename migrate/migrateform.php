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
 * @package    datafield
 * @subpackage linkedradiobutton
 * @copyright  2015 onwards Andrew Hancox (andrewdchancox@googlemail.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');
}

require_once($CFG->dirroot . '/lib/formslib.php');

class migrateform extends moodleform {
    protected function definition() {
        $mform = $this->_form;

        $radiobuttons = $this->get_radiobuttons();

        $mform->addElement('html', '<ul>');
        foreach ($radiobuttons as $radiobutton) {
            if (isset($lastradiobutton) && $lastradiobutton->categoryname != $radiobutton->categoryname) {
                $mform->addElement('html', "</ul>");
            }
            if (isset($lastradiobutton) && $lastradiobutton->coursename != $radiobutton->coursename) {
                $mform->addElement('html', "</ul>");
            }
            if (isset($lastradiobutton) && $lastradiobutton->activityname != $radiobutton->activityname) {
                $mform->addElement('html', "</ul>");
            }

            if (!isset($lastradiobutton)) {
                $mform->addElement('html', "<li>$radiobutton->categoryname</li><ul>");
            } else if ($lastradiobutton->categoryname != $radiobutton->categoryname) {
                $mform->addElement('html', "<li>$radiobutton->categoryname</li><ul>");
            }

            if (!isset($lastradiobutton)) {
                $mform->addElement('html', "<li>$radiobutton->coursename</li><ul>");
            } else if ($lastradiobutton->coursename != $radiobutton->coursename) {
                $mform->addElement('html', "<li>$radiobutton->coursename</li><ul>");
            }

            if (!isset($lastradiobutton)) {
                $mform->addElement('html', "<li>$radiobutton->activityname</li><ul>");
            } else if ($lastradiobutton->activityname != $radiobutton->activityname) {
                $mform->addElement('html', "<li>$radiobutton->activityname</li><ul>");
            }

            $mform->addElement('html', '<li>');
            $mform->addElement('checkbox', $radiobutton->fieldid, $radiobutton->fieldname);
            $mform->addElement('html', '</li>');

            $lastradiobutton = $radiobutton;
        }
        $mform->addElement('html', '</ul></ul>');

        $this->add_action_buttons(false);
    }

    private function get_radiobuttons() {
        global $DB;

        $sql = "
SELECT df.id as fieldid, cc.name as categoryname, c.fullname as coursename, d.name as activityname, df.name as fieldname
FROM {course} c
INNER JOIN {course_categories} cc on cc.id = c.category
INNER JOIN {course_modules} cm on c.id = cm.course
INNER JOIN {modules} m on m.id = cm.module
INNER JOIN {data} d on d.id = cm.instance
INNER JOIN {data_fields} df on df.dataid = cm.instance
WHERE m.name = 'data' AND df.type = 'radiobutton'
ORDER BY cc.name, c.fullname, d.name, df.name
";
        return $DB->get_records_sql($sql);
    }

    public function migrateradiobuttons() {
        global $DB;

        $data = $this->get_data();

        if (!$data) {
            return false;
        }

        $tomigrate = (array)$data;
        list($insql, $params) = $DB->get_in_or_equal(array_keys($tomigrate));
        $sql = "UPDATE {data_fields} SET type = 'linkedradiobutton' WHERE id $insql";
        $DB->execute($sql, $params);
        return true;
    }

    public function edit_definition($current, $commentoptions) {
        $this->set_data($current);
        $this->set_data($commentoptions);
    }
}

