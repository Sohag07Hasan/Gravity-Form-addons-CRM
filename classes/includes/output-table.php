<table class="widefat">
	<?php
		foreach(GravityFormCustomCRM::$gftooltips_profile as $key=>$value){
			?>
			<tr>
				<td><?php echo $value[0]; ?></td>
				<td><?php echo $entry[$form['customcrm_'.$key]]; ?></td>
			</tr>
			<?php
		}
	?>
</table>