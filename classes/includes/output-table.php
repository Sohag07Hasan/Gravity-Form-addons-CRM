<table class="widefat">
	<h2> Profile  </h2>
	<?php
		foreach(GravityFormCustomCRM::$gftooltips_profile as $key => $value){
			?>
			<tr>
				<td><?php echo $value[0]; ?></td>
				<td><?php echo $entry[$form['customcrm_'.$key]]; ?></td>
			</tr>
			<?php
		}
		
		//extra fields
		foreach(GravityFormCustomCRM::$gftooltips_additional_profile as $key => $value){
			?>
			<tr>
				<td><?php echo $value; ?></td>
				<td><?php echo $form[$key]; ?></td>
			</tr>
			<?php
		}
				
	?>
	
	<P> Policy </P>
	
	<?php 
		foreach(GravityFormCustomCRM::$gftooltips_policy as $key => $value){
			?>
			<tr>
				<td><?php echo $value[0]; ?></td>
				<td><?php echo $form[$key]; ?></td>
			</tr>
			<?php 
		}
	?>	
	
</table>