<p><input type="text" name="product_price" <?php
    if ($data['product_price']>0)
        echo 'value="'.$data['product_price'].'"';
    else
        echo 'placeholder="0"'
        ?>>
    درصد تخفیف:<input type="number" name="product_dicount_percent" value="<?= $data['product_dicount_percent']; ?>">
</p>