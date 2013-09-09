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
 * The main seevogh configuration form
 *
 * It uses the standard core Moodle formslib. For more info about them, please
 * visit: http://docs.moodle.org/en/Development:lib/formslib.php
 *
 * @package    mod
 * @subpackage seevogh
 * @copyright  2013 Evogh, Inc.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/course/moodleform_mod.php');

/**
 * Module instance settings form
 */
class mod_seevogh_mod_form extends moodleform_mod {

    /**
     * Defines forms elements
     */
    public function definition() {

        $mform = $this->_form;

//-------------------------------------------------------------------------------
// Adding the "general" fieldset, where all the common settings are showed
        $mform->addElement('header', 'general', get_string('general', 'form'));

// Adding the "sv_meetingname" field
        $mform->addElement('text', 'sv_meetingname', get_string('seevoghname', 'seevogh'), array('size' => '64'));
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('sv_meetingname', PARAM_TEXT);
        } else {
            $mform->setType('sv_meetingname', PARAM_CLEAN);
        }
        $mform->addRule('sv_meetingname', null, 'required', null, 'client');
        $mform->addRule('sv_meetingname', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('sv_meetingname', 'seevoghname', 'seevogh');

// Adding the standard "intro" and "introformat" fields
//        $this->add_intro_editor();
//-------------------------------------------------------------------------------
// Adding the rest of seevogh settings, spreading all them into this fieldset
// or adding more fieldsets ('header' elements) if needed for better logic
//        $mform->addElement('static', 'label1', 'seevoghsetting1', 'Your seevogh fields go here. Replace me!');
//
// Adding the "sv_meetingpwd" field - this is moderator key. Must exist
//
        $mform->addElement('text', 'sv_meetingpwd', get_string('seevoghpwd', 'seevogh'), array('size' => '64'));
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('sv_meetingpwd', PARAM_TEXT);
        } else {
            $mform->setType('sv_meetingpwd', PARAM_CLEAN);
        }
        $mform->addRule('sv_meetingpwd', null, 'required', null, 'client');
        $mform->addRule('sv_meetingpwd', get_string('maximumchars', '', 100), 'maxlength', 100, 'client');
        $mform->addHelpButton('sv_meetingpwd', 'seevoghpwd', 'seevogh');


//
// Adding the "sv_meetingaccesscode" field - user password for the meeting.
//
        $mform->addElement('text', 'sv_meetingaccesscode', get_string('seevoghaccesscode', 'seevogh'));
        $mform->setType('sv_meetingaccesscode', PARAM_TEXT);
//$mform->addRule('sv_meetingaccesscode', null, 'required', null, 'client');
        $mform->addRule('sv_meetingaccesscode', get_string('maximumchars', '', 100), 'maxlength', 100, 'client');
        $mform->addHelpButton('sv_meetingaccesscode', 'seevoghaccesscode', 'seevogh');

//
// SeeVogh meeting Parameters
//
//

        $mform->addElement('header', 'seevoghmeetingset', get_string('seevoghmeetingset', 'seevogh'));
//        $mform->addElement('static', 'label2', 'seevoghsetting2', 'Your seevogh fields go here. Replace me!');
//
// Adding meeting quality (default = 640x380)
//

        $optqualityArray = array();
        $optqualityArray[] = & $mform->createElement('radio', 'sv_meetingquality', '', get_string('one', 'seevogh'), 1);
        $optqualityArray[] = & $mform->createElement('radio', 'sv_meetingquality', '', get_string('two', 'seevogh'), 2);
        $optqualityArray[] = & $mform->createElement('radio', 'sv_meetingquality', '', get_string('three', 'seevogh'), 3);
        $optqualityArray[] = & $mform->createElement('radio', 'sv_meetingquality', '', get_string('four', 'seevogh'), 4);
        $optqualityArray[] = & $mform->createElement('radio', 'sv_meetingquality', '', get_string('five', 'seevogh'), 5);
        $mform->setDefault('sv_meetingquality', 3);
        $mform->addGroup($optqualityArray, 'sv_meetingquality', get_string('seevoghquality', 'seevogh'), array(' '), false);
        $mform->addHelpButton('sv_meetingquality', 'seevoghquality', 'seevogh');

//
// Adding meeting number of participants (default = 5)
//
        $mform->addElement('text', 'sv_meetingnpart', get_string('seevoghnpart', 'seevogh'));
        $mform->setType('sv_meetingnpart', PARAM_INT);
        $mform->addRule('sv_meetingnpart', null, 'required', null, 'client');
        $mform->addRule('sv_meetingnpart', get_string('maximumchars', '', 10), 'maxlength', 10, 'client');
        $mform->addHelpButton('sv_meetingnpart', 'seevoghnpart', 'seevogh');
        $mform->setDefault('sv_meetingnpart', 5);

//
// Adding meeting type (default = 0, 0-regular type, 1-plenary)
//
        $mtypeArray = array();
        $mtypeArray[] = & $mform->createElement('radio', 'sv_meetingtype', '', get_string('plenary', 'seevogh'), 1);
        $mtypeArray[] = & $mform->createElement('radio', 'sv_meetingtype', '', get_string('regular', 'seevogh'), 0);
        $mform->setDefault('sv_meetingtype', 0);
        $mform->addGroup($mtypeArray, 'sv_meetingtype', get_string('seevoghmtype', 'seevogh'), array(' '), false);
        $mform->addHelpButton('sv_meetingtype', 'seevoghmtype', 'seevogh');

//      
//Desired meeting time
//        
        $mform->addElement('date_time_selector', 'sv_meetingstarttime', get_string('seevoghstarttime', 'seevogh'));

#$mform->addRule('sv_meetingstarttime', "The meeting must begin at the current time or later.", 'compare', '>= time()');
        $mform->addHelpButton('sv_meetingstarttime', 'seevoghstarttime', 'seevogh');
        $mform->setDefault('sv_meetingstarttime', time());

//
// Adding meeting duration (default = 1 hour)
//
        $mform->addElement('text', 'sv_meetingduration', get_string('seevoghduration', 'seevogh'));
        $mform->setType('sv_meetingduration', PARAM_INT);
        $mform->addRule('sv_meetingduration', null, 'required', null, 'client');
        $mform->addRule('sv_meetingduration', get_string('maximumchars', '', 10), 'maxlength', 10, 'client');
        $mform->addHelpButton('sv_meetingduration', 'seevoghduration', 'seevogh');
        $mform->setDefault('sv_meetingduration', 1);

//
// Adding meeting recording option (default = yes)
//

        $optrecordArray = array();
        $optrecordArray[] = & $mform->createElement('radio', 'sv_meetingoptrecord', '', get_string('yes'), 1);
        $optrecordArray[] = & $mform->createElement('radio', 'sv_meetingoptrecord', '', get_string('no'), 0);
        $mform->setDefault('sv_meetingoptrecord', 1);
        $mform->addGroup($optrecordArray, 'sv_meetingoptrecord', get_string('seevoghoptrecord', 'seevogh'), array(' '), false);
        $mform->addHelpButton('sv_meetingoptrecord', 'seevoghoptrecord', 'seevogh');

//
// Adding meeting H323/SIP option (default = yes)
//

        $opth323Array = array();
        $opth323Array[] = & $mform->createElement('radio', 'sv_meetingopth323sip', '', get_string('yes'), 1);
        $opth323Array[] = & $mform->createElement('radio', 'sv_meetingopth323sip', '', get_string('no'), 0);
        $mform->setDefault('sv_meetingopth323sip', 1);
        $mform->addGroup($opth323Array, 'sv_meetingopth323sip', get_string('seevoghopth323sip', 'seevogh'), array(' '), false);
        $mform->addHelpButton('sv_meetingopth323sip', 'seevoghopth323sip', 'seevogh');

//
// Adding meeting phone option (default = yes, unused as of now, 4/25/13)
//

        $optphoneArray = array();
        $optphoneArray[] = & $mform->createElement('radio', 'sv_meetingoptphone', '', get_string('yes'), 1);
        $optphoneArray[] = & $mform->createElement('radio', 'sv_meetingoptphone', '', get_string('no'), 0);
        $mform->setDefault('sv_meetingoptphone', 1);
        $mform->addGroup($optphoneArray, 'sv_meetingoptphone', get_string('seevoghoptphone', 'seevogh'), array(' '), false);
        $mform->addHelpButton('sv_meetingoptphone', 'seevoghoptphone', 'seevogh');
        $mform->addElement('html', '<h3><br><br>**Once a SeeVogh meeting has already been started, options about the meeting cannot be changed.
        The SeeVogh activity will have to be deleted and remade with the updated information.**</h3>');


//-------------------------------------------------------------------------------
// add standard elements, common to all modules
        $this->standard_coursemodule_elements();
//-------------------------------------------------------------------------------
// add standard buttons, common to all modules
        $this->add_action_buttons();
    }

    function validation($data, $files) {

        $errors = parent::validation($data, $files);

        if ($data['sv_meetingstarttime'] < time()) {
            $errors['sv_meetingstarttime'] = get_string('seevoghstarttime_error', 'seevogh');
        }
        return $errors;
    }

}
