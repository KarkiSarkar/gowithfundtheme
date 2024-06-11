<?php
/**
 * $Desc
 *
 * @author     Gaviasthemes Team     
 * @copyright  Copyright (C) 2020 gaviasthemes. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 * 
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
    <div class="main-page-content row">
         <div class="content-page <?php echo esc_attr($main_content_config['class']); ?>">      
            <div id="wp-content" class="wp-content clearfix">
                <?php while ( have_posts() ) : the_post(); ?>

                    <?php get_template_part( 'content', get_post_format() ); ?>

                <?php endwhile; // end of the loop. ?>

                <?php 
                    if( comments_open() || get_comments_number() ) {
                        comments_template();
                    }
                ?>
                <?php krowd_post_nav(); ?>
            </div>    
         </div>      

         <!-- Left sidebar -->
         <?php if($left_sidebar_config['active']): ?>
         <div class="sidebar wp-sidebar sidebar-left <?php echo esc_attr($left_sidebar_config['class']); ?>">
            <?php do_action( 'krowd_before_sidebar' ); ?>
            <div class="sidebar-inner">
               <?php dynamic_sidebar($left_sidebar_config['name'] ); ?>
            </div>
            <?php do_action( 'krowd_after_sidebar' ); ?>
         </div>
         <?php endif ?>

         <!-- Right Sidebar -->
         <?php if($right_sidebar_config['active']): ?>
         <div class="sidebar wp-sidebar sidebar-right <?php echo esc_attr($right_sidebar_config['class']); ?>">
            <?php do_action( 'krowd_before_sidebar' ); ?>
               <div class="sidebar-inner">
                  <?php dynamic_sidebar($right_sidebar_config['name'] ); ?>
               </div>
            <?php do_action( 'krowd_after_sidebar' ); ?>
         </div>
         <?php endif ?>
      </div>   
    </div>
    <?php do_action( 'krowd_after_page_content' ); ?>
    <div class="gva-content-items container">
        <div class="lg-block-grid-3 md-block-grid-3 sm-block-grid-2 xs-block-grid-1 xx-block-grid-1">
            <h3 class="related_posts_title">Related Posts</h3>
            <?php 
                $related_post = get_posts( array(
                    'post_type' => 'post',
                    'posts_per_page' => 6,
                    'post__not_in' => array(),
                    'orderby' => 'rand'
                ));

                if($related_post){ 
                    foreach($related_post as $post){
                        setup_postdata($post);
                    ?>
                    <div class="all crowdfunding item-columns">
                        <article class="post post-style-1 type-post status-publish format-standard has-post-thumbnail hentry category-crowdfunding">
                            <div class="post-thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php echo get_the_post_thumbnail( $post->ID, 'large'); ?>
                                </a>   
                            </div>
                            <div class="entry-content">
                                <div class="content-inner">
                                    <div class="entry-meta">
                                        <div class="entry-date"><?php echo get_the_date(); ?></div>
                                        <div class="clearfix meta-inline">
                                            <span class="author vcard"><i class="far fa-user-circle"></i>by&nbsp;<?php the_author(); ?></span>
                                            <span class="post-comment"><i class="far fa-comments"></i><?php comments_number( '0 comments', '1 comment', '% comments' ); ?></span>
                                        </div>            
                                    </div> 
                                    <h2 class="entry-title">
                                        <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
                                    </h2>
                                    <div class="read-more">
                                        <a href="<?php the_permalink(); ?>">
                                            <i class="icon fi flaticon-left-arrow"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
            <?php  
                }
                wp_reset_postdata();
                }
            ?>

        </div>
    </div>
</section>

<?php get_footer(); ?>
