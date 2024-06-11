<?php
/*
Template Name: Partner Single Page
*/
get_header();
$form = get_field('form_shortcode');
$description = get_field('description');
$key_feature = get_field('key_feature');
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
    .custom-theme-button {
        background-color: black !important;
        width: 94%;
        margin-left: 15px;
    }

</style>
    <main id="main" class="container-layout-content container">
        <section>
            <header class="page-header">
                <h1 class="page-title"><?php the_title(); ?></h1>
            </header>
            <!-- <div style="display: flex;"> -->
                <?php if(!empty($description)){?>
                <div class="page-content" style="">
                    <p><?php the_field('description');?></p>
                </div>
                <?php  } ?>

                <div style="">
                <?php
                    if ( has_post_thumbnail() ) {
                        the_post_thumbnail('full'); // 'full' can be changed to 'thumbnail', 'medium', 'large', etc. based on your needs
                    }
                    ?>

                </div>
            <!-- </div> -->
            <?php if(!empty($key_feature)){?>
            <div class="page-content" style="padding-right: 10px;">
                <p><?php the_field('key_feature');?></p>
            </div>
            <?php  } ?>
            <?php if(!empty($form)){?>
            <div style="background: #00A9A5; width: 60%; padding: 3rem; margin: auto; ">
                <?php echo do_shortcode('[simple_form]'); ?>
            </div>
            <?php  } ?>

        </section>
    </main>
<?php
get_footer();
?>
    