<?php
/**
 * Adding Admin Menu
 *
 * @package Random Banner.
 */
add_action( 'admin_menu', 'bc_random_banner' );

/**
 * Menu and Sub Menu
 */
function bc_random_banner() {
	add_menu_page(
		__( 'Random Banner', 'random-banner' ),
		__( 'Random Banner', 'random-banner' ),
		'manage_options',
		'bc_random_banner',
		'bc_random_banner_settings',
		''
	);

	add_submenu_page(
		'bc_random_banner',
		__( 'Settings', 'random-banner' ),
		__( 'Settings', 'random-banner' ),
		'manage_options',
		'bc_random_banner_option',
		'bc_random_banner_option'
	);

	add_submenu_page(
		'bc_random_banner',
		__( 'Campaign (pro)', 'random-banner' ),
		__( 'Campaign (pro)', 'random-banner' ),
		'manage_options',
		'bc_random_banner_campaign',
		'bc_random_banner_campaign'
	);

	add_submenu_page(
		'bc_random_banner',
		__( 'Statistics (pro)', 'random-banner' ),
		__( 'Statistics (pro)', 'random-banner' ),
		'manage_options',
		'bc_random_banner_statistics',
		'bc_random_banner_statistics'
	);

	add_submenu_page(
		'bc_random_banner',
		__( 'Support', 'random-banner' ),
		__( 'Support', 'random-banner' ),
		'manage_options',
		'bc_random_banner_support',
		'bc_random_banner_support'
	);
}

/**
 * Main Menu Random Banner
 */
function bc_random_banner_settings() {
	$get_all_data = bc_rb_get_all_row(); ?>
	<div class="bc_rb container bc_random_banner" data-display_name="<?php echo esc_attr( bc_rb_get_user_display_name() ); ?>">
		<div class="row">
			<div class="col-md-7 p-20">
				<div class="dropdown">
					<button type="button" data-bs-toggle="dropdown" aria-expanded="false" class="btn btn-primary rb_btn dropdown-toggle" id="bc_rb_dropdown_button">
					<span class="glyphicon glyphicon-plus"></span>
					<?php esc_html_e( 'Add New Banner', 'random-banner' ); ?>
						<span class="caret"></span>
					</button>
					<ul class="dropdown-menu"aria-labelledby="bc_rb_dropdown_button">
						<li class="add_new_upload" data-item="add_new_upload">
							<a href="#" class="dropdown-item">
								<span class="glyphicon glyphicon-picture"></span>
								<?php esc_html_e( 'Image Banner', 'random-banner' ); ?>
							</a>
						</li>
						<li class="add_new_script" data-item="add_new_script">
							<a href="#"  class="dropdown-item">
								<span class="glyphicon glyphicon-align-left"></span>
								<?php esc_html_e( 'Script Banner', 'random-banner' ); ?>
							</a>
						</li>
						<li class="paypal_donation_button" data-item="add_new_swf">
							<a href="#"  class="dropdown-item">
								<span class="glyphicon glyphicon-facetime-video"></span>
								<?php esc_html_e( 'SWF Banner', 'random-banner' ); ?>
							</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="col-md-5 p-10">
				<div class="row">
					<div class="col-md-5 table-bordered p-10 text-center">
						<?php echo wp_kses_post( bc_rb_show_payment_details() ); ?>
					</div>
					<div class="col-md-7 bc_plugin_demo" data-email="<?php echo esc_attr( bc_get_current_user_email() ); ?>">
						<div class="flex table-bordered p-10">
							<div class="p-r-20">
								<img src="<?php echo esc_url( plugins_url( 'assets/images/demo.png', BC_RB_PLUGIN ) ); ?>" alt="demo image"/>
							</div>
							<div class="text-center">
								<a href="https://www.randombanners.com">
									<strong>Click Here for</strong>
									<h4>Live Demo</h4>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
		echo wp_kses_post( bc_rb_loader() );
		if ( isset( $_REQUEST['success'] ) ) {
			bc_rb_on_success_payment( $_REQUEST );
		}
		?>
		<div class="row">
			<div class="col-md-5 bc_filters hide">
				<div class="bc_filter_by"><?php esc_html_e( 'Filter By', 'random-banner' ); ?></div>
				<div>
					<?php echo bc_rb_drop_down_category_filter( 'bc_rb_category_names', bc_rb_get_category_by_array(), '', '', 'category_filter' ); ?>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="upload_area col-md-12"
			data-url="<?php echo esc_url( admin_url( 'admin-ajax.php?action=bc_rb_save_banner&nonce=' . wp_create_nonce( 'bc_rb_nonce' ) ) ); ?>"
			data-delete="<?php echo esc_url( admin_url( 'admin-ajax.php?action=bc_rb_delete_banner&nonce=' . wp_create_nonce( 'bc_rb_nonce_delete' ) ) ); ?>"
			data-payment_info="<?php echo esc_attr( get_option( 'bc_rb_payment_info' ) ); ?>"
			data-donation_later="<?php echo esc_url( admin_url( 'admin-ajax.php?action=bc_rb_donation_later&nonce=' . wp_create_nonce( 'bc_rb_donation_later' ) ) ); ?>" >
			<?php echo loop_data( $get_all_data ); ?>
		</div>
	</div>
	<?php
}

	/**
	 * Sub Menu Support
	 */
function bc_random_banner_support() {
	bc_get_random_banner_support();
}

	/**
	 * Settings
	 */
function bc_random_banner_option() {
	$options                  = get_random_banner_option_value();
	$category                 = bc_rb_get_all_category();
	$insert_short_code_values = get_option( 'bc_rb_insert_short_code_values', bc_rb_category_default_values() );
	if ( is_string( $insert_short_code_values ) ) {
		$insert_short_code_values = maybe_unserialize( $insert_short_code_values );
	}
	$popup = get_popup_option_value();

	$options['disable']        = array_key_exists( 'disable', $options ) ? $options['disable'] : '';
	$options['open']           = array_key_exists( 'open', $options ) ? $options['open'] : '';
	$options['disable_mobile'] = array_key_exists( 'disable_mobile', $options ) ? $options['disable_mobile'] : '';
	$options['user_logged_in'] = array_key_exists( 'user_logged_in', $options ) ? $options['user_logged_in'] : '';
	$options['empty_banner']   = array_key_exists( 'empty_banner', $options ) ? $options['empty_banner'] : 'There is no ads to display, Please add some';

	$popup['enable_popup']        = array_key_exists( 'enable_popup', $popup ) ? $popup['enable_popup'] : '';
	$popup['popup_category_name'] = array_key_exists( 'popup_category_name', $popup ) ? $popup['popup_category_name'] : '';
	?>
	<div class="bc_rb container bc_random_banner" data-display_name="<?php echo esc_attr( bc_rb_get_user_display_name() ); ?>">
		<?php echo wp_kses_post( bc_rb_loader() ); ?>
		<div class="row">
			<div class="col-md-12">
				<h1>
					<?php esc_html_e( 'Random Banner Settings', 'random-banner' ); ?>
					<span class="float-end"><?php echo wp_kses_post( bc_rb_show_payment_details() ); ?></span>
				</h1>
			</div>
		</div>
		<div id="content" class="padding_top_20">
			<ul class="nav nav-pills mb-3" id="pills-tab bc_rb_settings_tab" role="tablist">
				<li class="nav-item" role="presentation">
					<button class="nav-link active" data-bs-toggle="pill" data-bs-target="#category" type="button" role="tab" aria-controls="category" aria-selected="true"><?php
					 esc_html_e( 'Category', 'random-banner' ); ?></button>
				</li>
				<li class="nav-item" role="presentation">
					<button  class="nav-link" data-bs-toggle="pill" data-bs-target="#post_page" type="button" role="tab" aria-controls="post_page" aria-selected="true"><?php esc_html_e( 'Insert Banners inside the Post/Page', 'random-banner' ); ?></button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="nav-link"  data-bs-toggle="pill" data-bs-target="#Popup" type="button" role="tab" aria-controls="Popup" aria-selected="true"><?php esc_html_e( 'Popup', 'random-banner' ); ?></button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="nav-link"  data-bs-toggle="pill" data-bs-target="#Statistics" type="button" role="tab" aria-controls="Statistics" aria-selected="true"><?php esc_html_e( 'Statistics', 'random-banner' ); ?></button>
				</li>
				<li class="nav-item" role="presentation">
						<button class="nav-link"  data-bs-toggle="pill" data-bs-target="#FloatingAds" type="button" role="tab" aria-controls="FloatingAds" aria-selected="true"><?php esc_html_e( 'Floating Ads', 'random-banner' ); ?></button>
					</li>
				<li class="nav-item" role="presentation">
					<button class="nav-link"  data-bs-toggle="pill" data-bs-target="#Others" type="button" role="tab" aria-controls="Others" aria-selected="true"><?php esc_html_e( 'Others', 'random-banner' ); ?></button>
				</li>
			</ul>
			<div id="my-tab-content" class="tab-content">
                <div class="tab-pane p-20 fade show active" id="category">
                        <div class="col-md-6 category" data-display_name="<?php echo esc_attr( bc_rb_get_user_display_name() ); ?>">
                            <div class="row">
                                <div class="col-md-12 p-b-20">
                                    <button class="btn btn-primary new_category">
                                        <span class="glyphicon glyphicon-plus"></span>
                                        <?php esc_html_e( 'Add New Category', 'random-banner' ); ?>
                                    </button>
                                </div>
                                <div class="col-md-12 category_items"
                                data-payment_info="<?php echo esc_attr( get_option( 'bc_rb_payment_info' ) ); ?>"
                                data-donation_later="<?php echo esc_url( admin_url( 'admin-ajax.php?action=bc_rb_donation_later&nonce=' . wp_create_nonce( 'bc_rb_donation_later' ) ) ); ?>"
                                data-save="<?php echo esc_url( admin_url( 'admin-ajax.php?action=bc_rb_save_category&nonce=' . wp_create_nonce( 'bc_rb_save_category' ) ) ); ?>"
                                data-delete="<?php echo esc_url( admin_url( 'admin-ajax.php?action=bc_rb_delete_category&nonce=' . wp_create_nonce( 'bc_rb_delete_category' ) ) ); ?>">
                                    <?php echo bc_rb_loop_category( $category ); ?>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="tab-pane p-20 fade" id="post_page">
                    <div class="row">
                        <div class="col-md-7">
                            <form data-save="<?php echo esc_url( admin_url( 'admin-ajax.php?action=bc_rb_save_insert_post&nonce=' . wp_create_nonce( 'bc_rb_save_insert_post' ) ) ); ?>">
                            <div class="bc_rb_insert_post_shortcode padding_top_20">
                                <div class="bc_rb_check_insert_post">
                                    <h5><?php esc_html_e( 'Enable Banner to all Post and Pages?', 'random-banner' ); ?></h5>
                                    <select name="bc_rb_category_values[enable_insert]" class="form-control bc_rb_enable_insert">
                                        <?php echo bc_rb_get_slider_loop( $insert_short_code_values['enable_insert'] ); ?>
                                    </select>
                                </div>

                                <div class="row bc_rb_enable_insert_post_shortcode hide">
                                    <div class="col-md-6">
                                        <h5><?php esc_html_e( 'Category Name', 'random-banner' ); ?></h5>
                                        <?php echo bc_rb_drop_down( 'bc_rb_category_values[category_name]', bc_rb_get_category_by_array(), $insert_short_code_values['category_name'] ); ?>

                                        <h5><?php esc_html_e( 'Slider', 'random-banner' ); ?> [Pro Version only]</h5>
                                        <select disabled name="bc_rb_category_values[slider]" readonly class="form-control">
                                            <option value="No">No</option>
                                        </select>

                                        <h5><?php esc_html_e( 'Autoplay', 'random-banner' ); ?> [Pro Version only]</h5>
                                        <select disabled name="bc_rb_category_values[autoplay]" readonly class="form-control">
                                            <option value="True">True</option>
                                        </select>

                                        <h5><?php esc_html_e( 'Delay', 'random-banner' ); ?> [Pro Version only]</h5>
                                        <select disabled name="bc_rb_category_values[delay]" readonly class="form-control">
                                            <option value="3000">3000</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <h5><?php esc_html_e( 'Loop', 'random-banner' ); ?> [Pro Version only]</h5>
                                        <select disabled name="bc_rb_category_values[loop]" readonly class="form-control">
                                            <option value="True">True</option>
                                        </select>

                                        <h5><?php esc_html_e( 'Banner Location', 'random-banner' ); ?></h5>
                                        <?php echo bc_rb_drop_down( 'bc_rb_category_values[location]', bc_rb_insert_post_locations(), $insert_short_code_values['location'] ); ?>

                                        <h5><?php esc_html_e( 'Insert in', 'random-banner' ); ?></h5>
                                        <?php echo bc_rb_drop_down( 'bc_rb_category_values[post_page]', bc_rb_insert_post_page(), $insert_short_code_values['post_page'] ); ?>
                                    </div>
                                </div>
                                <div class="padding_top_10">
                                    <button type="submit" name="bc_rb_category_values_save" class="btn btn-primary bc_rb_category_values_save">
                                    <?php
                                    esc_html_e( 'Save', 'random-banner' );
                                    ?>
                                    </button>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="tab-pane p-20 fade" id="Popup">
                    <form data-save="<?php echo esc_url( admin_url( 'admin-ajax.php?action=bc_rb_save_popup&nonce=' . wp_create_nonce( 'bc_rb_save_popup' ) ) ); ?>">
                    <div class="row padding_top_20">
                        <div class="col-md-3">
                            <?php esc_html_e( 'Enable Popup?', 'random-banner' ); ?>
                        </div>
                        <div class="col-md-4">
                            <input <?php echo esc_attr( $popup['enable_popup'] ); ?> type="checkbox"
                            name="bc_rb_setting_popup[enable_popup]"
                            value="checked" />
                        </div>
                    </div>
                    <div class="row padding_top_20">
                        <div class="col-md-3">
                            <?php esc_html_e( 'Category to show in Popup', 'random-banner' ); ?>
                        </div>
                        <div class="col-md-4">
                            <?php
                            echo bc_rb_drop_down(
                                'bc_rb_setting_popup[popup_category_name]',
                                bc_rb_get_category_by_array(),
                                $popup['popup_category_name']
                            )
                            ?>
                        </div>

                    </div>
                    <div class="margin_20 paypal_donation_button">
                        <div class="row padding_top_20">
                            <div class="col-md-3">
                                <?php esc_html_e( 'How many times Popup should show per Session?', 'random-banner' ); ?>
                            </div>
                            <div class="col-md-4">
                                <input readonly type="text" value="3" class="paypal_donation_button"/>
                            </div>
                        </div>

                        <div class="row padding_top_20">
                            <div class="col-md-3">
                                <?php esc_html_e( 'Popup should show after', 'random-banner' ); ?>
                            </div>
                            <div class="col-md-4">
                                <input type="text" readonly value="2000" class="paypal_donation_button"/>
                                (milliseconds)
                            </div>
                        </div>

                        <div class="row padding_top_20">
                            <div class="col-md-3">
                                <?php esc_html_e( 'Popup Loading Animated Style', 'random-banner' ); ?>
                            </div>
                            <div class="col-md-4">
                                <?php
                                echo bc_rb_drop_down(
                                    '',
                                    bc_rb_popup_animated_style(),
                                    '',
                                    'readonly',
                                    'paypal_donation_button'
                                );
                                ?>
                            </div>
                        </div>

                        <div class="row padding_top_20">
                            <div class="col-md-3">
                                <?php esc_html_e( 'Enable Popup Background Transparent', 'random-banner' ); ?>
                            </div>
                            <div class="col-md-4">
                                <input disabled checked type="checkbox" value=""/>
                            </div>
                        </div>

                        <div class="row padding_top_20">
                            <div class="col-md-3">
                                <?php esc_html_e( 'Popup Background Color', 'random-banner' ); ?>
                            </div>
                            <div class="col-md-4">
                                <input disabled type="color" value=""/>
                            </div>
                        </div>

                        <div class="row padding_top_20">
                            <div class="col-md-3">
                                <?php esc_html_e( 'Popup Border Color', 'random-banner' ); ?>
                            </div>
                            <div class="col-md-4">
                                <div class="bc_rb_inline">
                                    <div>
                                    <?php
                                        echo bc_rb_drop_down(
                                            '',
                                            bc_rb_number_1_to_10(),
                                            '',
                                            'readonly',
                                            'paypal_donation_button'
                                        )
                                    ?>
                                    </div>
                                    <div>
                                    <?php
                                        echo bc_rb_drop_down(
                                            '',
                                            bc_rb_border_styles(),
                                            '',
                                            'readonly',
                                            'paypal_donation_button'
                                        );
                                    ?>
                                    </div>
                                    <div>
                                        <input disabled type="color" value=""/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="padding_top_10">
                        <button type="submit" name="bc_rb_popup_save" class="btn btn-primary bc_rb_popup_save"><?php esc_html_e( 'Save', 'random-banner' ); ?></button>
                    </div>
                    </form>
                </div>
                <div class="tab-pane p-20 fade paypal_donation_button" id="Statistics">
                    <div class="row">
                        <div>
                            <div class="row padding_top_20">
                                <div class="col-md-3">
                                    <?php esc_html_e( 'Google Map API Key', 'random-banner' ); ?>
                                </div>
                                <div class="col-md-5">
                                    <input type="text" value="" class="form-control" name=""/>
                                </div>
                                <div class="col-md-4">
                                    <div class="btn btn-sm btn-info" data-toggle="popover" title="Help"
                                         data-content="Instruction to generate <a href=\'https://developers.google.com/maps/documentation/javascript/get-api-key\' target=\'_blank\'>API Key</a>">
                                        <span class="glyphicon glyphicon-question-sign"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row padding_top_20">
                                <div class="col-md-6">
                                    <button type="submit" name="bc_rb_statistic_save" class="btn btn-primary bc_rb_statistic_save">
                                        <?php esc_html_e( 'Save', 'random-banner' ); ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row padding_top_200">
                        <div class="bg-danger p-20">
                            <div class="row padding_top_20">
                                <div class="col-md-3">
                                    <?php esc_html_e( 'Total Number of row in Statistics', 'random-banner' ); ?>
                                </div>
                                <div class="col-md-6" id="bc_stats_count_display">
                                    Pro Version
                                </div>
                            </div>

                            <div class="row padding_top_20">
                                <div class="col-md-3">
                                    <?php esc_html_e( 'Delete last __ records from statistics', 'random-banner' ); ?>
                                </div>
                                <div class="col-md-4">
                                    <?php
                                    echo bc_rb_drop_down(
                                        'bc_rb_setting_statistics_delete',
                                        array(
                                            'null' => 'Please Select the number of row to delete',
                                            '10'   => '10',
                                            '100'  => '100',
                                            '200'  => '200',
                                            '300'  => '300',
                                            '400'  => '400',
                                            '500'  => '500',
                                            '1000' => '1000',
                                        )
                                    );
                                    ?>
                                </div>
                            </div>

                            <div class="row padding_top_20">
                                <div class="col-md-6">
                                    <button type="submit" name="bc_rb_option" class="btn btn-danger bc_rb_statistic_delete">
                                        <?php esc_html_e( 'Delete Records', 'random-banner' ); ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="FloatingAds">
                            <div class="p-20">
                                <div>
                                    <div class="row">
                                        <div class="col-md-12 padding_bottom_20">
                                        <a target="_blank" href="https://www.randombanners.com" class="btn btn-primary pull-right"><span class="glyphicon glyphicon-eye-open"></span> Demo</a>
                                        </div>
                                    </div>
                                    <div class="row  paypal_donation_button">
                                        <div class="col-md-6">
                                            <div class="bc_floating_container">
                                                <h4>Top Floating Ads</h4>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="top_ads_enable">Enable Top Floating Ads?</label>
                                                        <?php echo bc_rb_drop_down( 'bc_rb_floating[top][enable]', bc_rb_get_yes_or_no_values() ); ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="top_ads_enable">Disable in Mobile?</label>
                                                        <?php echo bc_rb_drop_down( 'bc_rb_floating[top][mobile_disable]', bc_rb_get_yes_or_no_values() ); ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="top_ads_enable">Hide by Default?</label>
                                                        <?php echo bc_rb_drop_down( 'bc_rb_floating[top][hide]', bc_rb_get_yes_or_no_values() ); ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="top_ads_category">Category</label>
                                                        <?php echo bc_rb_drop_down( 'bc_rb_floating[top][category_name]', bc_rb_get_category_by_array() ); ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="top_ads_show_text">Show Text</label>
                                                            <input type="text" class="form-control" name="bc_rb_floating[top][show_text]" value="" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="top_ads_hide_text">Hide Text</label>
                                                            <input type="text" class="form-control" name="bc_rb_floating[top][hide_text]" value="" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="top_ads_category">Is Fixed</label>
                                                        <?php echo bc_rb_drop_down( 'bc_rb_floating[top][is_fixed]', bc_rb_get_yes_or_no_values() ); ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="top_ads_category">Padding Top</label>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" placeholder="10" name="bc_rb_floating[top][padding]" value="">
                                                                <span class="input-group-addon">px</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="bc_floating_container">
                                                <h4>Right Floating Ads</h4>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="right_ads_enable">Enable Right Floating Ads?</label>
                                                        <?php echo bc_rb_drop_down( 'bc_rb_floating[right][enable]', bc_rb_get_yes_or_no_values() ); ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="right_ads_enable">Disable in Mobile?</label>
                                                        <?php echo bc_rb_drop_down( 'bc_rb_floating[right][mobile_disable]', bc_rb_get_yes_or_no_values() ); ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="right_ads_enable">Hide by Default?</label>
                                                        <?php echo bc_rb_drop_down( 'bc_rb_floating[right][hide]', bc_rb_get_yes_or_no_values() ); ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="right_ads_category">Category</label>
                                                        <?php echo bc_rb_drop_down( 'bc_rb_floating[right][category_name]', bc_rb_get_category_by_array() ); ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="right_ads_show_text">Show Text</label>
                                                            <input type="text" class="form-control" name="bc_rb_floating[right][show_text]" value="" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="right_ads_hide_text">Hide Text</label>
                                                            <input type="text" class="form-control" name="bc_rb_floating[right][hide_text]" value="" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="top_ads_category">Is Fixed</label>
                                                        <?php echo bc_rb_drop_down( 'bc_rb_floating[right][is_fixed]', bc_rb_get_yes_or_no_values() ); ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="right_ads_category">Padding Right</label>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" placeholder="10" value="" name="bc_rb_floating[right][padding]">
                                                                <span class="input-group-addon">px</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="bc_floating_container ">
                                                <h4>Bottom Floating Ads</h4>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="bottom_ads_enable">Enable Bottom Floating Ads?
                                                            </label>
                                                        <?php echo bc_rb_drop_down( 'bc_rb_floating[bottom][enable]', bc_rb_get_yes_or_no_values() ); ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="bottom_ads_enable">Disable in Mobile?</label>
                                                        <?php echo bc_rb_drop_down( 'bc_rb_floating[bottom][mobile_disable]', bc_rb_get_yes_or_no_values() ); ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="bottom_ads_enable">Hide by Default?</label>
                                                        <?php echo bc_rb_drop_down( 'bc_rb_floating[bottom][hide]', bc_rb_get_yes_or_no_values() ); ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="bottom_ads_category">Category</label>
                                                        <?php echo bc_rb_drop_down( 'bc_rb_floating[bottom][category_name]', bc_rb_get_category_by_array() ); ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="bottom_ads_show_text">Show Text</label>
                                                            <input type="text" class="form-control" name="bc_rb_floating[bottom][show_text]" value="" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="bottom_ads_hide_text">Hide Text</label>
                                                            <input type="text" class="form-control" name="bc_rb_floating[bottom][hide_text]" value="" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="bottom_ads_category">Padding Bottom</label>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" placeholder="10" value="" name="bc_rb_floating[bottom][padding]">
                                                                <span class="input-group-addon">px</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="bc_floating_container">
                                                <h4>Left Floating Ads</h4>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="left_ads_enable">Enable Left Floating Ads?</label>
                                                        <?php echo bc_rb_drop_down( 'bc_rb_floating[left][enable]', bc_rb_get_yes_or_no_values() ); ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="left_ads_enable">Disable in Mobile?</label>
                                                        <?php echo bc_rb_drop_down( 'bc_rb_floating[left][mobile_disable]', bc_rb_get_yes_or_no_values() ); ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="left_ads_enable">Hide by Default?</label>
                                                        <?php echo bc_rb_drop_down( 'bc_rb_floating[left][hide]', bc_rb_get_yes_or_no_values() ); ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="left_ads_category">Category</label>
                                                        <?php echo bc_rb_drop_down( 'bc_rb_floating[left][category_name]', bc_rb_get_category_by_array() ); ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="left_ads_show_text">Show Text</label>
                                                            <input type="text" class="form-control" name="bc_rb_floating[left][show_text]" value="" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="left_ads_hide_text">Hide Text</label>
                                                            <input type="text" class="form-control" name="bc_rb_floating[left][hide_text]" value="" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="left_ads_category">Is Fixed</label>
                                                        <?php echo bc_rb_drop_down( 'bc_rb_floating[left][is_fixed]', bc_rb_get_yes_or_no_values() ); ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="left_ads_category">Padding Left</label>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" placeholder="10" value="" name="bc_rb_floating[left][padding]">
                                                                <span class="input-group-addon">px</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 padding_bottom_20">
                                        <a target="_blank" href="https://www.randombanners.com" class="btn btn-primary pull-right"><span class="glyphicon glyphicon-eye-open"></span> Demo</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                <div class="tab-pane  fade p-20" id="Others">
                    <form data-save="<?php echo esc_url( admin_url( 'admin-ajax.php?action=bc_rb_save_options&nonce=' . wp_create_nonce( 'bc_rb_save_options' ) ) ); ?>">
                    <div class="row padding_top_20">
                        <div class="col-md-6">
                            <?php esc_html_e( 'Open Ads Link in New Window', 'random-banner' ); ?>
                        </div>
                        <div class="col-md-4">
                            <input <?php echo esc_attr( $options['open'] ); ?> type="checkbox" name="bc_rb_setting_options[open]"
                            value="checked" />
                        </div>
                    </div>
                    <div class="row padding_top_20">
                        <div class="col-md-6">
                            <?php esc_html_e( 'Currency Type', 'random-banner' ); ?> [Pro Version]
                        </div>
                        <div class="col-md-3">
                            <?php echo bc_rb_drop_down_currency( 'bc_rb_setting_options[currency_type]', bc_rb_currency_lists() ); ?>
                        </div>
                    </div>

                    <div class="row padding_top_20">
                        <div class="col-md-6">
                            <?php esc_html_e( 'Disable Banners to Logged in Users?', 'random-banner' ); ?>
                        </div>
                        <div class="col-md-4">
                            <input <?php echo esc_attr( $options['user_logged_in'] ); ?> type="checkbox" name="bc_rb_setting_options[user_logged_in]"
                            value="checked" />
                        </div>
                    </div>

                    <div class="row padding_top_20">
                        <div class="col-md-6">
                            <span class="danger_fonts"><?php esc_html_e( 'Disable Random Banner for Mobile and Tablets?', 'random-banner' ); ?> [Pro Version]</span>
                            <br>
                            <i>
                            <?php
                            esc_html_e(
                                'Note: if checked the Random Banner will be disabled in all the location
                                including shortcodes',
                                'random-banner'
                            )
                            ?>
                                </i>
                        </div>
                        <div class="col-md-6">
                            <input type="checkbox" disabled/>
                        </div>
                    </div>

                    <div class="row padding_top_20">
                        <div class="col-md-6">
                            <span class="danger_fonts">
                            <?php
                            esc_html_e( 'Disable Random Banner in all location in that page when Post/Page banner disabled [Pro Version]', 'random-banner' );
                            ?>
                            </span>
                        </div>
                        <div class="col-md-6">
                            <input type="checkbox" disabled value="checked"/>
                        </div>
                    </div>


                    <div class="row padding_top_20">
                        <div class="col-md-6">
                            <span class="danger_fonts"><?php esc_html_e( 'Disable Random Banner in all locations', 'random-banner' ); ?></span>
                            <br>
                            <i>
                            <?php
                            esc_html_e(
                                'Note: if checked the Random Banner will be disabled in all the location
                                including shortcodes',
                                'random-banner'
                            )
                            ?>
                                </i>
                        </div>
                        <div class="col-md-6">
                            <input <?php echo esc_attr( $options['disable'] ); ?> type="checkbox" name="bc_rb_setting_options[disable]"
                            value="checked" />
                        </div>
                    </div>

                    <div class="row padding_top_20">
                        <div class="col-md-6">
                            <?php esc_html_e( 'Text for empty banner', 'random-banner' ); ?>
                            <br>
                            <i>
                            <?php
                            esc_html_e(
                                'Note: This text will be shown if there is no ads to display in that
                                category',
                                'random-banner'
                            )
                            ?>
                                </i>
                        </div>
                        <div class="col-md-6">
                            <input class="form-control" type="text" name="bc_rb_setting_options[empty_banner]"
                            value="<?php echo esc_attr( $options['empty_banner'] ); ?>"/>
                        </div>
                    </div>

                    <div class="row padding_top_20">
                        <div class="col-md-6">
                            <button type="submit" name="bc_rb_option_save" class="btn btn-primary bc_rb_option_save">
                                <?php esc_html_e( 'Save', 'random-banner' ); ?>
                            </button>
                        </div>
                    </div>
                    </form>
                </div>
			</div>
		</div>
	</div>
	<?php
}

	/**
	 * Run Campaign
	 */
function bc_random_banner_campaign() {
	$get_banner_data = bc_rb_get_all_row();
	echo '
    <div class="bc_rb container">
        <div class="row">
            <div class="col-md-12">
                <h1> ' . esc_html__( 'Campaign', 'random-banner' ) . '
                    <span class="float-end bc_random_banner" data-display_name="' . esc_attr( bc_rb_get_user_display_name() ) . '">' . wp_kses_post( bc_rb_show_payment_details() ) . '</span>
                </h1>
            </div>
            <i>
                <b> ' . esc_html__( 'Note:', 'random-banner' ) . '</b>
                ' . esc_html__( '1. Please use -1 for unlimited Impression/Click/Amount', 'random-banner' ) . ', ' . esc_html__(
					'2. There is no Cost per click for Script and SWF ads',
					'random-banner'
				) . '
            </i>
        </div>
        ';
	echo '
        <div class="bc_random_banner_campaign" data-display_name="' . esc_attr( bc_rb_get_user_display_name() ) . '" data-url="' . esc_url(
		admin_url(
			'admin-ajax.php?action=bc_rb_save_campaign&nonce=' .
			wp_create_nonce( ' bc_rb_save_campaign' )
		)
	) . '" >';
	echo bc_rb_loop_campaign_data( $get_banner_data );
	echo '
    </div></div>';
}

	/**
	 * Statistics
	 */
function bc_random_banner_statistics() {
	echo '
    <div class="bc_rb container bc_random_banner" data-display_name="' . esc_attr( bc_rb_get_user_display_name() ) . '">
        ' . wp_kses_post( bc_rb_loader() );
	echo '
        <div class="row">';
	echo '
            <h2> ' . esc_html__( 'Random Banner Statistics', 'random-banner' ) . '</h2>
            ';
	if ( isset( $_REQUEST['success'] ) ) {
		bc_rb_on_success_payment( $_REQUEST );
	}
	if ( bc_rb_is_paid() ) {
		echo '
            <div class="col-md-12 bc_rb_transaction_details">
                <div class="col-md-5 bg-primary">
                    <h4>PayPal Transaction ID</h4>
                </div>
                <div class="col-md-5 bg-primary">
                    <h4>' . esc_html( get_option( 'bc_rb_payment_info' ) ) . '</h4>
                </div>
            </div>
            ';
	} else {
		echo '
            <div class="col-md-12">
                <div class=" pull-right">
                    ' . wp_kses_post( bc_rb_show_payment_details() ) . '
                </div>
            </div>
            <div class="col-md-6 paypal_donation_button">
                <h4>Bar Chart with Geo Location</h4>
                <img class="img-responsive img-thumbnail" src="' . esc_url( plugins_url( '/assets/images/statistics_bar.jpg', BC_RB_PLUGIN ) ) . '"/>
            </div>
            <div class="col-md-6 paypal_donation_button">
                <h4>Line Chart with Geo Location</h4>
                <img class="img-responsive img-thumbnail" src="' . esc_url( plugins_url( '/assets/images/statistics_line.jpg', BC_RB_PLUGIN ) ) . '"/>
            </div>
            ';
	}
	echo '
        </div>
    </div>';
}

	/**
	 * Disable Random Banner to post and page
	 */
function add_custom_meta_box() {
	$post_types = get_post_types(
		array(
			'public' => true,
		),
		'objects'
	);
	if ( $post_types ) {
		foreach ( $post_types as $post_type ) {
			if ( 'attachment' === $post_type->name ) {
				continue;
			}
			add_meta_box( 'bc_rb_disable_banner', 'Disable Random Banner on this ' . $post_type->name, 'bc_rb_disable_random_banner', $post_type->name, 'normal', 'high', null );
		}
	}
}

	add_action( 'add_meta_boxes', 'add_custom_meta_box' );

	/**
	 * Disable Random Banner Inside the Post | Page
	 *
	 * @param object $post Banner Details.
	 */
function bc_rb_disable_random_banner( $post ) {
	$checked = '';
	$disable = get_post_meta( $post->ID, 'bc_rb_disable_banner', true );
	if ( 'yes' === $disable ) {
		$checked = 'checked';
	}
	wp_nonce_field( 'bc_rb_save_post_meta_nonce', 'bc_rb_save_post_meta_nonce' );
	?>
	<p>
		<label for="bc_rb_disable_banner"><?php esc_html_e( 'Disable Random Banner?', 'random-banner' ); ?></label>
		<input type="checkbox" name="bc_rb_disable_banner" id="bc_rb_disable_banner" value="yes" <?php echo esc_attr( $checked ); ?> />
	</p>
	<p class="description">
		<?php esc_html_e( 'If you wish to disable random banner on this content, please check this option.', 'random-banner' ); ?>
	</p>
	<?php
}

/**
 * Show Popup Based on Setting Value
 */
function bc_rb_show_popup() {
	$popup = get_popup_option_value();
	if ( ! isset( $_SESSION['popup_show'] ) ) {
		$_SESSION['popup_show'] = 0;
	}
	if ( 'checked' === $popup['enable_popup'] && $popup['popup_session'] > $_SESSION['popup_show'] ) {
		$_SESSION['popup_show'] += 1;
		$bg_color                = 'transparent';
		$bg_border               = 'none';
		$animation_style         = esc_attr( $popup['popup_animated_style'] );
		$popup_show              = esc_attr( $popup['popup_show'] );

		echo '<div id="popup" class="bc_rb_hide">
<div class="bc_rb_popup_container animated">
<div class="bc_rb_close"><img src="' . esc_url( plugins_url( 'assets/images/close.png', BC_RB_PLUGIN ) ) . '"  alt="close button"/></div>
<div class="bc_random_banner_shortcode">
' . do_shortcode( '[bc_random_banner category=' . esc_html( $popup['popup_category_name'] ) . ']' ) . '
</div>
</div>
</div>';
		echo "<script>
    jQuery(document).ready(function($) {
        window.setTimeout(function(){
            $('#popup').css({
            'background':'{$bg_color}',
            'border':'{$bg_border}',
            'z-index':999
    });

    $('.bc_rb_popup_container').addClass('{$animation_style}');

    if($('#popup').hasClass('bc_rb_hide')){
        $('#popup').removeClass('bc_rb_hide');
        }
},{$popup_show});
    });

    </script>";
	}
}

add_action( 'wp_footer', 'bc_rb_show_popup' );

add_filter( 'bulk_actions-edit-post', 'bc_bulk_actions_edit_post' );
add_filter( 'bulk_actions-edit-page', 'bc_bulk_actions_edit_post' );

/**
 *  Bulk action edit post/page
 *
 * @param array $bulk_actions Action Name.
 *
 * @return array
 */
function bc_bulk_actions_edit_post( $bulk_actions ) {
	$bulk_actions['bc_rb_enable']  = __( 'Enable Random Banner [Pro]', 'random-banner' );
	$bulk_actions['bc_rb_disable'] = __( 'Disable Random Banner [Pro]', 'random-banner' );

	return $bulk_actions;
}

add_filter( 'handle_bulk_actions-edit-post', 'bc_handle_bulk_actions_edit_post', 10, 3 );
add_filter( 'handle_bulk_actions-edit-page', 'bc_handle_bulk_actions_edit_post', 10, 3 );

/**
 * Bulk action edit post/page.
 *
 * @param string $redirect_to Redirect URL.
 * @param string $doaction    Action Name.
 * @param array  $post_ids    Post IDs.
 *
 * @return string
 */
function bc_handle_bulk_actions_edit_post( $redirect_to, $doaction, $post_ids ) {
	if ( 'bc_rb_enable' !== $doaction && 'bc_rb_disable' !== $doaction ) {
		return $redirect_to;
	}

	$redirect_to = add_query_arg(
		array(
			'bc_random_banner' => 'Buy pro',
		),
		$redirect_to
	);

	return $redirect_to;
}

add_action( 'admin_notices', 'bc_post_admin_notices' );

/**
 * post/page Admin Notice
 */
function bc_post_admin_notices() {
	if ( ! empty( $_REQUEST['bc_random_banner'] ) ) {
		printf( '<div id="message" class="updated fade"><h4>Please purchase pro version to activate this feature</h4></div>' );
	}
}
