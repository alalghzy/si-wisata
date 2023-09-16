<?php
/**
 * Blog Section
 * 
 * @package Travel_Booking
 */

$ed_blog   = get_theme_mod( 'ed_blog_section', true );
$bl_title  = get_theme_mod( 'blog_section_title', __( 'Latest Articles', 'travel-booking' ) );
$sub_title = get_theme_mod( 'blog_section_subtitle', __( 'This is the best place to show your most sold and popular travel packages. You can modify this section from Appearance > Customize > Front Page Settings > Blog section.', 'travel-booking' ) );
$readmore  = get_theme_mod( 'blog_section_readmore', __( 'Read More', 'travel-booking' ) );
$blog      = get_option( 'page_for_posts' );
$label     = get_theme_mod( 'blog_view_all', __( 'View All Posts', 'travel-booking' ) );
    
$args = array(
    'post_type'           => 'post',
    'post_status'         => 'publish',
    'posts_per_page'      => 3,
    'ignore_sticky_posts' => true
);

$qry = new WP_Query( $args );

if( $bl_title || $sub_title || ( $ed_blog && $qry->have_posts() ) ){ ?>

<section id="blog-section" class="blog-section">
	<div class="container">
        
        <?php if( $bl_title || $sub_title ){ ?>
            <header class="section-header">	
                <?php 
                    if( $bl_title ) echo '<h2 class="section-title">' . esc_html( $bl_title ) . '</h2>';
                    if( $sub_title ) echo '<div class="section-content">' . wp_kses_post( wpautop( $sub_title ) ) . '</div>'; 
                ?>
    		</header>
        <?php } ?>
        
        <?php if( $ed_blog && $qry->have_posts() ){ ?>
            <div class="grid">

    			<?php while( $qry->have_posts() ){
                    $qry->the_post(); ?>
                    <div class="col" itemscope itemtype="https://schema.org/Blog">
        				<div class="img-holder">
                            <a href="<?php the_permalink(); ?>">
                            <?php 
                                $image_size = 'travel-booking-blog';

                                if( has_post_thumbnail() ){
                                    the_post_thumbnail( $image_size, 'itemprop=image' );
                                }else{ 
                                    travel_booking_fallback_image( $image_size );                                
                                }                            
                            ?>                        
                            </a>
                            <div class="category">
                            <?php travel_booking_categories(); ?>
                            </div>
                        </div>
        				<div class="text-holder">

        					<header class="entry-header">
        						<span class="posted-on"><time itemprop="datePublished"><i class="fa fa-clock-o"></i><a href="<?php the_permalink(); ?>"><?php echo esc_html( get_the_date() ); ?></a></time></span>
        						<h3 class="entry-title" itemprop="headline"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        					</header>

        					<div class="entry-content">
        						<?php the_excerpt(); ?>
        					</div>

                            <?php if( ! empty( $readmore ) ){ ?>
                                <div class="btn-holder">
                                    <a href="<?php the_permalink(); ?>" class="primary-btn readmore-btn"><?php echo esc_html( $readmore ); ?></a>
                                </div>    
                            <?php } ?>  

        				</div>
        			</div><!-- .col -->			
        			<?php 
                }
                wp_reset_postdata();
                ?>
    		</div>

            <?php if( ( $blog > 0 ) && ! empty( $label ) ){ ?>
                <div class="btn-holder">
        			<a href="<?php the_permalink( $blog ); ?>" class="primary-btn view-all-btn"><?php echo esc_html( travel_booking_get_blog_view_all_btn() ); ?></a>
        		</div>
            <?php } ?>
        
        <?php } ?>
	</div>
</section>
<?php 
}

           
