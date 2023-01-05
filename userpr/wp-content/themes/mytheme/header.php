<!DOCTYPE html>
<html lang="en">
<head>
    <!--<script type="text/javascript" src="http://localhost:8080/mousetrc/smt2/core/js/smt2e.min.js"></script>
    <script type="text/javascript">
        try {
            smt2.record();
        } catch(err) {}
    </script>-->

    <script src="<?= get_home_url() ?>/evtrack-master/js/load.min.js"></script>
    <script>
        (function(){

            // Please read js/src/trackui.js for the API and code documentation.
            TrackUI.record({
                //debug: true,

                // Example: track all clicks and poll mouse movements at 50 ms
                //regularEvents: "click",


                // Example: pure event polling at 500 ms
                //regularEvents: "",
                //pollingEvents: "*",
                //pollingMs: 500,

                postServer: "<?= get_home_url() ?>/evtrack-master/save.php",
                regularEvents: "click click mousewheel keypress load unload scroll cut copy paste",
                pollingEvents: "mousemove",
                pollingMs: 50,
            });

        })();
    </script>

    <meta charset="UTF-8">
    <!--<script src="https://www.google.com/recaptcha/api.js?hl=fa"></script>-->
    <?php
    wp_head();
    ?>

    <link rel="stylesheet" href="<?= theme_asset::css('bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= theme_asset::css('bootstrap-select.css') ?>">
    <link rel="stylesheet" href="<?= theme_asset::css('style.css') ?>" type="text/css" media="all" />
    <link rel="stylesheet" href="<?= theme_asset::css('font-awesome.min.css') ?>" />

    <link rel="stylesheet" href="<?= theme_asset::css('remodal.css') ?>">
    <link rel="stylesheet" href="<?= theme_asset::css('remodal-default-theme.css') ?>">

    <!--<script>
        window.REMODAL_GLOBALS = {
            NAMESPACE: 'modal',
            DEFAULTS: {
                hashTracking: false
            }
        };
    </script>
    <script src="<?/*= theme_asset::js('remodal.min.js') */?>"></script>-->
    <script src="<?= theme_asset::js('jquery.min.js') ?>"></script>
    <!--<script src="<?/*= theme_asset::js('jquery.exitintent.min.js') */?>"></script>-->
    <script>
        window.REMODAL_GLOBALS = {
            NAMESPACE: 'remodal',
            DEFAULTS: {
                hashTracking: false,
                closeOnEscape:false,
                closeOnOutsideClick:false
            }
        };


    </script>
    <?php
    wp_register_script('exitintent',THEME_URL.'/assets/js/jquery.exitintent.min.js','jquery',false,false);
    wp_enqueue_script('exitintent');

    wp_register_script('modaljs',THEME_URL.'/assets/js/remodal.min.js','jquery',false,false);
    wp_enqueue_script('modaljs');

    isset($_SESSION['basket']['items'])?$basket_exist=1:$basket_exist=0;


        wp_register_script('sessionhandler', THEME_URL . '/assets/js/sessionhandler.js', 'exitintent', false, false);
        wp_enqueue_script('sessionhandler');

        $ajaxsessiondata = [];
        $ajaxsessiondata['ajax_url'] = admin_url() . 'admin-ajax.php';
        wp_localize_script('sessionhandler', 'ajaxdata', $ajaxsessiondata);


    if (is_single()){
        ?>

        <!--css-->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>


        <!--<script src="js/main.js"></script>-->
        <script type="text/javascript" src="<?= theme_asset::js('bootstrap.min.js') ?>"></script>
        <!--<script src="js/simpleCart.min.js"></script>-->
        <!--<script defer src="<?/*= theme_asset::js('jquery.flexslider.js') */?>"></script>-->
    <?php
    wp_register_script('flexsliderjs',THEME_URL.'/assets/js/jquery.flexslider.js','jquery',false,false);
    wp_enqueue_script('flexsliderjs');
    ?>
        <link rel="stylesheet" href="<?= theme_asset::css('flexslider.css') ?>" type="text/css" media="screen" />
        <script src="<?= theme_asset::js('imagezoom.js') ?>"></script>
        <script>
            $(window).load(function() {
                $('.flexslider').flexslider({
                    animation: "slide",
                    controlNav: "thumbnails"
                });
            });
        </script>

        <script src="<?= theme_asset::js('jstarbox.js') ?>"></script>
        <link rel="stylesheet" href="<?= theme_asset::css('jstarbox.css') ?>" type="text/css" media="screen" charset="utf-8" />
        <script type='text/javascript'>
    var likedata={"ajax_url":"http:\/\/localhost:8080\/mousetrc\/wp-admin\/admin-ajax.php"};
        </script>


    <?php
    }
    if (is_archive()){
    ?>
        <link rel="stylesheet" type="text/css" href="<?= theme_asset::css('jquery-ui.css') ?>">

        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>


        <script type="text/javascript" src="<?= theme_asset::js('jquery.min.js')?>"></script>


        <script src="<?= theme_asset::js('bootstrap.min.js')?>"></script>
        <script src="<?= theme_asset::js('bootstrap-select.js')?>"></script>
        <!--<script src="js/main.js"></script>-->
        <script type="text/javascript" src="<?= theme_asset::js('jquery-ui.min.js')?>"></script>
        <script>
            $(document).ready(function () {
                var mySelect = $('#first-disabled2');

                $('#special').on('click', function () {
                    mySelect.find('option:selected').prop('disabled', true);
                    mySelect.selectpicker('refresh');
                });

                $('#special2').on('click', function () {
                    mySelect.find('option:disabled').prop('disabled', false);
                    mySelect.selectpicker('refresh');
                });

                $('#basic2').selectpicker({
                    liveSearch: true,
                    maxOptions: 1
                });
            });
        </script>

        <script src="<?= theme_asset::js('jquery.uls.core.js')?>"></script>

        <link rel="stylesheet" type="text/css" href="<?= theme_asset::css('easy-responsive-tabs.css') ?>" />
        <script src="<?= theme_asset::js('easyResponsiveTabs.js')?>"></script>
        <?php
    }

    ?>
</head>
<body>
<div class="desktop-allarm">
    <h3>لطفا توجه کنید</h3>
    <p>فعالیت در این وبسایت به وسیله گوشی همراه و یا تبلت فاقد ارزش است. لطفا با استفاده از کامپیوتر به فعالیت در آن بپردازید.</p>
</div>
<div class="userpr-content">

