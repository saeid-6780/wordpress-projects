<style>
    .img-url-container{width: 100%;direction: ltr;}
    .img-url-container p span{}
    .img-url-container .img-url-input{border: 1px solid #ccc;width: 95%;}
</style>

<p>
    <button class="add-to-slider">اضافه کردن به اسلایدر</button>
</p>
<div class="img-url-container">
    <?php if ( is_array($data['product_slider']) && count($data['product_slider'])>0 ){
        foreach ($data['product_slider'] as $img){ ?>
            <p>

                <input class="img-url-input" type="text" name="product_slider[]" value="<?= $img ?>">
                <span onclick="remove_url_input(this)" class=" dashicons dashicons-no-alt"></span>
            </p>
        <?php } }else {  ?>
        <p>
            <input class="img-url-input" type="text" name="product_slider[]" value="">
            <span onclick="remove_url_input(this)" class=" dashicons dashicons-no-alt"></span>
        </p>
    <?php } ?>
</div>
<script>
    var image_input_tag='<p><input class="img-url-input" type="text" name="product_slider[]" value=""><span onclick="remove_url_input(this)" class=" remove-url dashicons dashicons-no-alt"></span></p>';
    $('.add-to-slider').click(function (c) {
        c.preventDefault();
        $('.img-url-container').append(image_input_tag);
    });

    function remove_url_input(tag) {
        var input_var=$(tag).parents('p');
        input_var.remove();
    }
</script>