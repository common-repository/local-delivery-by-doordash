<?php

/**
 * Main Settings
 *
 * This file is used to set the main settings fields
 *
 * @link       https://www.inverseparadox.com
 * @since      1.0.0
 *
 * @package    Woocommerce_Doordash
 * @subpackage Woocommerce_Doordash/admin/partials
 */

return array(
	array(
		'id' => $prefix . 'api_config_settings',
		'name' => __( 'API Configuration', 'local-delivery-by-doordash' ),
		'type' => 'title',
		'desc' => __( 'Visit the <a href="https://developer.doordash.com/en-US/docs/drive/tutorials/get_started_woocommerce/" target="_blank">DoorDash Documentation</a> for instructions on how to sign in to the <a href="https://developer.doordash.com/portal?wooCommerce=true" target="_blank">DoorDash Developer Portal</a> and create your access keys.', 'local-delivery-by-doordash' ),
	),
	array(
		'id' => $prefix . 'developer_id',
		'name' => __( 'Developer ID', 'local-delivery-by-doordash' ),
		'type' => 'text',
		'desc_tip' => __( 'Paste your Developer ID obtained from the DoorDash Developer Portal.', 'local-delivery-by-doordash' ),
	),
	array(
		'id' => $prefix . 'api_environment',
		'name' => __( 'Active Environment', 'local-delivery-by-doordash' ),
		'type' => 'select',
		'options' => array(
			'sandbox' => 'Sandbox',
			'production' => 'Production',
		),
		'desc_tip' => __( 'Enable <strong>Sandbox</strong> mode to test your integration with DoorDash. Enable <strong>Production</strong> mode to process live deliveries. A separate <em>Key ID</em> and <em>Signing Secret</em> are used for Sandbox and Production environments.' ),
		'css' => 'max-width: 120px;',
	),
	array(
		'id' => $prefix . 'production_key_id',
		'class' => 'wcdd-api-keys__production',
		'name' => __( 'Key ID', 'local-delivery-by-doordash' ),
		'type' => 'text',
		'desc_tip' => __( 'Paste your <em>Production</em> Key ID obtained from the DoorDash Developer Portal.', 'local-delivery-by-doordash' ),
	),
	array(
		'id' => $prefix . 'production_signing_secret',
		'class' => 'wcdd-api-keys__production',
		'name' => __( 'Signing Secret', 'local-delivery-by-doordash' ),
		'type' => 'text',
		'desc_tip' => __( 'Paste your <em>Production</em> Signing Secret obtained from the DoorDash Developer Portal.', 'local-delivery-by-doordash' ),
	),
	array(
		'id' => $prefix . 'sandbox_key_id',
		'class' => 'wcdd-api-keys__sandbox',
		'name' => __( 'Key ID', 'local-delivery-by-doordash' ),
		'type' => 'text',
		'desc_tip' => __( 'Paste your <em>Sandbox</em> Key ID obtained from the DoorDash Developer Portal.', 'local-delivery-by-doordash' ),
	),
	array(
		'id' => $prefix . 'sandbox_signing_secret',
		'class' => 'wcdd-api-keys__sandbox',
		'name' => __( 'Signing Secret', 'local-delivery-by-doordash' ),
		'type' => 'text',
		'desc_tip' => __( 'Paste your <em>Sandbox</em> Signing Secret obtained from the DoorDash Developer Portal.', 'local-delivery-by-doordash' ),
	),
	array(
		'id'        => $prefix . 'api_config_settings',
		'name' => __( 'API Configuration', 'local-delivery-by-doordash' ),
		'type'      => 'sectionend',
	),

	array(
		'id' => $prefix . 'delivery_config_settings',
		'name' => __( 'Delivery Configuration', 'local-delivery-by-doordash' ),
		'type' => 'title',
		'desc' => __( 'Configure settings for deliveries in your WooCommerce shop.', 'local-delivery-by-doordash' ),
	),
	array(
		'id'        => $prefix . 'delivery_scheduling',
		'name'      => __( 'Delivery Scheduling', 'local-delivery-by-doordash' ), 
		'type'      => 'select',
		// 'class'     => 'wc-enhanced-select',
		'options'   => array(
			'both' => 'Both ASAP and Scheduled Deliveries',
			'immediate' => 'ASAP Delivery Only',
			'scheduled' => 'Scheduled Delivery Only',
		),
		'desc_tip'  => __( 'Choose the type of delivery scheduling available to users at checkout.', 'local-delivery-by-doordash' ),
	),
	array(
		'id' => $prefix . 'number_of_days_ahead',
		'name' => __( 'Scheduled Delivery Days Ahead', 'local-delivery-by-doordash' ),
		'type' => 'number',
		'desc_tip' => __( 'Number of days ahead that deliveries can be scheduled.', 'local-delivery-by-doordash' ),
		'css'      => 'max-width:120px;',
		'custom_attributes' => array( 'min' => 1, 'max' => 90 ),
		'default' => 14,
	),
	array(
		'id' => $prefix . 'lead_time',
		'name' => __( 'Order Lead Time', 'local-delivery-by-doordash' ),
		'type' => 'number',
		'desc_tip' => __( 'Number of minutes to allow for preparation of an order.', 'local-delivery-by-doordash' ),
		'css'      => 'max-width:120px;',
	),
	array(
		'id' => $prefix . 'default_pickup_instructions',
		'name' => __( 'Default Pickup Instructions', 'woocommerce-doordash' ),
		'type' => 'text',
		'desc_tip' => __( 'These instructions will be provided to Dashers when picking up orders. These instructions can be overridden for each location on the <em>Edit Location</em> screen.', 'local-delivery-by-doordash' ),
	),
	array(
		'id' => $prefix . 'enable_alcohol_tobacco',
		'name' => __( 'Liquor Store/Smoke Shop Mode', 'woocommerce-doordash' ),
		'type'      => 'select',
		'options'   => array(
			'disabled' => 'Disabled',
			'enabled' => 'Enabled',
		),
		'desc_tip' => __( 'Contact DoorDash at developer-support@doordash.com to get approval before enabling this setting in sandbox or production. If your business has not been previously approved by DoorDash for alcohol and tobacco delivery, enabling this setting will cause all delivery requests to be rejected.', 'local-delivery-by-doordash' ),
		'default' => 'disabled',
	),
	array(
		'id' => $prefix . 'delivery_config_settings',
		'name' => __( 'Delivery Configuration', 'local-delivery-by-doordash' ),
		'type' => 'sectionend',
	),

	array(
		'id' => $prefix . 'fee_settings',
		'name' => __( 'Fees Configuration', 'local-delivery-by-doordash' ),
		'type' => 'title',
		'desc' => __( 'Set the fees charged to your customers.', 'local-delivery-by-doordash' ),
	),
	array(
		'id' => $prefix . 'fees_mode',
		'name' => __( 'Delivery Fees Mode', 'local-delivery-by-doordash' ),
		'type' => 'select',
		'options' => array(
			'no_rate' => __( 'No charge for customer (shop pays)', 'local-delivery-by-doordash' ),
			'quoted_rate' => __( 'Charge customer the quoted DoorDash rate', 'local-delivery-by-doordash' ),
			'fixed_rate' => __( 'Charge customer a fixed rate', 'local-delivery-by-doordash' ),
		),
		'desc_tip' => __( 'Choose how the customer will be charged for deliveries. Note: DoorDash will collect all delivery fees and tips directly from the shop owner. You can choose to offer free delivery or pass through any portion of DoorDash’s quoted delivery fee to the customer.', 'local-delivery-by-doordash' ),
	),
	array(
		'id' => $prefix . 'delivery_fee',
		'name' => __( 'Delivery Fee', 'local-delivery-by-doordash' ),
		'type' => 'number',
		'custom_attributes' => array( 'step' => 'any', 'min' => '0' ),
		'desc_tip' => __( 'Add a delivery fee in this amount to customer orders. If used with the "Quoted Rate" option above, customer will be charged this fee in addition to the quoted DoorDash rate.', 'local-delivery-by-doordash' ),
	),
	array(
		'id' => $prefix . 'tipping',
		'name' => __( 'Dasher Tipping', 'local-delivery-by-doordash' ),
		'type' => 'select',
		'desc_tip' => __( 'Select Enabled to allow your customers to add a tip for their DoorDasher. 100% of tips are passed on to the Dasher.', 'local-delivery-by-doordash' ),
		'options' => array(
			'enabled' => __( 'Enabled', 'local-delivery-by-doordash' ),
			'disabled' => __( 'Disabled', 'local-delivery-by-doordash' ),
		),
		'css'      => 'max-width:120px;',
	),
	array(
		'id' => $prefix . 'fee_settings',
		'name' => __( 'Fees Configuration', 'local-delivery-by-doordash' ),
		'type' => 'sectionend',
	),

	array(
		'id' => $prefix . 'hours_config_settings',
		'name' => __( 'Default Delivery Hours', 'local-delivery-by-doordash' ),
		'type' => 'title',
		'css' => 'max-width: 300px',
		'desc' => sprintf( __( 'Set the default hours that your shop will fulfill deliveries. These will be the default when new locations are created, if a location&rsquo;s individual hours are disabled, or if no locations have been configured.
		
		Enter hours for each day with a dash separating the opening and closing time, eg <code>10:00am - 8:00pm</code>. 
		Multiple ranges can also be entered separated with a comma, eg, <code>10:00am - 1:00pm, 4:00pm - 7:00pm</code>. 
		If deliveries are not offered, leave the field for that day blank.

		Make sure you have configured your Timezone in your WordPress settings under <a href="%s">Settings &raquo; General</a>. Current local time is <code>%s</code>.', 'local-delivery-by-doordash' ), admin_url( 'options-general.php#timezone_string' ), date_i18n( _x( 'Y-m-d H:i:s', 'timezone date format' ) ) ),
	),
	array(
		'id' => $prefix . 'sunday_hours',
		'name' => __( 'Sunday', 'local-delivery-by-doordash' ),
		'type' => 'text',
	),
	array(
		'id' => $prefix . 'monday_hours',
		'name' => __( 'Monday', 'local-delivery-by-doordash' ),
		'type' => 'text',
	),
	array(
		'id' => $prefix . 'tuesday_hours',
		'name' => __( 'Tuesday', 'local-delivery-by-doordash' ),
		'type' => 'text',
	),
	array(
		'id' => $prefix . 'wednesday_hours',
		'name' => __( 'Wednesday', 'local-delivery-by-doordash' ),
		'type' => 'text',
	),
	array(
		'id' => $prefix . 'thursday_hours',
		'name' => __( 'Thursday', 'local-delivery-by-doordash' ),
		'type' => 'text',
	),
	array(
		'id' => $prefix . 'friday_hours',
		'name' => __( 'Friday', 'local-delivery-by-doordash' ),
		'type' => 'text',
	),
	array(
		'id' => $prefix . 'saturday_hours',
		'name' => __( 'Saturday', 'local-delivery-by-doordash' ),
		'type' => 'text',
	),
	array(
		'id' => $prefix . 'hours_config_settings',
		'name' => __( 'Default Delivery Hours', 'local-delivery-by-doordash' ),
		'type' => 'sectionend',
	),

);