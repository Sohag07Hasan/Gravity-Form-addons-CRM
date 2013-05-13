<?php

/*
 * This class handles the front end form submissin
 * creteas xml
 * put xml to the remote site
 * parse returned xml and fires an action
 * the action is used to update the tracing table
 */

class Form_submission_To_CRM{
	
	static $count = 0;
	
	/*
	 * contains the hoook
	 */
	public static function init(){
		//if the form is submitted
		add_action("gform_after_submission", array(get_class(), 'push'), 10, 2);
		//add_filter('gform_validation', array(get_class(), 'validate'), 100);		
	}
	
	
	/*
	 * tracing crm data
	 */
	static function tracing_crm_data($lead_id, $status){
		$table = Offline_CRM::get_offline_table();
		global $wpdb;
		$wpdb->insert($table, array('lead_id'=>(int)$lead_id, 'crm_status'=>(int)$status), array('%d', '%d'));
	}






	/*
	 * Receive the submitted form data
	 */
	static function push($entry, $form){
		
		if(!$form['customcrm_enabled']) return;	
		$crm = new Gravity_form_CRM();
		
		//add request
		ob_start();
		include CRMGRAVITYDIR . '/classes/includes/xml-generator/AddRequest.php';
		$AddRequest = ob_get_contents();
		ob_end_clean();
		$response = $crm->addRequest($AddRequest);
		
		header('Content-type: text/xml');
		header('Content-Disposition: attachment; filename="addRequest.xml"');
		
		echo $response['request'];
		echo $response['response'];
		
		//add to campaign
		if(!empty($form['gravity_form_campaign'])){		
			ob_start();
			include CRMGRAVITYDIR . '/classes/includes/xml-generator/AddContactCampaign.php';
			$AddCotactCampaign = ob_get_contents();
			ob_end_clean();
			$response = $crm->addContactCampaign($AddCotactCampaign);
			
			echo $response['request'];
			echo $response['response'];
		}
		//add group
		
		if(!empty($form['gravity_form_contactgroup'])){
			ob_start();
			include CRMGRAVITYDIR . '/classes/includes/xml-generator/AddContactGroup.php';
			$AddCotactGroup = ob_get_contents();
			ob_end_clean();
			$response = $crm->addContactGroup($AddCotactGroup);
			
			echo $response['request'];
			echo $response['response'];
		}

				
		die();
			
	}
		
}