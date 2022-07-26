<?php
// Theme settings variables
$email                      = sanitize_email( get_option( 'options_business_email' ) );
$phone                      = esc_html( get_option( 'options_business_phone' ) );
$fax                        = esc_html( get_option( 'options_business_fax' ) );
$street_address             = nl2br( esc_html( get_option( 'options_business_street_address' ) ) );
$postal_address             = nl2br( esc_html( get_option( 'options_business_postal_address' ) ) );
$google_my_business         = esc_url( get_option( 'options_google_my_business' ) );
$opening_hours              = orgnk_get_the_content( get_option( 'options_business_opening_hours' ) );
$opening_hours              = orgnk_get_opening_hours();

if ( $email || $phone || $fax || $street_address || $postal_address ) : ?>

    <div class="business-contact-details">

        <?php if ( $email ) : ?>
            <div class="contact-group email">
                <div class="group-label">
                    <i class="icon email" aria-hidden="true"></i>
                    <span class="label"><?php esc_html_e( 'Email', 'orgnk_client_textdomain' ) ?></span>
                </div>
                <a href="mailto:<?php echo $email ?>"><?php echo $email ?></a>
            </div>
        <?php endif ?>

        <?php if ( $phone ) : ?>
            <div class="contact-group phone">
                <div class="group-label">
                    <i class="icon phone" aria-hidden="true"></i>
                    <span class="label"><?php esc_html_e( 'Phone', 'orgnk_client_textdomain' ) ?></span>
                </div>
                <a href="tel:<?php echo orgnk_format_phone_link( $phone ) ?>"><?php echo $phone ?></a>
            </div>
        <?php endif ?>

        <?php if ( $fax ) : ?>
            <div class="contact-group fax">
                <div class="group-label">
                    <i class="icon fax" aria-hidden="true"></i>
                    <span class="label"><?php esc_html_e( 'Fax', 'orgnk_client_textdomain' ) ?></span>
                </div>
                <a href="tel:<?php echo orgnk_format_phone_link( $fax ) ?>"><?php echo $fax ?></a>
            </div>
        <?php endif ?>

        <?php if ( $street_address ) : ?>
            <address class="contact-group street-address">
                <div class="group-label">
                    <i class="icon street-address" aria-hidden="true"></i>
                    <span class="label"><?php esc_html_e( 'Address', 'orgnk_client_textdomain' ) ?></span>
                </div>

                <?php if ( $google_my_business ) : ?>
                    <a class="google-my-business-link" href="<?php echo $google_my_business ?>" target="_blank" rel="noopener"><?php echo $street_address ?></a>
                <?php else : ?>
                    <span><?php echo $street_address ?></span>
                <?php endif ?>
            </address>
        <?php endif ?>

        <?php if ( $postal_address ) : ?>
            <address class="contact-group postal-address">
                <div class="group-label">
                    <i class="icon postal-address" aria-hidden="true"></i>
                    <span class="label"><?php esc_html_e( 'Post', 'orgnk_client_textdomain' ) ?></span>
                </div>
                <span><?php echo $postal_address ?></span>
            </address>
        <?php endif ?>

        <?php if ( $opening_hours ) : ?>
            <div class="contact-group opening-hours">
                <div class="group-label">
                    <i class="icon opening-hours" aria-hidden="true"></i>
                    <span class="label"><?php esc_html_e( 'Hours', 'ark_group_textdomain' ) ?></span>
                </div>
                <ul class="opening-hours-list">
                    <?php foreach ( $opening_hours as $key => $value ) : ?>
                        <li>
                            <span class="day"><?php echo $key ?>:</span>
                            <span class="time"><?php echo $value ?></span>
                        </li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif ?>

    </div>

<?php endif;
