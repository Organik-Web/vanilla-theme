<?php
// Setup social links array
$social_links = array(
    'Facebook'          => esc_url( get_option( 'options_facebook' ) ),
    'Instagram'         => esc_url( get_option( 'options_instagram' ) ),
    'Twitter'           => esc_url( get_option( 'options_twitter' ) ),
    'YouTube'           => esc_url( get_option( 'options_youtube' ) ),
    'Vimeo'             => esc_url( get_option( 'options_vimeo' ) ),
    'LinkedIn'          => esc_url( get_option( 'options_linkedin' ) ),
    'Pinterest'         => esc_url( get_option( 'options_pinterest' ) ),
    'Tumblr'            => esc_url( get_option( 'options_tumblr' ) ),
    'Dribbble'          => esc_url( get_option( 'options_dribbble' ) ),
    'Behance'           => esc_url( get_option( 'options_behance' ) ),
    'WhatsApp'          => esc_url( get_option( 'options_whatsapp' ) ),
    'Telegram'          => esc_url( get_option( 'options_telegram' ) ),
);

// Remove any empty array values
$social_links   = array_filter( $social_links );

// Store the number of social links in the finished array
$count          = count( $social_links );

// Check social links array is not empty before output
if ( ! empty( $social_links ) ) : ?>

    <div class="business-social-links">
        <ul class="social-links">

            <?php foreach ( $social_links as $key => $value ) : ?>
                <?php if ( $value ) : ?>

                    <li class="social-icon">
                        <a href="<?php echo $value ?>" target="_blank" rel="noopener">
                            <div class="icon-wrapper">
                                <i class="icon <?php echo strtolower( $key ) ?>" aria-hidden="true"></i>
                                <span class="screen-reader-text"><?php esc_html_e( $key, 'orgnk_client_textdomain' ) ?></span>
                            </div>

                            <?php if ( $count === 1 ) : ?>
                                <span class="label" aria-hidden="true"><?php esc_html_e( 'Follow us', 'orgnk_client_textdomain' ) ?></span>
                            <?php endif ?>
                        </a>
                    </li>

                <?php endif ?>
            <?php endforeach ?>

        </ul>
    </div>

<?php endif;
