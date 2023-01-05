<div class="header remodal-bg">
    <div class="container">
        <div class="logo">
            <a href="<?= get_home_url() ?>"><span>سیستم ثبت </span>علاقه‌مندی‌های کاربران</a>
        </div>
        <div class="header-right">
            <a data-remodal-target="guide-modal" class="site-guide btn btn-info">راهنما</a>
            <?php
            if (!is_user_logged_in()){
            ?>
            <a href="<?php echo get_home_url().DIRECTORY_SEPARATOR.'registration' ?>" class="site-guide btn btn-info">ثبت نام</a>
            <?php } ?>
        </div>
    </div>

    <?php
    if (isset($_POST['product_id'])) {
        if (isset($_POST['basket_quantity'])) {
            $quantity = intval($_POST['basket_quantity']);
            //product_basket::add($_POST['product_id'],$quantity);
            $tr_error=apply_filters('add_to_basket', $_POST['product_id'], $quantity);
        }
        //product_basket::add($_POST['product_id'],$quantity);
        else {
            $tr_error=apply_filters('add_to_basket', $_POST['product_id']);
        }
        //var_dump($_SESSION['basket']);
        if (!$tr_error){
            $fullBasketError['error']=1;
            wp_localize_script('sessionhandler','fullBasketError',$fullBasketError);

        }
    }

    if(isset($_SESSION['basket']['items'])&& count($_SESSION['basket']['items'])>0) {
        //var_dump($_SESSION['basket']);
        get_template_part('views/partials/basket');
    }
    ?>



    <div class="remodal guide-modal" data-remodal-id="guide-modal" data-remodal-options="closeOnOutsideClick: true">
        <button data-remodal-action="close" class="remodal-close"></button>
        <h2>راهنمای سایت</h2>
        <h4>حتما مطالعه کنید</h4>
        <p>این وبسایت، یک فروشگاه الکترونیکی آزمایشی است و برای اهداف پژوهشی طراحی شده است. در این وبسایت <span class="red">هیچ‌گونه تراکنش مالی</span> رخ نمی‌دهد، و خرید محصول به شکل <span class="red">نمادین</span> و در راستای اهداف پژوهشی انجام می‌گیرد.</p>
        <br>
        <p>هدف این پژوهش بررسی رفتارهای کاربران در صفحات وب و استخراج یک مدل رفتاری برای تخمین ترجیحات کاربران با استفاده از الگوریتم‌های یادگیری ماشین است.</p>
        <br>
        <p>خواهشمندیم با هدف کمک به اجرای این پژوهش، چند دقیقه از وقتتان را در وبسایت بگذرانید (برای شرکت در قرعه کشی، حدود 10 دقیقه فعالیت مفید کافیست) تا مجموعه داده‌ای مطلوب گردآوری شود. برای این منظور به موارد زیر توجه فرمایید:</p>
        <br>
        <p>1- لطفا برای فعالیت در سایت <span class="red">از کامپیوتر و لپتاپ استفاده نمایید</span>. <span class="red">فعالیت در سایت به وسیله گوشی‌های هوشمند و تبلت‌ها فاقد ارزش خواهد بود</span>.</p>
        <br>
        <p>2- <span class="red">تنها رفتار ضروری</span> که باید در گشت و گذار خود انجام دهید، <span class="red">ثبت میزان علاقه مندی خود به هر محصول</span> است. برای این کار لطفا، به هر یک از اقلامی که به مدتی بیش از 8 ثانیه در صفحه آن فعالیت می‌کنید، یک نمره از 1 تا 5 اختصاص دهید که بیانگر علاقه یا عدم علاقه شما به آن محصول است. </p>
        <br>
        <p>3- برای اقلام مختلف، محتوا، نقد و بررسی و نظرات سایت دیجی کالا قرار داده شده است که می‌توانید بر اساس ترجیحتان از آنها بازدید نمایید.</p>
        <br>
        <p>4- لطفا از بخش <span class="red">دیدگاه‌های</span> مربوط به محصولات مد نظرتان نیز بازدید کنید و در صورت تمایل دیدگاه یا بررسی و نقد خود را نیز ثبت کنید (برای این کار لازم است مشخص کنید که نظرتان راجع به کدام یک از جنبه‌های مربوط به محصول است.)</p>
        <br>
        <p>5- این امکان برای شما فراهم است که به صورت نمادین، اقلامی را که بیشتر به آن علاقه‌مند هستید، در سبد خرید قرار دهید. توجه کنید که ظرفیت این سبد خرید محدود است و نمی‌توانید تمامی اقلام را در آن قرار دهید.</p>
        <br>

        <!--<button data-remodal-action="cancel" class="remodal-cancel">Cancel</button>
        <button data-remodal-action="confirm" class="remodal-confirm">OK</button>-->
    </div>


<!--this-->
    <div class="remodal email-collection-modal" data-remodal-id="email-collection-modal">
        <button data-remodal-action="close" class="remodal-close"></button>
        <h3>ثبت ایمیل</h3>
        <h4 style="display: none;">ایمیل شما با موفقیت ثبت شد. حتما توضیحات زیر را مطالعه بفرمایید:</h4>
        <h5>جهت شرکت در قرعه کشی</h5>
        <p>

            <p class="email-collection-desc">می‌توانید با ثبت مشخصات خود در این قسمت در قرعه کشی این طرح پژوهشی شرکت نمایید. این اطلاعات صرفا جهت دسترسی‌های بعدی به شما ثبت می‌شود و کاربرد دیگری ندارد. توجه داشته باشید که ایمیل خود را با دقت در کادر مربوطه وارد کنید، در غیر این صورت مسئولیت عدم اطلاع رسانی به شما در مورد قرعه کشی بر عهده شخص خودتان می‌باشد. جزئیات مربوط به قرعه کشی صرفا از طریق ایمیل به اطلاع افرادی که در این قسمت مشخصات خود را ثبت می‌کنند، خواهد رسید و در سایت منتشر نخواهد شد.</p>
            <?php
            echo do_shortcode('[gravityform id="1" title="true" description="true" ajax="true"]')
            ?>
        </p>
        <br>

        <button style="display: none" data-remodal-action="confirm" class="remodal-confirm">تایید</button>
    </div>

</div>

<style>

    form.email-collection-form{text-align: center!important;}
    form.email-collection-form h3.gform_title,span.gform_description{display: none!important;}
    form.email-collection-form span.name_first{margin-right: 0;padding-right: 0;}
    form.email-collection-form span.name_first label{display: none!important;}
    form.email-collection-form li.gfield.field_sublabel_above.field_description_below.gfield_visibility_visible{padding-right: 0;margin-top:0}
    form.email-collection-form li.gfield.gfield_contains_required.field_sublabel_below.field_description_below {padding-right: 0;margin-top:0}
    form.email-collection-form div.ginput_complex.ginput_container.no_prefix.has_first_name{width: 40%;margin: 0 auto;}
    form.email-collection-form div.ginput_container.ginput_container_email input.medium{width: 40%;margin: 0 auto;}
    form.email-collection-form li.gfield.gfield_contains_required.field_sublabel_below.field_description_below {margin-top: 0;}
    form.email-collection-form li.gfield.gfield_contains_required.field_sublabel_below.field_description_below label{display: none;}
    form.email-collection-form input{box-shadow: 1px 1px 2px rgba(0,0,0,0.4)}
    form.email-collection-form input[type:'text']:hover{border: #01a185 2px solid }
    form.email-collection-form input.gform_button.button{margin-right: 0;border: #f3c500 solid 2px;color: #fff;background: #f3c500;}
    form.email-collection-form input.gform_button.button:hover{border: #01a185 solid 2px;background:#01a185}
    form.email-collection-form div.validation_error{border-top: 2px solid #AF1D0D;border-bottom: 2px solid #AF1D0D;color: #AF1D0D;}
    .gform_confirmation_message_1.gform_confirmation_message{line-height: 30px;}
    .gfield_description.validation_message{color: #AF1D0D;}


</style>
<!--
قرعه کشی
partials>header.php+اضافه کردن یک پرانتز به مودال راهنما
assets>js>mousetrcscripts.js
functions.php
app>classes>general>lottery.php===addd
column email_set on table smt2_usersessions
app>classes>general>smtdb.php
partials>single_top.php
assets>css>style.css
assets>css>remodal-default-theme.css

-->

<!--
استخراج سایز المانها

assets>js>mousetrcscripts.js
partials>single_top.php
functions.php
create table elementsrange to smt2 db
smtdb
app>classes>alanysis>elementsPosition.php===addd
app>classes>general>smtdb.php> add functions->search_element_position_range va insert_element_position_range
-->
<script type="text/javascript">

</script>