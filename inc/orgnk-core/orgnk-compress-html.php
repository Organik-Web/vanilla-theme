<?php
/**
 * orgnk_html_minify_start()
 */
function orgnk_html_minify_start()  {
    ob_start( 'orgnk_html_minyfy_finish' );
}

/**
 * orgnk_html_minyfy_finish()
 */
function orgnk_html_minyfy_finish( $html ) {

    $html = preg_replace( '/<!--(?!s*(?:[if [^]]+]|!|>))(?:(?!-->).)*-->/s', '', $html );

    while ( stristr( $html, '  ' ) ) {
        $html = str_replace('  ', ' ', $html);
    }

    return $html;
}

if ( defined( 'HOST_ENVIRONMENT' ) && HOST_ENVIRONMENT != 'production' ) {
    add_action( 'get_header', 'orgnk_html_minify_start' );
}
