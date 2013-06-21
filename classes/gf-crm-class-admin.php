<?php

/*
 * this class handles all the things to the admin panel
 * creates an options page
 * defines all the xml keys and input names
 * has some javascripts code to add this field with the form in advanced options in form admin page
 */

if(class_exists('GravityFormCustomCRM')) return;

class GravityFormCustomCRM{
	
	//tolltips
	//tolltips
	public static $gftooltips_profile = array(
		'FirstName' => array('First Name* (required)', 'FirstName'),
		'LastName' => array('Last Name* (required)', 'LastName'),
		'EmailPrimary' => array('Email Primary* (required)', 'EmailPrimary'),
		'PhoneCell' => array('PhoneCell', 'PhoneCell'),
		'PhoneWork' => array('PhoneWork', 'PhoneWork'),
		'PhoneHome' => array('PhoneHome', 'PhoneHome'),
		'Address1' => array('Address 1', 'Address 1'),
		'City' => array('City', 'City'),
		'State' => array('State', 'State'),
		'ZIPCode' => array('ZIPCode', 'ZIPCode'),
		'Address2' => array('Address 2', 'Address 2'),
		'AgentCompany' => array('AgentCompany', 'AgentCompany'),
		'AgentName' => array('AgentName', 'AgentName'),
		'EmailSecondary' => array('Email Secondary', 'EmailSecondary'),		
		'Fax' => array('Fax', 'Fax'),
		
	//	'HasAgent' => array('HasAgent', 'HasAgent'),
	//	'ReferrerURL' => array('ReferrerURL', 'ReferrerURL'),
		
		
		
		//'MeetingDate' => array('Meeting Date', 'Meeting Date'),				
		//'StatusID' => array('Status Id', 'Status Id')
	);
	
	
	/*
	 * Comments sections are also to be handled separately
	 * */
	public static $gftooltips_comments = array(
		'ContactComments_1' => array('ContactComments 01', 'ContactComments 01'),
		'ContactComments_2' => array('ContactComments 02', 'ContactComments 02'),
		'ContactComments_3' => array('ContactComments 03', 'ContactComments 03'),
		'ContactComments_4' => array('ContactComments 04', 'ContactComments 04'),
		'ContactComments_5' => array('ContactComments 05', 'ContactComments 05'),
	);
	
	
	
	/*
	 * Comment question
	 * */
	public static $gftooltips_questions = array(
		'Questions_1' => array('Questions 01', 'Questions 01'),
		'Questions_2' => array('Questions 02', 'Questions 02'),
		'Questions_3' => array('Questions 03', 'Questions 03'),
		'Questions_4' => array('Questions 04', 'Questions 04'),
		'Questions_5' => array('Questions 05', 'Questions 05')
	);
	
	
	// these are to be handled differently
	public static $gftooltips_additional_profile = array(
		'gravity_form_user_id' => 'UserId',
		'RequireEmailConfirmed' => 'EmailPrimaryConfirmed',
		'gravity_form_contactRating' => 'Rating',
		'gravity_form_leadSource' => 'LeadSourceID'
		
	);
	
	
	public static $gftooltips_policy = array(
	//	'RequireEmailConfirmed' => array('EmailPrimaryConfirmed', 'EmailPrimaryConfirmed'),
		'SMSUserOnRegister' => array('SMSUserOnRegister', 'SMSUserOnRegister'),
		'SMSUserOnRequestedInfo' => array('SMSUserOnRequestedInfo', 'SMSUserOnRequestedInfo'),
		'EmailUserOnRegister' => array('EmailUserOnRegister', 'EmailUserOnRegister'),
		'EmailContactOnRegister' => array('EmailContactOnRegister', 'EmailContactOnRegister'),
		'RequireEmailConfirmed' => array('RequireEmailConfirmed', 'RequireEmailConfirmed')		
	);
	
	
	/*
	 * contains necessary hooks
	 */
	static function init(){
		
		//adding new fileds in advanced setting section
		add_action('gform_advanced_settings', array(get_class(), 'gform_advanced_settings'));
		
		//add extra tool tips
		add_filter('gform_tooltips', array(get_class(), 'gform_tooltips'));
		
		//add settings page
		//add_action('admin_menu', array(get_class(), 'admin_menu_crm'));
				
				
	//	add_action('init', array(get_class(), 'soap_checking'));
	
		//submentu page
		add_filter('gform_addon_navigation', array(get_class(), 'addon_crm_menu'), 50);
		
		add_filter('gform_pre_form_settings_save', array(get_class(), 'gform_pre_form_settings_save'));
		
	}
	
	//save the meta data with the form
	static function gform_pre_form_settings_save($updated_form){
		$updated_form['gravity_form_user_id'] = rgpost('gravity_form_user_id');
		return $updated_form;
	}
	
	
	static function addon_crm_menu($addon_menus){
		$addon_menus[] = array(
				 "name"       => "parkplace_crm",
				 "label"      => __("ParkPlaceCRM", "gravityformscapsulecrm"),
				 "callback"   => array(get_class(), "options_page_content"),
				 "permission" => 'manage_options'
			);
			
			return $addon_menus;
	}
	
	
	
	static function soap_checking(){
		$crm = new Gravity_form_CRM();
			
		self::contactGroup_selector();
		
		die();
	}
	
	/*
	 * settigs page
	 */
	static function admin_menu_crm(){
		add_options_page('crm setting page', 'CRM', 'manage_options', 'crm_options_page', array(get_class(), 'options_page_content'));
	}
	
	/*
	 * Options page content
	 */
	static function options_page_content(){
			
		include dirname(__FILE__) . '/includes/options-page.php';
	}
	
	
	//return the crm credentials
	static function get_crm_credentials(){
		include dirname(__FILE__) . '/Credentials.inc';
		
		//referral usr id and new user pass
		$credentials = get_option('custom_crm_credentials');	

		return array(
			'crm_UserName' => $username,
			'crm_Pass' => $password,
			'crm_TypeId' => $type_id,
			'crm_UserId' => $credentials['crm_UserId'],
			'crm_genPass' => $credentials['crm_genPass']
		);
	}
	
	
	/*
	 * ssl enabled
	 */
	static function ssl_enabled(){
		return (get_option('gravity_form_crm_ssl_enabled') == '1') ? true : false;
	}
	
	/*
	 * return the ssl certificate dir
	 */
	static function get_ssl_certificate(){
		return get_option('gravity_form_crm_ssl_dir');
	}
	
	
	
	/*
	 * return crm url
	 */
	static function get_crm_url(){
		$url_info = get_option('gravity_form_crm_url');
		return trim($url_info['crm_url']) . '?USERNAME=' . urlencode(trim($url_info['crm_user'])) . '&PASSWORD=' . urlencode(trim($url_info['crm_pass']));
	}


	/*
	 *Add extra tooltips to show for this addon 
	 */
	static function gform_tooltips($gf_tooltips){
		
		$gf_tooltips["customcrm_enabled"] = "<h6>".__("Integrate form with CustomCRM")."</h6>".__("Tick this box to integrate this form with CustomCRM. When this form is submitted successfulling the data will be added to customCRM.");
		
		foreach(self::$gftooltips_profile as $key=>$value){		
			$gf_tooltips['customcrm_'.$key] = '<h6>' . __($value[0]) . '</h6>' . __($value[1]);
		}
			
		$gf_tooltips['UserId'] = '<h6> User ID </h6>' . 'If empty, default one will be used';
		
		return $gf_tooltips;
	}
	
		
	
	/*
	 * adding new settings fields with the Form in admin panel
	 */
	static function gform_advanced_settings($position, $form_id = ''){
		if($position != 800) return;
		if(isset($form_id) != $_GET['id']) return;
		
		
		
		 echo '<li><input type="checkbox" onclick="ToggleCustomCRM();" id="gform_customcrm" /> ';
		 echo '<label for="gform_customcrm" id="gform_enable_customcrm_label">';
		 _e("Enable CustomCRM integration ");
		 
		gform_tooltip("customcrm_enabled");
		
		 echo '</label></li>';
		 echo '<li id="gform_customcrm_container" style="display:none">';
			self::gfcustomcrm_form_options($_GET['id']);
		echo '</li>';
		
		?>

		<script>
			function ToggleCustomCRM(isInit)
			{
				var speed = isInit ? "" : "slow";
				if(jQuery("#gform_customcrm").is(":checked")) 
					jQuery("#gform_customcrm_container").show(speed);		
				else
					jQuery("#gform_customcrm_container").hide(speed);
					form.customcrm_enabled = jQuery("#gform_customcrm").is(":checked");
			}
			
			function ChangeCustomCRMfield(field_name) 
			{
				//alert(jQuery("#"+field_name).val());
				eval('form.'+field_name+' = jQuery("#"+field_name).val();');
				//alert(form.customcrm_person_email);
			}
			jQuery("#gform_customcrm").attr("checked", form.customcrm_enabled ? true : false);
			ToggleCustomCRM(true);
			
		</script>
		
		<?php
	}
	
	/*
	 * Custom Form Fields
	 */
	static function gfcustomcrm_form_options($form_id){
		// load the form for the field merge tag generators
		 //$form = RGFormsModel::get_form_meta($form_id);
		 include dirname(__FILE__) . '/includes/crm-form-options.php';
	}
	
	/*
	 * Selector fields
	 */
	public static function get_field_selector($form_id, $field_name, $selected_field = null ) {
		$form_fields = self::get_form_fields($form_id);
		$str = '<select id="'.$field_name.'" size="1" onchange=\'ChangeCustomCRMfield("'.$field_name.'");\'>';
		
	
			$str .= '<option value="">Choose</option>'."\n";
		
		
		foreach($form_fields as $_field) 
		{
			$str .= '<option value="'.$_field[0].'"';
			if($selected_field && $_field[0] == $selected_field) $str .= ' selected';
			$str .= '>'.$_field[1].'</option>'."\n";
		}
		$str .= '</select>'."\n";
		$str .= '<script> jQuery("#'.$field_name.'").val(form.'.$field_name.'); </script>'."\n";
		return $str;
	}
	
	
	
	//get form text field
	static function get_text_field($form_id, $field_name, $value = null) {
		$str = '<input name="'.$field_name.'" style="width:100%" type="text" id="'.$field_name.'" value="" onblur=\'ChangeCustomCRMfield("'.$field_name.'");\'>';
		$str .= '<script> jQuery("#'.$field_name.'").val(form.'.$field_name.'); </script>'."\n";
		return $str;
	}
	
	
	//get setting selector here swithing is used
	static function get_settings_selector($form_id, $field_name, $form = null, $bool = 0){
		
		$value = $form[$field_name];
		
		switch ($field_name){
			case "gravity_form_campaign" :
				return self::get_settings_field_selector(self::campaign_selector($form), $form_id, $field_name, $value);
				break;
				
			case "gravity_form_contactgroup" :
				return self::get_settings_field_selector(self::contactGroup_selector($form), $form_id, $field_name, $value);
				break;
				
			case "gravity_form_leadSource" :
				return self::get_settings_field_selector(self::leadSource_selector($form), $form_id, $field_name, $value);
				break;
				
			case "gravity_form_contactRating" :
				return self::get_settings_field_selector(self::contactRating_selector(), $form_id, $field_name, $value);
				break;
				
			case "RequireEmailConfirmed" :
				return self::get_settings_field_selector(self::boolean_selector(), $form_id, $field_name, $value, 1);
				break;
			case "EmailContactOnRegister" :
				return self::get_settings_field_selector(self::boolean_selector(), $form_id, $field_name, $value, 1);
				break;
			case "SMSUserOnRequestedInfo" :
				return self::get_settings_field_selector(self::boolean_selector(), $form_id, $field_name, $value, 1);
				break;
			case "SMSUserOnRegister" :
				return self::get_settings_field_selector(self::boolean_selector(), $form_id, $field_name, $value, 1);
				break;
			case "EmailUserOnRegister" :
				return self::get_settings_field_selector(self::boolean_selector(), $form_id, $field_name, $value, 1);
				break;
		}
	}
	
	
	//eamil selector and it is boolean
	static function boolean_selector(){
		$c = array();
		$d = array(0, 1);
		$e = array('No', 'Yes');
		
		foreach($e as $de => $value){
			$c[] = array(
				'id' => $d[$de],
				'name' => $value
			); 
		}
		
		return $c;
	}
	
	
	//campaigns are fetched and handled
	static function campaign_selector($form){
		$crm = new Gravity_form_CRM();
		
		$response = $crm->get_campaigns($form);		
		$campaigns_xml = $response['response'];
		
		
		$c = array();
		
		if($response['status_code'] == 200){
			$xml = simplexml_load_string($campaigns_xml);
			$namespace = $xml->getNamespaces(true);
			$xml->registerXPathNamespace('c', $namespace['soap']);
			$body = $xml->xpath('//c:Body');
			
			$campaigns = $body[0]->GetUserCampaignsResponse->GetUserCampaignsResult->RtkCampaignProfile;

			if($campaigns){				
				foreach($campaigns as $campaign){
					$c[] = array(
						'id' => (int) $campaign->CampaignID,
						'name' => (string) $campaign->CampaignName
					);
					
				}
			}
		}

		return $c;
	}
	
	
	//contact group selector
	static function contactGroup_selector($form){
		$crm = new Gravity_form_CRM();
		
		$response = $crm->get_contactGroups($form);		
		$contact_group_xml = $response['response'];
		$c = array();
			
		if($response['status_code'] == 200){
			$xml = simplexml_load_string($contact_group_xml);
			$namespace = $xml->getNamespaces(true);
			$xml->registerXPathNamespace('c', $namespace['soap']);
			$body = $xml->xpath('//c:Body');
			
			//var_dump($body);
			
			$cgroups = $body[0]->GetUserContactGroupsResponse->GetUserContactGroupsResult->Group;
			
			if($cgroups){
				foreach($cgroups as $cgroup){
					$c[] = array(
						'id' => (int) $cgroup->GroupID,
						'name' => (string) $cgroup->Caption
					);
				}
			}
		}
		
		return $c;		
	}
		
	
	//lead selector
	static function leadSource_selector($form){
		$crm = new Gravity_form_CRM();
		$response = $crm->get_leadSources($form);
		$leadSource_xml = $response['response'];
		$c = array();
		
		if($response['status_code']){
			$xml = simplexml_load_string($leadSource_xml);
			$namespace = $xml->getNamespaces(true);
			$xml->registerXPathNamespace('c', $namespace['soap']);
			$body = $xml->xpath('//c:Body');
			
			$leadSoruces = $body[0]->GetUserCustomLeadSourcesResponse->GetUserCustomLeadSourcesResult->RtkCustomLeadSourceProfile;
						
			if($leadSoruces){
				foreach($leadSoruces as $leadSoruce){
					
					//var_dump($leadSoruce);
					
					$c[] = array(
						'id' => (string) $leadSoruce->LeadSourceID,
						'name' => (string) $leadSoruce->LeadSourceName
					);
				}
			}
		}
		
		return $c;
	}
	
	
	
	//contact rating selector 
	static function contactRating_selector(){
		$digit = array(1, 2, 3, 4);
		$c = array();
		foreach($digit as $d){
			$c[] = array(
				'id' => $d,
				'name' => $d . ' star'
			);
		}
		
		return $c;
	}
	
	
	//settings fields selector
	static function get_settings_field_selector($fields, $form_id, $field_name, $selected_field = null, $bool = 0){					
		
		$str = '<select id="'.$field_name.'" size="1" onchange=\'ChangeCustomCRMfield("'.$field_name.'");\'>';
		
		if($bool == 0){
			$str .= '<option value="">Choose</option>'."\n";
		}
		
		if($fields){
			foreach($fields as $_field){
				$str .= '<option value="'.$_field['id'].'"';
				if($_field['id'] == $selected_field) $str .= ' selected';
				$str .= '>'.$_field['name'].'</option>'."\n";
			}			
		}
		
		$str .= '</select>'."\n";
		$str .= '<script> jQuery("#'.$field_name.'").val(form.'.$field_name.'); </script>'."\n";
		return $str;		
	}
	
	
	/*
	 * statif cuntions to return fields
	 */
	public static function get_form_fields($form_id){
		$form = RGFormsModel::get_form_meta($form_id);
		$fields = array();
		
		if(is_array($form["fields"])){
			foreach($form["fields"] as $field){
				if(is_array(rgar($field, "inputs"))){					
					
					foreach($field["inputs"] as $input)
						$fields[] =  array($input["id"], GFCommon::get_label($field, $input["id"]));
				}
				else if(!rgar($field,"displayOnly")){
					$fields[] =  array($field["id"], GFCommon::get_label($field));
				}
			}
		}
		return $fields;
	}
		
}
