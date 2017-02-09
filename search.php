<?php
/**
 * The template for displaying search results pages
 *
 * @package Moridrin
 * @subpackage SSV
 * @since SSV 1.0
 */

get_header() ?>
<header class="full-width-entry-header">
    <div class="parallax-container primary" style="height: 250px;">
        <div class="shade darken-1" style="height: 100%">
            <h1 class="entry-title center-align white-text" style="margin-top: 0; padding-top: 30px"><?= get_bloginfo() ?></h1>
            <h3 class="entry-title center-align white-text"><?= get_bloginfo('description') ?></h3>
        </div>
    </div>
</header>
<div id="page" class="container <?= is_admin_bar_showing() ? 'wpadminbar' : '' ?>">
    <div class="row">
        <div class="col s12 <?= is_dynamic_sidebar() ? 'm8 l9 xl10' : '' ?>">
            <div id="primary" class="content-area">
                <main id="main" class="site-main" role="main">
                    <?php
                    if (have_posts()) {
                        while (have_posts()) {
                            the_post();
                            get_template_part('template-parts/content', get_post_format());
                        }
                        echo mp_ssv_get_pagination();
                    } else {
                        get_template_part('template-parts/content', 'none');
                    }
                    ?>
                </main>
            </div>
        </div>
        <?php get_sidebar(); ?>
    </div>
</div>
<?php get_footer(); ?>
