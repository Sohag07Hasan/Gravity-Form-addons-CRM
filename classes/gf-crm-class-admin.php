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
		'Address1' => array('Address 1', 'Address 1'),
		'Address2' => array('Address 2', 'Address 2'),
		'City' => array('City', 'City'),
		'State' => array('State', 'State'),
		'ZIPCode' => array('ZIPCode', 'ZIPCode'),
		'ContactComments' => array('ContactComments', 'ContactComments'),
		'PhoneCell' => array('PhoneCell', 'PhoneCell'),
		'PhoneHome' => array('PhoneHome', 'PhoneHome'),
		'PhoneWork' => array('PhoneWork', 'PhoneWork'),
		'Fax' => array('Fax', 'Fax'),
		'AgentCompany' => array('AgentCompany', 'AgentCompany'),
		'AgentName' => array('AgentName', 'AgentName'),
		'HasAgent' => array('HasAgent', 'HasAgent'),
		'ReferrerURL' => array('ReferrerURL', 'ReferrerURL'),
		'FirstName' => array('FirstName', 'FirstName'),
		'LastName' => array('LastName', 'LastName'),
		'EmailPrimary' => array('EmailPrimary', 'EmailPrimary'),
		'EmailSecondary' => array('EmailSecondary', 'EmailSecondary'),
		'EmailPrimaryConfirmed' => array('EmailPrimaryConfirmed', 'EmailPrimaryConfirmed'),
		'UserId' => array('UserId', 'UserId'),
		'ReferringUserId' => array('ReferringUserId', 'ReferringUserId'),
		'StatusId' => array('Status Id', 'Status Id'),
		'Rating' => array('Rating', 'Rating'),
		'MeetingDate' => array('Meeting Date', 'Meeting Date')		
		
		
	);
	
	public static $gftooltips_policy = array(
		'SMSUserOnRegister' => array('SMSUserOnRegister', 'SMSUserOnRegister'),
		'SMSUserOnRequestedInfo' => array('SMSUserOnRequestedInfo', 'SMSUserOnRequestedInfo'),
		'EmailUserOnRegister' => array('EmailUserOnRegister', 'EmailUserOnRegister'),
		'EmailContactOnRegister' => array('EmailContactOnRegister', 'EmailContactOnRegister'),
		'RequireEmailConfirmed' => array('RequireEmailConfirmed', 'RequireEmailConfirmed')
	);
	
	//xml keys
	
	public static $xml_keys_dfaults = array(
		'client_fname' => 'ClientFirstName',
		'client_lname' => 'ClientLastName',
		'client_addr1' => 'ClientAddress1',
		'client_addr2' =>  'ClientAddress2',
		'client_zip' => 'ClientZip',
		'client_city' => 'ClientCity',
		'client_town' => 'ClientTown',
		'client_telephone' =>'ClientTelephone',
		'client_email' => 'ClientEmail',
		'client_state' => 'ClientState',
		'flight_location' => 'FlightLocation',
		'flight_date' => 'FlightDate',
		'flight_length' => 'FlightLenghth',
		'flight_video_enable' => 'FlightVideo',
		'person_who_fly_fname' => 'FlyingPersonFirstName',
		'person_who_fly_lname' =>'FlyingPersonLastName',
		'person_who_fly_height' => 'FlyingPersonHeight',
		'person_who_fly_weight' => 'FlyingPersonWeight',
		'money_sent' => 'MoneySent',
		'reseller_id' => 'ResellerId',
		'client_message' => 'Message',
		
		
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
		add_action('admin_menu', array(get_class(), 'admin_menu_crm'));
		
		add_shortcode('crm_reseller_id', array(get_class(), 'set_reseller_id'));
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
		if($_POST['Crm_saved'] == 'Y'){			
			$data = array(
				'crm_url' => trim($_POST['crm_url']),
				'crm_user' => trim($_POST['crm_user']),
				'crm_pass' =>  trim($_POST['crm_pass'])
			);
			
			update_option('gravity_form_crm_ssl_enabled', $_POST['crm_ssl']);
			update_option('gravity_form_crm_url', $data);
			update_option('gravity_form_crm_ssl_dir', trim($_POST['crm_ssl_dir']));
		}
		$url_info = get_option('gravity_form_crm_url');
		$ssl = get_option('gravity_form_crm_ssl_enabled');
		$ssl_dir = get_option('gravity_form_crm_ssl_dir');
		include dirname(__FILE__) . '/includes/options-page.php';
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
		
		foreach(self::$gftooltips_policy as $key=>$value){		
			$gf_tooltips['customcrm_'.$key] = '<h6>' . __($value[0]) . '</h6>' . __($value[1]);
		}
		
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
	public static function get_field_selector($form_id, $field_name, $selected_field = null) {
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
		$str .= '<script> jQuery("#'.$field_name.'").val( form.'.$field_name.'); </script>'."\n";
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
	

	/*
	 * set the reseller id for every unique form
	 */
	static function set_reseller_id(){
		return 'goodboy';
	}

}
