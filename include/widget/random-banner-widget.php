<?php
/**
 * Widget
 *
 * @package widget
 */

/**
 * Class random_banner_widget
 */
class random_banner_widget extends WP_Widget {

	/**
	 * Random_banner_widget constructor.
	 */
	public function __construct() {
		parent::__construct(
			'random_banner_widget',
			__( 'Random Banner', 'random-banner' ),
			array( 'description' => __( 'Simple way to create a beautiful Random Banner', 'random-banner' ) )
		);
	}

	/**
	 * Show widget on output screen
	 *
	 * @param array $args arguments.
	 * @param array $instance Instance.
	 */
	public function widget( $args, $instance ) {
		$options = get_random_banner_option_value();
		if ( ( isset( $options['disable'] ) && 'checked' === $options['disable'] ) || bc_rb_check_user_logged_in( $options ) ) {
			echo '';
		} else {
			echo $args['before_widget'];
			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'bc_rb_title', $instance['title'] ) . $args['after_title'];
			}
			$category = ( ! empty( $instance['category'] ) ) ? $instance['category'] : 'default';
			$autoplay = ( ! empty( $instance['autoplay'] ) ) ? $instance['autoplay'] : 'false';
			$autoplay = 'True' === $autoplay ? 'Autoplay' : 'false';
			$delay    = ! empty( $instance['delay'] ) ? $instance['delay'] : '';
			$loop     = ! empty( $instance['loop'] ) ? $instance['loop'] : 'false';
			$slider   = ! empty( $instance['slider'] ) ? $instance['slider'] : 'No';
			$dots     = ! empty( $instance['dots'] ) ? $instance['dots'] : 'true';

			$slider_list = array(
				'slider'   => $slider,
				'autoplay' => $autoplay,
				'delay'    => $delay,
				'loop'     => $loop,
				'dots'     => $dots,
			);
			echo bc_rb_generate_banners( $category, $slider_list );
			echo $args['after_widget'];
		}
	}

	/**
	 * Widget setting page
	 *
	 * @param array $instance Instance.
	 */
	public function form( $instance ) {
		$title    = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$category = ! empty( $instance['category'] ) ? $instance['category'] : '';
		$autoplay = ! empty( $instance['autoplay'] ) ? $instance['autoplay'] : '';
		$delay    = ! empty( $instance['delay'] ) ? $instance['delay'] : '';
		$loop     = ! empty( $instance['loop'] ) ? $instance['loop'] : '';
		$slider   = ! empty( $instance['slider'] ) ? $instance['slider'] : '';
		$dots     = ! empty( $instance['dots'] ) ? $instance['dots'] : '';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'random-banner' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
				   name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
				   value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>"><?php esc_html_e( 'Select Category:', 'random-banner' ); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'category' ) ); ?>"><?php echo bc_rb_get_category_by_array_js( $category ); ?></select>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'slider' ) ); ?>"><?php esc_html_e( 'Enable Slider?', 'random-banner' ); ?></label>
			<select class="widefat bc_rb_enable_slider" id="<?php echo esc_attr( $this->get_field_id( 'slider' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'slider' ) ); ?>"><?php echo bc_rb_get_enable_slider( $slider ); ?></select>
		</p>


		<div class="autoplay_options">
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'autoplay' ) ); ?>"><?php esc_html_e( 'Autoplay:', 'random-banner' ); ?></label>
				<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'autoplay' ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( 'autoplay' ) ); ?>"><?php echo bc_rb_get_slider_autoplay( $autoplay ); ?></select>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'delay' ) ); ?>"><?php esc_html_e( 'Delay:(ms)', 'random-banner' ); ?></label>
				<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'delay' ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( 'delay' ) ); ?>"><?php echo bc_rb_get_slider_delay( $delay ); ?></select>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'loop' ) ); ?>"><?php esc_html_e( 'Loop:', 'random-banner' ); ?></label>
				<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'loop' ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( 'loop' ) ); ?>"><?php echo bc_rb_get_slider_loop( $loop ); ?></select>
			</p>

			<p>
				<label
					for="<?php echo esc_attr( $this->get_field_id( 'dots' ) ); ?>"><?php esc_html_e( 'Show Dots Under the Slider:', 'random-banner' ); ?></label>
				<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'dots' ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( 'dots' ) ); ?>"><?php echo bc_rb_get_slider_loop( $dots ); ?></select>
			</p>
		</div>
		<?php
	}

	/**
	 * Post Widget data to save
	 *
	 * @param array $new_instance New Instance.
	 * @param array $old_instance Old Instance.
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance             = array();
		$instance['title']    = ( ! empty( $new_instance['title'] ) ) ? bc_rb_sanitize_text_field( $new_instance['title'] ) : '';
		$instance['category'] = ( ! empty( $new_instance['category'] ) ) ? bc_rb_sanitize_text_field( $new_instance['category'] ) : '';
		$instance['autoplay'] = ( ! empty( $new_instance['autoplay'] ) ) ? bc_rb_sanitize_text_field( $new_instance['autoplay'] ) : 'false';
		$instance['delay']    = ( ! empty( $new_instance['delay'] ) ) ? bc_rb_sanitize_text_field( $new_instance['delay'] ) : '';
		$instance['loop']     = ( ! empty( $new_instance['loop'] ) ) ? bc_rb_sanitize_text_field( $new_instance['loop'] ) : '';
		$instance['slider']   = ( ! empty( $new_instance['slider'] ) ) ? bc_rb_sanitize_text_field( $new_instance['slider'] ) : '';
		$instance['dots']     = ( ! empty( $new_instance['dots'] ) ) ? bc_rb_sanitize_text_field( $new_instance['dots'] ) : '';

		return $instance;
	}
}

/**
 * Random banner widget
 */
function bc_random_banner_widget() {
	register_widget( 'random_banner_widget' );
}

add_action( 'widgets_init', 'bc_random_banner_widget' );


