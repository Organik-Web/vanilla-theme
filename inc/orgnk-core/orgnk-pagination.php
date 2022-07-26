<?php
/**
 * orgnk_pagination()
 * Similar to the default paginate_links function, but doesn't include dots or the first/last pages
 * Instead, this function only displays pagination links around the current page based on the $mid_size set
 * If the current page is towards the beginning or end of the total pages, the $mid_size will grow so there are always a consistent number of page links shown
 * The total number of page links show is the $mid_size x 2 + 1 (1 being the current page), so by default there are 5 links showns
 */
function orgnk_pagination( $mid_size = 2 ) {

	global $wp_query;

	$output = null;
	$links = array();
	$current = max( 1, get_query_var( 'paged' ) );
	$total = $wp_query->max_num_pages;

	// Number of pages before the current
	$pos_from_start = $current - 1;

	// Number of pages between the current and the last
	$pos_from_end = $total - $current;

	// If there aren't enough previous pages, grow the number of next pages to maintain a consistent page count
	if ( $pos_from_start <= round( $mid_size / 2 ) ) {
		$mid_size = $mid_size + ( $mid_size - $pos_from_start );
	}

	// If there aren't enough next pages, grow the number of previous pages to maintain a consistent page count
	if ( $pos_from_end <= round( $mid_size / 2 ) ) {
		$mid_size = $mid_size + ( $mid_size - $pos_from_end );
	}

	// Previous button
	if ( $current > 1 ) {
		$links[] = '<li class="page prev"><a class="page-link" href="' . esc_url( get_pagenum_link( $current - 1 ) ) . '"><div class="page-wrap"><i class="icon" aria-hidden="true"></i><span class="screen-reader-text">Previous page</span></div></a></li>';
	}

	// Pages
	for ( $i = 1; $i <= $total; $i++ ) {
		if ( $i == $current ) {
			$links[] = '<li class="page current" aria-current="page"><div class="page-wrap"><span class="page-label" aria-hidden="true">' . $i . '</span></div></li>';
		} else {
			if ( ( $current && $i >= $current - $mid_size && $i <= $current + $mid_size ) || $i > $total ) {
				$links[] = '<li class="page"><a class="page-link" href="' . esc_url( get_pagenum_link( $i ) ) . '"><div class="page-wrap"><span class="screen-reader-text">Page ' . $i . '</span><span class="page-label" aria-hidden="true">' . $i . '</span></div></a></li>';
			}
		}
	}

	// Next button
	if ( $current < $total ) {
		$links[] = '<li class="page next"><a class="page-link" href="' . esc_url( get_pagenum_link( $current + 1 ) ) . '"><div class="page-wrap"><i class="icon" aria-hidden="true"></i><span class="screen-reader-text">Next page</span></div></a></li>';
	}

    $output .= '<nav class="pagination" aria-label="Pagination">';
    $output .= '<ul class="pages">';
    $output .= join( '', $links );
    $output .= '</ul>';
	$output .= '</nav>';

	return $output;
}
