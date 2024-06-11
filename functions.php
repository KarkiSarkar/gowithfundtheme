<?php
// Your code to enqueue parent theme styles
function enqueue_parent_styles() {
   wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}

add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles' );
add_action( 'wp_enqueue_scripts', 'grand_sunrise_enqueue_styles' );

function grand_sunrise_enqueue_styles() {
    $style_version = filemtime( get_stylesheet_directory() . '/style.css' );
    wp_enqueue_style( 
        'grand-sunrise-style', 
        get_stylesheet_uri(),
        array(),
        $style_version
    );
}
add_action( 'wp_enqueue_scripts', 'grand_sunrise_enqueue_styles' );


// Add page title as body class
function add_page_title_as_body_class($classes) {
    // Get the page title
    $page_title = get_the_title();
    
    // Sanitize the page title to make it suitable as a class name
    $sanitized_page_title = sanitize_title($page_title);
    
    // Add the sanitized page title as a class
    $classes[] = $sanitized_page_title;

    return $classes;
}
add_filter('body_class', 'add_page_title_as_body_class');
// Assign "subscriber" role to new users
function assign_subscriber_role_on_registration($user_id) {
    $user = new WP_User($user_id);
    $user->set_role('campaign_creator');
}
add_action('user_register', 'assign_subscriber_role_on_registration', 10, 1);
// function restrict_campaign_creators_from_admin_panel() {
//     if (current_user_can('campaign_creator')) {
//         wp_redirect(home_url());
//         exit;
//     }
// }
// add_action('admin_init', 'restrict_campaign_creators_from_admin_panel');

// function modify_curl_headers( $handle ) {
//     curl_setopt( $handle, CURLOPT_HTTPHEADER, array(
//         'Access-Control-Allow-Origin: https://js.stripe.com'
//     ) );
//     return $handle;
// }
// add_filter( 'http_api_curl', 'modify_curl_headers' );


// Add CSS to hide admin bar for users with 'campaign_creator' role
function hide_admin_bar_css() {
    if (current_user_can('campaign_creator')) {
        echo '<style>
            #wpadminbar {
                display:none;
            }
            html {
                margin-top: 0!important;
            }
        </style>';
    }
}
add_action('admin_head', 'hide_admin_bar_css');
add_action('wp_head', 'hide_admin_bar_css');


// Register Success Story Post Type
function create_success_story_post_type() {
    $labels = array(
        'name'               => _x( 'Success Stories', 'post type general name', 'textdomain' ),
        'singular_name'      => _x( 'Success Story', 'post type singular name', 'textdomain' ),
        'menu_name'          => _x( 'Success Stories', 'admin menu', 'textdomain' ),
        'name_admin_bar'     => _x( 'Success Story', 'add new on admin bar', 'textdomain' ),
        'add_new'            => _x( 'Add New', 'success story', 'textdomain' ),
        'add_new_item'       => __( 'Add New Success Story', 'textdomain' ),
        'new_item'           => __( 'New Success Story', 'textdomain' ),
        'edit_item'          => __( 'Edit Success Story', 'textdomain' ),
        'view_item'          => __( 'View Success Story', 'textdomain' ),
        'all_items'          => __( 'All Success Stories', 'textdomain' ),
        'search_items'       => __( 'Search Success Stories', 'textdomain' ),
        'parent_item_colon'  => __( 'Parent Success Stories:', 'textdomain' ),
        'not_found'          => __( 'No success stories found.', 'textdomain' ),
        'not_found_in_trash' => __( 'No success stories found in Trash.', 'textdomain' )
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __( 'Description.', 'textdomain' ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'success-story' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
        'taxonomies'         => array( 'success_story_category' ) // Add support for categories
    );

    register_post_type( 'success_story', $args );

    // Register Success Story Category Taxonomy
    register_taxonomy(
        'success_story_category',
        'success_story',
        array(
            'label' => __( 'Categories', 'textdomain' ),
            'rewrite' => array( 'slug' => 'success-story-category' ),
            'hierarchical' => true,
        )
    );
}
add_action( 'init', 'create_success_story_post_type' );

add_action( 'admin_init', function () {
    // Check the current user role.
    if ( current_user_can( 'contributor' ) || current_user_can( 'author' ) ) {
        remove_menu_page( 'edit.php?post_type=gva_header' );
        remove_menu_page( 'edit.php?post_type=tribe_events' );
        remove_menu_page( 'edit.php?post_type=footer' );
        remove_menu_page( 'edit.php?post_type=gallery' );
        remove_menu_page( 'edit.php?post_type=portfolio' );
        remove_menu_page( 'edit.php?post_type=gva_team' );
        remove_menu_page( 'edit.php?post_type=service' ); 
        remove_menu_page( 'edit.php?post_type=success_story' );
        remove_menu_page( 'edit.php?post_type=elementor_library' );
        remove_menu_page( 'edit-comments.php' );
        remove_menu_page( 'tools.php' );
        remove_menu_page( 'wpcf7' );
        add_filter('jetpack_just_in_time_msgs', '__return_false');
        add_filter('jetpack_admin_menu', '__return_false');
    }
});

function change_role_name() {
    global $wp_roles;

    if ( ! isset( $wp_roles ) )
        $wp_roles = new WP_Roles();
    $wp_roles->roles['contributor']['name'] = 'Content Writer';
    $wp_roles->role_names['contributor'] = 'Content Writer';           
}
add_action('init', 'change_role_name');


function create_career_post_type() {
    $labels = array(
        'name'                  => _x( 'Careers', 'Post type general name', 'textdomain' ),
        'singular_name'         => _x( 'Career', 'Post type singular name', 'textdomain' ),
        'menu_name'             => _x( 'Careers', 'Admin Menu text', 'textdomain' ),
        'name_admin_bar'        => _x( 'Career', 'Add New on Toolbar', 'textdomain' ),
        'add_new'               => __( 'Add New', 'textdomain' ),
        'add_new_item'          => __( 'Add New Career', 'textdomain' ),
        'new_item'              => __( 'New Career', 'textdomain' ),
        'edit_item'             => __( 'Edit Career', 'textdomain' ),
        'view_item'             => __( 'View Career', 'textdomain' ),
        'all_items'             => __( 'All Careers', 'textdomain' ),
        'search_items'          => __( 'Search Careers', 'textdomain' ),
        'parent_item_colon'     => __( 'Parent Careers:', 'textdomain' ),
        'not_found'             => __( 'No careers found.', 'textdomain' ),
        'not_found_in_trash'    => __( 'No careers found in Trash.', 'textdomain' ),
        'featured_image'        => _x( 'Career Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'textdomain' ),
        'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
        'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
        'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'textdomain' ),
        'archives'              => _x( 'Career archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'textdomain' ),
        'insert_into_item'      => _x( 'Insert into career', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'textdomain' ),
        'uploaded_to_this_item' => _x( 'Uploaded to this career', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'textdomain' ),
        'filter_items_list'     => _x( 'Filter careers list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'textdomain' ),
        'items_list_navigation' => _x( 'Careers list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'textdomain' ),
        'items_list'            => _x( 'Careers list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'textdomain' ),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'career' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
        'taxonomies'         => array( 'category' ),
    );

    register_post_type( 'career', $args );
}

add_action( 'init', 'create_career_post_type' );

function add_career_meta_boxes() {
    add_meta_box(
        'career_location',
        'Location',
        'career_location_callback',
        'career',
        'normal',
        'high'
    );
    add_meta_box(
        'career_company',
        'Company Name',
        'career_company_callback',
        'career',
        'normal',
        'high'
    );
}

add_action( 'add_meta_boxes', 'add_career_meta_boxes' );

function career_location_callback( $post ) {
    $value = get_post_meta( $post->ID, '_career_location', true );
    echo '<label for="career_location">Location: </label>';
    echo '<input type="text" id="career_location" name="career_location" value="' . esc_attr( $value ) . '" size="25" />';
}

function career_company_callback( $post ) {
    $value = get_post_meta( $post->ID, '_career_company', true );
    echo '<label for="career_company">Company Name: </label>';
    echo '<input type="text" id="career_company" name="career_company" value="' . esc_attr( $value ) . '" size="25" />';
}

function save_career_meta_boxes_data( $post_id ) {
    if ( ! isset( $_POST['career_location'] ) || ! isset( $_POST['career_company'] ) ) {
        return;
    }

    $location = sanitize_text_field( $_POST['career_location'] );
    $company = sanitize_text_field( $_POST['career_company'] );

    update_post_meta( $post_id, '_career_location', $location );
    update_post_meta( $post_id, '_career_company', $company );
}

add_action( 'save_post', 'save_career_meta_boxes_data' );



// Register the widget
class Recent_Posts_With_Images_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'recent_posts_with_images_widget',
            __('Recent Posts with Images', 'text_domain'),
            array('description' => __('A Widget to display recent posts with images, titles, and dates', 'text_domain'))
        );
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }

        $query_args = array(
            'post_type' => 'post',
            'posts_per_page' => 5,
            'post_status' => 'publish'
        );
        $recent_posts = new WP_Query($query_args);
        if ($recent_posts->have_posts()) {
            echo '<div class="recent-posts-widget">';
            while ($recent_posts->have_posts()) : $recent_posts->the_post();
                ?>
                <div class="post">
                    <?php if (has_post_thumbnail()) : ?>
                        <img src="<?php the_post_thumbnail_url('large'); ?>" class="post-image" alt="<?php the_title(); ?>">
                    <?php endif; ?>
                    <div class="post-details">
                        <h6 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>
                        <p class="post-date"><?php echo get_the_date(); ?></p>
                    </div>
                </div>
                <?php
            endwhile;
            echo '</div>';
            wp_reset_postdata();
        }

        echo $args['after_widget'];
    }

    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Recent Posts', 'text_domain');
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_attr_e('Title:', 'text_domain'); ?></label> 
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <?php 
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        return $instance;
    }
}

function register_recent_posts_with_images_widget() {
    register_widget('Recent_Posts_With_Images_Widget');
}
add_action('widgets_init', 'register_recent_posts_with_images_widget');


?>