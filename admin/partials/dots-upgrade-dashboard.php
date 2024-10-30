<?php
/**
 * Handles free plugin user dashboard
 * 
 * @package Woo_Hide_Shipping_Methods
 * @since   1.4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( plugin_dir_path( __FILE__ ) . 'header/plugin-header.php' );

// Get product details from Freemius via API
$annual_plugin_price = '';
$monthly_plugin_price = '';
$plugin_details = array(
    'product_id' => 43546,
);

$api_url = add_query_arg(wp_rand(), '', WHSM_STORE_URL . 'wp-json/dotstore-product-fs-data/v2/dotstore-product-fs-data');
$final_api_url = add_query_arg($plugin_details, $api_url);

if ( function_exists( 'vip_safe_wp_remote_get' ) ) {
    $api_response = vip_safe_wp_remote_get( $final_api_url, 3, 1, 20 );
} else {
    $api_response = wp_remote_get( $final_api_url ); // phpcs:ignore
}

if ( ( !is_wp_error($api_response)) && (200 === wp_remote_retrieve_response_code( $api_response ) ) ) {
	$api_response_body = wp_remote_retrieve_body($api_response);
	$plugin_pricing = json_decode( $api_response_body, true );

	if ( isset( $plugin_pricing ) && ! empty( $plugin_pricing ) ) {
		$first_element = reset( $plugin_pricing );
        if ( ! empty( $first_element['price_data'] ) ) {
            $first_price = reset( $first_element['price_data'] )['annual_price'];
        } else {
            $first_price = "0";
        }

        if( "0" !== $first_price ){
        	$annual_plugin_price = $first_price;
        	$monthly_plugin_price = round( intval( $first_price  ) / 12 );
        }
	}
}

// Set plugin key features content
$plugin_key_features = array(
    array(
        'title' => esc_html__( 'Hide Any Non-compatible Shipping', 'woo-hide-shipping-methods' ),
        'description' => esc_html__( 'Enter the values for any shipping method in the designated field to hide specific shipping options, ensuring seamless integration with popular shipping plugins.', 'woo-hide-shipping-methods' ),
        'popup_image' => esc_url( WHSM_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-one-img.png' ),
        'popup_content' => array(
        	esc_html__( 'Enter the values for any shipping method in the designated field to hide specific shipping options, ensuring seamless integration with popular shipping plugins.', 'woo-hide-shipping-methods' )
        ),
        'popup_examples' => array(
            esc_html__( 'If you use an incompatible third-party shipping plugin, you can easily find the shipping value (e.g., "flat_rate:7") from the front end and paste it into the specified field to hide it.', 'woo-hide-shipping-methods' ),
        )
    ),
    array(
        'title' => esc_html__( 'Time-Based Shipping Availability', 'woo-hide-shipping-methods' ),
        'description' => esc_html__( 'Control the display of shipping methods by setting specific start and end dates, days of the week, and time frames for hiding them.', 'woo-hide-shipping-methods' ),
        'popup_image' => esc_url( WHSM_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-two-img.png' ),
        'popup_content' => array(
        	esc_html__( 'Customize shipping availability and fully control when shipping methods are displayed by setting specific start and end dates, days of the week, and time frames.', 'woo-hide-shipping-methods' ),
        ),
        'popup_examples' => array(
            esc_html__( 'Show shipping methods for seasonal promotions or limited-time offers only on specific dates.', 'woo-hide-shipping-methods' ),
            esc_html__( 'Hide shipping methods on weekends or outside of business hours when unavailable.', 'woo-hide-shipping-methods' )
        )
    ),
    array(
        'title' => esc_html__( 'Location-Specific Hide Shipping', 'woo-hide-shipping-methods' ),
        'description' => esc_html__( 'Enhance shipping control by using precise location options like city, state, postcode, and zone to hide shipping methods for specific areas.', 'woo-hide-shipping-methods' ),
        'popup_image' => esc_url( WHSM_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-three-img.png' ),
        'popup_content' => array(
        	esc_html__( 'Easily hide shipping methods for specific locations by utilizing precise options like city, state, postcode, and zone.', 'woo-hide-shipping-methods' ),
        ),
        'popup_examples' => array(
            esc_html__( 'Hide shipping methods for a specific city, ensuring accurate delivery options for local customers.', 'woo-hide-shipping-methods' ),
            esc_html__( 'Restrict shipping methods based on postcode to provide custom shipping choices for different areas.', 'woo-hide-shipping-methods' )
        )
    ),
    array(
        'title' => esc_html__( 'Attribute-Specific Hide Shipping', 'woo-hide-shipping-methods' ),
        'description' => esc_html__( 'Easily hide shipping methods based on product attributes like color and size for a customized shipping experience.', 'woo-hide-shipping-methods' ),
        'popup_image' => esc_url( WHSM_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-four-img.png' ),
        'popup_content' => array(
        	esc_html__( 'Easily hide shipping methods based on product attributes like color and size, providing customized shipping options.', 'woo-hide-shipping-methods' ),
        ),
        'popup_examples' => array(
            esc_html__( 'Hide specific shipping methods for products with the attribute "Color = Red" offering alternative shipping choices.', 'woo-hide-shipping-methods' ),
            esc_html__( 'Restrict shipping methods for products with the attribute "Size = Medium" providing relevant shipping choices.', 'woo-hide-shipping-methods' )
        )
    ),
    array(
        'title' => esc_html__( 'User Role-Based Hide Shipping', 'woo-hide-shipping-methods' ),
        'description' => esc_html__( 'Easily hide shipping methods based on various user roles, including consumer, shop manager, customers, and more.', 'woo-hide-shipping-methods' ),
        'popup_image' => esc_url( WHSM_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-five-img.png' ),
        'popup_content' => array(
        	esc_html__( 'Easily hide shipping methods based on user roles like consumers, sellers, and shop managers to enhance the shipping experience for everyone.', 'woo-hide-shipping-methods' ),
        ),
        'popup_examples' => array(
            esc_html__( 'Restrict certain shipping methods for regular consumers while offering exclusive options for shop managers.', 'woo-hide-shipping-methods' ),
            esc_html__( 'Offer premium shipping options to customers, such as express delivery or free shipping, while hiding normal shipping methods.', 'woo-hide-shipping-methods' )
        )
    ),
    array(
        'title' => esc_html__( 'Cart Weight-based Shipping', 'woo-hide-shipping-methods' ),
        'description' => esc_html__( 'Take control of shipping methods by setting advanced cart-specific conditions like weight, length, and width to hide certain shipping options.', 'woo-hide-shipping-methods' ),
        'popup_image' => esc_url( WHSM_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-six-img.png' ),
        'popup_content' => array(
        	esc_html__( 'Take advantage of advanced cart-specific conditions like weight, length, width, and more to tailor shipping options to your customers\' needs.', 'woo-hide-shipping-methods' ),
        ),
        'popup_examples' => array(
            esc_html__( 'Hide certain shipping methods for oversized items based on the cart\'s weight and dimensions.', 'woo-hide-shipping-methods' ),
            esc_html__( 'Customize shipping options based on the cart\'s weight, ensuring accurate shipping choices for different order sizes.', 'woo-hide-shipping-methods' )
        )
    ),
    array(
        'title' => esc_html__( 'Hide Shipping Based on Payment', 'woo-hide-shipping-methods' ),
        'description' => esc_html__( 'Hide shipping methods based on the payment method selected by customers during checkout, optimizing the shipping experience.', 'woo-hide-shipping-methods' ),
        'popup_image' => esc_url( WHSM_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-seven-img.png' ),
        'popup_content' => array(
        	esc_html__( 'Hide shipping methods based on the payment method chosen by customers during checkout, ensuring a seamless shopping experience.', 'woo-hide-shipping-methods' ),
        ),
        'popup_examples' => array(
            esc_html__( 'Hide expensive shipping methods like international shipping when customers choose the "Cash on Delivery" payment option.', 'woo-hide-shipping-methods' ),
            esc_html__( 'Offer free shipping to customers who choose credit card payments, and encourage them to use this convenient and secure payment method.', 'woo-hide-shipping-methods' )
        )
    ),
    array(
        'title' => esc_html__( 'Advanced Hide Shipping Options', 'woo-hide-shipping-methods' ),
        'description' => esc_html__( 'Unlock advanced hide shipping options to create customized ranges based on the specific product, category, cart quantity, weight, subtotal, etc.', 'woo-hide-shipping-methods' ),
        'popup_image' => esc_url( WHSM_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-eight-img.png' ),
        'popup_content' => array(
        	esc_html__( 'Utilize advanced hide shipping rules to define specific ranges based on product, category, cart quantity, weight, or subtotal.', 'woo-hide-shipping-methods' ),
        ),
        'popup_examples' => array(
            esc_html__( 'Customize shipping options based on the cart\'s subtotal, offering free shipping for orders above a certain value.', 'woo-hide-shipping-methods' ),
            esc_html__( 'Hide specific shipping methods for products in the "Weak Items" category based on their weight.', 'woo-hide-shipping-methods' )
        )
    ),
    array(
        'title' => esc_html__( 'One Click Import & Export', 'woo-hide-shipping-methods' ),
        'description' => esc_html__( 'Easily import and export the hide shipping rules for seamless management and sharing of the data across your WooCommerce store.', 'woo-hide-shipping-methods' ),
        'popup_image' => esc_url( WHSM_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-nine-img.png' ),
        'popup_content' => array(
        	esc_html__( 'Effortlessly import and export hidden shipping rules for smooth data management and sharing in your WooCommerce store.', 'woo-hide-shipping-methods' ),
        ),
        'popup_examples' => array(
            esc_html__( 'Import hide shipping rules from a staging site to a production site with just one click, saving time and effort.', 'woo-hide-shipping-methods' ),
            esc_html__( 'Export hide shipping rules for backup purposes or to share them with colleagues or clients.', 'woo-hide-shipping-methods' )
        )
    ),
);
?>
	<div class="wcpfc-section-left">
		<div class="dotstore-upgrade-dashboard">
			<div class="premium-benefits-section">
				<h2><?php esc_html_e( 'Upgrade to Unlock Premium Features', 'woo-hide-shipping-methods' ); ?></h2>
				<p><?php esc_html_e( 'Upgrade to premium for advanced features and optimized shipping options that delight customers!', 'woo-hide-shipping-methods' ); ?></p>
			</div>
			<div class="premium-plugin-details">
				<div class="premium-key-fetures">
					<h3><?php esc_html_e( 'Discover Our Top Key Features', 'woo-hide-shipping-methods' ) ?></h3>
					<ul>
						<?php 
						if ( isset( $plugin_key_features ) && ! empty( $plugin_key_features ) ) {
							foreach( $plugin_key_features as $key_feature ) {
								?>
								<li>
									<h4><?php echo esc_html( $key_feature['title'] ); ?><span class="premium-feature-popup"></span></h4>
									<p><?php echo esc_html( $key_feature['description'] ); ?></p>
									<div class="feature-explanation-popup-main">
										<div class="feature-explanation-popup-outer">
											<div class="feature-explanation-popup-inner">
												<div class="feature-explanation-popup">
													<span class="dashicons dashicons-no-alt popup-close-btn" title="<?php esc_attr_e('Close', 'woo-hide-shipping-methods'); ?>"></span>
													<div class="popup-body-content">
														<div class="feature-content">
															<h4><?php echo esc_html( $key_feature['title'] ); ?></h4>
															<?php 
															if ( isset( $key_feature['popup_content'] ) && ! empty( $key_feature['popup_content'] ) ) {
																foreach( $key_feature['popup_content'] as $feature_content ) {
																	?>
																	<p><?php echo esc_html( $feature_content ); ?></p>
																	<?php
																}
															}
															?>
															<ul>
																<?php 
																if ( isset( $key_feature['popup_examples'] ) && ! empty( $key_feature['popup_examples'] ) ) {
																	foreach( $key_feature['popup_examples'] as $feature_example ) {
																		?>
																		<li><?php echo esc_html( $feature_example ); ?></li>
																		<?php
																	}
																}
																?>
															</ul>
														</div>
														<div class="feature-image">
															<img src="<?php echo esc_url( $key_feature['popup_image'] ); ?>" alt="<?php echo esc_attr( $key_feature['title'] ); ?>">
														</div>
													</div>
												</div>		
											</div>
										</div>
									</div>
								</li>
								<?php
							}
						}
						?>
					</ul>
				</div>
				<div class="premium-plugin-buy">
					<div class="premium-buy-price-box">
						<div class="price-box-top">
							<div class="pricing-icon">
								<img src="<?php echo esc_url( WHSM_PLUGIN_URL . 'admin/images/premium-upgrade-img/pricing-1.svg' ); ?>" alt="<?php esc_attr_e( 'Personal Plan', 'woo-hide-shipping-methods' ); ?>">
							</div>
							<h4><?php esc_html_e( 'Personal', 'woo-hide-shipping-methods' ) ?></h4>
						</div>
						<div class="price-box-middle">
							<?php
							if ( ! empty( $annual_plugin_price ) ) {
								?>
								<div class="monthly-price-wrap"><?php echo esc_html( '$' . $monthly_plugin_price ) ?><span class="seprater">/</span><span><?php esc_html_e( 'month', 'woo-hide-shipping-methods' ) ?></span></div>
								<div class="yearly-price-wrap"><?php echo sprintf( esc_html__( 'Pay $%s today. Renews in 12 months.', 'woo-hide-shipping-methods' ), esc_html( $annual_plugin_price ) ); ?></div>
								<?php	
							}
							?>
							<span class="for-site"><?php esc_html_e( '1 site', 'woo-hide-shipping-methods' ) ?></span>
							<p class="price-desc"><?php esc_html_e( 'Great for website owners with a single WooCommerce Store', 'woo-hide-shipping-methods' ) ?></p>
						</div>
						<div class="price-box-bottom">
							<a href="javascript:void(0);" class="upgrade-now"><?php esc_html_e( 'Get The Premium Version', 'woo-hide-shipping-methods' ) ?></a>
							<p class="trusted-by"><?php esc_html_e( 'Trusted by 100,000+ store owners and WP experts!', 'woo-hide-shipping-methods' ) ?></p>
						</div>
					</div>
					<div class="premium-satisfaction-guarantee premium-satisfaction-guarantee-2">
						<div class="money-back-img">
							<img src="<?php echo esc_url(WHSM_PLUGIN_URL . 'admin/images/premium-upgrade-img/14-Days-Money-Back-Guarantee.png'); ?>" alt="<?php esc_attr_e('14-Day money-back guarantee', 'woo-hide-shipping-methods'); ?>">
						</div>
						<div class="money-back-content">
							<h2><?php esc_html_e( '14-Day Satisfaction Guarantee', 'woo-hide-shipping-methods' ) ?></h2>
							<p><?php esc_html_e( 'You are fully protected by our 100% Satisfaction Guarantee. If over the next 14 days you are unhappy with our plugin or have an issue that we are unable to resolve, we\'ll happily consider offering a 100% refund of your money.', 'woo-hide-shipping-methods' ); ?></p>
						</div>
					</div>
					<div class="plugin-customer-review">
						<h3><?php esc_html_e( 'Very easy to use and does what it promises!', 'woo-hide-shipping-methods' ) ?></h3>
						<p>
							<?php echo wp_kses( __( 'After trying out other similar plugins, this one just <strong>worked out of the box</strong> and is not complicated to configure. <strong>More than happy</strong> and please keep up the good work!', 'woo-hide-shipping-methods' ), array(
					                'strong' => array(),
					            ) ); 
				            ?>
			            </p>
						<div class="review-customer">
							<div class="customer-img">
								<img src="<?php echo esc_url(WHSM_PLUGIN_URL . 'admin/images/premium-upgrade-img/customer-profile-img.jpeg'); ?>" alt="<?php esc_attr_e('Customer Profile Image', 'woo-hide-shipping-methods'); ?>">
							</div>
							<div class="customer-name">
								<span><?php esc_html_e( 'Vineet Gray', 'woo-hide-shipping-methods' ) ?></span>
								<div class="customer-rating-bottom">
									<div class="customer-ratings">
										<span class="dashicons dashicons-star-filled"></span>
										<span class="dashicons dashicons-star-filled"></span>
										<span class="dashicons dashicons-star-filled"></span>
										<span class="dashicons dashicons-star-filled"></span>
										<span class="dashicons dashicons-star-filled"></span>
									</div>
									<div class="verified-customer">
										<span class="dashicons dashicons-yes-alt"></span>
										<?php esc_html_e( 'Verified Customer', 'woo-hide-shipping-methods' ) ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="upgrade-to-pro-faqs">
				<h2><?php esc_html_e( 'FAQs', 'woo-hide-shipping-methods' ); ?></h2>
				<div class="upgrade-faqs-main">
					<div class="upgrade-faqs-list">
						<div class="upgrade-faqs-header">
							<h3><?php esc_html_e( 'Do you offer support for the plugin? What’s it like?', 'woo-hide-shipping-methods' ); ?></h3>
						</div>
						<div class="upgrade-faqs-body">
							<p>
							<?php 
								echo sprintf(
								    esc_html__('Yes! You can read our %s or submit a %s. We are very responsive and strive to do our best to help you.', 'woo-hide-shipping-methods'),
								    '<a href="' . esc_url('https://docs.thedotstore.com/collection/175-hide-shipping') . '" target="_blank">' . esc_html__('knowledge base', 'woo-hide-shipping-methods') . '</a>',
								    '<a href="' . esc_url('https://www.thedotstore.com/support-ticket/') . '" target="_blank">' . esc_html__('support ticket', 'woo-hide-shipping-methods') . '</a>',
								);

							?>
							</p>
						</div>
					</div>
					<div class="upgrade-faqs-list">
						<div class="upgrade-faqs-header">
							<h3><?php esc_html_e( 'What payment methods do you accept?', 'woo-hide-shipping-methods' ); ?></h3>
						</div>
						<div class="upgrade-faqs-body">
							<p><?php esc_html_e( 'You can pay with your credit card using Stripe checkout. Or your PayPal account.', 'woo-hide-shipping-methods' ) ?></p>
						</div>
					</div>
					<div class="upgrade-faqs-list">
						<div class="upgrade-faqs-header">
							<h3><?php esc_html_e( 'What’s your refund policy?', 'woo-hide-shipping-methods' ); ?></h3>
						</div>
						<div class="upgrade-faqs-body">
							<p><?php esc_html_e( 'We have a 14-day money-back guarantee.', 'woo-hide-shipping-methods' ) ?></p>
						</div>
					</div>
					<div class="upgrade-faqs-list">
						<div class="upgrade-faqs-header">
							<h3><?php esc_html_e( 'I have more questions…', 'woo-hide-shipping-methods' ); ?></h3>
						</div>
						<div class="upgrade-faqs-body">
							<p>
							<?php 
								echo sprintf(
								    esc_html__('No problem, we’re happy to help! Please reach out at %s.', 'woo-hide-shipping-methods'),
								    '<a href="' . esc_url('mailto:hello@thedotstore.com') . '" target="_blank">' . esc_html('hello@thedotstore.com') . '</a>',
								);

							?>
							</p>
						</div>
					</div>
				</div>
			</div>
			<div class="upgrade-to-premium-btn">
				<a href="javascript:void(0);" target="_blank" class="upgrade-now"><?php esc_html_e( 'Get The Premium Version', 'woo-hide-shipping-methods' ) ?><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="crown" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" class="svg-inline--fa fa-crown fa-w-20 fa-3x" width="22" height="20"><path fill="#000" d="M528 448H112c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h416c8.8 0 16-7.2 16-16v-32c0-8.8-7.2-16-16-16zm64-320c-26.5 0-48 21.5-48 48 0 7.1 1.6 13.7 4.4 19.8L476 239.2c-15.4 9.2-35.3 4-44.2-11.6L350.3 85C361 76.2 368 63 368 48c0-26.5-21.5-48-48-48s-48 21.5-48 48c0 15 7 28.2 17.7 37l-81.5 142.6c-8.9 15.6-28.9 20.8-44.2 11.6l-72.3-43.4c2.7-6 4.4-12.7 4.4-19.8 0-26.5-21.5-48-48-48S0 149.5 0 176s21.5 48 48 48c2.6 0 5.2-.4 7.7-.8L128 416h384l72.3-192.8c2.5.4 5.1.8 7.7.8 26.5 0 48-21.5 48-48s-21.5-48-48-48z" class=""></path></svg></a>
			</div>
		</div>
	</div>
</div>
</div>
</div>
</div>
<?php 
