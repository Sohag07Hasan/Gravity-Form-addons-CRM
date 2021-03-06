<?php

/*
 * Plugin Name: Gravity Forms + Custom CRM
 * Author: Mahibul Hasan Sohag
 * Description: This addons creates a way to integrate the gravity form with a custom made CRM
 * Author URI: http://sohag07hasan.elance.com
 */

//defining some global constant

//if(!class_exists('RGFormsModel')) return;

define('CRMGRAVITYDIR', dirname(__FILE__));
define('CRMGRAVITYFILE', __FILE__);
define('CROMGRAVITYURL', plugins_url('', __FILE__));

define('CRM_DEBUG', false);


include CRMGRAVITYDIR . '/classes/CRM.php';

include CRMGRAVITYDIR . '/classes/gf-crm-class-admin.php';
GravityFormCustomCRM :: init();

include CRMGRAVITYDIR . '/classes/form-submission-class.php';
Form_submission_To_CRM :: init();

include CRMGRAVITYDIR . '/classes/offline-class.php';
Offline_CRM :: init();