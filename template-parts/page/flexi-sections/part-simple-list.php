<?php
// Return early if no variables have been passed to this template part via get_template_part
if ( ! $args ) return;

// Retrieve any variables passed down to this template part
$meta_key           = ( $args && isset( $args['section_meta_key'] ) ) ? $args['section_meta_key'] : null;

// Section meta variables
$list_label         = esc_html( get_post_meta( orgnk_get_the_ID(), $meta_key . '_list_label', true ) );
$list_items_count   = esc_html( get_post_meta( orgnk_get_the_ID(), $meta_key . '_list_items', true ) );

if ( $list_items_count ) : ?>

    <?php if ( $list_label ) : ?>
        <span class="list-label"><?php echo $list_label ?></span>
    <?php endif ?>

    <ul class="simple-list">

        <?php for ( $i = 0; $i < $list_items_count; $i++ ) :

            // Repeater variables
            $list_item_text     = esc_html( get_post_meta( orgnk_get_the_ID(), $meta_key . '_list_items_' . $i . '_text', true ) );
            $list_item_link     = orgnk_get_acf_link( $meta_key . '_list_items_' . $i . '_link' );

            if ( $list_item_text ) : ?>

                <li>
                    <?php if ( $list_item_link ) echo '<a href="' . $list_item_link['url'] . '"' . $list_item_link['target'] . '>' ?>
                    <?php echo $list_item_text ?>
                    <?php if ( $list_item_link ) echo '</a>' ?>
                </li>

            <?php endif ?>
        <?php endfor ?>
    </ul>

<?php endif;
