<?php
/**
 * Travel_Booking_Toolkit Customizer Partials
 *
 * @package Travel_Booking_Toolkit
 */

if( ! function_exists( 'tbt_travel_muni_pro_demo_content' ) ) :
/**
 * Travel Muni Pro Demo Content
*/
function tbt_travel_muni_pro_demo_content( $section ){
	switch ( $section ) {
		case 'about':
			?>
			<section id="about_section" class="three-cols">
				<div class="container">
					<div class="three-cols-wrap">
						<section id="text-4" class="widget widget_text">
							<h2 class="widget-title" itemprop="name">Handpicked Treks</h2>
							<div class="textwidget">
								<p>Voluptatibus earum neque provident feugiat consequatur, nisi numquam aptent aspernatur proident, imperdiet duis veritatis. Accusamus id, quisquam neque repellat eu pede aspernatur, euismod quia vulputate per aliquam dolorum, minim suscipit! Voluptate cura</p>
							</div>
						</section>
						<section id="text-5" class="widget widget_text">
							<h2 class="widget-title" itemprop="name">Best Price Guaranteed</h2>
							<div class="textwidget">
								<p>Voluptatibus earum neque provident feugiat consequatur, nisi numquam aptent aspernatur proident, imperdiet duis veritatis. Accusamus id, quisquam neque repellat eu pede aspernatur, euismod quia vulputate per aliquam dolorum, minim suscipit! Voluptate cura</p>
							</div>
						</section>
						<section id="text-6" class="widget widget_text">
							<h2 class="widget-title" itemprop="name">No Hidden Charges</h2>
							<div class="textwidget">
								<p>Voluptatibus earum neque provident feugiat consequatur, nisi numquam aptent aspernatur proident, imperdiet duis veritatis. Accusamus id, quisquam neque repellat eu pede aspernatur, euismod quia vulputate per aliquam dolorum, minim suscipit! Voluptate cura</p>
							</div>
						</section>
						<section id="text-7" class="widget widget_text">
							<h2 class="widget-title" itemprop="name">Best Price Guaranteed</h2>
							<div class="textwidget">
								<p>Voluptatibus earum neque provident feugiat consequatur, nisi numquam aptent aspernatur proident, imperdiet duis veritatis. Accusamus id, quisquam neque repellat eu pede aspernatur, euismod quia vulputate per aliquam dolorum, minim suscipit! Voluptate cura</p>
							</div>
						</section>
						<section id="text-8" class="widget widget_text">
							<h2 class="widget-title" itemprop="name">Local Experts</h2>
							<div class="textwidget">
								<p>Voluptatibus earum neque provident feugiat consequatur, nisi numquam aptent aspernatur proident, imperdiet duis veritatis. Accusamus id, quisquam neque repellat eu pede aspernatur, euismod quia vulputate per aliquam dolorum, minim suscipit! Voluptate cura</p>
							</div>
						</section>
						<section id="text-9" class="widget widget_text">
							<h2 class="widget-title" itemprop="name">No Hidden Charges</h2>
							<div class="textwidget">
								<p>Voluptatibus earum neque provident feugiat consequatur, nisi numquam aptent aspernatur proident, imperdiet duis veritatis. Accusamus id, quisquam neque repellat eu pede aspernatur, euismod quia vulputate per aliquam dolorum, minim suscipit! Voluptate cura</p>
							</div>
						</section>
					</div>
				</div>
			</section><!-- .about-section -->
			<?php
		break;

		case 'destination':
		?>
			<div class="container-full">
					<div class="destination-wrap">
						<div class="large-desti-item">
							<div class="desti-single-wrap">
								<div class="desti-single-img-wrap">
									<a href="#"><img src="<?php echo esc_url( TBT_FILE_URL . '/images/demo/gosaikunda-lake.jpg' ); ?>" alt="Gosaikunda Lake"></a>
								</div>
								<div class="desti-single-desc">
									<h3 class="desti-single-title"> <a href="#"><strong>Gosaikunda Lake</strong><span>(13 tours)</span></a></h3>
								</div>
							</div>
						</div><!-- .large-desti-item -->
						<div class="desti-small-wrap">
							<div class="desti-single-wrap">
								<div class="desti-single-img-wrap">
								   <a href="#"><img src="<?php echo esc_url( TBT_FILE_URL . '/images/demo/buddha-statue.jpg' ); ?>" alt="Bhutan"></a>
								</div>
								<div class="desti-single-desc">
									<h3 class="desti-single-title"> <a href="#"><strong>Bhutan</strong><span>(13 tours)</span></a></h3>
								</div>
							</div>
							<div class="desti-single-wrap">
									<div class="desti-single-img-wrap">
									   <a href="#"><img src="<?php echo esc_url( TBT_FILE_URL . '/images/demo/mountain-climbing.jpg' ); ?>" alt="Everest Region"></a>
								   </div>
								   <div class="desti-single-desc">
									<h3 class="desti-single-title"> <a href="#"><strong>Everest Region</strong><span>(13 tours)</span></a></h3>
								</div>
							</div>
							<div class="desti-single-wrap">
									<div class="desti-single-img-wrap">
									   <a href="#"><img src="<?php echo esc_url( TBT_FILE_URL . '/images/demo/potters-carrying-goods.jpg' ); ?>" alt="Langtang Region"></a>
								   </div>
								   <div class="desti-single-desc">
									<h3 class="desti-single-title"><a href="#"><strong>Langtang Region</strong><span>(13 tours)</span></a></h3>
								</div>
							</div>
							<div class="desti-single-wrap">
									<div class="desti-single-img-wrap">
									   <a href="#"><img src="<?php echo esc_url( TBT_FILE_URL . '/images/demo/tibet-walk.jpg' ); ?>" alt="Tibet"></a>
								   </div>
								   <div class="desti-single-desc">
									<h3 class="desti-single-title"> <a href="#"><strong>Tibet</strong><span>(13 tours)</span></a></h3>
								</div>
							</div>
							<div class="desti-single-wrap">
									<div class="desti-single-img-wrap">
									   <a href="#"><img src="<?php echo esc_url( TBT_FILE_URL . '/images/demo/mountain-climbing.jpg' ); ?>" alt="Gosaikunda Lake"></a>
								   </div>
								   <div class="desti-single-desc">
									<h3 class="desti-single-title"> <a href="#"><strong>Annapurna Region</strong><span>(13 tours)</span></a></h3>
								</div>
							</div>
							<div class="desti-single-wrap">
								<div class="last-desti-single-item">
									<h4>28+ Top Destinations</h4>
									<div class="btn-book"><a class="btn-primary" href="#">View All</a></div>
								</div>
							</div>
						</div><!-- .desti-small-wrap -->
					</div>
				</div><!-- .container-full -->
		<?php
		break;

		case 'testimonials':
		?>
			<div class="clients-testimonial-wrap">
				<div class="clients-testimon-singl">
					<div class="clients-testi-inner-wrap">
					<h3 class="clients-testi-title">Best Experience Ever!!!</h3>
					<div class="clients-testi-trip">
							Reviewed Tour:<h6>Mardi Trip<span>(7 days ago)</span></h6>
						</div>
						<div class="clients-testi-desc">
							<p>We only had 6 days in Nepal and decided to go for the 3 day trek as recommended by Chhatra which was just great, really easy to organise in advance via email and online booking. We all loved it from the start to the end.</p>
						</div>
						<div class="client-intro-sc">
							<div class="client-dp">
								<img src="<?php echo esc_url( TBT_FILE_URL . '/images/demo/clients-testi-1.png' ); ?>" alt="">
							</div>
							<div class="client-intro-rght">
								<div class="ratingndrev">
									<div class="rating-layout-1 smaller-ver">
										<div class="circle-rating">
											<span class="circle-wrap-outer">
												<span class="single-circle-outer"></span>
												<span class="single-circle-outer"></span>
												<span class="single-circle-outer"></span>
												<span class="single-circle-outer"></span>
												<span class="single-circle-outer"></span>
											</span>
											<div class="circle-inner-rating">
												<span class="circle-wrap" style="width:95%">
													<span class="single-circle"></span>
													<span class="single-circle"></span>
													<span class="single-circle"></span>
													<span class="single-circle"></span>
													<span class="single-circle"></span>
												</span>
											</div>
										</div>
									</div>
								</div>

								<p class="clients-testi-locat"><span>Sagar Shrestha,</span> from Nepal</p>
							</div>
						</div>

						<div class="clients-testi-single-gall">
							<figure><img src="<?php echo esc_url( TBT_FILE_URL . '/images/demo/clients-testi-single-gall-1.png' ); ?>" alt="image"></figure>
							<figure><img src="<?php echo esc_url( TBT_FILE_URL . '/images/demo/clients-testi-single-gall-2.png' ); ?>" alt="image"></figure>
							<figure><img src="<?php echo esc_url( TBT_FILE_URL . '/images/demo/clients-testi-single-gall-3.png' ); ?>" alt="image"></figure>
						</div>
						<div class="clients-testi-single-dateexp">
							<h6>Date of Exerience:<span>December 2018</span></h6>
						</div>
					</div>
				</div>
				<div class="clients-testimon-singl">
					<div class="clients-testi-inner-wrap">
					<h3 class="clients-testi-title">One of the very best!!!</h3>
					<div class="clients-testi-trip">
						Reviewed Tour:<h6>Pokhara Trips<span>(12 days ago)</span></h6>
					</div>
					<div class="clients-testi-desc">
						<p>My partner and I went on this unforgettable trek for 12 days to EBC and had an amazing time. There were issues with the flights on the day we were due to fly to Lukla but the company booked us a helicopter at no extra charge, which meant amazing views.</p>
					</div>
						<div class="client-intro-sc">
							<div class="client-dp">
								<img src="<?php echo esc_url( TBT_FILE_URL . '/images/demo/clients-testi-1.png' ); ?>" alt="">
							</div>
							<div class="client-intro-rght">
								<div class="ratingndrev">
									<div class="rating-layout-1 smaller-ver">
										<div class="circle-rating">
											<span class="circle-wrap-outer">
												<span class="single-circle-outer"></span>
												<span class="single-circle-outer"></span>
												<span class="single-circle-outer"></span>
												<span class="single-circle-outer"></span>
												<span class="single-circle-outer"></span>
											</span>
											<div class="circle-inner-rating">
												<span class="circle-wrap" style="width:95%">
													<span class="single-circle"></span>
													<span class="single-circle"></span>
													<span class="single-circle"></span>
													<span class="single-circle"></span>
													<span class="single-circle"></span>
												</span>
											</div>
										</div>
									</div>
								</div>

								<p class="clients-testi-locat"><span>Sumina Sharma,</span> from Nepal</p>
							</div>
						</div>
						<div class="clients-testi-single-gall">
							<figure><img src="<?php echo esc_url( TBT_FILE_URL . '/images/demo/clients-testi-single-gall-1.png' ); ?>" alt="image"></figure>
							<figure><img src="<?php echo esc_url( TBT_FILE_URL . '/images/demo/clients-testi-single-gall-2.png' ); ?>" alt="image"></figure>
							<figure><img src="<?php echo esc_url( TBT_FILE_URL . '/images/demo/clients-testi-single-gall-3.png' ); ?>" alt="image"></figure>
						</div>
						<div class="clients-testi-single-dateexp">
							<h6>Date of Exerience:<span>March 2019</span></h6>
						</div>
					</div>
				</div>
				<div class="clients-testimon-singl">
					<div class="clients-testi-inner-wrap">
						<h3 class="clients-testi-title">Best Trip of Life!!!</h3>
						<div class="clients-testi-trip">
							Reviewed Tour:<h6>Pokhara Trips<span>(12 days ago)</span></h6>
						</div>
						<div class="clients-testi-desc">
							<p>We only had 6 days in Nepal and decided to go for the 3 day trek as recommended by Chhatra which was just great, really easy to organise in advance via email and online booking. We all loved it from the start to the end.</p>
						</div>
						<div class="client-intro-sc">
							<div class="client-dp">
								<img src="<?php echo esc_url( TBT_FILE_URL . '/images/demo/clients-testi-1.png' ); ?>" alt="">
							</div>
							<div class="client-intro-rght">
								<div class="ratingndrev">
									<div class="rating-layout-1 smaller-ver">
										<div class="circle-rating">
											<span class="circle-wrap-outer">
												<span class="single-circle-outer"></span>
												<span class="single-circle-outer"></span>
												<span class="single-circle-outer"></span>
												<span class="single-circle-outer"></span>
												<span class="single-circle-outer"></span>
											</span>
											<div class="circle-inner-rating">
												<span class="circle-wrap" style="width:95%">
													<span class="single-circle"></span>
													<span class="single-circle"></span>
													<span class="single-circle"></span>
													<span class="single-circle"></span>
													<span class="single-circle"></span>
												</span>
											</div>
										</div>
									</div>
								</div>

								<p class="clients-testi-locat"><span>Sudin Manandhar,</span> from Nepal</p>
							</div>
						</div>
						<div class="clients-testi-single-gall">
							<figure><img src="<?php echo esc_url( TBT_FILE_URL . '/images/demo/clients-testi-single-gall-1.png' ); ?>" alt="image"></figure>
							<figure><img src="<?php echo esc_url( TBT_FILE_URL . '/images/demo/clients-testi-single-gall-2.png' ); ?>" alt="image"></figure>
							<figure><img src="<?php echo esc_url( TBT_FILE_URL . '/images/demo/clients-testi-single-gall-3.png' ); ?>" alt="image"></figure>
						</div>
						<div class="clients-testi-single-dateexp">
							<h6>Date of Exerience:<span>May 2019</span></h6>
						</div>
					</div>
				</div>
				</div>
				<div class="loadmore-btn clients-testi-loadmore">
					<button class="btn-primary load-more">Read More Reviews</button>
				</div>
		<?php
		break;

		case 'popular':
		?>
			<div class="popular-trips-wrap">
				<div class="popular-trips-single">
					<div class="popular-trips-single-inner-wrap">
						<figure class="popular-trip-fig">
							<img src="<?php echo esc_url( TBT_FILE_URL . '/images/demo/popular-trip-home-1.jpg' ); ?>" alt="image">
							<div class="popular-trip-group-avil">
								<span class="pop-trip-grpavil-icon">
									<svg xmlns="http://www.w3.org/2000/svg" width="17.492" height="14.72" viewBox="0 0 17.492 14.72">
				<g id="Group_898" data-name="Group 898" transform="translate(-452 -737)">
					<g id="Group_757" data-name="Group 757" transform="translate(12.114)">
						<g id="multiple-users-silhouette" transform="translate(439.886 737)">
							<path id="Path_23387" data-name="Path 23387" d="M10.555,8.875a3.178,3.178,0,0,1,1.479,2.361,2.564,2.564,0,1,0-1.479-2.361ZM8.875,14.127a2.565,2.565,0,1,0-2.566-2.565A2.565,2.565,0,0,0,8.875,14.127Zm1.088.175H7.786A3.289,3.289,0,0,0,4.5,17.587v2.662l.007.042.183.057a14.951,14.951,0,0,0,4.466.72,9.168,9.168,0,0,0,3.9-.732l.171-.087h.018V17.587A3.288,3.288,0,0,0,9.963,14.3Zm4.244-2.648h-2.16a3.162,3.162,0,0,1-.976,2.2,3.9,3.9,0,0,1,2.788,3.735v.82a8.839,8.839,0,0,0,3.443-.723l.171-.087h.018V14.938A3.288,3.288,0,0,0,14.207,11.654Zm-9.834-.175a2.548,2.548,0,0,0,1.364-.4A3.175,3.175,0,0,1,6.931,9.058c0-.048.007-.1.007-.144a2.565,2.565,0,1,0-2.565,2.565Zm2.3,2.377a3.163,3.163,0,0,1-.975-2.19c-.08-.006-.159-.012-.241-.012H3.285A3.288,3.288,0,0,0,0,14.938V17.6l.007.041L.19,17.7a15.4,15.4,0,0,0,3.7.7v-.8A3.9,3.9,0,0,1,6.677,13.856Z" transform="translate(0 -6.348)" fill="#fff"/>
						</g>
					</g>
				</g>
			</svg>
								</span>
								<span class="pop-trip-grpavil-txt">Group discount Available</span>
							</div>
							<div class="popular-trip-discount">
								<span class="discount-offer"><span>30%</span> Off</span>
							</div>
							<div class="popular-trip-budget">
								<span class="price-holder">
									<span class="striked-price">$4000</span>
									<span class="actual-price">$3000</span>
								</span>
							</div>
						</figure>
						<div class="popular-trip-detail-wrap">
							<h2 class="popular-trip-title"><a href="#">Island Peak Climbing Via Everest Base Camp</a></h2>
							<div class="popular-trip-desti">
								<span class="popular-trip-loc">
									<i>
										<svg xmlns="http://www.w3.org/2000/svg" width="11.213" height="15.81" viewBox="0 0 11.213 15.81">
			  <path id="Path_23393" data-name="Path 23393" d="M5.607,223.81c1.924-2.5,5.607-7.787,5.607-10.2a5.607,5.607,0,0,0-11.213,0C0,216.025,3.682,221.31,5.607,223.81Zm0-13.318a2.492,2.492,0,1,1-2.492,2.492A2.492,2.492,0,0,1,5.607,210.492Zm0,0" transform="translate(0 -208)" opacity="0.8"/>
			</svg>
									</i>
									<span><a href="#">Everest Region, Nepal</a></span>
								</span>
								<span class="popular-trip-dur">
									<i>
										<svg xmlns="http://www.w3.org/2000/svg" width="17.332" height="15.61" viewBox="0 0 17.332 15.61">
			  <g id="Group_773" data-name="Group 773" transform="translate(283.072 34.13)">
				<path id="Path_23383" data-name="Path 23383" d="M-283.057-26.176h.1c.466,0,.931,0,1.4,0,.084,0,.108-.024.1-.106-.006-.156,0-.313,0-.469a5.348,5.348,0,0,1,.066-.675,5.726,5.726,0,0,1,.162-.812,5.1,5.1,0,0,1,.17-.57,9.17,9.17,0,0,1,.383-.946,10.522,10.522,0,0,1,.573-.96c.109-.169.267-.307.371-.479a3.517,3.517,0,0,1,.5-.564,6.869,6.869,0,0,1,1.136-.97,9.538,9.538,0,0,1,.933-.557,7.427,7.427,0,0,1,1.631-.608c.284-.074.577-.11.867-.162a7.583,7.583,0,0,1,1.49-.072c.178,0,.356.053.534.062a2.673,2.673,0,0,1,.523.083c.147.038.3.056.445.1.255.07.511.138.759.228a6.434,6.434,0,0,1,1.22.569c.288.179.571.366.851.556a2.341,2.341,0,0,1,.319.259c.3.291.589.592.888.882a4.993,4.993,0,0,1,.64.85,6.611,6.611,0,0,1,.71,1.367c.065.175.121.352.178.53s.118.348.158.526c.054.242.09.487.133.731.024.14.045.281.067.422a.69.69,0,0,1,.008.1c0,.244.005.488,0,.731s-.015.5-.04.745a4.775,4.775,0,0,1-.095.5c-.04.191-.072.385-.128.572-.094.312-.191.625-.313.926a7.445,7.445,0,0,1-.43.9c-.173.3-.38.584-.579.87a8.045,8.045,0,0,1-1.2,1.26,5.842,5.842,0,0,1-.975.687,8.607,8.607,0,0,1-1.083.552,11.214,11.214,0,0,1-1.087.36c-.19.058-.386.1-.58.137-.121.025-.245.037-.368.052a12.316,12.316,0,0,1-1.57.034,3.994,3.994,0,0,1-.553-.065c-.166-.024-.33-.053-.5-.082a1.745,1.745,0,0,1-.21-.043c-.339-.1-.684-.189-1.013-.317a7,7,0,0,1-1.335-.673c-.2-.136-.417-.263-.609-.415a6.9,6.9,0,0,1-.566-.517.488.488,0,0,1-.128-.331.935.935,0,0,1,.1-.457.465.465,0,0,1,.3-.223.987.987,0,0,1,.478-.059.318.318,0,0,1,.139.073c.239.185.469.381.713.559a5.9,5.9,0,0,0,1.444.766,5.073,5.073,0,0,0,.484.169c.24.062.485.1.727.154a1.805,1.805,0,0,0,.2.037c.173.015.346.033.52.036.3.006.6.01.9,0a3.421,3.421,0,0,0,.562-.068c.337-.069.676-.139,1-.239a6.571,6.571,0,0,0,.783-.32,5.854,5.854,0,0,0,1.08-.663,5.389,5.389,0,0,0,.588-.533,8.013,8.013,0,0,0,.675-.738,5.518,5.518,0,0,0,.749-1.274,9.733,9.733,0,0,0,.366-1.107,4.926,4.926,0,0,0,.142-.833c.025-.269.008-.542.014-.814a4.716,4.716,0,0,0-.07-.815,5.8,5.8,0,0,0-.281-1.12,5.311,5.311,0,0,0-.548-1.147,9.019,9.019,0,0,0-.645-.914,9.267,9.267,0,0,0-.824-.788,3.354,3.354,0,0,0-.425-.321,5.664,5.664,0,0,0-1.048-.581c-.244-.093-.484-.2-.732-.275a6.877,6.877,0,0,0-.688-.161c-.212-.043-.427-.074-.641-.109a.528.528,0,0,0-.084,0c-.169,0-.338,0-.506,0a5.882,5.882,0,0,0-1.177.1,6.79,6.79,0,0,0-1.016.274,6.575,6.575,0,0,0-1.627.856,6.252,6.252,0,0,0-1.032.948,6.855,6.855,0,0,0-.644.847,4.657,4.657,0,0,0-.519,1.017c-.112.323-.227.647-.307.979a3.45,3.45,0,0,0-.13.91,4.4,4.4,0,0,1-.036.529c-.008.086.026.1.106.1.463,0,.925,0,1.388,0a.122.122,0,0,1,.08.028c.009.009-.005.051-.019.072q-.28.415-.563.827c-.162.236-.33.468-.489.705-.118.175-.222.359-.339.535-.1.144-.2.281-.3.423-.142.2-.282.41-.423.615-.016.023-.031.047-.048.069-.062.084-.086.083-.142,0-.166-.249-.332-.5-.5-.746-.3-.44-.6-.878-.9-1.318q-.358-.525-.714-1.051c-.031-.045-.063-.09-.094-.134Z" transform="translate(0 0)"/>
				<path id="Path_23384" data-name="Path 23384" d="M150.612,112.52c0,.655,0,1.31,0,1.966a.216.216,0,0,0,.087.178,4.484,4.484,0,0,1,.358.346.227.227,0,0,0,.186.087q1.616,0,3.233,0a.659.659,0,0,1,.622.4.743.743,0,0,1-.516,1.074,1.361,1.361,0,0,1-.323.038q-1.507,0-3.013,0a.248.248,0,0,0-.216.109,1.509,1.509,0,0,1-.765.511,1.444,1.444,0,0,1-1.256-2.555.218.218,0,0,0,.09-.207q0-1.916,0-3.831a.784.784,0,0,1,.741-.732.742.742,0,0,1,.761.544.489.489,0,0,1,.015.127Q150.612,111.547,150.612,112.52Z" transform="translate(-423.686 -141.471)"/>
			  </g>
			</svg>
									</i>
									<span>11 Days</span>
								</span>
							</div>
							<div class="popular-trip-review">
								<div class="rating-rev rating-layout-1 smaller-ver">
									<div class="circle-rating">
										<span class="circle-wrap-outer">
											<span class="single-circle-outer"></span>
											<span class="single-circle-outer"></span>
											<span class="single-circle-outer"></span>
											<span class="single-circle-outer"></span>
											<span class="single-circle-outer"></span>
										</span>
										<div class="circle-inner-rating">
											<span class="circle-wrap" style="width:95%">
												<span class="single-circle"></span>
												<span class="single-circle"></span>
												<span class="single-circle"></span>
												<span class="single-circle"></span>
												<span class="single-circle"></span>
											</span>
										</div>
									</div>
								</div>
								<span class="popular-trip-reviewcount">22 Reviews</span>
							</div>
						</div>
					</div>
				</div>

				<div class="popular-trips-single">
					<div class="popular-trips-single-inner-wrap">
						<figure class="popular-trip-fig">
							<img src="<?php echo esc_url( TBT_FILE_URL . '/images/demo/popular-trip-home-4.jpg' ); ?>" alt="image">
							<div class="popular-trip-group-avil">
								<span class="pop-trip-grpavil-icon">
									<svg xmlns="http://www.w3.org/2000/svg" width="17.492" height="14.72" viewBox="0 0 17.492 14.72">
				<g id="Group_898" data-name="Group 898" transform="translate(-452 -737)">
					<g id="Group_757" data-name="Group 757" transform="translate(12.114)">
						<g id="multiple-users-silhouette" transform="translate(439.886 737)">
							<path id="Path_23387" data-name="Path 23387" d="M10.555,8.875a3.178,3.178,0,0,1,1.479,2.361,2.564,2.564,0,1,0-1.479-2.361ZM8.875,14.127a2.565,2.565,0,1,0-2.566-2.565A2.565,2.565,0,0,0,8.875,14.127Zm1.088.175H7.786A3.289,3.289,0,0,0,4.5,17.587v2.662l.007.042.183.057a14.951,14.951,0,0,0,4.466.72,9.168,9.168,0,0,0,3.9-.732l.171-.087h.018V17.587A3.288,3.288,0,0,0,9.963,14.3Zm4.244-2.648h-2.16a3.162,3.162,0,0,1-.976,2.2,3.9,3.9,0,0,1,2.788,3.735v.82a8.839,8.839,0,0,0,3.443-.723l.171-.087h.018V14.938A3.288,3.288,0,0,0,14.207,11.654Zm-9.834-.175a2.548,2.548,0,0,0,1.364-.4A3.175,3.175,0,0,1,6.931,9.058c0-.048.007-.1.007-.144a2.565,2.565,0,1,0-2.565,2.565Zm2.3,2.377a3.163,3.163,0,0,1-.975-2.19c-.08-.006-.159-.012-.241-.012H3.285A3.288,3.288,0,0,0,0,14.938V17.6l.007.041L.19,17.7a15.4,15.4,0,0,0,3.7.7v-.8A3.9,3.9,0,0,1,6.677,13.856Z" transform="translate(0 -6.348)" fill="#fff"/>
						</g>
					</g>
				</g>
			</svg>
								</span>
								<span class="pop-trip-grpavil-txt">Group discount Available</span>
							</div>
							<div class="popular-trip-discount">
								<span class="discount-offer"><span>30%</span> Off</span>
							</div>
							<div class="popular-trip-budget">
								<span class="price-holder">
									<span class="striked-price">$4000</span>
									<span class="actual-price">$3000</span>
								</span>
							</div>
						</figure>
						<div class="popular-trip-detail-wrap">
							<h2 class="popular-trip-title"><a href="#">Kathmandu Pokhara Chitwan Lumbini Tour</a></h2>
							<div class="popular-trip-desti">
								<span class="popular-trip-loc">
									<i>
										<svg xmlns="http://www.w3.org/2000/svg" width="11.213" height="15.81" viewBox="0 0 11.213 15.81">
			  <path id="Path_23393" data-name="Path 23393" d="M5.607,223.81c1.924-2.5,5.607-7.787,5.607-10.2a5.607,5.607,0,0,0-11.213,0C0,216.025,3.682,221.31,5.607,223.81Zm0-13.318a2.492,2.492,0,1,1-2.492,2.492A2.492,2.492,0,0,1,5.607,210.492Zm0,0" transform="translate(0 -208)" opacity="0.8"/>
			</svg>
									</i>
									<span><a href="#">Everest Region, Nepal</a></span>
								</span>
								<span class="popular-trip-dur">
									<i>
										<svg xmlns="http://www.w3.org/2000/svg" width="17.332" height="15.61" viewBox="0 0 17.332 15.61">
			  <g id="Group_773" data-name="Group 773" transform="translate(283.072 34.13)">
				<path id="Path_23383" data-name="Path 23383" d="M-283.057-26.176h.1c.466,0,.931,0,1.4,0,.084,0,.108-.024.1-.106-.006-.156,0-.313,0-.469a5.348,5.348,0,0,1,.066-.675,5.726,5.726,0,0,1,.162-.812,5.1,5.1,0,0,1,.17-.57,9.17,9.17,0,0,1,.383-.946,10.522,10.522,0,0,1,.573-.96c.109-.169.267-.307.371-.479a3.517,3.517,0,0,1,.5-.564,6.869,6.869,0,0,1,1.136-.97,9.538,9.538,0,0,1,.933-.557,7.427,7.427,0,0,1,1.631-.608c.284-.074.577-.11.867-.162a7.583,7.583,0,0,1,1.49-.072c.178,0,.356.053.534.062a2.673,2.673,0,0,1,.523.083c.147.038.3.056.445.1.255.07.511.138.759.228a6.434,6.434,0,0,1,1.22.569c.288.179.571.366.851.556a2.341,2.341,0,0,1,.319.259c.3.291.589.592.888.882a4.993,4.993,0,0,1,.64.85,6.611,6.611,0,0,1,.71,1.367c.065.175.121.352.178.53s.118.348.158.526c.054.242.09.487.133.731.024.14.045.281.067.422a.69.69,0,0,1,.008.1c0,.244.005.488,0,.731s-.015.5-.04.745a4.775,4.775,0,0,1-.095.5c-.04.191-.072.385-.128.572-.094.312-.191.625-.313.926a7.445,7.445,0,0,1-.43.9c-.173.3-.38.584-.579.87a8.045,8.045,0,0,1-1.2,1.26,5.842,5.842,0,0,1-.975.687,8.607,8.607,0,0,1-1.083.552,11.214,11.214,0,0,1-1.087.36c-.19.058-.386.1-.58.137-.121.025-.245.037-.368.052a12.316,12.316,0,0,1-1.57.034,3.994,3.994,0,0,1-.553-.065c-.166-.024-.33-.053-.5-.082a1.745,1.745,0,0,1-.21-.043c-.339-.1-.684-.189-1.013-.317a7,7,0,0,1-1.335-.673c-.2-.136-.417-.263-.609-.415a6.9,6.9,0,0,1-.566-.517.488.488,0,0,1-.128-.331.935.935,0,0,1,.1-.457.465.465,0,0,1,.3-.223.987.987,0,0,1,.478-.059.318.318,0,0,1,.139.073c.239.185.469.381.713.559a5.9,5.9,0,0,0,1.444.766,5.073,5.073,0,0,0,.484.169c.24.062.485.1.727.154a1.805,1.805,0,0,0,.2.037c.173.015.346.033.52.036.3.006.6.01.9,0a3.421,3.421,0,0,0,.562-.068c.337-.069.676-.139,1-.239a6.571,6.571,0,0,0,.783-.32,5.854,5.854,0,0,0,1.08-.663,5.389,5.389,0,0,0,.588-.533,8.013,8.013,0,0,0,.675-.738,5.518,5.518,0,0,0,.749-1.274,9.733,9.733,0,0,0,.366-1.107,4.926,4.926,0,0,0,.142-.833c.025-.269.008-.542.014-.814a4.716,4.716,0,0,0-.07-.815,5.8,5.8,0,0,0-.281-1.12,5.311,5.311,0,0,0-.548-1.147,9.019,9.019,0,0,0-.645-.914,9.267,9.267,0,0,0-.824-.788,3.354,3.354,0,0,0-.425-.321,5.664,5.664,0,0,0-1.048-.581c-.244-.093-.484-.2-.732-.275a6.877,6.877,0,0,0-.688-.161c-.212-.043-.427-.074-.641-.109a.528.528,0,0,0-.084,0c-.169,0-.338,0-.506,0a5.882,5.882,0,0,0-1.177.1,6.79,6.79,0,0,0-1.016.274,6.575,6.575,0,0,0-1.627.856,6.252,6.252,0,0,0-1.032.948,6.855,6.855,0,0,0-.644.847,4.657,4.657,0,0,0-.519,1.017c-.112.323-.227.647-.307.979a3.45,3.45,0,0,0-.13.91,4.4,4.4,0,0,1-.036.529c-.008.086.026.1.106.1.463,0,.925,0,1.388,0a.122.122,0,0,1,.08.028c.009.009-.005.051-.019.072q-.28.415-.563.827c-.162.236-.33.468-.489.705-.118.175-.222.359-.339.535-.1.144-.2.281-.3.423-.142.2-.282.41-.423.615-.016.023-.031.047-.048.069-.062.084-.086.083-.142,0-.166-.249-.332-.5-.5-.746-.3-.44-.6-.878-.9-1.318q-.358-.525-.714-1.051c-.031-.045-.063-.09-.094-.134Z" transform="translate(0 0)"/>
				<path id="Path_23384" data-name="Path 23384" d="M150.612,112.52c0,.655,0,1.31,0,1.966a.216.216,0,0,0,.087.178,4.484,4.484,0,0,1,.358.346.227.227,0,0,0,.186.087q1.616,0,3.233,0a.659.659,0,0,1,.622.4.743.743,0,0,1-.516,1.074,1.361,1.361,0,0,1-.323.038q-1.507,0-3.013,0a.248.248,0,0,0-.216.109,1.509,1.509,0,0,1-.765.511,1.444,1.444,0,0,1-1.256-2.555.218.218,0,0,0,.09-.207q0-1.916,0-3.831a.784.784,0,0,1,.741-.732.742.742,0,0,1,.761.544.489.489,0,0,1,.015.127Q150.612,111.547,150.612,112.52Z" transform="translate(-423.686 -141.471)"/>
			  </g>
			</svg>
									</i>
									<span>11 Days</span>
								</span>
							</div>
							<div class="popular-trip-review">
								<div class="rating-rev rating-layout-1 smaller-ver">
									<div class="circle-rating">
										<span class="circle-wrap-outer">
											<span class="single-circle-outer"></span>
											<span class="single-circle-outer"></span>
											<span class="single-circle-outer"></span>
											<span class="single-circle-outer"></span>
											<span class="single-circle-outer"></span>
										</span>
										<div class="circle-inner-rating">
											<span class="circle-wrap" style="width:95%">
												<span class="single-circle"></span>
												<span class="single-circle"></span>
												<span class="single-circle"></span>
												<span class="single-circle"></span>
												<span class="single-circle"></span>
											</span>
										</div>
									</div>
								</div>
								<span class="popular-trip-reviewcount">22 Reviews</span>
							</div>
						</div>
					</div>
				</div>


				<div class="popular-trips-single">
					<div class="popular-trips-single-inner-wrap">
						<figure class="popular-trip-fig">
							<img src="<?php echo esc_url( TBT_FILE_URL . '/images/demo/popular-trip-home-6.jpg' ); ?>" alt="image">
							<div class="popular-trip-group-avil">
								<span class="pop-trip-grpavil-icon">
									<svg xmlns="http://www.w3.org/2000/svg" width="17.492" height="14.72" viewBox="0 0 17.492 14.72">
				<g id="Group_898" data-name="Group 898" transform="translate(-452 -737)">
					<g id="Group_757" data-name="Group 757" transform="translate(12.114)">
						<g id="multiple-users-silhouette" transform="translate(439.886 737)">
							<path id="Path_23387" data-name="Path 23387" d="M10.555,8.875a3.178,3.178,0,0,1,1.479,2.361,2.564,2.564,0,1,0-1.479-2.361ZM8.875,14.127a2.565,2.565,0,1,0-2.566-2.565A2.565,2.565,0,0,0,8.875,14.127Zm1.088.175H7.786A3.289,3.289,0,0,0,4.5,17.587v2.662l.007.042.183.057a14.951,14.951,0,0,0,4.466.72,9.168,9.168,0,0,0,3.9-.732l.171-.087h.018V17.587A3.288,3.288,0,0,0,9.963,14.3Zm4.244-2.648h-2.16a3.162,3.162,0,0,1-.976,2.2,3.9,3.9,0,0,1,2.788,3.735v.82a8.839,8.839,0,0,0,3.443-.723l.171-.087h.018V14.938A3.288,3.288,0,0,0,14.207,11.654Zm-9.834-.175a2.548,2.548,0,0,0,1.364-.4A3.175,3.175,0,0,1,6.931,9.058c0-.048.007-.1.007-.144a2.565,2.565,0,1,0-2.565,2.565Zm2.3,2.377a3.163,3.163,0,0,1-.975-2.19c-.08-.006-.159-.012-.241-.012H3.285A3.288,3.288,0,0,0,0,14.938V17.6l.007.041L.19,17.7a15.4,15.4,0,0,0,3.7.7v-.8A3.9,3.9,0,0,1,6.677,13.856Z" transform="translate(0 -6.348)" fill="#fff"/>
						</g>
					</g>
				</g>
			</svg>
								</span>
								<span class="pop-trip-grpavil-txt">Group discount Available</span>
							</div>
							<div class="popular-trip-discount">
								<span class="discount-offer"><span>30%</span> Off</span>
							</div>
							<div class="popular-trip-budget">
								<span class="price-holder">
									<span class="striked-price">$4000</span>
									<span class="actual-price">$3000</span>
								</span>
							</div>
						</figure>
						<div class="popular-trip-detail-wrap">
							<h2 class="popular-trip-title"><a href="#">Everest Base Camp &amp; Gokyo Lakes Trek via Cho La Pass</a></h2>
							<div class="popular-trip-desti">
								<span class="popular-trip-loc">
									<i>
										<svg xmlns="http://www.w3.org/2000/svg" width="11.213" height="15.81" viewBox="0 0 11.213 15.81">
			  <path id="Path_23393" data-name="Path 23393" d="M5.607,223.81c1.924-2.5,5.607-7.787,5.607-10.2a5.607,5.607,0,0,0-11.213,0C0,216.025,3.682,221.31,5.607,223.81Zm0-13.318a2.492,2.492,0,1,1-2.492,2.492A2.492,2.492,0,0,1,5.607,210.492Zm0,0" transform="translate(0 -208)" opacity="0.8"/>
			</svg>
									</i>
									<span><a href="#">Everest Region, Nepal</a></span>
								</span>
								<span class="popular-trip-dur">
									<i>
										<svg xmlns="http://www.w3.org/2000/svg" width="17.332" height="15.61" viewBox="0 0 17.332 15.61">
			  <g id="Group_773" data-name="Group 773" transform="translate(283.072 34.13)">
				<path id="Path_23383" data-name="Path 23383" d="M-283.057-26.176h.1c.466,0,.931,0,1.4,0,.084,0,.108-.024.1-.106-.006-.156,0-.313,0-.469a5.348,5.348,0,0,1,.066-.675,5.726,5.726,0,0,1,.162-.812,5.1,5.1,0,0,1,.17-.57,9.17,9.17,0,0,1,.383-.946,10.522,10.522,0,0,1,.573-.96c.109-.169.267-.307.371-.479a3.517,3.517,0,0,1,.5-.564,6.869,6.869,0,0,1,1.136-.97,9.538,9.538,0,0,1,.933-.557,7.427,7.427,0,0,1,1.631-.608c.284-.074.577-.11.867-.162a7.583,7.583,0,0,1,1.49-.072c.178,0,.356.053.534.062a2.673,2.673,0,0,1,.523.083c.147.038.3.056.445.1.255.07.511.138.759.228a6.434,6.434,0,0,1,1.22.569c.288.179.571.366.851.556a2.341,2.341,0,0,1,.319.259c.3.291.589.592.888.882a4.993,4.993,0,0,1,.64.85,6.611,6.611,0,0,1,.71,1.367c.065.175.121.352.178.53s.118.348.158.526c.054.242.09.487.133.731.024.14.045.281.067.422a.69.69,0,0,1,.008.1c0,.244.005.488,0,.731s-.015.5-.04.745a4.775,4.775,0,0,1-.095.5c-.04.191-.072.385-.128.572-.094.312-.191.625-.313.926a7.445,7.445,0,0,1-.43.9c-.173.3-.38.584-.579.87a8.045,8.045,0,0,1-1.2,1.26,5.842,5.842,0,0,1-.975.687,8.607,8.607,0,0,1-1.083.552,11.214,11.214,0,0,1-1.087.36c-.19.058-.386.1-.58.137-.121.025-.245.037-.368.052a12.316,12.316,0,0,1-1.57.034,3.994,3.994,0,0,1-.553-.065c-.166-.024-.33-.053-.5-.082a1.745,1.745,0,0,1-.21-.043c-.339-.1-.684-.189-1.013-.317a7,7,0,0,1-1.335-.673c-.2-.136-.417-.263-.609-.415a6.9,6.9,0,0,1-.566-.517.488.488,0,0,1-.128-.331.935.935,0,0,1,.1-.457.465.465,0,0,1,.3-.223.987.987,0,0,1,.478-.059.318.318,0,0,1,.139.073c.239.185.469.381.713.559a5.9,5.9,0,0,0,1.444.766,5.073,5.073,0,0,0,.484.169c.24.062.485.1.727.154a1.805,1.805,0,0,0,.2.037c.173.015.346.033.52.036.3.006.6.01.9,0a3.421,3.421,0,0,0,.562-.068c.337-.069.676-.139,1-.239a6.571,6.571,0,0,0,.783-.32,5.854,5.854,0,0,0,1.08-.663,5.389,5.389,0,0,0,.588-.533,8.013,8.013,0,0,0,.675-.738,5.518,5.518,0,0,0,.749-1.274,9.733,9.733,0,0,0,.366-1.107,4.926,4.926,0,0,0,.142-.833c.025-.269.008-.542.014-.814a4.716,4.716,0,0,0-.07-.815,5.8,5.8,0,0,0-.281-1.12,5.311,5.311,0,0,0-.548-1.147,9.019,9.019,0,0,0-.645-.914,9.267,9.267,0,0,0-.824-.788,3.354,3.354,0,0,0-.425-.321,5.664,5.664,0,0,0-1.048-.581c-.244-.093-.484-.2-.732-.275a6.877,6.877,0,0,0-.688-.161c-.212-.043-.427-.074-.641-.109a.528.528,0,0,0-.084,0c-.169,0-.338,0-.506,0a5.882,5.882,0,0,0-1.177.1,6.79,6.79,0,0,0-1.016.274,6.575,6.575,0,0,0-1.627.856,6.252,6.252,0,0,0-1.032.948,6.855,6.855,0,0,0-.644.847,4.657,4.657,0,0,0-.519,1.017c-.112.323-.227.647-.307.979a3.45,3.45,0,0,0-.13.91,4.4,4.4,0,0,1-.036.529c-.008.086.026.1.106.1.463,0,.925,0,1.388,0a.122.122,0,0,1,.08.028c.009.009-.005.051-.019.072q-.28.415-.563.827c-.162.236-.33.468-.489.705-.118.175-.222.359-.339.535-.1.144-.2.281-.3.423-.142.2-.282.41-.423.615-.016.023-.031.047-.048.069-.062.084-.086.083-.142,0-.166-.249-.332-.5-.5-.746-.3-.44-.6-.878-.9-1.318q-.358-.525-.714-1.051c-.031-.045-.063-.09-.094-.134Z" transform="translate(0 0)"/>
				<path id="Path_23384" data-name="Path 23384" d="M150.612,112.52c0,.655,0,1.31,0,1.966a.216.216,0,0,0,.087.178,4.484,4.484,0,0,1,.358.346.227.227,0,0,0,.186.087q1.616,0,3.233,0a.659.659,0,0,1,.622.4.743.743,0,0,1-.516,1.074,1.361,1.361,0,0,1-.323.038q-1.507,0-3.013,0a.248.248,0,0,0-.216.109,1.509,1.509,0,0,1-.765.511,1.444,1.444,0,0,1-1.256-2.555.218.218,0,0,0,.09-.207q0-1.916,0-3.831a.784.784,0,0,1,.741-.732.742.742,0,0,1,.761.544.489.489,0,0,1,.015.127Q150.612,111.547,150.612,112.52Z" transform="translate(-423.686 -141.471)"/>
			  </g>
			</svg>
									</i>
									<span>11 Days</span>
								</span>
							</div>
							<div class="popular-trip-review">
								<div class="rating-rev rating-layout-1 smaller-ver">
									<div class="circle-rating">
										<span class="circle-wrap-outer">
											<span class="single-circle-outer"></span>
											<span class="single-circle-outer"></span>
											<span class="single-circle-outer"></span>
											<span class="single-circle-outer"></span>
											<span class="single-circle-outer"></span>
										</span>
										<div class="circle-inner-rating">
											<span class="circle-wrap" style="width:95%">
												<span class="single-circle"></span>
												<span class="single-circle"></span>
												<span class="single-circle"></span>
												<span class="single-circle"></span>
												<span class="single-circle"></span>
											</span>
										</div>
									</div>
								</div>
								<span class="popular-trip-reviewcount">22 Reviews</span>
							</div>
						</div>
					</div>
				</div>


				<div class="popular-trips-single">
					<div class="popular-trips-single-inner-wrap">
						<figure class="popular-trip-fig">
							<img src="<?php echo esc_url( TBT_FILE_URL . '/images/demo/popular-trip-home-4.jpg' ); ?>" alt="image">
							<div class="popular-trip-group-avil">
								<span class="pop-trip-grpavil-icon">
									<svg xmlns="http://www.w3.org/2000/svg" width="17.492" height="14.72" viewBox="0 0 17.492 14.72">
				<g id="Group_898" data-name="Group 898" transform="translate(-452 -737)">
					<g id="Group_757" data-name="Group 757" transform="translate(12.114)">
						<g id="multiple-users-silhouette" transform="translate(439.886 737)">
							<path id="Path_23387" data-name="Path 23387" d="M10.555,8.875a3.178,3.178,0,0,1,1.479,2.361,2.564,2.564,0,1,0-1.479-2.361ZM8.875,14.127a2.565,2.565,0,1,0-2.566-2.565A2.565,2.565,0,0,0,8.875,14.127Zm1.088.175H7.786A3.289,3.289,0,0,0,4.5,17.587v2.662l.007.042.183.057a14.951,14.951,0,0,0,4.466.72,9.168,9.168,0,0,0,3.9-.732l.171-.087h.018V17.587A3.288,3.288,0,0,0,9.963,14.3Zm4.244-2.648h-2.16a3.162,3.162,0,0,1-.976,2.2,3.9,3.9,0,0,1,2.788,3.735v.82a8.839,8.839,0,0,0,3.443-.723l.171-.087h.018V14.938A3.288,3.288,0,0,0,14.207,11.654Zm-9.834-.175a2.548,2.548,0,0,0,1.364-.4A3.175,3.175,0,0,1,6.931,9.058c0-.048.007-.1.007-.144a2.565,2.565,0,1,0-2.565,2.565Zm2.3,2.377a3.163,3.163,0,0,1-.975-2.19c-.08-.006-.159-.012-.241-.012H3.285A3.288,3.288,0,0,0,0,14.938V17.6l.007.041L.19,17.7a15.4,15.4,0,0,0,3.7.7v-.8A3.9,3.9,0,0,1,6.677,13.856Z" transform="translate(0 -6.348)" fill="#fff"/>
						</g>
					</g>
				</g>
			</svg>
								</span>
								<span class="pop-trip-grpavil-txt">Group discount Available</span>
							</div>
							<div class="popular-trip-discount">
								<span class="discount-offer"><span>30%</span> Off</span>
							</div>
							<div class="popular-trip-budget">
								<span class="price-holder">
									<span class="striked-price">$4000</span>
									<span class="actual-price">$3000</span>
								</span>
							</div>
						</figure>
						<div class="popular-trip-detail-wrap">
							<h2 class="popular-trip-title"><a href="#">Ghorepani Poon Hill Mountain Trek (Tadapani - Landruk)</a></h2>
							<div class="popular-trip-desti">
								<span class="popular-trip-loc">
									<i>
										<svg xmlns="http://www.w3.org/2000/svg" width="11.213" height="15.81" viewBox="0 0 11.213 15.81">
			  <path id="Path_23393" data-name="Path 23393" d="M5.607,223.81c1.924-2.5,5.607-7.787,5.607-10.2a5.607,5.607,0,0,0-11.213,0C0,216.025,3.682,221.31,5.607,223.81Zm0-13.318a2.492,2.492,0,1,1-2.492,2.492A2.492,2.492,0,0,1,5.607,210.492Zm0,0" transform="translate(0 -208)" opacity="0.8"/>
			</svg>
									</i>
									<span><a href="#">Everest Region, Nepal</a></span>
								</span>
								<span class="popular-trip-dur">
									<i>
										<svg xmlns="http://www.w3.org/2000/svg" width="17.332" height="15.61" viewBox="0 0 17.332 15.61">
			  <g id="Group_773" data-name="Group 773" transform="translate(283.072 34.13)">
				<path id="Path_23383" data-name="Path 23383" d="M-283.057-26.176h.1c.466,0,.931,0,1.4,0,.084,0,.108-.024.1-.106-.006-.156,0-.313,0-.469a5.348,5.348,0,0,1,.066-.675,5.726,5.726,0,0,1,.162-.812,5.1,5.1,0,0,1,.17-.57,9.17,9.17,0,0,1,.383-.946,10.522,10.522,0,0,1,.573-.96c.109-.169.267-.307.371-.479a3.517,3.517,0,0,1,.5-.564,6.869,6.869,0,0,1,1.136-.97,9.538,9.538,0,0,1,.933-.557,7.427,7.427,0,0,1,1.631-.608c.284-.074.577-.11.867-.162a7.583,7.583,0,0,1,1.49-.072c.178,0,.356.053.534.062a2.673,2.673,0,0,1,.523.083c.147.038.3.056.445.1.255.07.511.138.759.228a6.434,6.434,0,0,1,1.22.569c.288.179.571.366.851.556a2.341,2.341,0,0,1,.319.259c.3.291.589.592.888.882a4.993,4.993,0,0,1,.64.85,6.611,6.611,0,0,1,.71,1.367c.065.175.121.352.178.53s.118.348.158.526c.054.242.09.487.133.731.024.14.045.281.067.422a.69.69,0,0,1,.008.1c0,.244.005.488,0,.731s-.015.5-.04.745a4.775,4.775,0,0,1-.095.5c-.04.191-.072.385-.128.572-.094.312-.191.625-.313.926a7.445,7.445,0,0,1-.43.9c-.173.3-.38.584-.579.87a8.045,8.045,0,0,1-1.2,1.26,5.842,5.842,0,0,1-.975.687,8.607,8.607,0,0,1-1.083.552,11.214,11.214,0,0,1-1.087.36c-.19.058-.386.1-.58.137-.121.025-.245.037-.368.052a12.316,12.316,0,0,1-1.57.034,3.994,3.994,0,0,1-.553-.065c-.166-.024-.33-.053-.5-.082a1.745,1.745,0,0,1-.21-.043c-.339-.1-.684-.189-1.013-.317a7,7,0,0,1-1.335-.673c-.2-.136-.417-.263-.609-.415a6.9,6.9,0,0,1-.566-.517.488.488,0,0,1-.128-.331.935.935,0,0,1,.1-.457.465.465,0,0,1,.3-.223.987.987,0,0,1,.478-.059.318.318,0,0,1,.139.073c.239.185.469.381.713.559a5.9,5.9,0,0,0,1.444.766,5.073,5.073,0,0,0,.484.169c.24.062.485.1.727.154a1.805,1.805,0,0,0,.2.037c.173.015.346.033.52.036.3.006.6.01.9,0a3.421,3.421,0,0,0,.562-.068c.337-.069.676-.139,1-.239a6.571,6.571,0,0,0,.783-.32,5.854,5.854,0,0,0,1.08-.663,5.389,5.389,0,0,0,.588-.533,8.013,8.013,0,0,0,.675-.738,5.518,5.518,0,0,0,.749-1.274,9.733,9.733,0,0,0,.366-1.107,4.926,4.926,0,0,0,.142-.833c.025-.269.008-.542.014-.814a4.716,4.716,0,0,0-.07-.815,5.8,5.8,0,0,0-.281-1.12,5.311,5.311,0,0,0-.548-1.147,9.019,9.019,0,0,0-.645-.914,9.267,9.267,0,0,0-.824-.788,3.354,3.354,0,0,0-.425-.321,5.664,5.664,0,0,0-1.048-.581c-.244-.093-.484-.2-.732-.275a6.877,6.877,0,0,0-.688-.161c-.212-.043-.427-.074-.641-.109a.528.528,0,0,0-.084,0c-.169,0-.338,0-.506,0a5.882,5.882,0,0,0-1.177.1,6.79,6.79,0,0,0-1.016.274,6.575,6.575,0,0,0-1.627.856,6.252,6.252,0,0,0-1.032.948,6.855,6.855,0,0,0-.644.847,4.657,4.657,0,0,0-.519,1.017c-.112.323-.227.647-.307.979a3.45,3.45,0,0,0-.13.91,4.4,4.4,0,0,1-.036.529c-.008.086.026.1.106.1.463,0,.925,0,1.388,0a.122.122,0,0,1,.08.028c.009.009-.005.051-.019.072q-.28.415-.563.827c-.162.236-.33.468-.489.705-.118.175-.222.359-.339.535-.1.144-.2.281-.3.423-.142.2-.282.41-.423.615-.016.023-.031.047-.048.069-.062.084-.086.083-.142,0-.166-.249-.332-.5-.5-.746-.3-.44-.6-.878-.9-1.318q-.358-.525-.714-1.051c-.031-.045-.063-.09-.094-.134Z" transform="translate(0 0)"/>
				<path id="Path_23384" data-name="Path 23384" d="M150.612,112.52c0,.655,0,1.31,0,1.966a.216.216,0,0,0,.087.178,4.484,4.484,0,0,1,.358.346.227.227,0,0,0,.186.087q1.616,0,3.233,0a.659.659,0,0,1,.622.4.743.743,0,0,1-.516,1.074,1.361,1.361,0,0,1-.323.038q-1.507,0-3.013,0a.248.248,0,0,0-.216.109,1.509,1.509,0,0,1-.765.511,1.444,1.444,0,0,1-1.256-2.555.218.218,0,0,0,.09-.207q0-1.916,0-3.831a.784.784,0,0,1,.741-.732.742.742,0,0,1,.761.544.489.489,0,0,1,.015.127Q150.612,111.547,150.612,112.52Z" transform="translate(-423.686 -141.471)"/>
			  </g>
			</svg>
									</i>
									<span>11 Days</span>
								</span>
							</div>
							<div class="popular-trip-review">
								<div class="rating-rev rating-layout-1 smaller-ver">
									<div class="circle-rating">
										<span class="circle-wrap-outer">
											<span class="single-circle-outer"></span>
											<span class="single-circle-outer"></span>
											<span class="single-circle-outer"></span>
											<span class="single-circle-outer"></span>
											<span class="single-circle-outer"></span>
										</span>
										<div class="circle-inner-rating">
											<span class="circle-wrap" style="width:95%">
												<span class="single-circle"></span>
												<span class="single-circle"></span>
												<span class="single-circle"></span>
												<span class="single-circle"></span>
												<span class="single-circle"></span>
											</span>
										</div>
									</div>
								</div>
								<span class="popular-trip-reviewcount">22 Reviews</span>
							</div>
						</div>
					</div>
				</div>


				<div class="popular-trips-single">
					<div class="popular-trips-single-inner-wrap">
						<figure class="popular-trip-fig">
							<img src="<?php echo esc_url( TBT_FILE_URL . '/images/demo/popular-trip-home-1.jpg' ); ?>" alt="image">
							<div class="popular-trip-group-avil">
								<span class="pop-trip-grpavil-icon">
									<svg xmlns="http://www.w3.org/2000/svg" width="17.492" height="14.72" viewBox="0 0 17.492 14.72">
				<g id="Group_898" data-name="Group 898" transform="translate(-452 -737)">
					<g id="Group_757" data-name="Group 757" transform="translate(12.114)">
						<g id="multiple-users-silhouette" transform="translate(439.886 737)">
							<path id="Path_23387" data-name="Path 23387" d="M10.555,8.875a3.178,3.178,0,0,1,1.479,2.361,2.564,2.564,0,1,0-1.479-2.361ZM8.875,14.127a2.565,2.565,0,1,0-2.566-2.565A2.565,2.565,0,0,0,8.875,14.127Zm1.088.175H7.786A3.289,3.289,0,0,0,4.5,17.587v2.662l.007.042.183.057a14.951,14.951,0,0,0,4.466.72,9.168,9.168,0,0,0,3.9-.732l.171-.087h.018V17.587A3.288,3.288,0,0,0,9.963,14.3Zm4.244-2.648h-2.16a3.162,3.162,0,0,1-.976,2.2,3.9,3.9,0,0,1,2.788,3.735v.82a8.839,8.839,0,0,0,3.443-.723l.171-.087h.018V14.938A3.288,3.288,0,0,0,14.207,11.654Zm-9.834-.175a2.548,2.548,0,0,0,1.364-.4A3.175,3.175,0,0,1,6.931,9.058c0-.048.007-.1.007-.144a2.565,2.565,0,1,0-2.565,2.565Zm2.3,2.377a3.163,3.163,0,0,1-.975-2.19c-.08-.006-.159-.012-.241-.012H3.285A3.288,3.288,0,0,0,0,14.938V17.6l.007.041L.19,17.7a15.4,15.4,0,0,0,3.7.7v-.8A3.9,3.9,0,0,1,6.677,13.856Z" transform="translate(0 -6.348)" fill="#fff"/>
						</g>
					</g>
				</g>
			</svg>
								</span>
								<span class="pop-trip-grpavil-txt">Group discount Available</span>
							</div>
							<div class="popular-trip-discount">
								<span class="discount-offer"><span>30%</span> Off</span>
							</div>
							<div class="popular-trip-budget">
								<span class="price-holder">
									<span class="striked-price">$4000</span>
									<span class="actual-price">$3000</span>
								</span>
							</div>
						</figure>
						<div class="popular-trip-detail-wrap">
							<h2 class="popular-trip-title"><a href="#">Upper Mustang Trek</a></h2>
							<div class="popular-trip-desti">
								<span class="popular-trip-loc">
									<i>
										<svg xmlns="http://www.w3.org/2000/svg" width="11.213" height="15.81" viewBox="0 0 11.213 15.81">
			  <path id="Path_23393" data-name="Path 23393" d="M5.607,223.81c1.924-2.5,5.607-7.787,5.607-10.2a5.607,5.607,0,0,0-11.213,0C0,216.025,3.682,221.31,5.607,223.81Zm0-13.318a2.492,2.492,0,1,1-2.492,2.492A2.492,2.492,0,0,1,5.607,210.492Zm0,0" transform="translate(0 -208)" opacity="0.8"/>
			</svg>
									</i>
									<span><a href="#">Everest Region, Nepal</a></span>
								</span>
								<span class="popular-trip-dur">
									<i>
										<svg xmlns="http://www.w3.org/2000/svg" width="17.332" height="15.61" viewBox="0 0 17.332 15.61">
			  <g id="Group_773" data-name="Group 773" transform="translate(283.072 34.13)">
				<path id="Path_23383" data-name="Path 23383" d="M-283.057-26.176h.1c.466,0,.931,0,1.4,0,.084,0,.108-.024.1-.106-.006-.156,0-.313,0-.469a5.348,5.348,0,0,1,.066-.675,5.726,5.726,0,0,1,.162-.812,5.1,5.1,0,0,1,.17-.57,9.17,9.17,0,0,1,.383-.946,10.522,10.522,0,0,1,.573-.96c.109-.169.267-.307.371-.479a3.517,3.517,0,0,1,.5-.564,6.869,6.869,0,0,1,1.136-.97,9.538,9.538,0,0,1,.933-.557,7.427,7.427,0,0,1,1.631-.608c.284-.074.577-.11.867-.162a7.583,7.583,0,0,1,1.49-.072c.178,0,.356.053.534.062a2.673,2.673,0,0,1,.523.083c.147.038.3.056.445.1.255.07.511.138.759.228a6.434,6.434,0,0,1,1.22.569c.288.179.571.366.851.556a2.341,2.341,0,0,1,.319.259c.3.291.589.592.888.882a4.993,4.993,0,0,1,.64.85,6.611,6.611,0,0,1,.71,1.367c.065.175.121.352.178.53s.118.348.158.526c.054.242.09.487.133.731.024.14.045.281.067.422a.69.69,0,0,1,.008.1c0,.244.005.488,0,.731s-.015.5-.04.745a4.775,4.775,0,0,1-.095.5c-.04.191-.072.385-.128.572-.094.312-.191.625-.313.926a7.445,7.445,0,0,1-.43.9c-.173.3-.38.584-.579.87a8.045,8.045,0,0,1-1.2,1.26,5.842,5.842,0,0,1-.975.687,8.607,8.607,0,0,1-1.083.552,11.214,11.214,0,0,1-1.087.36c-.19.058-.386.1-.58.137-.121.025-.245.037-.368.052a12.316,12.316,0,0,1-1.57.034,3.994,3.994,0,0,1-.553-.065c-.166-.024-.33-.053-.5-.082a1.745,1.745,0,0,1-.21-.043c-.339-.1-.684-.189-1.013-.317a7,7,0,0,1-1.335-.673c-.2-.136-.417-.263-.609-.415a6.9,6.9,0,0,1-.566-.517.488.488,0,0,1-.128-.331.935.935,0,0,1,.1-.457.465.465,0,0,1,.3-.223.987.987,0,0,1,.478-.059.318.318,0,0,1,.139.073c.239.185.469.381.713.559a5.9,5.9,0,0,0,1.444.766,5.073,5.073,0,0,0,.484.169c.24.062.485.1.727.154a1.805,1.805,0,0,0,.2.037c.173.015.346.033.52.036.3.006.6.01.9,0a3.421,3.421,0,0,0,.562-.068c.337-.069.676-.139,1-.239a6.571,6.571,0,0,0,.783-.32,5.854,5.854,0,0,0,1.08-.663,5.389,5.389,0,0,0,.588-.533,8.013,8.013,0,0,0,.675-.738,5.518,5.518,0,0,0,.749-1.274,9.733,9.733,0,0,0,.366-1.107,4.926,4.926,0,0,0,.142-.833c.025-.269.008-.542.014-.814a4.716,4.716,0,0,0-.07-.815,5.8,5.8,0,0,0-.281-1.12,5.311,5.311,0,0,0-.548-1.147,9.019,9.019,0,0,0-.645-.914,9.267,9.267,0,0,0-.824-.788,3.354,3.354,0,0,0-.425-.321,5.664,5.664,0,0,0-1.048-.581c-.244-.093-.484-.2-.732-.275a6.877,6.877,0,0,0-.688-.161c-.212-.043-.427-.074-.641-.109a.528.528,0,0,0-.084,0c-.169,0-.338,0-.506,0a5.882,5.882,0,0,0-1.177.1,6.79,6.79,0,0,0-1.016.274,6.575,6.575,0,0,0-1.627.856,6.252,6.252,0,0,0-1.032.948,6.855,6.855,0,0,0-.644.847,4.657,4.657,0,0,0-.519,1.017c-.112.323-.227.647-.307.979a3.45,3.45,0,0,0-.13.91,4.4,4.4,0,0,1-.036.529c-.008.086.026.1.106.1.463,0,.925,0,1.388,0a.122.122,0,0,1,.08.028c.009.009-.005.051-.019.072q-.28.415-.563.827c-.162.236-.33.468-.489.705-.118.175-.222.359-.339.535-.1.144-.2.281-.3.423-.142.2-.282.41-.423.615-.016.023-.031.047-.048.069-.062.084-.086.083-.142,0-.166-.249-.332-.5-.5-.746-.3-.44-.6-.878-.9-1.318q-.358-.525-.714-1.051c-.031-.045-.063-.09-.094-.134Z" transform="translate(0 0)"/>
				<path id="Path_23384" data-name="Path 23384" d="M150.612,112.52c0,.655,0,1.31,0,1.966a.216.216,0,0,0,.087.178,4.484,4.484,0,0,1,.358.346.227.227,0,0,0,.186.087q1.616,0,3.233,0a.659.659,0,0,1,.622.4.743.743,0,0,1-.516,1.074,1.361,1.361,0,0,1-.323.038q-1.507,0-3.013,0a.248.248,0,0,0-.216.109,1.509,1.509,0,0,1-.765.511,1.444,1.444,0,0,1-1.256-2.555.218.218,0,0,0,.09-.207q0-1.916,0-3.831a.784.784,0,0,1,.741-.732.742.742,0,0,1,.761.544.489.489,0,0,1,.015.127Q150.612,111.547,150.612,112.52Z" transform="translate(-423.686 -141.471)"/>
			  </g>
			</svg>
									</i>
									<span>11 Days</span>
								</span>
							</div>
							<div class="popular-trip-review">
								<div class="rating-rev rating-layout-1 smaller-ver">
									<div class="circle-rating">
										<span class="circle-wrap-outer">
											<span class="single-circle-outer"></span>
											<span class="single-circle-outer"></span>
											<span class="single-circle-outer"></span>
											<span class="single-circle-outer"></span>
											<span class="single-circle-outer"></span>
										</span>
										<div class="circle-inner-rating">
											<span class="circle-wrap" style="width:95%">
												<span class="single-circle"></span>
												<span class="single-circle"></span>
												<span class="single-circle"></span>
												<span class="single-circle"></span>
												<span class="single-circle"></span>
											</span>
										</div>
									</div>
								</div>
								<span class="popular-trip-reviewcount">22 Reviews</span>
							</div>
						</div>
					</div>
				</div>


				<div class="popular-trips-single">
					<div class="popular-trips-single-inner-wrap">
						<figure class="popular-trip-fig">
							<img src="<?php echo esc_url( TBT_FILE_URL . '/images/demo/popular-trip-home-6.jpg' ); ?>" alt="image">
							<div class="popular-trip-group-avil">
								<span class="pop-trip-grpavil-icon">
									<svg xmlns="http://www.w3.org/2000/svg" width="17.492" height="14.72" viewBox="0 0 17.492 14.72">
				<g id="Group_898" data-name="Group 898" transform="translate(-452 -737)">
					<g id="Group_757" data-name="Group 757" transform="translate(12.114)">
						<g id="multiple-users-silhouette" transform="translate(439.886 737)">
							<path id="Path_23387" data-name="Path 23387" d="M10.555,8.875a3.178,3.178,0,0,1,1.479,2.361,2.564,2.564,0,1,0-1.479-2.361ZM8.875,14.127a2.565,2.565,0,1,0-2.566-2.565A2.565,2.565,0,0,0,8.875,14.127Zm1.088.175H7.786A3.289,3.289,0,0,0,4.5,17.587v2.662l.007.042.183.057a14.951,14.951,0,0,0,4.466.72,9.168,9.168,0,0,0,3.9-.732l.171-.087h.018V17.587A3.288,3.288,0,0,0,9.963,14.3Zm4.244-2.648h-2.16a3.162,3.162,0,0,1-.976,2.2,3.9,3.9,0,0,1,2.788,3.735v.82a8.839,8.839,0,0,0,3.443-.723l.171-.087h.018V14.938A3.288,3.288,0,0,0,14.207,11.654Zm-9.834-.175a2.548,2.548,0,0,0,1.364-.4A3.175,3.175,0,0,1,6.931,9.058c0-.048.007-.1.007-.144a2.565,2.565,0,1,0-2.565,2.565Zm2.3,2.377a3.163,3.163,0,0,1-.975-2.19c-.08-.006-.159-.012-.241-.012H3.285A3.288,3.288,0,0,0,0,14.938V17.6l.007.041L.19,17.7a15.4,15.4,0,0,0,3.7.7v-.8A3.9,3.9,0,0,1,6.677,13.856Z" transform="translate(0 -6.348)" fill="#fff"/>
						</g>
					</g>
				</g>
			</svg>
								</span>
								<span class="pop-trip-grpavil-txt">Group discount Available</span>
							</div>
							<div class="popular-trip-discount">
								<span class="discount-offer"><span>30%</span> Off</span>
							</div>
							<div class="popular-trip-budget">
								<span class="price-holder">
									<span class="striked-price">$4000</span>
									<span class="actual-price">$3000</span>
								</span>
							</div>
						</figure>
						<div class="popular-trip-detail-wrap">
							<h2 class="popular-trip-title"><a href="#">Langtang Valley Trek</a></h2>
							<div class="popular-trip-desti">
								<span class="popular-trip-loc">
									<i>
										<svg xmlns="http://www.w3.org/2000/svg" width="11.213" height="15.81" viewBox="0 0 11.213 15.81">
			  <path id="Path_23393" data-name="Path 23393" d="M5.607,223.81c1.924-2.5,5.607-7.787,5.607-10.2a5.607,5.607,0,0,0-11.213,0C0,216.025,3.682,221.31,5.607,223.81Zm0-13.318a2.492,2.492,0,1,1-2.492,2.492A2.492,2.492,0,0,1,5.607,210.492Zm0,0" transform="translate(0 -208)" opacity="0.8"/>
			</svg>
									</i>
									<span><a href="#">Everest Region, Nepal</a></span>
								</span>
								<span class="popular-trip-dur">
									<i>
										<svg xmlns="http://www.w3.org/2000/svg" width="17.332" height="15.61" viewBox="0 0 17.332 15.61">
			  <g id="Group_773" data-name="Group 773" transform="translate(283.072 34.13)">
				<path id="Path_23383" data-name="Path 23383" d="M-283.057-26.176h.1c.466,0,.931,0,1.4,0,.084,0,.108-.024.1-.106-.006-.156,0-.313,0-.469a5.348,5.348,0,0,1,.066-.675,5.726,5.726,0,0,1,.162-.812,5.1,5.1,0,0,1,.17-.57,9.17,9.17,0,0,1,.383-.946,10.522,10.522,0,0,1,.573-.96c.109-.169.267-.307.371-.479a3.517,3.517,0,0,1,.5-.564,6.869,6.869,0,0,1,1.136-.97,9.538,9.538,0,0,1,.933-.557,7.427,7.427,0,0,1,1.631-.608c.284-.074.577-.11.867-.162a7.583,7.583,0,0,1,1.49-.072c.178,0,.356.053.534.062a2.673,2.673,0,0,1,.523.083c.147.038.3.056.445.1.255.07.511.138.759.228a6.434,6.434,0,0,1,1.22.569c.288.179.571.366.851.556a2.341,2.341,0,0,1,.319.259c.3.291.589.592.888.882a4.993,4.993,0,0,1,.64.85,6.611,6.611,0,0,1,.71,1.367c.065.175.121.352.178.53s.118.348.158.526c.054.242.09.487.133.731.024.14.045.281.067.422a.69.69,0,0,1,.008.1c0,.244.005.488,0,.731s-.015.5-.04.745a4.775,4.775,0,0,1-.095.5c-.04.191-.072.385-.128.572-.094.312-.191.625-.313.926a7.445,7.445,0,0,1-.43.9c-.173.3-.38.584-.579.87a8.045,8.045,0,0,1-1.2,1.26,5.842,5.842,0,0,1-.975.687,8.607,8.607,0,0,1-1.083.552,11.214,11.214,0,0,1-1.087.36c-.19.058-.386.1-.58.137-.121.025-.245.037-.368.052a12.316,12.316,0,0,1-1.57.034,3.994,3.994,0,0,1-.553-.065c-.166-.024-.33-.053-.5-.082a1.745,1.745,0,0,1-.21-.043c-.339-.1-.684-.189-1.013-.317a7,7,0,0,1-1.335-.673c-.2-.136-.417-.263-.609-.415a6.9,6.9,0,0,1-.566-.517.488.488,0,0,1-.128-.331.935.935,0,0,1,.1-.457.465.465,0,0,1,.3-.223.987.987,0,0,1,.478-.059.318.318,0,0,1,.139.073c.239.185.469.381.713.559a5.9,5.9,0,0,0,1.444.766,5.073,5.073,0,0,0,.484.169c.24.062.485.1.727.154a1.805,1.805,0,0,0,.2.037c.173.015.346.033.52.036.3.006.6.01.9,0a3.421,3.421,0,0,0,.562-.068c.337-.069.676-.139,1-.239a6.571,6.571,0,0,0,.783-.32,5.854,5.854,0,0,0,1.08-.663,5.389,5.389,0,0,0,.588-.533,8.013,8.013,0,0,0,.675-.738,5.518,5.518,0,0,0,.749-1.274,9.733,9.733,0,0,0,.366-1.107,4.926,4.926,0,0,0,.142-.833c.025-.269.008-.542.014-.814a4.716,4.716,0,0,0-.07-.815,5.8,5.8,0,0,0-.281-1.12,5.311,5.311,0,0,0-.548-1.147,9.019,9.019,0,0,0-.645-.914,9.267,9.267,0,0,0-.824-.788,3.354,3.354,0,0,0-.425-.321,5.664,5.664,0,0,0-1.048-.581c-.244-.093-.484-.2-.732-.275a6.877,6.877,0,0,0-.688-.161c-.212-.043-.427-.074-.641-.109a.528.528,0,0,0-.084,0c-.169,0-.338,0-.506,0a5.882,5.882,0,0,0-1.177.1,6.79,6.79,0,0,0-1.016.274,6.575,6.575,0,0,0-1.627.856,6.252,6.252,0,0,0-1.032.948,6.855,6.855,0,0,0-.644.847,4.657,4.657,0,0,0-.519,1.017c-.112.323-.227.647-.307.979a3.45,3.45,0,0,0-.13.91,4.4,4.4,0,0,1-.036.529c-.008.086.026.1.106.1.463,0,.925,0,1.388,0a.122.122,0,0,1,.08.028c.009.009-.005.051-.019.072q-.28.415-.563.827c-.162.236-.33.468-.489.705-.118.175-.222.359-.339.535-.1.144-.2.281-.3.423-.142.2-.282.41-.423.615-.016.023-.031.047-.048.069-.062.084-.086.083-.142,0-.166-.249-.332-.5-.5-.746-.3-.44-.6-.878-.9-1.318q-.358-.525-.714-1.051c-.031-.045-.063-.09-.094-.134Z" transform="translate(0 0)"/>
				<path id="Path_23384" data-name="Path 23384" d="M150.612,112.52c0,.655,0,1.31,0,1.966a.216.216,0,0,0,.087.178,4.484,4.484,0,0,1,.358.346.227.227,0,0,0,.186.087q1.616,0,3.233,0a.659.659,0,0,1,.622.4.743.743,0,0,1-.516,1.074,1.361,1.361,0,0,1-.323.038q-1.507,0-3.013,0a.248.248,0,0,0-.216.109,1.509,1.509,0,0,1-.765.511,1.444,1.444,0,0,1-1.256-2.555.218.218,0,0,0,.09-.207q0-1.916,0-3.831a.784.784,0,0,1,.741-.732.742.742,0,0,1,.761.544.489.489,0,0,1,.015.127Q150.612,111.547,150.612,112.52Z" transform="translate(-423.686 -141.471)"/>
			  </g>
			</svg>
									</i>
									<span>11 Days</span>
								</span>
							</div>
							<div class="popular-trip-review">
								<div class="rating-rev rating-layout-1 smaller-ver">
									<div class="circle-rating">
										<span class="circle-wrap-outer">
											<span class="single-circle-outer"></span>
											<span class="single-circle-outer"></span>
											<span class="single-circle-outer"></span>
											<span class="single-circle-outer"></span>
											<span class="single-circle-outer"></span>
										</span>
										<div class="circle-inner-rating">
											<span class="circle-wrap" style="width:95%">
												<span class="single-circle"></span>
												<span class="single-circle"></span>
												<span class="single-circle"></span>
												<span class="single-circle"></span>
												<span class="single-circle"></span>
											</span>
										</div>
									</div>
								</div>
								<span class="popular-trip-reviewcount">22 Reviews</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php
		break;

		case 'activity':
		?>
			<div class="container-stretch">
					<div class="activity-category-wrap">
						<div class="activity-category-single">
							<figure>
								<a class="activity-category-image-wrap" href="#">
									<img src="<?php echo esc_url( TBT_FILE_URL . '/images/demo/trip-category-1.jpg' ); ?>" alt="image">
								</a>
								<div class="activity-category-title">
									<h3><a href="#">Luxury <span>(10 Tours)</span></a></h3>
								</div>
							</figure>
						</div>
						<div class="activity-category-single">
							<figure>
								<a class="activity-category-image-wrap" href="#">
									<img src="<?php echo esc_url( TBT_FILE_URL . '/images/demo/trip-category-2.jpg' ); ?>" alt="image">
								</a>
								<div class="activity-category-title">
									<h3><a href="#">Hiking <span>(10 Tours)</span></a></h3>
								</div>
							</figure>
						</div>
						<div class="activity-category-single">
							<figure>
								<a class="activity-category-image-wrap" href="#">
									<img src="<?php echo esc_url( TBT_FILE_URL . '/images/demo/trip-category-3.jpg' ); ?>" alt="image">
								</a>
								<div class="activity-category-title">
									<h3><a href="#">Kayaking <span>(10 Tours)</span></a></h3>
								</div>
							</figure>
						</div>
						<div class="activity-category-single">
							<figure>
								<a class="activity-category-image-wrap" href="#">
									<img src="<?php echo esc_url( TBT_FILE_URL . '/images/demo/trip-category-4.jpg' ); ?>" alt="image">
								</a>
								<div class="activity-category-title">
									<h3><a href="#">Rafting <span>(10 Tours)</span></a></h3>
								</div>
							</figure>
						</div>
						<div class="activity-category-single">
							<figure>
								<a class="activity-category-image-wrap" href="#">
									<img src="<?php echo esc_url( TBT_FILE_URL . '/images/demo/trip-category-5.jpg' ); ?>" alt="image">
								</a>
								<div class="activity-category-title">
									<h3><a href="#">Luxury <span>(10 Tours)</span></a></h3>
								</div>
							</figure>
						</div>
						<div class="activity-category-single">
							<figure>
								<a class="activity-category-image-wrap" href="#">
									<img src="<?php echo esc_url( TBT_FILE_URL . '/images/demo/trip-category-6.jpg' ); ?>" alt="image">
								</a>
								<div class="activity-category-title">
									<h3><a href="#">Climbing <span>(10 Tours)</span></a></h3>
								</div>
							</figure>
						</div>
					</div>
				</div>
		<?php
		break;

		case 'special':
		?>
			<div class="special-offer-slid-wrap">
				<div id="carousel" class="owl-carousel special-offer-slid-sc">
					<div class="item">
						<div class="popular-trips-single">
							<div class="popular-trips-single-inner-wrap">
								<figure class="popular-trip-fig">
									<img src="<?php echo esc_url( TBT_FILE_URL . '/images/demo/popular-trip-home-1.jpg' ); ?>" alt="image">
									<div class="popular-trip-group-avil">
										<span class="pop-trip-grpavil-icon">
											<svg xmlns="http://www.w3.org/2000/svg" width="17.492" height="14.72" viewBox="0 0 17.492 14.72">
				<g id="Group_898" data-name="Group 898" transform="translate(-452 -737)">
					<g id="Group_757" data-name="Group 757" transform="translate(12.114)">
						<g id="multiple-users-silhouette" transform="translate(439.886 737)">
							<path id="Path_23387" data-name="Path 23387" d="M10.555,8.875a3.178,3.178,0,0,1,1.479,2.361,2.564,2.564,0,1,0-1.479-2.361ZM8.875,14.127a2.565,2.565,0,1,0-2.566-2.565A2.565,2.565,0,0,0,8.875,14.127Zm1.088.175H7.786A3.289,3.289,0,0,0,4.5,17.587v2.662l.007.042.183.057a14.951,14.951,0,0,0,4.466.72,9.168,9.168,0,0,0,3.9-.732l.171-.087h.018V17.587A3.288,3.288,0,0,0,9.963,14.3Zm4.244-2.648h-2.16a3.162,3.162,0,0,1-.976,2.2,3.9,3.9,0,0,1,2.788,3.735v.82a8.839,8.839,0,0,0,3.443-.723l.171-.087h.018V14.938A3.288,3.288,0,0,0,14.207,11.654Zm-9.834-.175a2.548,2.548,0,0,0,1.364-.4A3.175,3.175,0,0,1,6.931,9.058c0-.048.007-.1.007-.144a2.565,2.565,0,1,0-2.565,2.565Zm2.3,2.377a3.163,3.163,0,0,1-.975-2.19c-.08-.006-.159-.012-.241-.012H3.285A3.288,3.288,0,0,0,0,14.938V17.6l.007.041L.19,17.7a15.4,15.4,0,0,0,3.7.7v-.8A3.9,3.9,0,0,1,6.677,13.856Z" transform="translate(0 -6.348)" fill="#fff"/>
						</g>
					</g>
				</g>
			</svg>
										</span>
										<span class="pop-trip-grpavil-txt">Group discount Available</span>
									</div>
									<div class="popular-trip-discount">
										<span class="discount-offer"><span>30%</span> Off</span>
									</div>
									<div class="popular-trip-budget">
										<span class="price-holder">
											<span class="striked-price">$4000</span>
											<span class="actual-price">$3000</span>
										</span>
									</div>
								</figure>
								<div class="popular-trip-detail-wrap">
									<h2 class="popular-trip-title"><a href="#">Poon Hill Mountain Trek</a></h2>
									<div class="popular-trip-desti">
										<span class="popular-trip-loc">
											<i>
												<svg xmlns="http://www.w3.org/2000/svg" width="11.213" height="15.81" viewBox="0 0 11.213 15.81">
			  <path id="Path_23393" data-name="Path 23393" d="M5.607,223.81c1.924-2.5,5.607-7.787,5.607-10.2a5.607,5.607,0,0,0-11.213,0C0,216.025,3.682,221.31,5.607,223.81Zm0-13.318a2.492,2.492,0,1,1-2.492,2.492A2.492,2.492,0,0,1,5.607,210.492Zm0,0" transform="translate(0 -208)" opacity="0.8"/>
			</svg>
											</i>
											<span><a href="#">Everest Region, Nepal</a></span>
										</span>
										<span class="popular-trip-dur">
											<i>
												<svg xmlns="http://www.w3.org/2000/svg" width="17.332" height="15.61" viewBox="0 0 17.332 15.61">
			  <g id="Group_773" data-name="Group 773" transform="translate(283.072 34.13)">
				<path id="Path_23383" data-name="Path 23383" d="M-283.057-26.176h.1c.466,0,.931,0,1.4,0,.084,0,.108-.024.1-.106-.006-.156,0-.313,0-.469a5.348,5.348,0,0,1,.066-.675,5.726,5.726,0,0,1,.162-.812,5.1,5.1,0,0,1,.17-.57,9.17,9.17,0,0,1,.383-.946,10.522,10.522,0,0,1,.573-.96c.109-.169.267-.307.371-.479a3.517,3.517,0,0,1,.5-.564,6.869,6.869,0,0,1,1.136-.97,9.538,9.538,0,0,1,.933-.557,7.427,7.427,0,0,1,1.631-.608c.284-.074.577-.11.867-.162a7.583,7.583,0,0,1,1.49-.072c.178,0,.356.053.534.062a2.673,2.673,0,0,1,.523.083c.147.038.3.056.445.1.255.07.511.138.759.228a6.434,6.434,0,0,1,1.22.569c.288.179.571.366.851.556a2.341,2.341,0,0,1,.319.259c.3.291.589.592.888.882a4.993,4.993,0,0,1,.64.85,6.611,6.611,0,0,1,.71,1.367c.065.175.121.352.178.53s.118.348.158.526c.054.242.09.487.133.731.024.14.045.281.067.422a.69.69,0,0,1,.008.1c0,.244.005.488,0,.731s-.015.5-.04.745a4.775,4.775,0,0,1-.095.5c-.04.191-.072.385-.128.572-.094.312-.191.625-.313.926a7.445,7.445,0,0,1-.43.9c-.173.3-.38.584-.579.87a8.045,8.045,0,0,1-1.2,1.26,5.842,5.842,0,0,1-.975.687,8.607,8.607,0,0,1-1.083.552,11.214,11.214,0,0,1-1.087.36c-.19.058-.386.1-.58.137-.121.025-.245.037-.368.052a12.316,12.316,0,0,1-1.57.034,3.994,3.994,0,0,1-.553-.065c-.166-.024-.33-.053-.5-.082a1.745,1.745,0,0,1-.21-.043c-.339-.1-.684-.189-1.013-.317a7,7,0,0,1-1.335-.673c-.2-.136-.417-.263-.609-.415a6.9,6.9,0,0,1-.566-.517.488.488,0,0,1-.128-.331.935.935,0,0,1,.1-.457.465.465,0,0,1,.3-.223.987.987,0,0,1,.478-.059.318.318,0,0,1,.139.073c.239.185.469.381.713.559a5.9,5.9,0,0,0,1.444.766,5.073,5.073,0,0,0,.484.169c.24.062.485.1.727.154a1.805,1.805,0,0,0,.2.037c.173.015.346.033.52.036.3.006.6.01.9,0a3.421,3.421,0,0,0,.562-.068c.337-.069.676-.139,1-.239a6.571,6.571,0,0,0,.783-.32,5.854,5.854,0,0,0,1.08-.663,5.389,5.389,0,0,0,.588-.533,8.013,8.013,0,0,0,.675-.738,5.518,5.518,0,0,0,.749-1.274,9.733,9.733,0,0,0,.366-1.107,4.926,4.926,0,0,0,.142-.833c.025-.269.008-.542.014-.814a4.716,4.716,0,0,0-.07-.815,5.8,5.8,0,0,0-.281-1.12,5.311,5.311,0,0,0-.548-1.147,9.019,9.019,0,0,0-.645-.914,9.267,9.267,0,0,0-.824-.788,3.354,3.354,0,0,0-.425-.321,5.664,5.664,0,0,0-1.048-.581c-.244-.093-.484-.2-.732-.275a6.877,6.877,0,0,0-.688-.161c-.212-.043-.427-.074-.641-.109a.528.528,0,0,0-.084,0c-.169,0-.338,0-.506,0a5.882,5.882,0,0,0-1.177.1,6.79,6.79,0,0,0-1.016.274,6.575,6.575,0,0,0-1.627.856,6.252,6.252,0,0,0-1.032.948,6.855,6.855,0,0,0-.644.847,4.657,4.657,0,0,0-.519,1.017c-.112.323-.227.647-.307.979a3.45,3.45,0,0,0-.13.91,4.4,4.4,0,0,1-.036.529c-.008.086.026.1.106.1.463,0,.925,0,1.388,0a.122.122,0,0,1,.08.028c.009.009-.005.051-.019.072q-.28.415-.563.827c-.162.236-.33.468-.489.705-.118.175-.222.359-.339.535-.1.144-.2.281-.3.423-.142.2-.282.41-.423.615-.016.023-.031.047-.048.069-.062.084-.086.083-.142,0-.166-.249-.332-.5-.5-.746-.3-.44-.6-.878-.9-1.318q-.358-.525-.714-1.051c-.031-.045-.063-.09-.094-.134Z" transform="translate(0 0)"/>
				<path id="Path_23384" data-name="Path 23384" d="M150.612,112.52c0,.655,0,1.31,0,1.966a.216.216,0,0,0,.087.178,4.484,4.484,0,0,1,.358.346.227.227,0,0,0,.186.087q1.616,0,3.233,0a.659.659,0,0,1,.622.4.743.743,0,0,1-.516,1.074,1.361,1.361,0,0,1-.323.038q-1.507,0-3.013,0a.248.248,0,0,0-.216.109,1.509,1.509,0,0,1-.765.511,1.444,1.444,0,0,1-1.256-2.555.218.218,0,0,0,.09-.207q0-1.916,0-3.831a.784.784,0,0,1,.741-.732.742.742,0,0,1,.761.544.489.489,0,0,1,.015.127Q150.612,111.547,150.612,112.52Z" transform="translate(-423.686 -141.471)"/>
			  </g>
			</svg>
											</i>
											<span>11 Days</span>
										</span>
									</div>
									<div class="popular-trip-review">
										<div class="rating-rev rating-layout-1 smaller-ver">
											<div class="circle-rating">
												<span class="circle-wrap-outer">
													<span class="single-circle-outer"></span>
													<span class="single-circle-outer"></span>
													<span class="single-circle-outer"></span>
													<span class="single-circle-outer"></span>
													<span class="single-circle-outer"></span>
												</span>
												<div class="circle-inner-rating">
													<span class="circle-wrap" style="width:95%">
														<span class="single-circle"></span>
														<span class="single-circle"></span>
														<span class="single-circle"></span>
														<span class="single-circle"></span>
														<span class="single-circle"></span>
													</span>
												</div>
											</div>
										</div>
										<span class="popular-trip-reviewcount">22 Reviews</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="item">
						<div class="popular-trips-single">
							<div class="popular-trips-single-inner-wrap">
								<figure class="popular-trip-fig">
									<img src="<?php echo esc_url( TBT_FILE_URL . '/images/demo/popular-trip-home-4.jpg' ); ?>" alt="image">
									<div class="popular-trip-group-avil">
										<span class="pop-trip-grpavil-icon">
											<svg xmlns="http://www.w3.org/2000/svg" width="17.492" height="14.72" viewBox="0 0 17.492 14.72">
				<g id="Group_898" data-name="Group 898" transform="translate(-452 -737)">
					<g id="Group_757" data-name="Group 757" transform="translate(12.114)">
						<g id="multiple-users-silhouette" transform="translate(439.886 737)">
							<path id="Path_23387" data-name="Path 23387" d="M10.555,8.875a3.178,3.178,0,0,1,1.479,2.361,2.564,2.564,0,1,0-1.479-2.361ZM8.875,14.127a2.565,2.565,0,1,0-2.566-2.565A2.565,2.565,0,0,0,8.875,14.127Zm1.088.175H7.786A3.289,3.289,0,0,0,4.5,17.587v2.662l.007.042.183.057a14.951,14.951,0,0,0,4.466.72,9.168,9.168,0,0,0,3.9-.732l.171-.087h.018V17.587A3.288,3.288,0,0,0,9.963,14.3Zm4.244-2.648h-2.16a3.162,3.162,0,0,1-.976,2.2,3.9,3.9,0,0,1,2.788,3.735v.82a8.839,8.839,0,0,0,3.443-.723l.171-.087h.018V14.938A3.288,3.288,0,0,0,14.207,11.654Zm-9.834-.175a2.548,2.548,0,0,0,1.364-.4A3.175,3.175,0,0,1,6.931,9.058c0-.048.007-.1.007-.144a2.565,2.565,0,1,0-2.565,2.565Zm2.3,2.377a3.163,3.163,0,0,1-.975-2.19c-.08-.006-.159-.012-.241-.012H3.285A3.288,3.288,0,0,0,0,14.938V17.6l.007.041L.19,17.7a15.4,15.4,0,0,0,3.7.7v-.8A3.9,3.9,0,0,1,6.677,13.856Z" transform="translate(0 -6.348)" fill="#fff"/>
						</g>
					</g>
				</g>
			</svg>
										</span>
										<span class="pop-trip-grpavil-txt">Group discount Available</span>
									</div>
									<div class="popular-trip-discount">
										<span class="discount-offer"><span>30%</span> Off</span>
									</div>
									<div class="popular-trip-budget">
										<span class="price-holder">
											<span class="striked-price">$4000</span>
											<span class="actual-price">$3000</span>
										</span>
									</div>
								</figure>
								<div class="popular-trip-detail-wrap">
									<h2 class="popular-trip-title"><a href="#">All Nepal experience Tour</a></h2>
									<div class="popular-trip-desti">
										<span class="popular-trip-loc">
											<i>
												<svg xmlns="http://www.w3.org/2000/svg" width="11.213" height="15.81" viewBox="0 0 11.213 15.81">
			  <path id="Path_23393" data-name="Path 23393" d="M5.607,223.81c1.924-2.5,5.607-7.787,5.607-10.2a5.607,5.607,0,0,0-11.213,0C0,216.025,3.682,221.31,5.607,223.81Zm0-13.318a2.492,2.492,0,1,1-2.492,2.492A2.492,2.492,0,0,1,5.607,210.492Zm0,0" transform="translate(0 -208)" opacity="0.8"/>
			</svg>                                            </i>
											<span><a href="#">Everest Region, Nepal</a></span>
										</span>
										<span class="popular-trip-dur">
											<i>
												<svg xmlns="http://www.w3.org/2000/svg" width="17.332" height="15.61" viewBox="0 0 17.332 15.61">
			  <g id="Group_773" data-name="Group 773" transform="translate(283.072 34.13)">
				<path id="Path_23383" data-name="Path 23383" d="M-283.057-26.176h.1c.466,0,.931,0,1.4,0,.084,0,.108-.024.1-.106-.006-.156,0-.313,0-.469a5.348,5.348,0,0,1,.066-.675,5.726,5.726,0,0,1,.162-.812,5.1,5.1,0,0,1,.17-.57,9.17,9.17,0,0,1,.383-.946,10.522,10.522,0,0,1,.573-.96c.109-.169.267-.307.371-.479a3.517,3.517,0,0,1,.5-.564,6.869,6.869,0,0,1,1.136-.97,9.538,9.538,0,0,1,.933-.557,7.427,7.427,0,0,1,1.631-.608c.284-.074.577-.11.867-.162a7.583,7.583,0,0,1,1.49-.072c.178,0,.356.053.534.062a2.673,2.673,0,0,1,.523.083c.147.038.3.056.445.1.255.07.511.138.759.228a6.434,6.434,0,0,1,1.22.569c.288.179.571.366.851.556a2.341,2.341,0,0,1,.319.259c.3.291.589.592.888.882a4.993,4.993,0,0,1,.64.85,6.611,6.611,0,0,1,.71,1.367c.065.175.121.352.178.53s.118.348.158.526c.054.242.09.487.133.731.024.14.045.281.067.422a.69.69,0,0,1,.008.1c0,.244.005.488,0,.731s-.015.5-.04.745a4.775,4.775,0,0,1-.095.5c-.04.191-.072.385-.128.572-.094.312-.191.625-.313.926a7.445,7.445,0,0,1-.43.9c-.173.3-.38.584-.579.87a8.045,8.045,0,0,1-1.2,1.26,5.842,5.842,0,0,1-.975.687,8.607,8.607,0,0,1-1.083.552,11.214,11.214,0,0,1-1.087.36c-.19.058-.386.1-.58.137-.121.025-.245.037-.368.052a12.316,12.316,0,0,1-1.57.034,3.994,3.994,0,0,1-.553-.065c-.166-.024-.33-.053-.5-.082a1.745,1.745,0,0,1-.21-.043c-.339-.1-.684-.189-1.013-.317a7,7,0,0,1-1.335-.673c-.2-.136-.417-.263-.609-.415a6.9,6.9,0,0,1-.566-.517.488.488,0,0,1-.128-.331.935.935,0,0,1,.1-.457.465.465,0,0,1,.3-.223.987.987,0,0,1,.478-.059.318.318,0,0,1,.139.073c.239.185.469.381.713.559a5.9,5.9,0,0,0,1.444.766,5.073,5.073,0,0,0,.484.169c.24.062.485.1.727.154a1.805,1.805,0,0,0,.2.037c.173.015.346.033.52.036.3.006.6.01.9,0a3.421,3.421,0,0,0,.562-.068c.337-.069.676-.139,1-.239a6.571,6.571,0,0,0,.783-.32,5.854,5.854,0,0,0,1.08-.663,5.389,5.389,0,0,0,.588-.533,8.013,8.013,0,0,0,.675-.738,5.518,5.518,0,0,0,.749-1.274,9.733,9.733,0,0,0,.366-1.107,4.926,4.926,0,0,0,.142-.833c.025-.269.008-.542.014-.814a4.716,4.716,0,0,0-.07-.815,5.8,5.8,0,0,0-.281-1.12,5.311,5.311,0,0,0-.548-1.147,9.019,9.019,0,0,0-.645-.914,9.267,9.267,0,0,0-.824-.788,3.354,3.354,0,0,0-.425-.321,5.664,5.664,0,0,0-1.048-.581c-.244-.093-.484-.2-.732-.275a6.877,6.877,0,0,0-.688-.161c-.212-.043-.427-.074-.641-.109a.528.528,0,0,0-.084,0c-.169,0-.338,0-.506,0a5.882,5.882,0,0,0-1.177.1,6.79,6.79,0,0,0-1.016.274,6.575,6.575,0,0,0-1.627.856,6.252,6.252,0,0,0-1.032.948,6.855,6.855,0,0,0-.644.847,4.657,4.657,0,0,0-.519,1.017c-.112.323-.227.647-.307.979a3.45,3.45,0,0,0-.13.91,4.4,4.4,0,0,1-.036.529c-.008.086.026.1.106.1.463,0,.925,0,1.388,0a.122.122,0,0,1,.08.028c.009.009-.005.051-.019.072q-.28.415-.563.827c-.162.236-.33.468-.489.705-.118.175-.222.359-.339.535-.1.144-.2.281-.3.423-.142.2-.282.41-.423.615-.016.023-.031.047-.048.069-.062.084-.086.083-.142,0-.166-.249-.332-.5-.5-.746-.3-.44-.6-.878-.9-1.318q-.358-.525-.714-1.051c-.031-.045-.063-.09-.094-.134Z" transform="translate(0 0)"/>
				<path id="Path_23384" data-name="Path 23384" d="M150.612,112.52c0,.655,0,1.31,0,1.966a.216.216,0,0,0,.087.178,4.484,4.484,0,0,1,.358.346.227.227,0,0,0,.186.087q1.616,0,3.233,0a.659.659,0,0,1,.622.4.743.743,0,0,1-.516,1.074,1.361,1.361,0,0,1-.323.038q-1.507,0-3.013,0a.248.248,0,0,0-.216.109,1.509,1.509,0,0,1-.765.511,1.444,1.444,0,0,1-1.256-2.555.218.218,0,0,0,.09-.207q0-1.916,0-3.831a.784.784,0,0,1,.741-.732.742.742,0,0,1,.761.544.489.489,0,0,1,.015.127Q150.612,111.547,150.612,112.52Z" transform="translate(-423.686 -141.471)"/>
			  </g>
			</svg>
											</i>
											<span>11 Days</span>
										</span>
									</div>
									<div class="popular-trip-review">
										<div class="rating-rev rating-layout-1 smaller-ver">
											<div class="circle-rating">
												<span class="circle-wrap-outer">
													<span class="single-circle-outer"></span>
													<span class="single-circle-outer"></span>
													<span class="single-circle-outer"></span>
													<span class="single-circle-outer"></span>
													<span class="single-circle-outer"></span>
												</span>
												<div class="circle-inner-rating">
													<span class="circle-wrap" style="width:95%">
														<span class="single-circle"></span>
														<span class="single-circle"></span>
														<span class="single-circle"></span>
														<span class="single-circle"></span>
														<span class="single-circle"></span>
													</span>
												</div>
											</div>
										</div>
										<span class="popular-trip-reviewcount">22 Reviews</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="item">
						<div class="popular-trips-single">
							<div class="popular-trips-single-inner-wrap">
								<figure class="popular-trip-fig">
									<img src="<?php echo esc_url( TBT_FILE_URL . '/images/demo/popular-trip-home-6.jpg' ); ?>" alt="image">
									<div class="popular-trip-group-avil">
										<span class="pop-trip-grpavil-icon">
											<svg xmlns="http://www.w3.org/2000/svg" width="17.492" height="14.72" viewBox="0 0 17.492 14.72">
				<g id="Group_898" data-name="Group 898" transform="translate(-452 -737)">
					<g id="Group_757" data-name="Group 757" transform="translate(12.114)">
						<g id="multiple-users-silhouette" transform="translate(439.886 737)">
							<path id="Path_23387" data-name="Path 23387" d="M10.555,8.875a3.178,3.178,0,0,1,1.479,2.361,2.564,2.564,0,1,0-1.479-2.361ZM8.875,14.127a2.565,2.565,0,1,0-2.566-2.565A2.565,2.565,0,0,0,8.875,14.127Zm1.088.175H7.786A3.289,3.289,0,0,0,4.5,17.587v2.662l.007.042.183.057a14.951,14.951,0,0,0,4.466.72,9.168,9.168,0,0,0,3.9-.732l.171-.087h.018V17.587A3.288,3.288,0,0,0,9.963,14.3Zm4.244-2.648h-2.16a3.162,3.162,0,0,1-.976,2.2,3.9,3.9,0,0,1,2.788,3.735v.82a8.839,8.839,0,0,0,3.443-.723l.171-.087h.018V14.938A3.288,3.288,0,0,0,14.207,11.654Zm-9.834-.175a2.548,2.548,0,0,0,1.364-.4A3.175,3.175,0,0,1,6.931,9.058c0-.048.007-.1.007-.144a2.565,2.565,0,1,0-2.565,2.565Zm2.3,2.377a3.163,3.163,0,0,1-.975-2.19c-.08-.006-.159-.012-.241-.012H3.285A3.288,3.288,0,0,0,0,14.938V17.6l.007.041L.19,17.7a15.4,15.4,0,0,0,3.7.7v-.8A3.9,3.9,0,0,1,6.677,13.856Z" transform="translate(0 -6.348)" fill="#fff"/>
						</g>
					</g>
				</g>
			</svg>
										</span>
										<span class="pop-trip-grpavil-txt">Group discount Available</span>
									</div>
									<div class="popular-trip-discount">
										<span class="discount-offer"><span>30%</span> Off</span>
									</div>
									<div class="popular-trip-budget">
										<span class="price-holder">
											<span class="striked-price">$4000</span>
											<span class="actual-price">$3000</span>
										</span>
									</div>
								</figure>
								<div class="popular-trip-detail-wrap">
									<h2 class="popular-trip-title"><a href="#">Bhutan Cultural Tour</a></h2>
									<div class="popular-trip-desti">
										<span class="popular-trip-loc">
											<i>
												<svg xmlns="http://www.w3.org/2000/svg" width="11.213" height="15.81" viewBox="0 0 11.213 15.81">
			  <path id="Path_23393" data-name="Path 23393" d="M5.607,223.81c1.924-2.5,5.607-7.787,5.607-10.2a5.607,5.607,0,0,0-11.213,0C0,216.025,3.682,221.31,5.607,223.81Zm0-13.318a2.492,2.492,0,1,1-2.492,2.492A2.492,2.492,0,0,1,5.607,210.492Zm0,0" transform="translate(0 -208)" opacity="0.8"/>
			</svg>                                            </i>
											<span><a href="#">Everest Region, Nepal</a></span>
										</span>
										<span class="popular-trip-dur">
											<i>
												<svg xmlns="http://www.w3.org/2000/svg" width="17.332" height="15.61" viewBox="0 0 17.332 15.61">
			  <g id="Group_773" data-name="Group 773" transform="translate(283.072 34.13)">
				<path id="Path_23383" data-name="Path 23383" d="M-283.057-26.176h.1c.466,0,.931,0,1.4,0,.084,0,.108-.024.1-.106-.006-.156,0-.313,0-.469a5.348,5.348,0,0,1,.066-.675,5.726,5.726,0,0,1,.162-.812,5.1,5.1,0,0,1,.17-.57,9.17,9.17,0,0,1,.383-.946,10.522,10.522,0,0,1,.573-.96c.109-.169.267-.307.371-.479a3.517,3.517,0,0,1,.5-.564,6.869,6.869,0,0,1,1.136-.97,9.538,9.538,0,0,1,.933-.557,7.427,7.427,0,0,1,1.631-.608c.284-.074.577-.11.867-.162a7.583,7.583,0,0,1,1.49-.072c.178,0,.356.053.534.062a2.673,2.673,0,0,1,.523.083c.147.038.3.056.445.1.255.07.511.138.759.228a6.434,6.434,0,0,1,1.22.569c.288.179.571.366.851.556a2.341,2.341,0,0,1,.319.259c.3.291.589.592.888.882a4.993,4.993,0,0,1,.64.85,6.611,6.611,0,0,1,.71,1.367c.065.175.121.352.178.53s.118.348.158.526c.054.242.09.487.133.731.024.14.045.281.067.422a.69.69,0,0,1,.008.1c0,.244.005.488,0,.731s-.015.5-.04.745a4.775,4.775,0,0,1-.095.5c-.04.191-.072.385-.128.572-.094.312-.191.625-.313.926a7.445,7.445,0,0,1-.43.9c-.173.3-.38.584-.579.87a8.045,8.045,0,0,1-1.2,1.26,5.842,5.842,0,0,1-.975.687,8.607,8.607,0,0,1-1.083.552,11.214,11.214,0,0,1-1.087.36c-.19.058-.386.1-.58.137-.121.025-.245.037-.368.052a12.316,12.316,0,0,1-1.57.034,3.994,3.994,0,0,1-.553-.065c-.166-.024-.33-.053-.5-.082a1.745,1.745,0,0,1-.21-.043c-.339-.1-.684-.189-1.013-.317a7,7,0,0,1-1.335-.673c-.2-.136-.417-.263-.609-.415a6.9,6.9,0,0,1-.566-.517.488.488,0,0,1-.128-.331.935.935,0,0,1,.1-.457.465.465,0,0,1,.3-.223.987.987,0,0,1,.478-.059.318.318,0,0,1,.139.073c.239.185.469.381.713.559a5.9,5.9,0,0,0,1.444.766,5.073,5.073,0,0,0,.484.169c.24.062.485.1.727.154a1.805,1.805,0,0,0,.2.037c.173.015.346.033.52.036.3.006.6.01.9,0a3.421,3.421,0,0,0,.562-.068c.337-.069.676-.139,1-.239a6.571,6.571,0,0,0,.783-.32,5.854,5.854,0,0,0,1.08-.663,5.389,5.389,0,0,0,.588-.533,8.013,8.013,0,0,0,.675-.738,5.518,5.518,0,0,0,.749-1.274,9.733,9.733,0,0,0,.366-1.107,4.926,4.926,0,0,0,.142-.833c.025-.269.008-.542.014-.814a4.716,4.716,0,0,0-.07-.815,5.8,5.8,0,0,0-.281-1.12,5.311,5.311,0,0,0-.548-1.147,9.019,9.019,0,0,0-.645-.914,9.267,9.267,0,0,0-.824-.788,3.354,3.354,0,0,0-.425-.321,5.664,5.664,0,0,0-1.048-.581c-.244-.093-.484-.2-.732-.275a6.877,6.877,0,0,0-.688-.161c-.212-.043-.427-.074-.641-.109a.528.528,0,0,0-.084,0c-.169,0-.338,0-.506,0a5.882,5.882,0,0,0-1.177.1,6.79,6.79,0,0,0-1.016.274,6.575,6.575,0,0,0-1.627.856,6.252,6.252,0,0,0-1.032.948,6.855,6.855,0,0,0-.644.847,4.657,4.657,0,0,0-.519,1.017c-.112.323-.227.647-.307.979a3.45,3.45,0,0,0-.13.91,4.4,4.4,0,0,1-.036.529c-.008.086.026.1.106.1.463,0,.925,0,1.388,0a.122.122,0,0,1,.08.028c.009.009-.005.051-.019.072q-.28.415-.563.827c-.162.236-.33.468-.489.705-.118.175-.222.359-.339.535-.1.144-.2.281-.3.423-.142.2-.282.41-.423.615-.016.023-.031.047-.048.069-.062.084-.086.083-.142,0-.166-.249-.332-.5-.5-.746-.3-.44-.6-.878-.9-1.318q-.358-.525-.714-1.051c-.031-.045-.063-.09-.094-.134Z" transform="translate(0 0)"/>
				<path id="Path_23384" data-name="Path 23384" d="M150.612,112.52c0,.655,0,1.31,0,1.966a.216.216,0,0,0,.087.178,4.484,4.484,0,0,1,.358.346.227.227,0,0,0,.186.087q1.616,0,3.233,0a.659.659,0,0,1,.622.4.743.743,0,0,1-.516,1.074,1.361,1.361,0,0,1-.323.038q-1.507,0-3.013,0a.248.248,0,0,0-.216.109,1.509,1.509,0,0,1-.765.511,1.444,1.444,0,0,1-1.256-2.555.218.218,0,0,0,.09-.207q0-1.916,0-3.831a.784.784,0,0,1,.741-.732.742.742,0,0,1,.761.544.489.489,0,0,1,.015.127Q150.612,111.547,150.612,112.52Z" transform="translate(-423.686 -141.471)"/>
			  </g>
			</svg>
											</i>
											<span>11 Days</span>
										</span>
									</div>
									<div class="popular-trip-review">
										<div class="rating-rev rating-layout-1 smaller-ver">
											<div class="circle-rating">
												<span class="circle-wrap-outer">
													<span class="single-circle-outer"></span>
													<span class="single-circle-outer"></span>
													<span class="single-circle-outer"></span>
													<span class="single-circle-outer"></span>
													<span class="single-circle-outer"></span>
												</span>
												<div class="circle-inner-rating">
													<span class="circle-wrap" style="width:95%">
														<span class="single-circle"></span>
														<span class="single-circle"></span>
														<span class="single-circle"></span>
														<span class="single-circle"></span>
														<span class="single-circle"></span>
													</span>
												</div>
											</div>
										</div>
										<span class="popular-trip-reviewcount">22 Reviews</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="item">
						<div class="popular-trips-single">
							<div class="popular-trips-single-inner-wrap">
								<figure class="popular-trip-fig">
									<img src="<?php echo esc_url( TBT_FILE_URL . '/images/demo/popular-trip-home-1.jpg' ); ?>" alt="image">
									<div class="popular-trip-group-avil">
										<span class="pop-trip-grpavil-icon">
											<svg xmlns="http://www.w3.org/2000/svg" width="17.492" height="14.72" viewBox="0 0 17.492 14.72">
				<g id="Group_898" data-name="Group 898" transform="translate(-452 -737)">
					<g id="Group_757" data-name="Group 757" transform="translate(12.114)">
						<g id="multiple-users-silhouette" transform="translate(439.886 737)">
							<path id="Path_23387" data-name="Path 23387" d="M10.555,8.875a3.178,3.178,0,0,1,1.479,2.361,2.564,2.564,0,1,0-1.479-2.361ZM8.875,14.127a2.565,2.565,0,1,0-2.566-2.565A2.565,2.565,0,0,0,8.875,14.127Zm1.088.175H7.786A3.289,3.289,0,0,0,4.5,17.587v2.662l.007.042.183.057a14.951,14.951,0,0,0,4.466.72,9.168,9.168,0,0,0,3.9-.732l.171-.087h.018V17.587A3.288,3.288,0,0,0,9.963,14.3Zm4.244-2.648h-2.16a3.162,3.162,0,0,1-.976,2.2,3.9,3.9,0,0,1,2.788,3.735v.82a8.839,8.839,0,0,0,3.443-.723l.171-.087h.018V14.938A3.288,3.288,0,0,0,14.207,11.654Zm-9.834-.175a2.548,2.548,0,0,0,1.364-.4A3.175,3.175,0,0,1,6.931,9.058c0-.048.007-.1.007-.144a2.565,2.565,0,1,0-2.565,2.565Zm2.3,2.377a3.163,3.163,0,0,1-.975-2.19c-.08-.006-.159-.012-.241-.012H3.285A3.288,3.288,0,0,0,0,14.938V17.6l.007.041L.19,17.7a15.4,15.4,0,0,0,3.7.7v-.8A3.9,3.9,0,0,1,6.677,13.856Z" transform="translate(0 -6.348)" fill="#fff"/>
						</g>
					</g>
				</g>
			</svg>
										</span>
										<span class="pop-trip-grpavil-txt">Group discount Available</span>
									</div>
									<div class="popular-trip-discount">
										<span class="discount-offer"><span>30%</span> Off</span>
									</div>
									<div class="popular-trip-budget">
										<span class="price-holder">
											<span class="striked-price">$4000</span>
											<span class="actual-price">$3000</span>
										</span>
									</div>
								</figure>
								<div class="popular-trip-detail-wrap">
									<h2 class="popular-trip-title"><a href="#">Tibet Lhasa Temple Tour</a></h2>
									<div class="popular-trip-desti">
										<span class="popular-trip-loc">
											<i>
												<svg xmlns="http://www.w3.org/2000/svg" width="11.213" height="15.81" viewBox="0 0 11.213 15.81">
			  <path id="Path_23393" data-name="Path 23393" d="M5.607,223.81c1.924-2.5,5.607-7.787,5.607-10.2a5.607,5.607,0,0,0-11.213,0C0,216.025,3.682,221.31,5.607,223.81Zm0-13.318a2.492,2.492,0,1,1-2.492,2.492A2.492,2.492,0,0,1,5.607,210.492Zm0,0" transform="translate(0 -208)" opacity="0.8"/>
			</svg>
											</i>
											<span><a href="#">Everest Region, Nepal</a></span>
										</span>
										<span class="popular-trip-dur">
											<i>
												<svg xmlns="http://www.w3.org/2000/svg" width="17.332" height="15.61" viewBox="0 0 17.332 15.61">
			  <g id="Group_773" data-name="Group 773" transform="translate(283.072 34.13)">
				<path id="Path_23383" data-name="Path 23383" d="M-283.057-26.176h.1c.466,0,.931,0,1.4,0,.084,0,.108-.024.1-.106-.006-.156,0-.313,0-.469a5.348,5.348,0,0,1,.066-.675,5.726,5.726,0,0,1,.162-.812,5.1,5.1,0,0,1,.17-.57,9.17,9.17,0,0,1,.383-.946,10.522,10.522,0,0,1,.573-.96c.109-.169.267-.307.371-.479a3.517,3.517,0,0,1,.5-.564,6.869,6.869,0,0,1,1.136-.97,9.538,9.538,0,0,1,.933-.557,7.427,7.427,0,0,1,1.631-.608c.284-.074.577-.11.867-.162a7.583,7.583,0,0,1,1.49-.072c.178,0,.356.053.534.062a2.673,2.673,0,0,1,.523.083c.147.038.3.056.445.1.255.07.511.138.759.228a6.434,6.434,0,0,1,1.22.569c.288.179.571.366.851.556a2.341,2.341,0,0,1,.319.259c.3.291.589.592.888.882a4.993,4.993,0,0,1,.64.85,6.611,6.611,0,0,1,.71,1.367c.065.175.121.352.178.53s.118.348.158.526c.054.242.09.487.133.731.024.14.045.281.067.422a.69.69,0,0,1,.008.1c0,.244.005.488,0,.731s-.015.5-.04.745a4.775,4.775,0,0,1-.095.5c-.04.191-.072.385-.128.572-.094.312-.191.625-.313.926a7.445,7.445,0,0,1-.43.9c-.173.3-.38.584-.579.87a8.045,8.045,0,0,1-1.2,1.26,5.842,5.842,0,0,1-.975.687,8.607,8.607,0,0,1-1.083.552,11.214,11.214,0,0,1-1.087.36c-.19.058-.386.1-.58.137-.121.025-.245.037-.368.052a12.316,12.316,0,0,1-1.57.034,3.994,3.994,0,0,1-.553-.065c-.166-.024-.33-.053-.5-.082a1.745,1.745,0,0,1-.21-.043c-.339-.1-.684-.189-1.013-.317a7,7,0,0,1-1.335-.673c-.2-.136-.417-.263-.609-.415a6.9,6.9,0,0,1-.566-.517.488.488,0,0,1-.128-.331.935.935,0,0,1,.1-.457.465.465,0,0,1,.3-.223.987.987,0,0,1,.478-.059.318.318,0,0,1,.139.073c.239.185.469.381.713.559a5.9,5.9,0,0,0,1.444.766,5.073,5.073,0,0,0,.484.169c.24.062.485.1.727.154a1.805,1.805,0,0,0,.2.037c.173.015.346.033.52.036.3.006.6.01.9,0a3.421,3.421,0,0,0,.562-.068c.337-.069.676-.139,1-.239a6.571,6.571,0,0,0,.783-.32,5.854,5.854,0,0,0,1.08-.663,5.389,5.389,0,0,0,.588-.533,8.013,8.013,0,0,0,.675-.738,5.518,5.518,0,0,0,.749-1.274,9.733,9.733,0,0,0,.366-1.107,4.926,4.926,0,0,0,.142-.833c.025-.269.008-.542.014-.814a4.716,4.716,0,0,0-.07-.815,5.8,5.8,0,0,0-.281-1.12,5.311,5.311,0,0,0-.548-1.147,9.019,9.019,0,0,0-.645-.914,9.267,9.267,0,0,0-.824-.788,3.354,3.354,0,0,0-.425-.321,5.664,5.664,0,0,0-1.048-.581c-.244-.093-.484-.2-.732-.275a6.877,6.877,0,0,0-.688-.161c-.212-.043-.427-.074-.641-.109a.528.528,0,0,0-.084,0c-.169,0-.338,0-.506,0a5.882,5.882,0,0,0-1.177.1,6.79,6.79,0,0,0-1.016.274,6.575,6.575,0,0,0-1.627.856,6.252,6.252,0,0,0-1.032.948,6.855,6.855,0,0,0-.644.847,4.657,4.657,0,0,0-.519,1.017c-.112.323-.227.647-.307.979a3.45,3.45,0,0,0-.13.91,4.4,4.4,0,0,1-.036.529c-.008.086.026.1.106.1.463,0,.925,0,1.388,0a.122.122,0,0,1,.08.028c.009.009-.005.051-.019.072q-.28.415-.563.827c-.162.236-.33.468-.489.705-.118.175-.222.359-.339.535-.1.144-.2.281-.3.423-.142.2-.282.41-.423.615-.016.023-.031.047-.048.069-.062.084-.086.083-.142,0-.166-.249-.332-.5-.5-.746-.3-.44-.6-.878-.9-1.318q-.358-.525-.714-1.051c-.031-.045-.063-.09-.094-.134Z" transform="translate(0 0)"/>
				<path id="Path_23384" data-name="Path 23384" d="M150.612,112.52c0,.655,0,1.31,0,1.966a.216.216,0,0,0,.087.178,4.484,4.484,0,0,1,.358.346.227.227,0,0,0,.186.087q1.616,0,3.233,0a.659.659,0,0,1,.622.4.743.743,0,0,1-.516,1.074,1.361,1.361,0,0,1-.323.038q-1.507,0-3.013,0a.248.248,0,0,0-.216.109,1.509,1.509,0,0,1-.765.511,1.444,1.444,0,0,1-1.256-2.555.218.218,0,0,0,.09-.207q0-1.916,0-3.831a.784.784,0,0,1,.741-.732.742.742,0,0,1,.761.544.489.489,0,0,1,.015.127Q150.612,111.547,150.612,112.52Z" transform="translate(-423.686 -141.471)"/>
			  </g>
			</svg>
											</i>
											<span>11 Days</span>
										</span>
									</div>
									<div class="popular-trip-review">
										<div class="rating-rev rating-layout-1 smaller-ver">
											<div class="circle-rating">
												<span class="circle-wrap-outer">
													<span class="single-circle-outer"></span>
													<span class="single-circle-outer"></span>
													<span class="single-circle-outer"></span>
													<span class="single-circle-outer"></span>
													<span class="single-circle-outer"></span>
												</span>
												<div class="circle-inner-rating">
													<span class="circle-wrap" style="width:95%">
														<span class="single-circle"></span>
														<span class="single-circle"></span>
														<span class="single-circle"></span>
														<span class="single-circle"></span>
														<span class="single-circle"></span>
													</span>
												</div>
											</div>
										</div>
										<span class="popular-trip-reviewcount">22 Reviews</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php
		break;

		case 'blog':
		?>
			<section id="blog_section" class="our-blog">
				<div class="our-blog-top-wrap">
					<div class="container">
						<div class="section-content-wrap algnlft">
							<h2 class="section-title">From Our Blog</h2>
							<div class="section-desc">
								<p>The origin of the word travel is most likely lost to history. The term travel may originate from the Old French word travail.</p>
							</div>
						</div>
					</div>
				</div>
				<div class="container">
					<div class="blog-section-main-wrap">
						<div class="blog-single-wrap">
							<figure><img src="<?php echo esc_url( TBT_FILE_URL . '/images/demo/blog-home-1.jpg' ); ?>"></figure>
							<div class="blog-single-content-wrap">
								<div class="cats-links">
									<a href="#">Travel Guide</a>
								</div>
								<header class="entry-header">
									<div class="reading-time">10 min read</div>
									<h3 class="entry-title"><a href="#">Northern Lights Winter Tour in Mount Everest</a></h3>
								</header>
							</div>
						</div>
						<div class="blog-single-wrap">
							<figure><img src="<?php echo esc_url( TBT_FILE_URL . '/images/demo/blog-home-2.jpg' ); ?>"></figure>
							<div class="blog-single-content-wrap">
								<div class="cats-links">
									<a href="#">Travel Guide</a>
								</div>
								<header class="entry-header">
									<div class="reading-time">10 min read</div>
									<h3 class="entry-title"><a href="#">Top 5 family Travel Destinations for 2019</a></h3>
								</header>
							</div>
						</div>
						<div class="blog-single-wrap">
							<figure><img src="<?php echo esc_url( TBT_FILE_URL . '/images/demo/blog-home-3.jpg' ); ?>"></figure>
							<div class="blog-single-content-wrap">
								<div class="cats-links">
									<a href="#">Travel Guide</a>
								</div>
								<header class="entry-header">
									<div class="reading-time">10 min read</div>
									<h3 class="entry-title"><a href="#">Top 5 adventure activities in Upper Mustang</a></h3>
								</header>
							</div>
						</div>
					</div>
				</div>

			</section>
		<?php
		break;

		case 'recommendedandassociated':
		?>
			<div class="clients-logo-wrap">
				<div class="clients-logo-single">
					<figure>
						<a href="#"><img src="<?php echo esc_url( TBT_FILE_URL . '/images/demo/clients-home-1.jpg' ); ?>"></a>
					</figure>
				</div>
				<div class="clients-logo-single">
					<figure>
						<a href="#"><img src="<?php echo esc_url( TBT_FILE_URL . '/images/demo/clients-home-2.jpg' ); ?>"></a>
					</figure>
				</div>
				<div class="clients-logo-single">
					<figure>
						<a href="#"><img src="<?php echo esc_url( TBT_FILE_URL . '/images/demo/clients-home-3.jpg' ); ?>"></a>
					</figure>
				</div>
					<div class="clients-logo-single">
					<figure>
						<a href="#"><img src="<?php echo esc_url( TBT_FILE_URL . '/images/demo/clients-home-3.jpg' ); ?>"></a>
					</figure>
				</div>
				<div class="clients-logo-single">
					<figure>
						<a href="#"><img src="<?php echo esc_url( TBT_FILE_URL . '/images/demo/clients-home-2.jpg' ); ?>"></a>
					</figure>
				</div>
				<div class="clients-logo-single">
					<figure>
						<a href="#"><img src="<?php echo esc_url( TBT_FILE_URL . '/images/demo/clients-home-1.jpg' ); ?>"></a>
					</figure>
				</div>
			</div>
		<?php
		break;
		default:
			# code...
		break;
	}
}
endif;

/**
 * Demot Content for Travel Muni.
 */
if ( ! function_exists( 'tbt_travel_muni_demo_content' ) ) :
	/**
	 * Travel Muni Demo Content
	 *
	 * @param string $section Section Name.
	 */
	function tbt_travel_muni_demo_content( $section ) {
		switch ( $section ) {
			case 'about':
				?>
				<section id="about_section" class="three-cols">
					<div class="container">
						<div class="three-cols-wrap">
							<section id="text-4" class="widget widget_text">
								<h2 class="widget-title" itemprop="name">Handpicked Treks</h2>
								<div class="textwidget">
									<p>Voluptatibus earum neque provident feugiat consequatur, nisi numquam aptent aspernatur proident, imperdiet duis veritatis. Accusamus id, quisquam neque repellat eu pede aspernatur, euismod quia vulputate per aliquam dolorum, minim suscipit! Voluptate cura</p>
								</div>
							</section>
							<section id="text-5" class="widget widget_text">
								<h2 class="widget-title" itemprop="name">Best Price Guaranteed</h2>
								<div class="textwidget">
									<p>Voluptatibus earum neque provident feugiat consequatur, nisi numquam aptent aspernatur proident, imperdiet duis veritatis. Accusamus id, quisquam neque repellat eu pede aspernatur, euismod quia vulputate per aliquam dolorum, minim suscipit! Voluptate cura</p>
								</div>
							</section>
							<section id="text-6" class="widget widget_text">
								<h2 class="widget-title" itemprop="name">No Hidden Charges</h2>
								<div class="textwidget">
									<p>Voluptatibus earum neque provident feugiat consequatur, nisi numquam aptent aspernatur proident, imperdiet duis veritatis. Accusamus id, quisquam neque repellat eu pede aspernatur, euismod quia vulputate per aliquam dolorum, minim suscipit! Voluptate cura</p>
								</div>
							</section>
							<section id="text-7" class="widget widget_text">
								<h2 class="widget-title" itemprop="name">Best Price Guaranteed</h2>
								<div class="textwidget">
									<p>Voluptatibus earum neque provident feugiat consequatur, nisi numquam aptent aspernatur proident, imperdiet duis veritatis. Accusamus id, quisquam neque repellat eu pede aspernatur, euismod quia vulputate per aliquam dolorum, minim suscipit! Voluptate cura</p>
								</div>
							</section>
							<section id="text-8" class="widget widget_text">
								<h2 class="widget-title" itemprop="name">Local Experts</h2>
								<div class="textwidget">
									<p>Voluptatibus earum neque provident feugiat consequatur, nisi numquam aptent aspernatur proident, imperdiet duis veritatis. Accusamus id, quisquam neque repellat eu pede aspernatur, euismod quia vulputate per aliquam dolorum, minim suscipit! Voluptate cura</p>
								</div>
							</section>
							<section id="text-9" class="widget widget_text">
								<h2 class="widget-title" itemprop="name">No Hidden Charges</h2>
								<div class="textwidget">
									<p>Voluptatibus earum neque provident feugiat consequatur, nisi numquam aptent aspernatur proident, imperdiet duis veritatis. Accusamus id, quisquam neque repellat eu pede aspernatur, euismod quia vulputate per aliquam dolorum, minim suscipit! Voluptate cura</p>
								</div>
							</section>
						</div>
					</div>
				</section><!-- .about-section -->
				<?php
				break;

			case 'destination':
				?>
				<div class="container-full">
					<div class="destination-wrap">
						<div class="large-desti-item">
							<div class="desti-single-wrap">
								<div class="desti-single-img-wrap">
									<a href="#"><img src="<?php echo esc_url( TBT_FILE_URL . '/includes/travel-muni/images/demo/gosaikunda-lake.jpg' ); ?>" alt="Gosaikunda Lake"></a>
								</div>
								<div class="desti-single-desc">
									<h3 class="desti-single-title"> <a href="#"><strong>Gosaikunda Lake</strong><span>(13 tours)</span></a></h3>
								</div>
							</div>
						</div><!-- .large-desti-item -->
						<div class="desti-small-wrap">
							<div class="desti-single-wrap">
								<div class="desti-single-img-wrap">
									<a href="#"><img src="<?php echo esc_url( TBT_FILE_URL . '/includes/travel-muni/images/demo/buddha-statue.jpg' ); ?>" alt="Bhutan"></a>
								</div>
								<div class="desti-single-desc">
									<h3 class="desti-single-title"> <a href="#"><strong>Bhutan</strong><span>(13 tours)</span></a></h3>
								</div>
							</div>
							<div class="desti-single-wrap">
								<div class="desti-single-img-wrap">
									<a href="#"><img src="<?php echo esc_url( TBT_FILE_URL . '/includes/travel-muni/images/demo/mountain-climbing.jpg' ); ?>" alt="Everest Region"></a>
								</div>
								<div class="desti-single-desc">
									<h3 class="desti-single-title"> <a href="#"><strong>Everest Region</strong><span>(13 tours)</span></a></h3>
								</div>
							</div>
							<div class="desti-single-wrap">
								<div class="desti-single-img-wrap">
									<a href="#"><img src="<?php echo esc_url( TBT_FILE_URL . '/includes/travel-muni/images/demo/potters-carrying-goods.jpg' ); ?>" alt="Langtang Region"></a>
								</div>
								<div class="desti-single-desc">
									<h3 class="desti-single-title"><a href="#"><strong>Langtang Region</strong><span>(13 tours)</span></a></h3>
								</div>
							</div>
							<div class="desti-single-wrap">
								<div class="desti-single-img-wrap">
									<a href="#"><img src="<?php echo esc_url( TBT_FILE_URL . '/includes/travel-muni/images/demo/tibet-walk.jpg' ); ?>" alt="Tibet"></a>
								</div>
								<div class="desti-single-desc">
									<h3 class="desti-single-title"> <a href="#"><strong>Tibet</strong><span>(13 tours)</span></a></h3>
								</div>
							</div>
							<div class="desti-single-wrap">
								<div class="desti-single-img-wrap">
									<a href="#"><img src="<?php echo esc_url( TBT_FILE_URL . '/includes/travel-muni/images/demo/mountain-climbing.jpg' ); ?>" alt="Gosaikunda Lake"></a>
								</div>
								<div class="desti-single-desc">
									<h3 class="desti-single-title"> <a href="#"><strong>Annapurna Region</strong><span>(13 tours)</span></a></h3>
								</div>
							</div>
							<div class="desti-single-wrap">
								<div class="last-desti-single-item">
									<h4>28+ Top Destinations</h4>
									<div class="btn-book"><a class="btn-primary" href="#">View All</a></div>
								</div>
							</div>
						</div><!-- .desti-small-wrap -->
					</div>
				</div><!-- .container-full -->
				<?php
				break;

			case 'testimonials':
				?>
				<div class="clients-testimonial-wrap">
					<div class="clients-testimon-singl">
						<div class="clients-testi-inner-wrap">
							<h3 class="clients-testi-title">Best Experience Ever!!!</h3>
							<div class="clients-testi-trip">
								Reviewed Tour:<h6>Mardi Trip<span>(7 days ago)</span></h6>
							</div>
							<div class="clients-testi-desc">
								<p>We only had 6 days in Nepal and decided to go for the 3 day trek as recommended by Chhatra which was just great, really easy to organise in advance via email and online booking. We all loved it from the start to the end.</p>
							</div>
							<div class="client-intro-sc">
								<div class="client-dp">
									<img src="<?php echo esc_url( TBT_FILE_URL . '/includes/travel-muni/images/demo/clients-testi-1.png' ); ?>" alt="">
								</div>
								<div class="client-intro-rght">
									<div class="ratingndrev">
										<div class="rating-layout-1 smaller-ver">
											<div class="circle-rating">
												<span class="circle-wrap-outer">
													<span class="single-circle-outer"></span>
													<span class="single-circle-outer"></span>
													<span class="single-circle-outer"></span>
													<span class="single-circle-outer"></span>
													<span class="single-circle-outer"></span>
												</span>
												<div class="circle-inner-rating">
													<span class="circle-wrap" style="width:95%">
														<span class="single-circle"></span>
														<span class="single-circle"></span>
														<span class="single-circle"></span>
														<span class="single-circle"></span>
														<span class="single-circle"></span>
													</span>
												</div>
											</div>
										</div>
									</div>

									<p class="clients-testi-locat"><span>Sagar Shrestha,</span> from Nepal</p>
								</div>
							</div>

							<div class="clients-testi-single-gall">
								<figure><img src="<?php echo esc_url( TBT_FILE_URL . '/includes/travel-muni/images/demo/clients-testi-single-gall-1.png' ); ?>" alt="image"></figure>
								<figure><img src="<?php echo esc_url( TBT_FILE_URL . '/includes/travel-muni/images/demo/clients-testi-single-gall-2.png' ); ?>" alt="image"></figure>
								<figure><img src="<?php echo esc_url( TBT_FILE_URL . '/includes/travel-muni/images/demo/clients-testi-single-gall-3.png' ); ?>" alt="image"></figure>
							</div>
							<div class="clients-testi-single-dateexp">
								<h6>Date of Exerience:<span>December 2018</span></h6>
							</div>
						</div>
					</div>
					<div class="clients-testimon-singl">
						<div class="clients-testi-inner-wrap">
							<h3 class="clients-testi-title">One of the very best!!!</h3>
							<div class="clients-testi-trip">
								Reviewed Tour:<h6>Pokhara Trips<span>(12 days ago)</span></h6>
							</div>
							<div class="clients-testi-desc">
								<p>My partner and I went on this unforgettable trek for 12 days to EBC and had an amazing time. There were issues with the flights on the day we were due to fly to Lukla but the company booked us a helicopter at no extra charge, which meant amazing views.</p>
							</div>
							<div class="client-intro-sc">
								<div class="client-dp">
									<img src="<?php echo esc_url( TBT_FILE_URL . '/includes/travel-muni/images/demo/clients-testi-1.png' ); ?>" alt="">
								</div>
								<div class="client-intro-rght">
									<div class="ratingndrev">
										<div class="rating-layout-1 smaller-ver">
											<div class="circle-rating">
												<span class="circle-wrap-outer">
													<span class="single-circle-outer"></span>
													<span class="single-circle-outer"></span>
													<span class="single-circle-outer"></span>
													<span class="single-circle-outer"></span>
													<span class="single-circle-outer"></span>
												</span>
												<div class="circle-inner-rating">
													<span class="circle-wrap" style="width:95%">
														<span class="single-circle"></span>
														<span class="single-circle"></span>
														<span class="single-circle"></span>
														<span class="single-circle"></span>
														<span class="single-circle"></span>
													</span>
												</div>
											</div>
										</div>
									</div>

									<p class="clients-testi-locat"><span>Sumina Sharma,</span> from Nepal</p>
								</div>
							</div>
							<div class="clients-testi-single-gall">
								<figure><img src="<?php echo esc_url( TBT_FILE_URL . '/includes/travel-muni/images/demo/clients-testi-single-gall-1.png' ); ?>" alt="image"></figure>
								<figure><img src="<?php echo esc_url( TBT_FILE_URL . '/includes/travel-muni/images/demo/clients-testi-single-gall-2.png' ); ?>" alt="image"></figure>
								<figure><img src="<?php echo esc_url( TBT_FILE_URL . '/includes/travel-muni/images/demo/clients-testi-single-gall-3.png' ); ?>" alt="image"></figure>
							</div>
							<div class="clients-testi-single-dateexp">
								<h6>Date of Exerience:<span>March 2019</span></h6>
							</div>
						</div>
					</div>
					<div class="clients-testimon-singl">
						<div class="clients-testi-inner-wrap">
							<h3 class="clients-testi-title">Best Trip of Life!!!</h3>
							<div class="clients-testi-trip">
								Reviewed Tour:<h6>Pokhara Trips<span>(12 days ago)</span></h6>
							</div>
							<div class="clients-testi-desc">
								<p>We only had 6 days in Nepal and decided to go for the 3 day trek as recommended by Chhatra which was just great, really easy to organise in advance via email and online booking. We all loved it from the start to the end.</p>
							</div>
							<div class="client-intro-sc">
								<div class="client-dp">
									<img src="<?php echo esc_url( TBT_FILE_URL . '/includes/travel-muni/images/demo/clients-testi-1.png' ); ?>" alt="">
								</div>
								<div class="client-intro-rght">
									<div class="ratingndrev">
										<div class="rating-layout-1 smaller-ver">
											<div class="circle-rating">
												<span class="circle-wrap-outer">
													<span class="single-circle-outer"></span>
													<span class="single-circle-outer"></span>
													<span class="single-circle-outer"></span>
													<span class="single-circle-outer"></span>
													<span class="single-circle-outer"></span>
												</span>
												<div class="circle-inner-rating">
													<span class="circle-wrap" style="width:95%">
														<span class="single-circle"></span>
														<span class="single-circle"></span>
														<span class="single-circle"></span>
														<span class="single-circle"></span>
														<span class="single-circle"></span>
													</span>
												</div>
											</div>
										</div>
									</div>

									<p class="clients-testi-locat"><span>Sudin Manandhar,</span> from Nepal</p>
								</div>
							</div>
							<div class="clients-testi-single-gall">
								<figure><img src="<?php echo esc_url( TBT_FILE_URL . '/includes/travel-muni/images/demo/clients-testi-single-gall-1.png' ); ?>" alt="image"></figure>
								<figure><img src="<?php echo esc_url( TBT_FILE_URL . '/includes/travel-muni/images/demo/clients-testi-single-gall-2.png' ); ?>" alt="image"></figure>
								<figure><img src="<?php echo esc_url( TBT_FILE_URL . '/includes/travel-muni/images/demo/clients-testi-single-gall-3.png' ); ?>" alt="image"></figure>
							</div>
							<div class="clients-testi-single-dateexp">
								<h6>Date of Exerience:<span>May 2019</span></h6>
							</div>
						</div>
					</div>
				</div>
				<div class="loadmore-btn clients-testi-loadmore">
					<button class="btn-primary load-more">Read More Reviews</button>
				</div>
				<?php
				break;

			case 'popular':
				?>
				<div class="popular-trips-wrap">
					<div class="popular-trips-single">
						<div class="popular-trips-single-inner-wrap">
							<figure class="popular-trip-fig">
								<img src="<?php echo esc_url( TBT_FILE_URL . '/includes/travel-muni/images/demo/popular-trip-home-1.jpg' ); ?>" alt="image">
								<div class="popular-trip-group-avil">
									<span class="pop-trip-grpavil-icon">
										<?php travel_muni_svg_helpers( 'group_discount' ); ?>
									</span>
									<span class="pop-trip-grpavil-txt">Group discount Available</span>
								</div>
								<div class="popular-trip-discount">
									<span class="discount-offer"><span>30%</span> Off</span>
								</div>
								<div class="popular-trip-budget">
									<span class="price-holder">
										<span class="striked-price">$4000</span>
										<span class="actual-price">$3000</span>
									</span>
								</div>
							</figure>
							<div class="popular-trip-detail-wrap">
								<h2 class="popular-trip-title"><a href="#">Island Peak Climbing Via Everest Base Camp</a></h2>
								<div class="popular-trip-desti">
									<span class="popular-trip-loc">
										<i>
											<?php travel_muni_svg_helpers( 'destination' ); ?>
										</i>
										<span><a href="#">Everest Region, Nepal</a></span>
									</span>
									<span class="popular-trip-dur">
										<i>
											<?php travel_muni_svg_helpers( 'duration' ); ?>
										</i>
										<span>11 Days</span>
									</span>
								</div>
								<div class="popular-trip-review">
									<div class="rating-rev rating-layout-1 smaller-ver">
										<div class="circle-rating">
											<span class="circle-wrap-outer">
												<span class="single-circle-outer"></span>
												<span class="single-circle-outer"></span>
												<span class="single-circle-outer"></span>
												<span class="single-circle-outer"></span>
												<span class="single-circle-outer"></span>
											</span>
											<div class="circle-inner-rating">
												<span class="circle-wrap" style="width:95%">
													<span class="single-circle"></span>
													<span class="single-circle"></span>
													<span class="single-circle"></span>
													<span class="single-circle"></span>
													<span class="single-circle"></span>
												</span>
											</div>
										</div>
									</div>
									<span class="popular-trip-reviewcount">22 Reviews</span>
								</div>
							</div>
						</div>
					</div>

					<div class="popular-trips-single">
						<div class="popular-trips-single-inner-wrap">
							<figure class="popular-trip-fig">
								<img src="<?php echo esc_url( TBT_FILE_URL . '/includes/travel-muni/images/demo/popular-trip-home-4.jpg' ); ?>" alt="image">
								<div class="popular-trip-group-avil">
									<span class="pop-trip-grpavil-icon">
										<?php travel_muni_svg_helpers( 'group_discount' ); ?>
									</span>
									<span class="pop-trip-grpavil-txt">Group discount Available</span>
								</div>
								<div class="popular-trip-discount">
									<span class="discount-offer"><span>30%</span> Off</span>
								</div>
								<div class="popular-trip-budget">
									<span class="price-holder">
										<span class="striked-price">$4000</span>
										<span class="actual-price">$3000</span>
									</span>
								</div>
							</figure>
							<div class="popular-trip-detail-wrap">
								<h2 class="popular-trip-title"><a href="#">Kathmandu Pokhara Chitwan Lumbini Tour</a></h2>
								<div class="popular-trip-desti">
									<span class="popular-trip-loc">
										<i>
											<?php travel_muni_svg_helpers( 'destination' ); ?>
										</i>
										<span><a href="#">Everest Region, Nepal</a></span>
									</span>
									<span class="popular-trip-dur">
										<i>
											<?php travel_muni_svg_helpers( 'duration' ); ?>
										</i>
										<span>11 Days</span>
									</span>
								</div>
								<div class="popular-trip-review">
									<div class="rating-rev rating-layout-1 smaller-ver">
										<div class="circle-rating">
											<span class="circle-wrap-outer">
												<span class="single-circle-outer"></span>
												<span class="single-circle-outer"></span>
												<span class="single-circle-outer"></span>
												<span class="single-circle-outer"></span>
												<span class="single-circle-outer"></span>
											</span>
											<div class="circle-inner-rating">
												<span class="circle-wrap" style="width:95%">
													<span class="single-circle"></span>
													<span class="single-circle"></span>
													<span class="single-circle"></span>
													<span class="single-circle"></span>
													<span class="single-circle"></span>
												</span>
											</div>
										</div>
									</div>
									<span class="popular-trip-reviewcount">22 Reviews</span>
								</div>
							</div>
						</div>
					</div>


					<div class="popular-trips-single">
						<div class="popular-trips-single-inner-wrap">
							<figure class="popular-trip-fig">
								<img src="<?php echo esc_url( TBT_FILE_URL . '/includes/travel-muni/images/demo/popular-trip-home-6.jpg' ); ?>" alt="image">
								<div class="popular-trip-group-avil">
									<span class="pop-trip-grpavil-icon">
										<?php travel_muni_svg_helpers( 'group_discount' ); ?>
									</span>
									<span class="pop-trip-grpavil-txt">Group discount Available</span>
								</div>
								<div class="popular-trip-discount">
									<span class="discount-offer"><span>30%</span> Off</span>
								</div>
								<div class="popular-trip-budget">
									<span class="price-holder">
										<span class="striked-price">$4000</span>
										<span class="actual-price">$3000</span>
									</span>
								</div>
							</figure>
							<div class="popular-trip-detail-wrap">
								<h2 class="popular-trip-title"><a href="#">Everest Base Camp &amp; Gokyo Lakes Trek via Cho La Pass</a></h2>
								<div class="popular-trip-desti">
									<span class="popular-trip-loc">
										<i>
											<?php travel_muni_svg_helpers( 'destination' ); ?>
										</i>
										<span><a href="#">Everest Region, Nepal</a></span>
									</span>
									<span class="popular-trip-dur">
										<i>
											<?php travel_muni_svg_helpers( 'duration' ); ?>
										</i>
										<span>11 Days</span>
									</span>
								</div>
								<div class="popular-trip-review">
									<div class="rating-rev rating-layout-1 smaller-ver">
										<div class="circle-rating">
											<span class="circle-wrap-outer">
												<span class="single-circle-outer"></span>
												<span class="single-circle-outer"></span>
												<span class="single-circle-outer"></span>
												<span class="single-circle-outer"></span>
												<span class="single-circle-outer"></span>
											</span>
											<div class="circle-inner-rating">
												<span class="circle-wrap" style="width:95%">
													<span class="single-circle"></span>
													<span class="single-circle"></span>
													<span class="single-circle"></span>
													<span class="single-circle"></span>
													<span class="single-circle"></span>
												</span>
											</div>
										</div>
									</div>
									<span class="popular-trip-reviewcount">22 Reviews</span>
								</div>
							</div>
						</div>
					</div>


					<div class="popular-trips-single">
						<div class="popular-trips-single-inner-wrap">
							<figure class="popular-trip-fig">
								<img src="<?php echo esc_url( TBT_FILE_URL . '/includes/travel-muni/images/demo/popular-trip-home-4.jpg' ); ?>" alt="image">
								<div class="popular-trip-group-avil">
									<span class="pop-trip-grpavil-icon">
										<?php travel_muni_svg_helpers( 'group_discount' ); ?>
									</span>
									<span class="pop-trip-grpavil-txt">Group discount Available</span>
								</div>
								<div class="popular-trip-discount">
									<span class="discount-offer"><span>30%</span> Off</span>
								</div>
								<div class="popular-trip-budget">
									<span class="price-holder">
										<span class="striked-price">$4000</span>
										<span class="actual-price">$3000</span>
									</span>
								</div>
							</figure>
							<div class="popular-trip-detail-wrap">
								<h2 class="popular-trip-title"><a href="#">Ghorepani Poon Hill Mountain Trek (Tadapani - Landruk)</a></h2>
								<div class="popular-trip-desti">
									<span class="popular-trip-loc">
										<i>
											<?php travel_muni_svg_helpers( 'destination' ); ?>
										</i>
										<span><a href="#">Everest Region, Nepal</a></span>
									</span>
									<span class="popular-trip-dur">
										<i>
											<?php travel_muni_svg_helpers( 'duration' ); ?>
										</i>
										<span>11 Days</span>
									</span>
								</div>
								<div class="popular-trip-review">
									<div class="rating-rev rating-layout-1 smaller-ver">
										<div class="circle-rating">
											<span class="circle-wrap-outer">
												<span class="single-circle-outer"></span>
												<span class="single-circle-outer"></span>
												<span class="single-circle-outer"></span>
												<span class="single-circle-outer"></span>
												<span class="single-circle-outer"></span>
											</span>
											<div class="circle-inner-rating">
												<span class="circle-wrap" style="width:95%">
													<span class="single-circle"></span>
													<span class="single-circle"></span>
													<span class="single-circle"></span>
													<span class="single-circle"></span>
													<span class="single-circle"></span>
												</span>
											</div>
										</div>
									</div>
									<span class="popular-trip-reviewcount">22 Reviews</span>
								</div>
							</div>
						</div>
					</div>


					<div class="popular-trips-single">
						<div class="popular-trips-single-inner-wrap">
							<figure class="popular-trip-fig">
								<img src="<?php echo esc_url( TBT_FILE_URL . '/includes/travel-muni/images/demo/popular-trip-home-1.jpg' ); ?>" alt="image">
								<div class="popular-trip-group-avil">
									<span class="pop-trip-grpavil-icon">
										<?php travel_muni_svg_helpers( 'group_discount' ); ?>
									</span>
									<span class="pop-trip-grpavil-txt">Group discount Available</span>
								</div>
								<div class="popular-trip-discount">
									<span class="discount-offer"><span>30%</span> Off</span>
								</div>
								<div class="popular-trip-budget">
									<span class="price-holder">
										<span class="striked-price">$4000</span>
										<span class="actual-price">$3000</span>
									</span>
								</div>
							</figure>
							<div class="popular-trip-detail-wrap">
								<h2 class="popular-trip-title"><a href="#">Upper Mustang Trek</a></h2>
								<div class="popular-trip-desti">
									<span class="popular-trip-loc">
										<i>
											<?php travel_muni_svg_helpers( 'destination' ); ?>
										</i>
										<span><a href="#">Everest Region, Nepal</a></span>
									</span>
									<span class="popular-trip-dur">
										<i>
											<?php travel_muni_svg_helpers( 'duration' ); ?>
										</i>
										<span>11 Days</span>
									</span>
								</div>
								<div class="popular-trip-review">
									<div class="rating-rev rating-layout-1 smaller-ver">
										<div class="circle-rating">
											<span class="circle-wrap-outer">
												<span class="single-circle-outer"></span>
												<span class="single-circle-outer"></span>
												<span class="single-circle-outer"></span>
												<span class="single-circle-outer"></span>
												<span class="single-circle-outer"></span>
											</span>
											<div class="circle-inner-rating">
												<span class="circle-wrap" style="width:95%">
													<span class="single-circle"></span>
													<span class="single-circle"></span>
													<span class="single-circle"></span>
													<span class="single-circle"></span>
													<span class="single-circle"></span>
												</span>
											</div>
										</div>
									</div>
									<span class="popular-trip-reviewcount">22 Reviews</span>
								</div>
							</div>
						</div>
					</div>


					<div class="popular-trips-single">
						<div class="popular-trips-single-inner-wrap">
							<figure class="popular-trip-fig">
								<img src="<?php echo esc_url( TBT_FILE_URL . '/includes/travel-muni/images/demo/popular-trip-home-6.jpg' ); ?>" alt="image">
								<div class="popular-trip-group-avil">
									<span class="pop-trip-grpavil-icon">
										<?php travel_muni_svg_helpers( 'group_discount' ); ?>
									</span>
									<span class="pop-trip-grpavil-txt">Group discount Available</span>
								</div>
								<div class="popular-trip-discount">
									<span class="discount-offer"><span>30%</span> Off</span>
								</div>
								<div class="popular-trip-budget">
									<span class="price-holder">
										<span class="striked-price">$4000</span>
										<span class="actual-price">$3000</span>
									</span>
								</div>
							</figure>
							<div class="popular-trip-detail-wrap">
								<h2 class="popular-trip-title"><a href="#">Langtang Valley Trek</a></h2>
								<div class="popular-trip-desti">
									<span class="popular-trip-loc">
										<i>
											<?php travel_muni_svg_helpers( 'destination' ); ?>
										</i>
										<span><a href="#">Everest Region, Nepal</a></span>
									</span>
									<span class="popular-trip-dur">
										<i>
											<?php travel_muni_svg_helpers( 'duration' ); ?>
										</i>
										<span>11 Days</span>
									</span>
								</div>
								<div class="popular-trip-review">
									<div class="rating-rev rating-layout-1 smaller-ver">
										<div class="circle-rating">
											<span class="circle-wrap-outer">
												<span class="single-circle-outer"></span>
												<span class="single-circle-outer"></span>
												<span class="single-circle-outer"></span>
												<span class="single-circle-outer"></span>
												<span class="single-circle-outer"></span>
											</span>
											<div class="circle-inner-rating">
												<span class="circle-wrap" style="width:95%">
													<span class="single-circle"></span>
													<span class="single-circle"></span>
													<span class="single-circle"></span>
													<span class="single-circle"></span>
													<span class="single-circle"></span>
												</span>
											</div>
										</div>
									</div>
									<span class="popular-trip-reviewcount">22 Reviews</span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php
				break;

			case 'activity':
				?>
				<div class="container-stretch">
					<div class="activity-category-wrap">
						<div class="activity-category-single">
							<figure>
								<a class="activity-category-image-wrap" href="#">
									<img src="<?php echo esc_url( TBT_FILE_URL . '/includes/travel-muni/images/demo/trip-category-1.jpg' ); ?>" alt="image">
								</a>
								<div class="activity-category-title">
									<h3><a href="#">Luxury <span>(10 Tours)</span></a></h3>
								</div>
							</figure>
						</div>
						<div class="activity-category-single">
							<figure>
								<a class="activity-category-image-wrap" href="#">
									<img src="<?php echo esc_url( TBT_FILE_URL . '/includes/travel-muni/images/demo/trip-category-2.jpg' ); ?>" alt="image">
								</a>
								<div class="activity-category-title">
									<h3><a href="#">Hiking <span>(10 Tours)</span></a></h3>
								</div>
							</figure>
						</div>
						<div class="activity-category-single">
							<figure>
								<a class="activity-category-image-wrap" href="#">
									<img src="<?php echo esc_url( TBT_FILE_URL . '/includes/travel-muni/images/demo/trip-category-3.jpg' ); ?>" alt="image">
								</a>
								<div class="activity-category-title">
									<h3><a href="#">Kayaking <span>(10 Tours)</span></a></h3>
								</div>
							</figure>
						</div>
						<div class="activity-category-single">
							<figure>
								<a class="activity-category-image-wrap" href="#">
									<img src="<?php echo esc_url( TBT_FILE_URL . '/includes/travel-muni/images/demo/trip-category-4.jpg' ); ?>" alt="image">
								</a>
								<div class="activity-category-title">
									<h3><a href="#">Rafting <span>(10 Tours)</span></a></h3>
								</div>
							</figure>
						</div>
						<div class="activity-category-single">
							<figure>
								<a class="activity-category-image-wrap" href="#">
									<img src="<?php echo esc_url( TBT_FILE_URL . '/includes/travel-muni/images/demo/trip-category-5.jpg' ); ?>" alt="image">
								</a>
								<div class="activity-category-title">
									<h3><a href="#">Luxury <span>(10 Tours)</span></a></h3>
								</div>
							</figure>
						</div>
						<div class="activity-category-single">
							<figure>
								<a class="activity-category-image-wrap" href="#">
									<img src="<?php echo esc_url( TBT_FILE_URL . '/includes/travel-muni/images/demo/trip-category-6.jpg' ); ?>" alt="image">
								</a>
								<div class="activity-category-title">
									<h3><a href="#">Climbing <span>(10 Tours)</span></a></h3>
								</div>
							</figure>
						</div>
					</div>
				</div>
				<?php
				break;

			case 'special':
				?>
				<div class="special-offer-slid-wrap">
					<div id="carousel" class="owl-carousel special-offer-slid-sc">
						<div class="item">
							<div class="popular-trips-single">
								<div class="popular-trips-single-inner-wrap">
									<figure class="popular-trip-fig">
										<img src="<?php echo esc_url( TBT_FILE_URL . '/includes/travel-muni/images/demo/popular-trip-home-1.jpg' ); ?>" alt="image">
										<div class="popular-trip-group-avil">
											<span class="pop-trip-grpavil-icon">
												<?php travel_muni_svg_helpers( 'group_discount' ); ?>
											</span>
											<span class="pop-trip-grpavil-txt">Group discount Available</span>
										</div>
										<div class="popular-trip-discount">
											<span class="discount-offer"><span>30%</span> Off</span>
										</div>
										<div class="popular-trip-budget">
											<span class="price-holder">
												<span class="striked-price">$4000</span>
												<span class="actual-price">$3000</span>
											</span>
										</div>
									</figure>
									<div class="popular-trip-detail-wrap">
										<h2 class="popular-trip-title"><a href="#">Poon Hill Mountain Trek</a></h2>
										<div class="popular-trip-desti">
											<span class="popular-trip-loc">
												<i>
													<?php travel_muni_svg_helpers( 'destination' ); ?>
												</i>
												<span><a href="#">Everest Region, Nepal</a></span>
											</span>
											<span class="popular-trip-dur">
												<i>
													<?php travel_muni_svg_helpers( 'duration' ); ?>
												</i>
												<span>11 Days</span>
											</span>
										</div>
										<div class="popular-trip-review">
											<div class="rating-rev rating-layout-1 smaller-ver">
												<div class="circle-rating">
													<span class="circle-wrap-outer">
														<span class="single-circle-outer"></span>
														<span class="single-circle-outer"></span>
														<span class="single-circle-outer"></span>
														<span class="single-circle-outer"></span>
														<span class="single-circle-outer"></span>
													</span>
													<div class="circle-inner-rating">
														<span class="circle-wrap" style="width:95%">
															<span class="single-circle"></span>
															<span class="single-circle"></span>
															<span class="single-circle"></span>
															<span class="single-circle"></span>
															<span class="single-circle"></span>
														</span>
													</div>
												</div>
											</div>
											<span class="popular-trip-reviewcount">22 Reviews</span>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="item">
							<div class="popular-trips-single">
								<div class="popular-trips-single-inner-wrap">
									<figure class="popular-trip-fig">
										<img src="<?php echo esc_url( TBT_FILE_URL . '/includes/travel-muni/images/demo/popular-trip-home-4.jpg' ); ?>" alt="image">
										<div class="popular-trip-group-avil">
											<span class="pop-trip-grpavil-icon">
												<?php travel_muni_svg_helpers( 'group_discount' ); ?>
											</span>
											<span class="pop-trip-grpavil-txt">Group discount Available</span>
										</div>
										<div class="popular-trip-discount">
											<span class="discount-offer"><span>30%</span> Off</span>
										</div>
										<div class="popular-trip-budget">
											<span class="price-holder">
												<span class="striked-price">$4000</span>
												<span class="actual-price">$3000</span>
											</span>
										</div>
									</figure>
									<div class="popular-trip-detail-wrap">
										<h2 class="popular-trip-title"><a href="#">All Nepal experience Tour</a></h2>
										<div class="popular-trip-desti">
											<span class="popular-trip-loc">
												<i>
													<?php travel_muni_svg_helpers( 'destination' ); ?>
												</i>
												<span><a href="#">Everest Region, Nepal</a></span>
											</span>
											<span class="popular-trip-dur">
												<i>
													<?php travel_muni_svg_helpers( 'duration' ); ?>
												</i>
												<span>11 Days</span>
											</span>
										</div>
										<div class="popular-trip-review">
											<div class="rating-rev rating-layout-1 smaller-ver">
												<div class="circle-rating">
													<span class="circle-wrap-outer">
														<span class="single-circle-outer"></span>
														<span class="single-circle-outer"></span>
														<span class="single-circle-outer"></span>
														<span class="single-circle-outer"></span>
														<span class="single-circle-outer"></span>
													</span>
													<div class="circle-inner-rating">
														<span class="circle-wrap" style="width:95%">
															<span class="single-circle"></span>
															<span class="single-circle"></span>
															<span class="single-circle"></span>
															<span class="single-circle"></span>
															<span class="single-circle"></span>
														</span>
													</div>
												</div>
											</div>
											<span class="popular-trip-reviewcount">22 Reviews</span>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="item">
							<div class="popular-trips-single">
								<div class="popular-trips-single-inner-wrap">
									<figure class="popular-trip-fig">
										<img src="<?php echo esc_url( TBT_FILE_URL . '/includes/travel-muni/images/demo/popular-trip-home-6.jpg' ); ?>" alt="image">
										<div class="popular-trip-group-avil">
											<span class="pop-trip-grpavil-icon">
												<?php travel_muni_svg_helpers( 'group_discount' ); ?>
											</span>
											<span class="pop-trip-grpavil-txt">Group discount Available</span>
										</div>
										<div class="popular-trip-discount">
											<span class="discount-offer"><span>30%</span> Off</span>
										</div>
										<div class="popular-trip-budget">
											<span class="price-holder">
												<span class="striked-price">$4000</span>
												<span class="actual-price">$3000</span>
											</span>
										</div>
									</figure>
									<div class="popular-trip-detail-wrap">
										<h2 class="popular-trip-title"><a href="#">Bhutan Cultural Tour</a></h2>
										<div class="popular-trip-desti">
											<span class="popular-trip-loc">
												<i>
													<?php travel_muni_svg_helpers( 'destination' ); ?>
												</i>
												<span><a href="#">Everest Region, Nepal</a></span>
											</span>
											<span class="popular-trip-dur">
												<i>
													<?php travel_muni_svg_helpers( 'duration' ); ?>
												</i>
												<span>11 Days</span>
											</span>
										</div>
										<div class="popular-trip-review">
											<div class="rating-rev rating-layout-1 smaller-ver">
												<div class="circle-rating">
													<span class="circle-wrap-outer">
														<span class="single-circle-outer"></span>
														<span class="single-circle-outer"></span>
														<span class="single-circle-outer"></span>
														<span class="single-circle-outer"></span>
														<span class="single-circle-outer"></span>
													</span>
													<div class="circle-inner-rating">
														<span class="circle-wrap" style="width:95%">
															<span class="single-circle"></span>
															<span class="single-circle"></span>
															<span class="single-circle"></span>
															<span class="single-circle"></span>
															<span class="single-circle"></span>
														</span>
													</div>
												</div>
											</div>
											<span class="popular-trip-reviewcount">22 Reviews</span>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="item">
							<div class="popular-trips-single">
								<div class="popular-trips-single-inner-wrap">
									<figure class="popular-trip-fig">
										<img src="<?php echo esc_url( TBT_FILE_URL . '/includes/travel-muni/images/demo/popular-trip-home-1.jpg' ); ?>" alt="image">
										<div class="popular-trip-group-avil">
											<span class="pop-trip-grpavil-icon">
												<?php travel_muni_svg_helpers( 'group_discount' ); ?>
											</span>
											<span class="pop-trip-grpavil-txt">Group discount Available</span>
										</div>
										<div class="popular-trip-discount">
											<span class="discount-offer"><span>30%</span> Off</span>
										</div>
										<div class="popular-trip-budget">
											<span class="price-holder">
												<span class="striked-price">$4000</span>
												<span class="actual-price">$3000</span>
											</span>
										</div>
									</figure>
									<div class="popular-trip-detail-wrap">
										<h2 class="popular-trip-title"><a href="#">Tibet Lhasa Temple Tour</a></h2>
										<div class="popular-trip-desti">
											<span class="popular-trip-loc">
												<i>
													<?php travel_muni_svg_helpers( 'destination' ); ?>
												</i>
												<span><a href="#">Everest Region, Nepal</a></span>
											</span>
											<span class="popular-trip-dur">
												<i>
													<?php travel_muni_svg_helpers( 'duration' ); ?>
												</i>
												<span>11 Days</span>
											</span>
										</div>
										<div class="popular-trip-review">
											<div class="rating-rev rating-layout-1 smaller-ver">
												<div class="circle-rating">
													<span class="circle-wrap-outer">
														<span class="single-circle-outer"></span>
														<span class="single-circle-outer"></span>
														<span class="single-circle-outer"></span>
														<span class="single-circle-outer"></span>
														<span class="single-circle-outer"></span>
													</span>
													<div class="circle-inner-rating">
														<span class="circle-wrap" style="width:95%">
															<span class="single-circle"></span>
															<span class="single-circle"></span>
															<span class="single-circle"></span>
															<span class="single-circle"></span>
															<span class="single-circle"></span>
														</span>
													</div>
												</div>
											</div>
											<span class="popular-trip-reviewcount">22 Reviews</span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php
				break;

			case 'blog':
				?>
				<section id="blog_section" class="our-blog">
					<div class="our-blog-top-wrap">
						<div class="container">
							<div class="section-content-wrap algnlft">
								<h2 class="section-title">From Our Blog</h2>
								<div class="section-desc">
									<p>The origin of the word travel is most likely lost to history. The term travel may originate from the Old French word travail.</p>
								</div>
							</div>
						</div>
					</div>
					<div class="container">
						<div class="blog-section-main-wrap">
							<div class="blog-single-wrap">
								<figure><img src="<?php echo esc_url( TBT_FILE_URL . '/includes/travel-muni/images/demo/blog-home-1.jpg' ); ?>"></figure>
								<div class="blog-single-content-wrap">
									<div class="cats-links">
										<a href="#">Travel Guide</a>
									</div>
									<header class="entry-header">
										<div class="reading-time">10 min read</div>
										<h3 class="entry-title"><a href="#">Northern Lights Winter Tour in Mount Everest</a></h3>
									</header>
								</div>
							</div>
							<div class="blog-single-wrap">
								<figure><img src="<?php echo esc_url( TBT_FILE_URL . '/includes/travel-muni/images/demo/blog-home-2.jpg' ); ?>"></figure>
								<div class="blog-single-content-wrap">
									<div class="cats-links">
										<a href="#">Travel Guide</a>
									</div>
									<header class="entry-header">
										<div class="reading-time">10 min read</div>
										<h3 class="entry-title"><a href="#">Top 5 family Travel Destinations for 2019</a></h3>
									</header>
								</div>
							</div>
							<div class="blog-single-wrap">
								<figure><img src="<?php echo esc_url( TBT_FILE_URL . '/includes/travel-muni/images/demo/blog-home-3.jpg' ); ?>"></figure>
								<div class="blog-single-content-wrap">
									<div class="cats-links">
										<a href="#">Travel Guide</a>
									</div>
									<header class="entry-header">
										<div class="reading-time">10 min read</div>
										<h3 class="entry-title"><a href="#">Top 5 adventure activities in Upper Mustang</a></h3>
									</header>
								</div>
							</div>
						</div>
					</div>

				</section>
				<?php
				break;

			case 'recommendedandassociated':
				?>
				<div class="clients-logo-wrap">
					<div class="clients-logo-single">
						<figure>
							<a href="#"><img src="<?php echo esc_url( TBT_FILE_URL . '/includes/travel-muni/images/demo/clients-home-1.jpg' ); ?>"></a>
						</figure>
					</div>
					<div class="clients-logo-single">
						<figure>
							<a href="#"><img src="<?php echo esc_url( TBT_FILE_URL . '/includes/travel-muni/images/demo/clients-home-2.jpg' ); ?>"></a>
						</figure>
					</div>
					<div class="clients-logo-single">
						<figure>
							<a href="#"><img src="<?php echo esc_url( TBT_FILE_URL . '/includes/travel-muni/images/demo/clients-home-3.jpg' ); ?>"></a>
						</figure>
					</div>
					<div class="clients-logo-single">
						<figure>
							<a href="#"><img src="<?php echo esc_url( TBT_FILE_URL . '/includes/travel-muni/images/demo/clients-home-3.jpg' ); ?>"></a>
						</figure>
					</div>
					<div class="clients-logo-single">
						<figure>
							<a href="#"><img src="<?php echo esc_url( TBT_FILE_URL . '/includes/travel-muni/images/demo/clients-home-2.jpg' ); ?>"></a>
						</figure>
					</div>
					<div class="clients-logo-single">
						<figure>
							<a href="#"><img src="<?php echo esc_url( TBT_FILE_URL . '/includes/travel-muni/images/demo/clients-home-1.jpg' ); ?>"></a>
						</figure>
					</div>
				</div>
				<?php
				break;
			default:
				// code...
				break;
		}
	}
endif;
