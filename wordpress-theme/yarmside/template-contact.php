<?php
/**
 * Template Name: Contact with valuation form
 *
 * @package Yarmside
 */

get_header();

$sent  = isset( $_GET['valuation'] ) && 'sent' === $_GET['valuation'];   // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$error = isset( $_GET['valuation'] ) && 'error' === $_GET['valuation'];  // phpcs:ignore WordPress.Security.NonceVerification.Recommended

$email   = get_theme_mod( 'yarmside_contact_email', 'enquiries@yarmside.co.uk' );
$phone   = get_theme_mod( 'yarmside_contact_phone', '' );
$address = get_theme_mod( 'yarmside_contact_address', "High Street, Yarm\nStockton-on-Tees" );
$hours   = get_theme_mod( 'yarmside_contact_hours', '' );
?>

<main>
	<?php while ( have_posts() ) : the_post(); ?>

		<div class="page-head">
			<div class="wrap page-head__inner">
				<h1 class="h1"><?php the_title(); ?></h1>
				<?php if ( has_excerpt() ) : ?>
					<p class="lede"><?php echo esc_html( get_the_excerpt() ); ?></p>
				<?php endif; ?>
			</div>
		</div>

		<section class="section--tight">
			<div class="wrap contact-grid">

				<div class="contact-details">
					<div>
						<h2 class="h3"><?php esc_html_e( 'The office', 'yarmside' ); ?></h2>
						<address><?php echo nl2br( esc_html( $address ) ); ?></address>
					</div>

					<p class="dim-line">
						<span class="dim-line__label"><?php esc_html_e( 'Email', 'yarmside' ); ?></span>
						<span class="dim-line__rule"></span>
						<span class="dim-line__value"><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></span>
					</p>
					<?php if ( $phone ) : ?>
						<p class="dim-line">
							<span class="dim-line__label"><?php esc_html_e( 'Phone', 'yarmside' ); ?></span>
							<span class="dim-line__rule"></span>
							<span class="dim-line__value"><a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></span>
						</p>
					<?php endif; ?>
					<?php if ( $hours ) : ?>
						<p class="dim-line">
							<span class="dim-line__label"><?php esc_html_e( 'Hours', 'yarmside' ); ?></span>
							<span class="dim-line__rule"></span>
							<span class="dim-line__value"><?php echo esc_html( $hours ); ?></span>
						</p>
					<?php endif; ?>

					<?php if ( get_the_content() ) : ?>
						<div class="entry-content">
							<?php the_content(); ?>
						</div>
					<?php endif; ?>
				</div>

				<div>
					<h2 class="h2" style="margin-bottom: var(--space-3);"><?php esc_html_e( 'Request a valuation', 'yarmside' ); ?></h2>

					<form class="form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
						<input type="hidden" name="action" value="yarmside_valuation">
						<?php wp_nonce_field( 'yarmside_valuation', 'yarmside_valuation_nonce' ); ?>
						<p style="display:none" aria-hidden="true">
							<label><?php esc_html_e( 'Leave this field empty', 'yarmside' ); ?> <input name="website_field" tabindex="-1" autocomplete="off"></label>
						</p>

						<div class="field">
							<label for="your_name"><?php esc_html_e( 'Your name', 'yarmside' ); ?></label>
							<input type="text" id="your_name" name="your_name" autocomplete="name" required>
						</div>
						<div class="field">
							<label for="your_email"><?php esc_html_e( 'Email', 'yarmside' ); ?></label>
							<input type="email" id="your_email" name="your_email" autocomplete="email" required>
						</div>
						<div class="field">
							<label for="your_phone"><?php esc_html_e( 'Phone', 'yarmside' ); ?></label>
							<span class="hint"><?php esc_html_e( "Optional, if you'd rather we call", 'yarmside' ); ?></span>
							<input type="tel" id="your_phone" name="your_phone" autocomplete="tel">
						</div>
						<div class="field">
							<label for="interest"><?php esc_html_e( "I'm interested in", 'yarmside' ); ?></label>
							<select id="interest" name="interest">
								<option><?php esc_html_e( 'A rental valuation of my property', 'yarmside' ); ?></option>
								<option><?php esc_html_e( 'Fully managed service', 'yarmside' ); ?></option>
								<option><?php esc_html_e( 'Rent collection', 'yarmside' ); ?></option>
								<option><?php esc_html_e( 'Tenant find only', 'yarmside' ); ?></option>
								<option><?php esc_html_e( 'Renting a home', 'yarmside' ); ?></option>
								<option><?php esc_html_e( 'Something else', 'yarmside' ); ?></option>
							</select>
						</div>
						<div class="field field--full">
							<label for="property_address"><?php esc_html_e( 'Property address', 'yarmside' ); ?></label>
							<span class="hint"><?php esc_html_e( 'If your enquiry is about a specific property', 'yarmside' ); ?></span>
							<input type="text" id="property_address" name="property_address" autocomplete="street-address">
						</div>
						<div class="field field--full">
							<label for="message"><?php esc_html_e( 'Anything else we should know', 'yarmside' ); ?></label>
							<textarea id="message" name="message"></textarea>
						</div>
						<div class="form__actions">
							<div class="btn-row">
								<button type="submit" class="btn btn--primary"><?php esc_html_e( 'Send request', 'yarmside' ); ?></button>
							</div>
							<p class="form__note"><?php esc_html_e( "We'll reply within one working day. Valuations are free and come with no obligation to instruct us.", 'yarmside' ); ?></p>
							<?php if ( $sent ) : ?>
								<p class="form-success is-visible" role="status"><?php esc_html_e( "Thanks, your request has been received. We'll reply within one working day.", 'yarmside' ); ?></p>
							<?php elseif ( $error ) : ?>
								<p class="form-success form-success--error is-visible" role="status"><?php esc_html_e( "Sorry, your request didn't send. Please check your name and email and try again, or email us directly.", 'yarmside' ); ?></p>
							<?php endif; ?>
						</div>
					</form>
				</div>

			</div>
		</section>

	<?php endwhile; ?>
</main>

<?php
// LocalBusiness structured data, built from the customizer values.
$schema = array(
	'@context'    => 'https://schema.org',
	'@type'       => 'RealEstateAgent',
	'name'        => get_bloginfo( 'name' ),
	'url'         => home_url( '/' ),
	'email'       => $email,
	'description' => get_bloginfo( 'description' ),
	'address'     => array(
		'@type'           => 'PostalAddress',
		'streetAddress'   => strtok( $address, "\n" ),
		'addressLocality' => 'Yarm',
		'addressRegion'   => 'Stockton-on-Tees',
		'addressCountry'  => 'GB',
	),
	'areaServed'  => array(
		array( '@type' => 'Place', 'name' => 'Yarm' ),
		array( '@type' => 'Place', 'name' => 'Tees Valley' ),
		array( '@type' => 'Place', 'name' => 'North East England' ),
	),
);
if ( $phone ) {
	$schema['telephone'] = $phone;
}
?>
<script type="application/ld+json"><?php echo wp_json_encode( $schema, JSON_UNESCAPED_SLASHES ); ?></script>

<?php get_footer(); ?>
