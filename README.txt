

	seevogh Activity Module for Moodle 2.x
	--------------------------------------

 SeeVogh is cloud based videoconferencing solution that enables universities and 
colleges to deliver a high-quality learning experience to remote students.

 These instructions describe how to install the seevogh Activity Module for Moodle 2.x.  
This module is developed and supported by Evogh Inc, the company that manages the 
SeeVogh Hybrid Cloud project in 2011.


With this plugin you can
        - Create links in any class to seevogh meetings
        - Book and Start seevogh meetings
        - Launch seevogh client, clicking on the link in any class using Java Web Start 

 
	Prerequisites
	-------------

You need:

        - A server running Moodle 2.0+
	- USHI account on seevogh.com (please ask contact@evogh.com)
        - API account on seevogh.com linked to your USHI account 
          (please ask contact@evogh.com)

For information how to setup USHI account, USHI server (if needed) and 
obtain API account please consult contact@evogh.com

Plugin will NOT work without proper API account on seevogh.com.


       Obtaining the source
       --------------------

 Zip file can be provided on demand - ask contact@evogh.com, 
or retrieved from https://github.com/seevogh/seevogh 

 If you will download this file from github as a zip master archive 
it will have a name seevogh-master.zip and when you unzip it, subdir will be 
seevogh-master instead of seevogh. Please change subdirectory name to seevogh 
as soon as you will unzip it in point 1. below. 

 
       Installation
       ------------

These instructions assume your Moodle server is installed at /var/www/moodle.

1.  Copy seevogh.zip  to /var/www/moodle/mod
2.  Enter the following commands

        cd /var/www/moodle/mod
        sudo unzip seevogh.zip

    This will create the directory
 
        ./seevogh
        
    Note: If you copied master zip file from github as explained above, please 
    rename seevogh-master subdirectory to seevogh. You should have 
    /var/www/moodle/mod/seevogh before doing update next step, otherwise 
    installation will not work. 

     
3.  Login to your moodle site as administrator

        Moodle will detect the new module and prompt you to Upgrade.
        
4.  Click the 'Upgrade' button.  

        The activity module will install mod_seevogh.
        
5.  Click the 'Continue' button. 

        You'll be prompted to configure the activity module.
        
6.  Enter the API URL, API username and API password which you obtained from 
seevogh support team. If you don't have it - ask contact@evogh.com

7.  Click the 'Save Changes' button.

At this point, you can enter any course, turn editing on, and add a seevogh 
activity link to the class.

	 Contact Us
	 ----------

If you have feedback, enhancement requests, or would like commercial support for 
hosting, integrating, customizing, branding, or scaling BigBlueButton, contact us at
contact@evogh.com



	
