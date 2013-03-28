<div class="wrap">
	<h2>CRM Options</h2>
	
	<?php
		if($_POST['Crm_saved'] == 'Y'){
			echo "<div class='updated'><p>saved</p></div>";
		}
	?>
	
	<form action="" method="post">
		<input type="hidden" name="Crm_saved" value="Y" />
		<table class="form-table">
			<tr>
				<td>Username</td>
				<td colspan="2"><input size="60" type="text" name="crm_UserName" value="<?php echo $credentials['crm_UserName']; ?>" /></td>
			</tr>
			<tr>
				<td>Password</td>
				<td colspan="2"><input size="60" type="text" name="crm_Pass" value="<?php echo $credentials['crm_Pass']; ?>" /></td>
			</tr>
			<tr>
				<td>Type ID</td>
				<td colspan="2"><input size="60" type="text" name="crm_TypeId" value="<?php echo $credentials['crm_TypeId']; ?>" /></td>
			</tr>
			
			<tr>
				<td colspan="2"> <hr /> </td>
			</tr>
			
			<tr>
				<td>Referral User ID</td>
				<td colspan="2"><input size="60" type="text" name="crm_UserId" value="<?php echo $credentials['crm_UserId']; ?>" /></td>
			</tr>
			
			<tr>
				<td>Password for new Contact</td>
				<td colspan="2"><input size="60" type="text" name="crm_genPass" value="<?php echo $credentials['crm_genPass']; ?>" /></td>
			</tr>
					
			<tr>
				<td><input type="submit" value="save" class="button-primary"  /></td>
			</tr>
		</table>
	</form>
</div>
