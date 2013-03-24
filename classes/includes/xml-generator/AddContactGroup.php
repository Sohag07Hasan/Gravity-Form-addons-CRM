<?php echo '<?xml version="1.0" encoding="utf-8"?>'; ?>

<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
	<soap:Body>	
		<AddContactGroup xmlns="https://secure.1parkplace.com/api/1.0/">
			<credentials>		       
		        <Username><?php echo Gravity_form_CRM::username; ?></Username>
		         <Password><?php echo Gravity_form_CRM::pass; ?></Password>
		        <TypeID><?php echo Gravity_form_CRM::TypeID; ?></TypeID>
			</credentials>
			<profile>
				<?php 
				foreach(GravityFormCustomCRM::$gftooltips_profile as $key => $value){
					?>
					<<?php echo $key; ?>><?php echo $entry[$form['customcrm_'.$key]];?></<?php echo $key?>>
					<?php 	
				}
				
				foreach(GravityFormCustomCRM::$gftooltips_additional_profile as $key => $value){
					?>
					<<?php echo $value; ?>><?php echo $form[$key]; ?></<?php echo $value; ?>>
					<?php 
				}
				
				?>
			</profile>
			<contactGroupID><?php echo $form['gravity_form_contactgroup']; ?></contactGroupID>	
			<policy>
			<?php 
			foreach(GravityFormCustomCRM::$gftooltips_policy as $key => $value){
				$v = ($value == 1) ? 1 : 0;
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
  
