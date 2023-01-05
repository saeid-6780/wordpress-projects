<style>
    div.options-input{margin: 20px 0;}
</style>
<div class="wrap">
    <h1>تنظیمات پوسته</h1>
</div>
<form action="" method="post">
    <div class="row options-input">
    <label for="help_section">آیا بخش راهنمای کاربر نمایش داده بشود؟</label>
    <input type="checkbox" name="help_section" <?php checked(1,$option['help_section']) ?>>
    </div>
    <div class="row options-input">
        <label for="banner_text">متن بنر صفحه اصلی</label>
        <input type="text" name="banner_text" value="<?php if (isset($option['banner_text'])) echo $option['banner_text']; ?>">
    </div>
    <div class="row options-input">
        <input type="submit" name="submit_theme_form" class="button button-primary" value="ذخیره تنظیمات">
    </div>
</form>

<?php
/*include get_home_path().'/smt2/core/functions.php';
echo get_client_id();
*/?>