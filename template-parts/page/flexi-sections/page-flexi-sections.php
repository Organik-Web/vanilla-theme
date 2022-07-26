<?php
// Retrieve any variables passed down to this template part
$group_key = ( $args && $args['group_key'] ) ? $args['group_key'] . '_' : null;
$meta_key = $group_key . 'flexi_sections';

// Get sections array
$sections = get_post_meta( orgnk_get_the_ID(), $meta_key, true );

if ( $sections ) {

    $count = count( $sections );

    // Loop through sections
    for ( $i = 0; $i < $count; $i++ ) {

        $type = $sections[$i];
        $args = array(
            'section_meta_key'      => $meta_key . '_' . $i,
            'section_classes'       => orgnk_flexi_sections_classes( $meta_key, $i ),
            'section_is_dark'       => orgnk_flexi_sections_is_dark( $meta_key, $i )
        );

        if ( $type === 'simple-content' ) {

            get_template_part( 'template-parts/page/flexi-sections/section-simple-content', null, $args );

        } elseif ( $type === 'split-content' ) {

            get_template_part( 'template-parts/page/flexi-sections/section-split-content', null, $args );

        } elseif ( $type === 'two-column-content' ) {

            get_template_part( 'template-parts/page/flexi-sections/section-two-column-content', null, $args );

        } elseif ( $type === 'cover-content' ) {

            get_template_part( 'template-parts/page/flexi-sections/section-cover-content', null, $args );

        } elseif ( $type === 'cards-list' ) {

            get_template_part( 'template-parts/page/flexi-sections/section-cards-list', null, $args );

        } elseif ( $type === 'simple-cards-list' ) {

            get_template_part( 'template-parts/page/flexi-sections/section-simple-cards-list', null, $args );

        } elseif ( $type === 'highlights-list' ) {

            get_template_part( 'template-parts/page/flexi-sections/section-highlights-list', null, $args );

        } elseif ( $type === 'pricing-list' ) {

            get_template_part( 'template-parts/page/flexi-sections/section-pricing-list', null, $args );

        } elseif ( $type === 'call-to-action' ) {

            get_template_part( 'template-parts/page/flexi-sections/section-call-to-action', null, $args );

        } elseif ( $type === 'tabs' ) {

            get_template_part( 'template-parts/page/flexi-sections/section-tabs', null, $args );

        }
    }
}
