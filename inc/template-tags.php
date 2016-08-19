<?php
/**
 * @package    Moridrin
 * @subpackage SSV
 * @since      SSV 1.0
 */

if (!function_exists('ssv_entry_meta')) :
    function ssv_entry_meta()
    {
        if ('post' === get_post_type()) {
            $author_avatar_size = apply_filters('ssv_author_avatar_size', 49);
            printf(
                '<span class="byline" style="margin-right: 10px;"><span class="author vcard">%1$s<span class="screen-reader-text" style="margin-left: 10px;">%2$s </span> <a class="url fn n" href="%3$s">%4$s</a></span></span><br/>',
                get_avatar(get_the_author_meta('user_email'), $author_avatar_size, get_site_url() . "/wp-content/uploads/2016/03/Mystery-Man.jpg", false, ["class" => "img-float"]),
                _x('Author', 'Used before post author name.', 'ssv'),
                esc_url(get_author_posts_url(get_the_author_meta('ID'))),
                get_the_author()
            );
        }

        if (in_array(get_post_type(), array('post', 'attachment'))) {
            ssv_entry_date();
        }

        $format = get_post_format();
        if (current_theme_supports('post-formats', $format)) {
            printf(
                '<span class="entry-format">%1$s<a href="%2$s">%3$s</a></span>',
                sprintf('<span class="screen-reader-text">%s </span>', _x('Format', 'Used before post format.', 'ssv')),
                esc_url(get_post_format_link($format)),
                get_post_format_string($format)
            );
        }

        if ('post' === get_post_type()) {
            ssv_entry_taxonomies();
        }
    }
endif;

if (!function_exists('ssv_entry_date')) :
    function ssv_entry_date()
    {
        $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

        if (get_the_time('U') !== get_the_modified_time('U')) {
            $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
        }

        $time_string = sprintf(
            $time_string,
            esc_attr(get_the_date('c')),
            get_the_date(),
            esc_attr(get_the_modified_date('c')),
            get_the_modified_date()
        );

        printf(
            '<span class="posted-on" style="margin-left: 10px;"><span class="screen-reader-text">%1$s </span><a href="%2$s" rel="bookmark">%3$s</a></span>',
            _x('Posted on', 'Used before publish date.', 'ssv'),
            esc_url(get_permalink()),
            $time_string
        );
    }
endif;

if (!function_exists('ssv_entry_taxonomies')) :
    function ssv_entry_taxonomies()
    {
        $categories_list = get_the_category_list(_x(', ', 'Used between list items, there is a space after the comma.', 'ssv'));
        if ($categories_list && ssv_categorized_blog()) {
            printf(
                '<span class="cat-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
                _x('Categories', 'Used before category names.', 'ssv'),
                $categories_list
            );
        }

        $tags_list = get_the_tag_list('', _x(', ', 'Used between list items, there is a space after the comma.', 'ssv'));
        if ($tags_list) {
            printf(
                '<span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
                _x('Tags', 'Used before tag names.', 'ssv'),
                $tags_list
            );
        }
    }
endif;

if (!function_exists('ssv_post_thumbnail')) :
    function ssv_post_thumbnail($without_link = false)
    {
        if (post_password_required() || is_attachment() || !has_post_thumbnail()) {
            return;
        }

        if (is_singular()) :
            ?>

            <div class="post-thumbnail">
                <?php echo get_the_post_thumbnail(get_the_ID(), 'ssv-banner-m'); ?>
            </div><!-- .post-thumbnail -->

        <?php else : ?>
            <?php if (!$without_link) { ?>
                <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true">
            <?php } ?>
            <?php the_post_thumbnail('post-thumbnail', array('alt' => the_title_attribute('echo=0'))); ?>
            <?php if (!$without_link) { ?>
                </a>
            <?php } ?>
        <?php endif; // End is_singular()
    }
endif;

if (!function_exists('ssv_excerpt')) :
    function ssv_excerpt($class = 'entry-summary')
    {
        $class = esc_attr($class);

        if (has_excerpt() || is_search()) : ?>
            <div class="<?php echo $class; ?>">
                <?php the_excerpt(); ?>
            </div><!-- .<?php echo $class; ?> -->
        <?php endif;
    }
endif;

if (!function_exists('ssv_excerpt_more') && !is_admin()) :
    function ssv_excerpt_more()
    {
        $link = sprintf(
            '<a href="%1$s" class="more-link">%2$s</a>',
            esc_url(get_permalink(get_the_ID())),
            /* translators: %s: Name of current post */
            sprintf(__('Continue reading<span class="screen-reader-text"> "%s"</span>', 'ssv'), get_the_title(get_the_ID()))
        );
        return ' &hellip; ' . $link;
    }

    add_filter('excerpt_more', 'ssv_excerpt_more');
endif;

function ssv_categorized_blog()
{
    if (false === ($all_the_cool_cats = get_transient('ssv_categories'))) {
        // Create an array of all the categories that are attached to posts.
        $all_the_cool_cats = get_categories(
            array(
                'fields' => 'ids',
                // We only need to know if there is more than one category.
                'number' => 2,
            )
        );

        // Count the number of categories that are attached to the posts.
        $all_the_cool_cats = count($all_the_cool_cats);

        set_transient('ssv_categories', $all_the_cool_cats);
    }

    if ($all_the_cool_cats > 1) {
        // This blog has more than 1 category so ssv_categorized_blog should return true.
        return true;
    } else {
        // This blog has only 1 category so ssv_categorized_blog should return false.
        return false;
    }
}

function ssv_category_transient_flusher()
{
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    // Like, beat it. Dig?
    delete_transient('ssv_categories');
}

add_action('edit_category', 'ssv_category_transient_flusher');
add_action('save_post', 'ssv_category_transient_flusher');
