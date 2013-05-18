<?php
/*
 * increases options to the form
 */

$form = RGFormsModel::get_form_meta($form_id);

//var_dump($form);

?>


<h4>
	Form Settings
</h4>

<table cellspacing="5" cellpadding="5">

	<tr>
		<td align="right"> Assign to This User ID  </td>
		<td> <?php echo self::get_text_field($form_id, 'gravity_form_user_id'); ?> <?php gform_tooltip('UserId'); ?> </td>
	</tr>	
	<tr>
		<td align="right"> Assign Contact to Campaign </td>
		<td> 
			<?php echo self::get_settings_selector($form_id, 'gravity_form_campaign', $form['gravity_form_campaign']); ?>
		</td>
	</tr>
	<tr>
		<td align="right"> Assign Contact to Group </td>		 
		<td> 
			<?php echo self::get_settings_selector($form_id, 'gravity_form_contactgroup', $form['gravity_form_contactgroup']); ?>
		</td>
	</tr>
	<tr>
		<td align="right"> Assign Lead Source As </td>
		<td> 
			<?php echo self::get_settings_selector($form_id, 'gravity_form_leadSource', $form['gravity_form_leadSource']); ?>
		</td>
	</tr>
	<tr>
		<td align="right"> Assign Rating to Contact </td>
		<td> 
			<?php echo self::get_settings_selector($form_id, 'gravity_form_contactRating', $form['gravity_form_contactRating']); ?>
		</td>
	</tr>	
	<tr>
		<td align="right"> Require Email Confirmation </td>
		
		<td> 
			<?php echo self::get_settings_selector($form_id, 'RequireEmailConfirmed', $form['RequireEmailConfirmed']); ?>
		</td>
	</tr>
	<tr>
		<td align="right"> Email Contact on Register </td>
		<td> 
			<?php echo self::get_settings_selector($form_id, 'EmailContactOnRegister', $form['EmailContactOnRegister']); ?>
		</td>
	</tr>
	
	<tr>
		<td align="right"> SMS User On Requested Info </td>
		<td> 
			<?php echo self::get_settings_selector($form_id, 'SMSUserOnRequestedInfo', $form['SMSUserOnRequestedInfo']); ?>
		</td>
	</tr>
	
	<tr>
		<td align="right"> Notify me via Text </td>
		<td> 
			<?php echo self::get_settings_selector($form_id, 'SMSUserOnRegister', $form['SMSUserOnRegister']); ?>
		</td>
	</tr>
	<tr>
		<td align="right"> Notify me via Email </td>
		<td> 
			<?php echo self::get_settings_selector($form_id, 'EmailUserOnRegister', $form['EmailUserOnRegister']); ?>
		</td>
	</tr>
	
	
</table>

<hr />



<h4>Map Form Questions to New Contact</h4>
<table cellspacing="5" cellpadding="5">
	<?php
		foreach(self::$gftooltips_profile as $key=>$value) :
			$key = 'customcrm_' . $key;
	?>
	
			<tr>
				<td align="right"><?php echo $value[0] ?></td>
				<td><?php echo self::get_field_selector($form_id, $key); ?> <?php gform_tooltip($key) ?></td>
			</tr>
	
	<?php endforeach; ?>
	
	<?php
		foreach(self::$gftooltips_comments as $key=>$value) :
			$key = 'customcrm_' . $key;
	?>
	
			<tr>
				<td align="right"><?php echo $value[0] ?></td>
				<td><?php echo self::get_field_selector($form_id, $key); ?> <?php gform_tooltip($key) ?></td>
			</tr>
	
	<?php endforeach; ?>
		
</table>