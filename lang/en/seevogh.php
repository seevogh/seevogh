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
$string['seevoghquality'] = 'SeeVogh Meeting Quality [1-lowest 5-highest]';
$string['seevoghnpart'] = 'SeeVogh Meeting Number of Participants';
$string['seevoghduration'] = 'SeeVogh Meeting Duration';
$string['seevoghoptrecord'] = 'SeeVogh Meeting Recording Option';
$string['seevoghopth323sip'] = 'SeeVogh Meeting H323/SIP Option';
$string['seevoghoptphone'] = 'SeeVogh Meeting Phone Option';

$string['seevoghname_help'] = 'This is SeeVogh Meeting Name';
$string['seevoghpwd_help'] = 'This is SeeVogh Meeting Moderator Key. It is mandatory parameter.';
$string['seevoghaccesscode_help'] = 'This is SeeVogh Meeting Access Code for the users to join the meeting. If this password set, user will be prompted for this passsword when he/she will try to join the meeting';
$string['seevoghquality_help'] = 'This is SeeVogh Meeting Quality. Lowest level is 1, Highest (1080p HD) - 5. We are recommending to set it at 3 (640x380).';
$string['seevoghnpart_help'] = 'This is SeeVogh Meeting number of participants. It should not be more than 50. Default value - 5. Please see http://seevogh.com configurator for details.';
$string['seevoghduration_help'] = 'This is SeeVogh Meeting duraiton in hours. Should be integer, default is 1 (hour).';
$string['seevoghoptrecord_help'] = 'This is SeeVogh Meeting Recording option. By default it is enabled for all joined from dashboard. Set to 0 to disable it.';
$string['seevoghopth323sip_help'] = 'This is SeeVogh Meeting H323/SIP option. By default it is enabled. Set to 0 to disable it.';
$string['seevoghoptphone_help'] = 'This is SeeVogh Meeting Phone option. By default it is enabled. Set to 0 to disable it.';

$string['seevogh'] = 'seevogh';
$string['pluginadministration'] = 'seevogh administration';
$string['pluginname'] = 'seevogh';
