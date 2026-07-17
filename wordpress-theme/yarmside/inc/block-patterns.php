<?php
/**
 * Block patterns: the design's sections as reusable, editable blocks.
 *
 * Every pattern uses core blocks with the theme's CSS classes, so pages
 * built from them (and any new page) carry the same branding.
 *
 * @package Yarmside
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the pattern category and all patterns.
 */
function yarmside_register_block_patterns() {
	register_block_pattern_category(
		'yarmside',
		array( 'label' => __( 'Yarmside', 'yarmside' ) )
	);

	$patterns = array(
		'service-tiers' => array(
			'title'   => __( 'Service tiers (three columns)', 'yarmside' ),
			'content' => yarmside_pattern_service_tiers(),
		),
		'process'       => array(
			'title'   => __( 'Process stages (orange band)', 'yarmside' ),
			'content' => yarmside_pattern_process(),
		),
		'testimonial'   => array(
			'title'   => __( 'Testimonial', 'yarmside' ),
			'content' => yarmside_pattern_testimonial(),
		),
		'cta'           => array(
			'title'   => __( 'Call to action (dark band)', 'yarmside' ),
			'content' => yarmside_pattern_cta(),
		),
		'spec-list'     => array(
			'title'   => __( 'Specification list (dimension lines)', 'yarmside' ),
			'content' => yarmside_pattern_spec_list(),
		),
		'team'          => array(
			'title'   => __( 'Team grid', 'yarmside' ),
			'content' => yarmside_pattern_team(),
		),
		'prose-image'   => array(
			'title'   => __( 'Prose beside a photograph', 'yarmside' ),
			'content' => yarmside_pattern_prose_image(),
		),
		'contact'       => array(
			'title'   => __( 'Contact details and enquiry form', 'yarmside' ),
			'content' => yarmside_pattern_contact(),
		),
	);

	foreach ( $patterns as $slug => $pattern ) {
		register_block_pattern(
			'yarmside/' . $slug,
			array(
				'title'      => $pattern['title'],
				'content'    => $pattern['content'],
				'categories' => array( 'yarmside' ),
			)
		);
	}
}
add_action( 'init', 'yarmside_register_block_patterns' );

/**
 * A single service tier column.
 *
 * @param string $name Tier name.
 * @param string $for  Who it's for.
 * @param array  $list Bullet items.
 * @param string $fee  Fee line.
 * @param bool   $lead Lead (dark) tier.
 * @return string Block markup.
 */
function yarmside_pattern_tier( $name, $for, $list, $fee, $lead = false ) {
	$class = $lead ? 'tier tier--lead' : 'tier';
	$items = '';
	foreach ( $list as $item ) {
		$items .= '<!-- wp:list-item --><li>' . $item . '</li><!-- /wp:list-item -->';
	}

	return '<!-- wp:group {"className":"' . $class . '","layout":{"type":"default"}} -->
<div class="wp-block-group ' . $class . '"><!-- wp:heading {"level":3,"className":"tier__name"} --><h3 class="wp-block-heading tier__name">' . $name . '</h3><!-- /wp:heading -->
<!-- wp:paragraph {"className":"tier__for"} --><p class="tier__for">' . $for . '</p><!-- /wp:paragraph -->
<!-- wp:list --><ul class="wp-block-list">' . $items . '</ul><!-- /wp:list -->
<!-- wp:paragraph {"className":"tier__fee"} --><p class="tier__fee">' . $fee . '</p><!-- /wp:paragraph --></div>
<!-- /wp:group -->';
}

/**
 * Service tiers section (landlords page).
 */
function yarmside_pattern_service_tiers() {
	$tier_1 = yarmside_pattern_tier(
		__( 'Tenant find', 'yarmside' ),
		__( 'For landlords who manage day to day themselves and just want the right tenant, found properly.', 'yarmside' ),
		array(
			__( 'Valuation and rental appraisal', 'yarmside' ),
			__( 'Photography and listing on the major portals', 'yarmside' ),
			__( 'Accompanied viewings', 'yarmside' ),
			__( 'Full referencing, right to rent checks and credit checks', 'yarmside' ),
			__( 'Tenancy agreement and deposit registration', 'yarmside' ),
		),
		__( 'Fee: one-off, quoted at valuation. We publish a plain fee sheet, ask us for it.', 'yarmside' )
	);

	$tier_2 = yarmside_pattern_tier(
		__( 'Fully managed', 'yarmside' ),
		__( 'Our main service. Most landlords who join us choose this, especially those who don\'t live locally.', 'yarmside' ),
		array(
			__( 'Everything in tenant find', 'yarmside' ),
			__( 'Rent collection and arrears handling', 'yarmside' ),
			__( 'Repairs coordinated with trusted local trades', 'yarmside' ),
			__( 'Quarterly inspections with a written note to you', 'yarmside' ),
			__( 'Compliance tracked and renewed before it lapses', 'yarmside' ),
			__( 'One clear statement, issued the same day every month', 'yarmside' ),
		),
		__( 'Fee: a percentage of monthly rent, quoted at valuation. No hidden extras, no mark-up on contractor invoices.', 'yarmside' ),
		true
	);

	$tier_3 = yarmside_pattern_tier(
		__( 'Rent collection', 'yarmside' ),
		__( 'For landlords who handle repairs themselves but want the money side run properly.', 'yarmside' ),
		array(
			__( 'Everything in tenant find', 'yarmside' ),
			__( 'Rent collected by standing order', 'yarmside' ),
			__( 'Arrears chased early and courteously', 'yarmside' ),
			__( 'Monthly statement on the same day each month', 'yarmside' ),
			__( 'Annual income summary for your tax return', 'yarmside' ),
		),
		__( 'Fee: a smaller percentage of monthly rent, quoted at valuation.', 'yarmside' )
	);

	return '<!-- wp:group {"className":"section-head","layout":{"type":"default"}} -->
<div class="wp-block-group section-head"><!-- wp:group {"className":"section-head__titles","layout":{"type":"default"}} -->
<div class="wp-block-group section-head__titles"><!-- wp:heading {"className":"h2"} --><h2 class="wp-block-heading h2">' . __( 'Choose how much you want to hand over.', 'yarmside' ) . '</h2><!-- /wp:heading --></div>
<!-- /wp:group -->
<!-- wp:paragraph {"className":"lede"} --><p class="lede">' . __( 'Every service starts with the same free valuation. You can move between levels later, plenty of our fully managed landlords started with tenant find only.', 'yarmside' ) . '</p><!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"tiers","layout":{"type":"default"}} -->
<div class="wp-block-group tiers">' . $tier_1 . $tier_2 . $tier_3 . '</div>
<!-- /wp:group -->

<!-- wp:paragraph {"className":"form__note"} --><p class="form__note">' . __( 'We don\'t publish headline percentages here because they depend on the property and what it needs. What we will promise: the quote you get at valuation is the fee you pay, in writing, before you commit to anything.', 'yarmside' ) . '</p><!-- /wp:paragraph -->';
}

/**
 * A single process stage.
 *
 * @param string $index Index label.
 * @param string $title Stage title.
 * @param string $text  Stage copy.
 * @return string Block markup.
 */
function yarmside_pattern_stage( $index, $title, $text ) {
	return '<!-- wp:group {"className":"stage","layout":{"type":"default"}} -->
<div class="wp-block-group stage"><!-- wp:paragraph {"className":"stage__index"} --><p class="stage__index">' . $index . '</p><!-- /wp:paragraph -->
<!-- wp:group {"className":"stage__rule","layout":{"type":"default"}} --><div class="wp-block-group stage__rule"></div><!-- /wp:group -->
<!-- wp:paragraph {"className":"stage__title"} --><p class="stage__title">' . $title . '</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>' . $text . '</p><!-- /wp:paragraph --></div>
<!-- /wp:group -->';
}

/**
 * The four-stage process band.
 */
function yarmside_pattern_process() {
	$stages = yarmside_pattern_stage( __( '01 · Valuation', 'yarmside' ), __( 'An honest read of the market', 'yarmside' ), __( 'A face to face visit, a fee-free recommendation on price, backed by real comparables on your street. If we think you\'d do better selling, or waiting, we\'ll say so.', 'yarmside' ) )
		. yarmside_pattern_stage( __( '02 · Preparation', 'yarmside' ), __( 'Everything ready before marketing', 'yarmside' ), __( 'Photography, a proper description, and the compliance work, EICR, gas safety, EPC and smoke alarms, done first so nothing holds the tenancy up later.', 'yarmside' ) )
		. yarmside_pattern_stage( __( '03 · Tenant find', 'yarmside' ), __( 'Referenced, and met in person', 'yarmside' ), __( 'Listed on the major portals, and matched against our own list of registered tenants. Every applicant is referenced and met before we recommend them to you.', 'yarmside' ) )
		. yarmside_pattern_stage( __( '04 · Management', 'yarmside' ), __( 'One statement, same day, every month', 'yarmside' ), __( 'Rent collected, repairs coordinated, inspections done and written up. You get one clear statement on the same day every month, so you always know where things stand.', 'yarmside' ) );

	return '<!-- wp:group {"className":"alignfull section process-section","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull section process-section"><!-- wp:group {"className":"wrap","layout":{"type":"default"}} -->
<div class="wp-block-group wrap"><!-- wp:group {"className":"section-head","layout":{"type":"default"}} -->
<div class="wp-block-group section-head"><!-- wp:group {"className":"section-head__titles","layout":{"type":"default"}} -->
<div class="wp-block-group section-head__titles"><!-- wp:heading {"className":"h2"} --><h2 class="wp-block-heading h2">' . __( 'From valuation to move-in, in four stages, always in this order.', 'yarmside' ) . '</h2><!-- /wp:heading --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
<!-- wp:group {"className":"stages","layout":{"type":"default"}} -->
<div class="wp-block-group stages">' . $stages . '</div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->';
}

/**
 * Testimonial pull-quote.
 */
function yarmside_pattern_testimonial() {
	return '<!-- wp:quote {"className":"testimonial"} -->
<blockquote class="wp-block-quote testimonial"><!-- wp:paragraph {"className":"testimonial__quote"} --><p class="testimonial__quote">' . __( '"Yarmside took over our two houses from a national chain three years ago. The rent arrives with a statement I can actually read, on the same day every month. Letting has stopped being something I think about."', 'yarmside' ) . '</p><!-- /wp:paragraph --><cite class="testimonial__cite">' . __( 'A landlord, Eaglescliffe', 'yarmside' ) . '</cite></blockquote>
<!-- /wp:quote -->';
}

/**
 * The dark call-to-action band.
 */
function yarmside_pattern_cta() {
	return '<!-- wp:group {"className":"alignfull section cta","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull section cta"><!-- wp:group {"className":"wrap cta__grid","layout":{"type":"default"}} -->
<div class="wp-block-group wrap cta__grid"><!-- wp:heading {"className":"h2"} --><h2 class="wp-block-heading h2">' . __( 'Start with a free valuation.', 'yarmside' ) . '</h2><!-- /wp:heading -->
<!-- wp:group {"className":"cta__panel","layout":{"type":"default"}} -->
<div class="wp-block-group cta__panel"><!-- wp:paragraph {"className":"lede"} --><p class="lede">' . __( 'We\'ll visit your property, give you an honest read of the market, and explain exactly how we\'d manage it, with no pressure to instruct.', 'yarmside' ) . '</p><!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="/contact/">' . __( 'Request a valuation', 'yarmside' ) . '</a></div><!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->';
}

/**
 * One dimension line for the spec list pattern.
 *
 * @param string $label Left label.
 * @param string $value Right value.
 * @return string Block markup.
 */
function yarmside_pattern_dim_line( $label, $value ) {
	return '<!-- wp:paragraph {"className":"dim-line"} --><p class="dim-line"><span class="dim-line__label">' . $label . '</span><span class="dim-line__rule"></span><span class="dim-line__value">' . $value . '</span></p><!-- /wp:paragraph -->';
}

/**
 * Compliance-style specification list.
 */
function yarmside_pattern_spec_list() {
	$lines = yarmside_pattern_dim_line( __( 'Electrical safety', 'yarmside' ), __( 'EICR renewed every five years', 'yarmside' ) )
		. yarmside_pattern_dim_line( __( 'Gas safety', 'yarmside' ), __( 'CP12 certificate renewed annually', 'yarmside' ) )
		. yarmside_pattern_dim_line( __( 'Energy performance', 'yarmside' ), __( 'EPC in place and re-run when it expires', 'yarmside' ) )
		. yarmside_pattern_dim_line( __( 'Alarms', 'yarmside' ), __( 'Smoke and CO alarms checked at every inspection', 'yarmside' ) )
		. yarmside_pattern_dim_line( __( 'Deposits', 'yarmside' ), __( 'Protected in a government-approved scheme', 'yarmside' ) )
		. yarmside_pattern_dim_line( __( 'Right to rent', 'yarmside' ), __( 'Checked for every adult occupant', 'yarmside' ) );

	return '<!-- wp:group {"className":"spec-list","layout":{"type":"default"}} -->
<div class="wp-block-group spec-list">' . $lines . '</div>
<!-- /wp:group -->';
}

/**
 * Team grid with three honest placeholder cards.
 */
function yarmside_pattern_team() {
	$card = '<!-- wp:group {"className":"team-card","layout":{"type":"default"}} -->
<div class="wp-block-group team-card"><!-- wp:paragraph {"className":"team-card__flag"} --><p class="team-card__flag">' . __( 'Placeholder: team member needed', 'yarmside' ) . '</p><!-- /wp:paragraph -->
<!-- wp:heading {"level":3,"className":"h3"} --><h3 class="wp-block-heading h3">' . __( 'Name to follow', 'yarmside' ) . '</h3><!-- /wp:heading -->
<!-- wp:paragraph --><p>' . __( 'Role, a line about their lettings experience, and what they look after day to day.', 'yarmside' ) . '</p><!-- /wp:paragraph --></div>
<!-- /wp:group -->';

	return '<!-- wp:group {"className":"section-head","layout":{"type":"default"}} -->
<div class="wp-block-group section-head"><!-- wp:group {"className":"section-head__titles","layout":{"type":"default"}} -->
<div class="wp-block-group section-head__titles"><!-- wp:heading {"className":"h2"} --><h2 class="wp-block-heading h2">' . __( 'The people who\'ll answer the phone.', 'yarmside' ) . '</h2><!-- /wp:heading --></div>
<!-- /wp:group -->
<!-- wp:paragraph {"className":"lede"} --><p class="lede">' . __( 'Photographs and short bios of the real team belong here. We won\'t publish stand-ins, so these slots stay honest until the real people are ready.', 'yarmside' ) . '</p><!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"team-grid","layout":{"type":"default"}} -->
<div class="wp-block-group team-grid">' . $card . $card . $card . '</div>
<!-- /wp:group -->';
}

/**
 * Prose column beside a photograph (about page).
 */
function yarmside_pattern_prose_image() {
	return '<!-- wp:columns {"className":"detail-grid-cols"} -->
<div class="wp-block-columns detail-grid-cols"><!-- wp:column {"width":"60%"} -->
<div class="wp-block-column" style="flex-basis:60%"><!-- wp:heading {"className":"h2"} --><h2 class="wp-block-heading h2">' . __( 'How we work', 'yarmside' ) . '</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>' . __( 'The lettings industry drifted somewhere odd. Portfolios got huge, offices got quiet, and the person managing your property became a ticket number in someone\'s inbox. We think a property deserves better than that, and so does the person living in it.', 'yarmside' ) . '</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>' . __( 'So we keep the practice small on purpose. Every home on our books has been visited, photographed and described by one of us in person. Every tenant has sat across a table from us. Every landlord knows who picks up the phone, because it\'s always one of the same few people.', 'yarmside' ) . '</p><!-- /wp:paragraph --></div>
<!-- /wp:column -->
<!-- wp:column {"width":"40%"} -->
<div class="wp-block-column" style="flex-basis:40%"><!-- wp:image {"className":"frame"} -->
<figure class="wp-block-image frame"><img src="" alt=""/></figure>
<!-- /wp:image --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->';
}

/**
 * Contact page layout: office details beside the enquiry form.
 */
function yarmside_pattern_contact() {
	return '<!-- wp:group {"className":"contact-grid","layout":{"type":"default"}} -->
<div class="wp-block-group contact-grid"><!-- wp:group {"className":"contact-details","layout":{"type":"default"}} -->
<div class="wp-block-group contact-details"><!-- wp:heading {"level":2,"className":"h3"} --><h2 class="wp-block-heading h3">' . __( 'The office', 'yarmside' ) . '</h2><!-- /wp:heading -->
<!-- wp:shortcode -->[yarmside_contact_details]<!-- /wp:shortcode -->
<!-- wp:paragraph {"className":"form__note"} --><p class="form__note">' . __( 'If your enquiry is about a repair in a home we manage, please use the phone during office hours, and the out of hours number in your welcome pack for emergencies.', 'yarmside' ) . '</p><!-- /wp:paragraph --></div>
<!-- /wp:group -->
<!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group"><!-- wp:heading {"className":"h2"} --><h2 class="wp-block-heading h2">' . __( 'Request a valuation', 'yarmside' ) . '</h2><!-- /wp:heading -->
<!-- wp:shortcode -->[yarmside_valuation_form]<!-- /wp:shortcode --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->';
}
