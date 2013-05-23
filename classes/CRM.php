<?php
//this class handles all the xml request to crm

class Gravity_form_CRM{
		
	//some properties
	public $username;
	public $pass;
	public $TypeID;
	public $refUserId;
	public $refPass;
	
	
	const user_proxy_url = 'https://secure.1parkplace.com/api/1.0/userproxy.asmx';
	const contact_proxy_url = 'https://secure.1parkplace.com/api/1.0/contactproxy.asmx';
	
	
	//push urls
	const AddRequest = 'https://secure.1parkplace.com/api/1.0/contactproxy.asmx';
	//const AddRequest = 'https://secure.1parkplace.com/api/1.0/contactproxy.asm';
	
	
	//constructor function
	function __construct(){
		$credentials = GravityFormCustomCRM::get_crm_credentials();
		
		//assign the private properties
		$this->username = $credentials['crm_UserName'];
		$this->pass = $credentials['crm_Pass'];
		$this->TypeID = $credentials['crm_TypeId'];
		$this->refUserId = $credentials['crm_UserId'];
		$this->refPass = $credentials['crm_genPass'];		
	}
	
	
	//post handler by curl
	private function curlPostHandler($url, $data, $action){
		
		$headers[] = 'Content-Type: text/xml; charset=utf-8';		
		$headers[] = 'Content-Length: ' . strlen($data);
		$headers[] = 'SOAPAction: ' . $action;
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);		
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);		
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);		
		return $ch;
		
	}
	
	
	//returns the campaigns
	public function get_campaigns($form){
		
		$user_id = empty($form['gravity_form_user_id']) ? $this->refUserId : $form['gravity_form_user_id'];
		
		//var_dump($user_id);
		
		$xml = '<?xml version="1.0" encoding="utf-8"?>
		<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
		  <soap:Body>
		    <GetUserCampaigns xmlns="https://secure.1parkplace.com/api/1.0/">
		      <userID>'.$user_id.'</userID>
		    </GetUserCampaigns>
		  </soap:Body>
		</soap:Envelope>' ;
		
		$url = self::user_proxy_url;
		$action = '"https://secure.1parkplace.com/api/1.0/GetUserCampaigns"';
		
		return $this->authRequest('POST', $url, $xml, $action);					
	}
	
	
	//get contact groups
	public function get_contactGroups($form){
		
		$user_id = empty($form['gravity_form_user_id']) ? $this->refUserId : $form['gravity_form_user_id'];
		
		//var_dump($user_id);
		
		$xml = '<?xml version="1.0" encoding="utf-8"?>
		<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
		  <soap:Body>
		    <GetUserContactGroups xmlns="https://secure.1parkplace.com/api/1.0/">
		      <userID>'.$user_id.'</userID>
		    </GetUserContactGroups>
		  </soap:Body>
		</soap:Envelope>';
		
		$url = self::user_proxy_url;
		$action = '"https://secure.1parkplace.com/api/1.0/GetUserContactGroups"';
				
		return $this->authRequest('POST', $url, $xml, $action);	
	}
	
	
	//lead soruces
	public function get_leadSources($form){
		
		$user_id = empty($form['gravity_form_user_id']) ? $this->refUserId : $form['gravity_form_user_id'];
		
		//var_dump($user_id);
		
		$xml = '<?xml version="1.0" encoding="utf-8"?>
		<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
		  <soap:Body>
		    <GetUserCustomLeadSources xmlns="https://secure.1parkplace.com/api/1.0/">
		      <userID>'.$user_id.'</userID>
		    </GetUserCustomLeadSources>
		  </soap:Body>
		</soap:Envelope>' ;
		
		$url = self::user_proxy_url;
		$action = '"https://secure.1parkplace.com/api/1.0/GetUserCustomLeadSources"';
		
		return $this->authRequest('POST', $url, $xml, $action);	
	}
	
 	//fatch all the request
	 private function authRequest($method, $url, $data, $action){
	 	
	 	//method checking
	 	switch($method){
	 		case "GET" :
	 			$ch = $this->curlGetHandle($url, $data);
	 			break;
	 		case "POST" :
	 			$ch = $this->curlPostHandler($url, $data, $action);
	 			break;
	 		case "PUT" :
	 			break;
	 	}
	 	
	 	//curl execution
	 	$response = curl_exec($ch);	 	
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$status = curl_getinfo($ch);
		curl_close($ch);	
		
	//	var_dump($data);
		
	//	var_dump($status);
		
	
	//	var_dump($response);
	
		return array(
			'status_code' => $http_code,
			'response' => $response,
			'http' => $status,
			'request' => $data
		);
			
	 	
	 }
	
	 
	 /******************************************************************************************************************
	  *	  * Form Submitted Data to push to the crm
	  * */
	 function addRequest($data){
	 	$url = self::AddRequest;
	 	$action = '"https://secure.1parkplace.com/api/1.0/AddRequest"';
	 	return $this->authRequest('POST', $url, $data, $action);
	 }
	
	 
	 //add contact campaign
	 function addContactCampaign($data){
	 	$url = self::AddRequest;
	 	$action = '"https://secure.1parkplace.com/api/1.0/AddContactCampaign"';
	 	return $this->authRequest('POST', $url, $data, $action);
	 }
	 
	 
 	//add contact gorup
	 function addContactGroup($data){
	 	$url = self::AddRequest;
	 	$action = '"https://secure.1parkplace.com/api/1.0/AddContactGroup"';
	 	return $this->authRequest('POST', $url, $data, $action);
	 }
	 
	  
	 
}