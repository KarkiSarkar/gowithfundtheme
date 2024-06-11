<?php

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
 <style>
    .page-title{
        padding-top: 3rem;
    }
    input[type="text"], input[type="tel"], input[type="password"], input[type="email"], textarea, select {
    background-color: #fff;
    -webkit-box-shadow: 0 0 2px 2px rgba(0, 0, 0, 0.02) inset;
    box-shadow: 0 0 2px 2px rgba(0, 0, 0, 0.02) inset;
    border: 1px solid #E9E9EE;
    padding: 5px 10px;
    max-width: 100%;
    border-radius: 0;
    width: 100%;
}
</style>
<section id="wp-main-content" class="clearfix main-page">
    <?php do_action( 'krowd_before_page_content' ); ?>
   <div class="container">  
    <div class="main-page-content row">
         <div class="content-page <?php echo esc_attr($main_content_config['class']); ?>">      
            <div id="wp-content" class="wp-content clearfix">
            <div class="entry-header">
        <?php if ( has_post_thumbnail() ) : ?>
            <a href="<?php the_permalink(); ?>">
                <?php echo get_the_post_thumbnail( $post->ID, 'medium' ); ?>
            </a>
        <?php endif; ?>

       
        <div>
            <strong>Job Title:</strong> <?php echo esc_html( the_title()); ?>
        </div>
        <div>
            <strong>Company Name:</strong> <?php echo esc_html( get_post_meta( get_the_ID(), '_career_company', true ) ); ?>
        </div>
        <div>
            <strong>Location:</strong> <?php echo esc_html( get_post_meta( get_the_ID(), '_career_location', true ) ); ?>
        </div>
        
    </div><!-- .entry-header -->

    <div class="entry-content">
        <?php the_content(); ?>
    </div><!-- .entry-content -->
    <div style="border: 1px solid #00A9A5; padding: 20px; background: #00A9A5; color: white; margin: 20px 0px;">
        <?php //echo do_shortcode('[simple_form]'); ?>








<!-- Custom Start -->

<?php


// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Handle form submission
    if (handle_form_submission()) {
        echo '<p>Thank you for your application!</p>';
    } else {
        echo '<p>There was an error processing your application. Please try again later.</p>';
    }
}

// Start the WordPress loop

    ?>
   
                    <form id="career-form" method="post" action="" enctype="multipart/form-data">
                    <?php the_title('<h1 class="entry-title" style="color: white;">Apply for ', '</h1>'); ?>
                        <p>
                            <label for="name">Name:</label>
                            <input type="text" id="name" name="name" required>
                        </p>
                        <p>
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" required>
                        </p>
                        <p>
                            <label for="cover-letter">Cover Letter:</label>
                            <textarea id="cover-letter" name="cover_letter" required></textarea>
                        </p>
                        <p>
                            <label for="file">Resume (PDF only):</label>
                            <input type="file" id="file" name="file" accept=".pdf" required>
                        </p>
                        <p>
                            <input type="submit" name="submit" style="background: white; color: black; font-size: 18px; font-weight: 600;" value="Apply">
                        </p>
                    </form>
                    <script>
                         document.addEventListener('DOMContentLoaded', function() {
                                var form = document.getElementById('career-form');
                                form.addEventListener('submit', function(event) {
                                    var name = document.getElementById('name').value;
                                    var email = document.getElementById('email').value;
                                    var coverLetter = document.getElementById('cover-letter').value;
                                    var file = document.getElementById('file').value;

                                    var errors = [];

                                    if (!name) {
                                        errors.push("Please enter your name.");
                                    }
                                    if (!email) {
                                        errors.push("Please enter your email address.");
                                    }
                                    if (!coverLetter) {
                                        errors.push("Please enter your cover letter.");
                                    }
                                    if (!file) {
                                        errors.push("Please upload your resume.");
                                    }

                                    if (errors.length > 0) {
                                        event.preventDefault(); // Prevent form submission
                                        alert(errors.join("\n")); // Display error messages
                                    }
                                    
                                });
                            });
                    </script>
                
<?php


// Handle form submission function
function handle_form_submission() {
    if (isset($_POST['submit'])) {
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $cover_letter = sanitize_textarea_field($_POST['cover_letter']);
        $file = isset($_FILES['file']) ? $_FILES['file'] : null;

        // Recipient email address
        $recipient_email = $email; // Replace with your recipient email
        global $post;
        $post_name = get_the_title($post->ID);
        // Email subject
        $email_subject = 'New Career Application for '.$post_name;
        

        // Email message
        $email_message = "Name: $name\n";
        $email_message .= "Email: $email\n";
        $email_message .= "Cover Letter:\n$cover_letter\n";

        // Handle file upload
        $attachments = array();
        if ($file) {
            $upload_dir = wp_upload_dir();
            $file_name = basename($file['name']);
            $file_path = $upload_dir['path'] . '/' . $file_name;
            if (move_uploaded_file($file['tmp_name'], $file_path)) {
                $attachments[] = $file_path;
            }
        }

        // Send email
        $success = wp_mail($recipient_email, $email_subject, $email_message, array(), $attachments);

        // Remove uploaded file if it exists
        if ($file && file_exists($file_path)) {
            unlink($file_path);
        }

        return $success;
    }
    return false;
}
?>


<!-- Custom end -->

















    </div>
    <div class="entry-footer">
        
        
    </div><!-- .entry-footer -->
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
    
    
</section>

<?php get_footer(); ?>
