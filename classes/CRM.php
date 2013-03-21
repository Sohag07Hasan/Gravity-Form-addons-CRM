<?php
//this class handles all the xml request to crm

class Gravity_form_CRM{
	
	const username = "oneparkplace";
	const pass = "123lanman";
	const TypeID = "Vendor";
	const user_id = 40572;
	
	const user_proxy_url = 'https://secure.1parkplace.com/api/1.0/userproxy.asmx';
	const contact_proxy_url = 'https://secure.1parkplace.com/api/1.0/contactproxy.asmx';
		
	
	
	
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
	public function get_campaigns($user_id){
				
		$xml = '<?xml version="1.0" encoding="utf-8"?>
		<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
		  <soap:Body>
		    <GetUserCampaigns xmlns="https://secure.1parkplace.com/api/1.0/">
		      <userID>'.self::user_id.'</userID>
		    </GetUserCampaigns>
		  </soap:Body>
		</soap:Envelope>' ;
		
		$url = self::user_proxy_url;
		$action = '"https://secure.1parkplace.com/api/1.0/GetUserCampaigns"';
		
		return $this->authRequest('POST', $url, $xml, $action);					
	}
	
	
	//get contact groups
	public function get_contactGroups($user_id){
		$xml = '<?xml version="1.0" encoding="utf-8"?>
		<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
		  <soap:Body>
		    <GetUserContactGroups xmlns="https://secure.1parkplace.com/api/1.0/">
		      <userID>'.self::user_id.'</userID>
		    </GetUserContactGroups>
		  </soap:Body>
		</soap:Envelope>';
		
		$url = self::user_proxy_url;
		$action = '"https://secure.1parkplace.com/api/1.0/GetUserContactGroups"';
				
		return $this->authRequest('POST', $url, $xml, $action);	
	}
	
	
	//lead soruces
	public function get_leadSources($user_id){
		$xml = '<?xml version="1.0" encoding="utf-8"?>
		<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
		  <soap:Body>
		    <GetUserCustomLeadSources xmlns="https://secure.1parkplace.com/api/1.0/">
		      <userID>'.self::user_id.'</userID>
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
		curl_close($ch);	
		
		/*
		var_dump($response);
		var_dump($http_code);
		*/
		if($http_code == 200){
			return $response;
		}
		else{
			return false;
		}
	 	
	 }
	
	
}