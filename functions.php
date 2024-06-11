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

?>