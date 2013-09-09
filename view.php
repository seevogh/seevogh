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
 * Prints a particular instance of seevogh
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod
 * @subpackage seevogh
 * @copyright  2013 Evogh, Inc.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
/// (Replace seevogh with the name of your module and remove this line)

require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_once(dirname(__FILE__) . '/lib.php');

$id = optional_param('id', 0, PARAM_INT); // course_module ID, or
$n = optional_param('n', 0, PARAM_INT);  // seevogh instance ID - it should be named as the first character of the module

if ($id) {
    $cm = get_coursemodule_from_id('seevogh', $id, 0, false, MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $seevogh = $DB->get_record('seevogh', array('id' => $cm->instance), '*', MUST_EXIST);
} elseif ($n) {
    $seevogh = $DB->get_record('seevogh', array('id' => $n), '*', MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $seevogh->course), '*', MUST_EXIST);
    $cm = get_coursemodule_from_instance('seevogh', $seevogh->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);
$context = get_context_instance(CONTEXT_MODULE, $cm->id);
$PAGE->set_context($context);


add_to_log($course->id, 'seevogh', 'view', "view.php?id=$cm->id", $seevogh->name, $cm->id);

/// Print the page header

$PAGE->set_url('/mod/seevogh/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($seevogh->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);

// other things you may want to set - remove if not needed
//$PAGE->set_cacheable(false);
//$PAGE->set_focuscontrol('some-html-id');
//$PAGE->add_body_class('seevogh-'.$somevar);
///////////////////////// SeeVogh setup /////////////////////////
//API info
$seevoghsession['api_url'] = $CFG->seevoghAPIURL;
$seevoghsession['api_username'] = $CFG->seevoghAPIUsername;
$seevoghsession['api_passwd'] = $CFG->seevoghAPIPassword;


// meeting info
$seevoghsession['sv_meetingname'] = $seevogh->sv_meetingname;
$seevoghsession['sv_meetingpwd'] = $seevogh->sv_meetingpwd;
$seevoghsession['sv_meetingtype'] = $seevogh->sv_meetingtype;
$seevoghsession['sv_meetingaccesscode'] = $seevogh->sv_meetingaccesscode;
$seevoghsession['sv_meetingquality'] = $seevogh->sv_meetingquality;
$seevoghsession['sv_meetingnpart'] = $seevogh->sv_meetingnpart;
$seevoghsession['sv_meetingduration'] = $seevogh->sv_meetingduration;
$seevoghsession['sv_meetingoptrecord'] = $seevogh->sv_meetingoptrecord;
$seevoghsession['sv_meetingopth323sip'] = $seevogh->sv_meetingopth323sip;
$seevoghsession['sv_meetingoptphone'] = $seevogh->sv_meetingoptphone;
$seevoghsession['sv_meetingid'] = $seevogh->sv_meetingid;
$seevoghsession['sv_meetingjnlp'] = $seevogh->sv_meetingjnlp;
$seevoghsession['sv_meetingerror'] = $seevogh->sv_meetingerror;
$seevoghsession['sv_meetingstatus'] = $seevogh->sv_meetingstatus;
//User data
$seevoghsession['username'] = $USER->firstname . ' ' . $USER->lastname;
$seevoghsession['userID'] = $USER->id;
//Additional info related to the course
$seevoghsession['coursename'] = $course->fullname;
$seevoghsession['courseid'] = $course->id;
$seevoghsession['cm'] = $cm;

/////////////////////////////// SeeVogh setup ends here /////////////////////
/// Print the page header
$PAGE->set_url($CFG->wwwroot . '/mod/seevogh/view.php', array('id' => $cm->id));
$PAGE->set_heading($course->shortname);

// Validate if the user is in a role allowed to join
if (!has_capability('mod/seevogh:join', $context)) {
    $PAGE->set_title(format_string($seevogh->name));
    echo $OUTPUT->header();
    if (isguestuser()) {
        echo $OUTPUT->confirm('<p>' . get_string('view_noguests', 'seevogh') . '</p>' . get_string('liketologin'), get_login_url(), $CFG->wwwroot . '/course/view.php?id=' . $course->id);
    } else {
        echo $OUTPUT->confirm('<p>' . get_string('view_nojoin', 'seevogh') . '</p>' . get_string('liketologin'), get_login_url(), $CFG->wwwroot . '/course/view.php?id=' . $course->id);
    }

    echo $OUTPUT->footer();
    exit;
}

$PAGE->set_title($seevogh->name);
$PAGE->set_button(update_module_button($cm->id, $course->id, get_string('modulename', 'seevogh')));
$PAGE->set_cacheable(false);


// Output starts here
echo $OUTPUT->header();

$seevoghsession['seevogh'] = $seevogh->id;
$seevoghsession['sv_meetingid'] = $seevogh->sv_meetingid;
$seevoghsession['sv_meetingstatus'] = $seevogh->sv_meetingstatus;


//print "<center>Meeting ID: $seevogh->sv_meetingid </center>";
//print "<center>Meeting PWD: $seevogh->sv_meetingpwd </center>";
//print "<center>Meeting status: $seevogh->sv_meetingstatus </center>";


if ($seevogh->sv_meetingstatus == 'running') {

    $meetingjnlp = $seevogh->sv_meetingjnlp;

    //  print "<center>Meeting (running) jnlp: $meetingjnlp </center>";  
// echo "<script type=\"text/javascript\" language=\"javascript\"> 
//window.open(\"$meetingjnlp\"); </script>"; 

    echo "<h1><u><center><a href=\"$meetingjnlp\">Click Here to Join the meeting</h1></u></center></a>";
    print "<h1><center>The link will download a .jnlp file to your computer. Double click this file to launch the SeeVogh application.</h1></center>";
    print "<br>";
    #Generates information that is relevant to a user scheduling a meeting 
    print_extra_meeting_info($seevogh, $context);
}



if ($seevogh->sv_meetingstatus == 'ready') {


    $five_min_from_now = (60 * 5);

    if (time() >= ($seevogh->sv_meetingstarttime - $five_min_from_now)) {
        $startret = seevogh_startMeeting($seevogh);

        //  print "<center>Meeting status after start: $startret->status </center>";  
        //print "<center>Meeting jnlp: $startret->jnlp </center>";  
        //print "<center>Meeting Error: $startret->err </center>";  

        $meetingjnlp = $startret->meetingJnlp;

        $seevogh->sv_meetingstatus = $startret->meetingStatus;
        $seevogh->sv_meetingjnlp = $startret->meetingJnlp;
        $seevogh->sv_meetingerror = 0;

        $seevoghsession['sv_meetingstatus'] = $startret->meetingStatus;
        $seevoghsession['sv_meetingjnlp'] = $startret->meetingJnlp;
        $seevoghsession['sv_meetingerror'] = 0;

        $returnid = $DB->update_record('seevogh', $seevogh);

// Join the meeting
//  seevogh_view_joining( $seevogh )
// echo "<script type=\"text/javascript\" language=\"javascript\"> 
//window.open(\"$meetingjnlp\"); </script>"; 

        echo "<h1><u><center><a href=\"$meetingjnlp\">Click Here to Join the meeting</a></h1></u></center>";
        print "<h1><center>The link will download a .jnlp file to your computer. Double click this file to launch the SeeVogh application.</h1></center>";
    } else {
        print "<h1><center>The meeting has not yet begun. It is scheduled to begin at  " . date("h:i:s", $seevogh->sv_meetingstarttime) . ".</center></h1>";
        if (has_capability('mod/seevogh:moderate', $context)) {
            print "<h1><center>Click the 'edit this SeeVogh' button if you would like to change the start time of the meeting.</h1></center>";
        }
    }
    print_extra_meeting_info($seevogh, $context);
}

#Print the meeting access code for a student to join the meeting.
if (!has_capability('mod/seevogh:moderate', $context)) {
    print "<h2><center>Meeting access code: $seevogh->sv_meetingaccesscode</h2></center>";
}

// Replace the following lines with you own code
//echo $OUTPUT->heading('Yay! It works!');
//JavaScript variables
$jsVars = array(
    'sv_meetingjnlp' => $seevoghsession['sv_meetingjnlp'],
);

$jsmodule = array(
    'name' => 'mod_seevogh',
    'fullpath' => '/mod/seevogh/module.js',
    'requires' => array('datasource-get', 'datasource-jsonschema', 'datasource-polling'),
);
$PAGE->requires->data_for_js('seevogh', $jsVars);
$PAGE->requires->js_init_call('M.mod_seevogh.init_view', array(), false, $jsmodule);

// Finish the page
echo $OUTPUT->footer();

function seevogh_view_joining($seevoghsession) {

    //TODO
    // seevogh_log($seevoghsession, 'Create');
    //  print get_string('view_groups_selection', 'seevogh' )."&nbsp;&nbsp;<input type='button' onClick='M.mod_seevogh.joinURL()' value='".get_string('view_groups_selection_join', 'seevogh' )."'>";
}

//Generates extra information that would be relevant
//to someone booking a SeeVogh meeting.
function print_extra_meeting_info($seevogh, $context) {
    //Print extra information if the user is able to create a SeeVogh instance
    if (has_capability('mod/seevogh:addinstance', $context)) {

        print "<h2><center>Meeting quality [1-5]: $seevogh->sv_meetingquality</h2></center>";
        print "<h2><center>Meeting status: $seevogh->sv_meetingstatus</h2></center>";
        print "<h2><center>Meeting access code: $seevogh->sv_meetingaccesscode</h2></center>";
        print "<h2><center>Meeting moderator key: $seevogh->sv_meetingpwd</h2></center>";
        print "<h2><center>Meeting duration: $seevogh->sv_meetingduration hour(s)</h2></center>";
        print "<h2><center>Number of meeting participants: $seevogh->sv_meetingnpart</h2></center>";
        if ($seevogh->sv_meetingtype == 1)
            $output = "Plenary";
        else
            $output = "Regular";
        print "<h2><center>Meeting Type: $output</h2></center>";

        if ($seevogh->sv_meetingoptrecord == 1)
            $output = "yes";
        else
            $output = "no";
        print "<h2><center>Can this SeeVogh meeting be recorded?: $output</h2></center>";
        if ($seevogh->sv_meetingoptphone == 1)
            $output = "yes";
        else
            $output = "no";
        print "<h2><center>Is the phone option enabled for this meeting? $output</h2></center>";
        print "<h1><center>**Once a SeeVogh meeting has already been started, options about the meeting cannot be changed. 
    The SeeVogh activity will have to be deleted and remade with the updated information.**</h1></center>";
    }
}