<?php
//this class handles all the xml request to crm

class Gravity_form_CRM{
	
	const username = "oneparkplace";
	const pass = "123lanman";
	const TypeID = "Vendor";
	
	const user_proxy_url = 'https://secure.1parkplace.com/api/1.0/userproxy.asmx';
	const contact_proxy_url = 'https://secure.1parkplace.com/api/1.0/contactproxy.asmx';
	
	const soap_envelop_first = '<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">';
	const soap_envelop_last = '</soap:Envelope>';
	const soap_body_first = '<soap:Body>';
	const soap_body_last = '</soap:Body>';
	const user_id = 40572;
	
	
	//post handler by curl
	private function curlPostHandler($url, $data, $action){
		
		$headers[] = 'Content-Type: application/soap+xml; charset=utf-8; action='.$action;		
		$headers[] = 'Content-Length: ' . strlen($data);
		
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
		
		//generating the xml
		$xml = '<?xml version="1.0" encoding="utf-8"?>';
		$xml .= self::soap_envelop_first;
		$xml .= self::soap_body_first;
		
		//xml main content
		$xml .= '<GetUserCampaigns xmlns="http://tempuri.org/">';
		$xml .= '<userID>';
		$xml .= self::user_id;
		$xml .= '</userID>';
		$xml .= '</GetUserCampaigns>';
		
		$xml .= self::soap_body_last;
		$xml .= self::soap_envelop_last;		
		
		
		$xml = '<?xml version="1.0" encoding="utf-8"?>
<soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
  <soap12:Body>
    <GetUserCampaigns xmlns="https://secure.1parkplace.com/api/1.0/">
      <userID>'.self::user_id.'</userID>
    </GetUserCampaigns>
  </soap12:Body>
</soap12:Envelope>' ;
		
		$url = self::user_proxy_url;
		$action = '"https://secure.1parkplace.com/api/1.0/GetUserCampaigns"';
		$ch = $this->curlPostHandler($url, $xml, $action);
			
		$response = curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$curl_header = curl_getinfo($ch);
		
		curl_close($ch);
		
		return array(
			'http_code' => $http_code,
			'response' => $response,
			'xml' => $xml,
			'header' => $curl_header
		);
		
		
	}
	
}