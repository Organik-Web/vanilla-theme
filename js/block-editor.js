wp.domReady( () => {

    // Remove core blocks
	wp.blocks.unregisterBlockType( 'core/cover' );
	wp.blocks.unregisterBlockType( 'core/verse' );
	wp.blocks.unregisterBlockType( 'core/pullquote' );
	wp.blocks.unregisterBlockType( 'core/freeform' ); // Classic editor block
    wp.blocks.unregisterBlockType( 'core/code' );
    wp.blocks.unregisterBlockType( 'core/more' );
	wp.blocks.unregisterBlockType( 'core/media-text' );
	wp.blocks.unregisterBlockType( 'core/preformatted' );
	wp.blocks.unregisterBlockType( 'core/nextpage' ); // Page break
	wp.blocks.unregisterBlockType( 'core/columns' );
    wp.blocks.unregisterBlockType( 'core/calendar' );
    wp.blocks.unregisterBlockType( 'core/latest-comments' );
    wp.blocks.unregisterBlockType( 'core/rss' );
    wp.blocks.unregisterBlockType( 'core/search' );
    wp.blocks.unregisterBlockType( 'core/tag-cloud' );
    wp.blocks.unregisterBlockType( 'core/archives' );
    wp.blocks.unregisterBlockType( 'core/categories' );
    wp.blocks.unregisterBlockType( 'core/latest-posts' );
    wp.blocks.unregisterBlockType( 'core/html' );

    // Remove plugin blocks
	wp.blocks.unregisterBlockType( 'yoast/how-to-block' );
	wp.blocks.unregisterBlockType( 'yoast/faq-block' );

	// Remove block styles
	wp.blocks.unregisterBlockStyle( 'core/button', 'fill' );
	wp.blocks.unregisterBlockStyle( 'core/button', 'outline' );
  	wp.blocks.unregisterBlockStyle( 'core/button', 'squared' );
	
	wp.blocks.unregisterBlockStyle( 'core/table', 'default' );
  	wp.blocks.unregisterBlockStyle( 'core/table', 'stripes' );

	wp.blocks.unregisterBlockStyle( 'core/separator', 'default' );
	wp.blocks.unregisterBlockStyle( 'core/separator', 'wide' );
	wp.blocks.unregisterBlockStyle( 'core/separator', 'dots' );

	wp.blocks.unregisterBlockStyle( 'core/quote', 'default' );
	wp.blocks.unregisterBlockStyle( 'core/quote', 'large' );

	wp.blocks.unregisterBlockStyle( 'core/image', 'default' );
	wp.blocks.unregisterBlockStyle( 'core/image', 'rounded' );

    // Register a single, standard button style
    wp.blocks.registerBlockStyle( 'core/button', {
        name: 'primary-button',
        label: 'Standard2',
        isDefault: true
    } );

} );