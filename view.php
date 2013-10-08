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
$seevoghsession['sv_meetingmobile'] = $seevogh->sv_meetingmobile;
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

if ($seevogh->sv_meetingstatus != 'over') {
  // Check the meeting status
  $startret = seevogh_getMeetingStatus($seevogh);
  //print "<center>Meeting status after start: $startret->meetingStatus </center>";  
  //print "<center>Meeting jnlp: $startret->jnlp </center>";  
  //print "<center>Meeting Error: $startret->err </center>";  
  //$meetingjnlp = $startret->meetingJnlp;
  $seevogh->sv_meetingstatus = $startret->meetingStatus;
  $seevoghsession['sv_meetingstatus'] = $startret->meetingStatus;
  $returnid = $DB->update_record('seevogh', $seevogh);
 }


$PAGE->set_title($seevogh->name);
if ($seevogh->sv_meetingstatus != 'over') {
  $PAGE->set_button(update_module_button($cm->id, $course->id, get_string('modulename', 'seevogh')));
 }
$PAGE->set_cacheable(false);


// Output starts here
echo $OUTPUT->header();

$seevoghsession['seevogh'] = $seevogh->id;
$seevoghsession['sv_meetingid'] = $seevogh->sv_meetingid;
$seevoghsession['sv_meetingstatus'] = $seevogh->sv_meetingstatus;


//print "<center>Meeting ID: $seevogh->sv_meetingid </center>";
//print "<center>Meeting PWD: $seevogh->sv_meetingpwd </center>";
//print "<center>Meeting status: $seevogh->sv_meetingstatus </center>";

// Add css style

print "<style type=\"text/css\"> ";
print "a.bigblue:link {color:#2DA0FE; text-decoration:none; font-size:2.5em; font-weight:bold;}";
print "a.bigblue:visited {color:#2DA0FE; text-decoration:none; font-size:2.5em; font-weight:bold;}";
print "a.bigblue:hover {color:#2DA0FE; text-decoration:underline; font-size:2.5em; font-weight:bold;}";
print "a.bigblue:active {color:#FF7070; text-decoration:underline; font-size:2.5em; font-weight:bold;}";
print "a.blue:link {color:#2DA0FE; text-decoration:none; font-weight:bold;}";
print "a.blue:visited {color:#2DA0FE; text-decoration:none; font-weight:bold;}";
print "a.blue:hover {color:#2DA0FE; text-decoration:underline; font-weight:bold;}";
print "a.blue:active {color:#FF7070; text-decoration:underline; font-weight:bold;}";
print "</style>";



if ($seevogh->sv_meetingstatus == 'over') {
        print "<h1><center>This meeting is over. </center></h1>";
	print "<center>";
	print_extra_meeting_info($seevogh, $context);
	print "</center>";

	if (!has_capability('mod/seevogh:moderate', $context)) {
	  echo "<center>";
	  echo "<table bgcolor=\"#000000\" cellpading=\"6px\" cellspacing=\"2px\" border=\"0\">\n";
	  echo "<tr height=\"80px\"><td align=\"center\" valign=\"middle\" colspan=\"2\"><img src=\"https://seevogh.com/wp-content/themes/seevogh/images/logo.jpg\" width=\"257px\" height=\"52px\">\n";
	  echo "<tr><td><font color=\"#ffffff\">Meeting Name: </font><font color=\"#00ff00\"><strong>$seevogh->sv_meetingname</strong></font></td><td></td></tr>\n";
	  echo "<tr><td><font color=\"#ffffff\">Status: </font><font color=\"#00ff00\"><strong>$seevogh->sv_meetingstatus</strong></font></td><td><font color=\"#ffffff\">Access Code: <strong>$seevogh->sv_meetingaccesscode</strong></font></td></tr>\n";
	  echo "</table>\n";
	  echo "</center>";
	}
	
 }


if ($seevogh->sv_meetingstatus == 'running') {



    $meetingjnlp = $seevogh->sv_meetingjnlp;
    $meetingmobile = $seevogh->sv_meetingmobile;

    //  print "<center>Meeting (running) jnlp: $meetingjnlp </center>";  
// echo "<script type=\"text/javascript\" language=\"javascript\"> 
//window.open(\"$meetingjnlp\"); </script>"; 

//    echo "<h1><u><center><a href=\"$meetingjnlp\">Click Here to Join the meeting on PC/laptop</h1></u></center></a>";
//    print "<h1><center>The link will download a .jnlp file to your computer. Double click this file to launch the SeeVogh application on Mac.</h1></center>";
//    print "<br>";
//    echo "<h1><u><center><a href=\"$meetingmobile\">Click Here to Join the meeting on Tablet/Smartphone</h1></u></center></a>";

    echo "\n";
    echo "<center>\n";
    echo "<p><br/></p><p>\n";
    echo "<table border=0>\n";
    echo "<tr><td>\n";
    echo "<script type=\"text/javascript\">\n";

    echo "if (navigator.userAgent.match(/(iPad|iPhone|iPod|android)/i)) {\n";
    echo "  document.write(\"<a class='bigblue' href='$meetingmobile'><img border='0' src='https://seevogh.com/wp-content/themes/seevogh/images/right_arrow.png' width='40px' height='28px'> Join the SeeVogh meeting</a><br>\");\n";
    echo "  document.write(\"<font color='#808080'><i>The SeeVogh Mobile App must be installed on your mobile device.<br>\");\n";
    echo " if (navigator.userAgent.match(/(iPad|iPhone|iPod)/i)) {\n";
    echo "   document.write(\"<font color='#808080'><i>The SeeVogh Mobile App must be installed on your iOS device.<br>\");\n";
    echo "   document.write(\"<a target='new' class='blue' href='https://itunes.apple.com/us/app/seevogh/id627519081?mt=8'>Download</a> from the App Store.</font><br>\");\n";
    echo " } else { \n";
    echo "   document.write(\"<font color='#808080'><i>The SeeVogh Mobile App must be installed on your Android device.<br>\");\n";
    echo "   document.write(\"<a target='new'  class='blue' href='https://play.google.com/store/apps/details?id=com.seevogh'>Download</a> from the Google Play store.</font><br>\");\n";
    echo " } \n";
    echo " } else { \n";
    echo "  document.write(\"<a class='bigblue' href='$meetingjnlp'><img border='0' src='https://seevogh.com/wp-content/themes/seevogh/images/right_arrow.png'  width='40px' height='28px'> Join the SeeVogh meeting</a><br>\"); \n";
    echo "  document.write(\"<font color='#808080'>The link will download a .jnlp file to your computer.<br>\");\n";
    echo "  if (navigator.userAgent.match(/Mac/i)) { \n";
    echo "    document.write(\"Double click this file to launch the SeeVogh application on Mac.<br>\");\n";
    echo "   } \n";
    echo "} \n";

    echo "</script>\n";
    echo "</td></tr>\n";
    echo "</table>\n";


    #Generates information that is relevant to a user scheduling a meeting 
    print "<br>";
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

	$meetingmobile = seevogh_getMobileURL($seevogh);

        $seevogh->sv_meetingstatus = $startret->meetingStatus;
        $seevogh->sv_meetingjnlp = $startret->meetingJnlp;
        $seevogh->sv_meetingmobile = $meetingmobile;
        $seevogh->sv_meetingerror = 0;

        $seevoghsession['sv_meetingstatus'] = $startret->meetingStatus;
        $seevoghsession['sv_meetingjnlp'] = $startret->meetingJnlp;
        $seevoghsession['sv_meetingmobile'] = $meetingmobile;
        $seevoghsession['sv_meetingerror'] = 0;

        $returnid = $DB->update_record('seevogh', $seevogh);

// Join the meeting
//  seevogh_view_joining( $seevogh )
// echo "<script type=\"text/javascript\" language=\"javascript\"> 
//window.open(\"$meetingjnlp\"); </script>"; 

    echo "\n";
    echo "<center>\n";
    echo "<p><br/></p><p>\n";
    echo "<table border=0>\n";
    echo "<tr><td>\n";
    echo "<script type=\"text/javascript\">\n";

    echo "if (navigator.userAgent.match(/(iPad|iPhone|iPod|android)/i)) {\n";
    echo "  document.write(\"<a class='bigblue' href='$meetingmobile'><img border='0' src='https://seevogh.com/wp-content/themes/seevogh/images/right_arrow.png' width='40px' height='28px'> Join the SeeVogh meeting</a><br>\");\n";
    echo "  document.write(\"<font color='#808080'><i>The SeeVogh Mobile App must be installed on your mobile device.<br>\");\n";
    echo " if (navigator.userAgent.match(/(iPad|iPhone|iPod)/i)) {\n";
    echo "   document.write(\"<font color='#808080'><i>The SeeVogh Mobile App must be installed on your iOS device.<br>\");\n";
    echo "   document.write(\"<a target='new' class='blue' href='https://itunes.apple.com/us/app/seevogh/id627519081?mt=8'>Download</a> from the App Store.</font><br>\");\n";
    echo " } else { \n";
    echo "   document.write(\"<font color='#808080'><i>The SeeVogh Mobile App must be installed on your Android device.<br>\");\n";
    echo "   document.write(\"<a target='new'  class='blue' href='https://play.google.com/store/apps/details?id=com.seevogh'>Download</a> from the Google Play store.</font><br>\");\n";
    echo " } \n";
    echo " } else { \n";
    echo "  document.write(\"<a class='bigblue' href='$meetingjnlp'><img border='0' src='https://seevogh.com/wp-content/themes/seevogh/images/right_arrow.png'  width='40px' height='28px'> Join the SeeVogh meeting</a><br>\"); \n";
    echo "  document.write(\"<font color='#808080'>The link will download a .jnlp file to your computer.<br>\");\n";
    echo "  if (navigator.userAgent.match(/Mac/i)) { \n";
    echo "    document.write(\"Double click this file to launch the SeeVogh application on Mac.<br>\");\n";
    echo "   } \n";
    echo "} \n";

    echo "</script>\n";
    echo "</td></tr>\n";
    echo "</table>\n";

    } else {
        print "<h1><center>The meeting has not yet begun. It is scheduled to begin at  " . date("h:i:s", $seevogh->sv_meetingstarttime) . ".</center></h1>";
        if (has_capability('mod/seevogh:moderate', $context)) {
            print "<h1><center>Click the 'edit this SeeVogh' button if you would like to change the start time of the meeting.</h1></center>";
        }
    }
    print_extra_meeting_info($seevogh, $context);
}

#Print the meeting access code for a student to join the meeting.
if ($seevogh->sv_meetingstatus == 'ready' || $seevogh->sv_meetingstatus == 'running') {
  if (!has_capability('mod/seevogh:moderate', $context)) {

    echo "<table bgcolor=\"#000000\" cellpading=\"6px\" cellspacing=\"2px\" border=\"0\">\n";
    echo "<tr height=\"80px\"><td align=\"center\" valign=\"middle\" colspan=\"2\"><img src=\"https://seevogh.com/wp-content/themes/seevogh/images/logo.jpg\" width=\"257px\" height=\"52px\">\n";
    echo "<tr><td><font color=\"#ffffff\">Meeting Name: </font><font color=\"#00ff00\"><strong>$seevogh->sv_meetingname</strong></font></td><td></td></tr>\n";
    echo "<tr><td><font color=\"#ffffff\">Status: </font><font color=\"#00ff00\"><strong>$seevogh->sv_meetingstatus</strong></font></td><td><font color=\"#ffffff\">Access Code: <strong>$seevogh->sv_meetingaccesscode</strong></font></td></tr>\n";
    echo "</table>\n";
  }
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


    // Make a table 

      if ($seevogh->sv_meetingtype == 1)
	$output = "Broadcast";
      elseif ($seevogh->sv_meetingtype == 2)
	$output = "Plenary";
      elseif ($seevogh->sv_meetingtype == 3)
	$output = "Classroom";
      else    
	$output = "Open Table";

      if ($seevogh->sv_meetingoptrecord == 1)
	$rec_output = "yes";
      else
	$rec_output = "no";

      if ($seevogh->sv_meetingoptphone == 1)
	$ph_output = "yes";
      else
	$ph_output = "no";

      if ($seevogh->sv_meetingopth323sip != 0)
	$h323_output = "yes";
      else
	$$h323_output = "no";
  
 
      
    echo "<table bgcolor=\"#000000\" cellpading=\"6px\" cellspacing=\"2px\" border=\"0\">\n";
    echo "<tr height=\"80px\"><td align=\"center\" valign=\"middle\" colspan=\"2\"><img src=\"https://seevogh.com/wp-content/themes/seevogh/images/logo.jpg\" width=\"257px\" height=\"52px\">\n";
    echo "<tr><td><font color=\"#ffffff\">Meeting Name: </font><font color=\"#00ff00\"><strong>$seevogh->sv_meetingname</strong></font></td><td></td></tr>\n";
    echo "<tr><td><font color=\"#ffffff\">Status: </font><font color=\"#00ff00\"><strong>$seevogh->sv_meetingstatus</strong></font></td><td><font color=\"#ffffff\">Access Code: <strong>$seevogh->sv_meetingaccesscode</strong></font></td></tr>\n";
    echo "<tr><td></td><td><font color=\"#ffffff\">Moderator Key: <strong>$seevogh->sv_meetingpwd</strong></font></td></tr>\n";
    echo "<tr><td><font color=\"#ffffff\">Meeting Type: </font><font color=\"#2DA0FE\"><strong>$output</strong></font></td><td><font color=\"#ffffff\">Duration: <strong>$seevogh->sv_meetingduration</strong> hour(s)</font></td></tr>\n";
    echo "<tr><td><font color=\"#ffffff\">Quality</font><font color=\"#a0a0a0\"> [1-5]</font><font color=\"#ffffff\">: <strong>$seevogh->sv_meetingquality</strong></font></td><td><font color=\"#ffffff\">Number of participants: <strong>$seevogh->sv_meetingnpart</strong></font></td></tr>\n";
    echo "<tr><td><font color=\"#ffffff\">Recording: </font><font color=\"#00ff00\"><strong>$rec_output</strong></font></td>\n";
    //    echo "<td><font color=\"#ffffff\">Phone Option:</font><font color=\"#00ff00\"><strong>$ph_output</strong></font></td></tr>\n";
    echo "<td><font color=\"#ffffff\">H323/SIP: </font><font color=\"#00ff00\"><strong>$h323_output</strong></font></td></tr>\n";
    echo "</table>\n";


    print "<font color=\"#808080\">Once a SeeVogh meeting has already been started, options about the meeting cannot be changed. <br>
    The SeeVogh activity will have to be deleted and remade with the updated information.</font>\n";
    }
}