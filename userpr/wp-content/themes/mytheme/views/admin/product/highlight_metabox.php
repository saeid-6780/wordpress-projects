<style>
    .highlight-container{width: 100%;direction: rtl;}
    .highlight-container textarea{border: 1px solid #ccc;width: 99%}
</style>


<div class="highlight-container">
            <p>

                <textarea name="product_highlight">
                    <?php if (isset($data['product_highlight'])){echo $data['product_highlight'];} ?>
                </textarea>

            </p>

</div>
<!--<script>
    var input_tag='<p><input class="img-url-input" type="text" name="product_slider[]" value=""><span onclick="remove_url_input(this)" class=" remove-url dashicons dashicons-no-alt"></span></p>';
    $('.add-to-slider').click(function (c) {
        c.preventDefault();
        $('.img-url-container').append(input_tag);
    });

    function remove_url_input(tag) {
        var input_var=$(tag).parents('p');
        input_var.remove();
    }
</script>-->