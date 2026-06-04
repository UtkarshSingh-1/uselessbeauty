<?php
class Paymob_85277_Card_Payment_Link_VPC_AED_Gateway extends Paymob_Payment {

	public $id;
	public $method_title;
	public $method_description;
	public $has_fields;
	public function __construct() {
		$this->id                 = 'paymob-85277-card-payment-link-vpc-aed';
		$this->method_title       = $this->title = __( 'Debit/Credit Card', 'paymob-woocommerce' );
		$this->method_description = $this->description = __( 'Secure Payment via Paymob Checkout', 'paymob-woocommerce' );
		parent::__construct();
		// config
		$this->init_settings();
	}
	public function admin_options() {
		PaymobAutoGenerate::gateways_method_title( $this->method_title, $this, $this->get_option( 'single_integration_id' ) );
	}
}
