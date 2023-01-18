<?php
// This section uses the 'split content' flexi section, so we're setting up the appropriate variables to pass to that template part
$args = array(
	'section_meta_key'      => 'front_about',
	'section_classes'       => ' section-front-about section-pad-bottom content-left',
	'section_is_dark'       => false
);

get_template_part( 'template-parts/page/flexi-sections/section-split-content', null, $args );
