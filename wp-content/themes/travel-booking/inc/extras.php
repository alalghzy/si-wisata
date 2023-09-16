<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Travel_Booking
 */

if ( ! function_exists( 'travel_booking_posted_on' ) ) :
	/**
	 * Posted On
	 */
	function travel_booking_posted_on() {
		$post_updated_date = get_theme_mod( 'ed_post_update_date', true );
		$on                = '';

		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			if ( $post_updated_date ) {
				$time_string = '<time class="entry-date published updated" datetime="%3$s" itemprop="dateModified">%4$s</time><time class="updated" datetime="%1$s" itemprop="datePublished">%2$s</time>';
				$on          = __( 'Updated on ', 'travel-booking' );
			} else {
				$time_string = '<time class="entry-date published" datetime="%1$s" itemprop="datePublished">%2$s</time><time class="updated" datetime="%3$s" itemprop="dateModified">%4$s</time>';
			}
		} else {
			$time_string = '<time class="entry-date published updated" datetime="%1$s" itemprop="datePublished">%2$s</time><time class="updated" datetime="%3$s" itemprop="dateModified">%4$s</time>';
		}

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf( '%1$s %2$s', '<i class="fa fa-clock-o"></i> ' . esc_html( $on ) . '', '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>' );

		echo '<span class="posted-on">' . $posted_on . '</span>';
	}
endif;

if ( ! function_exists( 'travel_booking_posted_by' ) ) :
	/**
	 * Posted By
	 */
	function travel_booking_posted_by() {
		echo '<span class="byline" itemprop="author" itemscope itemtype="https://schema.org/Person"><i class="fa fa-user"></i><a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" itemprop="url"><span itemprop="name">' . esc_html( get_the_author() ) . '</span></a></span>';
	}
endif;

if ( ! function_exists( 'travel_booking_categories' ) ) :
	/**
	 * Blog Categories
	 */
	function travel_booking_categories() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			$categories_list = get_the_category_list( ' ' );
			if ( $categories_list ) {
				echo wp_kses_post( $categories_list );
			}
		}
	}
endif;

if ( ! function_exists( 'travel_booking_tags' ) ) :
	/**
	 * Blog Categories
	 */
	function travel_booking_tags() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			$tags_list = get_the_tag_list( '', ' ' );
			if ( $tags_list ) {
				echo '<div class="tags">' . $tags_list . '</div>'; // WPCS: XSS OK.
			}
		}
	}
endif;

if ( ! function_exists( 'travel_booking_comment_count' ) ) :
	/**
	 * Comments counts
	 */
	function travel_booking_comment_count() {
		if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments"><i class="fa fa-comment-o"></i>';
			comments_popup_link(
				sprintf(
					wp_kses(
					/* translators: %s: post title */
						__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'travel-booking' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				),
				__( '1', 'travel-booking' ),
				'%'
			);
			echo '</span>';
		}
	}
endif;

if ( ! function_exists( 'travel_booking_sidebar_layout' ) ) :
	/**
	 * Return sidebar layouts for pages
	 */
	function travel_booking_sidebar_layout() {
		global $post;

		$return = false;

		// Default sidebar layout
		$default_layout = get_theme_mod( 'default_sidebar_layout', 'right-sidebar' );

		if ( is_singular( 'trip' ) ) {

			$wpte_option_setting = get_option( 'wp_travel_engine_settings', array() );
			$hide_sidebar        = false;

			if ( ! empty( $wpte_option_setting ) && isset( $wpte_option_setting['booking'] ) ) {
				$hide_sidebar = true;
			}

			if ( is_active_sidebar( 'wte-sidebar-id' ) || false == $hide_sidebar ) {
				if ( $default_layout == 'left-sidebar' ) {
					$return = 'leftsidebar';
				} elseif ( $default_layout == 'right-sidebar' ) {
					$return = 'rightsidebar';
				} else {
					$return = 'fullwidth';
				}
			} else {
				$return = 'fullwidth';
			}
		} elseif ( is_singular( 'product' ) ) {

			if ( is_active_sidebar( 'shop-sidebar' ) ) {
				if ( $default_layout == 'left-sidebar' ) {
					$return = 'leftsidebar';
				} elseif ( $default_layout == 'right-sidebar' ) {
					$return = 'rightsidebar';
				} else {
					$return = 'fullwidth';
				}
			} else {
				$return = 'fullwidth';
			}
		} elseif ( is_page() ) {

			$page_layout    = get_theme_mod( 'page_sidebar_layout', 'right-sidebar' );
			$sidebar_layout = get_post_meta( $post->ID, '_tb_sidebar_layout', true );
			$sidebar_layout = ! empty( $sidebar_layout ) ? $sidebar_layout : 'default-sidebar';

			if ( is_active_sidebar( 'sidebar' ) ) {
				if ( $sidebar_layout == 'no-sidebar' ) {
					$return = 'fullwidth';
				} elseif ( ( $sidebar_layout == 'default-sidebar' && $page_layout == 'right-sidebar' ) || ( $sidebar_layout == 'right-sidebar' ) ) {
					$return = 'rightsidebar';
				} elseif ( ( $sidebar_layout == 'default-sidebar' && $page_layout == 'left-sidebar' ) || ( $sidebar_layout == 'left-sidebar' ) ) {
					$return = 'leftsidebar';
				} elseif ( $sidebar_layout == 'default-sidebar' && $page_layout == 'no-sidebar' ) {
					$return = 'fullwidth';
				}
			} else {
				$return = 'fullwidth';
			}
		} elseif ( is_single() ) {

			$post_layout    = get_theme_mod( 'post_sidebar_layout', 'right-sidebar' );
			$sidebar_layout = get_post_meta( $post->ID, '_tb_sidebar_layout', true );
			$sidebar_layout = ! empty( $sidebar_layout ) ? $sidebar_layout : 'default-sidebar';

			if ( is_active_sidebar( 'sidebar' ) ) {
				if ( $sidebar_layout == 'no-sidebar' ) {
					$return = 'fullwidth';
				} elseif ( ( $sidebar_layout == 'default-sidebar' && $post_layout == 'right-sidebar' ) || ( $sidebar_layout == 'right-sidebar' ) ) {
					$return = 'rightsidebar';
				} elseif ( ( $sidebar_layout == 'default-sidebar' && $post_layout == 'left-sidebar' ) || ( $sidebar_layout == 'left-sidebar' ) ) {
					$return = 'leftsidebar';
				} elseif ( $sidebar_layout == 'default-sidebar' && $post_layout == 'no-sidebar' ) {
					$return = 'fullwidth';
				}
			} else {
				$return = 'fullwidth';
			}
		} elseif ( ! is_active_sidebar( 'sidebar' ) && ! is_singular( 'trip' ) ) {
			$return = 'fullwidth';
		} elseif ( is_post_type_archive( 'trip' ) ) {
			$return = 'fullwidth';
		} elseif ( travel_booking_is_woocommerce_activated() && is_post_type_archive( 'product' ) ) {
			if ( is_active_sidebar( 'shop-sidebar' ) ) {
				if ( $default_layout == 'right-sidebar' ) {
					$return = 'rightsidebar';
				} elseif ( $default_layout == 'left-sidebar' ) {
					$return = 'leftsidebar';
				} else {
					$return = 'fullwidth';
				}
			} else {
				$return = 'fullwidth';
			}
		} elseif ( is_404() ) {
			$return = '';
		} else {
			if ( is_active_sidebar( 'sidebar' ) ) {
				if ( $default_layout == 'right-sidebar' ) {
					$return = 'rightsidebar';
				} elseif ( $default_layout == 'left-sidebar' ) {
					$return = 'leftsidebar';
				} else {
					$return = 'fullwidth';
				}
			} else {
				$return = 'fullwidth';
			}
		}

		return $return;
	}
endif;

if ( ! function_exists( 'travel_booking_comment_list' ) ) :
	/**
	 * Callback function for Comment List
	 *
	 * @link https://codex.wordpress.org/Function_Reference/wp_list_comments
	 */
	function travel_booking_comment_list( $comment, $args, $depth ) {
		if ( 'div' === $args['style'] ) {
			$tag       = 'div';
			$add_below = 'comment';
		} else {
			$tag       = 'li';
			$add_below = 'div-comment';
		}
		?>
	<<?php echo $tag; ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?> id="comment-<?php comment_ID(); ?>">

		<?php if ( 'div' != $args['style'] ) { ?>
		<div id="div-comment-<?php comment_ID(); ?>" class="comment-body" itemscope itemtype="https://schema.org/UserComments">
	<?php } ?>

			<div class="comment-meta">
				<div class="comment-author vcard">
					<?php
					if ( $args['avatar_size'] != 0 ) {
						echo get_avatar( $comment, $args['avatar_size'] );}
					?>
				</div>
			</div><!-- .comment-meta -->

			<div class="text-holder">
				<div class="top">

					<?php if ( $comment->comment_approved == '0' ) { ?>
						<em class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'travel-booking' ); ?></em>
					<?php } ?>

					<div class="left">
						<b class="fn" itemprop="creator" itemscope itemtype="https://schema.org/Person"><?php comment_author(); ?></b>
						<span class="says"><?php __( 'Says:', 'travel-booking' ); ?></span>
						<div class="comment-metadata">
							<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
								<?php
								echo '<i class="fa fa-clock-o"></i>';
								/* translators: 1: date, 2: time */
								printf( __( '<time itemprop="commentTime" datetime="%3$s">%1$s at %2$s</time>', 'travel-booking' ), get_comment_date(), get_comment_time(), esc_attr( get_gmt_from_date( get_comment_date() . get_comment_time(), 'Y-m-d H:i:s' ) ) );
								?>
							</a>
						</div>
							<?php edit_comment_link( __( '(Edit)', 'travel-booking' ), '  ', '' ); ?>
					</div><!-- .left -->

					<div class="reply">
					<?php
					comment_reply_link(
						array_merge(
							$args,
							array(
								'add_below' => $add_below,
								'depth'     => $depth,
								'max_depth' => $args['max_depth'],
							)
						)
					);
					?>
										</div>
				</div>
				<div class="comment-content" itemprop="commentText"><?php comment_text(); ?></div>

			</div><!-- .text-holder -->

		<?php if ( 'div' != $args['style'] ) { ?>
		</div>
			<?php
		}
	}
endif;

if ( ! function_exists( 'travel_booking_get_trip_currency' ) ) :
	/**
	 * Get currency for WP Travel Engine Trip
	 */
	function travel_booking_get_trip_currency() {
		$currency = '';
		if ( travel_booking_is_wpte_activated() ) {
			$wpte_setting = get_option( 'wp_travel_engine_settings', true );
			$code         = 'USD';
			if ( isset( $wpte_setting['currency_code'] ) && $wpte_setting['currency_code'] != '' ) {
				$code = $wpte_setting['currency_code'];
			}
			$obj      = new Wp_Travel_Engine_Functions();
			$currency = $obj->wp_travel_engine_currencies_symbol( $code );
		}
		return $currency;
	}
endif;

if ( ! function_exists( 'travel_booking_get_template_part' ) ) :
	/**
	 * Get template from plus, companion or theme.
	 *
	 * @param string $template Name of the section.
	 */
	function travel_booking_get_template_part( $template ) {

		if ( locate_template( $template . '.php' ) ) {
			get_template_part( $template );
		} else {
			if ( defined( 'TBT_PLUGIN_DIR' ) ) {
				if ( file_exists( TBT_PLUGIN_DIR . 'public/sections/' . $template . '.php' ) ) {
					require_once TBT_PLUGIN_DIR . 'public/sections/' . $template . '.php';
				}
			}
		}
	}
endif;

if ( ! function_exists( 'travel_booking_primary_menu_fallback' ) ) :
	/**
	 * Fallback for primary menu
	 */
	function travel_booking_primary_menu_fallback() {
		if ( current_user_can( 'manage_options' ) ) {
			echo '<ul id="primary-menu" class="menu">';
			echo '<li><a href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">' . esc_html__( 'Click here to add a menu', 'travel-booking' ) . '</a></li>';
			echo '</ul>';
		}
	}
endif;

if ( ! function_exists( 'travel_booking_get_homepage_section' ) ) :
	/**
	 * Return homepage sections
	 */
	function travel_booking_get_homepage_section() {
		$sections       = array();
		$ed_popular     = get_theme_mod( 'ed_popular_section', true );
		$ed_feature     = get_theme_mod( 'ed_feature_section', true );
		$ed_deal        = get_theme_mod( 'ed_deal_section', true );
		$ed_destination = get_theme_mod( 'ed_destination_section', true );
		$ed_activities  = get_theme_mod( 'ed_activities_section', true );
		$ed_blog        = get_theme_mod( 'ed_blog_section', true );
		$ed_search_bar  = get_theme_mod( 'ed_search_bar', true );

		if ( $ed_search_bar && travel_booking_is_wte_advanced_search_active() ) {
			array_push( $sections, 'sections/search' );
		}

		if ( is_active_sidebar( 'about' ) ) {
			array_push( $sections, 'sections/about' );
		}
		if ( $ed_popular ) {
			array_push( $sections, 'popular' );
		}
		if ( is_active_sidebar( 'cta-one' ) ) {
			array_push( $sections, 'sections/cta-one' );
		}
		if ( $ed_feature ) {
			array_push( $sections, 'featured-trip' );
		}
		if ( $ed_deal ) {
			array_push( $sections, 'deals' );
		}
		if ( $ed_destination ) {
			array_push( $sections, 'destination' );
		}
		if ( is_active_sidebar( 'cta-two' ) ) {
			array_push( $sections, 'sections/cta-two' );
		}
		if ( $ed_activities ) {
			array_push( $sections, 'activities' );
		}
		if ( $ed_blog ) {
			array_push( $sections, 'sections/blog' );
		}

		return $sections;
	}
endif;

if ( ! function_exists( 'travel_booking_get_header_search' ) ) :
	/**
	 * Display search button in header
	 */
	function travel_booking_get_header_search() {
		?>
		<div class="form-section-holder">
			<button class="form-section" data-toggle-target=".search-modal" data-toggle-body-class="showing-search-modal" aria-expanded="false" data-set-focus=".search-modal input">
				<span id="btn-search">
					<svg x="0px" y="0px">
						<path class="st0" d="M12.7,11.1L9.9,8.3c0,0,0,0,0,0c0.6-0.8,0.9-1.9,0.9-2.9c0-3-2.4-5.3-5.3-5.3C2.4,0,0,2.4,0,5.3
						c0,3,2.4,5.3,5.3,5.3c1.1,0,2.1-0.3,2.9-0.9c0,0,0,0,0,0l2.8,2.8c0.4,0.4,1.1,0.4,1.5,0C13.1,12.3,13.1,11.6,12.7,11.1z M5.3,8.8
						c-1.9,0-3.5-1.6-3.5-3.5c0-1.9,1.6-3.5,3.5-3.5c1.9,0,3.5,1.6,3.5,3.5C8.8,7.3,7.3,8.8,5.3,8.8z"/>
					</svg>
				</span>
			</button>
			<div class="form-holder header-searh-wrap search-modal cover-modal" data-modal-target-string=".search-modal">
					<?php get_search_form(); ?>
				<button class="btn-form-close" data-toggle-target=".search-modal" data-toggle-body-class="showing-search-modal" aria-expanded="false" data-set-focus=".search-modal"></button>
			</div>
		</div>
		<?php
	}
endif;

if ( ! function_exists( 'travel_booking_get_page_id_by_template' ) ) :
	/**
	 * Returns Page ID by Page Template
	 */
	function travel_booking_get_page_id_by_template( $template_name ) {
		$args = array(
			'meta_key'   => '_wp_page_template',
			'meta_value' => $template_name,
		);
		return get_pages( $args );
	}
endif;

/**
 * Check if Wp Travel Engine Companion Plugin is activated
 */
function tb_is_tbt_activated() {
	return class_exists( 'Travel_Booking_Toolkit' ) ? true : false;
}

/**
 * Check if Wp Travel Engine Plugin is installed
 */
function travel_booking_is_wpte_activated() {
	return class_exists( 'Wp_Travel_Engine' ) ? true : false;
}

/**
 * Query WooCommerce activation
 */
function travel_booking_is_woocommerce_activated() {
	return class_exists( 'woocommerce' ) ? true : false;
}

/**
 * Check if WTE Advance Search is active
 */
function travel_booking_is_wte_advanced_search_active() {
	return class_exists( 'Wte_Advanced_Search' ) ? true : false;
}

/**
 * Fuction to list Custom Post Type
 */
function travel_booking_get_posts( $post_type = 'post' ) {

	$args = array(
		'posts_per_page' => -1,
		'post_type'      => $post_type,
		'post_status'    => 'publish',
		// 'suppress_filters' => true
	);
	$posts_array = get_posts( $args );

	// Initate an empty array
	$post_options     = array();
	$post_options[''] = __( ' -- Choose -- ', 'travel-booking' );
	if ( ! empty( $posts_array ) ) {
		foreach ( $posts_array as $posts ) {
			$post_options[ $posts->ID ] = esc_html( strip_tags( $posts->post_title ) );
		}
	}
	return $post_options;
	wp_reset_postdata();
}

if ( ! function_exists( 'travel_booking_get_svg' ) ) :
	/**
	 * Return SVG markup.
	 *
	 * @param array $args {
	 *     Parameters needed to display an SVG.
	 *
	 *     @type string $icon  Required SVG icon filename.
	 *     @type string $title Optional SVG title.
	 *     @type string $desc  Optional SVG description.
	 * }
	 * @return string SVG markup.
	 */
	function travel_booking_get_svg( $args = array() ) {
		// Make sure $args are an array.
		if ( empty( $args ) ) {
			return __( 'Please define default parameters in the form of an array.', 'travel-booking' );
		}

		// Define an icon.
		if ( false === array_key_exists( 'icon', $args ) ) {
			return __( 'Please define an SVG icon filename.', 'travel-booking' );
		}

		// Set defaults.
		$defaults = array(
			'icon'     => '',
			'title'    => '',
			'desc'     => '',
			'fallback' => false,
		);

		// Parse args.
		$args = wp_parse_args( $args, $defaults );

		// Set aria hidden.
		$aria_hidden = ' aria-hidden="true"';

		// Set ARIA.
		$aria_labelledby = '';

		/*
		 * Restaurant and Cafe Pro doesn't use the SVG title or description attributes; non-decorative icons are described with .screen-reader-text.
		 *
		 * However, child themes can use the title and description to add information to non-decorative SVG icons to improve accessibility.
		 *
		 * Example 1 with title: <?php echo travel_booking_get_svg( array( 'icon' => 'arrow-right', 'title' => __( 'This is the title', 'textdomain' ) ) ); ?>
		 *
		 * Example 2 with title and description: <?php echo travel_booking_get_svg( array( 'icon' => 'arrow-right', 'title' => __( 'This is the title', 'textdomain' ), 'desc' => __( 'This is the description', 'textdomain' ) ) ); ?>
		 *
		 * See https://www.paciellogroup.com/blog/2013/12/using-aria-enhance-svg-accessibility/.
		 */
		if ( $args['title'] ) {
			$aria_hidden     = '';
			$unique_id       = uniqid();
			$aria_labelledby = ' aria-labelledby="title-' . $unique_id . '"';

			if ( $args['desc'] ) {
				$aria_labelledby = ' aria-labelledby="title-' . $unique_id . ' desc-' . $unique_id . '"';
			}
		}

		// Begin SVG markup.
		$svg = '<svg class="icon icon-' . esc_attr( $args['icon'] ) . '"' . $aria_hidden . $aria_labelledby . ' role="img">';

		// Display the title.
		if ( $args['title'] ) {
			$svg .= '<title id="title-' . $unique_id . '">' . esc_html( $args['title'] ) . '</title>';

			// Display the desc only if the title is already set.
			if ( $args['desc'] ) {
				$svg .= '<desc id="desc-' . $unique_id . '">' . esc_html( $args['desc'] ) . '</desc>';
			}
		}

		/*
		 * Display the icon.
		 *
		 * The whitespace around `<use>` is intentional - it is a work around to a keyboard navigation bug in Safari 10.
		 *
		 * See https://core.trac.wordpress.org/ticket/38387.
		 */
		$svg .= ' <use href="#icon-' . esc_html( $args['icon'] ) . '" xlink:href="#icon-' . esc_html( $args['icon'] ) . '"></use> ';

		// Add some markup to use as a fallback for browsers that do not support SVGs.
		if ( $args['fallback'] ) {
			$svg .= '<span class="svg-fallback icon-' . esc_attr( $args['icon'] ) . '"></span>';
		}

		$svg .= '</svg>';

		return $svg;
	}
endif;

if ( ! function_exists( 'travel_booking_escape_text_tags' ) ) :

	/**
	 * Remove new line tags from string
	 *
	 * @param $text
	 *
	 * @return string
	 */
	function travel_booking_escape_text_tags( $text ) {

		return (string) str_replace( array( "\r", "\n" ), '', strip_tags( $text ) );
	}
endif;

if ( ! function_exists( 'travel_booking_fallback_image' ) ) :

	/**
	 * Returns fallback image
	 */
	function travel_booking_fallback_image( $image_size ) {

		$placeholder_src = get_template_directory_uri() . '/images/' . $image_size . '.jpg';
		?>
			<img src="<?php echo esc_url( $placeholder_src ); ?>" alt="<?php the_title_attribute(); ?>" itemprop="image"/>
		<?php
	}
endif;

if ( ! function_exists( 'travel_booking_fonts_url' ) ) :
	/**
	 * Register custom fonts.
	 */
	function travel_booking_fonts_url() {
		$fonts_url = '';

		/*
		* translators: If there are characters in your language that are not supported
		* by Lato, translate this to 'off'. Do not translate into your own language.
		*/
		$lato = _x( 'on', 'Lato font: on or off', 'travel-booking' );

		$font_families = array();

		if ( 'off' !== $lato ) {
			$font_families[] = 'Lato:100,100i,300,300i,400,400i,700,700i,900,900i';
		}

		$query_args = array(
			'family'  => urlencode( implode( '|', $font_families ) ),
			'display' => urlencode( 'fallback' ),
		);

		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );

		return esc_url( $fonts_url );
	}
endif;


if ( ! function_exists( 'travel_booking_load_preload_local_fonts' ) ) :
	/**
	 * Get the file preloads.
	 *
	 * @param string $url    The URL of the remote webfont.
	 * @param string $format The font-format. If you need to support IE, change this to "woff".
	 */
	function travel_booking_load_preload_local_fonts( $url, $format = 'woff2' ) {

		// Check if cached font files data preset present or not. Basically avoiding 'perfect_portfolio_WebFont_Loader' class rendering.
		$local_font_files = get_site_option( 'travel_booking_local_font_files', false );

		if ( is_array( $local_font_files ) && ! empty( $local_font_files ) ) {
			$font_format = apply_filters( 'travel_booking_local_google_fonts_format', $format );
			foreach ( $local_font_files as $key => $local_font ) {
				if ( $local_font ) {
					echo '<link rel="preload" href="' . esc_url( $local_font ) . '" as="font" type="font/' . esc_attr( $font_format ) . '" crossorigin>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
			}
			return;
		}

		// Now preload font data after processing it, as we didn't get stored data.
		$font = travel_booking_webfont_loader_instance( $url );
		$font->set_font_format( $format );
		$font->preload_local_fonts();
	}
endif;

if ( ! function_exists( 'travel_booking_flush_local_google_fonts' ) ) {
	/**
	 * Ajax Callback for flushing the local font
	 */
	function travel_booking_flush_local_google_fonts() {
		$WebFontLoader = new Travel_Booking_WebFont_Loader();
		// deleting the fonts folder using ajax
		$WebFontLoader->delete_fonts_folder();
		die();
	}
}
add_action( 'wp_ajax_flush_local_google_fonts', 'travel_booking_flush_local_google_fonts' );
add_action( 'wp_ajax_nopriv_flush_local_google_fonts', 'travel_booking_flush_local_google_fonts' );


if ( ! function_exists( 'travel_booking_content_grid_template' ) ) {
	/**
	 * Filters the content Grid template part.
	 *
	 * @param string $template Template Path.
	 * @param string $template_name Template Name.
	 *
	 * @since 1.2.9
	 */
	function travel_booking_content_grid_template( $template, $template_name ) {
		if( ! travel_booking_is_wpte_activated() ) return;
		static $is_new_layout_enabled = null;

		if ( 'content-grid.php' === $template_name ) {
			if ( is_null( $is_new_layout_enabled ) ) {
				$wptravelengine_settings = get_option( 'wp_travel_engine_settings', array() );
				$is_new_layout_enabled   = isset( $wptravelengine_settings['display_new_trip_listing'] ) && 'yes' === $wptravelengine_settings['display_new_trip_listing'];
			}

			if ( $is_new_layout_enabled ) {
				return WP_TRAVEL_ENGINE_BASE_PATH . '/includes/templates/' . $template_name;
			}
		}

		return $template;
	}

	add_filter( 'wte_get_template', 'travel_booking_content_grid_template', 10, 2 );
}
