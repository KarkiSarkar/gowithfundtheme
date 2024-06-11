<?php
/*
Template Name: Career Archive Template
*/

    get_header(); 

    $page_id = krowd_id();
    $default_sidebar_config = krowd_get_option('single_post_sidebar', 'right-sidebar'); 
    $default_left_sidebar = krowd_get_option('single_post_left_sidebar', 'default_sidebar');
    $default_right_sidebar = krowd_get_option('single_post_right_sidebar', 'default_sidebar');

    $sidebar_layout_config = get_post_meta($page_id, 'krowd_sidebar_config', true);
    $left_sidebar = get_post_meta($page_id, 'krowd_left_sidebar', true);
    $right_sidebar = get_post_meta($page_id, 'krowd_right_sidebar', true);

    if ($sidebar_layout_config == "") {
        $sidebar_layout_config = $default_sidebar_config;
    }
    if ($left_sidebar == "") {
        $left_sidebar = $default_left_sidebar;
    }
    if ($right_sidebar == "") {
        $right_sidebar = $default_right_sidebar;
    }

   $left_sidebar_config  = array('active' => false);
   $right_sidebar_config = array('active' => false);
   $main_content_config  = array('class' => 'col-lg-12 col-xs-12');

    $sidebar_config = krowd_sidebar_global($sidebar_layout_config, $left_sidebar, $right_sidebar);
   
    extract($sidebar_config);

 ?>
<section id="wp-main-content" class="clearfix main-page">
    <?php do_action( 'krowd_before_page_content' ); ?>
   <div class="container"> 
    <?php
        // Custom query to fetch 'career' post type
        $args = array(
            'post_type' => 'career',
            'posts_per_page' => 10, // Adjust the number of posts per page as needed
            'paged' => ( get_query_var('paged') ) ? get_query_var('paged') : 1
        );
        $career_query = new WP_Query($args);

        if ($career_query->have_posts()) : ?>
            <div class="career-archive">
                <?php while ($career_query->have_posts()) : $career_query->the_post(); ?>
                <div class="gva-element-gva-career-block gva-element"  style="margin-bottom: 15px;">   
                    <div class="gsc-career">
                        <div class="image-box">
                            <?php if (has_post_thumbnail()) : ?>
                                <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('thumbnail'); ?></a>
                            <?php endif; ?>
                        </div>
                    
                        <div class="box-content clearfix">
                           
                            <div style="display:flex; justify-content: space-between;">
                            <div>
                                <h2 class="title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>            
                                </h2>
                            </div>
                            <style>
                                .apply_now_button{
                                    padding: 10px 20px; 
                                    background-color: #00A9A5;
                                    color: white;
                                    border: 1px solid #00A9A5;
                                }
                                .apply_now_button:hover{
                                    padding: 10px 20px; 
                                    background-color: #ffffff!important;
                                    color: #00A9A5;
                                }
                            </style>
                            <div>
                                <a href="<?php the_permalink(); ?>"><span class="apply_now_button">Apply Now</span></a>
                            </div>
                            </div>
                            <div class="box-information clearfix">
                                <ul>
                                    <li class="job-company">
                                        <i class="icon fa fa-suitcase"></i>
                                        <?php echo esc_html(get_post_meta(get_the_ID(), '_career_company', true)); ?>                 
                                    </li>
                                    <li class="job-address">
                                        <i class="icon fas fa-map-marker-alt"></i>
                                        <?php echo esc_html(get_post_meta(get_the_ID(), '_career_location', true)); ?>                  
                                    </li>
                                </ul>
                            </div>
                        </div>   
                    </div>
                </div>
                    
                <?php endwhile; ?>

                <!-- Pagination -->
                <div class="pagination">
                    <?php
                    echo paginate_links(array(
                        'total' => $career_query->max_num_pages,
                        'prev_text' => __('Previous', 'textdomain'),
                        'next_text' => __('Next', 'textdomain')
                    ));
                    ?>
                </div>
            </div>
        <?php else : ?>
            <p><?php _e('No careers found.', 'textdomain'); ?></p>
        <?php endif; ?>

        <?php wp_reset_postdata(); // Reset the post data to avoid conflicts ?>
    </div>
    
</section>

<?php get_footer(); ?>
