<?php
/**
 * Defines the Settings for seevogh
 *
 *
 * @package    mod
 * @subpackage seevogh
 * @copyright  2013 Evogh, Inc.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
  $settings->add( new admin_setting_configtext( 'seevoghAPIURL', get_string( 'seevoghUrl', 'seevogh' ), get_string( 'seevoghurl', 'seevogh' ), 'https://seevogh.com/?/svws/?wsdl' ) );
  $settings->add( new admin_setting_configtext( 'seevoghAPIUsername', get_string( 'seevoghAPIUsername', 'seevogh' ), get_string( 'seevoghAPIUsername', 'seevogh' ), 'ask contact@evogh.com' ) );
  $settings->add( new admin_setting_configtext( 'seevoghAPIPassword', get_string( 'seevoghAPIPassword', 'seevogh' ), get_string( 'seevoghAPIPassword', 'seevogh' ), 'ask contact@evogh.com' ) );
 }

?>
