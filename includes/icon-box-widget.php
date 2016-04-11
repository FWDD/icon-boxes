<?php

/**
 * Class RivalMind_Icon_Boxes
 * @see WP_Widget
 */
class RivalMind_Icon_Boxes extends WP_Widget {
	/**
	 * The text domain for i18n translations
	 *
	 * @var string
	 */
	public $textDomain;

	/**
	 * Array of icon sizes
	 *
	 * @var array
	 */
	public $sizes;

	/**
	 * Array of column sizes
	 *
	 * @var array
	 */
	public $columns;

	/**
	 * Array of icon positions
	 *
	 * @var array
	 */
	public $positions;

	/**
	 * Sets up a new Icon Box Widget instance
	 *
	 * @access public
	 */
	function __construct() {
		$this->textDomain = 'rivalmind-icon-boxes';

		$this->sizes = array( '1', '2', '3', '4', '5', '6', '7', '8' );

		$this->positions = array( 'top', 'right', 'bottom', 'left' );

		$this->columns = array( '1', '2', '3', '4' );

		$widget_options = array(
			'classname'   => 'rivalmind-icon-boxes',
			'description' => __( 'Displays text widget with Icons.', $this->textDomain ),
		);

		$control_options = array(
			'id_base' => 'rivalmind-icon-boxes',
			'width'   => 400,
		);
		parent::__construct( 'rivalmind-icon-boxes', __( 'RivalMind Icon Boxes', $this->textDomain ), $widget_options, $control_options );

		/**
		 * Load font-awesome.css
		 */
		add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts' ), 8 );

	}

	/**
	 * Outputs the content for the current Icon widget instance
	 * @access public
	 *
	 * @param array $args Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Text widget instance.
	 */
	public function widget( $args, $instance ) {
		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title   = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$columns = ! empty($instance['columns']) ? $instance['columns'] : '';
		$button_color = ! empty($instance['button_color']) ? $instance['button_color'] : '#FF9800';
		$boxes = ! empty($instance['boxes']) ? $instance['boxes'] : array();
		/**
		 * Start the widget output
		 */
		echo $args['before_widget'];
		echo '<div class="rivalmind-icon-boxes">';

		/**
		 * Output the widget title
		 */
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		echo '<div class="rivalmind-icon-box-row">';
		/**
		 * Loop through the boxes and output box content
		 */
		foreach ( $boxes as $box ) {
			$heading     = ! empty( $box['heading'] ) ? $box['heading'] : '';
			$icon        = ! empty( $box['icon'] ) ? $box['icon'] : '';
			$position    = ! empty( $box['position'] ) ? $box['position'] : '';
			$size        = ! empty( $box['size'] ) ? 'font-size: ' . $box['size'] . 'rem;' : '';
			$icon_color  = ! empty( $box['icon_color'] ) ? 'color: ' . $box['icon_color'] . ';' : '';
			$widget_text = ! empty( $box['text'] ) ? $box['text'] : '';
			$button_text = ! empty( $box['button_text'] ) ? $box['button_text'] : '';
			$button_link = ! empty( $box['button_link'] ) ? $box['button_link'] : '';
			$iconStyles  = 'style="' . $icon_color . $size . '"';
			$class       = ' rivalmind-icon';
			
			/**
			 * Filter the content of the text widget
			 *
			 * @param string $widget_text The widget content.
			 * @param array $instance Array of settings for the current widget.
			 * @param WP_Widget_Text $this Current Text widget instance.
			 */
			$text = apply_filters( 'widget_text', $widget_text, $instance, $this );
			
			if ( $position == 'right' ) {
				$class .= ' rivalmind-icon-right';
			}
			if ( $position == 'left' ) {
				$class .= ' rivalmind-icon-left';
			}

			$iconOutput = '';
			if ( ! empty( $icon ) ) {
				$iconOutput = '<i class="fa fa-' . $icon . $class . '" ' . $iconStyles . '></i>';
			}

			echo '<div class="rivalmind-icon-box rivalmind-' . $columns . '">';

			if ( $position == 'top' ) {
				echo $iconOutput;
			}

			echo '<h4 class="accented">' . esc_html($heading) . '</h4>';

			if ( $position == 'bottom' ) {
				echo $iconOutput;
			}
			if ( $position == 'right' || $position == 'left' ) {
				echo $iconOutput;
			}
			echo( ! empty( $box['filter'] ) ? wpautop( $text ) : $text );

			if ( ! empty( $box['button_text'] ) ) {
				echo '<div class="rivalmind-icon-button"><a class="rivalmind-cta-button" href="' . esc_url($button_link) . '" style="background:' . $button_color . ';">' . esc_html($button_text) . '</a></div>';
			}
			echo '</div><!-- end rivalmind-icon-box-->';
		} // End foreach
		echo '</div><!-- end rivalmind-icon-box-row-->';
		echo '</div><!-- end rivalmind-icon-boxes-->';
		echo $args['after_widget'];
	}

	/**
	 * Form validation.
	 *
	 * Runs when you save the widget form. Allows you to validate widget options before they are saved.
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance            = array();
		$instance['title']   = $new_instance['title'];
		$instance['columns'] = ! empty( $new_instance['columns'] ) ? $new_instance['columns'] : '4';
		$instance['button_color'] = !empty( $new_instance['button_color']) ? $new_instance['button_color'] : '#FF9800';
		//Clear old boxes
		unset( $instance['boxes'] );
		$instance['boxes'] = array();
		foreach ( $new_instance['boxes'] as $box ) {
			$heading     = $box['heading'];
			$icon        = $box['icon'];
			$position    = $box['position'];
			$size        = $box['size'];
			$icon_color  = $box['icon_color'];
			$button_text = $box['button_text'];
			$button_link = esc_url_raw($box['button_link']);
			if ( current_user_can( 'unfiltered_html' ) ) {
				$text = $box['text'];
			} else {
				$text = wp_kses_post( stripslashes( $box['text'] ) );
			}
			$filter = ! empty( $box['filter'] );

			if ( ! empty( $heading ) || ! empty( $icon ) || ! empty( $text ) ) {
				$instance['boxes'][] = array(
					'heading'     => $heading,
					'icon'        => $icon,
					'position'    => $position,
					'size'        => $size,
					'icon_color'  => $icon_color,
					'button_text' => $button_text,
					'button_link' => $button_link,
					'text'        => $text,
					'filter'      => $filter
				);
			}
		}

		return $instance;
	}

	/**
	 * Widget Form.
	 *
	 * Outputs the widget form that allows users to control the output of the widget.
	 *
	 * @param array $instance
	 *
	 * @return string|void
	 */
	function form( $instance ) {
		$box      = array();
		$box[]    = array(
			'heading'     => '',
			'icon'        => '',
			'position'    => 'top',
			'size'        => '16',
			'icon_color'  => '#000000',
			'button_text' => '',
			'button_link' => '',
			'text'        => '',
			'filter'      => 0
		);
		$defaults = array(
			'title' => '',
			'columns' => 3,
			'boxes' => $box
		);
		/** Merge with defaults */
		$instance = wp_parse_args( (array) $instance, $defaults );

		$title = sanitize_text_field( $instance['title'] );

		$button_color = !empty($instance['button_color']) ? $instance['button_color'] : '#FF9800';

		$boxes = ! empty( $instance['boxes'] ) ? $instance['boxes'] : array();

		$boxCount = count( $boxes );

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', $this->textDomain ); ?>
				:</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
			       name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
			       value="<?php echo esc_attr( $title ); ?>"/>
		</p>
		<p>
			<label
				for="<?php echo $this->get_field_id( 'columns' ); ?>"><?php _e( 'Number of columns', $this->textDomain ); ?>
				:</label>
			<select name="<?php echo $this->get_field_name( 'columns' ); ?>"
			        class="rm-input-field">
				<?php
				foreach ( (array) $this->columns as $columns ) {
					printf( '<option value="%s" %s>%s</option>', $columns, selected( $columns, $instance['columns'], 0 ), $columns );
				}
				?>
			</select>
		</p>
		<p>
			<label><?php _e( 'Button color', $this->textDomain ); ?>
				<input class="rm-button-color-picker"
				       name="<?php echo $this->get_field_name( 'button_color' ); ?>"
				       type="text"
				       value="<?php echo $button_color; ?>" data-default-color="#FF9800"/>
			</label>
		</p>
		<div class="rm-icon-boxes">
			<?php
			for ( $i = 0; $i < $boxCount; $i ++ ) {
				$heading     = $boxes[ $i ]['heading'];
				$icon_color  = $boxes[ $i ]['icon_color'];
				$button_text = $boxes[ $i ]['button_text'];
				$button_link = $boxes[ $i ]['button_link'];
				$text        = $boxes[ $i ]['text'];
				$filter      = isset( $boxes[ $i ]['filter'] ) ? $boxes[ $i ]['filter'] : 0;
				?>
				<div class="rm-icon-box-form">
					<h3><?php printf( __( 'Box #%s', 'ledger' ), $i + 1 ); ?></h3>
					<p>
						<label><?php _e( 'Heading', $this->textDomain ); ?>
							<input type="text" class="widefat rm-input-field"
							       name="<?php echo $this->get_field_name( 'boxes[' . $i . '][heading]' ); ?>"
							       value="<?php echo $heading; ?>"/>
						</label>
					</p>
					<p class="rm_chosen_select">
						<label><?php _e( 'Icon', $this->textDomain ); ?>
							<select name="<?php echo $this->get_field_name( 'boxes[' . $i . '][icon]' ); ?>"
							        class="chosen-select rm-input-field" data-placeholder="Choose an Icon">
								<?php
								foreach ( RivalMind_Get_Icon_List() as $ico ) {
									printf( '<option value="%s" %s>%s</option>', $ico, selected( $ico, $boxes[ $i ]['icon'], 0 ), $ico );
								}
								?>
							</select>
						</label>
					</p>
					<p>
						<label><?php _e( 'Icon size', $this->textDomain ); ?>
							<select name="<?php echo $this->get_field_name( 'boxes[' . $i . '][size]' ); ?>"
							        class="rm-input-field">
								<?php
								foreach ( (array) $this->sizes as $size ) {
									printf( '<option value="%d" %s>%d rem</option>', (int) $size, selected( $size, $boxes[ $i ]['size'], 0 ), (int) $size );
								}
								?>
							</select>
						</label>
					</p>
					<p>
						<label><?php _e( 'Icon color', $this->textDomain ); ?>
							<input class="rm-color-picker rm-input-field"
							       name="<?php echo $this->get_field_name( 'boxes[' . $i . '][icon_color]' ); ?>"
							       type="text"
							       value="<?php echo $icon_color; ?>"/>
						</label>
					</p>
					<p>
						<label><?php _e( 'Icon position', $this->textDomain ); ?>
							<select name="<?php echo $this->get_field_name( 'boxes[' . $i . '][position]' ); ?>"
							        class="rm-input-field">
								<?php
								foreach ( (array) $this->positions as $position ) {
									printf( '<option value="%s" %s>%s</option>', $position, selected( $position, $boxes[ $i ]['position'], 0 ), $position );
								}
								?>
							</select>
						</label>
					</p>
					<p>
						<label><?php _e( 'Text', $this->textDomain ); ?>
							<textarea class="widefat rm-input-field" rows="8" cols="20"
							          name="<?php echo $this->get_field_name( 'boxes[' . $i . '][text]' ); ?>"><?php echo esc_textarea( $text ); ?></textarea>
						</label>
					</p>
					<p>
						<label>
							<input name="<?php echo $this->get_field_name( 'boxes[' . $i . '][filter]' ); ?>"
							       class=" rm-input-field"
							       type="checkbox"<?php checked( $filter ); ?> />&nbsp;<?php _e( 'Automatically add paragraphs', $this->textDomain ); ?>
						</label>
					</p>
					<p>
						<label><?php _e( 'Button Text', $this->textDomain ); ?>
							<input type="text" class="widefat rm-input-field"
							       name="<?php echo $this->get_field_name( 'boxes[' . $i . '][button_text]' ); ?>"
							       value="<?php echo $button_text; ?>"/>
						</label>
					</p>
					<p>
						<label><?php _e( 'Button Link', $this->textDomain ); ?>
							<input type="text" class="widefat rm-input-field"
							       name="<?php echo $this->get_field_name( 'boxes[' . $i . '][button_link]' ); ?>"
							       value="<?php echo $button_link; ?>"/>
						</label>
					</p>
					<a class="rm-remove-icon-box" href="#">Remove</a>
				</div>

				<?php
			}
			?>
		</div>

		<a class="rm-add-icon-box" href="#" style="display:block;text-align: center;margin:10px 0;">Add Box</a>

		<?php

	}

	/**
	 * Load FontAwesome 4.5.0 from CDN
	 * and our base styles if the widget is active
	 */
	public function load_scripts() {
		if ( ! wp_style_is( "fontawesome", "registered" ) ) {
			wp_register_style( "fontawesome", "//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css", array(), "4.5.0", "all" );
		}

		if ( ! is_admin() && is_active_widget( false, false, $this->id_base, true ) ) {
			wp_enqueue_style( 'fontawesome' );
			wp_register_style( "rm-icons", plugins_url( 'css/rm-icon-boxes.css', dirname( __FILE__ ) ) );
			wp_enqueue_style( 'rm-icons' );
		}
	}

}