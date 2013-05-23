<?php echo '<?xml version="1.0" encoding="utf-8"?>'; ?>

<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
	<soap:Body>	
		<AddContactGroup xmlns="https://secure.1parkplace.com/api/1.0/">
			<credentials>		       
		        <Username><?php echo $crm->username; ?></Username>
		         <Password><?php echo $crm->pass; ?></Password>
		        <TypeID><?php echo $crm->TypeID; ?></TypeID>
			</credentials>
			<profile>
				<?php 
				
				foreach(GravityFormCustomCRM::$gftooltips_profile as $key => $value){
					
					//filtering empty filed
					if(empty($entry[$form['customcrm_'.$key]])){
						continue;
					}
					else{
						$form_data = $entry[$form['customcrm_'.$key]];
					}
					
					?>
					<<?php echo $key; ?>><?php echo $form_data;?></<?php echo $key?>>
					<?php 	
				}
				
				foreach(GravityFormCustomCRM::$gftooltips_additional_profile as $key => $value){
					if($key == 'gravity_form_user_id'){						
						$form_data = empty($form[$key]) ? $crm->refUserId : $form[$key];
					}
					elseif($key == 'RequireEmailConfirmed'){
						$form_data = ($form[$key] == "1") ? 1 : 0;
					}
					else{						
						$form_data = $form[$key];
						if(empty($form_data)) continue;
					}
					
					?>
					<<?php echo $value; ?>><?php echo $form_data; ?></<?php echo $value; ?>>
					<?php 
				}				
				?>
				
				<ReferrerURL><?php echo $entry['source_url']; ?></ReferrerURL>
				
				<ReferringUserId><?php echo $crm->refUserId; ?></ReferringUserId>
				<Password><?php echo $crm->refPass; ?></Password>
				<ContactComments>
					<?php 
						$ques_answer = array();
					
						foreach(GravityFormCustomCRM::$gftooltips_comments as $key => $value){
							//echo $entry[$form['customcrm_'.$key]] . ', ';	

							if(empty($entry[$form['customcrm_'.$key]])) continue;
							
							$field_id = $form['customcrm_'.$key];
							$field = RGFormsModel::get_field($form, $field_id);
							
							$ques_answer[] = $field['label'] . ': ' . $entry[$form['customcrm_'.$key]];
						}
						
						echo implode(', ', $ques_answer);
					?>
				</ContactComments>
				
			</profile>
			
			<contactGroupID><?php echo $form['gravity_form_contactgroup']; ?></contactGroupID>	
						
			<policy>
			<?php 
				foreach(GravityFormCustomCRM::$gftooltips_policy as $key => $value){
					$v = ($form[$key] == "1") ? 1 : 0;
					?>
					<<?php echo $key; ?>>
						<State><?php echo $v; ?></State>
					</<?php echo $key?>>
					<?php 
				}
			?>
			</policy>
		</AddContactGroup>	
	</soap:Body>
</soap:Envelope>
  
