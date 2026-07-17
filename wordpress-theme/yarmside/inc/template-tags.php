<?php
/**
 * Template helpers shared across the theme.
 *
 * @package Yarmside
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The site logo: custom logo if set, otherwise the bundled brand file.
 *
 * @param string $context 'header' or 'footer'.
 */
function yarmside_logo( $context = 'header' ) {
	$home = esc_url( home_url( '/' ) );
	$name = get_bloginfo( 'name' );

	if ( has_custom_logo() ) {
		$logo_id  = get_theme_mod( 'custom_logo' );
		$logo_img = wp_get_attachment_image( $logo_id, 'full', false, array( 'alt' => esc_attr( $name ) ) );
	} else {
		$logo_img = sprintf(
			'<img src="%s" alt="%s" width="1999" height="571">',
			esc_url( get_template_directory_uri() . '/assets/images/yarmside-logo.png' ),
			esc_attr( $name )
		);
	}

	if ( 'footer' === $context ) {
		echo '<span class="logo">' . $logo_img . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput
		return;
	}

	printf(
		'<a class="logo" href="%s" aria-label="%s">%s</a>',
		$home, // phpcs:ignore WordPress.Security.EscapeOutput
		/* translators: %s: site name. */
		esc_attr( sprintf( __( '%s, home', 'yarmside' ), $name ) ),
		$logo_img // phpcs:ignore WordPress.Security.EscapeOutput
	);
}

/**
 * Primary navigation with a plain-anchor fallback before a menu is set.
 *
 * @param string $aria_label Accessible label for the nav element.
 */
function yarmside_primary_nav_links( $aria_label ) {
	if ( has_nav_menu( 'primary' ) ) {
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'container'      => false,
				'items_wrap'     => '%3$s',
				'walker'         => new Yarmside_Nav_Walker(),
				'depth'          => 1,
			)
		);
		return;
	}

	// Fallback: the design's five destinations, resolved dynamically.
	$links = array();

	$archive = get_post_type_archive_link( 'property' );
	if ( $archive ) {
		$links[ $archive ] = __( 'Properties', 'yarmside' );
	}
	$page = get_page_by_path( 'landlords' );
	if ( $page ) {
		$links[ get_permalink( $page ) ] = __( 'Landlords', 'yarmside' );
	}
	$blog = get_option( 'page_for_posts' );
	if ( $blog ) {
		$links[ get_permalink( $blog ) ] = get_the_title( $blog );
	}
	foreach ( array( 'about' => __( 'About', 'yarmside' ), 'contact' => __( 'Contact', 'yarmside' ) ) as $slug => $label ) {
		$page = get_page_by_path( $slug );
		if ( $page ) {
			$links[ get_permalink( $page ) ] = $label;
		}
	}

	foreach ( $links as $url => $label ) {
		printf( '<a href="%s">%s</a>', esc_url( $url ), esc_html( $label ) );
	}
}

/**
 * Format a numeric monthly rent: "£1,395 pcm".
 *
 * @param string|int $price Raw meta value.
 * @return string
 */
function yarmside_format_price( $price ) {
	$number = preg_replace( '/[^0-9.]/', '', (string) $price );
	if ( '' === $number ) {
		return (string) $price;
	}
	/* translators: %s: formatted monthly rent amount. */
	return sprintf( __( '£%s pcm', 'yarmside' ), number_format_i18n( (float) $number ) );
}

/**
 * Status label + modifier class for a property.
 *
 * @param int $post_id Property ID.
 * @return array [label, css modifier].
 */
function yarmside_property_status( $post_id ) {
	$statuses = yarmside_property_statuses();
	$status   = get_post_meta( $post_id, '_yarmside_status', true );
	if ( ! isset( $statuses[ $status ] ) ) {
		$status = 'available';
	}
	$modifier = array(
		'available' => 'status--let',
		'agreed'    => 'status--agreed',
		'let'       => 'status--gone',
	);
	return array( $statuses[ $status ], $modifier[ $status ], $status );
}

/**
 * The beds / baths / area dimension line for a property.
 *
 * @param int    $post_id Property ID.
 * @param string $variant '' or 'light'.
 * @param bool   $include_subtype Include the property description segment.
 */
function yarmside_property_dimline( $post_id, $variant = '', $include_subtype = false ) {
	$beds  = get_post_meta( $post_id, '_yarmside_beds', true );
	$baths = get_post_meta( $post_id, '_yarmside_baths', true );
	$sqft  = get_post_meta( $post_id, '_yarmside_sqft', true );

	$segments = array();
	if ( '' !== $beds ) {
		/* translators: %s: number of bedrooms. */
		$segments[] = array( 'label', sprintf( __( '%s bed', 'yarmside' ), $beds ) );
	}
	if ( '' !== $baths ) {
		/* translators: %s: number of bathrooms. */
		$segments[] = array( 'label', sprintf( __( '%s bath', 'yarmside' ), $baths ) );
	}
	if ( $include_subtype ) {
		$subtype = get_post_meta( $post_id, '_yarmside_subtype', true );
		if ( $subtype ) {
			$segments[] = array( 'label', $subtype );
		}
	}
	if ( '' !== $sqft ) {
		/* translators: %s: floor area in square feet. */
		$segments[] = array( 'value', sprintf( __( '%s sq ft', 'yarmside' ), number_format_i18n( (float) preg_replace( '/[^0-9.]/', '', $sqft ) ) ) );
	}

	if ( ! $segments ) {
		return;
	}

	$class = 'dim-line' . ( 'light' === $variant ? ' dim-line--light' : '' );
	echo '<p class="' . esc_attr( $class ) . '">';
	$last = count( $segments ) - 1;
	foreach ( $segments as $i => $segment ) {
		printf( '<span class="dim-line__%s">%s</span>', esc_attr( $segment[0] ), esc_html( $segment[1] ) );
		if ( $i < $last ) {
			echo '<span class="dim-line__rule"></span>';
		}
	}
	echo '</p>';
}

/**
 * The uppercase tag line above a card title: "Detached house · To let".
 *
 * @param int $post_id Property ID.
 * @return string
 */
function yarmside_property_tag( $post_id ) {
	$parts   = array();
	$subtype = get_post_meta( $post_id, '_yarmside_subtype', true );
	if ( $subtype ) {
		$parts[] = $subtype;
	} else {
		$types = get_the_terms( $post_id, 'property_type' );
		if ( $types && ! is_wp_error( $types ) ) {
			$parts[] = $types[0]->name;
		}
	}
	$status  = yarmside_property_status( $post_id );
	$parts[] = $status[0];

	return implode( ' · ', $parts );
}

/**
 * Data attributes used by the front-end archive filters.
 *
 * @param int $post_id Property ID.
 */
function yarmside_property_filter_atts( $post_id ) {
	$types  = get_the_terms( $post_id, 'property_type' );
	$type   = ( $types && ! is_wp_error( $types ) ) ? $types[0]->slug : '';
	$status = yarmside_property_status( $post_id );

	printf( ' data-type="%s" data-status="%s"', esc_attr( $type ), esc_attr( $status[2] ) );
}

/**
 * Estimated reading time for journal articles: "6 min read".
 *
 * @param int $post_id Post ID.
 * @return string
 */
function yarmside_read_time( $post_id ) {
	$words   = str_word_count( wp_strip_all_tags( get_post_field( 'post_content', $post_id ) ) );
	$minutes = max( 1, (int) round( $words / 220 ) );
	/* translators: %d: estimated minutes of reading. */
	return sprintf( __( '%d min read', 'yarmside' ), $minutes );
}

/**
 * The URL used by every "Request a valuation" / "Get in touch" button.
 *
 * @return string
 */
function yarmside_contact_url() {
	$page = get_page_by_path( 'contact' );
	return $page ? get_permalink( $page ) : home_url( '/' );
}

/**
 * The properties archive URL.
 *
 * @return string
 */
function yarmside_properties_url() {
	$archive = get_post_type_archive_link( 'property' );
	return $archive ? $archive : home_url( '/' );
}

/**
 * Shared closing CTA band. Arguments are optional and fall back to
 * the Customizer values, which fall back to the design copy.
 *
 * @param array $args heading, lede, buttons => [[label, url, style]].
 */
function yarmside_cta( $args = array() ) {
	$defaults = array(
		'heading' => get_theme_mod( 'yarmside_cta_heading', __( 'Considering a let? Let\'s begin with a conversation.', 'yarmside' ) ),
		'lede'    => get_theme_mod( 'yarmside_cta_lede', __( 'Request a free, no obligation valuation. We\'ll visit your property, give you an honest read of the market, and explain exactly how we\'d manage it, with no pressure to instruct.', 'yarmside' ) ),
		'buttons' => array(
			array( __( 'Request a valuation', 'yarmside' ), yarmside_contact_url(), 'btn--primary' ),
		),
	);
	$args = wp_parse_args( $args, $defaults );
	?>
	<section class="section cta">
		<div class="wrap cta__grid">
			<h2 class="h2"><?php echo esc_html( $args['heading'] ); ?></h2>
			<div class="cta__panel">
				<p class="lede"><?php echo esc_html( $args['lede'] ); ?></p>
				<div class="btn-row">
					<?php foreach ( $args['buttons'] as $button ) : ?>
						<a class="btn <?php echo esc_attr( $button[2] ); ?>" href="<?php echo esc_url( $button[1] ); ?>"><?php echo esc_html( $button[0] ); ?></a>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>
	<?php
}
