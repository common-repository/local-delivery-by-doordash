<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.inverseparadox.com
 * @since      1.0.0
 *
 * @package    Woocommerce_Doordash
 * @subpackage Woocommerce_Doordash/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woocommerce_Doordash
 * @subpackage Woocommerce_Doordash/admin
 * @author     Inverse Paradox <erik@inverseparadox.net>
 */
class Woocommerce_Doordash_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woocommerce_Doordash_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woocommerce_Doordash_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woocommerce-doordash-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woocommerce_Doordash_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woocommerce_Doordash_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woocommerce-doordash-admin.js', array( 'jquery', 'wp-i18n' ), $this->version, false );

		wp_register_script( 'woocommerce-doordash-admin-locations', plugin_dir_url( __FILE__ ) . 'js/woocommerce-doordash-admin-locations.js', array( 'jquery', 'wp-util', 'underscore', 'backbone', 'jquery-ui-sortable', 'wc-backbone-modal' ), $this->version, false );
	}

	/**
	 * Add the settings class to the WooCommerce settings array
	 *
	 * @param array $settings Currently defined settings
	 * @return array Filtered settings array containing new section
	 */
	public function add_settings( $settings ) {
		$settings[] = include_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-woocommerce-doordash-settings.php';
		return $settings;
	}

	/**
	 * Register a custom post type for the Pickup Locations
	 *
	 * @return void
	 */
	public function register_pickup_location_cpt() {

		$labels = array(
			'name'                  => _x( 'Pickup Locations', 'Post Type General Name', 'local-delivery-by-doordash' ),
			'singular_name'         => _x( 'Pickup Location', 'Post Type Singular Name', 'local-delivery-by-doordash' ),
		);
		$args = array(
			'label'                 => __( 'Pickup Location', 'local-delivery-by-doordash' ),
			'description'           => __( 'DoorDash Pickup Location', 'local-delivery-by-doordash' ),
			'labels'                => $labels,
			'supports'              => array( 'title' ),
			'hierarchical'          => false,
			'public'                => false,
			'show_ui'               => false,
			'show_in_menu'          => false,
			'show_in_admin_bar'     => false,
			'show_in_nav_menus'     => false,
			'can_export'            => true,
			'has_archive'           => false,
			'exclude_from_search'   => true,
			'publicly_queryable'    => true,
			'rewrite'               => false,
			'capability_type'       => 'page',
			'show_in_rest'          => true,
		);
		register_post_type( 'dd_pickup_location', $args );

	}

	/**
	 * Add the WooCommerce DoorDash Shipping Method to the registered methods array
	 *
	 * @param array $methods Array of registered methods
	 * @return array Filtered array
	 */
	public function register_shipping_method( $methods ) {
		$methods['woocommerce_doordash'] = 'Woocommerce_Doordash_Shipping_Method';
		return $methods;
	}

	/**
	 * Display a notice to administrators when DoorDash API is set to Sandbox mode
	 *
	 * @return void
	 */
	public function admin_sandbox_notice() {
		if ( 'sandbox' == WCDD()->api->get_env() ) {
			printf( '<div class="notice notice-warning is-dismissible"><p>%s</p></div>', __( 'Local Delivery by DoorDash is in <strong>Sandbox mode</strong>. Switch to Production mode to enable deliveries.', 'local-delivery-by-doordash' ) );
		}
	}

	/**
	 * Change the displayed meta key of the pickup location to something human readable
	 *
	 * @param string $displayed_key Meta key to display to the user
	 * @param WC_Meta $meta Meta object
	 * @param WC_Order_Item $item Current item
	 * @return string Filtered meta key for display
	 */
	public function filter_order_item_displayed_meta_key( $displayed_key, $meta, $item ) {
		if ( 'shipping' === $item->get_type() ) {
			switch ( $meta->key ) {
				case '_doordash_pickup_location': 
					$displayed_key = __( 'Pickup Location', 'local-delivery-by-doordash' );
					break;
				case 'doordash_external_delivery_id': 
					$displayed_key = __( 'Delivery ID', 'local-delivery-by-doordash' );
					break;
				case 'doordash_pickup_time': 
					$displayed_key = __( 'Estimated Pickup', 'local-delivery-by-doordash' );
					break;
				case 'doordash_dropoff_time': 
					$displayed_key = __( 'Estimated Dropoff', 'local-delivery-by-doordash' );
					break;
				case 'doordash_support_reference':
					$displayed_key = __( 'Support Reference', 'local-delivery-by-doordash' );
					break;
			}
		}
		return $displayed_key;
	}

	/**
	 * Change the displayed meta value for the pickup location to include the location name and address
	 *
	 * @param string $displayed_value Meta value to display to the user
	 * @param WC_Meta $meta Meta object
	 * @param WC_Order_Item $item Current item
	 * @return string Filtered meta value
	 */
	public function filter_order_item_displayed_meta_value( $displayed_value, $meta, $item ) {
		if ( 'shipping' === $item->get_type() ) {
			$gmt_offset = get_option( 'gmt_offset' ) * HOUR_IN_SECONDS;
			switch ( $meta->key ) {
				case '_doordash_pickup_location': 
					$location = new Woocommerce_Doordash_Pickup_Location( intval( $meta->value ) );
					$displayed_value = $location->get_name() . '<br>' . $location->get_formatted_address();
					break;
				case 'doordash_pickup_time': 
					$time = strtotime( $meta->value );
					$displayed_value = date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $time + $gmt_offset );
					break;
				case 'doordash_dropoff_time': 
					$time = strtotime( $meta->value );
					$displayed_value = date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $time + $gmt_offset );
					break;
			}
		}
		return $displayed_value;
	}

	/**
	 * Adjust the format of the default hours options before they are saved
	 *
	 * @param string $value Value entered by user
	 * @param string $option Name of option being saved
	 * @param string $old_value Old value of option
	 * @return string Filtered value in a normalized format
	 */
	public function update_default_hours( $value, $option, $old_value ) {
		// if ( str_starts_with( $option, 'woocommerce_doordash_' ) && str_ends_with( $option, '_hours' ) ) {
		// if ( strncmp( $option, 'woocommerce_doordash_', strlen( $option ) ) === 0 && substr( $option, -6 ) === '_hours' ) {
		if ( substr( $option, 0, 21 ) == 'woocommerce_doordash_' && substr( $option, -6 ) == '_hours' ) {
			$hours = new Woocommerce_Doordash_Hours();
			$value = $hours->normalize_hour_ranges( $value );
		}
		return $value;
	}

	/**
	 * Accept the delivery quote when the order is paid
	 *
	 * @param int $order_id Order ID being processed
	 * @return void
	 */
	public function accept_delivery_quote( $order_id ) {
		// Get the WC_Order object
		$order = wc_get_order( $order_id );

		// Get the shipping method for the order
		$methods = $order->get_shipping_methods(); 
		$method = array_shift( $methods );
	
		// Get the delivery ID and object from the shipping method
		$delivery_id = $method->get_meta("doordash_external_delivery_id");
		$delivery = $method->get_meta( "doordash_delivery" );

		// If the delivery ID isn't set, bail out here
		if ( empty( $delivery_id ) ) return;

		// Call the API to accept the delivery quote that was stored with the order
		WCDD()->api->accept_delivery_quote( $delivery );

		// Update the delivery object stored in the shipping method's meta
		$method->update_meta_data( 'doordash_delivery', $delivery );

		// Build the order note
		$note = __( 'DoorDash Quote Accepted.', 'local-delivery-by-doordash' );

		// Get the GMT offset for formatting our times
		$gmt_offset = get_option( 'gmt_offset' ) * HOUR_IN_SECONDS;

		// Add pickup time to order note
		if ( $delivery->get_pickup_time() ) {
			$time = strtotime( $delivery->get_pickup_time() );
			$displayed_value = date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $time + $gmt_offset );
			$note .= sprintf( ' Estimated pickup at %s.', $displayed_value );
			$order->add_meta_data( 'doordash_pickup_time', $delivery->get_pickup_time() );
		}

		// Add dropoff time to order note
		if ( $delivery->get_dropoff_time() ) {
			$time = strtotime( $delivery->get_dropoff_time() );
			$displayed_value = date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $time + $gmt_offset );
			$note .= sprintf( ' Estimated dropoff at %s.', $displayed_value );
			$order->add_meta_data( 'doordash_dropoff_time', $delivery->get_dropoff_time() );
		}

		// Add support reference
		if ( $delivery->get_support_reference() ) {
			$note .= sprintf( ' Support Reference #%s.', $delivery->get_support_reference() );
			$order->add_meta_data( 'doordash_support_reference', $delivery->get_support_reference() );
		}

		if ( $delivery->get_tracking_url() ) {
			// If there is a tracking number set, add it to the order note
			$note .= sprintf( ' <a href="%s" target="_blank">%s</a>', $delivery->get_tracking_url(), __( 'Track Delivery', 'local-delivery-by-doordash' ) );
			$order->add_meta_data( 'doordash_tracking_url', $delivery->get_tracking_url() );
			
			// Compat for WooCommerce Shipment Tracking plugin
			if ( function_exists( 'wc_st_add_tracking_number' ) ) {
				$tracking_code = basename( parse_url( $delivery->get_tracking_url(), PHP_URL_PATH ) );
				wc_st_add_tracking_number( $order->get_id(), $tracking_code, 'DoorDash', strtotime( $delivery->get_pickup_time() ) ); // phpcs:ignore
			}

		}
		// Add the note to the order
		$order->add_order_note( $note );

		// Clear delivery details from session. Leave the selected location.
		WC()->session->set( 'doordash_external_delivery_id', '' );
		WC()->session->set( 'doordash_dropoff_instructions', '' );
		WC()->session->set( 'doordash_delivery_type',        '' );
		WC()->session->set( 'doordash_delivery_date',        '' );
		WC()->session->set( 'doordash_delivery_time',        '' );
		WC()->session->set( 'doordash_tip_select',           '' );
		WC()->session->set( 'doordash_tip_amount',           '' );
		WC()->session->set( 'doordash_customer_information', '' );

		do_action( 'wcdd_delivery_quote_accepted', $delivery, $order );
	}

	/**
	 * Adds a DoorDash tracking provider to the WooCommerce Shipment Tracking plugin
	 *
	 * @param array $providers Array of providers
	 * @return array Filtered array of providers
	 */
	public function wc_shipment_tracking_add_doordash_provider( $providers ) {
		// $tracking_string = '%1$s'; 
		$tracking_string = 'https://www.doordash.com/drive/portal/track/%1$s'; //?intl=en-US';

		$providers['United States']['DoorDash'] = $tracking_string;
		$providers['Canada']['DoorDash'] = $tracking_string;
		$providers['Japan']['DoorDash'] = $tracking_string;
		$providers['Australia']['DoorDash'] = $tracking_string;

		return $providers;

	}

	/**
	 * Adds routing for custom REST API endpoint
	 * 
	 * @return void
	 */
	public function wc_doordash_register_rest_route() {
		register_rest_route( 'wc/v3', '/doordash/status_updated', [
			'methods' => 'POST',
			'callback' => array( $this, 'status_updated' ),
			'permission_callback' => array( $this, 'authorize_doordash_request' ),
		] );	

		register_rest_route( 'wc/v3', '/doordash/save_auth_header', [
			'methods' => 'POST',
			'callback' => array( $this, 'save_auth_header' ),
			'permission_callback' => array( $this, 'authorize_save_auth_header' ),
		] );
	}

	/**
	 * Permissions callback for users adding webhook creds
	 *
	 * @param HTTP_Request $request
	 * @return bool True if user can perform this action
	 */
	public function authorize_save_auth_header( $request ) {
		$body = json_decode( $request->get_body() );
		$perm = user_can( $body->user_id, 'manage_woocommerce' );
		return $perm;
	}

	/**
	 * Authenticate the user accessing the custom endpoint
	 *
	 * @param array $request JSON request with updated Woocommerce_Doordash_Delivery object data
	 * @return bool True if user is authenticated, false otherwise
	 */
	public function authorize_doordash_request( $request ) {
		// Get the headers and check if the request is coming from DoorDash.
		$headers = getallheaders();
		if ( ! $headers || strpos( $headers['User-Agent'], 'DoorDashDriveWebhooks' ) === false ) {
			WCDD()->log->error( 'Webhook: Incorrect user agent' );
			return false;
		}

		if ( current_user_can( 'manage_woocommerce' ) ) {
			return true;
		} else {
			WCDD()->log->error( 'Webhook: Authenticated user cannot manage WooCommerce' );
			return false;
		}
	}

	/**
	 * Saves authorization header temporarily in a transient
	 *
	 * @param WP_REST_Request $request
	 * @return void
	 */
	public function save_auth_header( $request ) {
		$params = wp_parse_args( $request->get_params() );

		if ( $params && $params['consumer_key'] && $params['consumer_secret'] ) {
			// save the data
			$header = base64_encode( $params['consumer_key'] . ":" . $params['consumer_secret'] );
			set_transient( 'woocommerce_doordash_auth_header', "Basic $header", 10 * MINUTE_IN_SECONDS );
			return true;
		}
		
		return false;
	}

	/**
	 * Updates Order Statuses when DoorDash webhook is fired
	 *
	 * @param WP_REST_Request $request JSON request with updated Woocommerce_Doordash_Delivery object data
	 * @return string with success or error messages
	 */
	public function status_updated( $request ) {
		// parse out the request into an array
		$params = wp_parse_args( $request->get_params() );

		// check to make sure the request has both an external_delivery_id and event_name before moving forward
		if ( $params && $params['external_delivery_id'] && $params['event_name'] ) {
			$external_delivery_id = $params['external_delivery_id'];

			WCDD()->log->info( __( 'Webhook: Received webhook event "' . $params['event_name'] . '" for ID ' . $external_delivery_id ) );

			// query the order with order item meta matching the external_delivery_id
		    global $wpdb;
			$results = $wpdb->get_col(
				$wpdb->prepare(
				"
					SELECT order_items.order_id
					FROM {$wpdb->prefix}woocommerce_order_items as order_items
					LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
					LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID
					WHERE posts.post_type = 'shop_order'
					AND order_items.order_item_type = 'shipping'
					AND order_item_meta.meta_key = 'doordash_external_delivery_id'
					AND order_item_meta.meta_value = %s
					LIMIT 1", $external_delivery_id
				)
			);

			// make sure we have found a post before moving forward
			if ( $results ) {
				$order = wc_get_order( $results[0] );

				// Make sure we were able to get the order from it's ID
				if ( $order ) {
					// Get the shipping method for the order
					$methods = $order->get_shipping_methods(); 
					$method = array_shift( $methods );
				
					// Get the delivery ID and object from the shipping method
					$delivery = $method->get_meta( "doordash_delivery" );

					// if this order has doordash delivery data, move forward with updating the delivery object, order status, and notes
					if ( $delivery ) {
						// Read the order status from the request, and update the order status/notes as needed
						$dd_to_woo_status_map = array(
							'DASHER_CONFIRMED' => array(
								'note' => __( 'A Dasher has accepted your delivery and is on the way to the pickup location.', 'local-delivery-by-doordash' ),
								'wc_status' => false,
							),
							'DASHER_CONFIRMED_PICKUP_ARRIVAL' => array(
								'note' => __( 'The Dasher has confirmed that they arrived at the pickup location and are attempting to pick up the delivery.', 'local-delivery-by-doordash' ),
								'wc_status' => false,
							),
							'DASHER_PICKED_UP' => array(
								'note' => __( 'The Dasher has picked up the delivery.', 'local-delivery-by-doordash' ),
								'wc_status' => 'wcdd-picked-up',
							),
							'DASHER_CONFIRMED_DROPOFF_ARRIVAL' => array(
								'note' => __( 'The Dasher has confirmed that they arrived at the dropoff location.', 'local-delivery-by-doordash' ),
								'wc_status' => false,
							),
							'DASHER_DROPPED_OFF' => array(
								'note' => __( 'The Dasher has dropped off the delivery at the dropoff location and the delivery is complete.', 'local-delivery-by-doordash' ),
								'wc_status' => 'completed',
							),
							'DELIVERY_CANCELLED' => array(
								'note' => __( 'The delivery has been cancelled.', 'local-delivery-by-doordash' ) . empty( $params['cancellation_reason_message'] ) ? '' : sprintf(  __( 'Reason: "%s"', 'local-delivery-by-doordash' ), $params['cancellation_reason_message'] ),
								'wc_status' => 'cancelled',
							),
							'DELIVERY_RETURN_INITIALIZED' => array(
								'note' => __( 'The Dasher was unable to deliver your delivery to the dropoff location; they contacted support to arrange a return-to-pickup delivery and are returning to the pickup location.', 'local-delivery-by-doordash' ),
								'wc_status' => false,
							),
							'DASHER_CONFIRMED_RETURN_ARRIVAL' => array(
								'note' => __( 'The Dasher has confirmed that they arrived at the pickup location and are attempting to return the delivery.', 'local-delivery-by-doordash' ),
								'wc_status' => false,
							),
							'DELIVERY_RETURNED' => array(
								'note' => __( 'The delivery has been returned successfully.', 'local-delivery-by-doordash' ),
								'wc_status' => 'wcdd-returned',
							),
						);

						// find the new status in the array map
						if ( array_key_exists( $params['event_name'], $dd_to_woo_status_map ) ) {
							$new_status_details = $dd_to_woo_status_map[$params['event_name']];
						} else {
							$new_status_details = false;
						}
						if ( $new_status_details && $new_status_details['wc_status'] ) {
							// status change event received from DoorDash, update the order status
							$original_status = $order->get_status();
							$order->update_status( $new_status_details['wc_status'], $new_status_details['note'] );
						} else if ( $new_status_details ) {
							// non status change event received from DoorDash, add a note to the order
							$order->add_order_note( $new_status_details['note'] );
						} else {
							// status not found in the status map, do not make any update to the status or object
							$note = sprintf( __( 'DoorDash status update: %s.', 'local-delivery-by-doordash' ), $params['event_name'] );
							$order->add_order_note( $note );
						}

						// Create delivery object based on the updated delivery data
						$updated_delivery = new Woocommerce_Doordash_Delivery( $params );
						if ( $updated_delivery ) {
							$method->update_meta_data( 'doordash_delivery', $updated_delivery );
						}
					} else {
						WCDD()->log->error( sprintf( __( 'Webhook: DoorDash not found order #%s.', 'local-delivery-by-doordash' ), $order->get_id() ) );
						return false;
					}

					WCDD()->log->info( sprintf( __( 'Webhook: Order #%s updated successfully.', 'local-delivery-by-doordash' ), $order->get_id() ) );
					return true;
				}
			} else {
				WCDD()->log->error( sprintf( __( 'Webhook: Unable to find an order with Delivery ID %s.', 'local-delivery-by-doordash' ), $external_delivery_id ) );
				return false;
			}
		} else {
			WCDD()->log->error( __( 'Webhook: Missing required parameters.', 'local-delivery-by-doordash' ) );
			WCDD()->log->error( $request );
			return false;
		}
	}

	/**
	 * Registers custom doordash post statuses
	 * 
	 * @return void
	 */
    public function register_doordash_order_statuses() {
    	// register Delivery Picked Up status
        register_post_status( 'wc-wcdd-picked-up', array(
            'label'                     => __( 'Delivery Picked Up', 'local-delivery-by-doordash' ),
            'public'                    => true,
            'show_in_admin_status_list' => true,
            'show_in_admin_all_list'    => true,
            'exclude_from_search'       => false
        ) );
		// register Delivery Returned status
        register_post_status( 'wc-wcdd-returned', array(
            'label'                     => __( 'Delivery Returned', 'local-delivery-by-doordash' ),
            'public'                    => true,
            'show_in_admin_status_list' => true,
            'show_in_admin_all_list'    => true,
            'exclude_from_search'       => false
        ) );
    }

	/**
	 * Adds DoorDash custom order statuses
	 *
	 * @param array $order_statuses Array with all existing order statuses
	 * @return array with doordash order statuses added
	 */
    public function add_doordash_order_statuses( $order_statuses ) {
    	// add the custom order statuses to the woo drop down
        $order_statuses['wc-wcdd-picked-up'] = __( 'Delivery Picked Up', 'local-delivery-by-doordash' );
        $order_statuses['wc-wcdd-returned'] = __( 'Delivery Returned', 'local-delivery-by-doordash' );
        return $order_statuses;
    }

	/**
	 * Adds the email address configured on the selected pickup location to the admin new order email
	 *
	 * @param string $recipient Comma separated list of email recipients
	 * @param WC_Order $order Order object
	 * @param WC_Email_New_Order $email The WooCommerce email being processed
	 * @return string Filtered list of recipients
	 */
	public function new_order_email_recipient( $recipient, $order, $email ) {
		// Allow developers to disable this functionality
		if ( ! apply_filters( 'wcdd_email_new_order_to_location', true, $recipient, $order ) ) return $recipient;

		// Only run this when dealing with a real order (fixes fatal error on WooCommerce > Settings > Emails screen)
		if ( ! is_a( $order, 'WC_Order' ) ) return $recipient;

		// Get the shipping method for the order
		$methods = $order->get_shipping_methods(); 
		$method = array_shift( $methods );
	
		// Get the location ID from the meta if it exists
		$location_id = (int) $method->get_meta( "_doordash_pickup_location" );
		
		if ( $location_id ) {
			// Get the location object
			$location = new Woocommerce_Doordash_Pickup_Location( $location_id );
			// Get the email from the location and add it to the recipient list
			$recipient .= ',' . $location->get_email();
		}

		// Send the list of recipients back to the email class
		return $recipient;
	}

}
