<?php
/*
 * Template Name: Raw Space
 */
get_header();

barcelona_breadcrumb();

if (function_exists('Backfeed\raw_space_query')) $barcelona_q = Backfeed\raw_space_query(1);

$barcelona_mod_header = '<div class="box-header archive-header has-title"><h2 class="title">Raw Space</h2></div>';

$barcelona_mod_post_meta = ["date", "author", "score", "votedrep", "comments"];

?>

<div class="container">
    <div class="<?=esc_attr(barcelona_row_class())?>">

        <main id="main" class="<?=esc_attr(barcelona_main_class())?>">
            <?php include(locate_template('includes/modules/module-d.php')); ?>
            <?php barcelona_pagination('infinite', $barcelona_q); ?>
        </main>

        <?php get_sidebar(); ?>

    </div><!-- .row -->
</div><!-- .container -->

<?php get_footer();