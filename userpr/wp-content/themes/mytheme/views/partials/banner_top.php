<div id="main-banner" class="main-banner banner text-center">
    <div class="container">
        <h1> <span class="segment-heading">یک فروشگاه الکترونیکی آزمایشی</span> </h1>
        <p><?php $options=theme_optionsPanel::get_options();
            echo $options['banner_text']?></p>
        <?php
        if (is_front_page()) {
            ?>
            <a id="select-cat-button" href="#select-cat">یکی از دسته ها را انتخاب کنید</a>
            <?php
        }else {
            ?>
            <a id="select-item-button" href="#parentVerticalTab">یکی از اقلام را انتخاب کنید</a>
            <?php
        }
        ?>
    </div>
</div>