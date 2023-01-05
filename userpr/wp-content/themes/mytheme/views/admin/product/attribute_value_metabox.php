
<Style>
    .attr-label{width: 150px;display: inline-block;margin: 10px 15px 5px 0}
    .attr-input{margin: 10px 0 5px 0}
</Style>
<div >
<?php
if($data['product_attributes_values']!=false){
?>

    <?php
    foreach ($data['product_attributes_values'] as $attrvaldata){
    ?>
    <label class="attr-label" for="attr-name-<?= $attrvaldata['id'] ?>"><?= $attrvaldata['name'];
        if ($attrvaldata['value']!=$attrvaldata['pure_value']){?><?php echo '('.$attrvaldata['value'].')'; }?></label>
    <input class="attr-input" id="attr-name-<?= $attrvaldata['id'] ?>" type="text" name="product_attr_val[]" <?php
    if (empty($attrvaldata['value']))
        echo 'value="" placeholder="مقدار را وارد کنید"';

    else
        echo 'value="'.$attrvaldata['pure_value'].'"';
    ?>>
    <input type="hidden" name="product_attr_id[]" value="<?= $attrvaldata['id'] ?>">

<?php } } ?>
</div>
