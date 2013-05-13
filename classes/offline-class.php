<?php

/*
 * This class does all the offline implementation
 * gets form meta data and lead entries of the failed submission to CRM
 * uses some functions of form submission class to create xml, put xml to the remote server.
 * remove an action that inserts lead id and it's status
 * add an action to updates the lead id and it's status
 * has some cron function
 */

Class Offline_CRM{
	
	const hook = "psuh_gf_to_crm";
	const interval = 'twicedaily';
	
	
	/*
	 * contain the hooks
	 */
	static function init(){
						
		//creating table to store information if the crm is down
		register_activation_hook(CRMGRAVITYFILE, array(get_class(), 'create_offline_table'));
		register_deactivation_hook(CRMGRAVITYFILE, array(get_class(), 'deactivate_scheduler'));

		add_action(self::hook, array(get_class(), 'process_scheduler'));
		
		add_action('init', array(get_class(), 'test'));
	}
	
	
	static function test(){
		return self::process_offline_leads();
	}
	
	
	/*
	 * main function to process the schulde
	 */
	static function process_scheduler(){
		return self::process_offline_leads();
	}
	
	

	/*
	 * tracing table
	 */
	static function create_offline_table(){
		
		self::activate_the_scheduler();
		
		$table = self::get_offline_table();
		$sql = "CREATE TABLE IF NOT EXISTS $table(
			`id` bigint unsigned NOT NULL AUTO_INCREMENT,			
			`lead_id` bigint unsigned NOT NULL,
			`log` text not null,		
			`try_count` int DEFAULT 0,
			PRIMARY KEY(id),
			UNIQUE(lead_id)	 
		)";
		
		if(!function_exists('dbDelta')) :
			include ABSPATH . 'wp-admin/includes/upgrade.php';
		endif;
		dbDelta($sql);
	}
	
	
	/*
	 * activate the scheduler
	 * */
	static function activate_the_scheduler(){
		if(!wp_next_scheduled(self::hook)) {
			wp_schedule_event( current_time( 'timestamp' ), self::interval, self::hook);
		}
	}
	
	
	static function deactivate_scheduler(){
		wp_clear_scheduled_hook(self::hook);
	}
	
	
	/*
	 * return the tracing table
	 */
	static function get_offline_table(){
		global $wpdb;
		return $wpdb->prefix . 'rg_crm_offline'; 
	}
	
	
	/*
	 * Finds a failed leads
	 */
	static function get_failed_leads(){
		$table = self::get_offline_table();
		global $wpdb;
		return $wpdb->get_col("SELECT `lead_id` FROM $table WHERE try_count < 10 ");
	}
	
	
	
	/*
	 * processing the offline data
	 * removes action xml_pushed_to_crm 
	 * add new action to update the database
	 */
	static function process_offline_leads(){
		$offline_leads = self::get_failed_leads();
		
		if(empty($offline_leads)) return;
			
		
		foreach($offline_leads as $lead){
			$entry = RGFormsModel::get_lead($lead);
			$form = RGFormsModel::get_form_meta($entry['form_id']);
						
			Form_submission_To_CRM :: push($entry, $form, false);
		}
	}
	
	
	//delete a lead	
	static function remove_a_lead($lead_id){
		global $wpdb;
		$table = self::get_offline_table();
		return $wpdb->query("delete from $table where lead_id = '$lead_id'");
	} 
	
	/*
	 * updates tracing tabel data while a offline xml data is pushed to the CRM
	 */
	static function update_a_lead($lead_id, $log){
		$table = self::get_offline_table();
		global $wpdb;
		
		$lead = self::get_lead($lead_id);
		$try_count = $lead->try_count + 1; 
		
		$wpdb->update($table, array('log'=>serialize($log), 'try_count'=>$try_count), array('lead_id'=>$lead_id));
		
	}
	
	
	//return the lead rows
	static function get_lead($lead_id){
		$table = self::get_offline_table();
		global $wpdb;
		
		return $wpdb->get_row("select * from $table where lead_id = '$lead_id'");
	}
	
	
	/*
	 * add a failed leads
	 * */
	
	static function add_a_lead($lead_id, $log){
		global $wpdb;
		$table = self::get_offline_table();
		
		return $wpdb->insert($table, array('lead_id'=>$lead_id, 'log'=>serialize($log)), array('%d', '%s'));
	}
	
	
}