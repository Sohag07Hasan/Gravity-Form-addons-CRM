<div class="wrap">

	<h2>Licenses</h2>
	
	<form method="post" action="<?php echo $action; ?>">
		<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
		<?php
			echo $License_List->search_box('search', 'athlete');		
			$License_List->display();
		?>
	
	</form>

</div>