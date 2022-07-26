<form class="site-search-form" method="get" role="search" action="<?php echo esc_url( home_url( '/' ) ) ?>" autocomplete="off">
    <label for="s" class="screen-reader-text"><?php esc_html_e( 'Type your search and press enter', 'orgnk_client_textdomain' ) ?></label>
    <input class="search-input" name="s" type="text" value="<?php echo esc_html( get_search_query() ) ?>" placeholder="Search the website...">
    <button class="search-submit">
        <i class="icon" aria-hidden="true"></i>
        <span class="screen-reader-text"><?php esc_html_e( 'Submit your search', 'orgnk_client_textdomain' ) ?></span>
    </button>
</form>
