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
 * English strings for seevogh
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod
 * @subpackage seevogh
 * @copyright  2013 Evogh, Inc.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();



$string['modulename'] = 'seevogh';
$string['modulenameplural'] = 'seevogh';
$string['modulename_help'] = 'Use the seevogh module for booking the SeeVogh meeting | The seevogh module allows book SeeVogh Hybrid Cloud meeting and participate in it';
$string['seevoghfieldset'] = 'Custom example fieldset';
$string['seevoghurl'] = "https://seevogh.com/?/svws/?wsdl";
$string['seevoghUrl'] = "https://seevogh.com/?/svws/?wsdl";
$string['seevoghAPIURL'] = "https://seevogh.com/?/svws/?wsdl";
$string['seevoghAPIUsername'] = "moodletestuser";
$string['seevoghAPIPassword'] = "moodlepwdtest";
$string['seevoghmeetingset'] = 'SeeVogh Meeting parameters';
$string['seevoghname'] = 'SeeVogh Meeting Name';
$string['seevoghpwd'] = 'SeeVogh Meeting Moderator Key';
$string['seevoghaccesscode'] = 'SeeVogh Meeting Access Code';
$string['seevoghquality'] = 'SeeVogh Meeting Quality (Lowest) 1 - 5 (Highest)';
$string['seevoghnpart'] = 'SeeVogh Meeting Number of Participants';
$string['seevoghduration'] = 'SeeVogh Meeting Duration [Hours]';
$string['seevoghoptrecord'] = 'SeeVogh Meeting Recording Option';
$string['seevoghopth323sip'] = 'SeeVogh Meeting H323/SIP Option';
$string['seevoghoptphone'] = 'SeeVogh Meeting Phone Option';
$string['seevoghmtype'] = 'SeeVogh Meeting Type';
$string['seevoghstarttime'] = 'SeeVogh Meeting Start Time';

$string['seevoghname_help'] = 'This is the SeeVogh Meeting Name.';
$string['seevoghpwd_help'] = 'This is the SeeVogh Meeting Moderator Key. It is a mandatory parameter to create a meeting.';
$string['seevoghaccesscode_help'] = 'This is SeeVogh Meeting Access Code for the users to join the meeting. If a code put into this field, users will be prompted for this code when they try to join the meeting';
$string['seevoghquality_help'] = 'This is the desired quality of the SeeVogh meeting.The lowest level is 1 and the highest level (1080p HD) is 5. We are recommending to set it at 3 (640x380).';
$string['seevoghnpart_help'] = 'This is the desired number of participants for the SeeVogh meeting. It should not be more than 50. Default value - 5. Please see http://seevogh.com configurator for details.';
$string['seevoghduration_help'] = 'This is the SeeVogh Meeting duraiton (in hours). This should be a whole, integer value. The default is 1 (hour).';
$string['seevoghoptrecord_help'] = 'This enables or disables the option to record a SeeVogh meeting. By default it is enabled for all joined from the dashboard. Set this value to No to disable it.';
$string['seevoghopth323sip_help'] = 'This is the SeeVogh Meeting H323/SIP option. By default it is enabled. Set this value to No to disable it.';
$string['seevoghoptphone_help'] = 'This is SeeVogh Meeting Phone option. By default it is enabled. Set this value to No to disable it.';
$string['seevoghmtype_help'] = 'This is The type of the SeeVogh Meeting. Select either a plenary or regular meeting. ';

$string['seevoghstarttime_help'] = 'This is the desired start time of the SeeVogh meeting. The meeting will be eligible to start five minutes before this time.';

$string['seevogh'] = 'seevogh';
$string['pluginadministration'] = 'seevogh administration';
$string['pluginname'] = 'seevogh';

#Strings for radio button text
$string['one'] = '1';
$string['two'] = '2';
$string['three'] = '3';
$string['four'] = '4';
$string['five'] = '5';
$string['plenary'] = "Plenary";
$string['regular'] = "Regular";

#Error strings
$string['seevoghstarttime_error'] = "Please select a meeting time that is either the present time or in the future. ";
