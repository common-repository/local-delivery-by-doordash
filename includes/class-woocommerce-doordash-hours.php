<?php

/**
 * DoorDash Hours
 *
 * @link       https://www.inverseparadox.com
 * @since      1.0.0
 *
 * @package    Woocommerce_Doordash
 * @subpackage Woocommerce_Doordash/includes
 */

/**
 * DoorDash Hours
 *
 * Functions to deal with delivery hours
 *
 * @package    Woocommerce_Doordash
 * @subpackage Woocommerce_Doordash/includes
 * @author     Inverse Paradox <erik@inverseparadox.net>
 */
class Woocommerce_Doordash_Hours {

	/**
	 * Time format to save hours in. Defaults to WordPress time_format option
	 * 
	 * @var string
	 */
	public $time_fmt;

	/**
	 * Initialize
	 */
	public function __construct() {
		// Get the site's setting for the time format, allow filtering
		$this->time_fmt = apply_filters( 'wcdd_time_format', get_option( 'time_format' ) );		
	}

	/**
	 * Normalize the value for a set of delivery hour ranges to a common format
	 *
	 * @param string $hours Ranges separated by commas, start and end times separated by dashes
	 * @return string Normalized string
	 */
	public function normalize_hour_ranges( $hours ) {
		if ( empty( $hours ) ) return $hours;

		// Break the hour ranges into an array
		$ranges = $this->get_hour_ranges( $hours );
		
		foreach ( $ranges as &$range ) {
			// Normalize the times in each range according to the time format, ignore more than two times
			$range = date( $this->time_fmt, $range[0] ) . ' - ' . date( $this->time_fmt, $range[1] );
		}

		// Recombine the ranges into a comma separated string
		return implode( ', ', $ranges );
	}

	/**
	 * Converts string with ranges into multidimensional array
	 *
	 * @param string $hours Ranges separated by commas, start and end times separated by dashes
	 * @return array Array of arrays containing start and end times.
	 */
	public function get_hour_ranges( $hours ) {
		// Split the string into ranges based on comma separation
		$ranges = explode( ',', $hours );

		foreach ( $ranges as &$range ) {
			// Separate each range into start and end times
			$range = explode( '-', $range );

			// Operate on the start and end times
			foreach ( $range as &$time ) {
				// Trim off the whitespace
				$time = trim( $time );
				// Convert to timestamp
				$time = strtotime( "1970-01-01 $time" ); // Use the epoch so we just get the seconds value of the TIME, not the date
				if ( $time === 0 ) $time = DAY_IN_SECONDS; // Edge case if user entered "midnight"
			}
		}

		// Return the ranges
		return $ranges;
	}

	/**
	 * Fill an array with 15-minute increments between a start and end time
	 *
	 * @param int $start Start timestamp
	 * @param int $end Ending timestamp
	 * @return array Associative array with timestamp => label
	 */
	public function fill_range( $start, $end, $datestamp = 0 ) {
		// Get the lead time
		$lead_time = intval( get_option( 'woocommerce_doordash_lead_time' ) ) * MINUTE_IN_SECONDS;

		$average_delivery_time = apply_filters( 'wcdd_average_delivery_time', 20 ) * MINUTE_IN_SECONDS;

		// Get the current time and add the lead time
		$now = time() + $lead_time + $average_delivery_time; // accounts for average delivery time of 20 min

		// Account for timezones in datestamp
		$offset = get_option( 'gmt_offset' ) * HOUR_IN_SECONDS;
		$datestamp = intval( $datestamp - $offset );

		// Allow devs to filter the interval for times on the selector
		$increment = apply_filters( 'wcdd_pickup_time_increment', 15 * MINUTE_IN_SECONDS );
		
		// Set up values for the loop
		$options = array();
		$value = $start + $average_delivery_time;

		// Create values in the array for every fifteen minutes
		while ( $value <= $end + $average_delivery_time ) {
			if ( ( $datestamp + $value ) >= $now ) { // Only output future times
				$options[ $value + $datestamp ] = date( $this->time_fmt, $value ); // Key is timestamp, value is the label
			}
			$value += $increment; // Increment the value by 15 min
		}

		// Return the array
		return $options;
	}

	/**
	 * Round minutes to increment
	 *
	 * @param int $minutes
	 * @param int $increment
	 * @return int $minutes
	 */
	public function round_minutes( $minutes, $increment = 10 ) {
		$left = $minutes > 60 ? $minutes % 60 : $minutes;

		if ( $left % $increment > 0 ) {
			$minutes += ( $increment - $left % $increment );
		}

		return $minutes;
	}
	
}