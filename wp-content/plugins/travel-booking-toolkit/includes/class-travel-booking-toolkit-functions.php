<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://wptravelengine.com
 * @since      1.0.0
 *
 * @package    Travel_Booking_Toolkit
 * @subpackage Travel_Booking_Toolkit/includes
 */
class Travel_Booking_Toolkit_Functions {
	/**
     * List out font awesome icon list
    */
    function travel_booking_toolkit_get_icon_list(){
        require plugin_dir_path( dirname( __FILE__ ) ) . 'includes/assets/fontawesome.php';
        echo '<input type="text" class="wptec-search-icon" placeholder="'.__('Search Icon','travel-booking-toolkit').'">';
        echo '<div class="travel_booking_toolkit-font-awesome-list"><ul class="travel_booking_toolkit-font-group">';
        foreach( $fontawesome as $font ){
            echo '<li><i class="' . esc_attr( $font ) . '"></i></li>';
        }
        echo '</ul></div>';
    }

    function travel_booking_toolkit_icon_list(){
        $fontawesome = array(
          '500px',
          'accessible-icon',
          'accusoft',
          'adn',
          'adversal',
          'affiliatetheme',
          'algolia',
          'amazon',
          'amazon-pay',
          'amilia',
          'android',
          'angellist',
          'angrycreative',
          'angular',
          'app-store',
          'app-store-ios',
          'apper',
          'apple',
          'apple-pay',
          'asymmetrik',
          'audible',
          'autoprefixer',
          'avianex',
          'aviato',
          'aws',
          'bandcamp',
          'behance',
          'behance-square',
          'bimobject',
          'bitbucket',
          'bitcoin',
          'bity',
          'black-tie',
          'blackberry',
          'blogger',
          'blogger-b',
          'bluetooth',
          'bluetooth-b',
          'btc',
          'buromobelexperte',
          'buysellads',
          'cc-amazon-pay',
          'cc-amex',
          'cc-apple-pay',
          'cc-diners-club',
          'cc-discover',
          'cc-jcb',
          'cc-mastercard',
          'cc-paypal',
          'cc-stripe',
          'cc-visa',
          'centercode',
          'chrome',
          'cloudscale',
          'cloudsmith',
          'cloudversify',
          'codepen',
          'codiepie',
          'connectdevelop',
          'contao',
          'cpanel',
          'creative-commons',
          'creative-commons-by',
          'creative-commons-nc',
          'creative-commons-nc-eu',
          'creative-commons-nc-jp',
          'creative-commons-nd',
          'creative-commons-pd',
          'creative-commons-pd-alt',
          'creative-commons-remix',
          'creative-commons-sa',
          'creative-commons-sampling',
          'creative-commons-sampling-plus',
          'creative-commons-share',
          'css3',
          'css3-alt',
          'cuttlefish',
          'd-and-d',
          'dashcube',
          'delicious',
          'deploydog',
          'deskpro',
          'deviantart',
          'digg',
          'digital-ocean',
          'discord',
          'discourse',
          'dochub',
          'docker',
          'draft2digital',
          'dribbble',
          'dribbble-square',
          'dropbox',
          'drupal',
          'dyalog',
          'earlybirds',
          'ebay',
          'edge',
          'elementor',
          'ello',
          'ember',
          'empire',
          'envira',
          'erlang',
          'ethereum',
          'etsy',
          'expeditedssl',
          'facebook',
          'facebook-f',
          'facebook-messenger',
          'facebook-square',
          'firefox',
          'first-order',
          'first-order-alt',
          'firstdraft',
          'flickr',
          'flipboard',
          'fly',
          'font-awesome',
          'font-awesome-alt',
          'font-awesome-flag',
          'fonticons',
          'fonticons-fi',
          'fort-awesome',
          'fort-awesome-alt',
          'forumbee',
          'foursquare',
          'free-code-camp',
          'freebsd',
          'fulcrum',
          'galactic-republic',
          'galactic-senate',
          'get-pocket',
          'gg',
          'gg-circle',
          'git',
          'git-square',
          'github',
          'github-alt',
          'github-square',
          'gitkraken',
          'gitlab',
          'gitter',
          'glide',
          'glide-g',
          'gofore',
          'goodreads',
          'goodreads-g',
          'google',
          'google-drive',
          'google-play',
          'google-plus',
          'google-plus-g',
          'google-plus-square',
          'google-wallet',
          'gratipay',
          'grav',
          'gripfire',
          'grunt',
          'gulp',
          'hacker-news',
          'hacker-news-square',
          'hackerrank',
          'hips',
          'hire-a-helper',
          'hooli',
          'hornbill',
          'hotjar',
          'houzz',
          'html5',
          'hubspot',
          'imdb',
          'instagram',
          'internet-explorer',
          'ioxhost',
          'itunes',
          'itunes-note',
          'java',
          'jedi-order',
          'jenkins',
          'joget',
          'joomla',
          'js',
          'js-square',
          'jsfiddle',
          'kaggle',
          'keybase',
          'keycdn',
          'kickstarter',
          'kickstarter-k',
          'korvue',
          'laravel',
          'lastfm',
          'lastfm-square',
          'leanpub',
          'less',
          'line',
          'linkedin',
          'linkedin-in',
          'linode',
          'linux',
          'lyft',
          'magento',
          'mailchimp',
          'mandalorian',
          'markdown',
          'mastodon',
          'maxcdn',
          'medapps',
          'medium',
          'medium-m',
          'medrt',
          'meetup',
          'megaport',
          'microsoft',
          'mix',
          'mixcloud',
          'mizuni',
          'modx',
          'monero',
          'napster',
          'neos',
          'nimblr',
          'nintendo-switch',
          'node',
          'node-js',
          'npm',
          'ns8',
          'nutritionix',
          'odnoklassniki',
          'odnoklassniki-square',
          'old-republic',
          'opencart',
          'openid',
          'opera',
          'optin-monster',
          'osi',
          'page4',
          'pagelines',
          'palfed',
          'patreon',
          'paypal',
          'periscope',
          'phabricator',
          'phoenix-framework',
          'phoenix-squadron',
          'php',
          'pied-piper',
          'pied-piper-alt',
          'pied-piper-hat',
          'pied-piper-pp',
          'pinterest',
          'pinterest-p',
          'pinterest-square',
          'playstation',
          'product-hunt',
          'pushed',
          'python',
          'qq',
          'quinscape',
          'quora',
          'r-project',
          'ravelry',
          'react',
          'readme',
          'rebel',
          'red-river',
          'reddit',
          'reddit-alien',
          'reddit-square',
          'rendact',
          'renren',
          'replyd',
          'researchgate',
          'resolving',
          'rev',
          'rocketchat',
          'rockrms',
          'safari',
          'sass',
          'schlix',
          'scribd',
          'searchengin',
          'sellcast',
          'sellsy',
          'servicestack',
          'shirtsinbulk',
          'shopware',
          'simplybuilt',
          'sistrix',
          'sith',
          'skyatlas',
          'skype',
          'slack',
          'slack-hash',
          'slideshare',
          'snapchat',
          'snapchat-ghost',
          'snapchat-square',
          'soundcloud',
          'speakap',
          'spotify',
          'squarespace',
          'stack-exchange',
          'stack-overflow',
          'staylinked',
          'steam',
          'steam-square',
          'steam-symbol',
          'sticker-mule',
          'strava',
          'stripe',
          'stripe-s',
          'studiovinari',
          'stumbleupon',
          'stumbleupon-circle',
          'superpowers',
          'supple',
          'teamspeak',
          'telegram',
          'telegram-plane',
          'tencent-weibo',
          'themeco',
          'themeisle',
          'trade-federation',
          'trello',
          'tripadvisor',
          'tumblr',
          'tumblr-square',
          'twitch',
          'twitter',
          'twitter-square',
          'typo3',
          'uber',
          'uikit',
          'uniregistry',
          'untappd',
          'usb',
          'ussunnah',
          'vaadin',
          'viacoin',
          'viadeo',
          'viadeo-square',
          'viber',
          'vimeo',
          'vimeo-square',
          'vimeo-v',
          'vine',
          'vk',
          'vnv',
          'vuejs',
          'weebly',
          'weibo',
          'weixin',
          'whatsapp',
          'whatsapp-square',
          'whmcs',
          'wikipedia-w',
          'windows',
          'wix',
          'wolf-pack-battalion',
          'wordpress',
          'wordpress-simple',
          'wpbeginner',
          'wpexplorer',
          'wpforms',
          'xbox',
          'xing',
          'xing-square',
          'y-combinator',
          'yahoo',
          'yandex',
          'yandex-international',
          'yelp',
          'yoast',
          'youtube',
          'youtube-square',
          'zhihu',
          'rss',
        );
        return $fontawesome;
    }
    /**
     * Get an attachment ID given a URL.
     *
     * @param string $url
     *
     * @return int Attachment ID on success, 0 on failure
     */
    function travel_booking_toolkit_get_attachment_id( $url ) {
        $attachment_id = 0;
        $dir = wp_upload_dir();
        if ( false !== strpos( $url, $dir['baseurl'] . '/' ) ) { // Is URL in uploads directory?
            $file = basename( $url );
            $query_args = array(
                'post_type'   => 'attachment',
                'post_status' => 'inherit',
                'fields'      => 'ids',
                'meta_query'  => array(
                    array(
                        'value'   => $file,
                        'compare' => 'LIKE',
                        'key'     => '_wp_attachment_metadata',
                    ),
                )
            );
            $query = new WP_Query( $query_args );
            if ( $query->have_posts() ) {
                foreach ( $query->posts as $post_id ) {
                    $meta = wp_get_attachment_metadata( $post_id );
                    $original_file       = basename( $meta['file'] );
                    $cropped_image_files = wp_list_pluck( $meta['sizes'], 'file' );
                    if ( $original_file === $file || in_array( $file, $cropped_image_files ) ) {
                        $attachment_id = $post_id;
                        break;
                    }
                }
            }
        }
        return $attachment_id;
    }

    /**
     * Retrieves the image field.
     *
     * @link https://pippinsplugins.com/retrieve-attachment-id-from-image-url/
     */
    function travel_booking_toolkit_get_image_field( $id, $name, $image, $label ){
        $output = '';
        $output .= '<div class="widget-upload">';
        $output .= '<label for="' . esc_attr( $id ) . '">' . esc_html( $label ) . '</label><br/>';
        $output .= '<input id="' . esc_attr( $id ) . '" class="travel_booking_toolkit-upload" type="hidden" name="' . esc_attr( $name ) . '" value="' . esc_attr( $image ) . '" placeholder="' . __('No file chosen', 'travel-booking-toolkit') . '" />' . "\n";
        if ( function_exists( 'wp_enqueue_media' ) ) {
            if ( $image == '' ) {
                $output .= '<input id="upload-' . esc_attr( $id ) . '" class="travel_booking_toolkit-upload-button button" type="button" value="' . __('Upload', 'travel-booking-toolkit') . '" />' . "\n";
            } else {
                $output .= '<input id="upload-' . esc_attr( $id ) . '" class="travel_booking_toolkit-upload-button button" type="button" value="' . __('Change', 'travel-booking-toolkit') . '" />' . "\n";
            }
        } else {
            $output .= '<p><i>' . __('Upgrade your version of WordPress for full media support.', 'travel-booking-toolkit') . '</i></p>';
        }

        $output .= '<div class="travel_booking_toolkit-screenshot" id="' . esc_attr( $id ) . '-image">';

        if ( $image != '' ) {
            $remove = '<a href="#" class="travel_booking_toolkit-remove-image">'.__('Remove Image','travel-booking-toolkit').'</a>';
            $attachment_id = $image;
            $image_array = wp_get_attachment_image_src( $attachment_id, 'full');
            $image = preg_match('/(^.*\.jpg|jpeg|png|gif|ico*)/i', $image_array[0]);
            if ( $image ) {
                $output .= '<img src="' . esc_url( $image_array[0] ) . '" alt="" />' . $remove;
            } else {
                // Standard generic output if it's not an image.
                $output .= '<small>' . __( 'Please upload valid image file.', 'travel-booking-toolkit' ) . '</small>';
            }
        }
        $output .= '</div></div>' . "\n";

        echo $output;
    }

	/**
	 * Get all the registered image sizes along with their dimensions
	 *
	 * @global array $_wp_additional_image_sizes
	 *
	 * @link http://core.trac.wordpress.org/ticket/18947 Reference ticket
	 * @return array $image_sizes The image sizes
	 */
	function travel_booking_toolkit_get_all_image_sizes() {
		global $_wp_additional_image_sizes;
		$default_image_sizes = array( 'thumbnail', 'medium', 'large', 'full' );

		foreach ( $default_image_sizes as $size ) {
			$image_sizes[$size]['width']	= intval( get_option( "{$size}_size_w") );
			$image_sizes[$size]['height'] = intval( get_option( "{$size}_size_h") );
			$image_sizes[$size]['crop']	= get_option( "{$size}_crop" ) ? get_option( "{$size}_crop" ) : false;
		}

		if ( isset( $_wp_additional_image_sizes ) && count( $_wp_additional_image_sizes ) )
			$image_sizes = array_merge( $image_sizes, $_wp_additional_image_sizes );

		return $image_sizes;
	}

    function wptravelengine_posted_on( $icon = false ) {

        echo '<span class="posted-on">';

        if( $icon ) echo '<i class="fa fa-calendar" aria-hidden="true"></i>';

        printf( '<a href="%1$s" rel="bookmark"><time class="entry-date published updated" datetime="%2$s">%3$s</time></a>', esc_url( get_permalink() ), esc_attr( get_the_date( 'c' ) ), esc_html( get_the_date() ) );

        echo '</span>';

    }

     /**
     * Function to list post categories in customizer options
    */
    function travel_booking_toolkit_get_categories( $select = true, $taxonomy = 'category', $slug = false ){

        /* Option list of all categories */
        $categories = array();
        if( $select ) $categories[''] = __( 'Choose Category', 'travel-booking-toolkit' );

        if( taxonomy_exists( $taxonomy ) ){
            $args = array(
                'hide_empty' => false,
                'taxonomy'   => $taxonomy
            );

            $catlists = get_terms( $args );

            foreach( $catlists as $category ){
                if( $slug ){
                    $categories[$category->slug] = $category->name;
                }else{
                    $categories[$category->term_id] = $category->name;
                }
            }
        }
        return $categories;
    }

    /**
     * Fuction to list Custom Post Type
    */
    function travel_booking_toolkit_get_posts( $post_type = 'post' ){

        $args = array(
            'posts_per_page'   => -1,
            'post_type'        => $post_type,
            'post_status'      => 'publish',
            //'suppress_filters' => true
        );
        $posts_array = get_posts( $args );

        // Initate an empty array
        $post_options = array();
        $post_options[''] = __( ' -- Choose -- ', 'travel-booking-toolkit' );
        if ( ! empty( $posts_array ) ) {
            foreach ( $posts_array as $posts ) {
                $post_options[ $posts->ID ] = $posts->post_title;
            }
        }
        return $post_options;
        wp_reset_postdata();
    }

    /**
     * Check if Wp Travel Engine Plugin is installed
    */
    function travel_booking_toolkit_is_wpte_activated(){
        return class_exists( 'Wp_Travel_Engine' ) ? true : false;
    }

    /**
     * Check if  WP Travel Engine - Group Discount Plugin is installed
     */
    function travel_booking_toolkit_is_wpte_gd_activated(){
        return class_exists( 'Wp_Travel_Engine_Group_Discount' ) ? true : false;
    }

    /**
     * Check if  WP Travel Engine - Trip Fixed Starting Dates Plugin is installed
     */
    function travel_booking_toolkit_is_wpte_fsd_activated(){
        return class_exists( 'WTE_Fixed_Starting_Dates' ) ? true : false;
    }

    /**
     * Check if WP Travel Engine - Trip Reviews Plugin is installed
    */
    function travel_booking_is_wpte_tr_activated(){
        return class_exists( 'Wte_Trip_Review_Init' ) ? true : false;
    }

    /**
     * Returns image url
    */
    function travel_booking_toolkit_get_image_url( $image_id ){
        if( ! is_numeric( $image_id ) ) return;

        return wp_get_attachment_image_url( $image_id, 'full' );
    }

    /**
     * Check if Wp Travel Engine - Trip Review Plugin is installed
    */
    function travel_booking_toolkit_is_wpte_tr_activated(){
        return class_exists( 'Wte_Trip_Review_Init' ) ? true : false;
    }


    /**
     * Returns posted on date
    */
    function travel_booking_toolkit_posted_on( $icon = false ) {

        echo '<span class="posted-on">';

        if( $icon ) echo '<i class="fa fa-calendar" aria-hidden="true"></i>';

        printf( '<a href="%1$s" rel="bookmark"><time class="entry-date published updated" datetime="%2$s">%3$s</time></a>', esc_url( get_permalink() ), esc_attr( get_the_date( 'c' ) ), esc_html( get_the_date() ) );

        echo '</span>';

    }

    /**
     * Get Trip Currency
    */
    function travel_booking_toolkit_get_trip_currency(){
        $currency = '';
        $setting = get_option( 'wp_travel_engine_settings', true );
        if( $this->travel_booking_toolkit_is_wpte_activated() ){
            $obj = new Wp_Travel_Engine_Functions();
            $travel_booking_toolkit_setting = get_option( 'wp_travel_engine_settings', true );
            $code = 'USD';
            if( isset( $travel_booking_toolkit_setting['currency_code'] ) && $travel_booking_toolkit_setting['currency_code']!= '' ){
                $code = $travel_booking_toolkit_setting['currency_code'];
            }

            $apiKey = isset($setting['currency_converter_api']) && $setting['currency_converter_api']!='' ? esc_attr($setting['currency_converter_api']) : '';

            if( class_exists( 'Wte_Trip_Currency_Converter_Init' ) && $apiKey != '' )
            {
                $converter_obj = new Wte_Trip_Currency_Converter_Init();
                $code = $converter_obj->wte_trip_currency_code_converter( $code );
            }
            $currency = $obj->wp_travel_engine_currencies_symbol( $code );
        }
        return $currency;
    }

    /**
    *
    */
    function travel_booking_toolkit_trip_symbol_options( $trip_id, $code='', $currency='' ){

        if ( function_exists( 'wte_get_trip' ) ) {
			$trip = wte_get_trip( $trip_id );
			if ( $trip->price || $trip->sale_price ) {
				echo '<span class="price-holder">';
				$trip->has_sale ? ( print( '<span class="old-price striked-price">' . wte_get_formated_price( $trip->price ) . '</span><span class="new-price actual-price">' . wte_get_formated_price( $trip->sale_price ) . '</span>' ) ) :print( '<span class="new-price actual-price">' . wte_get_formated_price( $trip->price ) . '</span>' );
				echo '</span>';
			}
			return;
		}

        $meta = get_post_meta( $trip_id, 'wp_travel_engine_setting', true );

        if( ( isset( $meta['trip_prev_price'] ) && $meta['trip_prev_price'] ) || ( isset( $meta['sale'] ) && $meta['sale'] && isset( $meta['trip_price'] ) && $meta['trip_price'] ) ) {

            echo '<span class="price-holder">';
                if( ( isset( $meta['trip_prev_price'] ) && $meta['trip_prev_price'] ) && ( isset( $meta['sale'] ) && $meta['sale'] ) && ( isset( $meta['trip_price'] ) && $meta['trip_price'] ) ) {
                    $cost = wp_travel_engine_get_sale_price( $trip_id );
                    $prev_cost = wp_travel_engine_get_prev_price( $trip_id );

                    echo '<span class="old-price striked-price">' . wp_travel_engine_get_formated_price_with_currency_code_symbol( $prev_cost ) . '</span>';
                    echo '<span class="new-price actual-price">'. wp_travel_engine_get_formated_price_with_currency_code_symbol( $cost ) .'</span>';
                } elseif( isset( $meta['trip_prev_price'] ) && $meta['trip_prev_price'] ) {
                    $prev_cost = wp_travel_engine_get_prev_price( $trip_id );

                    echo '<span class="new-price actual-price">'. wp_travel_engine_get_formated_price_with_currency_code_symbol( $prev_cost ) .'</span>';
                }
            echo '</span>';
        }
    }
}
new Travel_Booking_Toolkit_Functions;
