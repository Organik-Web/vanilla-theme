<?php
// Theme settings variables
$bio = esc_html( get_option( 'options_business_short_description' ) );

// Setup menus to include
$menus = array(
    'footer-menu-1',
    'footer-menu-2',
);
?>

<footer class="footer">
    <div class="footer-middle">
        <div class="container">

            <div class="footer-branding">
                <a class="footer-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php esc_html( bloginfo( 'name' ) ) ?>">
                    <img width="100" height="100" class="logo" src="<?php echo get_template_directory_uri() . '/images/logos/logo-full-white.svg' ?>" alt="<?php esc_html( bloginfo( 'name' ) ) ?>" loading="lazy" />
                </a>
            </div>

            <div class="footer-middle-left">

                <?php if ( $bio ) : ?>
                    <div class="footer-description">
                        <?php echo $bio ?>
                    </div>
                <?php endif ?>

                <div class="footer-contact">
                    <?php get_template_part( 'template-parts/global/contact-details' ) ?>
                    <?php get_template_part( 'template-parts/global/social-links' ) ?>
                </div>
            </div>

            <div class="footer-middle-right">
                <nav class="footer-menu" aria-label="Footer menu" aria-hidden="true">
                    <?php foreach ( $menus as $menu ) :
                        if ( $menu ) :
                            wp_nav_menu( array(
                                'container'         => false,
                                'theme_location'    => $menu,
                                'menu_class'        => 'menu',
                                'fallback_cb'       => false,
                                'depth'             => 2,
                                'link_before'       => '<i class="current-marker" aria-hidden="true"></i>'
                                )
                            );
                        endif;
                    endforeach ?>
                </nav>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <div class="footer-bottom-bar">
                <div class="footer-bottom-wrap">
                    <div class="footer-bottom-left">
                        <span class="copyright"><?php echo orgnk_site_copyright() ?></span>
                        <nav class="footer-bottom-menu" aria-label="Footer bottom menu">
                            <?php wp_nav_menu( array(
                                'container'         => false,
                                'theme_location'    => 'footer-bottom',
                                'menu_class'        => 'menu',
                                'fallback_cb'       => false,
                                'depth'             => 1,
                                'link_before'       => '<i class="current-marker" aria-hidden="true"></i>'
                                )
                            ); ?>
                        </nav>
                    </div>

                    <div class="footer-bottom-right">
                        <div class="agency-credit developer">
                            <?php echo orgnk_developer_link() ?>
                        </div>
                        <div class="agency-credit seo">
                            <?php echo orgnk_seo_agency_link() ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
