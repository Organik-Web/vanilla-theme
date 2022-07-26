<?php
// Setup any custom post types archives for the header sub nav
$sub_nav_cpts = ( function_exists( 'orgnk_header_sub_nav_cpt_archives' ) ) ? orgnk_header_sub_nav_cpt_archives() : null;
?>

<header class="header" data-header-breakpoint="1200">
    <div class="header-middle">
        <div class="container">
            <div class="header-middle-wrap">

                <div class="header-middle-left">
                    <a class="header-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php esc_html( bloginfo( 'name' ) ) ?>">
                        <img width="100" height="100" class="logo" src="<?php echo get_template_directory_uri() . '/images/logos/logo-full.svg'; ?>" alt="<?php esc_html( bloginfo( 'name' ) ) ?>" loading="lazy"/>
                    </a>
                </div>

                <div class="header-middle-right">
                    <nav class="desktop-menu nav-has-sub-menu">
                        <?php
                        wp_nav_menu( array(
                        'container'         => false,
                        'theme_location'    => 'main-menu',
                        'menu_class'        => 'menu',
                        'fallback_cb'       => false,
                        'depth'             => 2,
                        'link_before'       => '<i class="current-marker" aria-hidden="true"></i>'
                        ));
                        ?>
                    </nav>

                    <div class="search-panel">
                        <div class="container">
                            <div class="panel-wrap">
                                <?php get_search_form() ?>
                                <button class="search-close search-panel-trigger-close">
                                    <i class="icon" aria-hidden="true"></i>
                                    <span class="screen-reader-text"><?php esc_html_e( 'Close search', 'orgnk_client_textdomain' ) ?></span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="site-tools">

                        <button class="search-panel-trigger">
                            <span class="screen-reader-text"><?php esc_html_e( 'Search', 'orgnk_client_textdomain' ) ?></span>
                            <div class="button-inner">
                                <i class="icon" aria-hidden="true"></i>
                            </div>
                        </button>

                        <button class="mobile-menu-panel-trigger">
                            <span class="screen-reader-text"><?php esc_html_e( 'Mobile menu', 'orgnk_client_textdomain' ) ?></span>
                            <div class="button-inner">
                                <div class="hamburger">
                                    <div class="bar top-bun"></div>
                                    <div class="bar patty"></div>
                                    <div class="bar bottom-bun"></div>
                                </div>
                            </div>
                        </button>

                        <?php if ( orgnk_has_enquiry_form() ) : ?>
                            <button class="enquiry-panel-trigger">
                                <span class="screen-reader-text"><?php esc_html_e( 'Enquiry form', 'orgnk_client_textdomain' ) ?></span>
                                <div class="label-swapper align-centre slide-down" aria-hidden="true">
                                    <div class="label">
                                        <i class="icon contact" aria-hidden="true"></i>
                                        <span class="text"><?php esc_html_e( 'Enquire', 'orgnk_client_textdomain' ) ?></span>
                                    </div>
                                    <div class="label">
                                        <i class="icon close" aria-hidden="true"></i>
                                        <span class="text"><?php esc_html_e( 'Close', 'orgnk_client_textdomain' ) ?></span>
                                    </div>
                                </div>
                            </button>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php get_template_part( 'template-parts/global/overlay-panel' ) ?>

    <?php if ( orgnk_header_sub_nav( $sub_nav_cpts, 'swipe' ) ) : ?>
        <div class="header-bottom">
            <div class="container">
                <?php echo orgnk_header_sub_nav( $sub_nav_cpts, 'swipe' ) ?>
            </div>
        </div>
    <?php endif ?>

</header>
