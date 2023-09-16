<?php
/**
 * Array of default dummy posts
 *
 * @since      1.0.0
 * @package    Travel_Booking_Toolkit
 * @subpackage Travel_Booking_Toolkit/includes
 */
class Travel_Booking_Toolkit_Dummy_Array {

	/**
	 * Default Activities
	 */
	public function default_activities() {
		return apply_filters(
			'travel_booking_toolkit_default_activities',
			array(
				array(
					'thumbnail' => TBT_FILE_URL . '/includes/images/img3.jpg',
					'name'      => __( 'Whitewater Rafting', 'travel-booking-toolkit' ),
					'desc'      => __( 'Tell your potential customers about the services that you provide here.', 'travel-booking-toolkit' ),
					'url'       => '#',
				),
				array(
					'thumbnail' => TBT_FILE_URL . '/includes/images/img4.jpg',
					'name'      => __( 'Hiking', 'travel-booking-toolkit' ),
					'desc'      => __( 'Tell your potential customers about the services that you provide here.', 'travel-booking-toolkit' ),
					'url'       => '#',
				),
				array(
					'thumbnail' => TBT_FILE_URL . '/includes/images/img5.jpg',
					'name'      => __( 'Skiing', 'travel-booking-toolkit' ),
					'desc'      => __( 'Tell your potential customers about the services that you provide here.', 'travel-booking-toolkit' ),
					'url'       => '#',
				),
				array(
					'thumbnail' => TBT_FILE_URL . '/includes/images/img6.jpg',
					'name'      => __( 'Cycling', 'travel-booking-toolkit' ),
					'desc'      => __( 'Tell your potential customers about the services that you provide here.', 'travel-booking-toolkit' ),
					'url'       => '#',
				),
				array(
					'thumbnail' => TBT_FILE_URL . '/includes/images/img3.jpg',
					'name'      => __( 'Paragliding', 'travel-booking-toolkit' ),
					'desc'      => __( 'Tell your potential customers about the services that you provide here.', 'travel-booking-toolkit' ),
					'url'       => '#',
				),
				array(
					'thumbnail' => TBT_FILE_URL . '/includes/images/img4.jpg',
					'name'      => __( 'Whitewater Rafting', 'travel-booking-toolkit' ),
					'desc'      => __( 'Tell your potential customers about the services that you provide here.', 'travel-booking-toolkit' ),
					'url'       => '#',
				),
				array(
					'thumbnail' => TBT_FILE_URL . '/includes/images/img5.jpg',
					'name'      => __( 'Hiking', 'travel-booking-toolkit' ),
					'desc'      => __( 'Tell your potential customers about the services that you provide here.', 'travel-booking-toolkit' ),
					'url'       => '#',
				),
				array(
					'thumbnail' => TBT_FILE_URL . '/includes/images/img6.jpg',
					'name'      => __( 'Skiing', 'travel-booking-toolkit' ),
					'desc'      => __( 'Tell your potential customers about the services that you provide here.', 'travel-booking-toolkit' ),
					'url'       => '#',
				),
			)
		);
	}

	/**
	 * Default Why Us
	 */
	public function default_why_us() {
		return apply_filters(
			'travel_booking_toolkit_default_why_us',
			array(
				array(
					'whyus-icon'  => 'fa fa-check',
					'title'       => __( 'TripAdvisor Multiple Award winning company', 'travel-booking-toolkit' ),
					'description' => __( 'We\'ve received Certificate of Excellence award from TripAdvisor, the world\'s largest travel website.', 'travel-booking-toolkit' ),
					'url'         => '',
				),
				array(
					'whyus-icon'  => 'fa fa-check',
					'title'       => __( '100% Customizable', 'travel-booking-toolkit' ),
					'description' => __( 'Tell us about your trip requirement. We\'ll work together to customize your trip to meet your exact requirement so that you have a memorable trip.', 'travel-booking-toolkit' ),
					'url'         => '',
				),
				array(
					'whyus-icon'  => 'fa fa-check',
					'title'       => __( 'Local Experts. Middle-man Free Pricing', 'travel-booking-toolkit' ),
					'description' => __( 'We\'re a local travel agency. When you book with us, you get best possible price, which is middle-man free.', 'travel-booking-toolkit' ),
					'url'         => '',
				),
				array(
					'whyus-icon'  => 'fa fa-check',
					'title'       => __( 'No Hidden Charges', 'travel-booking-toolkit' ),
					'description' => __( 'We don\'t add hidden extras cost. All trips include travel permit, lodging and fooding. There are no surprises with hidden costs.', 'travel-booking-toolkit' ),
					'url'         => '',
				),
				array(
					'whyus-icon'  => 'fa fa-check',
					'title'       => __( 'TripAdvisor Multiple Award winning company', 'travel-booking-toolkit' ),
					'description' => __( 'We\'ve received Certificate of Excellence award from TripAdvisor, the world\'s largest travel website.', 'travel-booking-toolkit' ),
					'url'         => '',
				),
				array(
					'whyus-icon'  => 'fa fa-check',
					'title'       => __( '100% Customizable', 'travel-booking-toolkit' ),
					'description' => __( 'Tell us about your trip requirement. We\'ll work together to customize your trip to meet your exact requirement so that you have a memorable trip.', 'travel-booking-toolkit' ),
					'url'         => '',
				),
			)
		);
	}

	/**
	 * Default Stat Counter
	 */
	public function default_stat_counter() {
		return apply_filters(
			'travel_booking_toolkit_default_stat_counter',
			array(
				array(
					'icon'   => 'fa fa-group',
					'title'  => __( 'Number of Customers', 'travel-booking-toolkit' ),
					'number' => __( '859', 'travel-booking-toolkit' ),
				),
				array(
					'icon'   => 'fa fa-globe',
					'title'  => __( 'Number of Trips', 'travel-booking-toolkit' ),
					'number' => __( '1021', 'travel-booking-toolkit' ),
				),
				array(
					'icon'   => 'fa fa-plane',
					'title'  => __( 'Trips Types', 'travel-booking-toolkit' ),
					'number' => __( '225', 'travel-booking-toolkit' ),
				),
				array(
					'icon'   => 'fa fa-bus',
					'title'  => __( 'Travel with Bus', 'travel-booking-toolkit' ),
					'number' => __( '1020', 'travel-booking-toolkit' ),
				),
			)
		);
	}

	/**
	 * Default Popular posts
	 */
	public function travel_booking_toolkit_default_trip_popular_packages() {
		$arr = apply_filters(
			'travel_booking_toolkit_default_popular_packages',
			array(
				array(
					'img'            => TBT_FILE_URL . '/includes/images/popular-packages-img1.jpg',
					'discount'       => __( '45% ', 'travel-booking-toolkit' ),
					'featured'       => true,
					'old_price'      => __( '$ 1200', 'travel-booking-toolkit' ),
					'new_price'      => __( '$ 900', 'travel-booking-toolkit' ),
					'group_discount' => true,
					'rating'         => TBT_FILE_URL . '/includes/images/star-rating1.png',
					'title'          => __( 'Tiananmen Square, Forbidden City and Temple of Heaven', 'travel-booking-toolkit' ),
					'destination'    => __( 'Thailand', 'travel-booking-toolkit' ),
					'days'           => __( '15 Days', 'travel-booking-toolkit' ),
					'next-trip-info' => array(
						'first'  => array(
							'date'       => __( 'Jan 10', 'travel-booking-toolkit' ),
							'space_left' => __( '4 spaces left', 'travel-booking-toolkit' ),
						),
						'second' => array(
							'date'       => __( 'Feb 21', 'travel-booking-toolkit' ),
							'space_left' => __( '18 spaces left', 'travel-booking-toolkit' ),
						),
					),
				),
				array(
					'img'            => TBT_FILE_URL . '/includes/images/popular-packages-img2.jpg',
					'discount'       => __( '15% ', 'travel-booking-toolkit' ),
					'featured'       => false,
					'old_price'      => __( '$ 1200', 'travel-booking-toolkit' ),
					'new_price'      => __( '$ 900', 'travel-booking-toolkit' ),
					'group_discount' => true,
					'rating'         => TBT_FILE_URL . '/includes/images/star-rating2.png',
					'title'          => __( 'TMt Fuji Day Trip including Lake Ashi Sightseeing Cruise', 'travel-booking-toolkit' ),
					'destination'    => __( 'Japan', 'travel-booking-toolkit' ),
					'days'           => __( '15 Days', 'travel-booking-toolkit' ),
					'next-trip-info' => array(
						'first'  => array(
							'date'       => __( 'Jan 10', 'travel-booking-toolkit' ),
							'space_left' => __( '4 spaces left', 'travel-booking-toolkit' ),
						),
						'second' => array(
							'date'       => __( 'Feb 21', 'travel-booking-toolkit' ),
							'space_left' => __( '18 spaces left', 'travel-booking-toolkit' ),
						),
					),
				),
				array(
					'img'            => TBT_FILE_URL . '/includes/images/popular-packages-img3.jpg',
					'discount'       => __( '5% ', 'travel-booking-toolkit' ),
					'featured'       => true,
					'old_price'      => __( '$ 1200', 'travel-booking-toolkit' ),
					'new_price'      => __( '$ 900', 'travel-booking-toolkit' ),
					'group_discount' => true,
					'rating'         => TBT_FILE_URL . '/includes/images/star-rating3.png',
					'title'          => __( 'Floating markets and Sampran Riverside Day Tour from', 'travel-booking-toolkit' ),
					'destination'    => __( 'Rome', 'travel-booking-toolkit' ),
					'days'           => __( '15 Days', 'travel-booking-toolkit' ),
					'next-trip-info' => array(
						'first'  => array(
							'date'       => __( 'Jan 10', 'travel-booking-toolkit' ),
							'space_left' => __( '4 spaces left', 'travel-booking-toolkit' ),
						),
						'second' => array(
							'date'       => __( 'Feb 21', 'travel-booking-toolkit' ),
							'space_left' => __( '18 spaces left', 'travel-booking-toolkit' ),
						),
					),
				),
			)
		);
		return $arr;
	}

	/**
	 * Default Feature posts
	 */
	public function travel_booking_toolkit_default_trip_featured_posts() {

		$arr = apply_filters(
			'travel_booking_toolkit_default_featured_post',
			array(
				array(
					'img'            => TBT_FILE_URL . '/includes/images/popular-packages-img1.jpg',
					'discount'       => __( '45% ', 'travel-booking-toolkit' ),
					'featured'       => true,
					'old_price'      => __( '$ 1200', 'travel-booking-toolkit' ),
					'new_price'      => __( '$ 900', 'travel-booking-toolkit' ),
					'group_discount' => true,
					'rating'         => TBT_FILE_URL . '/includes/images/star-rating1.png',
					'title'          => __( 'Tiananmen Square, Forbidden City and Temple of Heaven', 'travel-booking-toolkit' ),
					'destination'    => __( 'Thailand', 'travel-booking-toolkit' ),
					'days'           => __( '15 Days', 'travel-booking-toolkit' ),
					'next-trip-info' => array(
						'first'  => array(
							'date'       => __( 'Jan 10', 'travel-booking-toolkit' ),
							'space_left' => __( '4 spaces left', 'travel-booking-toolkit' ),
						),
						'second' => array(
							'date'       => __( 'Feb 21', 'travel-booking-toolkit' ),
							'space_left' => __( '18 spaces left', 'travel-booking-toolkit' ),
						),
					),
				),
				array(
					'img'            => TBT_FILE_URL . '/includes/images/popular-packages-img2.jpg',
					'discount'       => __( '15% ', 'travel-booking-toolkit' ),
					'featured'       => false,
					'old_price'      => __( '$ 1200', 'travel-booking-toolkit' ),
					'new_price'      => __( '$ 900', 'travel-booking-toolkit' ),
					'group_discount' => true,
					'rating'         => TBT_FILE_URL . '/includes/images/star-rating2.png',
					'title'          => __( 'TMt Fuji Day Trip including Lake Ashi Sightseeing Cruise', 'travel-booking-toolkit' ),
					'destination'    => __( 'Japan', 'travel-booking-toolkit' ),
					'days'           => __( '15 Days', 'travel-booking-toolkit' ),
					'next-trip-info' => array(
						'first'  => array(
							'date'       => __( 'Jan 10', 'travel-booking-toolkit' ),
							'space_left' => __( '4 spaces left', 'travel-booking-toolkit' ),
						),
						'second' => array(
							'date'       => __( 'Feb 21', 'travel-booking-toolkit' ),
							'space_left' => __( '18 spaces left', 'travel-booking-toolkit' ),
						),
					),
				),
				array(
					'img'            => TBT_FILE_URL . '/includes/images/popular-packages-img3.jpg',
					'discount'       => __( '5% ', 'travel-booking-toolkit' ),
					'featured'       => true,
					'old_price'      => __( '$ 1200', 'travel-booking-toolkit' ),
					'new_price'      => __( '$ 900', 'travel-booking-toolkit' ),
					'group_discount' => true,
					'rating'         => TBT_FILE_URL . '/includes/images/star-rating3.png',
					'title'          => __( 'Floating markets and Sampran Riverside Day Tour from', 'travel-booking-toolkit' ),
					'destination'    => __( 'Rome', 'travel-booking-toolkit' ),
					'days'           => __( '15 Days', 'travel-booking-toolkit' ),
					'next-trip-info' => array(
						'first'  => array(
							'date'       => __( 'Jan 10', 'travel-booking-toolkit' ),
							'space_left' => __( '4 spaces left', 'travel-booking-toolkit' ),
						),
						'second' => array(
							'date'       => __( 'Feb 21', 'travel-booking-toolkit' ),
							'space_left' => __( '18 spaces left', 'travel-booking-toolkit' ),
						),
					),
				),
				array(
					'img'            => TBT_FILE_URL . '/includes/images/popular-packages-img4.jpg',
					'discount'       => __( '25% ', 'travel-booking-toolkit' ),
					'featured'       => true,
					'old_price'      => __( '$ 1200', 'travel-booking-toolkit' ),
					'new_price'      => __( '$ 900', 'travel-booking-toolkit' ),
					'group_discount' => true,
					'rating'         => TBT_FILE_URL . '/includes/images/star-rating1.png',
					'title'          => __( 'Mt Fuji Day Trip including Lake Ashi Sightseeing Cruise', 'travel-booking-toolkit' ),
					'destination'    => __( 'Japan', 'travel-booking-toolkit' ),
					'days'           => __( '15 Days', 'travel-booking-toolkit' ),
					'next-trip-info' => array(
						'first'  => array(
							'date'       => __( 'Jan 10', 'travel-booking-toolkit' ),
							'space_left' => __( '4 spaces left', 'travel-booking-toolkit' ),
						),
						'second' => array(
							'date'       => __( 'Feb 21', 'travel-booking-toolkit' ),
							'space_left' => __( '18 spaces left', 'travel-booking-toolkit' ),
						),
					),
				),
				array(
					'img'            => TBT_FILE_URL . '/includes/images/popular-packages-img5.jpg',
					'discount'       => __( '12% ', 'travel-booking-toolkit' ),
					'featured'       => false,
					'old_price'      => __( '$ 2800', 'travel-booking-toolkit' ),
					'new_price'      => __( '$ 2200', 'travel-booking-toolkit' ),
					'group_discount' => true,
					'rating'         => TBT_FILE_URL . '/includes/images/star-rating2.png',
					'title'          => __( 'Floating Markets and Sampran Riverside Day Tour from', 'travel-booking-toolkit' ),
					'destination'    => __( 'Rome', 'travel-booking-toolkit' ),
					'days'           => __( '15 Days', 'travel-booking-toolkit' ),
					'next-trip-info' => array(
						'first'  => array(
							'date'       => __( 'Jan 10', 'travel-booking-toolkit' ),
							'space_left' => __( '4 spaces left', 'travel-booking-toolkit' ),
						),
						'second' => array(
							'date'       => __( 'Feb 21', 'travel-booking-toolkit' ),
							'space_left' => __( '18 spaces left', 'travel-booking-toolkit' ),
						),
					),
				),
				array(
					'img'            => TBT_FILE_URL . '/includes/images/popular-packages-img6.jpg',
					'discount'       => __( '50% ', 'travel-booking-toolkit' ),
					'featured'       => true,
					'old_price'      => __( '$ 1200', 'travel-booking-toolkit' ),
					'new_price'      => __( '$ 900', 'travel-booking-toolkit' ),
					'group_discount' => true,
					'rating'         => TBT_FILE_URL . '/includes/images/star-rating3.png',
					'title'          => __( 'Tiananmen Square, Forbidden City and Temple of Heaven', 'travel-booking-toolkit' ),
					'destination'    => __( 'Thailand', 'travel-booking-toolkit' ),
					'days'           => __( '15 Days', 'travel-booking-toolkit' ),
					'next-trip-info' => array(
						'first'  => array(
							'date'       => __( 'Jan 10', 'travel-booking-toolkit' ),
							'space_left' => __( '4 spaces left', 'travel-booking-toolkit' ),
						),
						'second' => array(
							'date'       => __( 'Feb 21', 'travel-booking-toolkit' ),
							'space_left' => __( '18 spaces left', 'travel-booking-toolkit' ),
						),
					),
				),
			)
		);

		return $arr;
	}

	/**
	 * Default Deals posts
	 */
	public function travel_booking_toolkit_default_trip_deal_posts() {

		return apply_filters(
			'travel_booking_toolkit_default_deal_post',
			array(
				array(
					'img'            => TBT_FILE_URL . '/includes/images/popular-packages-img1.jpg',
					'discount'       => __( '45% Off', 'travel-booking-toolkit' ),
					'featured'       => true,
					'old_price'      => __( '$ 1200', 'travel-booking-toolkit' ),
					'new_price'      => __( '$ 900', 'travel-booking-toolkit' ),
					'group_discount' => true,
					'rating'         => TBT_FILE_URL . '/includes/images/star-rating1.png',
					'title'          => __( 'Tiananmen Square, Forbidden City and Temple of Heaven', 'travel-booking-toolkit' ),
					'destination'    => __( 'Thailand', 'travel-booking-toolkit' ),
					'days'           => __( '15 Days', 'travel-booking-toolkit' ),
					'next-trip-info' => array(
						'first'  => array(
							'date'       => __( 'Jan 10', 'travel-booking-toolkit' ),
							'space_left' => __( '4 spaces left', 'travel-booking-toolkit' ),
						),
						'second' => array(
							'date'       => __( 'Feb 21', 'travel-booking-toolkit' ),
							'space_left' => __( '18 spaces left', 'travel-booking-toolkit' ),
						),
					),
				),
				array(
					'img'            => TBT_FILE_URL . '/includes/images/popular-packages-img2.jpg',
					'discount'       => __( '15% Off', 'travel-booking-toolkit' ),
					'featured'       => false,
					'old_price'      => __( '$ 1200', 'travel-booking-toolkit' ),
					'new_price'      => __( '$ 900', 'travel-booking-toolkit' ),
					'group_discount' => true,
					'rating'         => TBT_FILE_URL . '/includes/images/star-rating2.png',
					'title'          => __( 'TMt Fuji Day Trip including Lake Ashi Sightseeing Cruise', 'travel-booking-toolkit' ),
					'destination'    => __( 'Japan', 'travel-booking-toolkit' ),
					'days'           => __( '15 Days', 'travel-booking-toolkit' ),
					'next-trip-info' => array(
						'first'  => array(
							'date'       => __( 'Jan 10', 'travel-booking-toolkit' ),
							'space_left' => __( '4 spaces left', 'travel-booking-toolkit' ),
						),
						'second' => array(
							'date'       => __( 'Feb 21', 'travel-booking-toolkit' ),
							'space_left' => __( '18 spaces left', 'travel-booking-toolkit' ),
						),
					),
				),
				array(
					'img'            => TBT_FILE_URL . '/includes/images/popular-packages-img3.jpg',
					'discount'       => __( '5% Off', 'travel-booking-toolkit' ),
					'featured'       => false,
					'old_price'      => __( '$ 1200', 'travel-booking-toolkit' ),
					'new_price'      => __( '$ 900', 'travel-booking-toolkit' ),
					'group_discount' => true,
					'rating'         => TBT_FILE_URL . '/includes/images/star-rating3.png',
					'title'          => __( 'Floating markets and Sampran Riverside Day Tour from', 'travel-booking-toolkit' ),
					'destination'    => __( 'Rome', 'travel-booking-toolkit' ),
					'days'           => __( '15 Days', 'travel-booking-toolkit' ),
					'next-trip-info' => array(
						'first'  => array(
							'date'       => __( 'Jan 10', 'travel-booking-toolkit' ),
							'space_left' => __( '4 spaces left', 'travel-booking-toolkit' ),
						),
						'second' => array(
							'date'       => __( 'Feb 21', 'travel-booking-toolkit' ),
							'space_left' => __( '18 spaces left', 'travel-booking-toolkit' ),
						),
					),
				),
				array(
					'img'            => TBT_FILE_URL . '/includes/images/popular-packages-img4.jpg',
					'discount'       => __( '25% Off', 'travel-booking-toolkit' ),
					'featured'       => true,
					'old_price'      => __( '$ 1200', 'travel-booking-toolkit' ),
					'new_price'      => __( '$ 900', 'travel-booking-toolkit' ),
					'group_discount' => true,
					'rating'         => TBT_FILE_URL . '/includes/images/star-rating1.png',
					'title'          => __( 'Mt Fuji Day Trip including Lake Ashi Sightseeing Cruise', 'travel-booking-toolkit' ),
					'destination'    => __( 'Japan', 'travel-booking-toolkit' ),
					'days'           => __( '15 Days', 'travel-booking-toolkit' ),
					'next-trip-info' => array(
						'first'  => array(
							'date'       => __( 'Jan 10', 'travel-booking-toolkit' ),
							'space_left' => __( '4 spaces left', 'travel-booking-toolkit' ),
						),
						'second' => array(
							'date'       => __( 'Feb 21', 'travel-booking-toolkit' ),
							'space_left' => __( '18 spaces left', 'travel-booking-toolkit' ),
						),
					),
				),
			)
		);
	}

	/**
	 * Default Destination
	 */
	public function travel_booking_toolkit_default_destination() {

		return apply_filters(
			'travel_booking_toolkit_default_destination',
			array(
				array(
					'img'        => TBT_FILE_URL . '/includes/images/popular-destination-img1.jpg',
					'title'      => __( 'Nepal', 'travel-booking-toolkit' ),
					'trip_count' => __( '42 Trips', 'travel-booking-toolkit' ),
				),
				array(
					'img'        => TBT_FILE_URL . '/includes/images/popular-destination-img2.jpg',
					'title'      => __( 'Germany', 'travel-booking-toolkit' ),
					'trip_count' => __( '29 Trips', 'travel-booking-toolkit' ),
				),
				array(
					'img'        => TBT_FILE_URL . '/includes/images/popular-destination-img3.jpg',
					'title'      => __( 'Thailand', 'travel-booking-toolkit' ),
					'trip_count' => __( '22 Trips', 'travel-booking-toolkit' ),
				),
				array(
					'img'        => TBT_FILE_URL . '/includes/images/popular-destination-img4.jpg',
					'title'      => __( 'Vietnam', 'travel-booking-toolkit' ),
					'trip_count' => __( '42 Trips', 'travel-booking-toolkit' ),
				),
			)
		);
	}

	 /**
	  * Default Activities
	  */
	public function travel_booking_toolkit_default_activities() {

		return apply_filters(
			'travel_booking_toolkit_default_activities',
			array(
				array(
					'img'   => TBT_FILE_URL . '/includes/images/activities-img1.jpg',
					'title' => __( 'Peak Climbing', 'travel-booking-toolkit' ),
				),
				array(
					'img'   => TBT_FILE_URL . '/includes/images/activities-img2.jpg',
					'title' => __( 'Sightseeing', 'travel-booking-toolkit' ),
				),
				array(
					'img'   => TBT_FILE_URL . '/includes/images/activities-img3.jpg',
					'title' => __( 'Paragliding', 'travel-booking-toolkit' ),
				),
				array(
					'img'   => TBT_FILE_URL . '/includes/images/activities-img4.jpg',
					'title' => __( 'Cycling', 'travel-booking-toolkit' ),
				),
				array(
					'img'   => TBT_FILE_URL . '/includes/images/activities-img5.jpg',
					'title' => __( 'Scuba Diving', 'travel-booking-toolkit' ),
				),
				array(
					'img'   => TBT_FILE_URL . '/includes/images/activities-img6.jpg',
					'title' => __( 'Jungle Safari', 'travel-booking-toolkit' ),
				),
				array(
					'img'   => TBT_FILE_URL . '/includes/images/activities-img7.jpg',
					'title' => __( 'Hiking', 'travel-booking-toolkit' ),
				),
				array(
					'img'   => TBT_FILE_URL . '/includes/images/activities-img8.jpg',
					'title' => __( 'Hot Air Ballon', 'travel-booking-toolkit' ),
				),
			)
		);
	}
}
new Travel_Booking_Toolkit_Dummy_Array();
