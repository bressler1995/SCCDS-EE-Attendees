function eccent_ee_transaction_success($event_queue) {
	global $wpdb;
	// echo var_dump($event_queue);
	$reg = $event_queue->registrations();
	$txn_ids = array();

	foreach ($reg as $key) {
		// loop through and use the get method to extract the protected data
		// echo $key->get('TXN_ID'); 
		// echo '<br>';
		array_push($txn_ids, $key->get('TXN_ID'));
	}

	echo '<strong>Transaction IDS From This Transaction:</strong><br>';
	echo var_dump($txn_ids);
	echo '<br><br>';

	$transactions = EEM_Transaction::instance()->get_all(
		array(
		  'limit' => 10,
		  array(
			'STS_ID' => 'TCM',
			'TXN_ID' => $txn_ids[0]
		  ) 
		) 
	  );
	  
	  echo '<strong>Attendee Data Using First Index of Transaction IDS:</strong><br>';
	  foreach( $transactions as $transaction ) {
		// echo 'txn_id: '.$transaction->ID().'&nbsp;';
		$reg_att = $transaction->get_first_related( 'Registration' );
		if ( $reg_att instanceof EE_Registration ) {
		  $att = $reg_att->attendee();
		  if ( $att instanceof EE_Attendee ) {
			$att_id = $reg_att->attendee()->get( 'ATT_ID' );
			echo var_dump($att);


			//   for($x = 0; $x < count($att); $x++) {
			// 	$att_temp = $att[$x];
			// 	echo $att_temp;
			//   }
			
		// 	$args = array(
		// 	  'meta_key'   => $wpdb->prefix . 'EE_Attendee_ID',
		// 	  'meta_value' => $att_id,
		// 	);
		// 	$wp_user_query  = new WP_User_Query( $args );
		// 	if ( ! empty( $wp_user_query->results ) ){
		// 	  $users = $wp_user_query->get_results();
		// 	  foreach ( $users as $user ) {
		// 		echo 'User ID: ' . $user->ID . '<br>';
		// 	  }
		// 	} else {
		// 	  echo 'No user found<br>';
		//    }
		  }
		}                               
	  }
}

add_action('AHEE__thank_you_page_overview_template__content', 'eccent_ee_transaction_success', 10, 1);
