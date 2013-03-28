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
	
	/**
	 * tracomg crm data
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
		
		
		
		//add to campaign
		ob_start();
		include CRMGRAVITYDIR . '/classes/includes/xml-generator/AddContactCampaign.php';
		$AddCotactCampaign = ob_get_contents();
		ob_end_clean();
		$response = $crm->addContactCampaign($AddCotactCampaign);
		
		
		//add group
		ob_start();
		include CRMGRAVITYDIR . '/classes/includes/xml-generator/AddContactGroup.php';
		$AddCotactGroup = ob_get_contents();
		ob_end_clean();
		$response = $crm->addContactGroup($AddCotactGroup);
				
		die();
			
	}
	
	
	
	/*
	 * validates the input field
	 */
	static function validate($validation_result){		
		// 2 - Get the form object from the validation result
		$form = $validation_result["form"];
		
		//if the crm is attached
		if($form['customcrm_enabled']){
			// 3 - Get the current page being validated
			$current_page = rgpost('gform_source_page_number_' . $form['id']) ? rgpost('gform_source_page_number_' . $form['id']) : 1;
			
			//loop thouth the form fields
			foreach($form['fields'] as &$field){
				// 6 - Get the field's page number
				$field_page = $field['pageNumber'];

				// 7 - Check if the field is hidden by GF conditional logic
				$is_hidden = RGFormsModel::is_field_hidden($form, $field, array());

				// 8 - If the field is not on the current page OR if the field is hidden, skip it
				if($field_page != $current_page || $is_hidden) continue;
				
				switch(RGFormsModel::get_input_type($field)){
					case "address" :
						if($field["isRequired"])
							{
							$street = $_POST["input_" . $field["id"] . "_1"];
							$city = $_POST["input_" . $field["id"] . "_3"];
							$state = $_POST["input_" . $field["id"] . "_4"];
							$zip = $_POST["input_" . $field["id"] . "_5"];
							$country = $_POST["input_" . $field["id"] . "_6"];
							if(empty($street) || empty($city) || empty($zip) || (empty($state) && !$field["hideState"] ) || (empty($country) && !$field["hideCountry"])){
								$field["failed_validation"] = true;
								$field["validation_message"] = empty($field["errorMessage"]) ? __("This field is required. Please enter a complete address.", "gravityforms") : $field["errorMessage"];
							}
							if(!is_numeric($zip)){
								$field["failed_validation"] = true;
								$field["validation_message"] = __("Zip code must be numeric");
							}
							
						}

					break;
				}
				
			}
			
		}
		
		$validation_result['form'] = $form;
		return $validation_result;
		
	}
}