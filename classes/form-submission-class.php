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
		add_action("gform_after_submission", array(get_class(), 'push_to_crm'), 10, 2);
		//add_filter('gform_validation', array(get_class(), 'validate'), 100);		
	}
	
	
	//push to the crm
	static function push_to_crm($entry, $form){
		return self::push($entry, $form, true);
	}
	
	
	/*
	 * Receive the submitted form data
	 */
	static function push($entry, $form, $is_new){
		
		if(CRM_DEBUG == true){
			return self::push_to_test($entry, $form, $is_new);
		}
		
		if(!$form['customcrm_enabled']) return;	
		
		$lead_id = $entry['id'];
		
		$crm = new Gravity_form_CRM();
		
		$status_array = array();
		
		//add request
		ob_start();
		include CRMGRAVITYDIR . '/classes/includes/xml-generator/AddRequest.php';
		$AddRequest = ob_get_contents();
		ob_end_clean();
		$response = $crm->addRequest($AddRequest);
		
		$status = self::parse_request($response, 'AddRequestResult');

		if(!$status){
			$status_array[] = 'AddRequestResult';			
		}
		
		
		//add to campaign
		if(!empty($form['gravity_form_campaign'])){		
			ob_start();
			include CRMGRAVITYDIR . '/classes/includes/xml-generator/AddContactCampaign.php';
			$AddCotactCampaign = ob_get_contents();
			ob_end_clean();
			$response = $crm->addContactCampaign($AddCotactCampaign);
			
			$status = self::parse_request($response, 'AddContactCampaignResult');
			
			if(!$status){
				$status_array[] = 'AddContactCampaignResult';
			}
						
			//var_dump($response['status_code']);
		}
		//add group
		
		if(!empty($form['gravity_form_contactgroup'])){
			ob_start();
			include CRMGRAVITYDIR . '/classes/includes/xml-generator/AddContactGroup.php';
			$AddCotactGroup = ob_get_contents();
			ob_end_clean();
			$response = $crm->addContactGroup($AddCotactGroup);
			
			$status = self::parse_request($response, 'AddContactGroupResult');
			
			if(!$status){
				$status_array[] = 'AddContactGroupResult';
			}
			//var_dump($response['status_code']);
		}
		
		
		if($is_new){
			if(count($status_array) > 0){
				Offline_CRM::add_a_lead($lead_id, $status_array);
			}
		}
		else{
			if(count($status_array) > 0){
				Offline_CRM::update_a_lead($lead_id, $status_array);
			}
			else{
				Offline_CRM::remove_a_lead($lead_id);
			}
		}		
	
	}
	
	
	/*
	  * request parsing
	  * */
	static function parse_request($response, $action){
	 	
		if($response['status_code'] == 200){
			$regex = "/<$action>(.*?)<\/$action>/msi";
			$request_id = self::match($regex, $response['response'], 1);			
			if($request_id > 0) return true;
		}
		
		return false;
		
	 }
	 
	 
	static function match($regex, $str, $i = 0) {
            if(preg_match($regex, $str, $match) == 1)
                return $match[$i];
            else
                return false;
        }
	
	
	
	//intake push function
	
	
	static function push_to_test($entry, $form, $is_new){
		
		if(!$form['customcrm_enabled']) return;	
		$crm = new Gravity_form_CRM();
		
		//add request
		ob_start();
		include CRMGRAVITYDIR . '/classes/includes/xml-generator/AddRequest.php';
		$AddRequest = ob_get_contents();
		ob_end_clean();
		
		//var_dump($AddRequest); exit;
		
		$response = $crm->addRequest($AddRequest);
				
		header('Content-type: text/xml');
		header('Content-Disposition: attachment; filename="addRequest.xml"');
		
		echo $response['request'];
		
		echo "\n \n";
		
		echo $response['response'];
		
		echo "\n \n";
		
		echo 'Status Code:' . $response['status_code'];
		
		echo "\n \n \n \n";
		
		//add to campaign
		if(!empty($form['gravity_form_campaign'])){		
			ob_start();
			include CRMGRAVITYDIR . '/classes/includes/xml-generator/AddContactCampaign.php';
			$AddCotactCampaign = ob_get_contents();
			ob_end_clean();
			$response = $crm->addContactCampaign($AddCotactCampaign);
			
			echo $response['request'];
			
			echo "\n \n";
		
			echo $response['response'];
			
			echo "\n \n";
			
			echo 'Status Code:' . $response['status_code'];
			
			echo "\n \n \n \n";
			
		}
		//add group
		
		if(!empty($form['gravity_form_contactgroup'])){
			ob_start();
			include CRMGRAVITYDIR . '/classes/includes/xml-generator/AddContactGroup.php';
			$AddCotactGroup = ob_get_contents();
			ob_end_clean();
			$response = $crm->addContactGroup($AddCotactGroup);
			
			echo $response['request'];
			
			echo "\n \n";
		
			echo $response['response'];
			
			echo "\n \n";
			
			echo 'Status Code:' . $response['status_code'];
			
			echo "\n \n \n \n";
		}

				
		die();
			
	}
	
	
	//validate form
	static function validate($data){
		$form = $data['form'];
		
		var_dump($_POST);
		var_dump($form);
		exit;
		
		$validation_fields = array('FirstName', 'LastName', 'EmailPrimary');
		
		/*
		foreach($form["fields"] as &$field){
			
		}
		*/
	}
	
		
}