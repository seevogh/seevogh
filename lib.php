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
 * Library of interface functions and constants for module seevogh
 *
 * All the core Moodle functions, neeeded to allow the module to work
 * integrated in Moodle should be placed here.
 * All the seevogh specific functions, needed to implement all the module
 * logic, should go to locallib.php. This will help to save some memory when
 * Moodle is performing actions across all modules.
 *
 * @package    mod
 * @subpackage seevogh
 * @copyright  2013 Evogh, Inc.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once($CFG->dirroot . '/calendar/lib.php');
defined('MOODLE_INTERNAL') || die();

/** example constant */
//define('NEWMODULE_ULTIMATE_ANSWER', 42);
////////////////////////////////////////////////////////////////////////////////
// Moodle core API                                                            //
////////////////////////////////////////////////////////////////////////////////

/**
 * Returns the information on whether the module supports a feature
 *
 * @see plugin_supports() in lib/moodlelib.php
 * @param string $feature FEATURE_xx constant for requested feature
 * @return mixed true if the feature is supported, null if unknown
 */
function seevogh_supports($feature) {
    switch ($feature) {
        case FEATURE_MOD_INTRO: return false;
        default: return null;
    }
}

/**
 * Technical function, needed for REST calls.
 *
 */
function do_post_request($url, $data, $optional_headers = null) {
    $params = array('http' => array(
            'method' => 'POST',
            'content' => $data
    ));
    if ($optional_headers !== null) {
        $params['http']['header'] = $optional_headers;
    }
    $ctx = stream_context_create($params);
    $fp = @fopen($url, 'rb', false, $ctx);
    if (!$fp) {
        throw new Exception("Problem with $url, $php_errormsg");
    }
    $response = @stream_get_contents($fp);
    if ($response === false) {
        throw new Exception("Problem reading data from $url, $php_errormsg");
    }
    return $response;
}

/**
 * Saves a new instance of the seevogh into the database
 *
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will create a new instance and return the id number
 * of the new instance.
 *
 * @param object $seevogh An object from the form in mod_form.php
 * @param mod_seevogh_mod_form $mform
 * @return int The id of the newly inserted seevogh record
 */
function seevogh_add_instance(stdClass $seevogh, mod_seevogh_mod_form $mform = null) {

    global $DB;

    //    struct Post { string id; string status; string jnlp; string err; string misc; };

    $seevogh->timecreated = time();
    $seevogh->welcome = 'welcome';

    # You may have to add extra stuff in here #
//////////// create seevogh meeting

    $apiret = seevogh_createMeeting($seevogh);

//////////// end seevogh API
    //    print $apiret;
    //    print_r($apiret);



    $seevogh->sv_meetingid = $apiret->meetingId;
    $seevogh->sv_meetingstatus = $apiret->meetingStatus;
    $seevogh->sv_meetingerror = 0;
    $seevogh->sv_meetingjnlp = '';


    $returnid = $DB->insert_record('seevogh', $seevogh);


    $event = new stdClass();
    $event->name = $seevogh->name;
    $event->courseid = $seevogh->course;
    $event->groupid = 0;
    $event->userid = 0;
    $event->modulename = 'seevogh';
    $event->instance = $returnid;


    calendar_event::create($event);

    return $returnid;
}

function seevogh_createMeeting($seevogh) {
    global $CFG;

    //  $api_url = $CFG->seevoghAPIURL;
    $api_user = $CFG->seevoghAPIUsername;
    $api_passwd = $CFG->seevoghAPIPassword;

    $api_url = 'https://seevogh.com/api/meeting/create';
    //  $client = new SOAPClient($api_url);

    $data_array = "apiLogin=$api_user&apiPassword=$api_passwd&meetingPwd=$seevogh->sv_meetingpwd&meetingName=$seevogh->sv_meetingname&meetingAccessCode=$seevogh->sv_meetingaccesscode&meetingType=$seevogh->sv_meetingtype&meetingDuration=$seevogh->sv_meetingduration&meetingNbrParticipants=$seevogh->sv_meetingnpart&optionRecording=$seevogh->sv_meetingoptrecord&optionH323sip=$seevogh->sv_meetingopth323sip&optionPhone=$seevogh->sv_meetingoptphone&meetingQuality=$seevogh->sv_meetingquality";

    //    print $data_array;

    $res = do_post_request($api_url, $data_array, null);


    $json = json_decode($res);

    //print_r($json);

    //  $res = $client->createMeeting($api_user,$api_passwd,$seevogh->sv_meetingname,$seevogh->sv_meetingpwd,$seevogh->sv_meetingaccesscode,(int)$seevogh->sv_meetingquality,(int)$seevogh->sv_meetingnpart,(int)$seevogh->sv_meetingduration,(int)$seevogh->sv_meetingoptrecord,(int)$seevogh->sv_meetingopth323sip,(int)$seevogh->sv_meetingoptphone);
    //  return $res;
    return $json;
}

function seevogh_getMobileURL($seevogh) {

  $url="https://seevogh.com/?joinMobile=$seevogh->sv_meetingid";

  $res=file_get_contents($url);

  return $res;

}


function seevogh_startMeeting($seevogh) {
    global $CFG;

    //  $api_url = $CFG->seevoghAPIURL;
    $api_user = $CFG->seevoghAPIUsername;
    $api_passwd = $CFG->seevoghAPIPassword;


    $api_url = 'https://seevogh.com/api/meeting/start';
    //  $client = new SOAPClient($api_url);

    $data_array = "apiLogin=$api_user&apiPassword=$api_passwd&meetingId=$seevogh->sv_meetingid&meetingPwd=$seevogh->sv_meetingpwd";

    $res = do_post_request($api_url, $data_array, null);
    $json = json_decode($res);

    return $json;
}



function seevogh_getMeetingStatus($seevogh) {
    global $CFG;

    //  $api_url = $CFG->seevoghAPIURL;
    $api_user = $CFG->seevoghAPIUsername;
    $api_passwd = $CFG->seevoghAPIPassword;


    $api_url = 'https://seevogh.com/api/meeting/status';
    //  $client = new SOAPClient($api_url);


    $data_array = "apiLogin=$api_user&apiPassword=$api_passwd&meetingId=$seevogh->sv_meetingid&meetingPwd=$seevogh->sv_meetingpwd";

    $res = do_post_request($api_url, $data_array, null);
    $json = json_decode($res);

    return $json;
}

/**
 * Updates an instance of the seevogh in the database
 *
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will update an existing instance with new data.
 *
 * @param object $seevogh An object from the form in mod_form.php
 * @param mod_seevogh_mod_form $mform
 * @return boolean Success/Fail
 */
function seevogh_update_instance(stdClass $seevogh, mod_seevogh_mod_form $mform = null) {
    global $DB;

    $seevogh->timemodified = time();
    $seevogh->id = $seevogh->instance;

    # You may have to add extra stuff in here #

    return $DB->update_record('seevogh', $seevogh);
}

/**
 * Removes an instance of the seevogh from the database
 *
 * Given an ID of an instance of this module,
 * this function will permanently delete the instance
 * and any data that depends on it.
 *
 * @param int $id Id of the module instance
 * @return boolean Success/Failure
 */
function seevogh_delete_instance($id) {
    global $DB;

    if (!$seevogh = $DB->get_record('seevogh', array('id' => $id))) {
        return false;
    }

    # Delete any dependent records here #

    $DB->delete_records('seevogh', array('id' => $seevogh->id));

    return true;
}

/**
 * Returns a small object with summary information about what a
 * user has done with a given particular instance of this module
 * Used for user activity reports.
 * $return->time = the time they did it
 * $return->info = a short text description
 *
 * @return stdClass|null
 */
function seevogh_user_outline($course, $user, $mod, $seevogh) {

    $return = new stdClass();
    $return->time = 0;
    $return->info = '';
    return $return;
}

/**
 * Prints a detailed representation of what a user has done with
 * a given particular instance of this module, for user activity reports.
 *
 * @param stdClass $course the current course record
 * @param stdClass $user the record of the user we are generating report for
 * @param cm_info $mod course module info
 * @param stdClass $seevogh the module instance record
 * @return void, is supposed to echp directly
 */
function seevogh_user_complete($course, $user, $mod, $seevogh) {
    
}

/**
 * Given a course and a time, this module should find recent activity
 * that has occurred in seevogh activities and print it out.
 * Return true if there was output, or false is there was none.
 *
 * @return boolean
 */
function seevogh_print_recent_activity($course, $viewfullnames, $timestart) {
    return false;  //  True if anything was printed, otherwise false
}

/**
 * Prepares the recent activity data
 *
 * This callback function is supposed to populate the passed array with
 * custom activity records. These records are then rendered into HTML via
 * {@link seevogh_print_recent_mod_activity()}.
 *
 * @param array $activities sequentially indexed array of objects with the 'cmid' property
 * @param int $index the index in the $activities to use for the next record
 * @param int $timestart append activity since this time
 * @param int $courseid the id of the course we produce the report for
 * @param int $cmid course module id
 * @param int $userid check for a particular user's activity only, defaults to 0 (all users)
 * @param int $groupid check for a particular group's activity only, defaults to 0 (all groups)
 * @return void adds items into $activities and increases $index
 */
function seevogh_get_recent_mod_activity(&$activities, &$index, $timestart, $courseid, $cmid, $userid = 0, $groupid = 0) {
    
}

/**
 * Prints single activity item prepared by {@see seevogh_get_recent_mod_activity()}

 * @return void
 */
function seevogh_print_recent_mod_activity($activity, $courseid, $detail, $modnames, $viewfullnames) {
    
}

/**
 * Function to be run periodically according to the moodle cron
 * This function searches for things that need to be done, such
 * as sending out mail, toggling flags etc ...
 *
 * @return boolean
 * @todo Finish documenting this function
 * */
function seevogh_cron() {
    return true;
}

/**
 * Returns all other caps used in the module
 *
 * @example return array('moodle/site:accessallgroups');
 * @return array
 */
function seevogh_get_extra_capabilities() {
    return array();
}

////////////////////////////////////////////////////////////////////////////////
// Gradebook API                                                              //
////////////////////////////////////////////////////////////////////////////////

/**
 * Is a given scale used by the instance of seevogh?
 *
 * This function returns if a scale is being used by one seevogh
 * if it has support for grading and scales. Commented code should be
 * modified if necessary. See forum, glossary or journal modules
 * as reference.
 *
 * @param int $seevoghid ID of an instance of this module
 * @return bool true if the scale is used by the given seevogh instance
 */
function seevogh_scale_used($seevoghid, $scaleid) {
    global $DB;

    /** @example */
    if ($scaleid and $DB->record_exists('seevogh', array('id' => $seevoghid, 'grade' => -$scaleid))) {
        return true;
    } else {
        return false;
    }
}

/**
 * Checks if scale is being used by any instance of seevogh.
 *
 * This is used to find out if scale used anywhere.
 *
 * @param $scaleid int
 * @return boolean true if the scale is used by any seevogh instance
 */
function seevogh_scale_used_anywhere($scaleid) {
    global $DB;

    /** @example */
    if ($scaleid and $DB->record_exists('seevogh', array('grade' => -$scaleid))) {
        return true;
    } else {
        return false;
    }
}

/**
 * Creates or updates grade item for the give seevogh instance
 *
 * Needed by grade_update_mod_grades() in lib/gradelib.php
 *
 * @param stdClass $seevogh instance object with extra cmidnumber and modname property
 * @return void
 */
function seevogh_grade_item_update(stdClass $seevogh) {
    global $CFG;
    require_once($CFG->libdir . '/gradelib.php');

    /** @example */
    $item = array();
    $item['itemname'] = clean_param($seevogh->name, PARAM_NOTAGS);
    $item['gradetype'] = GRADE_TYPE_VALUE;
    $item['grademax'] = $seevogh->grade;
    $item['grademin'] = 0;

    grade_update('mod/seevogh', $seevogh->course, 'mod', 'seevogh', $seevogh->id, 0, null, $item);
}

/**
 * Update seevogh grades in the gradebook
 *
 * Needed by grade_update_mod_grades() in lib/gradelib.php
 *
 * @param stdClass $seevogh instance object with extra cmidnumber and modname property
 * @param int $userid update grade of specific user only, 0 means all participants
 * @return void
 */
function seevogh_update_grades(stdClass $seevogh, $userid = 0) {
    global $CFG, $DB;
    require_once($CFG->libdir . '/gradelib.php');

    /** @example */
    $grades = array(); // populate array of grade objects indexed by userid

    grade_update('mod/seevogh', $seevogh->course, 'mod', 'seevogh', $seevogh->id, 0, $grades);
}

////////////////////////////////////////////////////////////////////////////////
// File API                                                                   //
////////////////////////////////////////////////////////////////////////////////

/**
 * Returns the lists of all browsable file areas within the given module context
 *
 * The file area 'intro' for the activity introduction field is added automatically
 * by {@link file_browser::get_file_info_context_module()}
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param stdClass $context
 * @return array of [(string)filearea] => (string)description
 */
function seevogh_get_file_areas($course, $cm, $context) {
    return array();
}

/**
 * File browsing support for seevogh file areas
 *
 * @package mod_seevogh
 * @category files
 *
 * @param file_browser $browser
 * @param array $areas
 * @param stdClass $course
 * @param stdClass $cm
 * @param stdClass $context
 * @param string $filearea
 * @param int $itemid
 * @param string $filepath
 * @param string $filename
 * @return file_info instance or null if not found
 */
function seevogh_get_file_info($browser, $areas, $course, $cm, $context, $filearea, $itemid, $filepath, $filename) {
    return null;
}

/**
 * Serves the files from the seevogh file areas
 *
 * @package mod_seevogh
 * @category files
 *
 * @param stdClass $course the course object
 * @param stdClass $cm the course module object
 * @param stdClass $context the seevogh's context
 * @param string $filearea the name of the file area
 * @param array $args extra arguments (itemid, path)
 * @param bool $forcedownload whether or not force download
 * @param array $options additional options affecting the file serving
 */
function seevogh_pluginfile($course, $cm, $context, $filearea, array $args, $forcedownload, array $options = array()) {
    global $DB, $CFG;

    if ($context->contextlevel != CONTEXT_MODULE) {
        send_file_not_found();
    }

    require_login($course, true, $cm);

    send_file_not_found();
}

////////////////////////////////////////////////////////////////////////////////
// Navigation API                                                             //
////////////////////////////////////////////////////////////////////////////////

/**
 * Extends the global navigation tree by adding seevogh nodes if there is a relevant content
 *
 * This can be called by an AJAX request so do not rely on $PAGE as it might not be set up properly.
 *
 * @param navigation_node $navref An object representing the navigation tree node of the seevogh module instance
 * @param stdClass $course
 * @param stdClass $module
 * @param cm_info $cm
 */
function seevogh_extend_navigation(navigation_node $navref, stdclass $course, stdclass $module, cm_info $cm) {
    
}

/**
 * Extends the settings navigation with the seevogh settings
 *
 * This function is called when the context for the page is a seevogh module. This is not called by AJAX
 * so it is safe to rely on the $PAGE.
 *
 * @param settings_navigation $settingsnav {@link settings_navigation}
 * @param navigation_node $seevoghnode {@link navigation_node}
 */
function seevogh_extend_settings_navigation(settings_navigation $settingsnav, navigation_node $seevoghnode = null) {
    
}
