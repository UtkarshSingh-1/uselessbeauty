<?php
if ( ! class_exists( 'WC_Paymob_53221_Card_VPC_AED_Blocks' ) ) {
	require_once plugin_dir_path( __FILE__ ) . 'gateway-blocks.php';

	final class WC_Paymob_53221_Card_VPC_AED_Blocks extends Paymob_Gateway_Blocks {

		public function __construct() {
			$this->name = 'paymob-53221-card-vpc-aed';
		}
	}

}
