<?php
/*
Template Name: Thank You Page
*/
get_header();
?>


<style>
    .buttonhovercss > a{
            padding: 10px 30px 10px 30px;
            border-radius: 20px;
            background: #00A9A5!important;
    }
</style>
<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <section class="thank-you">
            <header class="page-header">
                <h1 class="page-title" style="text-align: center; font-size: 60px; color: #00A9A5; padding-top: 3rem;"><?php esc_html_e('Thank You', 'krowd'); ?></h1>
            </header>
            <div class="page-content">
                <p style="text-align: center;"><?php esc_html_e('Your request on becoming a partner has been submitted. We will get back to you soon.', 'krowd'); ?></p>
                <div class="buttonhovercss" style="text-align: center; margin: 30px 10px 30px 10px; ">
                    <a href="<?php echo home_url('/become-a-partner'); ?>" style="color: white;"><i class="far fa-arrow-alt-circle-left"></i>Return Back</a>
                </div>
            </div>

        </section>
    </main>
</div>

<?php
get_footer();
?>
        
<script>
// JavaScript to redirect to home page when the Thank You page is refreshed
window.onload = function() {
    if(performance.navigation.type == 1) {
        window.location.href = '<?php echo esc_url( home_url( '/become-a-partner' ) ); ?>';
    }
}
</script>