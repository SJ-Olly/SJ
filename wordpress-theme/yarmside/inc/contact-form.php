<?php
/**
 * Valuation / enquiry form: shortcode + handler.
 *
 * Works without any form plugin. Submissions are emailed to the address
 * set under Customizer → Yarmside settings → Contact details (falling
 * back to the site admin email). Includes honeypot and nonce protection.
 *
 * @package Yarmside
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * [yarmside_contact_details] — the office dimension lines, driven by the Customizer.
 */
function yarmside_contact_details_shortcode() {
	$email = get_theme_mod( 'yarmside_contact_email', 'enquiries@yarmside.co.uk' );
	$phone = get_theme_mod( 'yarmside_contact_phone', '' );
	$hours = get_theme_mod( 'yarmside_contact_hours', '' );

	ob_start();
	?>
	<address>
		<?php echo esc_html( get_bloginfo( 'name' ) ); ?><br>
		<?php echo esc_html( get_theme_mod( 'yarmside_contact_street', 'High Street' ) ); ?>,
		<?php echo esc_html( get_theme_mod( 'yarmside_contact_locality', 'Yarm' ) ); ?><br>
		<?php echo esc_html( get_theme_mod( 'yarmside_contact_region', 'Stockton-on-Tees' ) ); ?>
	</address>
	<?php if ( $email ) : ?>
	<p class="dim-line">
		<span class="dim-line__label"><?php esc_html_e( 'Email', 'yarmside' ); ?></span>
		<span class="dim-line__rule"></span>
		<span class="dim-line__value"><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></span>
	</p>
	<?php endif; ?>
	<p class="dim-line">
		<span class="dim-line__label"><?php esc_html_e( 'Phone', 'yarmside' ); ?></span>
		<span class="dim-line__rule"></span>
		<span class="dim-line__value"><?php echo $phone ? esc_html( $phone ) : esc_html__( 'Number to be added', 'yarmside' ); ?></span>
	</p>
	<p class="dim-line">
		<span class="dim-line__label"><?php esc_html_e( 'Hours', 'yarmside' ); ?></span>
		<span class="dim-line__rule"></span>
		<span class="dim-line__value"><?php echo $hours ? esc_html( $hours ) : esc_html__( 'To be confirmed', 'yarmside' ); ?></span>
	</p>
	<?php
	return ob_get_clean();
}
add_shortcode( 'yarmside_contact_details', 'yarmside_contact_details_shortcode' );

/**
 * [yarmside_valuation_form] — the enquiry form from the design.
 */
function yarmside_valuation_form_shortcode() {
	$email    = get_theme_mod( 'yarmside_contact_email', get_option( 'admin_email' ) );
	$statuses = array(
		'sent'  => isset( $_GET['yarmside_sent'] ),  // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		'error' => isset( $_GET['yarmside_error'] ), // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	);

	$interests = array(
		__( 'A rental valuation of my property', 'yarmside' ),
		__( 'Fully managed service', 'yarmside' ),
		__( 'Rent collection', 'yarmside' ),
		__( 'Tenant find only', 'yarmside' ),
		__( 'Renting a home', 'yarmside' ),
		__( 'Something else', 'yarmside' ),
	);

	ob_start();

	if ( $statuses['sent'] ) {
		echo '<p class="form-success" role="status">';
		printf(
			/* translators: %s: office email address. */
			esc_html__( 'Thanks, your request has been received. We\'ll reply within one working day. If it\'s urgent, email %s directly.', 'yarmside' ),
			esc_html( $email )
		);
		echo '</p>';
	}
	if ( $statuses['error'] ) {
		echo '<p class="form-success form-success--error" role="status">';
		printf(
			/* translators: %s: office email address. */
			esc_html__( 'Sorry, your request didn\'t send. Please try again in a moment, or email %s directly.', 'yarmside' ),
			esc_html( $email )
		);
		echo '</p>';
	}
	?>
	<form class="form" id="valuation-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<input type="hidden" name="action" value="yarmside_enquiry">
		<input type="hidden" name="yarmside_return" value="<?php echo esc_url( get_permalink() ); ?>">
		<?php wp_nonce_field( 'yarmside_enquiry', 'yarmside_enquiry_nonce' ); ?>
		<p class="screen-reader-text" aria-hidden="true">
			<label><?php esc_html_e( 'Leave this field empty', 'yarmside' ); ?> <input name="yarmside_website" tabindex="-1" autocomplete="off"></label>
		</p>
		<div class="field">
			<label for="yarmside-name"><?php esc_html_e( 'Your name', 'yarmside' ); ?></label>
			<input type="text" id="yarmside-name" name="yarmside_name" autocomplete="name" required>
		</div>
		<div class="field">
			<label for="yarmside-email"><?php esc_html_e( 'Email', 'yarmside' ); ?></label>
			<input type="email" id="yarmside-email" name="yarmside_email" autocomplete="email" required>
		</div>
		<div class="field">
			<label for="yarmside-phone"><?php esc_html_e( 'Phone', 'yarmside' ); ?></label>
			<span class="hint"><?php esc_html_e( 'Optional, if you\'d rather we call', 'yarmside' ); ?></span>
			<input type="tel" id="yarmside-phone" name="yarmside_phone" autocomplete="tel">
		</div>
		<div class="field">
			<label for="yarmside-interest"><?php esc_html_e( 'I\'m interested in', 'yarmside' ); ?></label>
			<select id="yarmside-interest" name="yarmside_interest">
				<?php foreach ( $interests as $interest ) : ?>
					<option><?php echo esc_html( $interest ); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="field field--full">
			<label for="yarmside-address"><?php esc_html_e( 'Property address', 'yarmside' ); ?></label>
			<span class="hint"><?php esc_html_e( 'If your enquiry is about a specific property', 'yarmside' ); ?></span>
			<input type="text" id="yarmside-address" name="yarmside_address" autocomplete="street-address">
		</div>
		<div class="field field--full">
			<label for="yarmside-message"><?php esc_html_e( 'Anything else we should know', 'yarmside' ); ?></label>
			<textarea id="yarmside-message" name="yarmside_message"></textarea>
		</div>
		<div class="form__actions">
			<div class="btn-row">
				<button type="submit" class="btn btn--primary"><?php esc_html_e( 'Send request', 'yarmside' ); ?></button>
			</div>
			<p class="form__note"><?php esc_html_e( 'We\'ll reply within one working day. Valuations are free and come with no obligation to instruct us.', 'yarmside' ); ?></p>
		</div>
	</form>
	<?php
	return ob_get_clean();
}
add_shortcode( 'yarmside_valuation_form', 'yarmside_valuation_form_shortcode' );

/**
 * Handle the submission: validate, email the office, redirect back.
 */
function yarmside_handle_enquiry() {
	$return = isset( $_POST['yarmside_return'] ) ? esc_url_raw( wp_unslash( $_POST['yarmside_return'] ) ) : home_url( '/' );

	// Only redirect within this site.
	if ( wp_parse_url( $return, PHP_URL_HOST ) !== wp_parse_url( home_url(), PHP_URL_HOST ) ) {
		$return = home_url( '/' );
	}

	$fail = add_query_arg( 'yarmside_error', '1', $return ) . '#valuation-form';

	if ( ! isset( $_POST['yarmside_enquiry_nonce'] ) ||
		! wp_verify_nonce( sanitize_key( $_POST['yarmside_enquiry_nonce'] ), 'yarmside_enquiry' ) ) {
		wp_safe_redirect( $fail );
		exit;
	}

	// Honeypot: quietly pretend success for bots.
	if ( ! empty( $_POST['yarmside_website'] ) ) {
		wp_safe_redirect( add_query_arg( 'yarmside_sent', '1', $return ) );
		exit;
	}

	$name    = isset( $_POST['yarmside_name'] ) ? sanitize_text_field( wp_unslash( $_POST['yarmside_name'] ) ) : '';
	$email   = isset( $_POST['yarmside_email'] ) ? sanitize_email( wp_unslash( $_POST['yarmside_email'] ) ) : '';
	$phone   = isset( $_POST['yarmside_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['yarmside_phone'] ) ) : '';
	$topic   = isset( $_POST['yarmside_interest'] ) ? sanitize_text_field( wp_unslash( $_POST['yarmside_interest'] ) ) : '';
	$address = isset( $_POST['yarmside_address'] ) ? sanitize_text_field( wp_unslash( $_POST['yarmside_address'] ) ) : '';
	$message = isset( $_POST['yarmside_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['yarmside_message'] ) ) : '';

	if ( ! $name || ! is_email( $email ) ) {
		wp_safe_redirect( $fail );
		exit;
	}

	$to      = get_theme_mod( 'yarmside_contact_email', '' );
	$to      = is_email( $to ) ? $to : get_option( 'admin_email' );
	/* translators: %s: enquirer's name. */
	$subject = sprintf( __( 'Website enquiry from %s', 'yarmside' ), $name );

	$lines = array(
		__( 'Name:', 'yarmside' ) . ' ' . $name,
		__( 'Email:', 'yarmside' ) . ' ' . $email,
		__( 'Phone:', 'yarmside' ) . ' ' . $phone,
		__( 'Interested in:', 'yarmside' ) . ' ' . $topic,
		__( 'Property address:', 'yarmside' ) . ' ' . $address,
		'',
		$message,
	);

	$sent = wp_mail(
		$to,
		$subject,
		implode( "\n", $lines ),
		array( 'Reply-To: ' . $name . ' <' . $email . '>' )
	);

	$result = $sent ? array( 'yarmside_sent', '1' ) : array( 'yarmside_error', '1' );
	wp_safe_redirect( add_query_arg( $result[0], $result[1], $return ) . '#valuation-form' );
	exit;
}
add_action( 'admin_post_yarmside_enquiry', 'yarmside_handle_enquiry' );
add_action( 'admin_post_nopriv_yarmside_enquiry', 'yarmside_handle_enquiry' );
