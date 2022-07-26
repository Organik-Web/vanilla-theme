<?php
global $wp_query;

if ( $wp_query->max_num_pages > 1 ) : ?>

    <div class="archive-navigation">

        <?php echo orgnk_pagination() ?>

    </div>
    
<?php endif;
