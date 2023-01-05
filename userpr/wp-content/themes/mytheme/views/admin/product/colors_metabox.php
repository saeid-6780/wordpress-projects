<style>
    .color-hexa-container{width: 100%;direction: ltr;}
    .color-hexa-container p span{}
    .color-hexa-container .color-hexa-input{border: 1px solid #ccc;width: 95%;float: right;}
</style>

<p>
    <button class="add-to-colors">اضافه کردن رنگ</button>
</p>
<div class="color-hexa-container">
    <?php if ( is_array($data['product_colors']) && count($data['product_colors'])>0 ){
        foreach ($data['product_colors'] as $color){ ?>
            <p>

                <input class="color-hexa-input" type="text" name="product_colors[]" value="<?= $color ?>">
                <span onclick="remove_url_input(this)" class=" dashicons dashicons-no-alt"></span>
            </p>
        <?php } }else {  ?>
        <p>
            <input class="color-hexa-input" type="text" name="product_colors[]" value="">
            <span onclick="remove_url_input(this)" class=" dashicons dashicons-no-alt"></span>
        </p>
    <?php } ?>
</div>
<script>
    var input_tag='<p><input class="color-hexa-input" type="text" name="product_colors[]" value=""><span onclick="remove_url_input(this)" class=" remove-url dashicons dashicons-no-alt"></span></p>';
    $('.add-to-colors').click(function (c) {
        c.preventDefault();
        $('.color-hexa-container').append(input_tag);
    });

    function remove_url_input(tag) {
        var input_var=$(tag).parents('p');
        input_var.remove();
    }
</script>