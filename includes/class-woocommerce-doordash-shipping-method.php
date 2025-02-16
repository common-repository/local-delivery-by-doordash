<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.inverseparadox.com
 * @since      1.0.0
 *
 * @package    Woocommerce_Doordash
 * @subpackage Woocommerce_Doordash/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Woocommerce_Doordash
 * @subpackage Woocommerce_Doordash/includes
 * @author     Inverse Paradox <erik@inverseparadox.net>
 */
class Woocommerce_Doordash_Shipping_Method extends WC_Shipping_Method {

	/**
	 * Shipping class constructor.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $instance_id = 0 ) {
		$this->id = 'woocommerce_doordash';
		$this->instance_id = absint( $instance_id );
		$this->method_title = __( 'DoorDash', 'local-delivery-by-doordash' );
		$this->method_description = __( 'Allow customers to have their orders delivered via DoorDash', 'local-delivery-by-doordash' );
		$this->supports = array( 
			'shipping-zones',
			'instance-settings',
			'instance-settings-modal',
		);
		$this->init();
	}

	/**
	 * Initialize the shipping method
	 *
	 * @return void
	 */
	public function init() {
		$this->init_form_fields();
		$this->init_settings();

		$this->title = $this->get_option( 'title' );
		$this->tax_status = $this->get_option( 'tax_status' );
		$this->cost = $this->get_option( 'cost' );

		add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
	}

	/**
	 * Calculate the rate
	 *
	 * @param array $package
	 * @return void
	 */
	public function calculate_shipping( $package = array() ) {

		$chosen_shipping_rate_id = WC()->session->get( 'chosen_shipping_methods' )[0];
		if ( false === strpos( $chosen_shipping_rate_id, 'woocommerce_doordash' ) ) {
			$this->add_rate( array( 
				'label' => $this->title,
				'package' => $package,
				'cost' => 0,
			) );
			WC()->session->set( 'doordash_external_delivery_id', '' );
			return;
		}

		// $doordash_pickup_location = WC()->session->get( 'doordash_pickup_location' );
		$delivery = new Woocommerce_Doordash_Delivery(); // create from session data

		// Only fire an API request if certain params have been set for the delivery
		if ( $delivery->is_valid() ) {
			$quote_result = WCDD()->api->get_delivery_quote( $delivery );
		}
		
		// Save the delivery id
		WC()->session->set( 'doordash_external_delivery_id', $delivery->get_id() );

		$this->add_rate( array(
			'label' => $this->title,
			'package' => $package,
			'cost' => $delivery->get_fee(),
			'meta_data' => array(
				'doordash_delivery' => $delivery,
				'doordash_external_delivery_id' => $delivery->get_id(),
				'doordash_pickup_time' => $delivery->get_pickup_time(),
				'doordash_dropoff_time' => $delivery->get_dropoff_time(),
				'doordash_support_reference' => $delivery->get_support_reference(),
			)
		) );

		if ( wp_remote_retrieve_response_code( $quote_result ) !== 200 ) WC()->session->set( 'doordash_external_delivery_id', '' );
	}

	public function init_form_fields() {
		$this->instance_form_fields = array(
			'title'      => array(
				'title'       => __( 'Title', 'woocommerce' ),
				'type'        => 'text',
				'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),
				'default'     => __( 'DoorDash', 'woocommerce' ),
				'desc_tip'    => true,
			),
			'tax_status' => array(
				'title'   => __( 'Tax status', 'woocommerce' ),
				'type'    => 'select',
				'class'   => 'wc-enhanced-select',
				'default' => 'taxable',
				'options' => array(
					'taxable' => __( 'Taxable', 'woocommerce' ),
					'none'    => _x( 'None', 'Tax status', 'woocommerce' ),
				),
			),
			// 'cost'       => array(
			// 	'title'       => __( 'Cost', 'woocommerce' ),
			// 	'type'        => 'text',
			// 	'placeholder' => '0',
			// 	'description' => __( 'Optional cost for DoorDash.', 'woocommerce' ),
			// 	'default'     => '',
			// 	'desc_tip'    => true,
			// ),
		);
	}

}
